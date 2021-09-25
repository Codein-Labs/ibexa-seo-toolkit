<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\URLAliasService;

/**
 * Class KeywordInUrlSlugAnalyzer.
 *
 * Look for keyword in URL Slug
 *
 * This could be implemented as a richtext analyzer as well as it doesn't need access to content specifically
 */
final class KeywordInUrlSlugAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    private $analyzerService;
    private $urlAliasService;
    private $locationService;

    public function __construct(
        AnalyzerService $analyzerService,
        URLAliasService $urlAliasService,
        LocationService $locationService
    ) {
        $this->analyzerService = $analyzerService;
        $this->urlAliasService = $urlAliasService;
        $this->locationService = $locationService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $locationId = $analysisDTO->getLocationId();

        try {
            $location = $this->locationService->loadLocation($locationId);

            /** @var \eZ\Publish\API\Repository\Values\Content\URLAlias $urlAlias */
            $urlAlias = $this->urlAliasService->reverseLookup($location);

            $pathArray = \explode('/', $urlAlias->path);
            $urlSlug = \mb_strtolower(\end($pathArray));
            $urlSlugWithoutDashes = \str_replace('-', ' ', $urlSlug);
            $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($analysisDTO->getKeyword()), AnalyzerService::ACCENT_VALUES));
            $keywordSynonyms = \array_map('trim', $keywordSynonyms);

            $bestRatio = 0;
            foreach ($keywordSynonyms as $keyword) {
                $distance = AnalyzerService::levenshtein_utf8($keyword, $urlSlugWithoutDashes);
                $lenSum = \mb_strlen($urlSlugWithoutDashes) + \mb_strlen($keyword);
                $levenshteinRatio = 1 - ($distance / $lenSum);
                $bestRatio = ($levenshteinRatio > $bestRatio ? $levenshteinRatio : $bestRatio);
            }

            $status = RatioLevels::LOW;

            if ($bestRatio > 0.85 && $bestRatio < 1) {
                $status = RatioLevels::LOW;
            } elseif (1 === $bestRatio) {
                $status = RatioLevels::HIGH;
            }

            // case keyword is included, but far from equal
            if (RatioLevels::LOW === $status && false !== \mb_strpos($urlSlugWithoutDashes, $keyword)) {
                $status = RatioLevels::MEDIUM;
            }
        } catch (\Exception $e) {
            return $this->analyzerService->compile(self::CATEGORY, null, null);
        }

        return $this->analyzerService->compile(self::CATEGORY, $status, [
            'similarity' => $bestRatio * 100,
        ]);
    }

    public function support(AnalysisDTO $analysisDTO): bool
    {
        // Difficult to get non latin alphabet languages
        // to work well with this analyzer.
        // Moreover, we don't know how Search Engines treats them

        if (0 === \count($analysisDTO->getFields())) {
            return false;
        }

        return !\in_array($analysisDTO->getLanguageCode(), [
            'ara-SA',
            'chi-CN',
            'chi-HK',
            'chi-TW',
            'cze-CZ',
            'ell-GR',
            'heb-IL',
            'hin-IN',
            'ind-ID',
            'jpn-JP',
            'kor-KR',
            'mkd-MK',
            'pol-PL',
            'rus-RU',
            'slk-SK',
            'slo-SI',
            'srp-RS',
            'tur-TR',
            'ukr-UA',
            'vie-VN',
        ], true);
    }
}
