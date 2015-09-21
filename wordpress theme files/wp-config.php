<?php
//define('WP_HOME','http://');
//define('WP_SITEURL','http://');
define('UPLOADS', 'assets');
define('DISALLOW_FILE_EDIT', true);
define('AUTOMATIC_UPDATER_DISABLED', true);

/* Define website URL assets folder and FTP connection info */
/*
define('WP_HOME','http://WWW.DOMAIN.COM');
define('WP_SITEURL','http://WWW.DOMAIN.COM');
define( 'UPLOADS', '/assets' );
define('FTP_HOST', '');
define('FTP_USER', '');
define('FTP_PASS', '!');
define('FTP_METHOD', 'ftpext');
*/



// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'U-SQk*-~-zR0g(lp(S^XzHN@i(%Eo%|I7/{z_0~d4^vmH0)(R9LX&3uJ|z0cjX.w');
define('SECURE_AUTH_KEY',  '+><s@[P5`GP[I>_`u}IYGL?gC!-1f*[?+CQn<06|tdS8{TCR5Q6]]Y*E7(~6#MA0');
define('LOGGED_IN_KEY',    '4#-5R{^C:OR8FKJzK`9JRf|,0cgM-jB-e!w5ZKHNVM8@IfhO2fNK$|)||#CE5$si');
define('NONCE_KEY',        '4N4pO}#L)MtwD7 y{r,GmWTV~ae-q==7Yf0uEtt 95_b0`b%6y ,vrC8IY/|A@4e');
define('AUTH_SALT',        '+rr/+,ooRuZ-Tcr:CA]b_E//pYhW{%L|2u3uR&FuAF+k|-d-e)H-&-_pRd?pV.^n');
define('SECURE_AUTH_SALT', 'EOq,^p0[!M_a(~_IDuUBLN#Y,3]g0y,WKW$|&.hOUH!y|(_4s|+>[n=uH(b:|`#1');
define('LOGGED_IN_SALT',   ',TQ[`c1KYa7 ,A4GH/yBXY^{=R=PVvTsK}}0tfjSaA&}kleU1#o33FHO1|?!+z%q');
define('NONCE_SALT',       'my(3[[UIDRjyDa]&%66g<gHcrn<|Y pL^< BDuHpyqw@=jSIIh?W%KIvS<y_wUH&');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);


/* REVISION SETTINGS */
define('WP_POST_REVISIONS', 5);
define('WP_POST_REVISIONS', false );
define('AUTOSAVE_INTERVAL', 160 );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
