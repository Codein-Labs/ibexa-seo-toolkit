<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits\WordCountTrait;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;

/**
 * Class WordCountAnalyzer.
 */
final class WordCountAnalyzer extends AbstractAnalyzer
{
    use WordCountTrait;

    public const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    public const INFIMUM = 700;
    public const SUPREMUM = 1500;

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $count = $this->getWordCount($analysisDTO->getContentDOMDocument());
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
                    'supremum' => $supremum,
                ],
            ],
        ];
    }
}
