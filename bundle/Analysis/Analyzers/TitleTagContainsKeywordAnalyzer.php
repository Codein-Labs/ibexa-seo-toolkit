<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\AnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class TitleTagContainsKeywordAnalyzer.
 */
final class TitleTagContainsKeywordAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{
    const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
    private $as;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(AnalyzerService $analyzerService, LoggerInterface $loggerInterface)
    {
        $this->as = $analyzerService;
        $this->logger = $loggerInterface;
    }

    public function analyze(AnalysisDTO $data): array
    {
        $htmlDocument = new \DOMDocument();
        $htmlDocument->loadHTML($data->getPreviewHtml());

        $selector = new \DOMXPath($htmlDocument);

        /** @var \DOMNodeList $titleTag */
        $titleTags = $selector->query('//title');

        try {
            $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($data->getKeyword()), AnalyzerService::ACCENT_VALUES));

            $keywordSynonyms = \array_map('trim', $keywordSynonyms);
            $status = 'medium';
            if (0 === $titleTags->count()) {
                $status = 'low';
            } else {
                foreach ($titleTags as $titleTag) {
                    foreach ($keywordSynonyms as $keyword) {
                        if (false !== \strpos($titleTag->getAttribute('content'), $keyword)) {
                            $status = 'high';
                            break;
                        }
                    }
                    if ('high' === $status) {
                        break;
                    }
                }
            }

            return $this->as->compile(self::CATEGORY, $status, []);
        } catch (Exception $e) {
            $this->logger->error($e);

            return $this->as->compile(self::CATEGORY, null, null);
        }
    }
}
