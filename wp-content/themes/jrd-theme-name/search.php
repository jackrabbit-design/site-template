<?php
$title       = __( 'Search Results', 'jrd-theme-name' );
$found       = $wp_query->found_posts;
$search_term = esc_textarea( get_query_var( 's' ) );
// translators: [COUNT] result(s) found for [SEARCHTERM]
$description = sprintf( _n( '%1$d result found for "%2$s"', '%1$d results found for "%2$s"', $found, 'jrd-theme-name' ), $found, $search_term );
