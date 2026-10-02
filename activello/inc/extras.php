<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package activello
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function activello_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'activello_page_menu_args' );

if ( ! function_exists( 'activello_get_layout' ) ) :
	/**
	 * The layout of the current view: pull-right (left sidebar), side-right
	 * (right sidebar), no-sidebar or full-width.
	 *
	 * The single source of truth for header.php, sidebar.php and the body
	 * classes. A post's or page's own layout (the "site_layout" meta box)
	 * wins over the Customizer default; the Full-width page template is
	 * always full width. Each of those places used to work this out on its
	 * own, and the body class ignored the per-post layout entirely.
	 *
	 * @return string
	 */
	function activello_get_layout() {
		$layout = is_singular() ? activello_get_post_layout( get_queried_object_id() ) : activello_get_post_layout( 0 );

		/**
		 * Filters the layout of the current view.
		 *
		 * @param string $layout pull-right, side-right, no-sidebar or full-width.
		 */
		return apply_filters( 'activello_layout', $layout );
	}
endif;

if ( ! function_exists( 'activello_get_post_layout' ) ) :
	/**
	 * The layout a post renders with; 0 for the Customizer default.
	 *
	 * Also used by the block editor, which is not a front-end view, so it works
	 * from the post rather than from conditional tags.
	 *
	 * @param int $post_id Post ID, or 0.
	 * @return string pull-right, side-right, no-sidebar or full-width.
	 */
	function activello_get_post_layout( $post_id ) {
		$layouts = array( 'pull-right', 'side-right', 'no-sidebar', 'full-width' );
		$layout  = '';

		if ( $post_id ) {
			$layout = in_array( get_page_template_slug( $post_id ), array( 'page-fullwidth.php', 'page-blocks.php' ), true ) ? 'full-width' : get_post_meta( $post_id, 'site_layout', true );
		}

		if ( ! in_array( $layout, $layouts, true ) ) {
			$layout = get_theme_mod( 'activello_sidebar_position', 'side-right' );
		}

		return in_array( $layout, $layouts, true ) ? $layout : 'side-right';
	}
endif;

if ( ! function_exists( 'activello_show_sidebar' ) ) :
	/**
	 * Whether the current view shows the sidebar.
	 *
	 * @return bool
	 */
	function activello_show_sidebar() {
		return ! in_array( activello_get_layout(), array( 'no-sidebar', 'full-width' ), true );
	}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function activello_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// From the layout in use, so a post's own layout is reflected too.
	$layout = activello_get_layout();
	if ( 'pull-right' === $layout ) {
		$classes[] = 'has-sidebar-left';
	} elseif ( 'no-sidebar' === $layout ) {
		$classes[] = 'has-no-sidebar';
	} elseif ( 'full-width' === $layout ) {
		$classes[] = 'has-full-width';
	} else {
		$classes[] = 'has-sidebar-right';
	}

	// Custom Customizer colours switch on the extra rules at the end of style.css.
	foreach ( array_keys( activello_custom_colors() ) as $slug ) {
		$classes[] = 'activello-custom-' . $slug;
	}

	$blog_layout = get_theme_mod( 'activello_blog_layout', 'default' );
	if ( is_home() && 'default' === $blog_layout ) {
		$classes[] = 'half-posts';
	}

	return $classes;
}
add_filter( 'body_class', 'activello_body_classes' );


// Mark Posts/Pages as Untiled when no title is used
add_filter( 'the_title', 'activello_title' );

function activello_title( $title ) {
	if ( '' === (string) $title ) {
		return __( 'Untitled', 'activello' );
	} else {
		return $title;
	}
}

/**
 * Password protected post form using Bootstrap classes
 */
add_filter( 'the_password_form', 'activello_custom_password_form', 10, 3 );

/**
 * Replace core's password form with a Bootstrap input group.
 *
 * @param string       $output           Core's form, replaced.
 * @param WP_Post|null $post             Post being unlocked (WordPress 5.8+).
 * @param string       $invalid_password Error for a wrong password (WordPress 6.8+).
 * @return string
 */
function activello_custom_password_form( $output = '', $post = null, $invalid_password = '' ) {
	$post  = get_post( $post );
	$label = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
	$error = '';
	$aria  = '';

	if ( '' !== $invalid_password ) {
		$error = '<div class="post-password-form-invalid-password" role="alert"><p id="error-' . esc_attr( $label ) . '">' . esc_html( $invalid_password ) . '</p></div>';
		$aria  = ' aria-describedby="error-' . esc_attr( $label ) . '"';
	}

	/*
	 * Core sends the visitor back here after a wrong password. Without it they
	 * landed on the referring page and never saw the "Invalid password" notice.
	 */
	$redirect = empty( $post->ID ) ? '' : '<input type="hidden" name="redirect_to" value="' . esc_attr( get_permalink( $post->ID ) ) . '" />';

	$o = '<form class="protected-post-form post-password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">' . $redirect . $error . '
			<div class="row">
				<div class="col-lg-10">
					<p>' . esc_html__( 'This post is password protected. To view it please enter your password below:', 'activello' ) . '</p>
					<label for="' . esc_attr( $label ) . '">' . esc_html__( 'Password:', 'activello' ) . ' </label>
					<div class="input-group">
						<input class="form-control" name="post_password" id="' . esc_attr( $label ) . '" type="password" spellcheck="false" required' . $aria . '>
						<span class="input-group-btn"><button type="submit" class="btn btn-default" name="Submit">' . esc_html__( 'Submit', 'activello' ) . '</button></span>
					</div>
				</div>
			</div>
		</form>';
	return $o;
}

// Add Bootstrap classes for table
add_filter( 'the_content', 'activello_add_custom_table_class' );
function activello_add_custom_table_class( $content ) {
	return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'activello_site_name' ) ) :
	/**
	 * The custom logo, or the linked site title, as the "Show" option asks.
	 *
	 * Used by header.php and by the Customizer's live preview of the title.
	 */
	function activello_site_name() {
		$header_show = get_theme_mod( 'header_show', 'logo-text' );
		$show_logo   = ! in_array( $header_show, array( 'title-only', 'title-text' ), true );

		if ( $show_logo && has_custom_logo() ) {
			the_custom_logo();
			return;
		}
		?>
		<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		<?php
	}
endif;

if ( ! function_exists( 'activello_header_menu' ) ) :
	/**
 * Header menu (should you choose to use one)
 */
	function activello_header_menu() {

		// display the WordPress Custom Menu if available
		wp_nav_menu(
			array(
				'menu'            => 'primary',
				'theme_location'  => 'primary',
				'container'       => 'div',
				'container_id'    => 'activello-primary-menu',
				'container_class' => 'collapse navbar-collapse navbar-ex1-collapse',
				'menu_class'      => 'nav navbar-nav',
				'fallback_cb'     => 'Activello_Wp_Bootstrap_Navwalker::fallback',
				'walker'          => new Activello_Wp_Bootstrap_Navwalker(),
			)
		);
	}
endif;

if ( ! function_exists( 'activello_featured_slider' ) ) :
	/**
 * Featured image slider, displayed on front page for static page and blog
 */
	function activello_featured_slider() {
		if ( ( is_home() || is_front_page() ) && get_theme_mod( 'activello_featured_hide' ) ) {

			wp_enqueue_style( 'activello-flexslider-css' );
			wp_enqueue_script( 'activello-flexslider-js' );
			wp_enqueue_script( 'activello-flexslider' );

			echo '<div class="flexslider">';
			echo '<ul class="slides">';

			$slidecat    = get_theme_mod( 'activello_featured_cat' );
			$slidelimit  = get_theme_mod( 'activello_featured_limit', -1 );
			$slider_args = array(
				'cat'            => $slidecat,
				'posts_per_page' => $slidelimit,
				'meta_query'     => array(
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			);
			$query       = new WP_Query( $slider_args );
			if ( $query->have_posts() ) :

				while ( $query->have_posts() ) :
					$query->the_post();
					if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) :
						echo '<li>';
						$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						if ( $feat_image_url && function_exists( 'jetpack_photon_url' ) && class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
							// Jetpack's image CDN crops to the slider size. The image
							// had no alt text and no dimensions.
							$photon_url = jetpack_photon_url( $feat_image_url[0], array( 'resize' => '1920,550' ) );
							$alt        = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
							echo '<img src="' . esc_url( $photon_url ) . '" width="1920" height="550" alt="' . esc_attr( $alt ) . '">';
						} else {
								echo get_the_post_thumbnail( get_the_ID(), 'activello-slider' );
						}
								echo '<div class="flex-caption">';
								echo get_the_category_list(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core category list markup.
						if ( '' !== get_the_title() ) {
							echo '<a href="' . esc_url( get_permalink() ) . '"><h2 class="entry-title">' . esc_html( get_the_title() ) . '</h2></a>';
						}
								echo '<div class="read-more"><a href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'activello' ) . '</a></div>';
								echo '</div>';
								echo '</li>';
						endif;
					endwhile;
				wp_reset_postdata();
			endif;
			echo '</ul>';
			echo ' </div>';
		}
	}
endif;

/**
 * function to show the footer info, copyright information
 */
function activello_footer_info() {
	/* translators: 1: link to Colorlib, 2: link to WordPress */
	printf( esc_html__( 'Theme by %1$s Powered by %2$s', 'activello' ), '<a href="https://colorlib.com/" target="_blank">Colorlib</a>', '<a href="https://wordpress.org/" target="_blank">WordPress</a>' );
}


/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function activello_caption( $output, $attr, $content ) {
	if ( is_feed() ) {
		return $output;
	}

	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => '',
	);

	$attr = shortcode_atts( $defaults, $attr );

	// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	if ( $attr['width'] < 1 || empty( $attr['caption'] ) ) {
		return $content;
	}

	// Set up the attributes for the caption <figure>
	$attributes  = ( ! empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="thumbnail wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width: ' . ( (int) $attr['width'] + 10 ) . 'px"';

	$output  = '<figure' . $attributes . '>';
	$output .= do_shortcode( $content );
	$output .= '<figcaption class="caption wp-caption-text">' . wp_kses_post( $attr['caption'] ) . '</figcaption>';
	$output .= '</figure>';

	return $output;
}
add_filter( 'img_caption_shortcode', 'activello_caption', 10, 3 );

/**
 * Skype URI support for social media icons
 */
function activello_allow_skype_protocol( $protocols ) {
	$protocols[] = 'skype';
	return $protocols;
}
add_filter( 'kses_allowed_protocols', 'activello_allow_skype_protocol' );

/*
 * This display blog description from wp customizer setting.
 */
function activello_cats() {
	$cats    = array();
	$cats[0] = __( 'All', 'activello' );

	foreach ( get_categories() as $categories => $category ) {
		$cats[ $category->term_id ] = $category->name;
	}
	return $cats;
}

/**
 * Custom comment template
 */
function activello_cb_comment( $comment, $args, $depth ) {

	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 'li' or 'div', set above. ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<?php if ( 'div' !== $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
	<?php endif; ?>

	<div class="comment-author vcard">
		<?php
		if ( 0 !== (int) $args['avatar_size'] ) {
			echo get_avatar( $comment, $args['avatar_size'] );
		}
		?>
		<?php
		/* translators: %s: comment author link */
		printf( wp_kses_post( __( '<cite class="fn">%s</cite> <span class="says">says:</span>', 'activello' ) ), wp_kses_post( get_comment_author_link( $comment ) ) );
		?>
		<?php
			$comments_reply_args = array(
				'add_below' => $add_below,
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
			);
			comment_reply_link( array_merge( $args, $comments_reply_args ) );
			?>
		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>"><time datetime="<?php echo esc_attr( get_comment_time( 'c' ) ); ?>">
			<?php
			/* translators: 1: date, 2: time */
			printf( esc_html__( '%1$s at %2$s', 'activello' ), esc_html( get_comment_date( '', $comment ) ), esc_html( get_comment_time() ) );
			?>
			</time></a>
			<?php
			edit_comment_link( esc_html__( 'Edit', 'activello' ), '  ', '' );
			?>
		</div>
	</div>

	<?php if ( '0' === (string) $comment->comment_approved ) : ?>
		<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'activello' ); ?></em>
		<br />
	<?php endif; ?>

	<?php comment_text(); ?>

	<?php if ( 'div' !== $args['style'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
}

/**
 * Validate a colour value at output time.
 *
 * Customizer sanitizers reject invalid input on save, but options stored by
 * older theme versions may hold arbitrary text, so never trust a stored value
 * when printing it into the inline <style> block.
 *
 * @param string $color Stored colour value.
 * @return string A safe hex colour, or '' if the value is not one.
 */
function activello_css_color( $color ) {
	$hex = sanitize_hex_color( $color );
	return $hex ? $hex : '';
}

if ( ! function_exists( 'activello_custom_colors' ) ) :
	/**
	 * The colour theme mods that hold a valid custom colour, keyed by palette slug.
	 *
	 * @return string[]
	 */
	function activello_custom_colors() {
		$mods   = array(
			'accent_color'       => 'accent',
			'social_color'       => 'social',
			'social_hover_color' => 'social-hover',
		);
		$colors = array();

		foreach ( $mods as $mod => $slug ) {
			$color = activello_css_color( get_theme_mod( $mod ) );
			if ( $color ) {
				$colors[ $slug ] = $color;
			}
		}

		return $colors;
	}
endif;

if ( ! function_exists( 'activello_theme_json_customizer_colors' ) ) :
	/**
	 * Put the Customizer colours into the theme.json palette.
	 *
	 * theme.json is the one source for the theme's colours: style.css and the
	 * editor styles read its --wp--preset--color--* properties, and the block
	 * editor's colour pickers show the same swatches. The custom colours used
	 * to be printed in a second <style> block on the front end only, so the
	 * editor never saw them and elements outside that block's list kept the
	 * default purple.
	 *
	 * @param WP_Theme_JSON_Data $theme_json The theme's theme.json data.
	 * @return WP_Theme_JSON_Data
	 */
	function activello_theme_json_customizer_colors( $theme_json ) {
		$overrides = activello_custom_colors();

		if ( empty( $overrides ) ) {
			return $theme_json;
		}

		$data    = $theme_json->get_data();
		$palette = isset( $data['settings']['color']['palette'] ) ? $data['settings']['color']['palette'] : array();
		// The resolver keys presets by origin; a bare list is the file's own shape.
		if ( isset( $palette['theme'] ) ) {
			$palette = $palette['theme'];
		}

		foreach ( $palette as $i => $entry ) {
			if ( isset( $entry['slug'], $overrides[ $entry['slug'] ] ) ) {
				$palette[ $i ]['color'] = $overrides[ $entry['slug'] ];
			}
		}

		return $theme_json->update_with(
			array(
				'version'  => 2,
				'settings' => array(
					'color' => array(
						'palette' => array_values( $palette ),
					),
				),
			)
		);
	}
endif;
add_filter( 'wp_theme_json_data_theme', 'activello_theme_json_customizer_colors' );

/**
 * Print the legacy "custom_css" theme mod, if a site still has one.
 *
 * Versions before core Custom CSS stored it as a theme mod; activello_setup()
 * migrates it, so this is a fallback. The Customizer colours that used to be
 * printed here now come from the theme.json palette and style.css.
 */
if ( ! function_exists( 'get_activello_theme_setting' ) ) {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- name kept: child themes unhook it.
	function get_activello_theme_setting() {
		$custom_css = get_theme_mod( 'custom_css' );

		if ( $custom_css ) {
			// Strip tags so a stored value can never break out of <style>.
			echo '<style>' . wp_strip_all_tags( $custom_css ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS, tags stripped.
		}
	}
}
add_action( 'wp_head', 'get_activello_theme_setting', 10 );
