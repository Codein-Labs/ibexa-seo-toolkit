<?php

namespace Codein\IbexaSeoToolkit\EventSubscriber;

use Codein\IbexaSeoToolkit\Event\AnalysisDTOEvent;
use Codein\IbexaSeoToolkit\Service\XmlProcessingService;
use DOMElement;
use DOMXPath;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function count;

class AnalysisDTOEventSubscriber implements EventSubscriberInterface
{
    /** @var XmlProcessingService */
    private $processingService;

    public function __construct(XmlProcessingService $processingService)
    {
        $this->processingService = $processingService;
    }

    public static function getSubscribedEvents()
    {
        return [
            AnalysisDTOEvent::class => [
                ['setAnalyzableHtmlContent', -1000],
            ],
        ];

    }

    public function setAnalyzableHtmlContent(AnalysisDTOEvent $event)
    {
        libxml_use_internal_errors(true);
        if(0 === count($event->getAnalysisDTO()->getFields())) {
            $xpath = new DOMXPath($event->getAnalysisDTO()->getContentDOMDocument());
            $body = $xpath->query('//*');

            foreach ($body as $item) {
                /** @var DOMElement $item */
                if (in_array($item->tagName, ['head', 'header', 'footer', 'script', 'nav', 'aside', 'style', 'xml'])
                    && $item->parentNode instanceof DOMElement) {
                    $item->parentNode->removeChild($item);
                }
            }
        } else {
            $event->getAnalysisDTO()->setContentDOMDocument($this->processingService->combineAndProcessXmlFields(
                $event->getAnalysisDTO()->getFields()
            ));
        }
    }

}
