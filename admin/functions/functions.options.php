<?php

add_action('init', 'of_options');

if (!function_exists('of_options')) {

    function of_options() {

//Access the WordPress Categories via an Array
        $of_categories = array();
        $of_categories_obj = get_categories('hide_empty=0');
        foreach ($of_categories_obj as $of_cat) {
            $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;
        }
        //$categories_tmp = array_unshift($of_categories, "Select a category:");

//Access 0-100 numbers
        $days = array();
        for ($i = 1; $i <= 100; $i++) {
            $days[$i] = $i;
        }

//Access the WordPress Pages via an Array
        $of_all_pages = $of_pages = array('no' => 'Select a page:');
        $of_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $of_i = 0;
        $def_frontend = $def_profile = $def_favorite = $def_draft = 'no';
        foreach ($of_pages_obj as $of_page) {
            if (get_post_meta($of_page->ID, '_wp_page_template', true) == 'default') {
                $of_pages[$of_page->ID] = $of_page->post_title;
                switch ($of_i) {
                    case 0: {
                            $def_favorite = $def_profile = $def_frontend = $def_draft = $of_page->ID;
                            break;
                        }
                    case 1: {
                            $def_favorite = $def_profile = $def_draft = $of_page->ID;
                            break;
                        }
                    case 2: {
                            $def_favorite = $def_draft = $of_page->ID;
                            break;
                        }
                    case 3: {
                            $def_draft = $of_page->ID;
                            break;
                    }
                }
                $of_i++;
            }
            $of_all_pages[$of_page->ID] = $of_page->post_title;
        }
        //$of_pages_tmp = array_unshift($of_pages, "Select a page:");
//Testing 
        $of_options_select = array("one", "two", "three", "four", "five");
        $of_options_radio = array("light" => "Light", "dark" => "Dark");
        $of_options_homepage_blocks = array(
            "disabled" => array(
                "placebo" => "placebo", //REQUIRED!
                "block_one" => "Block One",
                "block_two" => "Block Two",
                "block_three" => "Block Three",
            ),
            "enabled" => array(
                "placebo" => "placebo", //REQUIRED!
                "block_four" => "Block Four",
            ),
        );


//Stylesheets Reader
        $alt_stylesheet_path = LAYOUT_PATH;
        $alt_stylesheets = array();

        if (is_dir($alt_stylesheet_path)) {
            if ($alt_stylesheet_dir = opendir($alt_stylesheet_path)) {
                while (($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false) {
                    if (stristr($alt_stylesheet_file, ".css") !== false) {
                        $alt_stylesheets[] = $alt_stylesheet_file;
                    }
                }
            }
        }

//Background Pattern Reader
        global $pattern_images;
        $pattern_images_path = get_template_directory() . '/images/bg/'; // change this to where you store your bg images
        $pattern_images_url = get_template_directory_uri() . '/images/bg/'; // change this to where you store your bg images
        $pattern_images = array();
        if (is_dir($pattern_images_path)) {
            if ($pattern_images_dir = opendir($pattern_images_path)) {
                $i = 0;
                while (($pattern_images_file = readdir($pattern_images_dir)) !== false) {
                    if (stristr($pattern_images_file, ".png") !== false || stristr($pattern_images_file, ".jpg") !== false) {
                        $i++;
                        $pattern_images[$pattern_images_file] = $pattern_images_url . $pattern_images_file;
                    }
                }
            }
        }

//Social Position Images Reader
        $social_images_path = get_template_directory() . '/framework/images/social-option/'; // change this to where you store your bg images
        $social_images_url = get_template_directory_uri() . '/framework/images/social-option/'; // change this to where you store your bg images
        $social_images = array();
        if (is_dir($social_images_path)) {
            if ($social_images_dir = opendir($social_images_path)) {
                while (($social_images_file = readdir($social_images_dir)) !== false) {
                    if (stristr($social_images_file, ".png") !== false || stristr($social_images_file, ".jpg") !== false) {
                        $social_images[] = $social_images_url . $social_images_file;
                    }
                }
            }
        }

//Post skin Images Reader
        $skin_images_path = get_template_directory() . '/images/skin/'; // change this to where you store your bg images
        $skin_images_url = get_template_directory_uri() . '/images/skin/'; // change this to where you store your bg images
        $skin_images = array();
        if (is_dir($skin_images_path)) {
            if ($skin_images_dir = opendir($skin_images_path)) {
                $i = 0;
                while (($skin_images_file = readdir($skin_images_dir)) !== false) {
                    if (stristr($skin_images_file, ".png") !== false || stristr($skin_images_file, ".jpg") !== false) {
                        $i++;
                        $skin_images[$skin_images_file] = $skin_images_url . $skin_images_file;
                    }
                }
            }
        }

//Post Size Images Reader
        $postsize_images_path = get_template_directory() . '/framework/images/post-size/'; // change this to where you store your bg images
        $postsize_images_url = get_template_directory_uri() . '/framework/images/post-size/'; // change this to where you store your bg images
        $postsize_images = array();
        if (is_dir($postsize_images_path)) {
            if ($postsize_images_dir = opendir($postsize_images_path)) {
                $i = 0;
                while (($postsize_images_file = readdir($postsize_images_dir)) !== false) {
                    if (stristr($postsize_images_file, ".png") !== false || stristr($postsize_images_file, ".jpg") !== false) {
                        $i++;
                        $postsize_images[$postsize_images_file] = $postsize_images_url . $postsize_images_file;
                    }
                }
            }
        }

        /* ----------------------------------------------------------------------------------- */
        /* TO DO: Add options/functions that use these */
        /* ----------------------------------------------------------------------------------- */

//More Options
        $uploads_arr = wp_upload_dir();
        $all_uploads_path = $uploads_arr['path'];
        $all_uploads = get_option('of_uploads');
        $other_entries = array("Select a number:", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19");
        $body_repeat = array("no-repeat", "repeat-x", "repeat-y", "repeat");
        $body_pos = array("top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right");

// Image Alignment radio box
        $of_options_thumb_align = array("alignleft" => "Left", "alignright" => "Right", "aligncenter" => "Center");

// Image Links to Options
        $of_options_image_link_to = array("image" => "The Image", "post" => "The Post");


        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */

// Set the Options Array
        global $of_options;
        $of_options = array();

        /* ----------------------------- GENERAL SETTINGS ----------------------------------- */

        $of_options[] = array("name" => "General Settings",
            "type" => "heading");

        $url = ADMIN_DIR . '../framework/images/layouts/';

        $of_options[] = array("name" => "Logo image",
            "desc" => "Upload your logo image here. If you didn't add image, your logo comes from <tt>Site title</tt> text",
            "id" => "logo_image",
            "std" => "",
            "type" => "upload");

        $of_options[] = array("name" => "Favicon image",
            "desc" => "Upload a 16px x 16px image that will represent your website's favicon. To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc' target='_blank'>www.favicon.cc</a>)",
            "id" => "favicon",
            "std" => "",
            "type" => "upload");
        $of_options[] = array("name" => "Set Header fixed",
            "desc" => "If you turn this option ON, Header section goes fixed on top.",
            "id" => "fixed_header",
            "std" => 1,
            "type" => "checkbox");
        $of_options[] = array("name" => "Pagination type",
            "desc" => "Select pagination type.",
            "id" => "pagination_type",
            "std" => "Date",
            "options" => Array("auto_infinite"=>"Auto infinite scroll","manual_infinite"=>"Manual infinite scroll","pagination"=>"Classic pagination"),
            "type" => "select");
        $of_options[] = array("name" => "Next, Prev buttons on single",
            "desc" => "If you turn this option OFF, your Next, Prev buttons will hide.",
            "id" => "hide_next_prev_button",
            "std" => 1,
            "type" => "checkbox");
        $of_options[] = array("name" => "Blog Meta show?",
            "desc" => "If you turn this option OFF your comment count, like count will hide in Blog.",
            "id" => "comment_like_show",
            "std" => 1,
            "type" => "checkbox");
        $of_options[] = array("name" => "Author Section show in post?",
            "desc" => "If you turn this option OFF, your About author will hide in Blog, Category and Single post.",
            "id" => "about_author_show",
            "std" => 1,
            "type" => "checkbox");
        $of_options[] = array("name" => "Author Section show in page?",
            "desc" => "If you turn this option OFF, your About author will hide in Page.",
            "id" => "about_author_show_page",
            "std" => 0,
            "type" => "checkbox");
        $of_options[] = array("name" => "Posts size",
            "desc" => "Please select default post size of your site.",
            "id" => "item_size",
            "std" => $postsize_images_url . "02.png",
            "type" => "tiles",
            "options" => $postsize_images);
        $of_options[] = array("name" => "Full width?",
            "desc" => "",
            "id" => "full_width",
            "std" => "fixed",
	    "options" => array('fixed' => 'Fixed width','only_grid' =>  'Only blog section','full' =>  'Blog + Header'),
            "type" => "select");
        $of_options[] = array("name" => "Sidebar width option",
            "desc" => "If you need to increase width for your sidebar area, you should select number 4.",
            "id" => "sidebar_width",
			"std" => 'grid3',
            "options" => array('grid3' => 'Grid 3, 238px', 'grid4' => 'Grid 4, 338px'),
            "type" => "select");
        $of_options[] = array("name" => "Teaser title for Index page",
            "desc" => "Please add your title text here.",
            "id" => "teaser_title",
            "std" => "",
            "type" => "text");
		$of_options[] = array("name" => "Text for Index page",
            "desc" => "Please add your teaser text here. Also you can add here html or shortcode.",
            "id" => "teaser_text",
            "std" => "",
            "type" => "textarea");
        $of_options[] = array("name" => "Tracking code",
            "desc" => "Please include your Google analytics or other tracking code here. It is official site of the Google <a href='http://www.google.com/analytics/' target='_blank'>analytics</a>.",
            "id" => "google_analytics",
            "std" => "",
            "type" => "textarea");
        $of_options[] = array("name" => "Breadcrumb?",
            "desc" => "If you want to show Breadcrumb instead of search box next of menu section, you should turn this option ON.",
            "id" => "show_breadcrumb",
            "std" => 0,
            "type" => "checkbox");


        /* ----------------------------- THEME PUBLISH ----------------------------------- */

        $of_options[] = array("name" => "Theme Publish",
            "type" => "heading");

        $of_options[] = array("name" => "User Login Menu in Header",
            "desc" => "If you turn this option ON, User login menu section will be displayed at top of site.",
            "id" => "header_user_nav",
            "std" => 1,
            "type" => "checkbox");
			
		$of_options[] = array("name" => "Post format section in Front editor",
            "desc" => "If you turn this option ON, Post format section will be show in front post editor page.",
            "id" => "front_postformat",
            "std" => 1,
            "type" => "checkbox");

        $of_options[] = array("name" => "Default post status",
            "desc" => "The post status when people add posts to your site. Draft of Pending status is much proper for site ovners who want to control publishing contents to their site.",
            "id" => "default_posts_status",
            "std" => "",
            "options" => array("draft" => "Draft", "publish" => "Publish", "pending" => "Pending"),
            "type" => "select");

        $of_options[] = array("name" => "The editor page",
            "desc" => "The page which adding new posts from front end. ",
            "id" => "frontend_editor_page",
            "std" => $def_frontend,
            "options" => $of_pages,
            "type" => "select");

        $of_options[] = array("name" => "Profile settings page",
            "desc" => "The page shows user information and post list current author",
            "id" => "profile_options_page",
            "std" => $def_profile,
            "options" => $of_pages,
            "type" => "select");
			
		$of_options[] = array("name" => "Draft posts page",
            "desc" => "The page shows draft posts list of the current user.",
            "id" => "draft_page",
            "std" => $def_draft,
            "options" => $of_pages,
            "type" => "select");

        $of_options[] = array("name" => "Favorite posts page",
            "desc" => "The page shows favorite posts list of the current user.",
            "id" => "favorite_page",
            "std" => $def_favorite,
            "options" => $of_pages,
            "type" => "select");

        $of_options[] = array("name" => "Logout redirect page",
            "desc" => "The page goes to direct when you logged out the site.",
            "id" => "logout_redirect_page",
            "std" => "",
            "options" => $of_all_pages,
            "type" => "select");
			
        $of_options[] = array("name" => "Exclude categories from Frontend",
            "desc" => "Please select categories those didn't allow frontend publishing posts.",
            "id" => "exclude_categories",
            "std" => "",
            "options" => $of_categories,
            "type" => "multicheck");

        $of_options[] = array("name" => "Facebook connect",
            "desc" => "Already registered? Find your keys in your <a href='http://www.facebook.com/developers/apps.php' target='_blank'>Facebook Application List</a><br>Need to register? Visit the <a href='http://www.facebook.com/developers/createapp.php' target='_blank'>Facebook Application Setup page</a><br>Get the API information from the <a href='http://www.facebook.com/developers/apps.php' target='_blank'>Facebook Application List</a><br>Select the application you created, then copy and paste the API key & Application Secret from there.",
            "id" => "fb_connect",
            "std" => 0,
            "folds" => 1,
            //"show" => "fb_app_id,fb_app_secret",
            "type" => "checkbox");

        $of_options[] = array("name" => "Facebook App ID/API Key",
            "desc" => "",
            "id" => "fb_app_id",
            "fold" => "fb_connect",
            "std" => "",
            "type" => "text");

        $of_options[] = array("name" => "Facebook App Secret",
            "desc" => "",
            "id" => "fb_app_secret",
            "fold" => "fb_connect",
            "std" => "",
            "type" => "text");

        $of_options[] = array("name" => "Twitter connect",
            "desc" => "You need to create new app on Dev.twitter.com click <a href='https://dev.twitter.com/apps'>here</a> then take Consumer Key, Consumer Secret insert our theme section. Callback URL : http://yoursite.com/?tt_tw_callback=true",
            "id" => "tw_connect",
            "std" => 0,
            "folds" => 1,
            //"show" => "tw_consumer_key,tw_consumer_secret",
            "type" => "checkbox");

        $of_options[] = array("name" => "Twitter Consumer Key",
            "desc" => "",
            "id" => "tw_consumer_key",
            "fold" => "tw_connect",
            "std" => "",
            "type" => "text");

        $of_options[] = array("name" => "Twitter Consumer Secret",
            "desc" => "",
            "id" => "tw_consumer_secret",
            "fold" => "tw_connect",
            "std" => "",
            "type" => "text");

        $of_options[] = array("name" => "Auto Post Delete Option (Cron Like)",
            "desc" => "If you turn this option ON, automatic post delete works depends on your chosen days. Please be careful.",
            "id" => "auto_delete",
            "std" => 0,
            "folds" => 1,
            //"show" => "auto_delete_day",
            "type" => "checkbox");

        $of_options[] = array("name" => "Choose expire day count",
            "desc" => "If your posts not modifying until your chosen days. Inserted posts are permanently deleted. Please choose carefully!",
            "id" => "auto_delete_day",
            "std" => "100",
            "fold" => "auto_delete",
            "options" => $days,
            "type" => "select");

        /* ----------------------------- ADDITIONAL OPTIONS ----------------------------------- */

        $of_options[] = array("name" => "Additional Options",
            "type" => "heading");

        $of_options[] = array("name" => "Post order type",
            "desc" => "Control post order on category, archive, search result pages (default pages). Note: Blog page has individual option in their page options section.",
            "id" => "order_type",
            "std" => "Date",
            "options" => Array('Date', 'Date ASC', 'Title', 'Title ASC', 'Random'),
            "type" => "select");
			
        $of_options[] = array("name" => "Featured section",
            "desc" => "If turn it ON, it will be show featured section on single.",
            "id" => "image_hide",
            "std" => 1,
            "type" => "checkbox");

        $of_options[] = array("name" => "Allow post comment",
            "desc" => "If turn it OFF, it will be close comment function from all the posts.",
            "id" => "post_comment",
            "std" => 1,
            "type" => "checkbox");

        $of_options[] = array("name" => "Allow page comment",
            "desc" => "If turn it OFF, it will be close comment function from all the pages.",
            "id" => "page_comment",
            "std" => 1,
            "type" => "checkbox");

        $of_options[] = array("name" => "Use facebook comment?",
            "desc" => "If turn it ON, site comment will show by facebook comments.",
            "id" => "facebook_comment",
            "std" => 0,
            "folds" => 1,
            "type" => "checkbox");

        $of_options[] = array("name" => "Facebook App ID",
            "desc" => "Please include your facebook App ID. You can get your appid from <a href='http://developers.facebook.com/docs/' target='_blank'>here</a>.",
            "id" => "facebook_app_id",
            "std" => "",
            "fold" => "facebook_comment",
            "type" => "text");

        $of_options[] = array("name" => "Comments per page",
            "desc" => "Please select comment count pagination of facebook comments.",
            "id" => "comment_perpage",
            "std" => "10",
            "fold" => "facebook_comment",
            "type" => "text");
						
        $of_options[] = array("name" => "Stop Nicescroll bar?",
            "desc" => "If you don't want to use regular scroll bar for your site, you should turn this ON.",
            "id" => "stop_nice_scroll",
            "std" => 0,
            "type" => "checkbox");

        /* ----------------------------- FONT OPTIONS ----------------------------------- */

        $of_options[] = array("name" => "Font Options",
            "type" => "heading");

        $of_options[] = array("name" => "General font",
            "id" => "general_font",
            "std" => array('size' => '12px', 'height' => '18px', 'face' => "Oswald"),
            "type" => "typography");

        $of_options[] = array("name" => "Menu font",
            "id" => "menu_font",
            "std" => array('size' => '14px', 'height' => '60px'),
            "type" => "typography");

        $of_options[] = array("name" => "Sub menu font",
            "id" => "submenu_font",
            "std" => array('size' => '12px', 'height' => '18px'),
            "type" => "typography");
			
        $of_options[] = array("name" => "Filter menu size",
            "id" => "filter_font",
            "std" => array('size' => '10px', 'height' => '18px'),
            "type" => "typography");
			
        $of_options[] = array("name" => "Post title in Blog",
            "id" => "blog_title",
            "std" => array('size' => '25px', 'height' => '19px'),
            "type" => "typography");

        $of_options[] = array("name" => "Single post title",
            "id" => "single_title",
            "std" => array('size' => '25px', 'height' => '19px'),
            "type" => "typography");

        $of_options[] = array("name" => "Sidebar title",
            "id" => "sidebar_title",
            "std" => array('size' => '12px', 'height' => '18px'),
            "type" => "typography");

        $of_options[] = array("name" => "Footer text",
            "id" => "footer_text",
            "std" => array('size' => '9px', 'height' => '15px'),
            "type" => "typography");

        /* ----------------------------- SKIN OPTIONS ----------------------------------- */

        $of_options[] = array("name" => "Skin Options",
            "type" => "heading");

        $of_options[] = array("name" => "Link color",
            "desc" => "Choose your color.",
            "id" => "link_color",
            "std" => "#0088cc",
            "type" => "color"
        );
        
        $of_options[] = array("name" => "Link hover color",
            "desc" => "Choose your color.",
            "id" => "link_hover_color",
            "std" => "#005580",
            "type" => "color"
        );

        $of_options[] = array("name" => "Active custom background image",
            "desc" => "If turn it ON, show your image for background.",
            "id" => "custom_bg_enable",
            "std" => 0,
            "folds" => 1,
            //"hide" => "bg_pattern,bg_color",
            "type" => "checkbox");

        $of_options[] = array("name" => "Custom background image",
            "desc" => "Upload a background image for body section of the site. You can get amazing background patterns from <a href='http://subtlepatterns.com' target='_blank'>SubtlePatterns.com</a>. Have a nice customizing =)",
            "id" => "custom_bg",
            "std" => "",
            "fold" => "custom_bg_enable",
            "type" => "media",
        );

        $of_options[] = array("name" => "Properties",
            "desc" => "Properties of custom background image.",
            "id" => "bg_options",
            "std" => array('color' => '#F4F4F4', 'repeat' => 'repeat', 'position' => 'left top', 'attachment' => 'scroll'),
            "fold" => "custom_bg_enable",
            "type" => "background",
        );

        $of_options[] = array("name" => "Background pattern",
            "desc" => "Select pattern.",
            "id" => "bg_pattern",
            "std" => $pattern_images_url . "bg1.png",
            "type" => "tiles",
            "options" => $pattern_images,
        );

        $of_options[] = array("name" => "Background color",
            "desc" => "Choose color.",
            "id" => "bg_color",
            "std" => "#F4F4F4",
            "type" => "color");

        $of_options[] = array("name" => "Header background color",
            "desc" => "Choose color.",
            "id" => "header_bg_color",
            "std" => "#2B2D2F",
            "type" => "color");

        $of_options[] = array("name" => "Footer Background color",
            "desc" => "Choose color.",
            "id" => "footer_bg_color",
            "std" => "#2B2D2F",
            "type" => "color");

        $of_options[] = array("name" => "Custom CSS",
            "desc" => "If you have advanced style changes, you can include here your custom CSS. Your included style will always priviliged than standard style.",
            "id" => "custom_css",
            "std" => "",
            "type" => "textarea");

        /* ----------------------------- CUSTOM SIDEBAR ----------------------------------- */

        $of_options[] = array("name" => "Custom sidebar",
            "type" => "heading");
        global $data;
        $default = isset($data["custom_sidebar"]) ? $data["custom_sidebar"] : "";
        $of_options[] = array("name" => "Custom sidebar",
            "desc" => "You can create unlimited siderbars on your site. You should add some widgets <strong>Appearance=><a href='widgets.php'>Widgets</a></strong> after you have add new sidebar here.",
            "id" => "custom_sidebar",
            "std" => $default,
            "type" => "sidebar");

        /* ----------------------------- FOOTER OPTIONS ----------------------------------- */

        $of_options[] = array("name" => "Footer Options",
            "type" => "heading");

        $of_options[] = array("name" => "Show footer",
            "desc" => "If you turn this option ON, footer area is appear.",
            "id" => "show_footer",
            "std" => 0,
            "folds" => 1,
            //"show" => "footer_layout",
            "type" => "checkbox");

        $of_options[] = array("name" => "Footer layout style",
            "desc" => "Please choose footer layout style. After you have chose it, you should go to the <strong>Appearance=><a href='widgets.php'>Widgets</a></strong> and add your widgets to generated sidebars.",
            "id" => "footer_layout",
            "std" => "4",
            "type" => "images",
            "fold" => "show_footer",
            "options" => array(
                '1' => $url . '1.png',
                '2' => $url . '2.png',
                '4' => $url . '4.png',
                '7' => $url . '7.png',
                '70' => $url . '70.png')
        );

        $of_options[] = array("name" => "Footer Text",
            "desc" => "Please insert your copyright text or footer element here. Also you can add here simple html tags too.",
            "id" => "copyrighttext",
            "std" => 'Copyright 2012. Powered by <a href="http://www.wordpress.org">WordPress</a><br> <span><a href="#"><strong>' . TT_THEMENAME . '</strong> theme</a> by <a href="http://www.themeton.com"><strong>ThemeTon</strong></a></span>',
            "type" => "textarea"
        );

        /* ----------------------------- SOCIAL LINKS ----------------------------------- */
        $of_options[] = array("name" => "Social shares",
            "type" => "heading");

        $of_options[] = array("name" => "Position Options",
            "desc" => "",
            "id" => "social_position",
            "std" => $social_images_url . "01.png",
            "type" => "tiles",
            "options" => $social_images);

        $of_options[] = array("name" => "Social shares",
            "desc" => "Activation of Social shares.",
            "id" => "social_media",
            "std" => "",
            "folds" => 1,
            //"show" => "sharethis_key,social_facebook,social_twitter,social_googlePlus,social_linkedin,social_pinterest,social_email",
            "type" => "checkbox");

        $of_options[] = array("name" => "Sharethis key",
            "desc" => "You can get your publisher key <a href='http://sharethis.com/' target='_blank'>here</a>. If you need more information please<a href='http://www.vodeblog.com/2010/09/how-to-get-your-sharethis-publisher-key-in-wordpress/' target='_blank'> read it</a>.",
            "id" => "sharethis_key",
            "std" => "",
            "fold" => "social_media",
            "type" => "text");

        $of_options[] = array("name" => "Facebook share",
            "id" => "social_facebook",
            "std" => 1,
            "fold" => "social_media",
            "type" => "checkbox");
        $of_options[] = array("name" => "Twitter share",
            "id" => "social_twitter",
            "std" => 1,
            "fold" => "social_media",
            "type" => "checkbox");
        $of_options[] = array("name" => "GooglePlus share",
            "id" => "social_googlePlus",
            "std" => 0,
            "fold" => "social_media",
            "type" => "checkbox");
        $of_options[] = array("name" => "Linkedin share",
            "id" => "social_linkedin",
            "std" => 0,
            "fold" => "social_media",
            "type" => "checkbox");
        $of_options[] = array("name" => "Pinterest share",
            "id" => "social_pinterest",
            "std" => 1,
            "fold" => "social_media",
            "type" => "checkbox");
        $of_options[] = array("name" => "Email this button",
            "id" => "social_email",
            "std" => 0,
            "fold" => "social_media",
            "type" => "checkbox");
        $of_options[] = array("name" => "Stumbleupon button",
            "id" => "social_stumbleupon",
            "std" => 0,
            "fold" => "social_media",
            "type" => "checkbox");

        // Backup Options
        $of_options[] = array("name" => "Backup Options",
            "type" => "heading");

        $of_options[] = array("name" => "Backup and Restore Options",
            "id" => "of_backup",
            "std" => "",
            "type" => "backup",
            "desc" => 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
        );

        $of_options[] = array("name" => "Transfer Theme Options Data",
            "id" => "of_transfer",
            "std" => "",
            "type" => "transfer",
            "desc" => 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".',
        );

        // Adsense Options
        $of_options[] = array("name" => "Adsense Options",
            "type" => "heading");
        
        $of_options[] = array("name" => "My ADS",
            "desc" => "You can include multiple adsenses on your site. Please add rich content including html and javascript in adsense field.<br><br>
                <strong>Adsense position</strong>: You can add on this field number value. Then it will show on your blog/category query. If you add here <code>abovecontent</code> or <code>bottomcontent</code> value, it'll be show on single page and relevant position.<br><br>
                <strong>Featured</strong>: If you select featured option, your ads will show larger just like your featured posts.<br><br>
                <strong>Custom width</strong>: If your ads doesn't fit proerly on regular or featured sizes, you should set manually width number. This options always previlage than Featured selection. Add there just number value, do not provide PX metrics.",
            "id" => "adsense",
            "std" => "",
            "type" => "adsense"
        );
    }

}
?>
