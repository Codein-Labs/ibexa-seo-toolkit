<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\RatioLevels;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class SeoTitleWidthAnalyzer.
 */
final class SeoTitleWidthAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
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

        if ($titleTags->count() == 0) {
            return $this->analyzerService->compile(self::CATEGORY, null, null);
        }
        $titleTags->item(0);
        
        $status = RatioLevels::MEDIUM;
        if (0 === $titleTags->count()) {
            $status = RatioLevels::LOW;
        } else if (($titleLength = strlen($titleTags->item(0)->nodeValue)) < 60) {
            $status = RatioLevels::HIGH;
        }

        return $this->analyzerService->compile(self::CATEGORY, $status, [
            'charCount' => $titleLength
        ]);
    }
}
