<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Controller;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
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
        $robotsRules = $this->siteAccessConfigResolver->getParameterConfig('robots');
        $content = '';
        $i = 1;
        foreach ($robotsRules['user_agents'] as $userAgent => $robotsElementsRules) {
            ++$i;
            $content .= \sprintf("User-agent: %s\n", $userAgent);
            if (!empty($robotsElementsRules['crawl_delay'])) {
                $content .= \sprintf("Crawl-Delay: %s\n", $robotsElementsRules['crawl_delay']);
            }
            if (\is_array($robotsElementsRules['disallow'])) {
                foreach ($robotsElementsRules['disallow'] as $rule) {
                    $content .= \sprintf("Disallow: %s\n", $rule);
                }
            }

            if (\is_array($robotsElementsRules['allow'])) {
                foreach ($robotsElementsRules['allow'] as $rule) {
                    $content .= \sprintf("Allow: %s\n", $rule);
                }
            }

            if (\is_array($robotsRules['sitemap_urls'])) {
                foreach ($robotsRules['sitemap_urls'] as $key => $value) {
                    $content .= \sprintf("Sitemap: %s\n", $value);
                }
            }

            if (\is_array($robotsRules['sitemap_routes'])) {
                foreach ($robotsRules['sitemap_routes'] as $key => $value) {
                    $url = $this->urlGenerator->generate($value, [], UrlGeneratorInterface::ABSOLUTE_URL);
                    $content .= \sprintf("Sitemap: %s\n", $url);
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
