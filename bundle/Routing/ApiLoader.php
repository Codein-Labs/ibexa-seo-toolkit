<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class ApiLoader.
 */
final class ApiLoader extends Loader
{
    private const RESOURCE = '@EzPlatformSeoToolkitBundle/Resources/config/routes.yaml';
    private const TYPE = 'yaml';
    private const ROUTE_PREFIX = '/api/seo';

    public function load($resource, $type = null)
    {
        $routeCollection = new RouteCollection();

        $importedRoutes = $this->import(self::RESOURCE, self::TYPE);

        $routeCollection->addCollection($importedRoutes);
        $routeCollection->addPrefix(self::ROUTE_PREFIX);

        return $routeCollection;
    }

    public function supports($resource, $type = null)
    {
        return 'api_ez_platform_seo' === $type;
    }
}
