<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Helper;

use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use eZ\Publish\Core\MVC\ConfigResolverInterface;

/**
 * Class SiteAccessConfigResolver.
 */
final class SiteAccessConfigResolver
{
    private $configResolver;

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function getParameterConfig(string $paramName)
    {
        return $this->configResolver->getParameter($paramName, EzPlatformSeoToolkitExtension::class);
    }
}
