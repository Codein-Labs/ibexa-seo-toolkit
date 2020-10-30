# Installing Codein eZ Platform SEO Toolkit

## Get the bundle using composer

Add EzPlatformSeoToolkitBundle. by running this command from the terminal at the root of
your Symfony project:

```bash
composer require codein/ezplatform-seo-toolkit
```

Alternatively, you can add the requirement `"codein/ezplatform-seo-toolkit": "^1.12"` to your composer.json and run `composer update`.
This could be useful when the installation of EzPlatformSeoToolkitBundle is not compatible with some currently installed dependencies (see [requirements details][1]).
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
            new Codein\eZPlatformSeoToolkit\EzPlatformSeoToolkitBundle(),
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
EzPlatformSeoToolkitBundle that you want to use it.

```yaml
# config/packages/codein_ez_platform_seo_toolkit.yaml or app/config/config.yml
codein_ez_platform_seo_toolkit: ~
```

# Configure Webpack to build bundle assets

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

## That was it!

Yeah, the bundle is installed! Move onto the [usage section](USAGE.md) to find out how
to specify your custom configuration.

[1]: docs/REQUIREMENTS.md
