<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventListener;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;

class PageBuilderMenuListener
{
    private $siteAccessConfigResolver;

    public function __construct(SiteAccessConfigResolver $siteAccessConfigResolver)
    {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    public function onPageBuilderMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void
    {
        $currentContentTypeIdentifier = $configureMenuEvent->getOptions()['content']->getContentType()->identifier;
        $analysisConfiguration = $this->siteAccessConfigResolver->getParameterConfig('analysis');
        if (!\array_key_exists('content_types', $analysisConfiguration)) {
            return;
        }

        if (!\in_array($currentContentTypeIdentifier, array_keys($analysisConfiguration['content_types']), true)) {
            return;
        }

        $root = $configureMenuEvent->getMenu();
        $root->addChild(
            'menu_item_seo_analyzer',
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
                    'icon_path' => '/bundles/codein-ibexaseotoolkit/images/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
                    // 'template' => 'EzSystemsDateBasedPublisherBundle::publish_later_widget.html.twig',
                    'orderNumber' => 20,
                ],
            ]
        );
    }
}
