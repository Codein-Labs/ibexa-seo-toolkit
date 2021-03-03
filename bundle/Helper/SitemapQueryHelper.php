<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Helper;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Repository;

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

    public function __construct(
        Repository $repository,
        SiteAccessConfigResolver $siteAccessConfigResolver
    ) {
        $this->repository = $repository;
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    /**
     * Get Sitemap Query.
     *
     * @param string $specificContentType for content type sitemap cut
     * @return LocationQuery
     */
    public function getSitemapQuery(string $specificContentType = ''): LocationQuery
    {
        $query = new LocationQuery();

        $baseCriteria = [
            new Criterion\Visibility(Criterion\Visibility::VISIBLE),
        ];

        if (\strlen($specificContentType)) {
            $baseCriteria[] = new Criterion\ContentTypeIdentifier($specificContentType);
        }

        $query->query = new Criterion\LogicalAnd(
            \array_merge(
                $baseCriteria,
                $this->createGenericFilterCriteria()
            )
        );
        $query->sortClauses = [
            new SortClause\DatePublished(LocationQuery::SORT_DESC),
        ];

        return $query;
    }

    public function generateSiteaccessRootLocationCriterion(): array
    {
        $siteaccessRootLocation = $this->repository->getLocationService()->loadLocation(
            $this->siteAccessConfigResolver->getConfigResolver()->getParameter('content.tree_root.location_id')
        );

        return [new Criterion\Subtree($siteaccessRootLocation->pathString)];
    }

    private function createGenericFilterCriteria(): array
    {
        $sitemapConfiguration = $this->siteAccessConfigResolver->getParameterConfig('sitemap');

        $blocklistCriteria = [];
        $passlistCriteria = [];

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

        return \array_merge(
            $this->generateSiteaccessRootLocationCriterion(),
            $blocklistCriteria,
            $passlistCriteria,
        );
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
