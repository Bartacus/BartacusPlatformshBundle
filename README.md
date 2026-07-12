BartacusPlatformshBundle
========================

This bundle provides some helper functionality if you use TYPO3 on the [Upsun](https://www.upsun.com) cloud hosting platform.

## Usage

In your `config/system/additional.php`, you can use the `UpsunConfigLoader` to automatically configure your TYPO3 instance:

```php
use Bartacus\Bundle\PlatformshBundle\UpsunConfigLoader;

$upsunLoader = new UpsunConfigLoader();
$upsunLoader->applyDatabaseConfiguration('database');
$upsunLoader->applyRedisCaching('rediscache');
$upsunLoader->applySolrService('solrsearch');
$upsunLoader->mapRoutes(['main']);
```

### Methods

- `applyDatabaseConfiguration(string $relationshipName = 'database')`: Maps the database relationship to `$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']`.
- `applyRedisCaching(string $relationshipName = 'rediscache')`: Sets up Redis for common TYPO3 cache groups (`pages`, `pagesection`, `hash`, `extbase`).
- `mapRoutes(array $siteIdentifiers = ['main'])`: Maps Upsun routes to environment variables (e.g., `TYPO3_BASE_DOMAIN_MAIN`).
