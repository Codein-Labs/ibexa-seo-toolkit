<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

/**
 * Class ContentAnalyzerInterface.
 */
interface ContentAnalyzerInterface
{
    /**
     * @param $fieldValue
     * @return mixed
     */
    public function analyze($fieldValue);
}
