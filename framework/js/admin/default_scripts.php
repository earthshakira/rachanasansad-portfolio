<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (is_admin ()) {
add_action('admin_init', 'tt_admin_default_scripts_init');
add_action('admin_print_scripts', 'tt_admin_default_scripts');

add_action('admin_init', 'tt_admin_default_scripts_init');
}

function tt_admin_default_scripts_init() {
    wp_register_script('admin-default', FRAMEWORKURL .'/js/admin/default.js');
    wp_register_script('admin-default-options', FRAMEWORKURL .'/js/admin/admin_options.js');
    wp_register_script('admin-font-uploader', FRAMEWORKURL .'/js/admin/font_uploader_plugin.js');
    //wp_register_script('admin-colorpicker', FRAMEWORKURL .'/js/colorpicker.js');
    wp_register_script('tt-slider-script', FRAMEWORKURL .'/js/jquery.slider-min.js');
    wp_register_script('tt-checkbox-iphonestyle', FRAMEWORKURL .'/js/iphone-style-checkboxes.js');
    wp_register_script('tt-dynamic-count', FRAMEWORKURL .'/js/admin/slide_image_plugin.js');
    wp_register_script('tt-dynamic-accordion', FRAMEWORKURL .'/js/admin/dynamic_accordion.js');
    wp_register_script('tt-page-selector', FRAMEWORKURL .'/js/admin/theme_page_selector.js');
    wp_register_style('tt-checkbox-iphonestyle', FRAMEWORKURL .'/css/iphone-checkbox.css');
}

function tt_admin_default_scripts() {
    
}

?>
