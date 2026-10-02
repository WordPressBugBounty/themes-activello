<?php

/**
 * Class Name: activello_wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Activello_Wp_Bootstrap_Navwalker extends Walker_Nav_Menu {

		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		// No role="menu": that promises menuitem children and arrow-key
		// navigation, neither of which this list has.
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/**
				 * Dividers, Headers or Disabled
				 * =============================
				 * Determine whether the item is a Divider, Header, Disabled or regular
				 * menu item. To prevent errors we use the strcasecmp() function to so a
				 * comparison that is not case sensitive. The strcasecmp() function returns
				 * a 0 if the strings are equal.
				 */
		if ( 0 === strcasecmp( $item->attr_title, 'divider' ) && 1 === $depth ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} elseif ( 0 === strcasecmp( $item->title, 'divider' ) && 1 === $depth ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} elseif ( 0 === strcasecmp( $item->attr_title, 'dropdown-header' ) && 1 === $depth ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_html( $item->title );
		} elseif ( 0 === strcasecmp( $item->attr_title, 'disabled' ) ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_html( $item->title ) . '</a>';
		} else {
			$class_names = '';
			$value       = '';
			$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[]   = 'menu-item-' . $item->ID;

			/*
			 * The core menu filters, applied as Walker_Nav_Menu applies them so
			 * plugins that hook into menus keep working with this walker.
			 */
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

			if ( in_array( 'current-menu-item', $classes, true ) ) {
					$class_names .= ' active';
			}

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . '>';

			/*
			 * No title attribute: it repeated the link text, so screen readers
			 * announced every item twice. (The Title Attribute field is used
			 * for a glyphicon class below.)
			 */
			$atts           = array();
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			if ( ! empty( $item->current ) ) {
				$atts['aria-current'] = 'page';
			}

			// If item has_children add atts to a.
			if ( 0 === $args->has_children && $depth ) {
				$atts['href']  = ! empty( $item->url ) ? $item->url : '';
				$atts['class'] = 'dropdown-toggle';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */
			if ( ! empty( $item->attr_title ) ) {
					$item_output .= '<a' . $attributes . '><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
			} else {
				$item_output .= '<a' . $attributes . '>';
			}

			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			/*
			 * The sub-menu toggle, shown on touch screens of tablet width and
			 * up. It was an empty <span> with a click handler, which keyboards
			 * cannot reach and screen readers do not announce.
			 */
			$item_output .= ( $args->has_children )
				? ' </a><button type="button" class="activello-dropdown" aria-expanded="false"><span class="screen-reader-text">'
					/* translators: %s: parent menu item title */
					. esc_html( sprintf( __( 'Show sub-menu of %s', 'activello' ), wp_strip_all_tags( $item->title ) ) )
					. '</span></button>'
				: '</a>';
			$item_output .= $args->after;

			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];

		// Display this element.
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
	public static function fallback( $args ) {
		$fb_output = null;

		if ( $args['container'] ) {
			$fb_output = '<' . tag_escape( $args['container'] );

			if ( $args['container_id'] ) {
					$fb_output .= ' id="' . esc_attr( $args['container_id'] ) . '"';
			}

			if ( $args['container_class'] ) {
					$fb_output .= ' class="' . esc_attr( $args['container_class'] ) . '"';
			}

			$fb_output .= '>';
		}

		$fb_output .= '<ul';

		if ( $args['menu_id'] ) {
				$fb_output .= ' id="' . esc_attr( $args['menu_id'] ) . '"';
		}

		if ( $args['menu_class'] ) {
				$fb_output .= ' class="' . esc_attr( $args['menu_class'] ) . '"';
		}

		$fb_output .= '>';
		$fb_output .= wp_list_pages(
			array(
				'depth'       => 1,
				'exclude'     => '',
				'title_li'    => '', // Must be empty, or it wraps the list in another <li>.
				'sort_column' => 'post_title',
				'sort_order'  => 'ASC',
				'echo'        => false,
			)
		);
		$fb_output .= '</ul>';

		if ( $args['container'] ) {
				$fb_output .= '</' . tag_escape( $args['container'] ) . '>';
		}

		echo $fb_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributes escaped above; wp_list_pages() markup.
	}
}
