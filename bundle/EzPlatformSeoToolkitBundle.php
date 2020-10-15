<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit;

use Codein\eZPlatformSeoToolkit\Analyzer\ContentAnalyzerInterface;
use Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler\RichTextAnalyzerPass;
use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzPlatformSeoToolkitBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EzPlatformSeoToolkitExtension();
        }

        return $this->extension;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerForAutoconfiguration(ContentAnalyzerInterface::class)
            ->addTag('codein_ez_platform_seo_toolkit.seo_analyzer.richtext')
        ;
        $container->addCompilerPass(new RichTextAnalyzerPass());
    }
}
