<?php
/*
Title		: SMOF
Description	: Slightly Modified Options Framework
Version		: 1.4.0
Author		: Syamil MJ
Author URI	: http://aquagraphite.com
License		: WTFPL - http://sam.zoy.org/wtfpl/
Credits		: Thematic Options Panel - http://wptheming.com/2010/11/thematic-options-panel-v2/
		 	  KIA Thematic Options Panel - https://github.com/helgatheviking/thematic-options-KIA
		 	  Woo Themes - http://woothemes.com/
		 	  Option Tree - http://wordpress.org/extend/plugins/option-tree/
*/

/**
 * Definitions
 *
 * @since 1.4.0
 */
$theme_version = '';
	    
if( function_exists( 'wp_get_theme' ) ) {
	if( is_child_theme() ) {
		$temp_obj = wp_get_theme();
		$theme_obj = wp_get_theme( $temp_obj->get('Template') );
	} else {
		$theme_obj = wp_get_theme();    
	}

	$theme_version = $theme_obj->get('Version');
	$theme_name = $theme_obj->get('Name');
	$theme_uri = $theme_obj->get('ThemeURI');
	$author_uri = $theme_obj->get('AuthorURI');
} else {
	$theme_data = get_theme_data( get_template_directory().'/style.css' );
	$theme_version = $theme_data['Version'];
	$theme_name = $theme_data['Name'];
	$theme_uri = $theme_data['ThemeURI'];
	$author_uri = $theme_data['AuthorURI'];
}


define( 'SMOF_VERSION', '1.4.0' );
define( 'ADMIN_PATH', TEMPLATEPATH . '/admin/' );
define( 'ADMIN_DIR', get_template_directory_uri() . '/admin/' );
define( 'LAYOUT_PATH', ADMIN_PATH . '/layouts/' );
/* Theme version, uri, and the author uri are not completely necessary, but may be helpful in adding functionality */
define( 'THEMEVERSION', $theme_version );
define( 'THEMEURI', $theme_uri );
define( 'THEMEAUTHORURI', $author_uri );

define( 'OPTIONS', strtolower(THEMENAME).'_options' );
define( 'BACKUPS',strtolower(THEMENAME).'_backups' );

define( 'SEO_OPTIONS', strtolower(THEMENAME).'_seo' );
define( 'COMP_OPTIONS', strtolower(THEMENAME).'_comp' );

/**
 * Required action filters
 *
 * @uses add_action()
 *
 * @since 1.0.0
 */
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) add_action('admin_head','of_option_setup');
add_action('admin_head', 'optionsframework_admin_message');
add_action('admin_init','optionsframework_admin_init');
add_action('admin_menu', 'optionsframework_add_admin');
add_action( 'init', 'optionsframework_mlu_init');

/**
 * Required Files
 *
 * @since 1.0.0
 */ 
  
function generate_options_css($newdata) {

    $data = $newdata;
    $css_dir = get_stylesheet_directory() . '/css/'; // Shorten code, save 1 call
    ob_start(); // Capture all output (output buffering)

    require($css_dir . 'styles.php'); // Generate CSS

    $css = ob_get_clean(); // Get generated CSS (output buffering)
    file_put_contents($css_dir . 'options.css', $css, LOCK_EX); // Save it
}


require_once ( ADMIN_PATH . 'functions/functions.load.php' );
require_once ( ADMIN_PATH . 'classes/class.options_machine.php' );

/**
 * AJAX Saving Options
 *
 * @since 1.0.0
 */
add_action('wp_ajax_seo_of_ajax_post_action', 'seo_of_ajax_callback');
add_action('wp_ajax_comp_of_ajax_post_action', 'comp_of_ajax_callback');
add_action('wp_ajax_of_ajax_post_action', 'of_ajax_callback');

?>