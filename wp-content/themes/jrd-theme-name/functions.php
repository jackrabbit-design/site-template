<?php
$production_url = ''; // set this to the Production URL after this site's Staging environment is created post-launch, e.g. 'www.jumpingjackrabbit.com';
$is_production  = $_SERVER['HTTP_HOST'] === $production_url;

// Use this as a conditional instead of is_user_logged_in(). This function is more strict, as long as your wp username is admin_jackrabbit
function is_jrd() {
	return 'admin_jackrabbit' === wp_get_current_user()->user_login;
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
function jrd_add_img_sizes( $size, $width, $height, $crop = true ) {
	add_image_size( $size, $width, $height, $crop );
	add_image_size( "$size-2x", $width * 2, $height * 2, $crop );
	add_image_size( "$size-m", $width / 2, $height / 2, $crop );
}
add_theme_support( 'post-thumbnails' );
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

add_filter( 'wp_image_editors', 'change_graphic_lib' );

function change_graphic_lib( $array ) {
	return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
}

/* ========================================================================= */
/* Define Additional HTTP Headers */
/* ========================================================================= */

function replace_wp_headers( $headers ) {
	$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains'; // Forces the User Agent to use HTTPS
	$headers['X-Frame-Options']           = 'SAMEORIGIN'; // Prevents a browser from framing your site and protects against attacks like clickjacking.
	$headers['X-Content-Type-Options']    = 'nosniff'; // Stops a browser from trying to MIME-sniff the content type and forces it to stick with the declared content-type.
	return $headers;
}
add_filter( 'wp_headers', 'replace_wp_headers' );

/* ========================================================================= */
/* WordPress External Files     */
/* ========================================================================= */

require_once 'functions/functions-post-types.php';
require_once 'functions/functions-helpers.php';
require_once 'functions/functions-rg.php';
/* == Uncomment below line when site launches == */
// require_once 'functions/functions-acf.php';

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


/* Hide WordPress Default Dashboard Widgets */
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


/* ========================================================================= */
/* CUSTOM LOGIN STYLES */
/* ========================================================================= */

function my_login_stylesheet() {
	wp_enqueue_style( 'custom-login', jrd_ui( 'css/login.css' ), array(), '1.0.0' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

function jrd_login() {
	echo '<a href="http://www.jumpingjackrabbit.com/" target="_blank">';
	echo '<div id="jrd-login"></div>';
	echo '</a>';
}
add_action( 'login_footer', 'jrd_login' );

function jrd_login_url() {
	return home_url();
}
add_action( 'login_headerurl', 'jrd_login_url' );

function jrd_login_title() {
	return home_url();
}
add_action( 'login_headertext', 'jrd_login_title' );


/* ========================================================================= */
/* ENQUEUE SCRIPTS */
/* ========================================================================= */

function enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'modernizr', jrd_ui( 'js/modernizr.js' ), array(), '1.0.0', true );
	wp_enqueue_script( 'svgxuse', jrd_ui( 'js/svgxuse.js' ), array(), '1.0.0', true );
	wp_enqueue_script( 'plugins', jrd_ui( 'js/jquery.plugins.js' ), array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'init', jrd_ui( 'js/jquery.init.js' ), array( 'jquery', 'plugins', 'modernizr' ), filemtime( get_template_directory() . '/ui/js/jquery.init.js' ), true );
	wp_localize_script(
		'init',
		'jrd',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'https' ),
			'nonce'    => wp_create_nonce( 'ajax-nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );


/* ========================================================================= */
/* ENQUEUE STYLES */
/* ========================================================================= */

function enqueue_styles() {
	wp_enqueue_style( 'style', jrd_ui( 'css/style.css' ), array(), filemtime( get_template_directory() . '/ui/css/style.css' ) );
}
// With Print Style Sheet
// function enqueue_styles() {
//     wp_enqueue_style( 'style', '/ui/css/style.css', array(), null, 'screen' );
//     wp_enqueue_style( 'print', '/ui/css/print.css', array(), null, 'print' );
// }
add_action( 'wp_enqueue_scripts', 'enqueue_styles' );


/* Add a Stylesheet for Admin Content Area */
function admin_font_setup() {
	add_editor_style( array( 'style-wysiwyg.css?v=' . current_time( 'his' ), '/' ) );
}
add_action( 'after_setup_theme', 'admin_font_setup' );

function my_custom_fonts() {
	wp_enqueue_style( 'style-wysiwyg', get_template_directory_uri() . '/style-wysiwyg.css', array(), current_time( 'his' ) );
}
add_action( 'admin_head', 'my_custom_fonts' );


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
add_filter( 'gform_submit_button', 'add_custom_css_classes', 10, 2 );
function add_custom_css_classes( $button, $form ) {
	$dom = new DOMDocument();
	$dom->loadHTML( $button );
	$input    = $dom->getElementsByTagName( 'input' )->item( 0 );
	$classes  = $input->getAttribute( 'class' );
	$classes .= ' submit btn';
	$input->setAttribute( 'class', $classes );
	return $dom->saveHtml( $input );
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
add_filter( 'gform_next_button', 'input_to_button', 10, 2 );
add_filter( 'gform_previous_button', 'input_to_button', 10, 2 );
add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
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

// Disables the Gravity Forms CSS.
add_filter( 'gform_disable_css', '__return_true' );

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

function new_excerpt_more( $more ) {
	return '';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );


/* ========================================================================= */
/*  BROWSER DETECTION body_class() OUTPUT */
/* ========================================================================= */

function body_class_adjustments( $classes1 ) {

	$browser = $_SERVER['HTTP_USER_AGENT'];
	global  $is_iphone,     //iPhone Safari
			$is_chrome,     //Google Chrome
			$is_safari,     //Safari
			$is_NS4,        //NetScape 4
			$is_opera,      //Opera
			$is_macIE,      //Mac Internet Explorer
			$is_winIE,      //Windows Internet Explorer
			$is_gecko,      //FireFox
			$is_lynx,       //Lynx - The Rad Termninal Browsers
			$is_IE,         //Internet Explore
			$is_edge;       //Microsoft Edge

	$classes = array();
	/* Browsers no one cares about or uses... just fun to keep lol lynx*/
	if ( $is_lynx ) {
		$classes[] = 'lynx';
	} elseif ( $is_NS4 ) {
		$classes[] = 'ns4';
	} elseif ( $is_opera ) {
		$classes[] = 'opera';
	} elseif ( $is_chrome ) {
		$browser   = explode( ' ', $browser );
		$browser   = explode( '/', $browser[11] );
		$browser   = explode( '.', $browser[1] );
		$browser   = 'chrome-' . $browser[0];
		$classes[] = $browser;
		$classes[] = 'chrome';
	} elseif ( $is_gecko ) {
		$browser   = explode( 'Gecko', $browser )[1];
		$browser   = explode( ' ', $browser )[1];
		$browser   = strtolower( $browser );
		$browser   = str_replace( '/', '-', str_replace( '.0', '', $browser ) );
		$classes[] = $browser;
		$classes[] = 'gecko';
	} elseif ( $is_safari ) {
		$browser   = explode( ' ', $browser );
		$browser   = str_replace( 'Version/', '', $browser[11] );
		$browser   = explode( '.', $browser );
		$browser   = 'safari-' . $browser[0];
		$classes[] = $browser;
		$classes[] = 'safari';
	} elseif ( $is_edge ) {
		$browser   = explode( ' ', $browser );
		$browser   = strtolower( str_replace( '/', '-', $browser[12] ) );
		$browser   = explode( '.', $browser );
		$browser   = $browser[0];
		$classes[] = $browser;
		$classes[] = 'edge';
	} elseif ( $is_IE ) {
		$iecheck = substr( "$browser", 25, 8 );
		if ( 'MSIE 10.' === $iecheck ) {
			$classes[] = 'ie10';
			$classes[] = 'ie';
		} else {
			//Assume ie11 it's the last one.
			$classes[] = 'ie11';
			$classes[] = 'ie';
		}
	}

	return array_merge( $classes1, $classes );
}
add_filter( 'body_class', 'body_class_adjustments' );

/* Apply various additional filters to the_content() and anything with 'the_content' filtering */
add_filter( 'the_content', 'jrd_content_filters' );
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

/* RUN ANY ACF WYSIWYG FIELD THROUGH THE "THE_CONTENT" FILTER */
add_filter( 'acf/format_value/type=wysiwyg', 'format_value_wysiwyg', 10, 3 );
function format_value_wysiwyg( $value, $post_id, $field ) {
	$value = apply_filters( 'the_content', $value );
	return $value;
}


/* ========================================================================= */
/* ADD OPTION TO FILTER PDF IN MEDIA LIBRARY */
/* ========================================================================= */

function modify_post_mime_types( $post_mime_types ) {
	// translators: PDF
	$post_mime_types['application/pdf'] = array( __( 'PDFs', 'jrd-theme-name' ), __( 'Manage PDF', 'jrd-theme-name' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>', 'jrd-theme-name' ) );
	return $post_mime_types;
}
add_filter( 'post_mime_types', 'modify_post_mime_types' );


/* ========================================================================= */
/* SVG Support */
/* ========================================================================= */

function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );


/* ========================================================================= */
/* Move SEO to Bottom of Edit Page
	Shove Yoast/Rank Math to the bottom of the edit page where it belongs. */
/* ========================================================================= */

function seo_to_bottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'seo_to_bottom' );
add_filter( 'rank_math/metabox/priority', 'seo_to_bottom' );



/* ========================================================================= */
/* SHORTCUT CODES */
/* ========================================================================= */
/*
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
*/


/* ========================================================================= */
/* TINYMCE SELECT DROPDOWN CLASS SETUP CODES */
/* ========================================================================= */

add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

function my_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	$buttons[] = 'superscript';
	$buttons[] = 'subscript';
	return $buttons;
}

add_filter( 'tiny_mce_before_init', 'my_mce_before_init' );

function my_mce_before_init( $settings ) {

	$style_formats = array(
		array(
			'title'   => 'Button Link',
			'inline'  => 'a',
			'classes' => 'btn',
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

/* ========================================================================= */
/* Add Classes to next_posts_link and prev_posts_link. */
/* ========================================================================= */
/*
add_filter( 'next_posts_link_attributes', 'posts_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'posts_link_attributes' );

function posts_link_attributes() {
	return 'class="btn pink-purple"';
} */


/* ========================================================================= */
/* !CUSTOM CHILD SITE COLOR
	Add a custom color strip to the header for child sites on a multisite
	install. Helps differentiate them when jumping back and forth.
/* ========================================================================= */
/*
add_action( 'admin_enqueue_scripts', 'my_admin_background' );
function my_admin_background() {
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
} */

/* ========================================================================= */
/* RELEVANSSI Add visible custom fields to the_excerpt when searching.
/* ========================================================================= */
add_filter( 'relevanssi_excerpt_content', 'custom_fields_to_excerpts', 10, 3 );
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

// Stay logged in for longer periods
add_filter( 'auth_cookie_expiration', 'keep_me_logged_in' );
function keep_me_logged_in( $expirein ) {
	return 10 * DAY_IN_SECONDS;
}

//Add instructions to Featured Image metabox
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

// Remove 32px top margin from html tag
function remove_admin_login_header() {
	remove_action( 'wp_head', '_admin_bar_bump_cb' );
}
add_action( 'get_header', 'remove_admin_login_header' );

// Change admin verification screen to only show up once a year.
function year_in_seconds() {
	return YEAR_IN_SECONDS;
}
add_filter( 'admin_email_check_interval', 'year_in_seconds' );

add_action( 'wp_ajax_function_name', 'my_function_name' );
add_action( 'wp_ajax_nopriv_my_function_name', 'my_function_name' );
function my_function_name() {
	// use $_POST variables to pull in data
	$foo = $_POST['foo'];
	// if output is an array, make sure you json_encode() the variable being echoed
	echo $foo;
	wp_die();
}
