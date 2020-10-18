<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class EzPlatformSeoToolkitExtension.
 */
final class EzPlatformSeoToolkitExtension extends Extension
{
    public const ALIAS = 'codein_ez_platform_seo_toolkit';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $processor = new ConfigurationProcessor($container, $this->getAlias());

        $processor->mapConfig(
            $config,
            static function ($scopeSettings, $currentScope, ContextualizerInterface $contextualize) {
                foreach ($scopeSettings as $key => $value) {
                    $contextualize->setContextualParameter($key, $currentScope, $value);
                }
            }
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }
}
