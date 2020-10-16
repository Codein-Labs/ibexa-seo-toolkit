<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

/**
 * Class ContentAnalyzerInterface.
 */
interface ContentAnalyzerInterface
{
    /**
     * @param $fieldDefinition
     * @param $fieldValue
     * @return mixed
     */
    public function analyze($fieldDefinition, $fieldValue);
}
