# Metas

Meta informations help search engines understand the pages topic, it is still necessary and very useful for SEO today.

The bundle allows to add a `codeinseometas` field type to a content type to store theses informations.

## Configuration

```yml
# config/packages/codein_ibexa_seo_toolkit.yaml

codein_ibexa_seo_toolkit:
  system:
    default:
      metas:
        default_metas:
          copyright: 'My Company'
          author: 'John Doe'
        field_type_metas:
          title:
            label: 'Title'
            default_pattern: "<title|name>"
          meta_description:
            label: 'Meta Description'
```

`default_metas`: Add meta information which have the same and unique value for each content type.

`field_type_metas`: Add meta information which have a value defined by another field of the content type.

This configuration adds meta informations `copyright` and `author` with respective values `My Company` and `John Doe` for any content type with `codeinseometas` attribute.
It also add a meta `title`, with by default the value of the `title` field of the content type (or the `name` field if it doesn't exist), and a meta `description`.

There are 3 levels of fallback which allow to set a value for the metas :
* 1: in configuration (`codein_ibexa_seo_toolkit.yaml`)



* 2: in the Field Type definition (Administration Interface, on the Content Type)

<img src="../img/ContentTypeMetaDefinition.png"/>

* 3: in the Content (Administration Interface, on the Content)

<img src="../img/ContentMetaDefinition.png"/>

## Result

The meta informations can be rendered as any other content field in a twig template:
```twig
<head>
    {{ ez_render_field(content, 'metas') }}
    ...
</head>
```

The result can be seen in the source code of the page:
```html
<head>
    <meta name="copyright" content="My Company"/>
    <meta name="author" content="John Doe"/>
    <title> This is a title </title>
    <meta name="meta_description" content="This is a description">
    ...
</head>
```

If you want to render a page that's not an Ibexa Content (for example a classic Symfony route), you can still render the default meta informations by including this template :
```twig
<head>
    {# You can override the default metas according if you need it #}
    {% include "@CodeinIbexaSeoToolkit/default_metas.html.twig" with {
        metas: {
            'author': 'John Doe'
        }
    } %}
    ...
</head>
```
