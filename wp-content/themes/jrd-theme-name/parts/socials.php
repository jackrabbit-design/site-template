<?php
//use get_template_part( 'parts/socials' ) to include this code
//nest this within ACF group markup for use in the options page
//in ACF, clone the Part - Socials field group
if ( have_rows( 'social_links' ) ) {
	echo "<div class='socials'>";
	while ( have_rows( 'social_links' ) ) {
		the_row();
		$platform    = get_sub_field( 'social_platform' );
		$link        = get_sub_field( 'social_link' );
		$aria_string = 'Link to [platform]';
		$aria_label  = esc_attr( str_replace( '[platform]', $platform, $aria_string ) );
		if ( $link ) {
			$url    = $link['url'];
			$target = $link['target'];
		}
		if ( 'email' === $platform ) {
			$url    = 'mailto:' . get_sub_field( 'email_address' );
			$target = '_blank';
		}
		$url    = esc_url( $url );
		$target = esc_attr( $target );
		echo "<a class='social' aria-label='$aria_label' href='$url' target='$target'>";
			echo jrd_use( "social_$platform" );
		echo '</a>' . PHP_EOL;
	}
	echo '</div>' . PHP_EOL; ?>
	<?php
}
