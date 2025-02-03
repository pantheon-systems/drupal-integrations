<?php

namespace Pantheon\Integrations;

/**
 * The Utils class provides useful utility functions.
 */
class Utils {
	/**
	 * stopScaffolding
	 * 
	 * Remove scaffolding from the current site.
	 * 
	 * Usage:
	 * 
	 *   terminus connection:set sftp
	 *   terminus drush ev '\Pantheon\Integrations\Utils::stopScaffolding();'
	 *   terminus composer update
	 *   terminus env:commit --message "Stop scaffolding Pantheon's Drupal integrations, and include directly from vendor instead."
	 * 
	 * @return void
	 */
	public static function stopScaffolding(): void {
		$kernel = \Drupal::service("kernel");
		$site_path = DRUPAL_ROOT . '/' . $kernel->getSitePath();
		self::useSettingsFromVendor($site_path);

		$composer_json_path = DRUPAL_ROOT . '/composer.json';
		self::removePantheonScaffolding($composer_json_path);
	}

	/**
	 * useSettingsFromVendor
	 * 
	 * Stop including settings.pantheon.php from the same directory
	 * as settings.php, and instead load it directly from the vendor directory.
	 * 
	 * @return bool
	 * 	TRUE if a replacement was done
	 */
	public static function useSettingsFromVendor($site_path): bool {
		$settings_path = $site_path . '/settings.php';
		$settings_contents = file_get_contents($settings_path);

		$pantheon_settings_pattern = 'include __DIR__ . "/settings.pantheon.php";';
		$include_from_vendor = file_get_contents(\Pantheon\Integrations\Assets::dir() . "/include-settings.php.tmpl");

		$updated_contents = str_replace($pantheon_settings_pattern, $include_from_vendor, $settings_contents);
		if ($updated_contents == $settings_contents) {
			return FALSE;
		}
		file_put_contents($settings_path, $updated_contents);
		return TRUE;
	}

	/**
	 * removePantheonScaffolding
	 * 
	 * Remove the directive that allows pantheon-systems/drupal-integrations to scaffold files.
	 * 
	 * @return bool
	 *   TRUE if a replacement was done
	 */
	public static function removePantheonScaffolding($composer_json_path): bool {
		$composer_json_contents = file_get_contents($composer_json_path);
		$composer_data = json_decode($composer_json_contents, TRUE);

		if (!isset($composer_data['extra']['drupal-scaffold']['allowed-packages'])) {
			return FALSE;
		}

		// Find the index of the drupal-integrations entry in the allowed-packages array
		$index = array_search('pantheon-systems/drupal-integrations', $composer_data['extra']['drupal-scaffold']['allowed-packages']);
		if ($index === FALSE) {
			return FALSE;
		}
	    unset($composer_data['extra']['drupal-scaffold']['allowed-packages'][$index]);
	    if (empty($composer_data['extra']['drupal-scaffold']['allowed-packages'])) {
	    	unset($composer_data['extra']['drupal-scaffold']['allowed-packages']);
	    }

	    // Write the updated composer.json file
	    $composer_json_contents = static::jsonEncodePretty($composer_data);
	    file_put_contents($composer_json_path, $composer_json_contents . PHP_EOL);
	    return TRUE;
	}

	/**
	 * jsonEncodePretty.
	 *
	 * Convert a nested array into a pretty-printed json-encoded string.
	 * Multi-line arrays with a single entry are converted to single-line arrays.
	 * 
	 * @param array $data
	 *   The data array to encode
	 *
	 * @return string
	 *   The pretty-printed encoded string version of the supplied data.
	 */
	protected static function jsonEncodePretty(array $data): string {
		$prettyContents = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$prettyContents = preg_replace('#": \[\s*("[^"]*")\s*\]#m', '": [\1]', $prettyContents);

		return $prettyContents;
	}
}
