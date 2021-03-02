<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;

/**
 * Class AnalyzerService.
 */
final class ParentAnalyzerService implements ParentAnalyzerInterface, \IteratorAggregate
{
    private const CONTENT_TYPES = 'content_types';
    /**
     * @var AnalyzerInterface[]
     */
    private $analyzers = [];
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
    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $result = [];
        foreach ($this->analyzers as $className => $analyzer) {
            if (
                !$this->isAllowed($analysisDTO->getContentTypeIdentifier(), $className, $analysisDTO->getSiteaccess()) ||
                !$analyzer->support($analysisDTO)
            ) {
                continue;
            }

            $analysisResult = $analyzer->analyze($analysisDTO);

            if (!\array_key_exists(\key($analysisResult), $result)) {
                $result[\key($analysisResult)] = [];
            }
            $result[\key($analysisResult)][\substr(\get_class($analyzer), \strrpos(\get_class($analyzer), '\\') + 1)] =
                $analysisResult[\key($analysisResult)];
        }

        return $result;
    }

    /**
     * Checks if analyzer is in a specific blocklist for the content type.
     *
     * @param string $siteAccess
     */
    public function isAllowed(string $contentTypeIdentifier, string $analyzerClassName, ?string $siteAccess = null): bool
    {
        $analysisConfig = $this->siteAccessConfigResolver->getParameterConfig('analysis', $siteAccess);
        if (!\array_key_exists(self::CONTENT_TYPES, $analysisConfig)) {
            return true;
        }
        if (!\array_key_exists($contentTypeIdentifier, $analysisConfig[self::CONTENT_TYPES])) {
            return true;
        }
        if (!\array_key_exists('blocklist', $analysisConfig[self::CONTENT_TYPES][$contentTypeIdentifier])) {
            return true;
        }
        $blocklist = $analysisConfig[self::CONTENT_TYPES][$contentTypeIdentifier]['blocklist'];

        return !(\is_array($blocklist) && \in_array($analyzerClassName, $blocklist, true));
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
