<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;

/**
 * Class KeywordLengthAnalyzer.
 */
final class KeywordLengthAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    /** @var \Codein\IbexaSeoToolkit\Service\AnalyzerService */
    private $analyzerService;

    public function __construct(
        AnalyzerService $analyzerService
    ) {
        $this->analyzerService = $analyzerService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($analysisDTO->getKeyword()), AnalyzerService::ACCENT_VALUES));
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
