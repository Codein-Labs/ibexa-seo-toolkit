<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits\StringNormalizerTrait;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;

/**
 * Class KeywordLengthAnalyzer.
 */
final class KeywordLengthAnalyzer extends AbstractAnalyzer
{
    use StringNormalizerTrait;

    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $keywordSynonyms = \explode(',', $this->normalizeString($analysisDTO->getKeyword()));
        $keywordSynonyms = \array_map('trim', $keywordSynonyms);
        $maxCount = 0;

        $status = RatioLevels::HIGH;

        foreach ($keywordSynonyms as $keywordSynonym) {
            $count = \str_word_count($keywordSynonym);
            if ($count > 6) {
                $status = RatioLevels::LOW;
            } elseif ($count > 4 && RatioLevels::LOW !== $status) {
                $status = RatioLevels::MEDIUM;
            }
            if ($count > $maxCount) {
                $maxCount = $count;
            }
        }

        return [
            self::CATEGORY => [
                'status' => $status,
                'data' => [
                    'count' => $maxCount,
                ],
            ],
        ];
    }
}
