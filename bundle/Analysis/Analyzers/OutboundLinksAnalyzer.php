<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers;

use DOMElement;
use DOMNodeList;

/**
 * Class OutboundLinksAnalyzer.
 */
final class OutboundLinksAnalyzer extends AbstractLinksAnalyzer
{
    protected function getLinksCount(DOMNodeList $allLinks): int
    {
        $count = 0;
        /** @var DOMElement $link */
        foreach ($allLinks as $link) {
            $linkHref = $link->getAttribute('href');
            if (false === \mb_strpos($linkHref, 'mailto:') && !$this->hrefIsInternal($linkHref)) {
                ++$count;
            }
        }
        return $count;
    }
}
