<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler;

use Codein\eZPlatformSeoToolkit\Analyzer\RichTextAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RichTextAnalyzerPass.
 */
final class RichTextAnalyzerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(RichTextAnalyzerService::class)) {
            return;
        }

        $analyzerDefinition = $container->getDefinition(RichTextAnalyzerService::class);

        $allFieldAnalyzers = $container->findTaggedServiceIds(\sprintf('%s.seo_analyzer.richtext', EzPlatformSeoToolkitExtension::EXTENSION_ALIAS));
        $analysis = $container->getParameter(\sprintf('%s.default.analysis', EzPlatformSeoToolkitExtension::EXTENSION_ALIAS))['blocklist'];
        foreach ($allFieldAnalyzers as $id => $tags) {
            if (false === \in_array($id, $analysis, true)) {
                $analyzerDefinition->addMethodCall('addAnalyzer', [new Reference($id)]);
            }
        }
    }
}
