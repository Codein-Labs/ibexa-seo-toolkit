# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
* SitemapQuery Extensibility point through `Codein\IbexaSeoToolkit\Event\SitemapQueryEvent`
* Handle "non-string" metadata field types (such as images and checkboxes)

### Changed
* Fixed versions requirements in the docs to be consistent.
* Use `ezpublish.api.service.inner_schema_namer` factory instead of the internal schema_namer service (#8)
* Update install documentation
* Removed `codein_ibexa_seo_toolkit.seo_analyzer` yaml service tag declaration. Replaced by DI `registerForAutoconfiguration`
* Allow `KeywordInUrlSlugAnalyzer` to run event if no richtext is configured
* Fixed content type filtering with multiple content types in `SitemapQueryHelper`
* Fixed image variation generation in `SitemapContentService`
* Fixed return value of `SitemapContentService::generate()` and `SitemapContentService::generateResults()`
* Move Doctrine migrations from `bundle/DoctrineMigrations/` to `bundle/migrations/`
* Fixed title and description analysis in lowercase without accents
* [Admin UI] Fixed edition right toolbar button handling between contents with or without page builder
* Use `ezplatform` entrypoint for assets import (#9)
* [Admin UI] Fixed toolbar icon path

## [1.0.0] - 2021-07-09
### Added

* Initial release.
