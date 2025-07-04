<?php
$production_url = ''; // set this to the same value as $_SERVER['HTTP_HOST'] on the Production environment - do this after this site's Staging environment is created post-launch, e.g. 'www.jumpingjackrabbit.com';
$is_production  = $_SERVER['HTTP_HOST'] === $production_url;

// force "discourage search engines" to be unchecked in production
if ( $is_production ) {
	if ( (int) 0 === (int) get_option( 'blog_public' ) ) {
		update_option( 'blog_public', 1 );
	}
} else {
	if ( (int) 1 === (int) get_option( 'blog_public' ) ) {
		update_option( 'blog_public', 0 );
	}
}

// Use this as a conditional instead of is_user_logged_in(). This function is more strict, as long as your wp username is admin_jackrabbit
if ( ! function_exists( 'is_jrd' ) ) {
	function is_jrd() {
		return 'admin_jackrabbit' === wp_get_current_user()->user_login;
	}
}

/* ========================================================================= */
/* WordPress Customization & Setup */
/* ========================================================================= */

/* Disable WordPress's Auto-scale of Images */
add_filter( 'big_image_size_threshold', '__return_false' );

/* Post Thumbnail Sizes */
/**
 * Returns image crops for the supplied dimension .5x, 1x, and 2x
 * @param  string $size The name of the image crop
 * @param  int $width   The width of the image at 1x
 * @param  int $height  The height of the image at 1x
 * @param  bool $crop   Choose whether the image crops or not
 */
if ( ! function_exists( 'jrd_add_img_sizes' ) ) {
	function jrd_add_img_sizes( $size, $width, $height, $crop = true ) {
		add_image_size( $size, $width, $height, $crop );
		add_image_size( "$size-2x", $width * 2, $height * 2, $crop );
		add_image_size( "$size-m", $width / 2, $height / 2, $crop );
	}
	add_theme_support( 'post-thumbnails' );
}

set_post_thumbnail_size( 64, 64, true );
//add_image_size( 'size-name', 100, 100, true );
//jrd_add_img_sizes( 'size-name', 100, 100 );

/* Declare Nav Menu Areas */
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
			'main-menu'   => 'Main Menu',
			'footer-menu' => 'Footer Menu',
		)
	);
}

add_theme_support( 'title-tag' ); // Add support for title tag in wp_head

/* ========================================================================= */
/* Favor GD over Imagick (prevent HTTP Error) */
/* ========================================================================= */

if ( ! function_exists( 'change_graphic_lib' ) ) {
	function change_graphic_lib( $array ) {
		return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
	}
	add_filter( 'wp_image_editors', 'change_graphic_lib' );
}

/* ========================================================================= */
/* Define Additional HTTP Headers */
/* ========================================================================= */

if ( ! function_exists( 'replace_wp_headers' ) ) {
	function replace_wp_headers( $headers ) {
		$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains'; // Forces the User Agent to use HTTPS
		$headers['X-Frame-Options']           = 'SAMEORIGIN'; // Prevents a browser from framing your site and protects against attacks like clickjacking.
		$headers['X-Content-Type-Options']    = 'nosniff'; // Stops a browser from trying to MIME-sniff the content type and forces it to stick with the declared content-type.
		return $headers;
	}
	add_filter( 'wp_headers', 'replace_wp_headers' );
}

/* ========================================================================= */
/* Redirect attachment post to parent post */
/* ========================================================================= */

if ( ! function_exists( 'attachment_redirect' ) ) {
	function attachment_redirect() {
		global $post;
		if ( ! is_attachment() || ! isset( $post->post_parent ) || ! is_numeric( $post->post_parent ) ) {
			return;
		}
		// Does the attachment have a parent post?
		// If the post is trashed, fallback to redirect to homepage.
		if ( 0 !== $post->post_parent && 'publish' === get_post_status( $post->post_parent ) ) {
			// Redirect to the attachment parent.
			wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
		} else {
			// For attachment without a parent redirect to homepage.
			wp_safe_redirect( home_url(), 302 );
		}
		exit;
	}
	add_action( 'template_redirect', 'attachment_redirect', 1 );
}

/* ========================================================================= */
/* WordPress External Files     */
/* ========================================================================= */

require_once 'functions/functions-post-types.php';
require_once 'functions/functions-helpers.php';
require_once 'functions/functions-rg.php';

// setup acf-json folder
if ( ! function_exists( 'jrd_acf_json_save_point' ) ) {
	function jrd_acf_json_save_point( $path ) {
		return get_stylesheet_directory() . '/functions/acf-json';
	}
	if ( ! $is_production ) {
		add_filter( 'acf/settings/save_json', 'jrd_acf_json_save_point' );
	}
}

// make acf read acf-json folder
if ( ! function_exists( 'jrd_acf_json_load_point' ) ) {
	function jrd_acf_json_load_point( $paths ) {
		unset( $paths[0] );
		$paths[] = get_stylesheet_directory() . '/functions/acf-json';
		return $paths;
	}
	add_filter( 'acf/settings/load_json', 'jrd_acf_json_load_point' );
}

require_once 'functions/class-aria-walker-nav-menu.php';
// require_once 'functions/functions-widgets.php';
// require_once 'functions/functions-comments.php';


/* ========================================================================= */
/* WordPress Security */
/* ========================================================================= */

remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version

/* Prevent Login Errors for Security */
add_filter( 'login_errors', '__return_null' );

/* ========================================================================= */
/* DISABLE EMOJIS */
/* ========================================================================= */

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* Globally Hide Admin Meta Boxes */
if ( ! function_exists( 'hide_meta_boxes' ) ) {
	function hide_meta_boxes() {
		remove_meta_box( 'postcustom', 'post', 'normal' );                  // custom fields post
		remove_meta_box( 'postcustom', 'page', 'normal' );                  // custom fields page
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );            // discussion page
		remove_meta_box( 'authordiv', 'page', 'normal' );                   // author page
		remove_meta_box( 'postimagediv', 'page', 'normal' );                // featured image page
		remove_meta_box( 'trackbacksdiv', 'post', 'normal' );               // track backs
		//remove_meta_box( 'pageparentdiv', 'page', 'normal' );             // page attributes
		//remove_meta_box( 'tagsdiv-post-tag', 'post', 'normal' );          // post tags
		//remove_meta_box( 'categorydiv', 'post', 'normal' );               // post categories
		//remove_meta_box( 'postexcerpt', 'post', 'normal' );               // post excerpt
		//remove_meta_box( 'revisionsdiv', 'post', 'normal' );              // revisions post
		//remove_meta_box( 'revisionsdiv', 'page', 'normal' );              // revisions page
		//remove_meta_box( 'postimagediv', 'post', 'normal' );              // featured image post
		//remove_meta_box( 'commentsdiv', 'post', 'normal' );               // comments post
		//remove_meta_box( 'commentsdiv', 'page', 'normal' );               // comments page
		//remove_meta_box( 'authordiv', 'post', 'normal' );                 // author post
		//remove_meta_box( 'commentstatusdiv', 'post', 'normal' );          // discussion post
	}
	add_action( 'admin_init', 'hide_meta_boxes' );
}


/* Hide WordPress Default Dashboard Widgets */
if ( ! function_exists( 'remove_dashboard_widgets' ) ) {
	function remove_dashboard_widgets() {
		global $wp_meta_boxes;
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	}
	add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );
}


/* ========================================================================= */
/* CUSTOM LOGIN STYLES */
/* ========================================================================= */

if ( ! function_exists( 'jrd_login_stylesheet' ) ) {
	function jrd_login_stylesheet() {
		wp_enqueue_style( 'custom-login', jrd_ui( 'css/login.css' ), array(), '1.0.0' );
	}
	add_action( 'login_enqueue_scripts', 'jrd_login_stylesheet' );
}

if ( ! function_exists( 'jrd_login' ) ) {
	function jrd_login() {
		echo '<a href="http://www.jumpingjackrabbit.com/" target="_blank">';
		echo '<div id="jrd-login"></div>';
		echo '</a>';
	}
	add_action( 'login_footer', 'jrd_login' );
}

if ( ! function_exists( 'jrd_login_url' ) ) {
	function jrd_login_url() {
		return home_url();
	}
	add_action( 'login_headerurl', 'jrd_login_url' );
}

if ( ! function_exists( 'jrd_login_title' ) ) {
	function jrd_login_title() {
		return home_url();
	}
	add_action( 'login_headertext', 'jrd_login_title' );
}


/* ========================================================================= */
/* ENQUEUE SCRIPTS */
/* ========================================================================= */

if ( ! function_exists( 'jrd_enqueue_scripts' ) ) {
	function jrd_enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'plugins', jrd_ui( 'js/jquery.plugins.js' ), array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'init', jrd_ui( 'js/jquery.init-dist.js' ), array( 'jquery', 'plugins' ), filemtime( get_template_directory() . '/ui/js/jquery.init.js' ), true );
		wp_localize_script(
			'init',
			'jrd',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'https' ),
				'nonce'    => wp_create_nonce( 'ajax-nonce' ),
			)
		);
	}
	add_action( 'wp_enqueue_scripts', 'jrd_enqueue_scripts' );
}


/* ========================================================================= */
/* ENQUEUE STYLES */
/* ========================================================================= */

if ( ! function_exists( 'jrd_enqueue_styles' ) ) {
	function jrd_enqueue_styles() {
		wp_enqueue_style( 'style', jrd_ui( 'css/style.css' ), array(), filemtime( get_template_directory() . '/ui/css/style.css' ) );
	}
	// With Print Style Sheet
	// function enqueue_styles() {
	//     wp_enqueue_style( 'style', '/ui/css/style.css', array(), null, 'screen' );
	//     wp_enqueue_style( 'print', '/ui/css/print.css', array(), null, 'print' );
	// }
	add_action( 'wp_enqueue_scripts', 'jrd_enqueue_styles' );
}


/* Add a Stylesheet for Admin Content Area */
if ( ! function_exists( 'admin_font_setup' ) ) {
	function admin_font_setup() {
		add_editor_style( array( 'style-wysiwyg.css?v=' . current_time( 'his' ), '/' ) );
	}
	add_action( 'after_setup_theme', 'admin_font_setup' );
}

if ( ! function_exists( 'jrd_custom_fonts' ) ) {
	function jrd_custom_fonts() {
		wp_enqueue_style( 'style-wysiwyg', get_template_directory_uri() . '/style-wysiwyg.css', array(), current_time( 'his' ) );
	}
	add_action( 'admin_head', 'jrd_custom_fonts' );
}

if ( ! function_exists( 'jrd_custom_css' ) ) {
	function jrd_custom_css() {
		echo <<<HTML
		  <style>
		.akismet-section-header__actions { display: none; }
		</style>
	HTML;
	}
	add_action( 'admin_head', 'jrd_custom_css' );
}


/* ========================================================================= */
/* GRAVITY FORM CUSTOMIZATIONS */
/* ========================================================================= */

/**
 * Add 'submit btn' as classes for gform submit button
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param string $button Contains the <input> tag to be filtered.
 * @param object $form Contains all the properties of the current form.
 *
 * @return string The filtered button.
 */
/*
if ( ! function_exists( 'add_custom_css_classes' ) ) {
	function add_custom_css_classes( $button, $form ) {
		$dom = new DOMDocument();
		$dom->loadHTML( $button );
		$input    = $dom->getElementsByTagName( 'input' )->item( 0 );
		$classes  = $input->getAttribute( 'class' );
		$classes .= ' submit btn';
		$input->setAttribute( 'class', $classes );
		return $dom->saveHtml( $input );
	}
	add_filter( 'gform_submit_button', 'add_custom_css_classes', 10, 2 );
}
*/

/**
 * Filters the next, previous and submit buttons.
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param string $button Contains the <input> tag to be filtered.
 * @param object $form Contains all the properties of the current form.
 *
 * @return string The filtered button.
 */
if ( ! function_exists( 'input_to_button' ) ) {
	function input_to_button( $button, $form ) {
		$dom = new DOMDocument();
		$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
		$input      = $dom->getElementsByTagName( 'input' )->item( 0 );
		$new_button = $dom->createElement( 'button' );
		$new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
		$input->removeAttribute( 'value' );
		foreach ( $input->attributes as $attribute ) {
			$new_button->setAttribute( $attribute->name, $attribute->value );
		}
		$classes  = $new_button->getAttribute( 'class' );
		$classes .= ' submit btn';
		$new_button->setAttribute( 'class', $classes );
		$input->parentNode->replaceChild( $new_button, $input ); // phpcs:ignore

		return $dom->saveHtml( $new_button );
	}
	add_filter( 'gform_next_button', 'input_to_button', 10, 2 );
	add_filter( 'gform_previous_button', 'input_to_button', 10, 2 );
	add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
}

// Disables the Gravity Forms CSS.
// add_filter( 'gform_disable_css', '__return_true' );

/* ========================================================================= */
/* ADD ACF5 OPTIONS PAGE - more args available at http://www.advancedcustomfields.com/resources/acf_add_options_page/  */
/* ========================================================================= */

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		array(
			'page_title' => 'Options',
			'menu_slug'  => 'options',
		)
	);
}


/* ========================================================================= */
/*  REMOVE [] FROM [...] */
/* ========================================================================= */

if ( ! function_exists( 'new_excerpt_more' ) ) {
	function new_excerpt_more( $more ) {
		return '';
	}
	add_filter( 'excerpt_more', 'new_excerpt_more' );
}


/* ========================================================================= */
/*  BROWSER DETECTION body_class() OUTPUT */
/* ========================================================================= */

function custom_body_browser_classes( $classes ) {
	$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';

	$browser = 'unknown';
	if ( preg_match( '/MSIE|Trident/i', $user_agent ) ) {
		$browser = 'Internet Explorer';
	} elseif ( preg_match( '/Edge/i', $user_agent ) ) {
		$browser = 'Edge';
	} elseif ( preg_match( '/OPR|Opera/i', $user_agent ) ) {
		$browser = 'Opera';
	} elseif ( preg_match( '/Chrome/i', $user_agent ) ) {
		$browser = 'Chrome';
	} elseif ( preg_match( '/Safari/i', $user_agent ) ) {
		$browser = 'Safari';
	} elseif ( preg_match( '/Firefox/i', $user_agent ) ) {
		$browser = 'Firefox';
	}

	$parent = 'unknown';
	if ( in_array( $browser, array( 'Chrome', 'Opera' ) ) ) {
		$parent = 'Chromium';
	} elseif ( 'Safari' === $browser ) {
		$parent = 'WebKit';
	} elseif ( 'Firefox' === $browser ) {
		$parent = 'Gecko';
	} elseif ( 'Internet Explorer' === $browser ) {
		$parent = 'Trident';
	} elseif ( 'Edge' === $browser ) {
		$parent = 'EdgeHTML';
	}

	$platform = 'unknown';
	if ( preg_match( '/windows|win32/i', $user_agent ) ) {
		$platform = 'windows';
	} elseif ( preg_match( '/macintosh|mac os x/i', $user_agent ) ) {
		$platform = 'mac';
	} elseif ( preg_match( '/linux/i', $user_agent ) ) {
		$platform = 'linux';
	} elseif ( preg_match( '/android/i', $user_agent ) ) {
		$platform = 'android';
	} elseif ( preg_match( '/iphone|ipad|ipod/i', $user_agent ) ) {
		$platform = 'ios';
	}

	// Append the classes to the body_class array.
	$classes[] = 'browser-' . sanitize_html_class( strtolower( $browser ) );
	$classes[] = 'parent-' . sanitize_html_class( strtolower( $parent ) );
	$classes[] = 'platform-' . sanitize_html_class( strtolower( $platform ) );

	return $classes;
}
add_filter( 'body_class', 'custom_body_browser_classes' );


/* Apply various additional filters to the_content() and anything with 'the_content' filtering */
if ( ! function_exists( 'jrd_content_filters' ) ) {
	function jrd_content_filters( $content ) {

		/* REMOVE <P> WRAPPER WHEN ONLY <IMG /> IS CONTAINED WITHIN */
		$content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );

		/* INSERT <SPAN> BETWEEN OPEN AND CLOSING <A CLASS='BTN'> TAGS */
		$content = preg_replace( '/(<a .*class=[\'"][\w\d\s-]*btn[\w\d\s-]*[\'"].*>)(.+)(<\/a>)/iU', '\1<span>\2</span>\3', $content );

		/* REMOVE <P> WRAPPER WHEN ONLY A.BTN IS CONTAINED WITHIN */
		$content = preg_replace( '/<p>\s*(<a .*class=[\'"][\w\d\s-]*btn[\w\d\s-]*[\'"].*>.+<\/a>)\s*<\/p>/iU', '\1', $content );

		/* REMOVE EMPTY <P> TAG */
		$content = preg_replace( '/<p>[(?:&nbsp;)\s]*<\/p>/iU', '', $content );

		return $content;
	}
	add_filter( 'the_content', 'jrd_content_filters' );
}

/* RUN ANY ACF WYSIWYG FIELD THROUGH THE "THE_CONTENT" FILTER */
if ( ! function_exists( 'format_value_wysiwyg' ) ) {
	function format_value_wysiwyg( $value, $post_id, $field ) {
		$value = apply_filters( 'the_content', $value );
		return $value;
	}
	add_filter( 'acf/format_value/type=wysiwyg', 'format_value_wysiwyg', 10, 3 );
}


/* ========================================================================= */
/* ADD OPTION TO FILTER PDF IN MEDIA LIBRARY */
/* ========================================================================= */

if ( ! function_exists( 'modify_post_mime_types' ) ) {
	function modify_post_mime_types( $post_mime_types ) {
		// translators: PDF
		$post_mime_types['application/pdf'] = array( __( 'PDFs', 'jrd-theme-name' ), __( 'Manage PDF', 'jrd-theme-name' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>', 'jrd-theme-name' ) );
		return $post_mime_types;
	}
	add_filter( 'post_mime_types', 'modify_post_mime_types' );
}


/* ========================================================================= */
/* SVG Support */
/* ========================================================================= */

if ( ! function_exists( 'cc_mime_types' ) ) {
	function cc_mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	add_filter( 'upload_mimes', 'cc_mime_types' );
}


/* ========================================================================= */
/* Move SEO to Bottom of Edit Page
	Shove Yoast/Rank Math to the bottom of the edit page where it belongs. */
/* ========================================================================= */

if ( ! function_exists( 'seo_to_bottom' ) ) {
	function seo_to_bottom() {
		return 'low';
	}
	add_filter( 'wpseo_metabox_prio', 'seo_to_bottom' );
	add_filter( 'rank_math/metabox/priority', 'seo_to_bottom' );
}



/* ========================================================================= */
/* SHORTCUT CODES */
/* ========================================================================= */
/*
if ( ! function_exists( 'morelink' ) ) {
	function morelink( $atts, $content = null ) {
		$a = shortcode_atts(
			array(
				'link' => '',
				'target' => '',
			),
			$atts
		);
		$link = $a['link'];
		$target = $a['target'];
		return '<a href="' . $link . '" class="btn" target="' . $target . '">' . $content . '</a>';
	}
	add_shortcode( 'button', 'morelink' );
}
*/


/* ========================================================================= */
/* TINYMCE SELECT DROPDOWN CLASS SETUP CODES */
/* ========================================================================= */

if ( ! function_exists( 'jrd_mce_buttons_2' ) ) {
	function jrd_mce_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		$buttons[] = 'superscript';
		$buttons[] = 'subscript';
		return $buttons;
	}
	add_filter( 'mce_buttons_2', 'jrd_mce_buttons_2' );
}

if ( ! function_exists( 'jrd_mce_before_init' ) ) {
	function jrd_mce_before_init( $settings ) {

		$style_formats = array(
			array(
				'title'   => 'Button Link',
				'inline'  => 'a',
				'attributes' => array(
					'class' => "btn",
				),
			),
		);

		for ( $i = 1; $i <= 6; $i++ ) {
			$style_formats[] = array(
				'title'      => "Title $i",
				'selector'   => 'h1, h2, h3, h4, h5, h6, p',
				'attributes' => array(
					'class' => "title{$i}",
				),
			);
		}

		// additional styles go below. be sure to uncomment first
		/*
		$style_formats[] = array(
			'title'    => 'Style Name',
			'selector' => 'p',
			'classes'  => '.style-name',
		);
		*/

		$settings['style_formats'] = json_encode( $style_formats );

		return $settings;
	}
	add_filter( 'tiny_mce_before_init', 'jrd_mce_before_init' );
}

/* ========================================================================= */
/* Add Classes to next_posts_link and prev_posts_link. */
/* ========================================================================= */
/*

if ( ! function_exists( 'post_link_attributes' ) ) {
	function posts_link_attributes() {
		return 'class="btn pink-purple"';
	}
	add_filter( 'next_posts_link_attributes', 'posts_link_attributes' );
	add_filter( 'previous_posts_link_attributes', 'posts_link_attributes' );
}
*/


/* ========================================================================= */
/* !CUSTOM CHILD SITE COLOR
	Add a custom color strip to the header for child sites on a multisite
	install. Helps differentiate them when jumping back and forth.
/* ========================================================================= */
/*

if ( ! function_exists( 'jrd_admin_background' ) ) {
	function jrd_admin_background() {
		wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/css/custom_script.css' );
		global $blog_id;
		$color = '';
		if ( 1 == $blog_id ) {
			$color = '#E1B13A';
		} elseif ( 2 == $blog_id ) {
			$color = '#BA273A';
		} elseif ( 3 == $blog_id ) {
			$color = '#BFD945';
		}
		$custom_css = "#wpadminbar { border-top: 5px solid $color }";
		wp_add_inline_style( 'custom-style', $custom_css );
	}
	add_action( 'admin_enqueue_scripts', 'jrd_admin_background' );
}
*/

/* ========================================================================= */
/* RELEVANSSI Add visible custom fields to the_excerpt when searching.
/* ========================================================================= */

if ( ! function_exists( 'custom_fields_to_excerpts' ) ) {
	function custom_fields_to_excerpts( $content, $post, $query ) {

		$custom_fields            = get_post_custom_keys( $post->ID );
		$remove_underscore_fields = true;

		if ( is_array( $custom_fields ) ) {
			$custom_fields = array_unique( $custom_fields );
			foreach ( $custom_fields as $field ) {
				if ( $remove_underscore_fields ) {
					if ( '_' === substr( $field, 0, 1 ) ) {
						continue;
					}
				}
				if ( is_int( strpos( $field, 'rank_math' ) ) ) {
					continue;
				}
				if ( '_image' === substr( $field, -6 ) ) {
					continue;
				}
				if ( is_int( strpos( $field, 'featured' ) ) ) {
					continue;
				}

				if ( in_array(
					$field,
					array(
						// 'custom_fields', to exclude
					),
					true
				) ) {
					continue;
				}

				$values = get_post_meta( $post->ID, $field, false );
				if ( '' === $values ) {
					continue;
				}
				foreach ( $values as $value ) {
					if ( ! is_array( $value ) ) {
						$content .= ' ' . $value;
					}
				}
			}
		}
		return $content;
	}
	add_filter( 'relevanssi_excerpt_content', 'custom_fields_to_excerpts', 10, 3 );
}

// Stay logged in for longer periods
if ( ! function_exists( 'keep_me_logged_in' ) ) {
	function keep_me_logged_in( $expirein ) {
		return 10 * DAY_IN_SECONDS;
	}
	add_filter( 'auth_cookie_expiration', 'keep_me_logged_in' );
}

//Add instructions to Featured Image metabox
if ( ! function_exists( 'jrd_add_featured_image_size' ) ) {
	function jrd_add_featured_image_size() {
		switch ( get_post_type() ) {
			case 'post':
				$size = '640x480';
				break;
		}
		if ( isset( $size ) ) {
			$content = "$size recommended.";
			$css     = "<style>#postimagediv .inside:after { content: '$content' }</style>";
			echo $css;
		}
	}
	add_action( 'admin_head', 'jrd_add_featured_image_size' );
}

// Prevent non-Jackrabbit users from accessing ACF config pages
if ( is_admin() ) {
	$not_jrd       = -1 === strpos( get_userdata( get_current_user_id() )->data->user_login, 'jackrabbit' );
	$currently_acf = false;
	if ( isset( $_GET['post'] ) ) {
		if ( 'acf-field-group' === get_post( $_GET['post'] )->post_type ) {
			$currently_acf = true;
		}
	}
	if ( isset( $_GET['post_type'] ) && 'acf-field-group' === $_GET['post_type'] ) {
		$currently_acf = true;
	}
	if ( $currently_acf && ( $not_jrd || $is_production ) ) {
		if ( $not_jrd ) {
			$reason = 'This section is for developers only.';
		}
		if ( $is_production ) {
			$reason = 'You are on the Production environment.';
		}
		wp_die( "<h1>Disallowed</h1><p>You are not authorized to edit custom fields on this environment.</p><p><b>Reason:</b> $reason</p>" );
		die;
	}
}

/* ================================================================================ */
/* Add Edit Post Link to Lower Left Corner; Hide Admin Bar for Your User */
/* ================================================================================ */

if ( ! function_exists( 'jrd_edit_post' ) ) {
	function jrd_edit_post() {
		$html = '';
		if ( current_user_can( 'administrator' ) ) {
			global $post;
			wp_enqueue_style( 'dashicons' );
			$html  = '
			<style>
				#jrd-edit-post { position:fixed; left:0; bottom:0; background:#23282d; display:block; width: 70px; height: 70px; transform: translate(-50%, 50%) rotate(45deg); transform-origin: center center; text-align: center; z-index:9999; }
				#jrd-edit-post span { transform: translateX(27px) translateY(4px) rotate(-45deg); display: block; transform-origin: center center; color: #fff; }
			#wpadminbar {display: none;}
			</style>
			';
			$html .= '<a href="' . get_edit_post_link( $post->ID ) . '" id="jrd-edit-post"><span class="dashicons dashicons-edit"></span></a>';
		}
		echo $html;
	}
	add_action( 'wp_footer', 'jrd_edit_post' );
}

// Remove 32px top margin from html tag
if ( ! function_exists( 'remove_admin_login_header' ) ) {
	function remove_admin_login_header() {
		remove_action( 'wp_head', '_admin_bar_bump_cb' );
	}
	add_action( 'get_header', 'remove_admin_login_header' );
}

// Change admin verification screen to only show up once a year.
if ( ! function_exists( 'year_in_seconds' ) ) {
	function year_in_seconds() {
		return YEAR_IN_SECONDS;
	}
	add_filter( 'admin_email_check_interval', 'year_in_seconds' );
}

if ( ! function_exists( 'jrd_function_name' ) ) {
	function jrd_function_name() {
		// use $_POST variables to pull in data
		$foo = $_POST['foo'];
		// if output is an array, make sure you json_encode() the variable being echoed
		echo $foo;
		wp_die();
	}
	add_action( 'wp_ajax_function_name', 'jrd_function_name' );
	add_action( 'wp_ajax_nopriv_my_function_name', 'jrd_function_name' );
}

/**
 * Allow mailto: and tel: links in URL field
 */
if ( ! function_exists( 'jrd_validate_acf_urls' ) ) {
	function jrd_validate_acf_urls( $valid, $value, $field, $input ) {
		if ( true === $valid ) {
			return $valid;
		}
		if ( 'url' === $field['type'] ) {
			if ( preg_match( '/^(mailto\:|tel\:).*/', $value ) ) {
				$valid = true;
			} else {
				$valid = 'Value must be a valid URL, mailto:, or tel: link.';
			}
		}
		return $valid;
	}
	add_filter( 'acf/validate_value', 'jrd_validate_acf_urls', 10, 4 );
}

// truly lock out password-protected pages
if ( ! function_exists( 'jrd_password_protected_filter' ) ) {
	function jrd_password_protected_filter() {
		if ( post_password_required() ) {
			the_content();
			get_footer();
			exit;
		}
	}
	add_filter( 'loop_start', 'jrd_password_protected_filter' );
}

// block users endpoint
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );


/* ================================================================================ */
/* Restrict Access to Activity Log */
/* ================================================================================ */

function is_jrd_user() {
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    // Regex pattern to match only @jumpingjackrabbit.com emails
    $pattern = '/^[a-zA-Z0-9._%+-]+@jumpingjackrabbit\.com$/';

    if ( preg_match( $pattern, $email ) ) {
        return true;
    }

    return false;
}

add_action( 'admin_menu', 'jrd_restrict_plugin_access_for_users', 999 );

function jrd_restrict_plugin_access_for_users() {
    $restricted_plugin_slug = 'activity-log-page'; // e.g., 'wp-mail-smtp' or 'some-plugin/settings.php'

    if ( !is_jrd_user() ) {
        remove_menu_page( $restricted_plugin_slug );
    }
}

// Prevent direct access to plugin page even if user knows the URL
add_action( 'admin_init', 'jrd_block_plugin_page_access' );

function jrd_block_plugin_page_access() {
    $restricted_plugin_page = 'activity-log-page'; // e.g., 'wp-mail-smtp' or 'some-plugin/settings.php'

    if ( isset( $_GET['page'] ) && $_GET['page'] === $restricted_plugin_page ) {
        if ( !is_jrd_user() ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }
    }
}

// Removes from Plugin list
add_filter( 'all_plugins', 'jrd_hide_plugins_from_list' );

function jrd_hide_plugins_from_list( $plugins ) {

    if ( !is_jrd_user() ) {
        unset( $plugins['aryo-activity-log/aryo-activity-log.php'] ); // Replace with actual plugin path
    }

    return $plugins;
}