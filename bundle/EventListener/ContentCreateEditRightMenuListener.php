<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformPageBuilderBundle\Menu\Event\PageBuilderConfigureMenuEventName;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContentCreateEditRightMenuListener implements EventSubscriberInterface
{
    public function onMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void
    {
        $menuItem = $configureMenuEvent->getMenu();

        $menuItem->addChild(
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

    public function onPageBuilderMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void 
    {
        $root = $configureMenuEvent->getMenu();
        $root->addChild(
            'Seo analyzer',
            [
                'attributes' => [
                    // 'class' => 'ez-btn--extra-actions',
                    'id' => 'menu_item_seo_analyzer-tab',
                    'data-actions' => 'seo-analyzer',
                    'title' => 'test',
                ],
                'uri' => '#codein-seo-move-in',
                'extras' => [
                    'translation_domain' => 'codein_seo_toolkit',
                    'icon_path' => '/bundles/codein-ezplatformseotoolkit/images/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
                    // 'template' => 'EzSystemsDateBasedPublisherBundle::publish_later_widget.html.twig',
                    'orderNumber' => 20,
                ],
            ]
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            ConfigureMenuEvent::CONTENT_EDIT_SIDEBAR_RIGHT => ['onMenuConfigure', 0],
            PageBuilderConfigureMenuEventName::PAGE_BUILDER_INFOBAR_EDIT_MODE_ACTIONS => ['onPageBuilderMenuConfigure', 0]
        ];
    }
}
