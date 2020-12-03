<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;

/**
 * Class AnalyzerService.
 */
final class ParentAnalyzerService implements ParentAnalyzerInterface, \IteratorAggregate
{
    /**
     * @var array|AnalyzerInterface[]
     */
    private $analyzers = [];

    /**
     * @var \Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver
     */
    private $siteAccessConfigResolver;

    public function __construct(SiteAccessConfigResolver $siteAccessConfigResolver)
    {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    /**
     * Adds an analyzer to the list of analyzers which will run.
     */
    public function addAnalyzer(string $className, AnalyzerInterface $analyzer): void
    {
        $this->analyzers[$className] = $analyzer;
    }

    /**
     * Fetch result of all analyzers for the provided content data.
     */
    public function analyze(AnalysisDTO $data): array
    {
        $result = [];
        foreach ($this->analyzers as $className => $analyzer) {
            if (
                !$this->allowAnalyzer($data->getContentTypeIdentifier(), $className, $data->getSiteaccess())
                || !$analyzer->support($data)
            ) {
                continue;
            }

            $analysisResult = $analyzer->analyze($data);

            if (!\array_key_exists(\key($analysisResult), $result)) {
                $result[\key($analysisResult)] = [];
            }
            $result[\key($analysisResult)][\substr(\get_class($analyzer), \strrpos(\get_class($analyzer), '\\') + 1)] = $analysisResult[\key($analysisResult)];
        }

        return $result;
    }

    /**
     * Checks if analyzer is in a specific blocklist for the content type.
     *
     * @param string $siteAccess
     */
    public function allowAnalyzer(string $contentTypeIdentifier, string $analyzerClassName, string $siteAccess = null): bool
    {
        $analysisConfig = $this->siteAccessConfigResolver->getParameterConfig('analysis', $siteAccess);
        if (!\array_key_exists('content_types', $analysisConfig)) {
            return true;
        }
        if (!\array_key_exists($contentTypeIdentifier, $analysisConfig['content_types'])) {
            return true;
        }
        if (!\array_key_exists('blocklist', $analysisConfig['content_types'][$contentTypeIdentifier])) {
            return true;
        }
        $blocklist = $analysisConfig['content_types'][$contentTypeIdentifier]['blocklist'];
        if (\is_array($blocklist) && \in_array($analyzerClassName, $blocklist, true)) {
            return false;
        }

        return true;
    }

    /**
     * Iterates over the mapped analyzers while generating them.
     *
     * An analyzer is initialized only if we really need it (at
     * the corresponding iteration).
     *
     * @return \Generator The generated {@link RichTextParentAnalyzerInterface} implementations
     */
    public function getIterator()
    {
        foreach ($this->analyzers as $analyzer) {
            if ($analyzer instanceof ParentAnalyzerInterface) {
                yield $analyzer;
            }
        }
    }
}
