<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class ApiLoader.
 */
final class ApiLoader extends Loader
{
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        $resource = '@EzPlatformSeoToolkitBundle/Resources/config/routes.yaml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $collection->addCollection($importedRoutes);

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'api_ez_platform_seo' === $type;
    }
}
