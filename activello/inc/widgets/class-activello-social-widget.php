<?php
/**
 * Activello Social widget: the Social Menu as icons.
 *
 * @package activello
 */

/**
 * Social icons widget.
 */
class Activello_Social_Widget extends WP_Widget {

	/**
	 * Register the widget.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'activello-social',
			'description'                 => esc_html__( 'Activello theme widget to display social media icons', 'activello' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'activello-social', esc_html__( 'Activello Social Widget', 'activello' ), $widget_ops );
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Sidebar arguments.
	 * @param array $instance Saved settings.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow us', 'activello' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().
		}
		?>

		<!-- social icons -->
		<div class="social-icons sticky-sidebar-social">
			<?php activello_social_icons(); ?>
		</div><!-- end social icons -->

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
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow us', 'activello' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'activello' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * The widget had no update(), so the title was stored exactly as typed and
	 * printed unescaped.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title' => isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '',
		);
	}
}
