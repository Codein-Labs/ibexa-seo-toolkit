<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Interface ContentPreviewAnalyzerInterface.
 */
interface ContentPreviewAnalyzerInterface
{
    public function analyze(BaseValue $fieldValue): array;

    public function support(FieldDefinition $fieldDefinition): bool;
}
