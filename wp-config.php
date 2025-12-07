<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          's2_c%O/^[acbqj^yUEz;#GSee@{m63|$bn=Wb$hLcYdAN@+2TcW0o7z^^^l%#.H?' );
define( 'SECURE_AUTH_KEY',   '|&3X|Dv~pxFqkLNKFGX/`4JuI|THt)g6EU2k^BGAb/0Iy-y0Qd*wK5ACj?NI=a6R' );
define( 'LOGGED_IN_KEY',     'x7e?NcPFGZK5|-!L3.Z@T%KTlk?:v4MMlVtQHQ:v^Ne06Che--cKx-JIzWw,P+`h' );
define( 'NONCE_KEY',         'wYp5x~2<^v,B$=x&=`1mbgi?JRA/OT7jUOv%RlZ}ck59g+C.jhRi^(q^Xv;e+zfj' );
define( 'AUTH_SALT',         '5%S3>%9>2xa~0[X4PFaZOlq oy<?T&~*<I6x4UW8>nFf;16::xA5^$>SE8.Hv!p8' );
define( 'SECURE_AUTH_SALT',  '$Zd65(HZ`_e^oDX5p,5l.vi+R9b6@9x*DXK-h(xutFcFlcBic1kjA># pcF#hCVT' );
define( 'LOGGED_IN_SALT',    'sI.O]D_soF!$Hd#ttXGNK?*%r@f|KvEN4x4l#1RY:YJ0k4SWDd2NfUe0MIeKHY:e' );
define( 'NONCE_SALT',        '._G:$&o{3#1l+ewduM=@UT~V<Raif`f3<AD56+VQHJ0%~wzMD5E9SrHhuxKh@ANc' );
define( 'WP_CACHE_KEY_SALT', '9_oJ`1fi,H1(}&2=12AhD^/rlo^R`T<yOza8*5?AwvTwfgHc.4Znx~=r3_)Y~Jz2' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
