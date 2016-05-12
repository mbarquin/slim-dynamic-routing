# Slim 3 Dynamic routing setup with a Factory object

...

## Install the Utility

...

## Get a configured Slim app with Factory

```php
    $app = \mbarquin\SlimDR\Factory::slim()
            ->withGroup('api')
            ->withVersionGroup('v1')
            ->withContainer($settings)
            ->withNamespace('\\MyApp\\Controller')
            ->getApp();
```