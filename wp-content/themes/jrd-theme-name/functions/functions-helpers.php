<?php
/*
	Add helper functions, methods, and classes here. Helphers are for templating and not for adjusting body_class and functionaility.

	TABLE OF CONTENTS
	******************
	EASY PRINTR()
	GET/THE POST SLUG
	EXCERPT LIMITER
	TAG WRAP
	CLEAN FUNCTION
	JRD_IMG
	JRD_LINK
	JRD_TERMS_DROPDOWN
	JRD_SOCIAL_NAV
	JRD_GF
	JRD_EMBED_URL
*/


/**
 * Easily echo a preformatted print_r
 * @param  mixed $thing Variable to examine
 * @return array      Array to be returned
 */
if ( ! function_exists( 'printr' ) ) {
	function printr( $thing ) {
		echo '<pre>' . PHP_EOL;
		print_r( $thing );
		echo '</pre>' . PHP_EOL;
	}
}

/**
 * Get the slug of a post
 * @return int The posts's slug
 */
if ( ! function_exists( 'get_the_slug' ) ) {
	function get_the_slug() {
		global $post;
		$slug = $post->post_name;
		return $slug;
	}
}

/**
 * Echo the slug of a post
 * @return int The post's slug
 */
if ( ! function_exists( 'the_slug' ) ) {
	function the_slug() {
		echo esc_attr( get_the_slug() );
	}
}

/**
 * Limit the amount of words in a given string
 * @param  string $str     The string to limit
 * @param  int $word_limit    The max word length
 * @return string             The delimited string
 */
if ( ! function_exists( 'limit_excerpt' ) ) {
	function limit_excerpt( $str, $word_limit ) {
		$words = explode( ' ', $str );
		return implode( ' ', array_slice( $words, 0, $word_limit ) );
	}
}

/**
 * Wrap a variable in a tag with custom attributes
 * @param  string $str    The text to be wrapped
 * @param  string $notation  CSS notation for the tag
 * @return string           The returned HTML
 */
if ( ! function_exists( 'tag_wrap' ) ) {
	function tag_wrap( $str, $notation ) {
		if ( ! $str ) {
			return '';
		}
		$element = preg_split( '/[\.\#\[]/', $notation )[0];
		$classes = array();
		preg_match_all( '(\.[\w\d-]+)', $notation, $raw_classes );
		if ( ! empty( $raw_classes[0] ) ) {
			foreach ( $raw_classes[0] as $class ) {
				$class     = str_replace( '.', '', $class );
				$classes[] = $class;
			}
			$classes = ' class="' . implode( ' ', $classes ) . '"';
		} else {
			$classes = '';
		}
		$id = '';
		preg_match_all( '(\#[\w\d-]+)', $notation, $raw_id );
		if ( ! empty( $raw_id[0] ) ) {
			$id = ' id="' . str_replace( '#', '', $raw_id[0][0] ) . '"';
		}

		$atts = array();
		preg_match_all( '(\[[^\[\]]+\])', $notation, $raw_atts );
		if ( ! empty( $raw_atts[0] ) ) {
			foreach ( $raw_atts[0] as $att ) {
				$att    = str_replace( array( '[', ']' ), '', $att );
				$atts[] = $att;
			}
			$atts = ' ' . implode( ' ', $atts );
		} else {
			$atts = '';
		}
		$html = "<{$element}{$classes}{$id}{$atts}>{$str}</{$element}>";
		return $html;
	}
}

/* Example Usage:
	echo tag_wrap(get_field('field_name'), 'h3.something#id[data-attr="value"]');
	output: <h3 class="something" id="identifier" data-attr="value">[field contents]</h3>
*/

/**
 * CLEAN FUNCTION - Helpful making better hash links out of repeating fields.
 * @param  string $str The string to be clenaed
 * @return string         The sanitized string
 */
if ( ! function_exists( 'clean' ) ) {
	function clean( $string ) {
		$string = wp_strip_all_tags( $string );
		$string = strtolower( $string );
		$string = preg_replace( '/\s+/', '-', $string );
		$string = sanitize_html_class( $string );
		return $string;
	}
}

/**
 * Returns a proper <img /> tag with all necessary attributes and values
 * @param  array $field   The ACF image field
 * @param  string $size    The image_size as defined in functions.php
 * @param  string $classes The desired HTML element class for the image
 * @param  string $id      The desired HTML element ID for the image
 * @param  array  $data    key=>val pairs for data attributes for the image
 * @return string          The HTML for the image
 */
if ( ! function_exists( 'jrd_img' ) ) {
	function jrd_img( $field, $size = 'large', $classes = null, $id = null, $attrs = array() ) {
		if ( ! $field ) {
			return false;
		}
		$atts = array();
		if ( $id ) {
			$atts['id'] = $id;
		}
		if ( $classes ) {
			$atts['class'] = $classes;
		}
		if ( ! empty( $attrs ) ) {
			foreach ( $attrs as $key => $val ) {
				$atts[ esc_attr( $key ) ] = esc_attr( $val );
			}
		}
		if ( is_numeric( $field ) ) {
			$image_id = $field;
		} else {
			$image_id = $field['ID'];
		}
		return wp_get_attachment_image( $image_id, $size, false, $atts ) . PHP_EOL;
	}
}

/**
 * JRD Link - use with ACF link field
 * @param  array $link  The ACF link object
 * @param  string $class The desired HTML element class for the link
 * @param  string $id    The desired HTML ID for the link
 * @param  bool $span    Wrap the link text in a span tag (default: true)
 * @return string        HTML for the link
 */
if ( ! function_exists( 'jrd_link' ) ) {
	function jrd_link( $link, $classes = '', $id = '', $span = true, $atts_array = array() ) {
		if ( isset( $link['url'] ) && '' !== (string) $link['url'] ) {
			$link_label = isset( $link['label'] ) && '' !== (string) $link['label'] ? $link['label'] : esc_attr( $link['title'] );
			$link_url   = esc_url( $link['url'] );
			$target     = $link['target'] ?? '_self';
			$nofollow   = isset( $link['nofollow'] ) && 'nofollow' === $link['nofollow'] ? "rel='nofollow'" : '';
			$title      = $span ? tag_wrap( $link['title'], 'span' ) : $link['title'];
			if ( isset( $link['classes'] ) && '' !== (string) $link['classes'] ) {
				$classes = esc_attr( $link['classes'] );
			} else {
				$classes = esc_attr( $classes );
			}
			$id   = esc_attr( $id );
			$atts = '';
			if ( ! empty( $atts_array ) ) {
				foreach ( $atts_array as $key => $val ) {
					$key   = esc_attr( $key );
					$val   = esc_attr( $val );
					$atts .= " $key='$val'";
				}
			}
			return "<a href='{$link_url}' aria-label='{$link_label}' target='{$target}' class='$classes' id='$id' $nofollow $atts>$title</a>" . PHP_EOL;
		}
	}
}

/**
 * ADA Link - Use with clone field (fields must be named "link", "aria_label", and "nofollow")
 * @param  array $link   The ACF Clone field
 * @param  string $class The desired HTML element class for the link
 * @param  string $id    The desired HTML ID for the link
 * @param  bool $span    Wrap the link text in a span tag (default: true)
 * @return string        HTML for the link
 */
if ( ! function_exists( 'ada_link' ) ) {
	function ada_link( $link, $classes = '', $id = '', $span = true, $atts_array = array() ) {
		if ( isset( $link['link']['url'] ) && '' !== (string) $link['link']['url'] ) {
			$link_label = isset( $link['aria_label'] ) && '' !== (string) $link['aria_label'] ? $link['aria_label'] : esc_attr( $link['link']['title'] );
			$link_url   = esc_url( $link['link']['url'] );
			$target     = $link['link']['target'] ?? '_self';
			$nofollow   = $link['nofollow'] ? "rel='nofollow'" : '';
			$title      = $span ? tag_wrap( $link['link']['title'], 'span' ) : $link['link']['title'];
			$atts       = '';
			if ( ! empty( $atts_array ) ) {
				foreach ( $atts_array as $key => $val ) {
					$key   = esc_attr( $key );
					$val   = esc_attr( $val );
					$atts .= " $key='$val'";
				}
			}
			return "<a href='{$link_url}' aria-label='{$link_label}' target='{$target}' class='$classes' id='$id' $nofollow $atts>$title</a>" . PHP_EOL;
		}
	}
}

/**
 * Taxonomy terms as a select menu
 * @param  string $tax            The taxonomy's slug
 * @param  string $default_text   The default text for the select menu (eg. All Categories)
 * @param  string $default_value  The default value for the select menu (ex. the landing page's permalink)
 * @param  string $id             The desired HTML element ID for the dropdown
 * @return string                 HTML for the select menu
 */
if ( ! function_exists( 'jrd_terms_dropdown' ) ) {
	function jrd_terms_dropdown( $tax, $default_text = 'Select Category', $default_value = '', $id = '', $label_id = '' ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
			)
		);
		$aria  = '';
		if ( $id && $label_id ) {
			$aria = " aria-labelledby=\"{$id} {$label_id}\"";
		}
		$html  = "<select id=\"{$id}\" name=\"{$tax}\"{$aria}>" . PHP_EOL;
		$html .= "<option value=\"{$default_value}\">{$default_text}</option>" . PHP_EOL;
		foreach ( $terms as $term ) {
			$selected = ( isset( $_GET[ $tax ] ) && $_GET[ $tax ] === $term->slug ) ? 'selected' : '';
			$html    .= "<option $selected value=\"{$term->slug}\">{$term->name}</option>" . PHP_EOL;
		}
		$html .= '</select>' . PHP_EOL;
		return $html;
	}
}


/**
 * Generate a date range for events
 * Usage with ACF: jrd_date_range( get_field( 'event_start_date' ), get_field( 'event_end_date' ) );
 * Note that the fields would be set to return as 'Ymd'.
 * @param  string $start_date, in whatever format $date_format is set to
 * @param  string $end_date, in whatever format $date_format is set to
 * @param  string $date_format, the format of the above 2 parameters, defaulting to Ymd
 */
if ( ! function_exists( 'jrd_date_range' ) ) {
	function jrd_date_range( $start_date, $end_date = null, $date_format = 'Ymd' ) {
		if ( $start_date ) {
			// if there's at least a start date
			$start_datetime = date_create_from_format( $date_format, $start_date );
			if ( $end_date ) {
				// if there's also an end date
				$end_datetime = date_create_from_format( $date_format, $end_date );
				if ( $start_datetime->format( 'Y' ) === $end_datetime->format( 'Y' ) ) {
					// if start date and end date are the same year
					if ( $start_datetime->format( 'F' ) === $end_datetime->format( 'F' ) ) {
						// if start date and end date have the same month and year, return ex. January 1-3, 2020
						$date_range = $start_datetime->format( 'F j' ) . '-' . $end_datetime->format( 'j, Y' );
					} else {
						// otherwise, return ex. Jan 30 - Feb 1, 2020
						$date_range = $start_datetime->format( 'F j' ) . ' - ' . $end_datetime->format( 'F j, Y' );
					}
				} else {
					// if start date and end date have different years, return ex. December 30 - January 1, 2020
					$date_range = $start_datetime->format( 'F j, Y' ) . ' - ' . $end_datetime->format( 'F j, Y' );
				}
			} else {
				// if there's no end date, just return the start date, ex. January 30, 2020
				$date_range = $start_datetime->format( 'F j, Y' );
			}
		} else {
			// otherwise, return nothing
			$date_range = '';
		}
		return $date_range;
	}
}

/**
 * Social Media Nav Creator
 * @param string $field_name    parent field name
 * @param string $icon_field    icon field name; I usually use a select field.
 * @param string $url_field     url field
 * @param string $id            ID for the <nav> element
 * @param boolean $option_menu  whether pulling social options from options area
 * @param boolean $wrap_nav     whether to wrap icon list in a <nav> element
 * List of Options for easy copypasta
	social_facebook : Facebook
	social_instagram : Instagram
	social_linkedin : LinkedIn
	social_pinterest : Pinterest
	social_rss : RSS
	social_sharethis : ShareThis
	social_twitter : Twitter
	social_vimeo : Vimeo
	social_youtube : YouTube
 */
if ( ! function_exists( 'jrd_social_nav' ) ) {
	function jrd_social_nav( $field_name, $icon_subfield = 'social_platform', $link_subfield = 'social_link', $option_menu = true, $wrap_nav = true ) {
		global $post;
		$html = '';
		$loc  = $option_menu ? 'options' : $post->ID;
		if ( have_rows( $field_name, $loc ) ) {
			if ( $wrap_nav ) {
				$html .= "<nav class='social-media' aria-label='Social Media'><ul>";
			}
			while ( have_rows( $field_name, $loc ) ) {
				the_row();
				$icon     = get_sub_field( $icon_subfield );
				$link     = get_sub_field( $link_subfield );
				$icon_url = jrd_ui( "svg/sprites.svg#social_{$icon['value']}" );
				$html    .= <<<HTML
				<li class="social">
					<a
						href="{$link['url']}"
						target="_blank"
						aria-label="{$link['title']}"
					>
						<svg class="{$icon['value']}">
							<use xlink:href="{$icon_url}"></use>
						</svg>
					</a>
				</li>
HTML;
			}
			if ( $wrap_nav ) {
				$html .= '</ul></nav>';
			}
		}
		return $html;
	}
}
// TODO: fix field referencing, add phone option

/**
 * SVG Use generator
 * @param string $symbol_id          the intended ID of the symbol in the SVG sprite file
 * @param string $classes            classes for the SVG file
 * @param string $title              title of the SVG
 * @param string $filename           the filename (minus the extension) of the SVG sprite file
 * @param string $parent_or_child    whether it should reach into the parent theme or active child. omit if it's not a multisite
 **/
if ( ! function_exists( 'jrd_use' ) ) {
	function jrd_use( $symbol_id, $classes = '', $title = '', $filename = 'sprites', $parent_or_child = 'parent' ) {
		if ( 'child' === $parent_or_child ) {
			$dir = get_stylesheet_directory_uri();
		} else {
			$dir = get_template_directory_uri();
		}
		$html = "<svg class='$classes'>";
		if ( $title ) {
			$html .= "<title>$title</title>";
		}
		$html .= "<use xlink:href='$dir/ui/svg/$filename.svg#$symbol_id'></use></svg>";
		return $html;
	}
}

/**
 * UI path generator
 * @param string $file_path          the path and filename relative to the ui directory inside the theme
 * @param string $parent_or_child    whether it should reach into the parent theme or active child. omit if it's not a multisite
 **/
if ( ! function_exists( 'jrd_ui' ) ) {
	function jrd_ui( $file_path = '', $parent_or_child = 'parent' ) {
		if ( 'child' === $parent_or_child ) {
			$dir = get_stylesheet_directory_uri();
		} else {
			$dir = get_template_directory_uri();
		}
		$full_path = trailingslashit( $dir ) . 'ui/';
		if ( $file_path ) {
			$full_path .= $file_path;
		}
		return $full_path;
	}
}

/**
 * Gravity Forms shortcode generator
 * @param int $gf_id             the id of the gravity form
 * @param string $title          defaults to false
 * @param string $description    defaults to false
 * @param string $ajax           defaults to true
 **/
if ( ! function_exists( 'jrd_gf' ) ) {
	function jrd_gf( $gf_id, $title = false, $description = false, $ajax = true ) {
		$shortcode   = '';
		$title       = json_encode( $title );
		$description = json_encode( $description );
		$ajax        = json_encode( $ajax );
		if ( $gf_id ) {
			$shortcode = do_shortcode( "[gravityform id='{$gf_id}' title='{$title}' description='{$description}' ajax='{$ajax}']" );
		}
		return $shortcode;
	}
}



/**
 * JRD Phone
 * Replaces all letters with their respective numbers on a phone keypad.
 * Strips out all other characters except digits, plus signs, commas, and semicolons
 * @param string $phone    The phone number
*/

function jrd_phone( $phone ) {
	if ( $phone ) {
		$formatted_number = preg_replace(
			array(
				'/[abc]/i',
				'/[def]/i',
				'/[ghi]/i',
				'/[jkl]/i',
				'/[mno]/i',
				'/[pqrs]/i',
				'/[tuv]/i',
				'/[wxyz]/i',
				'/[^\d\+,;]/',
			),
			array(
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9',
				'',
			),
			$phone
		);
		return $formatted_number;
	}
}
