<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
define( 'BP_DEFAULT_COMPONENT', 'profile' );
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'grirg_org');

/** MySQL database username */
define('DB_USER', 'grirgorg');

/** MySQL database password */
define('DB_PASSWORD', '1^zOtn40~d8');

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
define('AUTH_KEY',         '"QPIk~L^Y5TF|!/:Ctyb*14^dMJ8t"4KPyn0QiI8A/@Ad`8azkZadLGD&Omp`%s/');
define('SECURE_AUTH_KEY',  'S3_3TeG3W&idEQCRn81KT%:&HeBaVDNE$^0~FGSZ_2/w:+ptzqxh"xZxNghA%V1k');
define('LOGGED_IN_KEY',    ')vhgJ9Tr;;jhFAdy~&vZKkEz(s*disK%!7+%Qw:d!dhtWx!0@kbqv?N2Y&0#P~C"');
define('NONCE_KEY',        'qIS(b)FpIbhh_q5ff$+An80nLawLmerUjFm_QHQ*M9qqt0xxZk#8gev0hM|SOR/h');
define('AUTH_SALT',        'l*aytA?wXa4U%YPdDLjRKUl:ac*W)NrMH!L$1~Dp&z72hXrVTmAQ6c!P:wjBJ^Fl');
define('SECURE_AUTH_SALT', 'lIr8(8`bPtvA5Wz90xB^DzF8u*C+4Rq500U1:|j2o^TXG0FnSl!wAh&HMJG6&#d6');
define('LOGGED_IN_SALT',   'i3V7)%Q*uS7gTSRB3!Zxax8Ah+O91l_7?unH;mC8YVFIc|yf@i83j5QodU`adbvn');
define('NONCE_SALT',       '*^~CwVl(h/SCWI8Ccor^qyOC4Xo*kkRdJ&5rbv+GS_"yBNz_1BUiB*q#HC^$/ynH');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_74w8h4_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');  