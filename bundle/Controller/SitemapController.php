<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Controller;

use Codein\IbexaSeoToolkit\Service\SitemapContentService;
use EzSystems\PlatformHttpCacheBundle\ResponseConfigurator\ResponseCacheConfigurator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class SitemapController.
 */
final class SitemapController extends AbstractController
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

        $sitemapContent = $this->sitemapContentService->prependXSLStyleTag($sitemapContent);

        $response = new Response();
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        
        return $response->setContent($sitemapContent->saveXML());
    }

    public function page($page)
    {
        $sitemapContent = $this->sitemapContentService->generatePage($page);
        if (!$sitemapContent) {
            throw new HttpException(404, 'Not found');
        }
        $sitemapContent = $this->sitemapContentService->prependXSLStyleTag($sitemapContent);

        $response = new Response();
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        return $response->setContent($sitemapContent->saveXML());
    }

    public function contentTypePage($contentTypeIdentifier)
    {
        $sitemapContent = $this->sitemapContentService->generateContentTypePage($contentTypeIdentifier);
        if (!$sitemapContent) {
            throw new HttpException(404, 'Not found');
        }
        $sitemapContent = $this->sitemapContentService->prependXSLStyleTag($sitemapContent);

        $response = new Response();
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        return $response->setContent($sitemapContent->saveXML());
    }

    public function xsltStylesheet(Request $request)
    {
        $xslView = $this->renderView('@CodeinIbexaSeoToolkit/sitemap/sitemap.xsl.twig', [
            'referer' => $request->headers->get('referer')
        ]);
        $xslDocument = new \DOMDocument('1.0', 'utf-8');
        $xslDocument->loadXML($xslView);

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        $response->headers->set('Content-Type', 'text/xsl');

        return $response->setContent($xslDocument->saveXML());
    }
}
