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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'cpaexamg_cpaexamguide');

/** MySQL database username */
define('DB_USER', 'cpaexamg_adminBK');

/** MySQL database password */
define('DB_PASSWORD', 'Impreza12');

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
define('AUTH_KEY',         'OaQV6@Pg1A^Kx[FfE#a-mW*h@m@WzJnhK8-x-|+t;[ox8`#/e?iQ8cpt #x/yC+r');
define('SECURE_AUTH_KEY',  'LXN1 F3JT!kNiN4#(3OJ%DISPFcj#YM8sdt7OK,-e!O7ucG~!4`#0O%QMWZY,mZ-');
define('LOGGED_IN_KEY',    'g%)tPM+hi)eaI^r+O9l[R,;HZ(?2mM,t3Div(]f=o-)4:;GBtqGb`gt|m(!]#C6r');
define('NONCE_KEY',        'wfV?(=;)<lW_Jv+S&W08 m%fs4M>kTv4{tzKCy?zOTR8U56 >zic3U~e`AdY|-3a');
define('AUTH_SALT',        ',W.y1X.[d,+9Seol8>4k=9!2MMCAG~rAxyt(Feo/33=a`nFq@#P3(q-3*A*pKfh|');
define('SECURE_AUTH_SALT', '~T_h5xI/zSNv-Is{{F>pCg`|Lk,wdhw2zNdDR#Y<S|=nN@r9~Lt|[N*dM*sjerT7');
define('LOGGED_IN_SALT',   'wsl0sd)s2U-ya+PWGyN-G&E!en{Q|Oj9f.s2i?lE]Ei{c%28E<^& :2}I<qe(z+H');
define('NONCE_SALT',       'rD&2pbJW}qZP68PhRtw%-#`8|-+SelM-e#asWa}RY7fj_;@YTD@7E.x&{v!NTPK0');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_534hfd';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
