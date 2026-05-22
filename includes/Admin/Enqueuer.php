<?php
/**
 * Admin Assets Enqueuer
 *
 * @package VideoWallSlider
 * @subpackage Admin
 */

namespace VideoWallSlider\Admin;

/**
 * Enqueues admin scripts and styles
 */
class Enqueuer {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook_suffix Current admin page hook
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix ) {
		// Only load on plugin pages
		if ( false === strpos( $hook_suffix, 'video-wall-slider' ) ) {
			return;
		}

		// Enqueue jQuery UI for dragging
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Enqueue admin styles
		wp_enqueue_style(
			'vws-admin-styles',
			VIDEO_WALL_SLIDER_ASSETS_URL . 'css/admin.css',
			array(),
			VIDEO_WALL_SLIDER_VERSION
		);

		// Enqueue admin scripts
		wp_enqueue_script(
			'vws-admin-scripts',
			VIDEO_WALL_SLIDER_ASSETS_URL . 'js/admin.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			VIDEO_WALL_SLIDER_VERSION,
			true
		);

		// Localize script
		wp_localize_script(
			'vws-admin-scripts',
			'vwsAdmin',
			array(
				'nonce' => wp_create_nonce( 'vws_admin_nonce' ),
				'i18n'  => array(
					'confirmDelete' => __( 'Are you sure you want to delete this video?', 'video-wall-slider' ),
				),
			)
		);
	}
}
