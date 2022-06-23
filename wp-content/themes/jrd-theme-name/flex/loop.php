<?php
/*
 * Usage: Add the function below to the templates that flexible content modules should be
 * get_template_part( 'flex/loop' );
 * Create a flex-{layout slug}.php for each flexible content layout you have. The slug of the layout will dictate the template part requested.
 */

if ( have_rows( 'flexible_content' ) ) {
	while ( have_rows( 'flexible_content' ) ) {
		the_row();
		$layout = str_replace( '_', '-', get_row_layout() );
		get_template_part( "flex/$layout" );
	}
}
