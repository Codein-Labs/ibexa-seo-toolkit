<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;

/**
 * Class OneH1TagMaximumAnalyzer.
 */
final class OneH1TagMaximumAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    private $analyzerService;

    public function __construct(AnalyzerService $analyzerService)
    {
        $this->analyzerService = $analyzerService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $selector = new \DOMXPath($analysisDTO->getHtmlPreviewDOMDocument());
        $h1 = $selector->query('//h1');
        $count = $h1->count();
        $status = RatioLevels::LOW;
        if (1 === $count) {
            $status = RatioLevels::HIGH;
        }

        return $this->analyzerService->compile(self::CATEGORY, $status, [
            'count' => $count,
        ]);
    }
}
