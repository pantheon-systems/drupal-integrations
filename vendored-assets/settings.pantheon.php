<?php

// phpcs:ignoreFile

/**
 * @file
 * Pantheon configuration file.
 */

/**
 * Version of Pantheon files.
 *
 * This is a monotonically-increasing sequence number.
 */
if (!defined("PANTHEON_VERSION")) {
  define("PANTHEON_VERSION", "5");
}

/**
 * Set the default location for the 'private' directory.  Note
 * that this location is protected when running on the Pantheon
 * environment, but may be exposed if you migrate your site to
 * another environment.
 */
$settings['file_private_path'] = 'sites/default/files/private';

/**
 * Add the Drupal 8 CMI Directory Information directly in settings.php to make sure
 * Drupal knows all about that.
 *
 * Issue: https://github.com/pantheon-systems/drops-8/issues/2
 *
 * IMPORTANT SECURITY NOTE:  The configuration paths set up
 * below are secure when running your site on Pantheon.  If you
 * migrate your site to another environment on the public internet,
 * you should relocate these locations. See "After Installation"
 * at https://www.drupal.org/node/2431247
 *
 */
if (empty($settings['config_sync_directory'])) {
  $settings['config_sync_directory'] = '../config';
}

/**
 * Override the $databases variable to pass the correct Database credentials
 * directly from Pantheon to Drupal.
 *
 * Issue: https://github.com/pantheon-systems/drops-8/issues/8
 *
 */
if (isset($_SERVER['PRESSFLOW_SETTINGS'])) {
  $pressflow_settings = json_decode($_SERVER['PRESSFLOW_SETTINGS'], TRUE);
  foreach ($pressflow_settings as $key => $value) {
    // One level of depth should be enough for $conf and $database.
    if ($key == 'conf') {
      foreach($value as $conf_key => $conf_value) {
        $conf[$conf_key] = $conf_value;
      }
    }
    elseif ($key == 'databases') {
      // Protect default configuration but allow the specification of
      // additional databases. Also, allows fun things with 'prefix' if they
      // want to try multisite.
      if (!isset($databases) || !is_array($databases)) {
        $databases = array();
      }
      $databases = array_replace_recursive($databases, $value);
    }
    else {
      $$key = $value;
    }
  }
}

/**
 * Handle Hash Salt Value from Drupal
 *
 * Changing these will invalidate all one-time login links.
 * Pantheon sets this values for you. If you want to shuffle it you could
 * use terminus env:rotate-random-seed command:
 * https://docs.pantheon.io/terminus/commands/env-rotate-random-seed
 *
 */
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  $settings['hash_salt'] = $_ENV['DRUPAL_HASH_SALT'];
}

/**
 * Define appropriate location for tmp directory
 *
 * Issue: https://github.com/pantheon-systems/drops-8/issues/114
 *
 */
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  $settings["file_temp_path"] = sys_get_temp_dir();
}

/**
 * Place Twig cache files in the Pantheon rolling temporary directory.
 * A new rolling temporary directory is provided on every code deploy,
 * guaranteeing that fresh twig cache files will be generated every time.
 * Note that the rendered output generated from the twig cache files
 * are also cached in the database, so a cache clear is still necessary
 * to see updated results after a code deploy.
 */
if (isset($_ENV['PANTHEON_ROLLING_TMP']) && isset($_ENV['PANTHEON_DEPLOYMENT_IDENTIFIER'])) {
  // Relocate the compiled twig files to <binding-dir>/tmp/ROLLING/twig.
  // The location of ROLLING will change with every deploy.
  $settings['php_storage']['twig']['directory'] = $_ENV['PANTHEON_ROLLING_TMP'];
  // Ensure that the compiled twig templates will be rebuilt whenever the
  // deployment identifier changes.  Note that a cache rebuild is also necessary.
  $settings['deployment_identifier'] = $_ENV['PANTHEON_DEPLOYMENT_IDENTIFIER'];
  $settings['php_storage']['twig']['secret'] = $_ENV['DRUPAL_HASH_SALT'] . $settings['deployment_identifier'];
}

/**
 * Install the Pantheon Service Provider to hook Pantheon services into
 * Drupal 11. This service provider handles operations such as clearing the
 * Pantheon edge cache whenever the Drupal cache is rebuilt.
 */
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  $GLOBALS['conf']['container_service_providers']['PantheonServiceProvider'] = '\Pantheon\Internal\PantheonServiceProvider11';
}

/**
 * "Trusted host settings" are not necessary on Pantheon; traffic will only
 * be routed to your site if the host settings match a domain configured for
 * your site in the dashboard.
 */
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  $settings['trusted_host_patterns'][] = '.*';
}

/**
 * The default list of directories that will be ignored by Drupal's file API.
 *
 * By default ignore node_modules and bower_components folders to avoid issues
 * with common frontend tools and recursive scanning of directories looking for
 * extensions.
 *
 * @see file_scan_directory()
 * @see \Drupal\Core\Extension\ExtensionDiscovery::scanDirectory()
 */
if (empty($settings['file_scan_ignore_directories'])) {
  $settings['file_scan_ignore_directories'] = [
    'node_modules',
    'bower_components',
  ];
}
