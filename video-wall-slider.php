<?php
/**
 * Plugin Name: Video Wall Slider
 * Plugin URI: https://github.com/Captian-Rainbowbeard/video-wall-slider
 * Description: A modern, responsive video wall slider plugin inspired by TikTok/Instagram Reels. Display unlimited YouTube videos with vertical scrolling, autoplay, and advanced admin controls.
 * Version: 1.0.0
 * Author: Video Wall Team
 * Author URI: https://github.com/Captian-Rainbowbeard
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: video-wall-slider
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 *
 * @package VideoWallSlider
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'VIDEO_WALL_SLIDER_VERSION', '1.0.0' );
define( 'VIDEO_WALL_SLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VIDEO_WALL_SLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VIDEO_WALL_SLIDER_ASSETS_URL', VIDEO_WALL_SLIDER_PLUGIN_URL . 'assets/' );
define( 'VIDEO_WALL_SLIDER_BASENAME', plugin_basename( __FILE__ ) );

// Autoloader for plugin classes
spl_autoload_register( function ( $class ) {
	$prefix   = 'VideoWallSlider\\';
	$base_dir = VIDEO_WALL_SLIDER_PLUGIN_DIR . 'includes/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, $len );
	$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

// Load plugin text domain
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'video-wall-slider', false, dirname( VIDEO_WALL_SLIDER_BASENAME ) . '/languages' );
} );

// Initialize plugin
add_action( 'plugins_loaded', function () {
	VideoWallSlider\Core\Plugin::get_instance();
} );

// Activation hook
register_activation_hook( __FILE__, function () {
	VideoWallSlider\Core\Installer::activate();
} );

// Deactivation hook
register_deactivation_hook( __FILE__, function () {
	VideoWallSlider\Core\Installer::deactivate();
} );

// Uninstall hook
register_uninstall_hook( __FILE__, function () {
	VideoWallSlider\Core\Installer::uninstall();
} );