<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Class ContentPreviewAnalyzerService.
 */
final class ContentPreviewParentAnalyzerService implements ParentAnalyzerInterface, \IteratorAggregate
{
    /**
     * @var array|ContentPreviewAnalyzerInterface[]
     */
    private $analyzers = [];

    public function addAnalyzer($analyzer): void
    {
        $this->analyzers[] = $analyzer;
    }

    public function analyze(FieldDefinition $fieldDefinition, BaseValue $fieldValue): array
    {
        $result = [];
        foreach ($this->analyzers as $analyzer) {
            if (!$analyzer->support($fieldDefinition)) {
                continue;
            }

            $result[\substr(\get_class($analyzer), \strrpos(\get_class($analyzer), '\\') + 1)] = $analyzer->analyze($fieldValue);
        }

        return $result;
    }

    /**
     * Iterates over the mapped analyzers while generating them.
     *
     * An analyzer is initialized only if we really need it (at
     * the corresponding iteration).
     *
     * @return \Generator The generated {@link ParentAnalyzerInterface} implementations
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
