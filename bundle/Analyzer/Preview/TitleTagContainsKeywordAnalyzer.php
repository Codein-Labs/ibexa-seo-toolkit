<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class TitleTagContainsKeywordAnalyzer.
 */
final class TitleTagContainsKeywordAnalyzer implements ContentPreviewAnalyzerInterface
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

    public function analyze(array $data): array
    {
        $selector = new \DOMXPath($data['previewHtml']);

        /** @var \DOMNodeList $titleTag */
        $titleTags = $selector->query('//title');

        try {
            $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($data['keyword']), AnalyzerService::ACCENT_VALUES));

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

    public function support($data): bool
    {
        return true;
    }
}
