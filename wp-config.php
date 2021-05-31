<?php

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL



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

define('DB_NAME', "ktransform_db");


/** MySQL database username */

define('DB_USER', "ktransform_user");


/** MySQL database password */

define('DB_PASSWORD', "FGDToehnSi");


/** MySQL hostname */

define('DB_HOST', "localhost");


/** Database Charset to use in creating database tables. */

define('DB_CHARSET', 'utf8mb4');


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

define('AUTH_KEY',         '>T^_3D~bE+K[[.H&b4d}4%oOW;%djzRne*,B1).uyG,Aa&GckcRio1%l4NZ!7yq{');

define('SECURE_AUTH_KEY',  'g/hlnpkdA2pXB/+>ga?jPV<]4xH^f42c+Z}BYsNQ0roYi^HHfP((9<u=~)Ly]V#;');

define('LOGGED_IN_KEY',    'sl..?GuKGkwA>m[>U|TD)uByMCppH|q./z|5$sbUws=uW_,uAuMv%OKOQJu|!``l');

define('NONCE_KEY',        'x%z9mNe0#cKh4k9]+.&NJNB5s<}BS?w;b~f}.WyR@_|f=L#z2@_a*0,54;6u-=hq');

define('AUTH_SALT',        '@N6S{qO,]6b__X^&D(/K-9RztTyY{]Q1/&ejl1`1Sdmh>TASf%Pwb52_>NgH:b/N');

define('SECURE_AUTH_SALT', 'PRxw4/4]@kGqRUIW!U1*7A(&O}^1~Agm<k:4+A_;}zN6GODP(+Y:IbR%x7$SueGm');

define('LOGGED_IN_SALT',   '%PA=TcEcn[4[oHElZSd~2gZ_FcC1U(l.Qksptedh/I&?k3vi]<D!<7<mMpti(40$');

define('NONCE_SALT',       ']K`8!y0;<fjZu536]0D;[XG}rzA+EfHz:NoVaD!}L@P/vdWM- 3{/{W|3~LDXQ,E');


/**#@-*/


/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = 'wp_';


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

