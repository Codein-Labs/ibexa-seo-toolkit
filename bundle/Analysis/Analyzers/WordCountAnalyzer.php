<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\XmlProcessingService;

/**
 * Class WordCountAnalyzer.
 */
final class WordCountAnalyzer extends AbstractAnalyzer
{
    public const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    public const INFIMUM = 700;
    public const SUPREMUM = 1500;

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
        $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields)->saveHTML();

        $text = \strip_tags($html);

        $count = \str_word_count($text);
        $status = RatioLevels::LOW;

        // Pillar content increases the requirements
        $infimum = self::INFIMUM * ($analysisDTO->isPillarContent() ? 1.5 : 1);
        $supremum = self::SUPREMUM * ($analysisDTO->isPillarContent() ? 1.5 : 1);
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
                    'infimum' => $infimum,
                    'supremum' => $supremum
                ],
            ],
        ];
    }

    public function support(AnalysisDTO $data): bool
    {
        if (count($data->getFields()) === 0) {
            return false;
        }
        return true;
    }
}
