<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Class ContentPreviewAnalyzerService.
 */
final class ContentPreviewParentAnalyzerService implements ParentAnalyzerInterface
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
        foreach ($this->analyzers as $analyzer) {
            $result = [];
            if (!$analyzer->support($fieldDefinition)) {
                continue;
            }

            $result[\substr(\get_class($analyzer), \strrpos(\get_class($analyzer), '\\') + 1)] = $analyzer->analyze($fieldValue);
        }

        return $result;
    }
}
