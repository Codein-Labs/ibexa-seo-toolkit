<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer;

/**
 * Class RichTextAnalyzerService.
 */
final class RichTextAnalyzerService
{
    /**
     * @var array|ContentAnalyzerInterface[]
     */
    private $analyzers = [];

    public function addAnalyzer(ContentAnalyzerInterface $converter): void
    {
        $this->analyzers[] = $converter;
    }

    /**
     * @param $fieldDefinition
     * @param $fieldValue
     * @return array
     */
    public function analyze($fieldDefinition, $fieldValue): array
    {
        foreach ($this->analyzers as $analyzer) {
            $result = [];
            if (!$analyzer->support($fieldDefinition)) {
                continue;
            }

            $result[] = $analyzer->analyze($fieldValue);
        }

        return $result;
    }
}
