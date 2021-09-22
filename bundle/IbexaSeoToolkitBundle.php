<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit;

use Codein\IbexaSeoToolkit\Analysis\AnalyzerInterface;
use Codein\IbexaSeoToolkit\DependencyInjection\Compiler\AnalyzerPass;
use Codein\IbexaSeoToolkit\DependencyInjection\IbexaSeoToolkitExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IbexaSeoToolkitBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new IbexaSeoToolkitExtension();
        }

        return $this->extension;
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->registerForAutoconfiguration(AnalyzerInterface::class)
            ->addTag(AnalyzerPass::TAG_NAME);

        $container->addCompilerPass(new AnalyzerPass());
    }
}
