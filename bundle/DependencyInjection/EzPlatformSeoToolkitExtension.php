<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * Class EzPlatformSeoToolkitExtension.
 */
final class EzPlatformSeoToolkitExtension extends Extension implements PrependExtensionInterface
{
    public const ALIAS = 'codein_ez_platform_seo_toolkit';

    public function prepend(ContainerBuilder $container)
    {
        if (isset($container->getExtensions()['fos_rest'])) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('config.yaml');
        }
    }

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
        $loader->load('admin_ui.yaml');
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->prependBazingaJsTranslationConfiguration($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function prependBazingaJsTranslationConfiguration(ContainerBuilder $container)
    {
        $configFile = __DIR__ . '/../Resources/config/bazinga_js_translation.yml';
        $config = Yaml::parseFile($configFile);
        $container->prependExtensionConfig('bazinga_js_translation', $config);
        $container->addResource(new FileResource($configFile));
    }
    
}
