<?php
/* Note: Copy/paste the below to the beginning of your wp-config.php file. */

//define('WP_HOME','http://WWW.DOMAIN.COM');
//define('WP_SITEURL','http://WWW.DOMAIN.COM');

// define('UPLOADS', 'assets'); // Define website URL assets folder, only on non-WPEngine installs
define('DISALLOW_FILE_EDIT', true);

/* DEBUG MODE */
define( 'WP_DEBUG', isset( $_GET['debug'] ) );

/* REVISION SETTINGS */
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 160 );

/* MULTISITE SETTINGS */
//define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] ); // Define cookie location for multisite children