<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\RichText;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Class RichTextAnalyzerInterface.
 */
interface RichTextAnalyzerInterface
{
    public function analyze(FieldValue $fieldValue, array $data = []): array;

    public function support(FieldDefinition $fieldDefinition, $data): bool;
}
