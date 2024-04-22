<?php
function jrd_acf_gui_notice() {
	?>
	<div class="notice notice-warning">
		<p>Your theme is currently utilizing <code>functions-acf.php</code>. Following any changes made, the contents of that file must be replaced with a new export of all field groups.</p>
	</div>
	<?php
}
$currently_acf = false;
if ( isset( $_GET['post'] ) ) {
	if ( 'acf-field-group' === get_post( $_GET['post'] )->post_type ) {
		$currently_acf = true;
	}
}
if ( isset( $_GET['post_type'] ) && 'acf-field-group' === $_GET['post_type'] ) {
	$currently_acf = true;
}
if ( $currently_acf ) {
	add_action( 'admin_notices', 'jrd_acf_gui_notice' );
}
//phpcs:disable
//This is where your PHP export from Custom Fields > Tools > Export goes.
