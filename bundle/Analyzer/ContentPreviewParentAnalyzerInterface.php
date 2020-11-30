<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

/**
 * Interface AnalyzerInterface.
 */
interface ContentPreviewParentAnalyzerInterface
{
    public function addAnalyzer($analyzer): void;

    public function analyze(array $data): array;
}
