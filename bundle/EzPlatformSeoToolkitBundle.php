<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit;

use Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler\ContentPreviewAnalyzerPass;
use Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler\PublicForTestsCompilerPass;
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

        $container->addCompilerPass(new RichTextAnalyzerPass());
        $container->addCompilerPass(new ContentPreviewAnalyzerPass());
        $container->addCompilerPass(new PublicForTestsCompilerPass());
    }
}
