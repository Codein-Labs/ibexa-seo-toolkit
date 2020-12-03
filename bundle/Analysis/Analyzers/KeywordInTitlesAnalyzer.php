<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\AnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Codein\eZPlatformSeoToolkit\Service\XmlProcessingService;

/**
 * Class KeywordInTitlesAnalyzer.
 */
final class KeywordInTitlesAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{
    const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
    private $as;

    /** @var \Codein\eZPlatformSeoToolkit\Service\XmlProcessingService */
    private $xmlProcessingService;

    public function __construct(AnalyzerService $analyzerService, XmlProcessingService $xmlProcessingService)
    {
        $this->as = $analyzerService;
        $this->xmlProcessingService = $xmlProcessingService;
    }

    public function analyze(AnalysisDTO $data): array
    {
        $fields = $data->getFields();

        \libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields);

        $selector = new \DOMXPath($html);

        $titles = $selector->query('//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]');

        $status = 'low';
        $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($data->getKeyword()), AnalyzerService::ACCENT_VALUES));
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

        if ($ratioKeywordInTitle > 10 && $ratioKeywordInTitle < 30) {
            $status = 'medium';
        } elseif ($ratioKeywordInTitle >= 30) {
            $status = 'high';
        }

        $analysisData = [
            'ratio' => $ratioKeywordInTitle,
        ];

        return $this->as->compile(self::CATEGORY, $status, $analysisData);
    }
}
