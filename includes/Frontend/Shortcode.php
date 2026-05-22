<?php
/**
 * Shortcode Handler
 *
 * @package VideoWallSlider
 * @subpackage Frontend
 */

namespace VideoWallSlider\Frontend;

/**
 * Handles video wall shortcode
 */
class Shortcode {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'video_wall', array( $this, 'render' ) );
	}

	/**
	 * Render shortcode
	 *
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => 0,
			),
			$atts,
			'video_wall'
		);

		$wall_id = intval( $atts['id'] );

		if ( 0 === $wall_id ) {
			return '';
		}

		$post = get_post( $wall_id );

		if ( ! $post || 'video_wall' !== $post->post_type ) {
			return '';
		}

		$videos  = get_post_meta( $wall_id, '_vws_videos', true );
		$videos  = is_array( $videos ) ? $videos : array();
		$settings = get_post_meta( $wall_id, '_vws_settings', true );
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
			)
		);

		if ( empty( $videos ) ) {
			return '<p>' . esc_html__( 'No videos added to this wall.', 'video-wall-slider' ) . '</p>';
		}

		return $this->render_slider( $wall_id, $videos, $settings );
	}

	/**
	 * Render slider HTML
	 *
	 * @param int   $wall_id Wall post ID
	 * @param array $videos Video URLs
	 * @param array $settings Wall settings
	 * @return string
	 */
	private function render_slider( $wall_id, $videos, $settings ) {
		$columns = $settings['columns'];
		$spacing = $settings['spacing'];
		$radius  = $settings['border_radius'];
		$hover   = sanitize_html_class( $settings['hover_effect'] );

		$css_vars = sprintf(
			'--vws-columns: %d; --vws-spacing: %dpx; --vws-radius: %dpx; --vws-hover: %s;',
			$columns,
			$spacing,
			$radius,
			esc_attr( $hover )
		);

		$autoplay = $settings['autoplay'] ? 1 : 0;
		$mute     = $settings['mute'] ? 1 : 0;
		$loop     = $settings['loop'] ? 1 : 0;
		$lazy     = $settings['lazy_load'] ? 1 : 0;

		ob_start();
		?>
		<div class="vws-container" style="<?php echo esc_attr( $css_vars ); ?>" data-wall-id="<?php echo esc_attr( $wall_id ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-mute="<?php echo esc_attr( $mute ); ?>" data-loop="<?php echo esc_attr( $loop ); ?>" data-lazy="<?php echo esc_attr( $lazy ); ?>">
			<div class="vws-wrapper">
				<div class="vws-scroller">
					<?php foreach ( $videos as $index => $video_url ) : ?>
						<?php $video_id = $this->extract_youtube_id( $video_url ); ?>
						<?php if ( $video_id ) : ?>
							<div class="vws-slide" data-video-id="<?php echo esc_attr( $video_id ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
								<div class="vws-video-wrapper">
									<iframe
										class="vws-video-iframe"
										data-video-id="<?php echo esc_attr( $video_id ); ?>"
										src="https://www.youtube.com/embed/<?php echo esc_attr( $video_id ); ?>?enablejsapi=1&controls=0&modestbranding=1&rel=0&showinfo=0"
										width="100%"
										height="100%"
										frameborder="0"
										allowfullscreen
										loading="<?php echo $lazy ? 'lazy' : 'eager'; ?>"
									></iframe>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Extract YouTube video ID from URL
	 *
	 * @param string $url YouTube URL
	 * @return string|false
	 */
	private function extract_youtube_id( $url ) {
		$patterns = array(
			'/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
			'/(?:https?:\/\/)?(?:www\.)?youtu\.be\/([a-zA-Z0-9_-]{11})/',
			'/^([a-zA-Z0-9_-]{11})$/',
		);

		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $url, $matches ) ) {
				return $matches[1];
			}
		}

		return false;
	}
}
