# Installing Codein Ibexa SEO Toolkit

## Get the bundle using composer

Add IbexaSeoToolkitBundle. by running this command from the terminal at the root of
your Symfony project:

```bash
composer require codein/ibexa-seo-toolkit
```

Alternatively, you can add the requirement `"codein/ibexa-seo-toolkit": "^1.12"` to your composer.json and run `composer update`.
This could be useful when the installation of IbexaSeoToolkitBundle is not compatible with some currently installed dependencies (see [requirements details][1]).
Anyway, the previous option is the preferred way, since composer can pick the best requirement constraint for you.

## Enable the bundle

If you use Flex (you should!), the bundle is automatically enabled with a recipe and no further action is required.
Otherwise, to start using the bundle, register it in the bundles list:

```php
// config/bundles.php
return [
    //...
    Codein\IbexaSeoToolkit\IbexaSeoToolkitBundle::class => ['all' => true],
];
```

Register the routes of our seo entrypoint by adding the following lines to ``config/routes.yml``:


```yaml
# config/routes.yaml
api_codein_ibexa_seo:
    resource: .
    type: api_codein_ibexa_seo
```

Once the bundle is installed and configured, tell
IbexaSeoToolkitBundle that you want to use it.

```yaml
# config/packages/codein_ibexa_seo_toolkit.yaml
codein_ibexa_seo_toolkit: ~
```

See [usage section](./usage) for configuring the bundle.

## Add necessary tables to the database

In your project root, launch :
```bash
php bin/console doctrine:schema:update --force
```

If you're using MySQL and have DoctrineMigrationsBundle installed, you can use the migration provided by configuring the bundle path:
```yml
doctrine_migrations:
    migrations_paths:
        'Codein\IbexaSeoToolkit\DoctrineMigrations': '@IbexaSeoToolkitBundle/migrations'
```

Then, execute it:
```bash
php bin/console doctrine:migrations:execute 'Codein\IbexaSeoToolkit\DoctrineMigrations\Version20210304163313' --up
```

Obviously, the migration name matches what we've got at the current time of editing this documentation. Look inside the bundle to find the migration file.

## Configure Webpack to build bundle assets

In your `webpack.config.js` (same level as your composer.json) :
```js
const codeinSeoToolkitConfig = require('./vendor/codein/ibexa-seo-toolkit/bundle/Resources/encore/codein.config.js')(Encore);

module.exports = [ eZConfig, ...customConfigs, codeinSeoToolkitConfig ];
```

In your `config/packages/webpack_encore.yml`:
```yml
webpack_encore:
    builds:
        codein: "%kernel.project_dir%/public/bundles/codein-ibexaseotoolkit"
```

### Compiling backoffice translations

```bash
php bin/console bazinga:js-translation:dump public/assets --merge-domains
```

### Compiling JS files:
```
yarn encore dev
```


### FAQ

#### Why so much configuration ?

As an Ibexa bundle, we want to make sure developers can extend / override most of it.

Moreover, we use React for adding an interface in the backoffice, for maintainability purposes ... and it comes with upfront application configuration.

#### Backoffice translations aren't found

This is typically not a problem with the bundle.

**Try:**
* clearing caches,
* check yarn cache is writable,
* do `yarn install`

## That's it!

Yeah, the bundle is installed! Move onto the [usage section](./usage) to find out how to specify your custom configuration.

[1]: REQUIREMENTS.md
