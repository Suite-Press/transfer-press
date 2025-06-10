<?php

if ( ! defined('ABSPATH')) {
    exit;
}
/**
 * WordPress - Transfer Press
 *
 * Plugin Name:         Transfer Press
 * Plugin URI:          https://wordpress.org/plugins/transfer-press
 * Description:         Transfer Press - is an advanced migration management tools for WordPress Plugins
 * Version:             2.0.0
 * Requires at least:   5.2
 * Requires PHP:        7.2
 * Contributor:         suitepress
 * Author:              suitepress
 * Author URI:          https://suitepress.org/transfer-press
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         transfer-press
 * Domain Path:         /languages
 */
require_once __DIR__ . '/vendor/autoload.php';

use TransferPress\App;

if ( class_exists( 'TransferPress\App' ) ) {
    $app = new App();
}
