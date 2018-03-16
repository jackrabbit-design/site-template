<?php
/*

// Sample Register Post
$postName         = 'Newsroom'; // Name of post type
$postNameSlug     = 'news-post'; // Name of post type
$postNameSingular = 'News Post'; // Singular Name
$postNamePlural   = 'News Posts'; // Plural Name
$postDashIcon     = 'dashicons-admin-post'; // Define Dashicon | Commonly Used: News = dashicons-welcome-widgets-menus, Clients - dashicons-businessman, Team - dashicons-groups, Event - dashicons-calendar, Full List - https://developer.wordpress.org/resource/dashicons/

register_post_type(
	$postNameSlug, array(
		'labels' => array(
	       'name' => $postName,
	       'singular_name' => $postNameSingular,
	       'add_new' => 'Add ' . $postNameSingular,
	       'add_new_item' => 'Add ' . $postNameSingular,
	       'edit_item' => 'Edit ' . $postNameSingular,
	       'search_items' => 'Search ' . $postNamePlural,
	       'not_found' => 'No ' . $postNamePlural. ' found',
	       'not_found_in_trash' => 'No ' . $postNamePlural. ' found in trash'
	    ),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
        'menu_icon' => $postDashIcon,
		'hierarchical' => true,
		'rest_api' => true,
		'rewrite' => array( 'slug' => $postNameSlug ),
		'query_var' => true,
		'show_in_nav_menus' => true,
		'exclude_from_search' => false,
		'has_archive' => false,
		'supports' => array(
    		'title',
    		'editor',
    		'author',
    		'thumbnail', //featured image, theme must also support thumbnails
    		'excerpt',
    		'trackbacks',
    		'custom-fields',
    		'comments',
    		'revisions',
    		'page-attributes' //template and menu order, hierarchical must be true
		)
	)
);

// Sample Register Taxonomy
$taxonomyName         = 'News Type';
$taxonomyNameSlug     = 'news-type';
$taxonomyNameSingular = 'News Type';
$taxonomyNamePlural   = 'News Types';
register_taxonomy(
	$taxonomyNameSlug, array( $postNameSlug ), array(
		'hierarchical' => true, // Category or Tag functionality
		'query_var' => true,
		'rewrite' => array( 'slug' => $taxonomyNameSlug ),
		'labels' => array(
		     'name' => $taxonomyName,
		     'singular_name' => $taxonomyNameSingular,
		     'search_items' => 'Search ' . $taxonomyNamePlural,
		     'popular_items' => 'Popular ' . $taxonomyNamePlural,
		     'all_items' => 'All ' . $taxonomyNamePlural,
		     'parent_item' => null,
		     'parent_item_colon' => null,
		     'edit_item' => 'Edit ' . $taxonomyNameSingular,
		     'update_item' => 'Update ' . $taxonomyNameSingular,
		     'add_new_item' => 'Add New ' . $taxonomyNameSingular,
		     'new_item_name' => 'New ' . $taxonomyNameSingular,
		     'separate_items_with_commas' => 'Separate ' . $taxonomyNamePlural . ' with commas',
		     'add_or_remove_items' => 'Add or remove ' . $taxonomyNamePlural,
		     'choose_from_most_used' => 'Choose from most used ' . $taxonomyNamePlural
		 )
	)
);
*/
