<?php
/**
 * WPBakery Visual Composer Here includes useful files for plugin
 *
 * @package WPBakeryVisualComposer
 *
 */

$lib_dir = $composer_settings['COMPOSER_LIB'];
$shortcodes_dir = $composer_settings['SHORTCODES_LIB'];

require_once( $lib_dir . 'abstract.php' );
require_once( $lib_dir . 'helpers.php' );
require_once( $lib_dir . 'mapper.php' );
require_once( $lib_dir . 'shortcodes.php' );
require_once( $lib_dir . 'composer.php' );

/* Add shortcodes classes */
require_once( $shortcodes_dir . 'default.php' );
require_once( $shortcodes_dir . 'column.php' );
require_once( $shortcodes_dir . 'accordion.php' );
require_once( $shortcodes_dir . 'tabs.php' );
require_once( $shortcodes_dir . 'tour.php' );
require_once( $shortcodes_dir . 'buttons.php' );
require_once( $shortcodes_dir . 'images_galleries.php' );
require_once( $shortcodes_dir . 'media.php' );
require_once( $shortcodes_dir . 'posts_slider.php' );
require_once( $shortcodes_dir . 'raw_content.php' );
require_once( $shortcodes_dir . 'social.php' );
require_once( $shortcodes_dir . 'teaser_grid.php' );
require_once( $shortcodes_dir . 'theme_elements.php' );

// require_once( $shortcodes_dir . 'example.php' );


require_once( $lib_dir . 'media_tab.php' );

require_once( $lib_dir . 'layouts.php' );