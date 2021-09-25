<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Tests\DependencyInjection;

use Codein\IbexaSeoToolkit\Analysis\Analyzers\TitleTagContainsKeywordAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\Analyzers\WordCountAnalyzer;
use Codein\IbexaSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\IbexaSeoToolkit\DependencyInjection\IbexaSeoToolkitExtension;
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
             'codein_ibexa_seo_toolkit.seo_analyzer.parent_interface'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            WordCountAnalyzer::class,
             'codein_ibexa_seo_toolkit.seo_analyzer'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            TitleTagContainsKeywordAnalyzer::class,
             'codein_ibexa_seo_toolkit.seo_analyzer'
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new IbexaSeoToolkitExtension()];
    }
}
