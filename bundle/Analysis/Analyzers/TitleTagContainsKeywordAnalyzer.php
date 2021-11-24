<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits\StringNormalizerTrait;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class TitleTagContainsKeywordAnalyzer.
 */
final class TitleTagContainsKeywordAnalyzer extends AbstractAnalyzer
{
    use StringNormalizerTrait;

    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    /** @var \Codein\IbexaSeoToolkit\Service\AnalyzerService */
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
        $domxPath = new \DOMXPath($analysisDTO->getHtmlPreviewDOMDocument());

        /** @var \DOMNodeList $titleTags */
        $titleTags = $domxPath->query('//title');

        try {
            $keywordSynonyms = \explode(',', $this->normalizeString($analysisDTO->getKeyword()));

            $keywordSynonyms = \array_map('trim', $keywordSynonyms);
            $status = RatioLevels::MEDIUM;
            if (0 === $titleTags->count()) {
                $status = RatioLevels::LOW;
            } else {
                /** @var \DOMElement $titleTag */
                foreach ($titleTags as $titleTag) {
                    foreach ($keywordSynonyms as $keyword) {
                        $contentTitleTagAttribute = $this->normalizeString($titleTag->textContent);
                        if (false !== \mb_strpos($contentTitleTagAttribute, $keyword)) {
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
