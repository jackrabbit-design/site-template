<?php

/*
// Sample Register Post
$post_name          = 'Newsroom'; // Name of post type
$post_name_slug     = 'news-post'; // Name of post type
$post_name_singular = 'News Post'; // Singular Name
$post_name_plural   = 'News Posts'; // Plural Name
$post_dash_icon     = 'dashicons-admin-post'; // Define Dashicon | Commonly Used: News = dashicons-welcome-widgets-menus, Clients - dashicons-businessman, Team - dashicons-groups, Event - dashicons-calendar, Full List - https://developer.wordpress.org/resource/dashicons/

register_post_type(
	$post_name_slug,
	array(
		'labels'              => array(
			'name'               => $post_name,
			'singular_name'      => $post_name_singular,
			'add_new'            => 'Add ' . $post_name_singular,
			'add_new_item'       => 'Add ' . $post_name_singular,
			'edit_item'          => 'Edit ' . $post_name_singular,
			'search_items'       => 'Search ' . $post_name_plural,
			'not_found'          => 'No ' . $post_name_plural . ' found',
			'not_found_in_trash' => 'No ' . $post_name_plural . ' found in trash',
		),
		'public'              => true,
		'show_ui'             => true,
		'capability_type'     => 'post',
		'menu_icon'           => $post_dash_icon,
		'hierarchical'        => true,
		'rest_api'            => true,
		'rewrite'             => array( 'slug' => $post_name_slug ),
		'query_var'           => true,
		'show_in_nav_menus'   => true,
		'exclude_from_search' => false,
		'has_archive'         => false,
		'supports'            => array(
			'title',
			'editor',
			'author',
			'thumbnail', //featured image, theme must also support thumbnails
			'excerpt',
			'trackbacks',
			'custom-fields',
			'comments',
			'revisions',
			'page-attributes', //template and menu order, hierarchical must be true
		),
	)
);

// Sample Register Taxonomy
$taxonomy_name          = 'News Type';
$taxonomy_name_slug     = 'news-type';
$taxonomy_name_singular = 'News Type';
$taxonomy_name_plural   = 'News Types';
register_taxonomy(
	$taxonomy_name_slug,
	array( $post_name_slug ),
	array(
		'hierarchical' => true,                                   // Category or Tag functionality
		'query_var'    => true,
		'rewrite'      => array( 'slug' => $taxonomy_name_slug ),
		'labels'       => array(
			'name'                       => $taxonomy_name,
			'singular_name'              => $taxonomy_name_singular,
			'search_items'               => 'Search ' . $taxonomy_name_plural,
			'popular_items'              => 'Popular ' . $taxonomy_name_plural,
			'all_items'                  => 'All ' . $taxonomy_name_plural,
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => 'Edit ' . $taxonomy_name_singular,
			'update_item'                => 'Update ' . $taxonomy_name_singular,
			'add_new_item'               => 'Add New ' . $taxonomy_name_singular,
			'new_item_name'              => 'New ' . $taxonomy_name_singular,
			'separate_items_with_commas' => 'Separate ' . $taxonomy_name_plural . ' with commas',
			'add_or_remove_items'        => 'Add or remove ' . $taxonomy_name_plural,
			'choose_from_most_used'      => 'Choose from most used ' . $taxonomy_name_plural,
		),
	)
);
*/




// Change Capitlites for Members Plugin
// Repalce 'capability_type' => 'post', with this
/*
$something = array(
	'map_meta_cap'    => true,
	'capability_type' => $post_name_slug,
	'capabilities'    => array(
		'edit_post'          => 'edit_' . $post_name_slug,
		'read_post'          => 'read_' . $post_name_slug,
		'delete_post'        => 'delete_' . $post_name_slug,
		'edit_posts'         => 'edit_' . $post_name_slug,
		'edit_others_posts'  => 'edit_others_' . $post_name_slug,
		'publish_posts'      => 'publish_' . $post_name_slug,
		'read_private_posts' => 'read_private_' . $post_name_slug,
		'create_posts'       => 'edit_' . $post_name_slug,
	),
);
*/
