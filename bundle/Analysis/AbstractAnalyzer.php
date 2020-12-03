<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis;

use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;

/**
 * Class AbstractAnalyzer.
 */
abstract class AbstractAnalyzer implements AnalyzerInterface
{
    public function support(AnalysisDTO $data): bool {
        return true;
    }
}
