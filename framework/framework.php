<?php

if (!defined('FRAMEWORKPATH')) {
    define('FRAMEWORKPATH', TEMPLATEPATH . '/framework'); 
}
if (!defined('FRAMEWORKURL')) {
    define('FRAMEWORKURL', get_template_directory_uri() . '/framework');
}
if (!defined('TT_SHORTNAME')) {
    define('TT_SHORTNAME', 'themeton');
}

class wp_tt_framework {

    var $admin = null;

    function init() {
        $this->admin();
        require_once FRAMEWORKPATH . '/includes/admin/category_option.php';
        require_once FRAMEWORKPATH . '/includes/functions.php';
        require_once FRAMEWORKPATH . '/widgets/recent_posts_widget.php';
        require_once FRAMEWORKPATH . '/widgets/flickr_widget.php';
        require_once FRAMEWORKPATH . '/widgets/social_links_widget.php';
    }

    function admin() {
        if (is_admin()) {
            require_once FRAMEWORKPATH . '/admin/admin.php';
            $this->admin = new wp_tt_admin();
            $this->admin->init();
            $this->admin->myFramework = &$this;
        }
    }

}

?>
