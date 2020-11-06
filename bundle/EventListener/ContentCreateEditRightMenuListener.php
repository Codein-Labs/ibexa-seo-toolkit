<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentCreateEditRightMenuListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ConfigureMenuEvent::CONTENT_CREATE_SIDEBAR_RIGHT => ['onMenuConfigure', 0],
            ConfigureMenuEvent::CONTENT_EDIT_SIDEBAR_RIGHT => ['onMenuConfigure', 0],
        ];
    }

    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild(
            'menu_item_seo_analyzer',
            [
                'label' => 'codein_seo_toolkit.content_create_edit.menu_label',
                'uri' => '#codein-seo-move-in',
                'extras' => [
                    'icon_path' => '/bundles/codein-ezplatformseotoolkit/images/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
                    'translation_domain' => 'codein_seo_toolkit',
                ],
            ]
        );
    }
}
