# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed

* Fixed versions requirements in the docs to be consistent.
* Use `ezpublish.api.service.inner_schema_namer` factory instead of the internal schema_namer service (#8)
* Removal of `codein_ibexa_seo_toolkit.seo_analyzer` yaml service tag declaration. Replaced by DI `registerForAutoconfiguration`
* Adding SitemapQuery Extensibility point through `Codein\IbexaSeoToolkit\Event\SitemapQueryEvent`
* Allow `KeywordInUrlSlugAnalyzer` to run event if no richtext is configured
* Fixed content type filtering with multiple content types in `SitemapQueryHelper`
* Fixed image variation generation in `SitemapContentService`
* Fixed return value of `SitemapContentService::generate()` and `SitemapContentService::generateResults()`

## [1.0.0] - 2021-07-09
### Added

* Initial release.
