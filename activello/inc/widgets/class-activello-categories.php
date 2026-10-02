<?php
/**
 * Activello Categories widget: the most used categories, optionally with counts.
 *
 * @package activello
 */

/**
 * Categories widget.
 */
class Activello_Categories extends WP_Widget {

	/**
	 * Register the widget.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'activello-cats',
			'description'                 => esc_html__( 'Activello widget to display categories', 'activello' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'activello-cats', esc_html__( 'Activello Categories', 'activello' ), $widget_ops );
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Sidebar arguments.
	 * @param array $instance Saved settings.
	 */
	public function widget( $args, $instance ) {
		$title        = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Categories', 'activello' );
		$title        = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$enable_count = ! empty( $instance['enable_count'] );
		$limit        = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 4;

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from register_sidebar().
		}

		$categories = wp_list_categories(
			array(
				'echo'       => 0,
				'show_count' => $enable_count ? 1 : 0,
				'title_li'   => '',
				'depth'      => 1,
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => $limit,
			)
		);

		/*
		 * Wrap each post count in a <span> so it can sit at the right edge.
		 * Only the "(12)" core prints after the link is matched: replacing
		 * every bracket also mangled category names with brackets in them.
		 */
		$categories = preg_replace( '#</a>\s*\(([^()<]*)\)#', '</a> <span>$1</span>', $categories );
		?>

		<div class="cats-widget">
			<ul><?php echo wp_kses_post( $categories ); ?></ul>
		</div><!-- end widget content -->

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
		$title        = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Categories', 'activello' );
		$limit        = isset( $instance['limit'] ) ? $instance['limit'] : 4;
		$enable_count = isset( $instance['enable_count'] ) ? $instance['enable_count'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'activello' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Limit Categories', 'activello' ); ?></label>
			<input type="number" min="1" value="<?php echo esc_attr( $limit ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" class="widefat" />
		</p>
		<p>
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'enable_count' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'enable_count' ) ); ?>" value="1" <?php checked( '' !== (string) $enable_count ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'enable_count' ) ); ?>"><?php esc_html_e( 'Enable Posts Count', 'activello' ); ?></label>
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
		$instance                 = array();
		$instance['title']        = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['limit']        = ! empty( $new_instance['limit'] ) ? (string) absint( $new_instance['limit'] ) : '';
		$instance['enable_count'] = ! empty( $new_instance['enable_count'] ) ? '1' : '';

		return $instance;
	}
}
