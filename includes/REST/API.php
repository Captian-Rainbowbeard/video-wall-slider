<?php
/**
 * REST API Handler
 *
 * @package VideoWallSlider
 * @subpackage REST
 */

namespace VideoWallSlider\REST;

/**
 * Registers REST API endpoints
 */
class API {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes
	 *
	 * @return void
	 */
	public function register_routes() {
		// Get wall videos
		register_rest_route(
			'vws/v1',
			'/walls/(?P<id>\d+)/videos',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_wall_videos' ),
				'permission_callback' => '__return_true',
			)
		);

		// Update wall videos
		register_rest_route(
			'vws/v1',
			'/walls/(?P<id>\d+)/videos',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'update_wall_videos' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		// Get wall settings
		register_rest_route(
			'vws/v1',
			'/walls/(?P<id>\d+)/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_wall_settings' ),
				'permission_callback' => '__return_true',
			)
		);

		// List all walls
		register_rest_route(
			'vws/v1',
			'/walls',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'list_walls' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Get wall videos
	 *
	 * @param \WP_REST_Request $request REST request
	 * @return \WP_REST_Response
	 */
	public function get_wall_videos( $request ) {
		$wall_id = intval( $request->get_param( 'id' ) );
		$videos  = get_post_meta( $wall_id, '_vws_videos', true );
		$videos  = is_array( $videos ) ? $videos : array();

		return rest_ensure_response( $videos );
	}

	/**
	 * Update wall videos
	 *
	 * @param \WP_REST_Request $request REST request
	 * @return \WP_REST_Response
	 */
	public function update_wall_videos( $request ) {
		$wall_id = intval( $request->get_param( 'id' ) );
		$videos  = $request->get_json_params();

		if ( ! is_array( $videos ) ) {
			return new \WP_REST_Response(
				array( 'error' => 'Invalid videos array' ),
				400
			);
		}

		$sanitized = array_map( 'esc_url_raw', $videos );
		$sanitized = array_filter( $sanitized );

		update_post_meta( $wall_id, '_vws_videos', $sanitized );

		return rest_ensure_response(
			array( 'success' => true, 'videos' => $sanitized )
		);
	}

	/**
	 * Get wall settings
	 *
	 * @param \WP_REST_Request $request REST request
	 * @return \WP_REST_Response
	 */
	public function get_wall_settings( $request ) {
		$wall_id  = intval( $request->get_param( 'id' ) );
		$settings = get_post_meta( $wall_id, '_vws_settings', true );
		$settings = is_array( $settings ) ? $settings : array();

		return rest_ensure_response( $settings );
	}

	/**
	 * List all walls
	 *
	 * @param \WP_REST_Request $request REST request
	 * @return \WP_REST_Response
	 */
	public function list_walls( $request ) {
		$walls = get_posts(
			array(
				'post_type'      => 'video_wall',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		$data = array();
		foreach ( $walls as $wall ) {
			$videos  = get_post_meta( $wall->ID, '_vws_videos', true );
			$data[] = array(
				'id'       => $wall->ID,
				'title'    => $wall->post_title,
				'status'   => $wall->post_status,
				'videos'   => is_array( $videos ) ? count( $videos ) : 0,
				'shortcode' => '[video_wall id="' . $wall->ID . '"]',
			);
		}

		return rest_ensure_response( $data );
	}
}
