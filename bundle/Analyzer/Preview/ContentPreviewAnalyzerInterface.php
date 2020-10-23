<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Interface ContentPreviewAnalyzerInterface.
 */
interface ContentPreviewAnalyzerInterface
{
    public function analyze(Field $fieldDefinition, BaseValue $fieldValue): array;

    public function support(Field $fieldDefinition): bool;
}
