<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits;

use Codein\IbexaSeoToolkit\Service\AnalyzerService;

trait StringNormalizerTrait
{
    public function normalizeString(?string $string): string
    {
        return \strtr(\mb_strtolower($string), AnalyzerService::ACCENT_VALUES);
    }
}
