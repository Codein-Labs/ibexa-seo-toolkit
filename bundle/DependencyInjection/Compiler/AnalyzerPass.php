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

    public function process(ContainerBuilder $containerBuilder): void
    {
        if (!$containerBuilder->has(ParentAnalyzerService::class)) {
            return;
        }
        $blockedAnalysis = [];
        $analyzerDefinition = $containerBuilder->getDefinition(ParentAnalyzerService::class);

        $allFieldAnalyzers = $containerBuilder->findTaggedServiceIds(self::TAG_NAME);
        $analysisParam = \sprintf('%s.default.analysis', IbexaSeoToolkitExtension::ALIAS);
        if (true === $containerBuilder->hasParameter($analysisParam)) {
            $blockedAnalysis = $containerBuilder->getParameter($analysisParam)['blocklist'];
        }

        foreach (\array_keys($allFieldAnalyzers) as $id) {
            if (!\in_array($id, $blockedAnalysis, true)) {
                $analyzerDefinition->addMethodCall('addAnalyzer', [$id, new Reference($id)]);
            }
        }
    }
}
