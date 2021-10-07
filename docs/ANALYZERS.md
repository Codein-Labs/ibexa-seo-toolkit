Analyzers
======

The bundle uses a set of analyzers to analyze the page content

An analyzer extends the `Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer` abstract class.

From here, you have two main options:
* Use the set of analyzers provided by the bundle.
* Implement some analyzers yourself. (After all, Symfony offers great tooling for extending bundles)

## Details

### Philosophy

The goal of analyzers is **to provide good intel of whether the writing strategy is adapted** to the focus keyword we want to rank on. (a.k.a. *Keyword analysis*)

For example, if we want to rank for "Ibexa Bundle", it make sense to use this keyword in some h2 titles, in the text.

Moreover, another goal is to help content creators to write content which will be read well by both search engines and the customer (a.k.a. *Lisibility analysis*).

### Analyzed content scope

The content can be analyzed through 3 scopes

1. `Full source code` : full content preview source code (Ex: analysis of `<head>` metadata and title)
2. `Filtered source code` : filtered version of the preview source code (Ex: count words, count links)
   * By default the following tags are removed from the full content preview source code : 'head', 'header', 'footer',
       'script', 'nav', 'aside', 'style', 'xml'
   * You can build your own filtered content by subscribing the `Codein\IbexaSeoToolkit\Event\AnalysisDTOEvent` event
3. `Rich text data` : rich text fields content if they are configured for the current content type
    * If no rich text fields are configured for the current content type, `Filtered source code` will be used instead.

### Data analytics

Analyzers uses data configured:
* Content preview in full or filtered mode (requires at least to save the content to get up to date insight)
* Rich text field (at writing time) if they are configured for the current content type
* Keyword text field (contributed in the content edit view)
* Is this a pillar content or not (contributed in the content edit view)

## Analyzers available

At the moment there are several available analyzers:

### Word Count Analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\WordCountAnalyzer`
* <ins>Data context</ins>: `Filtered source code` or `Rich text data`
* <ins>Role</ins> : It will count the number of words the configured scope.
* <ins>Scores</ins> :
  * _Low_ : if text content < 700 words
  * _Medium_ : if 700 <= text content < 1500 words
  * _High_ : if 1500 < text content
* <ins>Notes</ins> : if the content is a **pillar content**, number of words requirements will be raised by a factor of `1.5`.

### Keyword In Titles Analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\KeywordInTitlesAnalyzer`
* <ins>Data context</ins>: `Filtered source code` or `Rich text data`
* <ins>Role</ins> : It will check if the keyword is present in (h1|h2|h3|h4|h5|h6) titles.
* <ins>Scores</ins> :
  * _Low_ : if keyword is present in less than 10% of titles
  * _Medium_ : if keyword is present in more than 10% and less than 30% of titles.
  * _High_ : if keyword is present in more than 30% of titles.

### Keyword In Url Slug Analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\KeywordInUrlSlugAnalyzer`
* <ins>Data context</ins>: `Url slug via locationId`
* <ins>Role</ins> : It will check if the keyword is present in the url alias (= url slug).
* <ins>Scores</ins> :
  * _Low_ : if keyword is not present in the slug
  * _Medium_ : if keyword is present, but does not match the slug exactly
  * _High_ : if keyword matches exactly the slug

### Title Tag Contains Keyword Analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\TitleTagContainsKeywordAnalyzer`
* <ins>Data context</ins>: `Full source code`
* <ins>Role</ins> : It will check if the keyword is present in the title tag (= `<title></title>`).
* <ins>Scores</ins> :
  * _Low_ : if no title tag is in the DOM
  * _Medium_ : if the title text does not contain the keyword
  * _High_ : if the title text contains the keyword

### Meta description contains Keyword Analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\MetaDescriptionContainsKeywordAnalyzer`
* <ins>Data context</ins>: `Full source code`
* <ins>Role</ins> : It will check if the keyword is present in the meta description tag.
* <ins>Scores</ins> :
    * _Low_ : if no meta description tag is in the DOM
    * _Medium_ : if the meta description text does not contain the keyword
    * _High_ : if the meta description text contains the keyword

### Internal links analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\InternalLinksAnalyzer`
* <ins>Data context</ins>: `Filtered source code`
* <ins>Role</ins> : It will count the number of internal links in the significant part of the page
* <ins>Scores</ins> :
    * _Low_ : if no internal links
    * _Medium_ : if the number of internal ratio is less than 1% of word count
    * _High_ : if the number of internal ratio is at least 1% of word count

Use the `internal_links_hostnames` configuration parameter to set which hostnames to consider as internal links
in absolute href's

### External links analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\OutboundLinksAnalyzer`
* <ins>Data context</ins>: `Filtered source code`
* <ins>Role</ins> : It will count the number of external links in the significant part of the page
* <ins>Scores</ins> :
    * _Low_ : if no external links
    * _Medium_ : if the number of external ratio is less than 1% of word count
    * _High_ : if the number of external ratio is at least 1% of word count

Use the `internal_links_hostnames` configuration parameter to set which hostnames to consider as internal links
in absolute href's

### One H1 Tag Maximum Analyzer

* <ins>Class</ins>: `Codein\IbexaSeoToolkit\Analysis\Analyzers\OneH1TagMaximumAnalyzer`
* <ins>Data context</ins>: `Full source code`
* <ins>Role</ins> : It will check if the article has exactly one `h1 tag`.
* <ins>Scores</ins> :
  * _Low_ : No h1 tag or more than one.
  * _High_ : 1 h1 tag

### Create a custom analyzer

To create a custom analyzer, simply extend ``Codein\IbexaSeoToolkit\Analysis\AbstractAnalyzer`` class and in the ``analyze`` method of your class return the result.

You can get inspiration on how to proceed by looking at existing analysis classes.

By extending AbstractAnalyzer, your service should automatically be tagged : `codein_ibexa_seo_toolkit.seo_analyzer`

If that's not the case, know that it's required for your analysis class to be taken into account.

### Create you own filtered source code scope

By default some analyzers are using a filtered version of the page source code excluding all `head`, `header`, `footer`,
`script`, `nav`, `aside`, `style`, `xml` tags.

In order to improve the analysis quality, you might filter the source code your own way (Ex: keep only the `main` tag)

Subscribe to the `Codein\IbexaSeoToolkit\Event\AnalysisDTOEvent` event and look at the `Codein\IbexaSeoToolkit\EventSubscriber\AnalysisDTOEventSubscriber`
for a working example.

## That's it!

Check out the docs for information on how to use the bundle! [Return to the
index.](./usage)
