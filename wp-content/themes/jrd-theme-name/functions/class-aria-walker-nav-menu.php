<?php
/**
 * WAI-ARIA Navigation Menu template functions
 * @see wp-includes/nav-menu-template.php
 */

/**
 * Create HTML list of nav menu items.
 *
 * @since 1.0.0
 * @uses Walker
 * @uses Walker_Nav_Menu
 */
class Aria_Walker_Nav_Menu extends Walker_Nav_Menu {
	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el() for parameters and longer explanation
	 */
	private $curItem; // phpcs:ignore

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->curItem = $item; // phpcs:ignore
		$indent        = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . $args->menu->slug . '-' . esc_attr( $id ) . '"' : '';
		//$roles = ' role="none"';

		// $output .= sprintf( '%s<li%s%s%s>',
		//  $indent,
		//  $id,
		//  $class_names,
		//  $roles
		// );

		$output .= sprintf(
			'%s<li%s%s>',
			$indent,
			$id,
			$class_names,
		);

		$atts                 = array();
		$atts['title']        = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target']       = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']          = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = isset( $menu_item->current ) ? 'page' : '';
		$atts['aria-label']   = ! empty( $item->target ) ? __( 'Opens in new window', 'jrd' ) : '';
		if ( '#' === $atts['href'] ) {
			$atts['data-link']  = 'nonactive';
			$atts['aria-label'] = __( 'This link goes no where and is only for presentation', 'jrd' );
		} else {
			$atts['data-link'] = 'active';
		}
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			//$atts['aria-expanded']   = "false";
			//$atts['tabindex']   = "0";
		}
		$current_domain = parse_url( home_url(), PHP_URL_HOST );
		$href_domain    = parse_url( $atts['href'], PHP_URL_HOST ); //echo $current_domain  . ' - ' . $href_domain . '|  ';
		$href_fragment  = parse_url( $atts['href'], PHP_URL_FRAGMENT );
		if ( ( $href_domain !== $current_domain ) && ! isset( $href_fragment ) ) {
			$atts['rel'] = 'external';
		}
		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		if ( (int) 0 === (int) $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$item_output = $args->before;
			if ( str_contains( $attributes, 'data-link="active"' ) ) {
				$item_output .= '<a' . $attributes . '>';
				$item_output .= $args->link_before . $title . $args->link_after;
				$item_output .= '</a>';
				$item_output .= '<button id="' . $args->menu->slug . '-menu-item-' . $item->ID . 'B" class="menu-toggle-button" aria-controls="' . $args->menu->slug . '-menu-item-' . $item->ID . 'C" data-toggle="true" aria-expanded="false"><span class="sr-only">Show submenu for "' . $title . '"</span></button>';
			} else {
				$item_output .= '<a' . $attributes . ' id="' . $args->menu->slug . '-menu-item-' . $item->ID . 'A" data-toggle="true">';
				$item_output .= $args->link_before . $title . $args->link_after;
				$item_output .= '</a>';
				$item_output .= '<button id="' . $args->menu->slug . '-menu-item-' . $item->ID . 'B" class="menu-toggle-button" aria-controls="' . $args->menu->slug . '-menu-item-' . $item->ID . 'C" data-toggle="true" aria-expanded="false"><span class="sr-only">Show submenu for "' . $title . '"</span></button>';
			}
			$item_output .= $args->after;
		} elseif ( $depth > 0 ) {
			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
		} else {
			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
		}

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
			$indent = str_repeat( $t, $depth );

			// Default class.
			$classes = array( 'sub-menu' );

			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 *
			 * @since 4.8.0
			 *
			 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
			 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$role       = ' aria-label="Sub Menu for ' . $this->curItem->post_title . '" aria-hidden="true"'; // phpcs:ignore
			$parentlink = $this->curItem->ID; // phpcs:ignore
			$output    .= "{$n}{$indent}<div class='sub-menu-wrap'><ul id=\"{$args->menu->slug}-menu-item-{$parentlink}C\" $class_names $role>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul></div>\n";
	}
}
