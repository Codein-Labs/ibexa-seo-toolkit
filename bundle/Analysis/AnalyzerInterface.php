<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis;

use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;

/**
 * Class AnalyzerInterface.
 */
interface AnalyzerInterface
{
    public function analyze(AnalysisDTO $analysisDTO): array;

    public function support(AnalysisDTO $analysisDTO): bool;
}
