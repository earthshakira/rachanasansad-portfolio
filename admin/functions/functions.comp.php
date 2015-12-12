<?php

add_action('init', 'comp_options');

if (!function_exists('comp_options')) {

    function comp_options() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */

        global $comp_options;
        $comp_options = array();

        /* ----------------------------- Title and Description SETTINGS ----------------------------------- */

        $comp_options[] = array("name" => "Composer",
            "type" => "heading");
        $comp_options[] = array("name" => "Activate Theme Elements",
            "desc" => "If On, Enable Theme Elements. If you want to use another shortcode plugin for your site you should turn it off.",
            "id" => "enable_composer",
            "std" => 1,
			"folds" => 1,
            "show" => 'check_composer',
            "type" => "checkbox");
        $comp_options[] = array("name" => "Theme Elements' range",
            "desc" => "Select for which content types Theme Element (visual shortcode) should be available during post creation/editing.",
            "id" => "check_composer",
            "std" => array('post', 'page'),
			"fold" => "enable_composer",
            "type" => "multicheck",
            "options" => array("post"=>"Post", "page"=>"Page"));
    }
}
?>
