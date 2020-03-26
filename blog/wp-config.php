<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'casheiro_com_br');

/** MySQL database username */
define('DB_USER', 'casheiro_com_br');

/** MySQL database password */
define('DB_PASSWORD', 'zNd7yMan');

/** MySQL hostname */
define('DB_HOST', 'casheiro.com.br.mysql.service.one.com');

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
define('AUTH_KEY',         'KB0cNsyZJt7V-E2YM5O028XKM-9UOnMfXjCWErI04EE=');
define('SECURE_AUTH_KEY',  'Qod4fs11rEhKrqamlc6bL74teM4AL4co_Nadv2BqxGc=');
define('LOGGED_IN_KEY',    'PxJyV8ybVxAiXcZ3iRMdN-ODBwI0nLOyQLnePAWmEL0=');
define('NONCE_KEY',        'lkLTzzrqwD1O-qP40dX5zFnmTC88fAFc7790ptKPuFw=');
define('AUTH_SALT',        'ITT8YjJfh7aoldC-FUqyC1x4pjxhqr40v8Lhp-vqwaY=');
define('SECURE_AUTH_SALT', 'OfpY6zSi0XRLMA1DC41AYzJIbw3YpthzwmQaTNM3A58=');
define('LOGGED_IN_SALT',   'S06-RS4fwwShJw1ytwerhMrf2TGA_8hz6RalyvXGIIk=');
define('NONCE_SALT',       '0UFev1B3gyR1hHu3MFDxIbyF2u1bQZ4zSXz__dcVyXc=');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'blog_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'en_GB');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/** 
 * Prevent file editing from WP admin.
 * Just set to false if you want to edit templates and plugins from WP admin.  
 */
define('DISALLOW_FILE_EDIT', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
