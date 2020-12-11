<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\RatioLevels;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class TitleTagContainsKeywordAnalyzer.
 */
final class TitleTagContainsKeywordAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
    private $analyzerService;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(
        AnalyzerService $analyzerService,
        LoggerInterface $logger
    ) {
        $this->analyzerService = $analyzerService;
        $this->logger = $logger;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadHTML($analysisDTO->getPreviewHtml());

        $domxPath = new \DOMXPath($domDocument);

        /** @var \DOMNodeList $titleTags */
        $titleTags = $domxPath->query('//title');

        try {
            $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($analysisDTO->getKeyword()), AnalyzerService::ACCENT_VALUES));

            $keywordSynonyms = \array_map('trim', $keywordSynonyms);
            $status = RatioLevels::MEDIUM;
            if (0 === $titleTags->count()) {
                $status = RatioLevels::LOW;
            } else {
                foreach ($titleTags as $titleTag) {
                    foreach ($keywordSynonyms as $keyword) {
                        $contentTitleTagAttribute = $titleTag->getAttribute('content');
                        if (false !== \strpos($contentTitleTagAttribute, $keyword)) {
                            $status = RatioLevels::HIGH;
                            break;
                        }
                    }
                    if (RatioLevels::HIGH === $status) {
                        break;
                    }
                }
            }

            return $this->analyzerService->compile(self::CATEGORY, $status, []);
        } catch (Exception $e) {
            $this->logger->error($e);

            return $this->analyzerService->compile(self::CATEGORY, null, null);
        }
    }
}
