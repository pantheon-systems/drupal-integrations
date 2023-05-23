# pantheon-systems/drupal-integrations

[![Actively Maintained](https://img.shields.io/badge/Pantheon-Actively_Maintained-yellow?logo=pantheon&color=FFDC28)](https://pantheon.io/docs/oss-support-levels#actively-maintained-support)

Add this project to any Drupal distribution based on drupal/core-composer-scaffold to enable it for use on Pantheon.

This project enables the following Pantheon/Drupal integrations:

- Injects the Pantheon database credentials for the Drupal site
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
        "pantheon-systems/drupal-integrations": "^9"
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
