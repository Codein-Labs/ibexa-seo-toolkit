<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\RichText;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Class RichTextAnalyzerInterface.
 */
interface RichTextAnalyzerInterface
{
    public function analyze(Field $fieldDefinition, BaseValue $fieldValue): array;

    public function support(Field $fieldDefinition): bool;
}
