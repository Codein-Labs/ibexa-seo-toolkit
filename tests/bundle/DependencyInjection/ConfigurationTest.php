<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Tests\DependencyInjection;

use Codein\eZPlatformSeoToolkit\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class ConfigurationTest.
 */
final class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Processor
     */
    private $processor;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testDefaultConfig()
    {
        $this->runDefaultConfigTests();
    }

    public function runDefaultConfigTests()
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();
        $config = $this->processor->processConfiguration($this->configuration, [
            'codein_ez_platform_seo_toolkit' => [
                'system' => [
                    'default' => [],
                ],
            ],
        ]);
        $this->assertSame([
            'system' => [
                'default' => [
                    'analysis' => [
                        'content_types' => [],
                        'blocklist' => [],
                    ],
                    'sitemap' => [
                        'split_by' => 'content_type',
                        'blocklist' => [
                            'locations' => [],
                            'subtrees' => [],
                            'content_type_identifiers' => [],
                        ],
                        'passlist' => [
                            'locations' => [],
                            'subtrees' => [],
                            'content_type_identifiers' => [],
                        ],
                    ],
                    'robots' => [
                        'user_agents' => [],
                        'sitemap_routes' => [],
                        'sitemap_urls' => []
                    ],
                    'metas' => [
                        'default_metas' => [],
                        'field_type_metas' => [],
                    ],
                    'links' => [],
                    'hreflang' => [
                        'enabled' => false,
                    ],
                ],
            ],
        ], $config);
        $this->assertInstanceOf(ConfigurationInterface::class, $this->configuration);
        $this->assertInstanceOf(TreeBuilder::class, $treeBuilder);
    }
}
