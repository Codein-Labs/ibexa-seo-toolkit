<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Service\SitemapContentService;
use EzSystems\PlatformHttpCacheBundle\ResponseConfigurator\ResponseCacheConfigurator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class SitemapController.
 */
final class SitemapController extends Controller
{
    private $sitemapContentService;
    private $responseCacheConfigurator;

    public function __construct(
        SitemapContentService $sitemapContentService,
        ResponseCacheConfigurator $responseCacheConfigurator
    ) {
        $this->sitemapContentService = $sitemapContentService;
        $this->responseCacheConfigurator = $responseCacheConfigurator;
    }

    public function index()
    {
        $sitemapContent = $this->sitemapContentService->generate();

        $sitemapContent = $this->styleXML($sitemapContent);

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        $response->headers->set('Content-Type', 'text/xml');

        return $response->setContent($sitemapContent->saveXML());
    }

    public function page($page)
    {
        $sitemapContent = $this->sitemapContentService->generatePage($page);

        $sitemapContent = $this->styleXML($sitemapContent);

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        $response->headers->set('Content-Type', 'text/xml');

        return $response->setContent($sitemapContent->saveXML());
    }

    public function contentTypePage($contentTypeIdentifier)
    {
        $sitemapContent = $this->sitemapContentService->generateContentTypePage($contentTypeIdentifier);

        $sitemapContent = $this->styleXML($sitemapContent);

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        $response->headers->set('Content-Type', 'text/xml');

        return $response->setContent($sitemapContent->saveXML());
    }

    public function xsltStylesheet()
    {
        $xslView = $this->renderView('EzPlatformSeoToolkitBundle:sitemap:sitemap.xsl.twig');
        $xslDocument = new \DOMDocument('1.0', 'utf-8');
        $xslDocument->loadXML($xslView);

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        $response->headers->set('Content-Type', 'text/xsl');

        return $response->setContent($xslDocument->saveXML());
    }

    private function styleXML(\DOMDocument $sitemapContent)
    {
        $sitemapContent->xmlStandalone = false;
        $xslDocument = new \DOMDocument('1.0', 'utf-8');
        $xslFileRoute = $this->generateUrl(
            'codein_ez_platform_seo_toolkit.sitemap_xsl',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $xslt = $sitemapContent->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . $xslFileRoute . '"');

        $sitemapContent->insertBefore($xslt, $sitemapContent->firstChild);

        return $sitemapContent;
    }
}
