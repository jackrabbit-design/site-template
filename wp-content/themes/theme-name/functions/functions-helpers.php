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

*/


/**
 * Easily echo a preformatted print_r
 * @param  mixed $var Variable to examine
 * @return array      Array to be returned
 */
function printr( $var ) {
	echo '<pre>' . PHP_EOL;
	print_r( $var );
	echo '</pre>' . PHP_EOL;
}

/**
 * Get the slug of a post
 * @return int The posts's slug
 */
function get_the_slug() {
	global $post;
	$slug = $post->post_name;
	return $slug;
}

/**
 * Echo the slug of a post
 * @return int The post's slug
 */
function the_slug() {
	echo esc_html( get_the_slug() );
}

/**
 * Limit the amount of words in a given string
 * @param  string $string     The string to limit
 * @param  int $word_limit    The max word length
 * @return string             The delimited string
 */
function limit_excerpt( $string, $word_limit ) {
	$words = explode( ' ', $string );
	return implode( ' ', array_slice( $words, 0, $word_limit ) );
}

/**
 * Wrap a variable in a tag
 * @param  string $string   The text to be wrapped
 * @param  string $wrapper  The wrapper element
 * @return string           The returned HTML
 */
function tag_wrap( $string, $wrapper ) {
	if ( $string ) {
		$return  = "<{$wrapper}>{$string}";
		$element = explode( ' ', $wrapper );
		$element = $element[0];
		$return .= "</{$element}>" . PHP_EOL;
		return wp_kses_post( $return );
	}
}
/* Example Usage:
	echo tag_wrap(get_field('field_name'), 'h3 class="something"');
	output: <h3 class="something">[contents]</h3>
*/

/**
 * CLEAN FUNCTION - Helpful making better hash links out of repeating fields.
 * @param  string $string The string to be clenaed
 * @return string         The sanitized string
 */
function clean( $string ) {
	$string = wp_strip_all_tags( $string );
	$string = strtolower( $string );
	$string = str_replace( ' ', '-', $string );
	return preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
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
function jrd_img( $field, $size = 'large', $classes = null, $id = null, $data = array() ) {
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
	if ( ! empty( $data ) ) {
		foreach ( $data as $key => $val ) {
			$key = str_replace( 'data-', '', $key );

			$atts[ 'data-' . $key ] = $val;
		}
	}
	if ( is_int( $field ) ) {
		$image_id = $field;
	} else {
		$image_id = $field['ID'];
	}
	return wp_kses_post( wp_get_attachment_image( $image_id, $size, false, $atts ) ) . PHP_EOL;
}

/**
 * JRD Link - use with ACF link field
 * @param  array $link  The ACF link object
 * @param  string $class The desired HTML element class for the link
 * @param  string $id    The desired HTML ID for the link
 * @return string        HTML for the link
 */
function jrd_link( $link, $class = '', $id = '' ) {
	if ( $link ) {
		$link_title = $link['title'] ? $link['title'] : $link['url'];
		$link_url   = esc_url( $link['url'] );
		return "<a href='{$link_url}' title='{$link_title}' target='{$link['target']}' class='$class' id='$id'><span>{$link['title']}</span></a>" . PHP_EOL;
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
function jrd_terms_dropdown( $tax, $default_text = 'Select Category', $default_value = '', $id = '' ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $tax,
			'hide_empty' => false,
		)
	);
	$html  = "<select id=\"{$id}\" name=\"{$tax}\">" . PHP_EOL;
	$html .= "<option value=\"{$default_value}\">{$default_text}</option>" . PHP_EOL;
	foreach ( $terms as $term ) {
		$selected = ( isset( $_GET[ $tax ] ) && $_GET[ $tax ] === $term->slug ) ? 'selected' : '';
		$html    .= "<option $selected value=\"{$term->slug}\">{$term->name}</option>" . PHP_EOL;
	}
	$html .= '</select>' . PHP_EOL;
	return $html;
}


/**
 * Generate a date range for events
 * Usage with ACF: jrd_date_range( get_field( 'event_start_date' ), get_field( 'event_end_date' ) );
 * Note that the fields would be set to return as 'Ymd'.
 * @param  string $start_date, in whatever format $date_format is set to
 * @param  string $end_date, in whatever format $date_format is set to
 * @param  string $date_format, the format of the above 2 parameters, defaulting to Ymd
 */
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



/**
 * Social Media Nav Creator
 * @param string $field_name    parent field name
 * @param string $icon_fn       icon field name; I usually use a select field.
 * @param string $url_fn        url field
 * @param string $option        is it on the options page? default is true
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
function jrd_social_nav( $field_name, $icon_field, $url_field, $id = 'nav-social' ) {
	$html = '';
	if ( have_rows( $field_name, 'option' ) ) {
		$html .= "<nav id=\"{$id}\" class=\"social-media\"><ul>";
		while ( have_rows( $field_name, 'option' ) ) {
			the_row();
			$icon  = get_sub_field( $icon_field );
			$url   = get_sub_field( $url_field );
			$html .= "<li><a href=\"{$url}\" target=\"_blank\"><svg class=\"{$icon}\"><use xlink:href=\"/ui/svg/social-sprites.svg#{$icon}\"></use></svg></a></li> ";
		}
		$html .= '</ul></nav>';
	}
	return $html;
}
