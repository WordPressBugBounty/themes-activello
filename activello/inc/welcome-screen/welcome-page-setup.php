<?php
/**
 * About Activello screen setup.
 *
 * @package activello
 */

require_once get_template_directory() . '/inc/welcome-screen/class-activello-welcome.php';

if ( is_admin() ) {
	global $activello_recommended_plugins;

	/*
	 * Plugins offered on the Recommended Plugins tab, keyed by wordpress.org
	 * slug. Kept as a global so a child theme can change the list.
	 */
	$activello_recommended_plugins = array(
		'kali-forms'                       => array( 'recommended' => true ),
		'modula-best-grid-gallery'         => array( 'recommended' => true ),
		'fancybox-for-wordpress'           => array( 'recommended' => false ),
		'simple-custom-post-order'         => array( 'recommended' => false ),
		'colorlib-404-customizer'          => array( 'recommended' => false ),
		'colorlib-coming-soon-maintenance' => array( 'recommended' => false ),
		'colorlib-login-customizer'        => array( 'recommended' => false ),
		'rsvp'                             => array( 'recommended' => false ),
	);

	Activello_Welcome::get_instance();
}
