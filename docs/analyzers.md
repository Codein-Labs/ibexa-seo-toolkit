Analyzers
======

The bundle uses analyzers to analyze the rich_text content and content preview dom. An analyzer
implements the ` Codein\eZPlatformSeoToolkit\Analyzer\ParentAnalyzerInterface` interface. 

If should change this, you can use one of the provided anayzers or implement a custom one.

## RichText Analyzer

### Analyze RichText field type

At the moment there are several available analyzers:

  * `Codein\eZPlatformSeoToolkit\Analyzer\RichText\WordCount`

**WordCount** will count the number of words in a content of type `ezrichtext`.
                             
### Create a custom RichText Analyzer

To create a custom RichText Analyzer, simply implement ``Codein\eZPlatformSeoToolkit\Analyzer\RichText`` and in the ``analyze`` method of your class return the result.


## That was it!

Check out the docs for information on how to use the bundle! [Return to the
index.](USAGE.md)
