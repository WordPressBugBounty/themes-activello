<?php
/**
 * Activello Meta Boxes
 *
 */

add_action( 'add_meta_boxes', 'activello_add_custom_box' );
/**
 * Add Meta Boxes.
 *
 * Add Meta box in page and post post types.
 */
function activello_add_custom_box() {
	add_meta_box(
		'post-siderbar-layout', //Unique ID
		__( 'Select layout for this specific Page only ( Note: This setting only reflects if page Template is set as Default Template and Blog Type Templates.)', 'activello' ), //Title
		'activello_sidebar_layout', //Callback function
		'page' //show metabox in pages
	);
	add_meta_box(
		'page-siderbar-layout', //Unique ID
		__( 'Select layout for this specific Post only', 'activello' ), //Title
		'activello_sidebar_layout', //Callback function
		'post', //show metabox in posts
		'side'
	);
	if ( class_exists( 'WooCommerce' ) ) {
		add_meta_box(
			'product-siderbar-layout', //Unique ID
			__( 'Select layout for this specific Product only', 'activello' ), //Title
			'activello_sidebar_layout', //Callback function
			'product', //show metabox in posts
			'side'
		);
	}
}

/**
 * Displays metabox to for sidebar layout
 *
 * @param WP_Post $post The post being edited.
 */
function activello_sidebar_layout( $post ) {
	global $site_layout;

	$layout = get_post_meta( $post->ID, 'site_layout', true );

	// Use nonce for verification.
	wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );
	?>
	<p>
		<label for="site_layout" class="screen-reader-text"><?php esc_html_e( 'Layout', 'activello' ); ?></label>
		<select name="site_layout" id="site_layout">
			<option value=""><?php esc_html_e( 'Default', 'activello' ); ?></option>
			<?php foreach ( (array) $site_layout as $key => $val ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $layout, $key ); ?>><?php echo esc_html( $val ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php
}

add_action( 'save_post', 'activello_save_custom_meta' );
/**
 * Save the custom metabox data.
 *
 * @param int $post_id Post ID.
 */
function activello_save_custom_meta( $post_id ) {
	global $site_layout;

	// Verify the nonce before proceeding.
	if ( ! isset( $_POST['custom_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['custom_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {
		return;
	}

	// Stop WP from clearing custom fields on autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Only the layouts the select offers; anything else clears the override.
	$layout_value = isset( $_POST['site_layout'] ) ? sanitize_key( wp_unslash( $_POST['site_layout'] ) ) : '';

	if ( '' !== $layout_value && is_array( $site_layout ) && array_key_exists( $layout_value, $site_layout ) ) {
		update_post_meta( $post_id, 'site_layout', $layout_value );
	} else {
		delete_post_meta( $post_id, 'site_layout' );
	}
}
