<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler;

use Codein\eZPlatformSeoToolkit\Analyzer\ContentPreviewAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ContentPreviewAnalyzerPass.
 */
final class ContentPreviewAnalyzerPass implements CompilerPassInterface
{
    const TAG_NAME = EzPlatformSeoToolkitExtension::ALIAS . '.seo_analyzer.content_preview';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ContentPreviewAnalyzerService::class)) {
            return;
        }
        $analysis = [];
        $analyzerDefinition = $container->getDefinition(ContentPreviewAnalyzerService::class);

        $allFieldAnalyzers = $container->findTaggedServiceIds(self::TAG_NAME);
        $analysisParam = \sprintf('%s.default.analysis', EzPlatformSeoToolkitExtension::ALIAS);
        if (true === $container->hasParameter($analysisParam)) {
            $analysis = $container->getParameter($analysisParam)['blocklist'];
        }

        foreach ($allFieldAnalyzers as $id => $tags) {
            if (false === \in_array($id, $analysis, true)) {
                $analyzerDefinition->addMethodCall('addAnalyzer', [new Reference($id)]);
            }
        }
    }
}
