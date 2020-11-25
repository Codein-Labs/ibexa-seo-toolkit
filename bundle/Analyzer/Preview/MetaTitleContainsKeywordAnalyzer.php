<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class CountWordService.
 */
final class MetaTitleContainsKeywordAnalyzer implements ContentPreviewAnalyzerInterface
{

    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService $as */
    private $as; 

    /** @var \Psr\Log\LoggerInterface $logger */
    private $logger;

    const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';


    public function __construct(AnalyzerService $analyzerService, LoggerInterface $loggerInterface)
    {
        $this->as = $analyzerService;
        $this->logger = $loggerInterface;
    }

    public function analyze(array $data): array
    {
        $selector = new \DOMXPath($data['previewHtml']);
        $metaTitle = $selector->query('//meta[@name="title"]');

        try {
            $status = 'medium';
            if ($metaTitle->count() == 0) {
                $status = 'low';
            }
            else if (strpos($metaTitle->item(0)->getAttribute('content'), $data['keyword']) !== false) {
                $status = 'high';
            }
            return $this->as->compile(self::CATEGORY, $status, []);
        } catch (Exception $e) {
            $this->logger->error($e);
            return $this->as->compile(self::CATEGORY, null, null);
        }

    }

    public function support($data): bool
    {
        return true;
    }
}
