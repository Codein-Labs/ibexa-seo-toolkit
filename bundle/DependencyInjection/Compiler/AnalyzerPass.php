<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\DependencyInjection\Compiler;

use Codein\IbexaSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\IbexaSeoToolkit\DependencyInjection\IbexaSeoToolkitExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AnalyzerPass.
 */
final class AnalyzerPass implements CompilerPassInterface
{
    public const TAG_NAME = IbexaSeoToolkitExtension::ALIAS . '.seo_analyzer';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ParentAnalyzerService::class)) {
            return;
        }
        $blockedAnalysis = [];
        $analyzerDefinition = $container->getDefinition(ParentAnalyzerService::class);

        $allFieldAnalyzers = $container->findTaggedServiceIds(self::TAG_NAME);
        $analysisParam = \sprintf('%s.default.analysis', IbexaSeoToolkitExtension::ALIAS);
        if (true === $container->hasParameter($analysisParam)) {
            $blockedAnalysis = $container->getParameter($analysisParam)['blocklist'];
        }

        foreach (\array_keys($allFieldAnalyzers) as $id) {
            if (!\in_array($id, $blockedAnalysis, true)) {
                $analyzerDefinition->addMethodCall('addAnalyzer', [$id, new Reference($id)]);
            }
        }
    }
}
