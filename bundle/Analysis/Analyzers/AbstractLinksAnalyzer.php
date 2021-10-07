<?php

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits\WordCountTrait;
use Codein\IbexaSeoToolkit\Analysis\RatioLevels;
use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzerService;
use DOMNodeList;
use DOMXPath;

abstract class AbstractLinksAnalyzer extends AbstractAnalyzer
{
    use WordCountTrait;

    protected const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    protected const GOOD_RATIO = 1 / 100;

    /** @var AnalyzerService */
    protected $analyzerService;

    /** @var string[] */
    protected $internalHostnames;

    public function __construct(AnalyzerService $analyzerService, SiteAccessConfigResolver $siteAccessConfigResolver)
    {
        $this->analyzerService = $analyzerService;
        $this->internalHostnames = $siteAccessConfigResolver->getParameterConfig('internal_links_hostnames');
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $domDocument = $analysisDTO->getContentDOMDocument();
        $wordCount = $this->getWordCount($domDocument);
        $domxPath = new DOMXPath($domDocument);
        $count = $this->getLinksCount($domxPath->query('.//a'));
        $ratio = ($wordCount > 0 ? $count / $wordCount : 0);

        $status = RatioLevels::LOW;
        if ($ratio > 0 && $ratio < self::GOOD_RATIO) {
            $status = RatioLevels::MEDIUM;
        } elseif ($ratio >= self::GOOD_RATIO) {
            $status = RatioLevels::HIGH;
        }

        return [
            self::CATEGORY => [
                'status' => $status,
                'data' => [
                    'count' => $count,
                    'recommended' => \ceil($wordCount / (1 / self::GOOD_RATIO)),
                ],
            ],
        ];
    }

    protected function hrefIsInternal(string $linkHref): bool
    {
        if (false !== \mb_strpos($linkHref, 'ezlocation://')) {
            return true;
        }

        $parsed = parse_url($linkHref);
        $isInternal = false;
        if (is_array($parsed)) {
            $isInternal = true;
            if (isset($parsed['scheme'])
                && isset($parsed['host'])
                && !in_array($parsed['host'], $this->internalHostnames)
            ) {
                $isInternal = false;
            }
        }

        return $isInternal;
    }

    abstract protected function getLinksCount(DOMNodeList $allLinks): int;
}
