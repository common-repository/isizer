<?php
/**
 * Isizer
 *
 * Plugin Name: Isizer - image size manager
 * Description: Simple change, create and remove registered WordPress image sizes
 * Author: Seredniy
 * Author URI: https://profiles.wordpress.org/seredniy/
 * Requires at least: 5.1
 * Tested up to: 5.2.4
 * Version: 1.0.1
 * Stable tag: 1.0.1
 *
 * Text Domain: isizer
 * Domain Path: /languages/
 *
 */

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'ISIZER_VERSION' ) ) {
	/**
	 * Plugin version.
	 */
	define( 'ISIZER_VERSION', '0.0.1' );
}

if ( ! defined( 'ISIZER_FILE' ) ) {
	/**
	 * Main plugin file.
	 */
	define( 'ISIZER_FILE', __FILE__ );
}

if ( ! defined( 'ISIZER_PATH' ) ) {
	/**
	 * Path to the plugin dir.
	 */
	define( 'ISIZER_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'ISIZER_URL' ) ) {
	/**
	 * Plugin dir url.
	 */
	define( 'ISIZER_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

require_once ISIZER_PATH . '/vendor/autoload.php';

$plugin = new Isizer_Main();
