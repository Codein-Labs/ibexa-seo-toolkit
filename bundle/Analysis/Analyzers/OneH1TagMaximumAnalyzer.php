<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\AnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use DOMDocument;

/**
 * Class OneH1TagMaximumAnalyzer.
 */
final class OneH1TagMaximumAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
    private $as;

    public function __construct(AnalyzerService $analyzerService)
    {
        $this->as = $analyzerService;
    }

    public function analyze(AnalysisDTO $data): array
    {
        $htmlDocument = new DOMDocument();
        $htmlDocument->loadHTML($data->getPreviewHtml());

        $selector = new \DOMXPath($htmlDocument);
        $h1 = $selector->query('//h1');
        $count = $h1->count();
        $status = 'low';
        if (1 === $count) {
            $status = 'high';
        }
        $analysisData = [
            'count' => $count,
        ];

        return $this->as->compile(self::CATEGORY, $status, $analysisData);
    }
}
