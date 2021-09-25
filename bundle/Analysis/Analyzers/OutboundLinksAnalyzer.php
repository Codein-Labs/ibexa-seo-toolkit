<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\XmlProcessingService;

/**
 * Class OutboundLinksAnalyzer.
 */
final class OutboundLinksAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    private const GOOD_RATIO = 1 / 400;

    /** @var XmlProcessingService */
    private $xmlProcessingService;

    public function __construct(XmlProcessingService $xmlProcessingService)
    {
        $this->xmlProcessingService = $xmlProcessingService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $fields = $analysisDTO->getFields();

        \libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields);

        $htmlText = \strip_tags($html->saveHTML());
        $wordCount = \str_word_count($htmlText);

        $domxPath = new \DOMXPath($html);
        $allLinks = $domxPath->query('.//a');

        $count = 0;

        /** @var \DOMElement $link */
        foreach ($allLinks as $link) {
            $linkHref = $link->getAttribute('href');
            // Drop internal links
            if (false === \mb_strpos($linkHref, 'ezlocation://')) {
                ++$count;
            }
        }

        $ratio = $count / $wordCount;

        $status = RatioLevels::LOW;
        if ($ratio > 0 && $ratio < self::GOOD_RATIO) {
            $status = RatioLevels::MEDIUM;
        } elseif ($ratio >= self::GOOD_RATIO) {
            $status = RatioLevels::HIGH;
        }

        return [
            self::CATEGORY => [
                'status' => $status,
                'data' => [
                    'count' => $count,
                    'recommended' => \ceil($wordCount / (1 / self::GOOD_RATIO)),
                ],
            ],
        ];
    }

    public function support(AnalysisDTO $analysisDTO): bool
    {
        if (0 === \count($analysisDTO->getFields())) {
            return false;
        }

        return true;
    }
}
