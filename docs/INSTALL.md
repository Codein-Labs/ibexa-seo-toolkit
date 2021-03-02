# Installing Codein eZ Platform SEO Toolkit

## Get the bundle using composer

Add IbexaSeoToolkitBundle. by running this command from the terminal at the root of
your Symfony project:

```bash
composer require codein/ezplatform-seo-toolkit
```

Alternatively, you can add the requirement `"codein/ezplatform-seo-toolkit": "^1.12"` to your composer.json and run `composer update`.
This could be useful when the installation of IbexaSeoToolkitBundle is not compatible with some currently installed dependencies (see [requirements details][1]).
Anyway, the previous option is the preferred way, since composer can pick the best requirement constraint for you.

## Enable the bundle

If you use Flex (you should!), the bundle is automatically enabled with a recipe and no further action is required.
Otherwise, to start using the bundle, register it in your application's kernel class:

```php
// app/AppKernel.php (your kernel class may be defined in a different class/path)
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Codein\IbexaSeoToolkit\IbexaSeoToolkitBundle(),
            // ...
        ];
    }
}
```

Register the routes of our seo entrypoint by adding the following lines to ``app/config/routing.yml``:


```yaml
# app/routes.yaml or app/config/routing.yml
api_ez_platform_seo:
    resource: .
    type: api_ez_platform_seo
```

Once the bundle is installed and configured, tell
IbexaSeoToolkitBundle that you want to use it.

```yaml
# config/packages/codein_ibexa_seo_toolkit.yaml or app/config/config.yml
codein_ibexa_seo_toolkit: ~
```

## Add necessary tables to the database

In your project root, launch :
```bash
php bin/console doctrine:schema-update --force
```

As doctrine migrations aren't installed by default on eZ Platform projects, we use `doctrine:schema-update`. 

You can make a migration of it instead.

## Configure Webpack to build bundle assets

In your `webpack.config.js` (same level as your composer.json) :
```js
const codeinSeoToolkitConfig = require('./vendor/codein/ezplatform-seo-toolkit/bundle/Resources/encore/codein.config.js')(Encore);

module.exports = [ eZConfig, ...customConfigs, codeinSeoToolkitConfig ];
```

In your `config.yml`:
```yml
webpack_encore:
    builds:
        codein: "%kernel.project_dir%/web/bundles/codein-ezplatformseotoolkit"
```

### Compiling backoffice translations

```bash
php bin/console bazinga:js-translation:dump web/assets --merge-domains
```

### Compiling JS files: 
```
yarn encore dev
```


### FAQ

#### Why so much configuration ?

As an eZ Platform bundle, we want to make sure developers can extend / override most of it. 

Moreover, we use React for adding an interface in the backoffice, for maintainability purposes ... and it comes with upfront application configuration. 

#### Backoffice translations aren't found

This is typically not a problem with the bundle. 

**Try:**
* clearing caches,
* check yarn cache is writable,
* do `yarn install`

## That's it!

Yeah, the bundle is installed! Move onto the [usage section](USAGE.md) to find out how to specify your custom configuration.

[1]: REQUIREMENTS.md
