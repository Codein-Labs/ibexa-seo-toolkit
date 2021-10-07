<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Configuration.
 */
final class Configuration extends SiteAccessConfiguration
{
    /**
     * {@inheritdoc}
     * @psalm-suppress TooFewArguments
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder(IbexaSeoToolkitExtension::ALIAS);
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older.
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root(IbexaSeoToolkitExtension::ALIAS);
        }

        $nodeBuilder = $this->generateScopeBaseNode($rootNode);
        $this->addAnalysisSection($nodeBuilder);
        $this->addSitemapSection($nodeBuilder);
        $this->addRobotsSection($nodeBuilder);
        $this->addMetasSection($nodeBuilder);
        $this->addLinksSection($nodeBuilder);
        $this->addHreflangSection($nodeBuilder);
        $this->addInternalLinksHostnamesSection($nodeBuilder);
        $nodeBuilder->end();

        return $treeBuilder;
    }

    private function addAnalysisSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('analysis')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('content_types')
                            ->validate()
                            ->ifTrue(
                                function ($array) {
                                    $notValid = false;
                                    foreach ($array as $key => $value) {
                                        if (!\is_string($key) || empty($key)) {
                                            $notValid = true;
                                            break;
                                        }
                                        if (1 !== \preg_match('/^[[:alnum:]_]+$/', $key)) {
                                            $notValid = true;
                                            break;
                                        }
                                    }

                                    return $notValid;
                                }
                            )
                            ->thenInvalid("Content type identifier may only contain letters from 'a' to 'z', numbers and underscores.")
                            ->end()
                        ->arrayPrototype()
                            ->children()
                                ->arrayNode('richtext_fields')
                                    ->cannotBeEmpty()
                                    ->scalarPrototype()

                                        ->info('Specify rich text fields to analyze in the content type.')
                                        ->validate()
                                        ->ifTrue(
                                            function ($value) {
                                                $notValid = false;

                                                if (!\is_string($value) || empty($value)) {
                                                    $notValid = true;
                                                }
                                                if (1 !== \preg_match('/^[[:alnum:]_]+$/', $value)) {
                                                    $notValid = true;
                                                }

                                                return $notValid;
                                            }
                                        )
                                        ->thenInvalid("Field identifiers may only contain letters from 'a' to 'z', numbers and underscores.")
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('blocklist')
                                    ->scalarPrototype()
                                    ->info('Specify some analyzer services identifier to block for this content type.')
                                    ->example('ibexa_seo.rich_text.one_h1_max_analyzer')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                    ->end()
                    ->arrayNode('siteaccesses_blocklist')
                        ->scalarPrototype()
                        ->info("Specify which siteaccesses won't be suggested for analysis. Typically admin siteaccesses.")
                        ->example('admin_group')
                        ->end()
                    ->end()
                    ->arrayNode('blocklist')
                        ->scalarPrototype()
                        ->info('Specify some analyzer services identifier to block.')
                        ->example('ibexa_seo.rich_text.one_h1_max_analyzer')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    private function addSitemapSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('sitemap')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('split_by')->defaultNull()->values(['number_of_results', 'content_type'])->defaultValue('content_type')->example('number_of_results')->end()
                    ->scalarNode('max_items_per_page')->defaultValue(1000)->end()
                    ->booleanNode('use_images')->defaultValue(false)->end()
                    ->arrayNode('blocklist')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('locations')
                                ->scalarPrototype()->example(2)->end()
                            ->end()
                            ->arrayNode('subtrees')
                                ->scalarPrototype()->example(45)->end()
                            ->end()
                            ->arrayNode('content_type_identifiers')
                                ->scalarPrototype()->example('header')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('passlist')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('locations')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('subtrees')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('content_type_identifiers')
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    private function addRobotsSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('robots')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('user_agents')
            ->defaultValue([])
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('crawl_delay')->defaultNull()->end()
                        ->arrayNode('disallow')
                            ->defaultValue([])
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('allow')
                            ->defaultValue([])
                            ->scalarPrototype()->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
            ->arrayNode('sitemap_routes')
            ->defaultValue([])

            ->scalarPrototype()->end()
            ->end()
            ->arrayNode('sitemap_urls')
            ->defaultValue([])
            ->validate()
            ->ifTrue(
             function ($array) {
                 $notValid = false;
                 foreach ($array as $key => $value) {
                     if (false === \filter_var($value, \FILTER_VALIDATE_URL)) {
                         $notValid = true;
                         break;
                     }
                 }

                 return $notValid;
             }
            )
            ->thenInvalid('This value is not a valid URL.')
            ->end()
            ->scalarPrototype()->end()
            ->end()
            ->end()
            ->end()
        ;

        return $this;
    }

    private function addMetasSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('metas')
                ->addDefaultsIfNotSet()
                ->info('Metas + Field type meta configuration.')
                ->children()
                    ->arrayNode('default_metas')
                       ->defaultValue([])
                       ->example('[ value: next ]')
                       ->scalarPrototype()
                       ->end()
                    ->end()

                    ->arrayNode('field_type_metas')
                        ->arrayPrototype()
                        ->children()
                                ->scalarNode('name')->defaultNull()->example('title')->end()
                                ->scalarNode('label')->defaultNull()->example('bo.meta_label')->end()
                                ->scalarNode('default_pattern')->defaultNull()->example('<title|name>')->end()
                                ->arrayNode('default_choices')->scalarPrototype()->end()->example('[index, noindex]')->end()
                        ->end()
                        ->end()
                    ->end()

                ->end()
            ->end()
        ;

        return $this;
    }

    private function addLinksSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('links')
                ->arrayPrototype()
                    ->children()
                        ->arrayNode('attrs')
                            ->defaultValue([])
                            ->example('[ { key: rel, value: next }, { key: title, value: title ]')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('key')->end()
                                    ->scalarNode('value')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('href')->isRequired()
                            ->children()
                                ->integerNode('location_id')->example(2)->end()
                                ->arrayNode('asset')->example('/test/favicon.ico')
                                    ->beforeNormalization()
                                        ->ifString()
                                        ->then(
                                            function ($value) {
                                                return ['path' => $value];
                                            }
                                        )
                                    ->end()
                                    ->children()
                                        ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('package')->example('ezdesign')->end()
                                    ->end()
                                ->end()
                                ->scalarNode('route')->example('main')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    private function addHreflangSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('hreflang')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')
                    ->defaultFalse()
                    ->info('If true, hreflang link tags will be generated automatically according to the languages already configured.')
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    private function addInternalLinksHostnamesSection(NodeBuilder $nodeBuilder): self
    {
        $nodeBuilder
            ->arrayNode('internal_links_hostnames')
                ->info('Hostnames to consider as internal links during analysis.')
                ->defaultValue([])
                ->scalarPrototype()->end()
            ->end()
        ;
        return $this;
    }
}
