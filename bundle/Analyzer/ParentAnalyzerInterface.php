<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Interface AnalyzerInterface.
 */
interface ParentAnalyzerInterface
{
    public function addAnalyzer($analyzer): void;

    public function analyze(Field $fieldDefinition, BaseValue $fieldValue): array;
}
