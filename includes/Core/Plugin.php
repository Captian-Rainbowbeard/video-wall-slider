<?php
/**
 * Main Plugin Class
 *
 * @package VideoWallSlider
 * @subpackage Core
 */

namespace VideoWallSlider\Core;

use VideoWallSlider\Admin\Menu;
use VideoWallSlider\Admin\MetaBoxes;
use VideoWallSlider\Admin\Enqueuer as AdminEnqueuer;
use VideoWallSlider\Frontend\Enqueuer as FrontendEnqueuer;
use VideoWallSlider\Frontend\Shortcode;
use VideoWallSlider\REST\API;

/**
 * Plugin main class
 */
class Plugin {

	/**
	 * Instance of the class
	 *
	 * @var Plugin
	 */
	private static $instance = null;

	/**
	 * Get instance of the class
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	private function init() {
		// Register custom post type
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		// Admin hooks
		if ( is_admin() ) {
			new Menu();
			new MetaBoxes();
			new AdminEnqueuer();
		}

		// Frontend hooks
		if ( ! is_admin() ) {
			new FrontendEnqueuer();
			new Shortcode();
		}

		// REST API
		new API();
	}

	/**
	 * Register custom post type
	 *
	 * @return void
	 */
	public function register_post_type() {
		$args = array(
			'labels'              => array(
				'name'               => __( 'Video Walls', 'video-wall-slider' ),
				'singular_name'      => __( 'Video Wall', 'video-wall-slider' ),
				'add_new'            => __( 'Add New', 'video-wall-slider' ),
				'add_new_item'       => __( 'Add New Video Wall', 'video-wall-slider' ),
				'edit_item'          => __( 'Edit Video Wall', 'video-wall-slider' ),
				'view_item'          => __( 'View Video Wall', 'video-wall-slider' ),
				'all_items'          => __( 'All Video Walls', 'video-wall-slider' ),
				'search_items'       => __( 'Search Video Walls', 'video-wall-slider' ),
				'not_found'          => __( 'No Video Walls found', 'video-wall-slider' ),
				'not_found_in_trash' => __( 'No Video Walls found in Trash', 'video-wall-slider' ),
			),
			'public'              => false,
			'show_ui'            => true,
			'show_in_menu'       => 'video-wall-slider',
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'supports'           => array( 'title' ),
			'has_archive'        => false,
			'show_in_rest'       => true,
			'rest_base'          => 'video-walls',
		);

		register_post_type( 'video_wall', $args );
	}

	/**
	 * Register taxonomy
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		$args = array(
			'labels'            => array(
				'name'          => __( 'Categories', 'video-wall-slider' ),
				'singular_name' => __( 'Category', 'video-wall-slider' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'rest_base'         => 'video-wall-categories',
		);

		register_taxonomy( 'video_wall_category', 'video_wall', $args );
	}
}
