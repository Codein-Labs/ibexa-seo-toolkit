<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;
use Codein\IbexaSeoToolkit\Service\XmlProcessingService;

/**
 * Class KeywordInTitlesAnalyzer.
 */
final class KeywordInTitlesAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    private $analyzerService;
    private $xmlProcessingService;

    public function __construct(
        AnalyzerService $analyzerService,
        XmlProcessingService $xmlProcessingService
    ) {
        $this->analyzerService = $analyzerService;
        $this->xmlProcessingService = $xmlProcessingService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $fields = $analysisDTO->getFields();

        \libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields);

        $domxPath = new \DOMXPath($html);

        $titles = $domxPath->query('//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]');

        $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($analysisDTO->getKeyword()), AnalyzerService::ACCENT_VALUES));
        $keywordSynonyms = \array_map('trim', $keywordSynonyms);

        $numberOfTitles = 0;
        $numberOfTitlesContainingKeyword = 0;
        foreach ($titles as $title) {
            foreach ($keywordSynonyms as $keyword) {
                /** @var \DOMElement $title */
                $titleLowercase = \strtr(\mb_strtolower($title->textContent), AnalyzerService::ACCENT_VALUES);
                if (false !== \strpos($titleLowercase, $keyword)) {
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

    public function support(AnalysisDTO $data): bool
    {
        if (count($data->getFields()) === 0) {
            return false;
        }
        return true;
    }
}
