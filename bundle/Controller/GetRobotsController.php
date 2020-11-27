<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use EzSystems\PlatformHttpCacheBundle\ResponseConfigurator\ResponseCacheConfigurator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class GetRobotsController.
 */
final class GetRobotsController
{
    private $siteAccessConfigResolver;
    private $urlGenerator;
    private $responseCacheConfigurator;

    public function __construct(
        SiteAccessConfigResolver $siteAccessConfigResolver,
        UrlGeneratorInterface $urlGenerator,
        ResponseCacheConfigurator $responseCacheConfigurator
    ) {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
        $this->urlGenerator = $urlGenerator;
        $this->responseCacheConfigurator = $responseCacheConfigurator;
    }

    public function __invoke()
    {
        $robotsRulesPerUserAgent = $this->siteAccessConfigResolver->getParameterConfig('robots');
        $content = '';
        $i = 1;
        foreach ($robotsRulesPerUserAgent as $userAgent => $robotsElementsRules) {
            ++$i;
            $content .= "User-agent: {$userAgent}\n";
            if (!empty($robotsElementsRules['crawl-delay'])) {
                $content .= "Crawl-Delay: {$robotsElementsRules['crawl-delay']}";
            }
            if (\is_array($robotsElementsRules['disallow'])) {
                foreach ($robotsElementsRules['disallow'] as $rule) {
                    $content .= "Disallow: {$rule}\n";
                }
            }

            if (\is_array($robotsElementsRules['allow'])) {
                foreach ($robotsElementsRules['allow'] as $rule) {
                    $content .= "Allow: {$rule}\n";
                }
            }

            if (\is_array($robotsElementsRules['sitemap'])) {
                foreach ($robotsElementsRules['sitemap']  as $key => $value) {
                    if ('route' === $key) {
                        $url = $this->urlGenerator->generate($value, [], UrlGeneratorInterface::ABSOLUTE_URL);
                        $content .= "Sitemap: {$url}\n";
                    }
                    if ('url' === $key) {
                        $content .= "Sitemap: {$value}\n";
                    }
                }
            }
            $content .= ($i !== \count($robotsElementsRules)) ? "\n" : '';
        }
        $response = new Response();
        $this->responseCacheConfigurator->setSharedMaxAge($response);
        $response->headers->set('Content-Type', 'text/plain');

        return $response->setContent($content);
    }
}
