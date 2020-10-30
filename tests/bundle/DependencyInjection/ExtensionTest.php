<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analyzer\ContentPreviewParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Analyzer\RichText\WordCountAnalyzer;
use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use FOS\RestBundle\DependencyInjection\FOSRestExtension;
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

    protected function getContainerExtensions(): array
    {
        $this->container->setParameter('kernel.debug', false);

        return [new EzPlatformSeoToolkitExtension(), new FOSRestExtension()];
    }
}
