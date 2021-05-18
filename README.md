# pantheon-systems/drupal-integrations

Add this project to any Drupal distribution based on drupal/core-composer-scaffold to enable it for use on Pantheon.

This project enables the following Pantheon/Drupal integrations:

- Injects the Pantheon database credentials for the Drupal site
- Provides a default PHP version to use (7.3)
- Enables HTTPS (in transitional mode) by default
- Demonstrates how to turn on twig debugging on non-production Pantheon environments
- Sets the path to:
  - Configuration import / export directory
  - Private files
  - Temporary files
  - Twig cache files
- Establishes a secure, random hash salt for Drupal
- Injects the Pantheon Service Provider (clears edge cache on cache rebuild, etc.)
- Prevents the user from updating Drupal core with Drush
- Configures the trusted host patterns to avoid a warning that is not applicable to Panthoen
- Ignores large cache directories (e.g. node modules and bower components)

## Enabling this project

This project must be enabled in the top-level composer.json file, or it will be ignored and will not perform any of its functions.
```
{
    ...
    "require": {
        "pantheon-systems/drupal-integrations": "^8"
    },
    ...
    "extra": {
        "drupal-scaffold": {
            "allowed-packages": [
                "pantheon-systems/drupal-integrations"
            ]
        }
    }
}
```

## Versions

Use version "^8" for Drupal 8, and version "^9" for Drupal 9.
