<?php
/**
 * activello Theme Customizer
 *
 * @package activello
 */

/**
 * Live-preview the site title and tagline.
 *
 * This used to switch header_textcolor to postMessage, a setting for a
 * custom header the theme does not support, while the title and tagline it
 * does print were left on full-page refresh. They are selective-refresh
 * partials now, so the preview re-renders just the logo area -- with the
 * logo or the title, whichever the "Show" option picks.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function activello_customize_register( $wp_customize ) {
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->selective_refresh->add_partial(
		'blogname',
		array(
			'selector'            => '#logo .site-name',
			'container_inclusive' => false,
			'render_callback'     => 'activello_site_name',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'blogdescription',
		array(
			'selector'            => '#logo .tagline',
			'container_inclusive' => false,
			'render_callback'     => 'activello_customize_partial_blogdescription',
		)
	);
}
add_action( 'customize_register', 'activello_customize_register' );

/**
 * Render the tagline for the selective refresh partial.
 *
 * @return void
 */
function activello_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Options for WordPress Theme Customizer.
 */
function activello_customizer( $wp_customize ) {

	global $header_show;
	$wp_customize->add_setting(
		'header_show',
		array(
			'default'           => 'logo-text',
			'sanitize_callback' => 'activello_sanitize_radio_header',
		)
	);
	$wp_customize->add_control(
		'header_show',
		array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Show', 'activello' ),
			'section' => 'title_tagline',
			'choices' => $header_show,
		)
	);

	/* Main option Settings Panel */
	$wp_customize->add_panel(
		'activello_main_options',
		array(
			'capability'  => 'edit_theme_options',
			'title'       => esc_html__( 'Activello Options', 'activello' ),
			'description' => esc_html__( 'Options for the Activello theme.', 'activello' ),
			'priority'    => 10, // Mixed with top-level-section hierarchy.
		)
	);

	// add "Content Options" section
	$wp_customize->add_section(
		'activello_content_section',
		array(
			'title'    => esc_html__( 'Content Options', 'activello' ),
			'priority' => 50,
			'panel'    => 'activello_main_options',
		)
	);

	// add setting for excerpts/full posts toggle
	$wp_customize->add_setting(
		'activello_excerpts',
		array(
			'default'           => 1,
			'sanitize_callback' => 'activello_sanitize_checkbox',
		)
	);

	// add checkbox control for excerpts/full posts toggle
	$wp_customize->add_control(
		new Activello_Customize_Toggle_Control(
			$wp_customize,
			'activello_excerpts',
			array(
				'type'     => 'activello-toggle',
				'label'    => esc_html__( 'Show post excerpts?', 'activello' ),
				'section'  => 'activello_content_section',
				'priority' => 10,
			)
		)
	);

	// add setting for excerpts/full posts toggle
	$wp_customize->add_setting(
		'activello_categories',
		array(
			'default'           => 0,
			'sanitize_callback' => 'activello_sanitize_checkbox',
		)
	);

	// add checkbox control for excerpts/full posts toggle
	$wp_customize->add_control(
		new Activello_Customize_Toggle_Control(
			$wp_customize,
			'activello_categories',
			array(
				'type'     => 'activello-toggle',
				'label'    => esc_html__( 'Show only one category in archives?', 'activello' ),
				'section'  => 'activello_content_section',
				'priority' => 10,
			)
		)
	);

	$wp_customize->add_setting(
		'activello_page_comments',
		array(
			'default'           => 1,
			'sanitize_callback' => 'activello_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new Activello_Customize_Toggle_Control(
			$wp_customize,
			'activello_page_comments',
			array(
				'type'     => 'activello-toggle',
				'label'    => esc_html__( 'Display Comments on Static Pages?', 'activello' ),
				'section'  => 'activello_content_section',
				'priority' => 20,
			)
		)
	);
	// add "Featured Posts" section
	$wp_customize->add_section(
		'activello_featured_section',
		array(
			'title'    => esc_html__( 'Slider Options', 'activello' ),
			'priority' => 60,
			'panel'    => 'activello_main_options',
		)
	);

	$wp_customize->add_setting(
		'activello_featured_cat',
		array(
			'default'           => 0,
			'transport'         => 'refresh',
			'sanitize_callback' => 'activello_sanitize_slidecat',
		)
	);

	$wp_customize->add_control(
		'activello_featured_cat',
		array(
			'type'    => 'select',
			'label'   => esc_html__( 'Choose a category', 'activello' ),
			'choices' => activello_cats(),
			'section' => 'activello_featured_section',
		)
	);

	$wp_customize->add_setting(
		'activello_featured_limit',
		array(
			'default'           => -1,
			'transport'         => 'refresh',
			'sanitize_callback' => 'activello_sanitize_number',
		)
	);

	$wp_customize->add_control(
		'activello_featured_limit',
		array(
			'type'        => 'number',
			'label'       => esc_html__( 'Limit posts', 'activello' ),
			'description' => esc_html__( 'How many posts the slider shows. -1 shows every post with a featured image.', 'activello' ),
			'section'     => 'activello_featured_section',
			'input_attrs' => array(
				'min'  => -1,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'activello_featured_hide',
		array(
			'default'           => 0,
			'transport'         => 'refresh',
			'sanitize_callback' => 'activello_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new Activello_Customize_Toggle_Control(
			$wp_customize,
			'activello_featured_hide',
			array(
				'type'    => 'activello-toggle',
				'label'   => esc_html__( 'Show Slider', 'activello' ),
				'section' => 'activello_featured_section',
			)
		)
	);

	// add "Sidebar" section
	$wp_customize->add_section(
		'activello_layout_section',
		array(
			'title'    => esc_html__( 'Layout Options', 'activello' ),
			'priority' => 31,
			'panel'    => 'activello_main_options',
		)
	);
	// Layout options
	global $site_layout;
	$wp_customize->add_setting(
		'activello_sidebar_position',
		array(
			'default'           => 'side-right',
			'sanitize_callback' => 'activello_sanitize_layout',
		)
	);
	$wp_customize->add_control(
		'activello_sidebar_position',
		array(
			'label'       => esc_html__( 'Website Layout Options', 'activello' ),
			'section'     => 'activello_layout_section',
			'type'        => 'select',
			'description' => esc_html__( 'Choose between different layout options to be used as default', 'activello' ),
			'choices'     => $site_layout,
		)
	);

	$wp_customize->add_setting(
		'activello_blog_layout',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'activello_sanitize_blog_layout',
		)
	);
	$wp_customize->add_control(
		'activello_blog_layout',
		array(
			'label'       => esc_html__( 'Blog Posts Layout Options', 'activello' ),
			'section'     => 'activello_layout_section',
			'type'        => 'radio',
			'description' => esc_html__( 'Choose how you want your posts to look on the Blog Page', 'activello' ),
			'choices'     => array(
				'default'    => esc_html__( 'Two full width posts then half width posts', 'activello' ),
				'full-width' => esc_html__( 'Full width posts', 'activello' ),
			),
		)
	);

	$wp_customize->add_setting(
		'accent_color',
		array(
			'default'           => '#964fb8',
			'sanitize_callback' => 'activello_sanitize_hexcolor',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accent_color',
			array(
				'label'       => esc_html__( 'Accent Color', 'activello' ),
				'description' => esc_html__( 'Default used if no color is selected', 'activello' ),
				'section'     => 'colors',
			)
		)
	);

	$wp_customize->add_setting(
		'social_color',
		array(
			'default'           => '#696969',
			'sanitize_callback' => 'activello_sanitize_hexcolor',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'social_color',
			array(
				'label'       => esc_html__( 'Social Icon Color', 'activello' ),
				'description' => esc_html__( 'Default used if no color is selected', 'activello' ),
				'section'     => 'colors',
			)
		)
	);

	$wp_customize->add_setting(
		'social_hover_color',
		array(
			'default'           => '#964fb8',
			'sanitize_callback' => 'activello_sanitize_hexcolor',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'social_hover_color',
			array(
				'label'       => esc_html__( 'Social Icon Hover Color', 'activello' ),
				'description' => esc_html__( 'Default used if no color is selected', 'activello' ),
				'section'     => 'colors',
			)
		)
	);

	// add "Footer" section
	$wp_customize->add_section(
		'activello_footer_section',
		array(
			'title'    => esc_html__( 'Footer', 'activello' ),
			'priority' => 90,
		)
	);

	$wp_customize->add_setting(
		'activello_footer_copyright',
		array(
			/*
			 * The footer has always printed "Activello" while no text is
			 * saved, but this default was empty, so the Customizer showed an
			 * empty field for text that was on the page.
			 */
			'default'           => 'Activello',
			'transport'         => 'refresh',
			'sanitize_callback' => 'activello_sanitize_copyright',
		)
	);

	$wp_customize->add_control(
		'activello_footer_copyright',
		array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Copyright Text', 'activello' ),
			'description' => esc_html__( 'Basic HTML such as links is allowed.', 'activello' ),
			'section'     => 'activello_footer_section',
		)
	);
}
add_action( 'customize_register', 'activello_customizer' );

/**
 * Sanitize the footer copyright text: the HTML allowed in post content.
 *
 * @param string $input Raw value.
 * @return string
 */
function activello_sanitize_copyright( $input ) {
	return wp_kses_post( wp_unslash( (string) $input ) );
}

/**
 * Adds sanitization callback function: Strip Slashes
 * @package Activello
 */
function activello_sanitize_strip_slashes( $input ) {
	return wp_kses_stripslashes( $input );
}

/**
 * Sanitzie checkbox for WordPress customizer
 */
function activello_sanitize_checkbox( $input ) {
	// Saved as true, 1 or '1' depending on the version that saved it.
	if ( true === $input || 1 === $input || '1' === $input ) {
		return 1;
	} else {
		return '';
	}
}
/**
 * Adds sanitization callback function: Sidebar Layout
 * @package Activello
 */
function activello_sanitize_layout( $input ) {
	global $site_layout;
	if ( array_key_exists( $input, $site_layout ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: Blog Posts Layout
 * @package Activello
 */
function activello_sanitize_blog_layout( $input ) {
	global $site_layout;
	if ( in_array( $input, array( 'default', 'full-width' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: colors
 * @package Activello
 */
function activello_sanitize_hexcolor( $color ) {
	$unhashed = sanitize_hex_color_no_hash( $color );
	if ( $unhashed ) {
		return '#' . $unhashed;
	}
	return '';
}

/**
 * Adds sanitization callback function: Slider Category
 * @package Activello
 */
function activello_sanitize_slidecat( $input ) {

	if ( array_key_exists( $input, activello_cats() ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: Radio Header
 * @package Activello
 */
function activello_sanitize_radio_header( $input ) {
	global $header_show;
	if ( array_key_exists( $input, $header_show ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: Number
 * @package Activello
 */
function activello_sanitize_number( $input ) {
	// -1 means "all"; anything else that is not a whole number falls back to it.
	return is_numeric( $input ) ? max( -1, (int) $input ) : -1;
}

/**
 * Styles for the theme's own Customizer controls (the on/off toggles).
 */
function activello_customize_controls_css() {
	wp_enqueue_style( 'activello-customizer-controls', get_template_directory_uri() . '/assets/css/customizer.css', array(), ACTIVELLO_VERSION );
}
add_action( 'customize_controls_enqueue_scripts', 'activello_customize_controls_css' );
