<?php

namespace Pantheon\Integrations;

/**
 * The Assets class manages the assets needed to integrate a Drupal site with the Pantheon platform.
 */
class Assets {
	/**
	 * dir
	 * 
	 * Return the path to the assets directory.
	 * 
	 * @return string
	 * 	 The asset directory path.
	 */
	public function dir(): string {
		return dirname(__DIR__) . '/assets';
	}
}
