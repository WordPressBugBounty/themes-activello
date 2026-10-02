<?php
/**
 * Activello Recent Posts widget: the latest posts with thumbnails.
 *
 * @package activello
 */

/**
 * Recent posts widget.
 */
class Activello_Recent_Posts extends WP_Widget {

	/**
	 * Register the widget.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'activello-recent-posts',
			'description'                 => esc_html__( 'Activello recent posts widget with thumbnails', 'activello' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'activello_recent_posts', esc_html__( 'Activello Recent Posts Widget', 'activello' ), $widget_ops );
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Sidebar arguments.
	 * @param array $instance Saved settings.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'activello' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$limit = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 5;

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().
		}

		/*
		 * Posts with an empty body used to be skipped, so the widget showed
		 * fewer posts than its limit (or none) on sites that publish image or
		 * gallery posts without text.
		 */
		$recent = new WP_Query(
			array(
				'posts_per_page'      => $limit,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		?>

		<!-- recent posts -->
		<div class="recent-posts-wrapper">
			<?php
			while ( $recent->have_posts() ) :
				$recent->the_post();
				$activello_format = (string) get_post_format();
				?>

				<!-- post -->
				<div class="post">

					<!-- image -->
					<div class="post-image <?php echo esc_attr( $activello_format ); ?>">
						<?php if ( 'quote' !== $activello_format && has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'thumbnail', array( 'alt' => '' ) ); ?></a>
						<?php endif; ?>
					</div> <!-- end post image -->

					<!-- content -->
					<div class="post-content">
						<a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ); ?></a>
						<span class="date">- <?php echo esc_html( get_the_date() ); ?></span>
					</div><!-- end content -->
				</div><!-- end post -->

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div> <!-- end posts wrapper -->

		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().
	}

	/**
	 * Settings form.
	 *
	 * @param array $instance Saved settings.
	 * @return void
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'activello' );
		$limit = isset( $instance['limit'] ) ? $instance['limit'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'activello' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Limit Posts Number', 'activello' ); ?></label>
			<input type="number" min="1" value="<?php echo esc_attr( $limit ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" class="widefat" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['limit'] = ! empty( $new_instance['limit'] ) ? (string) absint( $new_instance['limit'] ) : '';

		return $instance;
	}
}
