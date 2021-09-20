<?php

namespace Codein\IbexaSeoToolkit\EventListener;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;

class PageBuilderMenuListener extends AbstractToolbarMenuListener
{
    public function onPageBuilderMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void
    {
        $this->onMenuConfigure($configureMenuEvent);
    }
}
