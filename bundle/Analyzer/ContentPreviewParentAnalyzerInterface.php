<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Interface AnalyzerInterface.
 */
interface ContentPreviewParentAnalyzerInterface
{
    public function addAnalyzer($analyzer): void;

    public function analyze(array $data): array;
}
