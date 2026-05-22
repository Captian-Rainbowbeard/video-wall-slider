<?php
/**
 * Admin Menu Class
 *
 * @package VideoWallSlider
 * @subpackage Admin
 */

namespace VideoWallSlider\Admin;

/**
 * Creates admin menu and dashboard
 */
class Menu {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register admin menu
	 *
	 * @return void
	 */
	public function register_menu() {
		// Add main menu
		add_menu_page(
			__( 'Video Wall Slider', 'video-wall-slider' ),
			__( 'Video Wall Slider', 'video-wall-slider' ),
			'manage_options',
			'video-wall-slider',
			array( $this, 'render_dashboard' ),
			'dashicons-format-video',
			25
		);

		// Add submenu
		add_submenu_page(
			'video-wall-slider',
			__( 'Dashboard', 'video-wall-slider' ),
			__( 'Dashboard', 'video-wall-slider' ),
			'manage_options',
			'video-wall-slider',
			array( $this, 'render_dashboard' )
		);

		// Settings submenu
		add_submenu_page(
			'video-wall-slider',
			__( 'Settings', 'video-wall-slider' ),
			__( 'Settings', 'video-wall-slider' ),
			'manage_options',
			'video-wall-slider-settings',
			array( $this, 'render_settings' )
		);
	}

	/**
	 * Render dashboard page
	 *
	 * @return void
	 */
	public function render_dashboard() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Video Wall Slider', 'video-wall-slider' ); ?></h1>
			<div id="vws-dashboard" class="vws-dashboard"></div>
		</div>
		<?php
	}

	/**
	 * Render settings page
	 *
	 * @return void
	 */
	public function render_settings() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Video Wall Slider Settings', 'video-wall-slider' ); ?></h1>
			<div id="vws-settings" class="vws-settings"></div>
		</div>
		<?php
	}
}
