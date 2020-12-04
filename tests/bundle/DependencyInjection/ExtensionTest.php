<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Analysis\Analyzers\TitleTagContainsKeywordAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\Analyzers\WordCountAnalyzer;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * Class ExtensionTest.
 */
final class ExtensionTest extends AbstractExtensionTestCase
{
    public function testTaggedServices()
    {
        $this->load();
        $this->compile();
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            ParentAnalyzerService::class,
             'codein_ez_platform_seo_toolkit.seo_analyzer.parent_interface'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            WordCountAnalyzer::class,
             'codein_ez_platform_seo_toolkit.seo_analyzer'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            TitleTagContainsKeywordAnalyzer::class,
             'codein_ez_platform_seo_toolkit.seo_analyzer'
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new EzPlatformSeoToolkitExtension()];
    }
}
