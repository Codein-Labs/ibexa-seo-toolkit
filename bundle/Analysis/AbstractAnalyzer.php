<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis;

use Codein\IbexaSeoToolkit\Model\AnalysisDTO;

/**
 * Class AbstractAnalyzer.
 */
abstract class AbstractAnalyzer implements AnalyzerInterface
{
    abstract public function analyze(AnalysisDTO $analysisDTO): array;

    public function support(AnalysisDTO $data): bool
    {
        return true;
    }
}
