# pantheon-systems/pantheon-enabler

Add this project to any Drupal distribution based on drupal/core-composer-scaffold to enable it for use on Pantheon.

## Enabling this project

This project must be enabled in the top-level composer.json file, or it will be ignored and will not scaffold any files.
```
{
	  ...
	  "require": {
	  	  "pantheon-systems/pantheon-enabler"
	  },
	  ...
    "extra": {
        "drupal-scaffold": {
            "allowed-packages": [
                "pantheon-systems/pantheon-enabler"
            ]
        }
    }
}
```

## Special instructions for upstreams

If including this project from a Composer-enabled upstream, you must disable settings.pantheon.yml from this project if that file is included as part of the upstream.
```
{
	  ...
    "extra": {
        "drupal-scaffold": {
            "file-mapping": {
                "[project-root]/pantheon.upstream.yml": false
            }
        }
    }
}
```
