<?php
/**
Plugin Name: WPBakery Visual Composer
Plugin URI: http://wpbakery.com/vc
Description: Create content without headaches with simple drag-n-drop interface.
Version: 3.1.0
Author: Michael M - WPBakery.com
Author URI: http://wpbakery.com
License:
 */

// don't load directly

if (!defined('ABSPATH')) die('-1');

/**
 * Current plugin file directory.
 * var string
 */

$dir = dirname(__FILE__);

// {{{ constants

/**
 * Current visual composer version
 */

define('WPB_VC_VERSION', '3.0.2');

/**
 * jQuery UI version
 */

define('WPB_JQUERY_UI_VERSION', '1.8.20');

// }}}

/**
 * Define default settings for visual composer.
 *
 * APP_ROOT - plugin directory.
 * WP_ROOT - Wordpress application root directory.
 * APP_DIR - plugin directory name.
 * CONFIG - configuration directory.
 * ASSETS_DIR  - directory name for assets. Used from urls creating.
 * COMPOSER      => main visual composer directory.
 * COMPOSER_LIB  => libraries for composer.
 * SHORTCODES_LIB  => Shortcodes directory.
 *
 * @var array
 */

$composer_settings = Array(
    'APP_ROOT'      => $dir . '/',
    'WP_ROOT'       => dirname( dirname( dirname( dirname($dir ) ) ) ). '/',
    'APP_DIR'       => basename( $dir ) . '/',
    'CONFIG'        => $dir . '/config/',
    'ASSETS_DIR'    => 'assets/',
    'COMPOSER'      => $dir . '/composer/',
    'COMPOSER_LIB'  => $dir . '/composer/lib/',
    'SHORTCODES_LIB'  => $dir . '/composer/lib/shortcodes/'
);

/*
 * Here we include all useful files
 */

require_once( $composer_settings['COMPOSER'] . 'wp_bakery_visual_composer.php' );

/*
 * Include visual composer builders.
 * class WPBakeryVisualComposerSetupAdmin - for admin panel
 * class WPBakeryVisualComposerSetup - for frontend
 */
require_once( $composer_settings['COMPOSER'] . 'build.php' );

/**
 * Setting file for layouts and shortcodes
 */

require_once( $composer_settings['CONFIG'] . 'map.php' );


/**
 * Initialize plugin depending on admin panel or public layout
 * @var object
 */

$wpVC_setup = is_admin() ? new WPBakeryVisualComposerSetupAdmin() : new WPBakeryVisualComposerSetup();
$wpVC_setup->init($composer_settings);

?>