<?php
/**
 * Block editor integration.
 *
 * Activello is a classic theme, but posts and pages are written in the block
 * editor, so the editor should show them at the width they render at.
 *
 * @package activello
 */

if ( ! function_exists( 'activello_editor_layout_sizes' ) ) :
	/**
	 * Size the block editor like the column the post will actually render in.
	 *
	 * theme.json declares 1060px, the full-width column, so that nothing on the
	 * front end is ever narrowed by it. Posts and pages default to a layout
	 * with a sidebar, where the column is 697px, so the editor would show lines
	 * half as long again as the published post. In the editor the sizes follow
	 * the post's own layout instead; the front end is sized by style.css.
	 *
	 * @param WP_Theme_JSON_Data $theme_json The theme's theme.json data.
	 * @return WP_Theme_JSON_Data
	 */
	function activello_editor_layout_sizes( $theme_json ) {
		global $pagenow;

		if ( ! is_admin() || ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return $theme_json;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only: which post the editor is open on.
		$post_id = ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) ? absint( $_GET['post'] ) : 0;
		$size    = 'full-width' === activello_get_post_layout( $post_id ) ? '1060px' : '697px';

		return $theme_json->update_with(
			array(
				'version'  => 2,
				'settings' => array(
					'layout' => array(
						'contentSize' => $size,
						'wideSize'    => $size,
					),
				),
			)
		);
	}
endif;
add_filter( 'wp_theme_json_data_theme', 'activello_editor_layout_sizes' );

if ( ! function_exists( 'activello_register_block_pattern_category' ) ) :
	/**
	 * Group the theme's block patterns (patterns/*.php, registered by core).
	 */
	function activello_register_block_pattern_category() {
		register_block_pattern_category(
			'activello',
			array( 'label' => esc_html__( 'Activello', 'activello' ) )
		);
	}
endif;
add_action( 'init', 'activello_register_block_pattern_category' );
