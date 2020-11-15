<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler;

use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RichTextAnalyzerPass.
 */
final class RichTextAnalyzerPass implements CompilerPassInterface
{
    private const TAG_NAME = EzPlatformSeoToolkitExtension::ALIAS . '.seo_analyzer.rich_text';

    public function process(ContainerBuilder $containerBuilder): void
    {
        if (!$containerBuilder->has(RichTextParentAnalyzerService::class)) {
            return;
        }
        $analysis = [];
        $analyzerDefinition = $containerBuilder->getDefinition(RichTextParentAnalyzerService::class);

        $allFieldAnalyzers = $containerBuilder->findTaggedServiceIds(self::TAG_NAME);
        $analysisParam = \sprintf('%s.default.analysis', EzPlatformSeoToolkitExtension::ALIAS);
        if (true === $containerBuilder->hasParameter($analysisParam)) {
            $analysis = $containerBuilder->getParameter($analysisParam)['blocklist'];
        }

        foreach ($allFieldAnalyzers as $id => $tags) {
            if (false === \in_array($id, $analysis, true)) {
                $analyzerDefinition->addMethodCall('addAnalyzer', [new Reference($id)]);
            }
        }
    }
}
