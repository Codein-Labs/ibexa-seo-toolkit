<?php

declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\AnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use DOMDocument;
use DOMXPath;

/**
 * Class OneH1TagMaximumAnalyzer.
 */
final class OneH1TagMaximumAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';
    /**
     * @var AnalyzerService
     */
    private $analyzerService;

    public function __construct(AnalyzerService $analyzerService)
    {
        $this->analyzerService = $analyzerService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($analysisDTO->getPreviewHtml());

        $selector = new DOMXPath($domDocument);
        $h1 = $selector->query('//h1');
        $count = $h1->count();
        $status = 'low';
        if (1 === $count) {
            $status = 'high';
        }
        $analysisData = [
            'count' => $count,
        ];

        return $this->analyzerService->compile(self::CATEGORY, $status, $analysisData);
    }
}
