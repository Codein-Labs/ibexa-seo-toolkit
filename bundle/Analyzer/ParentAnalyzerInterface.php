<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Interface AnalyzerInterface.
 */
interface ParentAnalyzerInterface
{
    public function addAnalyzer($analyzer): void;

    public function analyze(FieldDefinition $fieldDefinition, FieldValue $fieldValue): array;
}
