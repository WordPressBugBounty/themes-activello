<?php
/**
 * Social Navigation Menu
 *
 * @package activello
 */

/**
 * Register Social Icon menu
 */
add_action( 'init', 'activello_register_social_menu' );

/**
 * Register the social menu location.
 */
function activello_register_social_menu() {
	register_nav_menu( 'social-menu', _x( 'Social Menu', 'nav menu location', 'activello' ) );
}

if ( ! function_exists( 'activello_social_icons' ) ) :
	/**
	 * Display social links in footer and widgets
	 *
	 * The per-item icon markup is attached in activello_social_menu_item_args(),
	 * which runs once per menu item and can therefore pick the right Font
	 * Awesome family for each network.
	 */
	function activello_social_icons() {
		static $count = 0;

		if ( ! has_nav_menu( 'social-menu' ) ) {
			return;
		}

		/*
		 * The footer prints this and so does the Activello Social widget, which
		 * duplicated both ids on every page using the widget. Later copies are
		 * numbered; style.css styles #social, #social-2 and #social-3.
		 */
		++$count;
		$suffix = 1 === $count ? '' : '-' . $count;

		wp_nav_menu(
			array(
				'theme_location'       => 'social-menu',
				'container'            => 'nav',
				'container_id'         => 'social' . $suffix,
				'container_class'      => 'social-icons',
				'container_aria_label' => __( 'Social links', 'activello' ),
				'menu_id'              => 'menu-social-items' . $suffix,
				'menu_class'           => 'social-menu',
				'depth'                => 1,
				'fallback_cb'          => '',
			)
		);
	}
endif;

if ( ! function_exists( 'activello_get_social_networks' ) ) :
	/**
	 * Map of host fragment => array( icon slug, Font Awesome family ).
	 *
	 * More specific hosts come first, so that a "mastodon.social" URL is not
	 * swallowed by a broader rule.
	 *
	 * @return array
	 */
	function activello_get_social_networks() {
		$networks = array(
			'x.com'           => array( 'x-twitter', 'brands' ),
			'twitter.com'     => array( 'x-twitter', 'brands' ),
			'facebook.com'    => array( 'facebook-f', 'brands' ),
			'github.com'      => array( 'github', 'brands' ),
			'gitlab.com'      => array( 'gitlab', 'brands' ),
			'pinterest.'      => array( 'pinterest-p', 'brands' ),
			'linkedin.com'    => array( 'linkedin-in', 'brands' ),
			'youtube.com'     => array( 'youtube', 'brands' ),
			'youtu.be'        => array( 'youtube', 'brands' ),
			'instagram.com'   => array( 'instagram', 'brands' ),
			'flickr.com'      => array( 'flickr', 'brands' ),
			'tumblr.com'      => array( 'tumblr', 'brands' ),
			'dribbble.com'    => array( 'dribbble', 'brands' ),
			'behance.net'     => array( 'behance', 'brands' ),
			'vimeo.com'       => array( 'vimeo-v', 'brands' ),
			'spotify.com'     => array( 'spotify', 'brands' ),
			'soundcloud.com'  => array( 'soundcloud', 'brands' ),
			'tiktok.com'      => array( 'tiktok', 'brands' ),
			'threads.net'     => array( 'threads', 'brands' ),
			'threads.com'     => array( 'threads', 'brands' ),
			'discord.com'     => array( 'discord', 'brands' ),
			'discord.gg'      => array( 'discord', 'brands' ),
			'twitch.tv'       => array( 'twitch', 'brands' ),
			'reddit.com'      => array( 'reddit-alien', 'brands' ),
			'mastodon.social' => array( 'mastodon', 'brands' ),
			'mastodon.'       => array( 'mastodon', 'brands' ),
			'medium.com'      => array( 'medium', 'brands' ),
			'slack.com'       => array( 'slack', 'brands' ),
			'telegram.org'    => array( 'telegram', 'brands' ),
			't.me'            => array( 'telegram', 'brands' ),
			'whatsapp.com'    => array( 'whatsapp', 'brands' ),
			'wa.me'           => array( 'whatsapp', 'brands' ),
			'bsky.app'        => array( 'bluesky', 'brands' ),
			'skype.com'       => array( 'skype', 'brands' ),
			'foursquare.com'  => array( 'foursquare', 'brands' ),
			'snapchat.com'    => array( 'snapchat', 'brands' ),
			'vk.com'          => array( 'vk', 'brands' ),
			'weheartit.com'   => array( 'heart', 'solid' ),
		);

		/**
		 * Filter the recognised social networks.
		 *
		 * @param array $networks Host fragment => array( icon slug, Font Awesome family ).
		 */
		return apply_filters( 'activello_social_networks', $networks );
	}
endif;

if ( ! function_exists( 'activello_get_social_icon' ) ) :
	/**
	 * Resolve a menu item URL to its icon slug and Font Awesome family.
	 *
	 * @param string $url Menu item URL.
	 * @return array{slug:string,family:string}
	 */
	function activello_get_social_icon( $url ) {
		$url = (string) $url;

		// mailto:, tel: and skype: links have no host, so test the scheme first.
		if ( 0 === stripos( $url, 'mailto:' ) ) {
			return array(
				'slug'   => 'envelope',
				'family' => 'solid',
			);
		}
		if ( 0 === stripos( $url, 'tel:' ) ) {
			return array(
				'slug'   => 'phone',
				'family' => 'solid',
			);
		}
		if ( 0 === stripos( $url, 'skype:' ) ) {
			return array(
				'slug'   => 'skype',
				'family' => 'brands',
			);
		}

		// Feeds are identified by path, not host, and RSS is a solid glyph.
		if ( false !== stripos( $url, '/feed' ) ) {
			return array(
				'slug'   => 'rss',
				'family' => 'solid',
			);
		}

		$host = (string) wp_parse_url( $url, PHP_URL_HOST );
		$host = strtolower( preg_replace( '/^www\./', '', $host ) );

		if ( '' !== $host ) {
			foreach ( activello_get_social_networks() as $fragment => $icon ) {
				/*
				 * Match whole host labels, so netflix.com is not taken for
				 * x.com. A fragment ending in a dot ("mastodon.") matches any
				 * top-level domain.
				 */
				if ( '.' === substr( $fragment, -1 ) ) {
					$matches = 0 === strpos( $host, $fragment ) || false !== strpos( $host, '.' . $fragment );
				} else {
					$matches = $host === $fragment || substr( $host, -strlen( $fragment ) - 1 ) === '.' . $fragment;
				}

				if ( $matches ) {
					return array(
						'slug'   => $icon[0],
						'family' => $icon[1],
					);
				}
			}
		}

		// Unknown network: a generic link glyph beats a blank square.
		return array(
			'slug'   => 'link',
			'family' => 'solid',
		);
	}
endif;

if ( ! function_exists( 'activello_social_menu_item_args' ) ) :
	/**
	 * Wrap each social menu item's label in its icon.
	 *
	 * Font Awesome 7 keeps brand glyphs in a separate family, so every icon
	 * used to render in the solid font as an empty box (only RSS, a solid
	 * glyph, survived). The family is now picked per item, and the label is
	 * kept for screen readers instead of being hidden with display: none,
	 * which left the links without an accessible name.
	 *
	 * @param stdClass $args wp_nav_menu() arguments for this item.
	 * @param WP_Post  $item Menu item.
	 * @return stdClass
	 */
	function activello_social_menu_item_args( $args, $item ) {
		if ( ! is_object( $args ) || empty( $args->theme_location ) || 'social-menu' !== $args->theme_location ) {
			return $args;
		}

		$icon = activello_get_social_icon( isset( $item->url ) ? $item->url : '' );

		$args->link_before = sprintf(
			'<i class="social_icon fa-%1$s fa-%2$s" aria-hidden="true"></i><span class="screen-reader-text">',
			esc_attr( $icon['family'] ),
			esc_attr( $icon['slug'] )
		);
		$args->link_after  = '</span>';

		return $args;
	}
endif;
add_filter( 'nav_menu_item_args', 'activello_social_menu_item_args', 10, 2 );

if ( ! function_exists( 'activello_social_menu_item_class' ) ) :
	/**
	 * Add a network class (social-github, ...) to each social menu item.
	 *
	 * @param string[] $classes Menu item classes.
	 * @param WP_Post  $item    Menu item.
	 * @param stdClass $args    wp_nav_menu() arguments.
	 * @return string[]
	 */
	function activello_social_menu_item_class( $classes, $item, $args ) {
		if ( ! is_object( $args ) || empty( $args->theme_location ) || 'social-menu' !== $args->theme_location ) {
			return $classes;
		}

		$icon      = activello_get_social_icon( isset( $item->url ) ? $item->url : '' );
		$classes[] = 'social-' . $icon['slug'];

		return $classes;
	}
endif;
add_filter( 'nav_menu_css_class', 'activello_social_menu_item_class', 10, 3 );
