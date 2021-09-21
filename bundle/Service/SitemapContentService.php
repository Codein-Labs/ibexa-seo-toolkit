<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Service;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\IbexaSeoToolkit\Helper\SitemapQueryHelper;
use DOMDocument;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\SPI\Variation\VariationHandler;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapContentService
{
    public const SPLIT_RESULTS = 'number_of_results';
    public const SPLIT_CONTENT_TYPE = 'content_type';

    private $requestStack;
    private $urlGenerator;
    private $searchService;
    private $variationHandler;
    private $sitemapQueryHelper;
    private $siteAccessConfigResolver;
    private $fieldHelper;

    public function __construct(
        SiteAccessConfigResolver $siteAccessConfigResolver,
        UrlGeneratorInterface $urlGenerator,
        SearchService $searchService,
        SitemapQueryHelper $sitemapQueryHelper,
        FieldHelper $fieldHelper,
        RequestStack $requestStack
    ) {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
        $this->urlGenerator = $urlGenerator;
        $this->searchService = $searchService;
        $this->sitemapQueryHelper = $sitemapQueryHelper;
        $this->requestStack = $requestStack;
        $this->fieldHelper = $fieldHelper;
    }

    public function generate(): DOMDocument
    {
        $sitemapConfiguration = $this->siteAccessConfigResolver->getParameterConfig('sitemap');
        $limit = $sitemapConfiguration['max_items_per_page'];

        $paginationType = self::SPLIT_RESULTS;
        if (\array_key_exists('split_by', $sitemapConfiguration)) {
            $paginationType = $sitemapConfiguration['split_by'];
        }

        $query = $this->sitemapQueryHelper->getSitemapQuery();
        $query->limit = 0;

        $count = $this->searchService->findLocations($query)->totalCount;
        $sitemap = new DOMDocument('1.0', 'UTF-8');
        $sitemap->formatOutput = true;

        if (self::SPLIT_RESULTS === $paginationType && $count > $limit) {
            $sitemap = $this->generateIndex($sitemap, $count, $limit);
        } elseif (self::SPLIT_RESULTS === $paginationType && $count <= $limit) {
            $query->limit = $count;
            $queryResults = $this->searchService->findLocations($query);

            $sitemap = $this->generateResults($sitemap, $queryResults, $sitemapConfiguration['use_images']);
        } else {
            // Case where we split by content type, no matter the limit

            $sitemap = $this->generateContentTypeIndex($sitemap);
        }

        return $sitemap;
    }

    public function generateIndex(DOMDocument $sitemap, int $numberOfResults, int $limit): DOMDocument
    {
        $maxPages = (int) \ceil($numberOfResults / $limit);

        $sitemapIndex = $sitemap->createElement('sitemapindex');
        $sitemapIndex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $sitemap->appendChild($sitemapIndex);

        for ($page = 1; $page <= $maxPages; ++$page) {
            try {
                $locString = $this->generateURLWithScheme(
                    'codein_ibexa_seo_toolkit.sitemap_page_result',
                    ['page' => $page]
                );
            } catch (\Exception $e) {
                continue;
            }

            $sitemapChild = $sitemap->createElement('sitemap');
            $loc = $sitemap->createElement('loc', $locString);
            $date = new \DateTime('c');
            $modificationDate = $date->format('c');
            $mod = $sitemap->createElement('lastmod', $modificationDate);
            $sitemapChild->appendChild($loc);
            $sitemapChild->appendChild($mod);
            $sitemapIndex->appendChild($sitemapChild);
        }

        return $sitemap;
    }

    public function generateContentTypeIndex(DOMDocument $sitemap): DOMDocument
    {
        $contentTypes = $this->siteAccessConfigResolver->getListOfContentTypes();

        $sitemapIndex = $sitemap->createElement('sitemapindex');
        $sitemapIndex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $sitemap->appendChild($sitemapIndex);

        foreach ($contentTypes as $contentType) {
            if (null === $this->generateContentTypePage($contentType)) {
                continue;
            }
            try {
                $locString = $this->generateURLWithScheme(
                    'codein_ibexa_seo_toolkit.sitemap_page_content_type',
                    ['contentTypeIdentifier' => $contentType]
                );
            } catch (\Exception $e) {
                continue;
            }

            $sitemapChild = $sitemap->createElement('sitemap');
            $loc = $sitemap->createElement('loc', $locString);
            $date = new \DateTime('c');
            $modificationDate = $date->format('c');
            $mod = $sitemap->createElement('lastmod', $modificationDate);
            $sitemapChild->appendChild($loc);
            $sitemapChild->appendChild($mod);
            $sitemapIndex->appendChild($sitemapChild);
        }

        return $sitemap;
    }

    public function generatePage($page)
    {
        $sitemapConfiguration = $this->siteAccessConfigResolver->getParameterConfig('sitemap');
        $limit = $sitemapConfiguration['max_items_per_page'];
        $useImages = $sitemapConfiguration['use_images'];

        if ((\array_key_exists('split_by', $sitemapConfiguration) && self::SPLIT_RESULTS !== $sitemapConfiguration['split_by'])
        || !\array_key_exists('split_by', $sitemapConfiguration)) {
            return null;
        }

        $query = $this->sitemapQueryHelper->getSitemapQuery();
        $query->limit = $limit;
        $query->offset = $limit * ($page - 1);

        $queryResults = $this->searchService->findLocations($query);

        $sitemap = new DOMDocument('1.0', 'UTF-8');
        $sitemap->formatOutput = true;
        $sitemap = $this->generateResults($sitemap, $queryResults, $useImages);

        return $sitemap;
    }

    public function generateResults(
        DOMDocument $sitemap,
        \eZ\Publish\API\Repository\Values\Content\Search\SearchResult $queryResults,
        bool $useImages
    ): DOMDocument {
        $sitemapUrlset = $sitemap->createElement('urlset');
        $sitemapUrlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $sitemapUrlset->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        $sitemapUrlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd');
        $sitemapUrlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $sitemap->appendChild($sitemapUrlset);

        if (0 === $queryResults->totalCount) {
            return $sitemap;
        }

        foreach ($queryResults->searchHits as $queryResult) {
            /** @var \eZ\Publish\Core\Repository\Values\Content\Location $location */
            $location = $queryResult->valueObject;

            try {
                $locString = $this->generateURLWithScheme(
                    'ez_urlalias',
                    ['location' => $location]
                );
            } catch (\Exception $e) {
                continue;
            }

            $sitemapUrl = $sitemap->createElement('url');
            $loc = $sitemap->createElement('loc', $locString);
            $date = $location->getContentInfo()->modificationDate;
            $modificationDate = $date->format('c');
            $lastMod = $sitemap->createElement('lastmod', $modificationDate);

            if ($useImages) {
                $contentFields = $location->getContent()->getFields();

                foreach ($contentFields as $field) {
                    if ('ezimage' !== $field->fieldTypeIdentifier) {
                        continue;
                    }
                    if($this->fieldHelper->isFieldEmpty($location->getContent(), $field->fieldDefIdentifier)) {
                        continue;
                    }

                    $variation = $this->variationHandler->getVariation($field, $location->getContent()->getVersionInfo(), 'original');

                    $imageBlock = $sitemap->createElement('image:image');
                    $imageLoc = $sitemap->createElement('image:loc', $variation->uri);
                    $imageBlock->appendChild($imageLoc);

                    if ($field->value->alternativeText) {
                        $imageCaption = $sitemap->createElement('image:caption', $field->value->alternativeText);
                        $imageBlock->appendChild($imageCaption);
                    }

                    $sitemapUrl->appendChild($imageBlock);
                }
            }

            $sitemapUrl->appendChild($loc);
            $sitemapUrl->appendChild($lastMod);

            $sitemapUrlset->appendChild($sitemapUrl);
        }

        return $sitemap;
    }

    public function generateContentTypePage(string $contentType, bool $tryQuery = false): ?DOMDocument
    {
        $sitemapConfiguration = $this->siteAccessConfigResolver->getParameterConfig('sitemap');
        $useImages = $sitemapConfiguration['use_images'];

        if (\array_key_exists('split_by', $sitemapConfiguration) && self::SPLIT_CONTENT_TYPE !== $sitemapConfiguration['split_by']) {
            return null;
        }

        $query = $this->sitemapQueryHelper->getSitemapQuery($contentType);
        if ($tryQuery) {
            $query->limit = 0;
        }

        $queryResults = $this->searchService->findLocations($query);

        $sitemap = new DOMDocument('1.0', 'UTF-8');
        $sitemap->formatOutput = true;
        $sitemap = $this->generateResults($sitemap, $queryResults, $useImages);

        return $sitemap;
    }

    public function setVariationHandler(VariationHandler $variationHandler)
    {
        $this->variationHandler = $variationHandler;
    }

    public function prependXSLStyleTag(DOMDocument $sitemapContent)
    {
        $sitemapContent->xmlStandalone = false;
        $xslFileRoute = $this->generateURLWithScheme('codein_ibexa_seo_toolkit.sitemap_xsl');

        $xslt = $sitemapContent->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . $xslFileRoute . '"');

        $sitemapContent->insertBefore($xslt, $sitemapContent->firstChild);

        return $sitemapContent;
    }

    private function generateURLWithScheme(string $route, $data = [])
    {
        $request = $this->requestStack->getCurrentRequest();

        $url = $this->urlGenerator->generate(
            $route,
            $data,
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $scheme = $request->getScheme();

        if (false !== mb_strpos($scheme, 'https')) {
            $url = preg_replace('/^http:/', 'https:', $url);
        }

        return $url;
    }
}
