<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use eZ\Publish\Core\Repository\LocationService;
use eZ\Publish\Core\Repository\URLAliasService;

/**
 * Class KeywordInUrlSlugAnalyzer.
 * 
 * Look for keyword in URL Slug
 * 
 * This could be implemented as a richtext analyzer as well as it doesn't need access to content specifically
 */
final class KeywordInUrlSlugAnalyzer implements ContentPreviewAnalyzerInterface
{
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService $as */
    private $as;

    /** @var URLAliasService $urlAliasService */
    private $urlAliasService;

    /** @var LocationService $locationService */
    private $locationService;

    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';


    public function __construct(
        AnalyzerService $analyzerService,
        URLAliasService $urlAliasService,
        LocationService $locationService
    )
    {
        $this->as = $analyzerService;
        $this->urlAliasService = $urlAliasService;
        $this->locationService = $locationService;
    }

    public function analyze(array $data): array
    {
        $locationId = $data['locationId'];

        try {
            $location = $this->locationService->loadLocation($locationId);
    
            /** @var \eZ\Publish\API\Repository\Values\Content\URLAlias $urlAlias */
            $urlAlias = $this->urlAliasService->reverseLookup($location);
    
            $pathArray = explode('/', $urlAlias->path);
            $urlSlug = mb_strtolower(end($pathArray));
            $urlSlugWithoutDashes = str_replace('-', " ", $urlSlug);
            $keywordSynonyms = explode(',',strtr(mb_strtolower($data['keyword']), AnalyzerService::ACCENT_VALUES));
            $keywordSynonyms = array_map('trim', $keywordSynonyms);

            $bestRatio = 0;
            foreach ($keywordSynonyms as $keyword) {
                $distance = AnalyzerService::levenshtein_utf8($keyword, $urlSlugWithoutDashes);
                $lenSum = strlen($urlSlugWithoutDashes) + strlen($keyword);
                $levenshteinRatio = 1 - ($distance / $lenSum);
                $bestRatio = ($levenshteinRatio > $bestRatio ? $levenshteinRatio : $bestRatio);
            }
            
    
            $status = 'low';

            if ($bestRatio > 0.85 && $bestRatio < 1)
            {
                $status = 'medium';
            }
            else if ($bestRatio == 1) {
                $status = 'high';
            }
            
            // case keyword is included, but far from equal
            if ($status == 'low' && strpos($urlSlugWithoutDashes, $keyword) !== false) {
                $status = "medium";
            }
        }
        catch(\Exception $e) {
            return $this->as->compile(self::CATEGORY, null, null);
        }
        
        return $this->as->compile(self::CATEGORY, $status, [
            'similarity' => $bestRatio * 100 
        ]);
    }

    public function support($data): bool
    {
        // Difficult to get non latin alphabet languages
        // to work well with this analyzer.
        // Moreover, we don't know how Search Engines treats them
        if (in_array($data['language'], [
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
            'vie-VN'
        ])) {
            return false;
        }
        return true;
    }
}
