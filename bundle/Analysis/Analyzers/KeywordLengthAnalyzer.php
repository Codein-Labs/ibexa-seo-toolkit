<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\RatioLevels;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class KeywordLengthAnalyzer.
 */
final class KeywordLengthAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
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

        $status = RatioLevels::HIGH;

        foreach ($keywordSynonyms as $keywordSynonym) {
            if (\str_word_count($keywordSynonym) > 6) {
                $status = RatioLevels::LOW;
            } else if (\str_word_count($keywordSynonym) > 4 && $status !== RatioLevels::LOW) {
                $status = RatioLevels::MEDIUM;
            }
        }

        return $this->analyzerService->compile(self::CATEGORY, $status, []);
    }
}
