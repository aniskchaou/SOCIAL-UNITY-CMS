<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'social-unity' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Qhbool%L%hlc{Oq.+nTL<YlnQn$ nV,kwA],hC/OEX[60%*oH,8$Pd9qhZ|v4<>v' );
define( 'SECURE_AUTH_KEY',  'ytsk3]T@8Dl^21.f@{SFr:J341+&*8c>?A0kxlARZBVmCuyXMs[[>V=x(@}jy|.g' );
define( 'LOGGED_IN_KEY',    'qG2I.T{qHLyi#C[ieEMr!dipR)HaMH53(mDAp[5+sS{:l[^8XNmsf@+9g}N3x~lV' );
define( 'NONCE_KEY',        'U^kWw<[jYOs9nzs[}/P,[,;b> CTG=g?~;ti9KxRJn$gs,(+6u&Hn_wWBj6k0<:/' );
define( 'AUTH_SALT',        'vNPX z!U/pKKp>#jFcc9arU]$v]@^Vb(]9kprmU[Dc.WmG?eheb8SE(iArQ}9[Qx' );
define( 'SECURE_AUTH_SALT', 'aCIN .^c-30>e?eP8=qu@LwP3sp1 ZaX0ik}J g9/)Olp{mBJ?ysw}fL?lNGErOo' );
define( 'LOGGED_IN_SALT',   '(Nmv{hP?-&ZpL$1h7O6=q)R:@@^?5`k.r~b<`C1p;al1[:z^Iv~s$(;qR2I#Ph3u' );
define( 'NONCE_SALT',       '@`~HLaV9+{UuGFa@1/(^J==r2R$`Dppm-5<58{>Gf.m0OH*xs *(ca]n}MK~{1|8' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
