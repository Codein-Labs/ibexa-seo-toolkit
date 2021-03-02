<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;

/**
 * Class SeoTitleWidthAnalyzer.
 */
final class SeoTitleWidthAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    /** @var \Codein\IbexaSeoToolkit\Service\AnalyzerService */
    private $analyzerService;

    public function __construct(
        AnalyzerService $analyzerService
    ) {
        $this->analyzerService = $analyzerService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadHTML($analysisDTO->getPreviewHtml());

        $domxPath = new \DOMXPath($domDocument);

        /** @var \DOMNodeList $titleTags */
        $titleTags = $domxPath->query('//title');

        if (0 === $titleTags->count()) {
            return $this->analyzerService->compile(self::CATEGORY, null, null);
        }
        $titleTags->item(0);

        $status = RatioLevels::MEDIUM;
        if (0 === $titleTags->count()) {
            $status = RatioLevels::LOW;
        } elseif (($titleLength = \strlen($titleTags->item(0)->nodeValue)) < 60) {
            $status = RatioLevels::HIGH;
        }

        return $this->analyzerService->compile(self::CATEGORY, $status, [
            'charCount' => $titleLength,
        ]);
    }
}
