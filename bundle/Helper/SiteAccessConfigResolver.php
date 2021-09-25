<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Helper;

use Codein\IbexaSeoToolkit\DependencyInjection\IbexaSeoToolkitExtension;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\MVC\ConfigResolverInterface;

/**
 * Class SiteAccessConfigResolver.
 */
final class SiteAccessConfigResolver
{
    private $configResolver;
    private $contentTypeService;

    public function __construct(ConfigResolverInterface $configResolver, ContentTypeService $contentTypeService)
    {
        $this->configResolver = $configResolver;
        $this->contentTypeService = $contentTypeService;
    }

    public function getParameterConfig(string $paramName, ?string $siteAccess = null)
    {
        return $this->configResolver->getParameter(
            $paramName,
            IbexaSeoToolkitExtension::ALIAS,
            $siteAccess
        );
    }

    public function getConfigResolver()
    {
        return $this->configResolver;
    }

    /**
     * Get full list of content types.
     */
    public function getListOfContentTypes(): array
    {
        $passlist = [];
        $blocklist = [];
        $sitemapConfig = $this->getParameterConfig('sitemap');

        if (\array_key_exists('passlist', $sitemapConfig)) {
            $passlist = $sitemapConfig['passlist']['content_type_identifiers'];
        }
        if (\array_key_exists('blocklist', $sitemapConfig)) {
            $blocklist = $sitemapConfig['blocklist']['content_type_identifiers'];
        }

        $contentTypeIdentifiers = [];
        $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();

        foreach ($contentTypeGroups as $contentTypeGroup) {
            foreach ($this->contentTypeService->loadContentTypes($contentTypeGroup) as $contentType) {
                $contentTypeIdentifier = $contentType->identifier;

                // We apply the same passing/blocking we do on search
                if (!\count($passlist) && !\in_array($contentTypeIdentifier, $blocklist, true)) {
                    $contentTypeIdentifiers[] = $contentType->identifier;
                } elseif (
                    \count($passlist)
                    && \in_array($contentTypeIdentifier, $passlist, true)
                    && !\in_array($contentTypeIdentifier, $blocklist, true)
                ) {
                    $contentTypeIdentifiers[] = $contentType->identifier;
                }
            }
        }

        return $contentTypeIdentifiers;
    }
}
