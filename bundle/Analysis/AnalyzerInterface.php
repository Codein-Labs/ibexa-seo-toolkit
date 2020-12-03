<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis;

use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;

/**
 * Class AnalyzerInterface.
 */
interface AnalyzerInterface
{
    public function analyze(AnalysisDTO $data): array;

    public function support(AnalysisDTO $data): bool;
}
