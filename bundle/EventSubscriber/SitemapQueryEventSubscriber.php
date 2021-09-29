<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventSubscriber;

use Codein\IbexaSeoToolkit\Event\SitemapQueryEvent;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SitemapQueryEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            SitemapQueryEvent::class => [
                ['defaultSitemapQueryCriterions', -1000],
            ],
        ];
    }

    public function defaultSitemapQueryCriterions(SitemapQueryEvent $event)
    {
        $event->getLocationQuery()->query = new Criterion\LogicalAnd(
            \array_merge(
                $event->getBaseCriteria(),
                $event->getBlocklistCriteria(),
                $event->getPasslistCriteria(),
            )
        );
    }
}
