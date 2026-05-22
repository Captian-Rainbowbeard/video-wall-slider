<?php
/**
 * Frontend Assets Enqueuer
 *
 * @package VideoWallSlider
 * @subpackage Frontend
 */

namespace VideoWallSlider\Frontend;

/**
 * Enqueues frontend scripts and styles
 */
class Enqueuer {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue frontend assets
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		// Only enqueue on pages that use the shortcode
		if ( ! $this->should_enqueue() ) {
			return;
		}

		// Enqueue styles
		wp_enqueue_style(
			'vws-frontend-styles',
			VIDEO_WALL_SLIDER_ASSETS_URL . 'css/frontend.css',
			array(),
			VIDEO_WALL_SLIDER_VERSION
		);

		// Enqueue YouTube IFrame API
		wp_enqueue_script(
			'youtube-api',
			'https://www.youtube.com/iframe_api',
			array(),
			null,
			false
		);

		// Enqueue main plugin script
		wp_enqueue_script(
			'vws-frontend-scripts',
			VIDEO_WALL_SLIDER_ASSETS_URL . 'js/frontend.js',
			array( 'youtube-api' ),
			VIDEO_WALL_SLIDER_VERSION,
			true
		);

		// Localize script
		wp_localize_script(
			'vws-frontend-scripts',
			'vwsData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'vws_frontend_nonce' ),
			)
		);
	}

	/**
	 * Check if should enqueue assets
	 *
	 * @return bool
	 */
	private function should_enqueue() {
		// Always enqueue if we're viewing a video wall
		if ( is_singular( 'video_wall' ) ) {
			return true;
		}

		// Check if shortcode is used in post content
		if ( is_singular() ) {
			global $post;
			if ( has_shortcode( $post->post_content, 'video_wall' ) ) {
				return true;
			}
		}

		return false;
	}
}
