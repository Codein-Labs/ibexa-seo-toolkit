<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use DOMDocument;
use eZ\Publish\Core\MVC\Symfony\Controller\Content\PreviewController;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

/**
 * Class ContentPreviewAnalyzerService.
 */
final class ContentPreviewParentAnalyzerService implements ContentPreviewParentAnalyzerInterface, \IteratorAggregate
{
    /**
     * @var Client
     */
    private $client;

    /** @var string */
    private $defaultSiteaccess;

    /** @var PreviewController */
    private $previewControllerService;

    /**
     * @var array|ContentPreviewAnalyzerInterface[]
     */
    private $analyzers = [];

    private $logger;

    public function __construct($defaultSiteaccess, PreviewController $previewControllerService, LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->defaultSiteaccess = $defaultSiteaccess;
        $this->previewControllerService = $previewControllerService;
        $this->logger = $logger;
    }

    public function addAnalyzer($analyzer): void
    {
        $this->analyzers[] = $analyzer;
    }

    public function analyze(array $data): array
    {
        $result = [];

        $dataPreviewHtml = $this->previewControllerService->previewContentAction(
            $data['request'],
            $data['contentId'],
            $data['versionNo'],
            $data['language'],
            $data['siteaccess']
        )->getContent();
        if (!$dataPreviewHtml || 0 === \strlen($dataPreviewHtml)) {
            return [];
        }

        try {
            $domDocument = new DOMDocument();
            $domDocument->loadHTML($dataPreviewHtml);
        } catch (\DOMException $domError) {
            $this->logger->error($domError);

            return [];
        }
        $data['previewHtml'] = $domDocument;

        foreach ($this->analyzers as $analyzer) {
            if (!$analyzer->support($data)) {
                continue;
            }
            $analysisResult = $analyzer->analyze($data);
            if (!\array_key_exists(\key($analysisResult), $result)) {
                $result[\key($analysisResult)] = [];
            }
            $result[\key($analysisResult)][\substr(\get_class($analyzer), \strrpos(\get_class($analyzer), '\\') + 1)]
                = $analysisResult[\key($analysisResult)];
        }

        return $result;
    }

    /**
     * Iterates over the mapped analyzers while generating them.
     *
     * An analyzer is initialized only if we really need it (at
     * the corresponding iteration).
     *
     * @return \Generator The generated {@link ContentPreviewParentAnalyzerInterface} implementations
     */
    public function getIterator()
    {
        foreach ($this->analyzers as $analyzer) {
            if ($analyzer instanceof ContentPreviewParentAnalyzerInterface) {
                yield $analyzer;
            }
        }
    }
}
