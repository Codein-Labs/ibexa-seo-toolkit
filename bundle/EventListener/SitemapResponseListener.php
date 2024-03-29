<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class SitemapResponseListener
{
    const ROUTES = [
        'codein_ibexa_seo_toolkit.sitemap',
        'codein_ibexa_seo_toolkit.sitemap_page_result',
        'codein_ibexa_seo_toolkit.sitemap_page_content_type',
    ];

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if (\in_array($request->attributes->get('_route'), self::ROUTES, true)) {
            $response->setPublic();
            $response->setMaxAge(600);
            $response->headers->set('Content-Type', 'text/xml');
        }
    }
}
