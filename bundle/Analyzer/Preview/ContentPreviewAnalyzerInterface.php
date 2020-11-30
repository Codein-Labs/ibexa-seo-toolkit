<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

/**
 * Interface ContentPreviewAnalyzerInterface.
 */
interface ContentPreviewAnalyzerInterface
{
    public function analyze(array $data): array;

    public function support($data): bool;
}
