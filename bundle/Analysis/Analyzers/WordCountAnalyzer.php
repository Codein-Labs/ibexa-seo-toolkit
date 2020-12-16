<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\RatioLevels;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\XmlProcessingService;

/**
 * Class WordCountAnalyzer.
 */
final class WordCountAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

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
        $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields)
            ->saveHTML();

        $text = \strip_tags($html);

        $count = \str_word_count($text);
        $status = RatioLevels::LOW;

        // Pillar content increases the requirements
        $infimum = 700 * ($analysisDTO->isPillarContent() ? 1.5 : 1);
        $supremum = 1500 * ($analysisDTO->isPillarContent() ? 1.5 : 1);
        if ($count > $infimum && $count < $supremum) {
            $status = RatioLevels::MEDIUM;
        } elseif ($count >= $supremum) {
            $status = RatioLevels::HIGH;
        }

        return [
            self::CATEGORY => [
                'status' => $status,
                'data' => [
                    'count' => $count,
                ],
            ],
        ];
    }
}
