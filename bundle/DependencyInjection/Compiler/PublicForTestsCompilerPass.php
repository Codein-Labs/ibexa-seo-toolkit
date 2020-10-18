<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class PublicForTestsCompilerPass.
 */
final class PublicForTestsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        if (!$this->isPHPUnit()) {
            return;
        }

        foreach ($containerBuilder->getDefinitions() as $definition) {
            $definition->setPublic(true);
        }

        foreach ($containerBuilder->getAliases() as $definition) {
            $definition->setPublic(true);
        }
    }

    private function isPHPUnit(): bool
    {return true;
        // there constants are defined by PHPUnit
        return \defined('PHPUNIT_COMPOSER_INSTALL') || \defined('__PHPUNIT_PHAR__');
    }
}
