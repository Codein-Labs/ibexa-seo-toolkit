# Contributing to Codein Ibexa SEO Toolkit

:+1::tada: First off, thanks for taking the time to contribute! :tada::+1:

The following is a set of guidelines for contributing to *Ibexa SEO Toolkit*, which is hosted in the [Codein Labs Organization](https://github.com/Codein-Labs) on GitHub. These are mostly guidelines, not rules. Use your best judgment, and feel free to propose changes to this document in a pull request.

## Code of Conduct

This project and everyone participating in it is governed by the [Codein Code of Conduct](docs/CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to [labs@codein.fr](mailto:labs@codein.com).

## What should I know before I get started?

### Symfony

**Codein Ibexa SEO Toolkit** is a symfony bundle. As such, we aim at providing code that comply to [symfony coding standards](https://symfony.com/doc/current/contributing/code/standards.html).

### Ibexa

This symfony bundle aims at providing tools for Ibexa DXP (Digital Experience Platform).

As such, we don't provide tools for a Symfony only application, and don't plan to.

### Design Decisions

When we make a significant decision in how we maintain the project and what we can or cannot support, we will document it in the [/docs](https://github.com/Codein-Labs/ezplatform-seo-toolkit/tree/master/docs) folder of the repository. If you have a question around how we do things, check to see if it is documented there. If it is *not* documented there, please open a new issue and ask your question.

## How Can I Contribute ?

### Reporting Bugs

In that case, please supply information such as :
* your composer packages version
* reproducible example, if it applies
* other you think useful

### Suggesting Enhancements

This bundle aims at being useful and giving you the tools you need. 

Do not hesitate to share your dreamed feature with us ! 

#### Before Suggesting an enhancement

- *Check the enhancement has not been asked already in the issue section*

### Pull Requests

You can send a pull request for contributing code.
## Styleguides

* We follow [symfony coding standards](https://symfony.com/doc/current/contributing/code/standards.html) as much as possible. 
* You can use tools as [Psalm](https://github.com/vimeo/psalm) and [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

### Git commit message

* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
* Limit the first line to 72 characters or less
* Reference issues and pull requests liberally after the first line
* The first word should be one of these in order to describe the goal of the commit : 
  * feat: (new feature for the user, not a new feature for build script)
  * fix: (bug fix for the user, not a fix to a build script)
  * docs: (changes to the documentation)
  * style: (formatting, missing semi colons, etc; no production code change)
  * refactor: (refactoring production code, eg. renaming a variable)
  * test: (adding missing tests, refactoring tests; no production code change)
  * chore: (updating webpack tasks etc; no production code change)
* Feel free to use [Gitmojis](https://gist.github.com/MeryllEssig/db4e2b38ebf5cf54169765b8b00c5f8e)

## This contibuting guide
This guide is inspired by [Atom contributing guide](https://github.com/atom/atom/blob/master/CONTRIBUTING.md).
