<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit;

use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
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
}
