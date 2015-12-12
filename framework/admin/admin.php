<?php

class wp_tt_admin {

    var $myFramework;

    function init() {
        require_once FRAMEWORKPATH . '/includes/admin/post_format.php';
        require_once FRAMEWORKPATH . '/includes/metaboxes/slide_meta.php';
        require_once FRAMEWORKPATH . '/includes/metaboxes/default_meta_boxes.php';

        add_action('admin_print_styles', array(&$this, 'adminStyles'), 5);
        add_action('admin_enqueue_scripts', array(&$this, 'adminScripts'), 6);
    }

    function addSlideMeta($options) {
        $post_meta = new TTSlideMetaBox();
        $post_meta->construct($options);
    }

    function addMeta($options) {
        $defaultMeta = new XacDefaultMetabox();
        $defaultMeta->construct($options);
    }

    function adminStyles() {
        wp_enqueue_style('tt-checkbox-iphonestyle', FRAMEWORKURL . '/css/iphone-checkbox.css');
        wp_enqueue_style('theme-colorpicker', FRAMEWORKURL . '/css/colorpicker.css');
        wp_enqueue_style('tt-post-format', FRAMEWORKURL . '/css/post-format.css');
        wp_enqueue_style('tt-admin-style', FRAMEWORKURL . '/css/style.css');        
    }

    function adminScripts($hook) {
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('tt-dynamic-count', FRAMEWORKURL . '/js/admin/slide_image_plugin.js');
        wp_enqueue_script('tt-checkbox-iphonestyle', FRAMEWORKURL . '/js/iphone-style-checkboxes.js', array('jquery'));
        wp_enqueue_script('tt-selectbox-iphonestyle', FRAMEWORKURL . '/js/iphone-style-tri-toggle.js', array('jquery'));
        wp_enqueue_script('tt-dynamic-accordion', FRAMEWORKURL . '/js/admin/dynamic_accordion.js');
        wp_enqueue_script('admin-default', FRAMEWORKURL . '/js/admin/default.js');
        wp_enqueue_script('tt-page-selector', FRAMEWORKURL . '/js/admin/theme_page_selector.js');
        //wp_enqueue_script('theme-colorpicker', FRAMEWORKURL . '/js/colorpicker.js');
    }

}

?>
