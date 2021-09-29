<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;

class PageBuilderMenuListener extends AbstractToolbarMenuListener
{
    public function onPageBuilderMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void
    {
        $this->onMenuConfigure($configureMenuEvent);
    }
}
