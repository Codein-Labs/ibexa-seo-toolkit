<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventListener;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContentCreateEditRightMenuListener implements EventSubscriberInterface
{
    private $siteAccessConfigResolver;

    public function __construct(SiteAccessConfigResolver $siteAccessConfigResolver)
    {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    public function onMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void
    {
        $currentContentTypeIdentifier = $configureMenuEvent->getOptions()['content_type']->identifier;
        $analysisConfiguration = $this->siteAccessConfigResolver->getParameterConfig('analysis');

        $menuItem = $configureMenuEvent->getMenu();

        if (!\array_key_exists('content_types', $analysisConfiguration) || !\in_array($currentContentTypeIdentifier, array_keys($analysisConfiguration['content_types']), true)) {
            $menuItem->addChild(
                'menu_item_seo_analyzer_not_configured',
                [
                    'label' => 'codein_seo_toolkit.content_create_edit.menu_label',
                    'uri' => '#codein-seo-not-configured',
                    'extras' => [
                        'icon_path' => '/bundles/codein-ibexaseotoolkit/images/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
                        'translation_domain' => 'codein_seo_toolkit',
                    ],
                ]
            );

            return;
        }

        $menuItem->addChild(
            'menu_item_seo_analyzer',
            [
                'label' => 'codein_seo_toolkit.content_create_edit.menu_label',
                'uri' => '#codein-seo-move-in',
                'extras' => [
                    'icon_path' => '/bundles/codein-ibexaseotoolkit/images/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
                    'translation_domain' => 'codein_seo_toolkit',
                ],
            ]
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            ConfigureMenuEvent::CONTENT_EDIT_SIDEBAR_RIGHT => ['onMenuConfigure', 0],
        ];
    }
}
