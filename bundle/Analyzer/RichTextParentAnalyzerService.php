<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use Codein\eZPlatformSeoToolkit\Analyzer\RichText\RichTextAnalyzerInterface;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Class RichTextAnalyzerService.
 */
final class RichTextParentAnalyzerService implements RichTextParentAnalyzerInterface, \IteratorAggregate
{
    /**
     * @var array|RichTextAnalyzerInterface[]
     */
    private $analyzers = [];

    public function addAnalyzer($analyzer): void
    {
        $this->analyzers[] = $analyzer;
    }

    public function analyze(FieldDefinition $fieldDefinition, fieldValue $fieldValue, array $data): array
    {
        $result = [];
        foreach ($this->analyzers as $analyzer) {
            if (!$analyzer->support($fieldDefinition)) {
                continue;
            }

            $analysisResult = $analyzer->analyze($fieldValue, $data);
            if (!array_key_exists(\key($analysisResult), $result)) {
                $result[\key($analysisResult)] = [];
            }
            $result[\key($analysisResult)][\substr(\get_class($analyzer), \strrpos(\get_class($analyzer), '\\') + 1)] = $analysisResult[\key($analysisResult)];
        }

        return $result;
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
            if ($analyzer instanceof RichTextParentAnalyzerInterface) {
                yield $analyzer;
            }
        }
    }
}
