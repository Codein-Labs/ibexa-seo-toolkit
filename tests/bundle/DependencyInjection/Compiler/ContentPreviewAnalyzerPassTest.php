<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analyzer\ContentPreviewParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler\ContentPreviewAnalyzerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ContentPreviewAnalyzerPassTest.
 */
class ContentPreviewAnalyzerPassTest extends AbstractCompilerPassTestCase
{
    const ANALYZER_ADDEDER_ID = 'alanyzer.example.id';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setDefinition(ContentPreviewParentAnalyzerService::class, new Definition());
    }

    public function testAddAnalyzer()
    {
        $definition = new Definition();
        $definition->addTag(ContentPreviewAnalyzerPass::TAG_NAME);

        $this->setDefinition(self::ANALYZER_ADDEDER_ID, $definition);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            ContentPreviewParentAnalyzerService::class,
             'addAnalyzer',
             [new Reference(self::ANALYZER_ADDEDER_ID)]
         );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ContentPreviewAnalyzerPass());
    }
}
