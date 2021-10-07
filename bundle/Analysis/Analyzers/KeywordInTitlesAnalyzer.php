<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits\StringNormalizerTrait;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;

/**
 * Class KeywordInTitlesAnalyzer.
 */
final class KeywordInTitlesAnalyzer extends AbstractAnalyzer
{
    use StringNormalizerTrait;

    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    /** @var AnalyzerService */
    private $analyzerService;

    public function __construct(
        AnalyzerService $analyzerService
    ) {
        $this->analyzerService = $analyzerService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $domxPath = new \DOMXPath($analysisDTO->getContentDOMDocument());

        $titles = $domxPath->query('//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]');

        $keywordSynonyms = \explode(',', $this->normalizeString($analysisDTO->getKeyword()));
        $keywordSynonyms = \array_map('trim', $keywordSynonyms);

        $numberOfTitles = 0;
        $numberOfTitlesContainingKeyword = 0;
        foreach ($titles as $title) {
            foreach ($keywordSynonyms as $keyword) {
                /** @var \DOMElement $title */
                $titleLowercase = $this->normalizeString($title->textContent);
                if (false !== \mb_strpos($titleLowercase, $keyword)) {
                    ++$numberOfTitlesContainingKeyword;
                    break;
                }
            }
            ++$numberOfTitles;
        }

        $ratioKeywordInTitle = 0;
        if ($numberOfTitles > 0) {
            $ratioKeywordInTitle = \round($numberOfTitlesContainingKeyword / $numberOfTitles * 100, 2);
        }

        $status = RatioLevels::LOW;
        if ($ratioKeywordInTitle > 10 && $ratioKeywordInTitle < 30) {
            $status = RatioLevels::MEDIUM;
        } elseif ($ratioKeywordInTitle >= 30) {
            $status = RatioLevels::HIGH;
        }

        return $this->analyzerService->compile(self::CATEGORY, $status, [
            'ratio' => $ratioKeywordInTitle,
        ]);
    }
}
