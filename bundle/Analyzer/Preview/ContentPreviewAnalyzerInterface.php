<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Interface ContentPreviewAnalyzerInterface.
 */
interface ContentPreviewAnalyzerInterface
{
    public function analyze(FieldValue $fieldValue): array;

    public function support(FieldDefinition $fieldDefinition): bool;
}
