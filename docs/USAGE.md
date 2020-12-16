# Using eZ Platform SEO Toolkit

Unlike a wordpress plugin such as Yoast, eZ Platform SEO Toolkit allows developper configuration to match specific customer needs.

## Analysis

```yml
codein_ez_platform_seo_toolkit:
  system:
    default: # siteaccess
      analysis:
        content_types:
          product:
            richtext_field: description
            blocklist: [WordCountAnalyzer]
        blocklist: [SomeAnalyzerYouNeverWantToUse]
```

This configuration allows the product content_type to be analyzed.

On the `product` content type, the field identified by `description` will be analyzed.

`blocklist`: You can prevent some analyzers to run, if you don't find them useful.

## Sitemap
```yml
codein_ez_platform_seo_toolkit:
  system:
    default:
      sitemap:
        split_by: "number_of_results" # One of "number_of_results"; "content_type"
        blocklist:
          locations:             [2, 5]
          subtrees:             [153]
          content_type_identifiers: ['product']
```
`blocklist`: disallow some objects to appear in sitemap
`split_by`: in case of a to large sitemap, it gives an option to split either by number of results or content_types.

## Robots

```yml
codein_ez_platform_seo_toolkit:
  system:
    default:
      robots:
        # If true, prevent search engines from indexing.
        prevent_indexing: '%is_dev_environment%'
        rules:
          googlebot:
            Allow: /
            Crawl-delay: 120
            Disallow: /search
          '*':
            Allow: /
```

## Metas

```yml
codein_ez_platform_seo_toolkit:
  system:
    default:
      metas:
        default_metas:
          copyright: 'Some entity'
          author: 'Someone cool'
        field_type_metas:
          # Prototype
          title:
            label: 'Title'
            default_pattern: "<title|name>"
```

This serves both as default metas and field type metas configuration.

`title` key serves as en example, and will make title configuration appear in the field definition of the meta field type.
