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
	add_meta_box('post-siderbar-layout', //Unique ID
		__( 'Select layout for this specific Page only ( Note: This setting only reflects if page Template is set as Default Template and Blog Type Templates.)', 'activello' ), //Title
		'activello_sidebar_layout', //Callback function
		'page' //show metabox in pages
	);
	add_meta_box('page-siderbar-layout', //Unique ID
		__( 'Select layout for this specific Post only', 'activello' ), //Title
		'activello_sidebar_layout', //Callback function
		'post', //show metabox in posts
		'side'
	);
	if ( class_exists( 'WooCommerce' ) ) {
		add_meta_box('product-siderbar-layout', //Unique ID
			__( 'Select layout for this specific Product only', 'activello' ), //Title
			'activello_sidebar_layout', //Callback function
			'product', //show metabox in posts
			'side'
		);
	}

}

/****************************************************************************************/

global $site_layout;

/****************************************************************************************/

/**
 * Displays metabox to for sidebar layout
 */
function activello_sidebar_layout() {
	global $site_layout, $post;
	// Use nonce for verification
	wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' ); ?>
	
	<table id="sidebar-metabox" class="form-table" width="100%">
		<tbody>
			<tr>
				<label class="description"><?php
					$layout = get_post_meta( $post->ID, 'site_layout', true );?>                        
					<select name="site_layout" id="site_layout">
						<option value=""><?php _e( 'Default', 'activello' ); ?></option><?php
						foreach ( $site_layout as $key => $val ) { ?>
						<option value="<?php echo $key; ?>" <?php selected( $layout, $key ); ?> ><?php echo $val; ?></option><?php
						}?>
					</select>                           
				</label>
			</tr>
		</tbody>
	</table><?php
}

/****************************************************************************************/


add_action( 'save_post', 'activello_save_custom_meta' );
/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function activello_save_custom_meta( $post_id ) {
	global $site_layout, $post;

	// Verify the nonce before proceeding.
	if ( ! isset( $_POST['custom_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['custom_meta_box_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	// Stop WP from clearing custom fields on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Validate user permissions
	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';
	
	if ( 'page' === $post_type ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Sanitize and save the site layout value
	if ( isset( $_POST['site_layout'] ) && ! empty( $_POST['site_layout'] ) ) {
		$layout_value = sanitize_text_field( $_POST['site_layout'] );
		update_post_meta( $post_id, 'site_layout', $layout_value );
	} else {
		delete_post_meta( $post_id, 'site_layout' );
	}
}
