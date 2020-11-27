<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler\RichTextAnalyzerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RichTextAnalyzerPassTest.
 */
class RichTextAnalyzerPassTest extends AbstractCompilerPassTestCase
{
    const ANALYZER_ADDER_ID = 'analyzer.example.id';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setDefinition(RichTextParentAnalyzerService::class, new Definition());
    }

    public function testAddAnalyzer()
    {
        $definition = new Definition();
        $definition->addTag(RichTextAnalyzerPass::TAG_NAME);

        $this->setDefinition(self::ANALYZER_ADDER_ID , $definition);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            RichTextParentAnalyzerService::class,
             'addAnalyzer',
             [new Reference(self::ANALYZER_ADDER_ID )]
         );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RichTextAnalyzerPass());
    }
}
