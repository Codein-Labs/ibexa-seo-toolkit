<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;

/**
 * Class OneH1TagMaximumAnalyzer.
 */
final class OneH1TagMaximumAnalyzer implements ContentPreviewAnalyzerInterface
{
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService $as */
    private $as;

    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';


    public function __construct(AnalyzerService $analyzerService)
    {
        $this->as = $analyzerService;
    }

    public function analyze(array $data): array
    {
        $selector = new \DOMXPath($data['previewHtml']);
        $h1 = $selector->query('//h1');
        $count = $h1->count();
        $status = 'low';
        if ($count == 1) {
            $status = 'high';
        }
        $analysisData = [
            'count' => $count
        ];
        return $this->as->compile(self::CATEGORY, $status, $analysisData);
    }

    public function support($data): bool
    {
        return true;
    }
}
