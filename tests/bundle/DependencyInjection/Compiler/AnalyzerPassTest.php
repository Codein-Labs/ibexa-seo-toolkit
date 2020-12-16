<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Tests\DependencyInjection\Compiler;

use Codein\eZPlatformSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler\AnalyzerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RichTextAnalyzerPassTest.
 */
class AnalyzerPassTest extends AbstractCompilerPassTestCase
{
    const ANALYZER_ADDER_ID = 'analyzer.example.id';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setDefinition(ParentAnalyzerService::class, new Definition());
    }

    public function testAddAnalyzer()
    {
        $definition = new Definition();
        $definition->addTag(AnalyzerPass::TAG_NAME);

        $this->setDefinition(self::ANALYZER_ADDER_ID , $definition);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            ParentAnalyzerService::class,
             'addAnalyzer',
             [self::ANALYZER_ADDER_ID, new Reference(self::ANALYZER_ADDER_ID )]
         );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AnalyzerPass());
    }
}
