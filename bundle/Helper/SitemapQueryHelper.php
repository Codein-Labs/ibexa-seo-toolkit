<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Helper;

use Codein\IbexaSeoToolkit\Event\SitemapQueryEvent;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Repository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SitemapQueryHelper
 *.
 */
final class SitemapQueryHelper
{
    /** @var Repository */
    private $repository;

    /** @var SiteAccessConfigResolver */
    private $siteAccessConfigResolver;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        Repository $repository,
        SiteAccessConfigResolver $siteAccessConfigResolver,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->repository = $repository;
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Get Sitemap Query.
     *
     * @param string $specificContentType for content type sitemap cut
     */
    public function getSitemapQuery(string $specificContentType = ''): LocationQuery
    {
        $query = new LocationQuery();

        $baseCriteria = $this->generateSiteaccessRootLocationCriterion();
        $baseCriteria[] = new Criterion\Visibility(Criterion\Visibility::VISIBLE);

        if (\mb_strlen($specificContentType)) {
            $baseCriteria[] = new Criterion\ContentTypeIdentifier($specificContentType);
        }

        $query->sortClauses = [
            new SortClause\DatePublished(LocationQuery::SORT_DESC),
        ];

        /** @var SitemapQueryEvent $event */
        $event = $this->eventDispatcher->dispatch(new SitemapQueryEvent(
            $query,
            $baseCriteria,
            $this->getBlocklistCriteria(),
            $this->getPasslistCriteria(),
            $specificContentType
        ));
        return $event->getLocationQuery();
    }

    private function generateSiteaccessRootLocationCriterion(): array
    {
        $siteaccessRootLocation = $this->repository->getLocationService()->loadLocation(
            $this->siteAccessConfigResolver->getConfigResolver()->getParameter('content.tree_root.location_id')
        );

        return [new Criterion\Subtree($siteaccessRootLocation->pathString)];
    }

    private function getBlocklistCriteria() : array
    {
        $sitemapConfiguration = $this->siteAccessConfigResolver->getParameterConfig('sitemap');
        $blocklistCriteria = [];
        if (\array_key_exists('blocklist', $sitemapConfiguration)) {
            $locations = $sitemapConfiguration['blocklist']['locations'];
            $subtrees = $sitemapConfiguration['blocklist']['subtrees'];
            $contentTypeIdentifiers = $sitemapConfiguration['blocklist']['content_type_identifiers'];

            $criteria = $this->calculateCriteria(
                $locations,
                $subtrees,
                $contentTypeIdentifiers
            );

            $blocklistCriteria = \array_map(function ($criterion) {
                return new Criterion\LogicalNot($criterion);
            }, $criteria);
        }

        return $blocklistCriteria;
    }

    private function getPasslistCriteria() : array
    {
        $sitemapConfiguration = $this->siteAccessConfigResolver->getParameterConfig('sitemap');
        $passlistCriteria = [];
        if (\array_key_exists('passlist', $sitemapConfiguration)) {
            $locations = $sitemapConfiguration['passlist']['locations'];
            $subtrees = $sitemapConfiguration['passlist']['subtrees'];
            $contentTypeIdentifiers = $sitemapConfiguration['passlist']['content_type_identifiers'];

            $passlistCriteria = $this->calculateCriteria(
                $locations,
                $subtrees,
                $contentTypeIdentifiers
            );
        }
        return $passlistCriteria;
    }

    private function calculateCriteria(
        array $locations,
        array $subtrees,
        array $contentTypeIdentifiers
    ) {
        $criteria = [];
        $contentTypeService = $this->repository->getContentTypeService();

        foreach ($locations as $location) {
            $criteria[] = new Criterion\LocationId($location);
        }

        foreach ($subtrees as $subtreeLocationId) {
            /** @var ?Location */
            $subtree = $this->repository->sudo(
                function (Repository $repository) use ($subtreeLocationId) {
                    $locationService = $repository->getLocationService();
                    try {
                        return $locationService->loadLocation($subtreeLocationId);
                    } catch (\eZ\Publish\API\Repository\Exceptions\NotFoundException $e) {
                        return null;
                    }
                }
            );
            if (!$subtree) {
                continue;
            }
            $criteria[] = new Criterion\Subtree($subtree->pathString);
        }

        foreach ($contentTypeIdentifiers as $contentTypeIdentifier) {
            try {
                $contentTypeService->loadContentTypeByIdentifier($contentTypeIdentifier);
            } catch (\eZ\Publish\API\Repository\Exceptions\NotFoundException $e) {
                continue;
            }
            $criteria[] = new Criterion\ContentTypeIdentifier($contentTypeIdentifier);
        }

        return $criteria;
    }
}
