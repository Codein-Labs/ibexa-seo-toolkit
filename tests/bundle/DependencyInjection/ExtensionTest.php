<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analyzer\ContentPreviewParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Analyzer\RichText\WordCountAnalyzer;
use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * Class ExtensionTest.
 */
final class ExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    : array
    {
        return [new EzPlatformSeoToolkitExtension()];
    }

    public function testTaggedServices()
    {
        $this->load();
        $this->compile();
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            RichTextParentAnalyzerService::class,
             'codein_ez_platform_seo_toolkit.seo_analyzer.parent'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            ContentPreviewParentAnalyzerService::class,
             'codein_ez_platform_seo_toolkit.seo_analyzer.parent'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            WordCountAnalyzer::class,
             'codein_ez_platform_seo_toolkit.seo_analyzer.rich_text'
        );
    }
}