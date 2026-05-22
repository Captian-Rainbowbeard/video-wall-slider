<?php
/**
 * Meta Boxes Class
 *
 * @package VideoWallSlider
 * @subpackage Admin
 */

namespace VideoWallSlider\Admin;

/**
 * Registers meta boxes for video walls
 */
class MetaBoxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes_video_wall', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post_video_wall', array( $this, 'save_meta_boxes' ) );
	}

	/**
	 * Register meta boxes
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'vws_videos',
			__( 'Video URLs', 'video-wall-slider' ),
			array( $this, 'render_videos_meta_box' ),
			'video_wall',
			'normal',
			'high'
		);

		add_meta_box(
			'vws_settings',
			__( 'Slider Settings', 'video-wall-slider' ),
			array( $this, 'render_settings_meta_box' ),
			'video_wall',
			'side',
			'high'
		);

		add_meta_box(
			'vws_shortcode',
			__( 'Shortcode', 'video-wall-slider' ),
			array( $this, 'render_shortcode_meta_box' ),
			'video_wall',
			'side'
		);
	}

	/**
	 * Render videos meta box
	 *
	 * @param \WP_Post $post Post object
	 * @return void
	 */
	public function render_videos_meta_box( $post ) {
		wp_nonce_field( 'vws_save_videos', 'vws_videos_nonce' );

		$videos = get_post_meta( $post->ID, '_vws_videos', true );
		$videos = is_array( $videos ) ? $videos : array();
		?>
		<div id="vws-videos-container" class="vws-videos-container">
			<?php foreach ( $videos as $index => $video ) : ?>
				<div class="vws-video-item" data-index="<?php echo esc_attr( $index ); ?>">
					<span class="vws-drag-handle">⋮⋮</span>
					<input type="text" class="vws-video-url" name="vws_video_url[]" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo esc_attr( $video ); ?>" />
					<button type="button" class="button vws-remove-video"><?php esc_html_e( 'Remove', 'video-wall-slider' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-secondary" id="vws-add-video"><?php esc_html_e( '+ Add Video', 'video-wall-slider' ); ?></button>
		<?php
	}

	/**
	 * Render settings meta box
	 *
	 * @param \WP_Post $post Post object
	 * @return void
	 */
	public function render_settings_meta_box( $post ) {
		wp_nonce_field( 'vws_save_settings', 'vws_settings_nonce' );

		$settings = get_post_meta( $post->ID, '_vws_settings', true );
		$settings = wp_parse_args(
			$settings,
			array(
				'columns'          => 4,
				'autoplay'         => 1,
				'mute'             => 1,
				'loop'             => 1,
				'spacing'          => 10,
				'border_radius'    => 8,
				'lazy_load'        => 1,
				'hover_effect'     => 'scale',
				'scroll_type'      => 'smooth',
			)
		);
		?>
		<p>
			<label for="vws_columns"><?php esc_html_e( 'Visible Columns:', 'video-wall-slider' ); ?></label>
			<input type="number" id="vws_columns" name="vws_columns" min="1" max="6" value="<?php echo esc_attr( $settings['columns'] ); ?>" />
		</p>

		<p>
			<label>
				<input type="checkbox" name="vws_autoplay" value="1" <?php checked( $settings['autoplay'], 1 ); ?> />
				<?php esc_html_e( 'Autoplay', 'video-wall-slider' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="vws_mute" value="1" <?php checked( $settings['mute'], 1 ); ?> />
				<?php esc_html_e( 'Mute Videos', 'video-wall-slider' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="vws_loop" value="1" <?php checked( $settings['loop'], 1 ); ?> />
				<?php esc_html_e( 'Loop Videos', 'video-wall-slider' ); ?>
			</label>
		</p>

		<p>
			<label for="vws_spacing"><?php esc_html_e( 'Spacing (px):', 'video-wall-slider' ); ?></label>
			<input type="number" id="vws_spacing" name="vws_spacing" min="0" max="50" value="<?php echo esc_attr( $settings['spacing'] ); ?>" />
		</p>

		<p>
			<label for="vws_border_radius"><?php esc_html_e( 'Border Radius (px):', 'video-wall-slider' ); ?></label>
			<input type="number" id="vws_border_radius" name="vws_border_radius" min="0" max="50" value="<?php echo esc_attr( $settings['border_radius'] ); ?>" />
		</p>

		<p>
			<label>
				<input type="checkbox" name="vws_lazy_load" value="1" <?php checked( $settings['lazy_load'], 1 ); ?> />
				<?php esc_html_e( 'Lazy Load', 'video-wall-slider' ); ?>
			</label>
		</p>

		<p>
			<label for="vws_hover_effect"><?php esc_html_e( 'Hover Effect:', 'video-wall-slider' ); ?></label>
			<select id="vws_hover_effect" name="vws_hover_effect">
				<option value="none" <?php selected( $settings['hover_effect'], 'none' ); ?>><?php esc_html_e( 'None', 'video-wall-slider' ); ?></option>
				<option value="scale" <?php selected( $settings['hover_effect'], 'scale' ); ?>><?php esc_html_e( 'Scale', 'video-wall-slider' ); ?></option>
				<option value="overlay" <?php selected( $settings['hover_effect'], 'overlay' ); ?>><?php esc_html_e( 'Overlay', 'video-wall-slider' ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Render shortcode meta box
	 *
	 * @param \WP_Post $post Post object
	 * @return void
	 */
	public function render_shortcode_meta_box( $post ) {
		$shortcode = '[video_wall id="' . intval( $post->ID ) . '"]';
		?>
		<div class="vws-shortcode-box">
			<input type="text" readonly="readonly" value="<?php echo esc_attr( $shortcode ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Copy this shortcode and paste it into any page or post.', 'video-wall-slider' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Save meta boxes
	 *
	 * @param int $post_id Post ID
	 * @return void
	 */
	public function save_meta_boxes( $post_id ) {
		// Verify nonce for videos
		if ( ! isset( $_POST['vws_videos_nonce'] ) || ! wp_verify_nonce( $_POST['vws_videos_nonce'], 'vws_save_videos' ) ) {
			return;
		}

		// Sanitize and save videos
		if ( isset( $_POST['vws_video_url'] ) && is_array( $_POST['vws_video_url'] ) ) {
			$videos = array_map( 'esc_url_raw', $_POST['vws_video_url'] );
			$videos = array_filter( $videos );
			update_post_meta( $post_id, '_vws_videos', $videos );
		} else {
			delete_post_meta( $post_id, '_vws_videos' );
		}

		// Verify nonce for settings
		if ( ! isset( $_POST['vws_settings_nonce'] ) || ! wp_verify_nonce( $_POST['vws_settings_nonce'], 'vws_save_settings' ) ) {
			return;
		}

		// Sanitize and save settings
		$settings = array(
			'columns'       => max( 1, intval( $_POST['vws_columns'] ?? 4 ) ),
			'autoplay'      => isset( $_POST['vws_autoplay'] ) ? 1 : 0,
			'mute'          => isset( $_POST['vws_mute'] ) ? 1 : 0,
			'loop'          => isset( $_POST['vws_loop'] ) ? 1 : 0,
			'spacing'       => max( 0, intval( $_POST['vws_spacing'] ?? 10 ) ),
			'border_radius' => max( 0, intval( $_POST['vws_border_radius'] ?? 8 ) ),
			'lazy_load'     => isset( $_POST['vws_lazy_load'] ) ? 1 : 0,
			'hover_effect'  => sanitize_text_field( $_POST['vws_hover_effect'] ?? 'scale' ),
		);

		update_post_meta( $post_id, '_vws_settings', $settings );
	}
}
