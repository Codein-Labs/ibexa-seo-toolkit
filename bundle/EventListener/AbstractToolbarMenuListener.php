<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventListener;

use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use eZ\Publish\API\Repository\Values\Content\Content;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;

abstract class AbstractToolbarMenuListener
{
    protected $siteAccessConfigResolver;

    public function __construct(SiteAccessConfigResolver $siteAccessConfigResolver)
    {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    public function onMenuConfigure(ConfigureMenuEvent $configureMenuEvent): void
    {
        $currentContentTypeIdentifier = $this->getCurrentContentTypeIdentifier($configureMenuEvent->getOptions());
        if (null === $currentContentTypeIdentifier) {
            return;
        }

        $analysisConfiguration = $this->siteAccessConfigResolver->getParameterConfig('analysis');

        $menuItem = $configureMenuEvent->getMenu();

        if (!\array_key_exists('content_types', $analysisConfiguration) || !\in_array($currentContentTypeIdentifier, array_keys($analysisConfiguration['content_types']), true)) {
            $menuItem->addChild(
                'menu_item_seo_analyzer_not_configured',
                [
                    'label' => 'codein_seo_toolkit.content_create_edit.menu_label',
                    'uri' => '#codein-seo-not-configured',
                    'extras' => [
                        'icon_path' => '/bundles/ibexaseotoolkit/img/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
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
                    'icon_path' => '/bundles/ibexaseotoolkit/img/SEO-Toolkit_logo.svg#codein-seo-toolkit-logo',
                    'translation_domain' => 'codein_seo_toolkit',
                ],
            ]
        );
    }

    protected function getCurrentContentTypeIdentifier(array $options): ?string
    {
        if (isset($options['content']) && $options['content'] instanceof Content) {
            return $options['content']->getContentType()->identifier;
        }

        return null;
    }
}
