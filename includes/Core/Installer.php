<?php
/**
 * Plugin Installer Class
 *
 * @package VideoWallSlider
 * @subpackage Core
 */

namespace VideoWallSlider\Core;

/**
 * Handles plugin activation and deactivation
 */
class Installer {

	/**
	 * Activate the plugin
	 *
	 * @return void
	 */
	public static function activate() {
		// Create plugin directory structure
		self::create_directories();

		// Flush rewrite rules
		flush_rewrite_rules();

		// Set activation timestamp
		update_option( 'vws_activated_at', current_time( 'mysql' ) );
	}

	/**
	 * Deactivate the plugin
	 *
	 * @return void
	 */
	public static function deactivate() {
		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Uninstall the plugin
	 *
	 * @return void
	 */
	public static function uninstall() {
		// Delete all video wall posts
		$walls = get_posts(
			array(
				'post_type'      => 'video_wall',
				'posts_per_page' => -1,
				'post_status'    => 'any',
			)
		);

		foreach ( $walls as $wall ) {
			wp_delete_post( $wall->ID, true );
		}

		// Delete options
		delete_option( 'vws_activated_at' );
	}

	/**
	 * Create plugin directories
	 *
	 * @return void
	 */
	private static function create_directories() {
		$directories = array(
			VIDEO_WALL_SLIDER_PLUGIN_DIR . 'uploads',
			VIDEO_WALL_SLIDER_PLUGIN_DIR . 'cache',
		);

		foreach ( $directories as $dir ) {
			if ( ! is_dir( $dir ) ) {
				wp_mkdir_p( $dir );
			}
		}
	}
}
