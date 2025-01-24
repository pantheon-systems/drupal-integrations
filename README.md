# pantheon-systems/drupal-integrations

[![Actively Maintained](https://img.shields.io/badge/Pantheon-Actively_Maintained-yellow?logo=pantheon&color=FFDC28)](https://pantheon.io/docs/oss-support-levels#actively-maintained-support)

Add this project to any Composer-managed Drupal to enable it for use on Pantheon.

This project enables the following Pantheon/Drupal integrations:

- Injects the Pantheon database credentials for the Drupal site
- Sets the path to:
  - Configuration import / export directory
  - Private files
  - Temporary files
  - Twig cache files
- Establishes a secure, random hash salt for Drupal
- Injects the Pantheon Service Provider (clears edge cache on cache rebuild, etc.)
- Configures the trusted host patterns to avoid a warning that is not applicable to Panthoen
- Ignores large cache directories (e.g. node modules and bower components)

## Enabling this project

To enable this project, it must first be added to the Drupal site:

```
composer require pantheon-systems/drupal-integrations:^11
```

Then, Pantheon must be enabled from within your site's settings.php file:

```
include \Pantheon\Integrations\Assets::dir() . "/settings.pantheon.php";
```
This project must be enabled in the top-level composer.json file, or it will be ignored and will not perform any of its functions.

## Versions

Use the major version of this project that matches your Drupal version.

| Drupal Version | drupal-integrations Version |
| -------------- | --------------------------- |
| 11.x           | ^11                         |
| 10.x           | ^10                         |
| 9.x            | ^9                          |
| 8.x            | ^8                          |

## Scaffolding

Early versions of this project used the project drupal/core-composer-scaffold to copy the files needed into the right locations. Starting with Drupal 10.4, the scaffold extension is deprecated, and will cause the Drupal Package Manager to refuse to allow your site to be updated if it is allowed to scaffold files in your site's root composer.json file.

If your site is still using the scaffolding feature, you will see the following error messages:

```
Unable to download modules via the UI: Any packages other than the implicitly allowed packages are not allowed to scaffold files. See the scaffold documentation for more information. pantheon-systems/drupal-integrations
```

and:

```
Your site cannot be automatically updated until further action is performed.

Any packages other than the implicitly allowed packages are not allowed to scaffold files. See the scaffold documentation for more information.

pantheon-systems/drupal-integrations
```

To fix this problem, first update to the latest version of pantheon-systems/drupal-integrations via `composer update`. Then, find the following section in your top-level composer.json file:

```
        "drupal-scaffold": {
            "locations": {
                "web-root": "./web"
            },
            "allowed-packages": [
                "pantheon-systems/drupal-integrations"
            ],
            "file-mapping": {
                "[project-root]/.editorconfig": false,
                "[project-root]/pantheon.upstream.yml": false,
                "[project-root]/.gitattributes": false
            }
        },
```

Delete the `allowed-packages` section. 

Next, find the following line in your settings.php file:

```
include __DIR__ . "/settings.pantheon.php";
```

Replace the `__DIR__` on that line with `\Pantheon\Integrations\Assets::dir()`, as shown in the section "Enabling this project", above. Once you do this, the `settings.pantheon.php` file will no longer be copied into your site's configuration folder, and will instead be included directly from its installed location in the `vendor` directory. This should also cause the errors from the Drupal Package Manager to go away.


