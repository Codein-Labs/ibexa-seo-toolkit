# Installing Codein Ibexa SEO Toolkit

## Get the bundle using composer

Add IbexaSeoToolkitBundle. by running this command from the terminal at the root of
your Symfony project:

```bash
composer require codein/ibexa-seo-toolkit --no-scripts
```

Alternatively, you can add the requirement `"codein/ibexa-seo-toolkit": "^1.0"` to your composer.json and run `composer update`.
This could be useful when the installation of IbexaSeoToolkitBundle is not compatible with some currently installed dependencies (see [requirements details][1]).
Anyway, the previous option is the preferred way, since composer can pick the best requirement constraint for you.

### Enable the bundle

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
codein_ibexa_seo_toolkit:
    system:
        default:
            metas:
                default_metas:
```

See [usage section](./usage) for configuring the bundle.

### Run post-install scripts

```bash
composer run-script post-install-cmd
```

### Add necessary tables to the database

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

## That's it!

Yeah, the bundle is installed! Move onto the [usage section](./usage) to find out how to specify your custom configuration.

[1]: REQUIREMENTS.md

## FAQ

### Why so much configuration ?

As an Ibexa bundle, we want to make sure developers can extend / override most of it.

Moreover, we use React for adding an interface in the backoffice, for maintainability purposes ... and it comes with upfront application configuration.

### Compiling backoffice translations

```bash
php bin/console bazinga:js-translation:dump public/assets --merge-domains
```

### Compiling JS files:
```
yarn encore dev
```

### Backoffice translations aren't found

This is typically not a problem with the bundle.

**Try:**
* clearing caches,
* check yarn cache is writable,
* do `yarn install`

### Know issues

#### Error while dump JS translation

If an error occurs, while running `bazinga:js-translation:dump %PUBLIC_DIR%/assets --merge-domains`, refering to missing
folders :

```
[webpack-cli] Error: ENOENT: no such file or directory, scandir '/app/ezplatform/public/assets/translations'
```

Use `make-dir` to create the missing directories as first step in `webpack.config.js`

```
const makeDir = require('make-dir');
makeDir.sync('./public/assets');
makeDir.sync('./public/assets/translations');

const Encore = require('@symfony/webpack-encore');
```
