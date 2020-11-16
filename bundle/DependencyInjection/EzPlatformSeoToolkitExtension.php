<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * Class EzPlatformSeoToolkitExtension.
 */
final class EzPlatformSeoToolkitExtension extends Extension implements PrependExtensionInterface
{
    public const ALIAS = 'codein_ez_platform_seo_toolkit';

    public function prepend(ContainerBuilder $containerBuilder): void
    {
        if (isset($containerBuilder->getExtensions()['fos_rest'])) {
            $yamlFileLoader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources/config'));
            $yamlFileLoader->load('config.yaml');
        }
        $this->prependBazingaJsTranslationConfiguration($containerBuilder);

        $configDirectoryPath = __DIR__ . '/../Resources/config';
        $this->prependYamlConfigFile($containerBuilder, 'ezpublish', $configDirectoryPath . '/field_templates.yaml');
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
            static function ($scopeSettings, $currentScope, ContextualizerInterface $contextualize): void {
                foreach ($scopeSettings as $key => $value) {
                    $contextualize->setContextualParameter($key, $currentScope, $value);
                }
            }
        );

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('admin_ui.yaml');
        $loader->load('default_parameters.yaml');
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    private function prependYamlConfigFile(ContainerBuilder $container, $extensionName, $configFilePath): void
    {
        $config = Yaml::parse(\file_get_contents($configFilePath));
        $container->prependExtensionConfig($extensionName, $config);
    }

    private function prependBazingaJsTranslationConfiguration(ContainerBuilder $container)
    {
        $configFile = __DIR__ . '/../Resources/config/bazinga_js_translation.yml';
        $config     = Yaml::parseFile($configFile);
        $container->prependExtensionConfig('bazinga_js_translation', $config);
        $container->addResource(new FileResource($configFile));
    }
}
