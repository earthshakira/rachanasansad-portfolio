<?php
/* ----------------------------------------------------------------------------------- */
// All functions and configuration of the theme
/* ----------------------------------------------------------------------------------- */

define('TT_SHORTNAME', 'pressgrid');
define('TT_THEMENAME', 'PressGrid');
define('THEMENAME', 'PressGrid');

define('FRAMEWORKPATH', TEMPLATEPATH . '/framework');
define('FRAMEWORKURL', get_template_directory_uri() . '/framework');

/* ------------------------------------------ */
/* Options Framework
  /*------------------------------------------ */

require_once (TEMPLATEPATH . '/admin/index.php');


$s_layout = Array(
    array('title' => 'Full', 'value' => '0-1-1', 'image' => FRAMEWORKURL . '/images/layouts/s0.png'),
    array('title' => 'Right sidebar', 'value' => '1-1-1', 'image' => FRAMEWORKURL . '/images/layouts/s12.png'),
);
$social_url = get_template_directory_uri() . '/framework/images/social-option/';
$social_position = Array(
    $social_url . '01.png' => 'bottom',
    $social_url . '02.png' => 'top',
    $social_url . '03.png' => 'left',
    $social_url . '04.png' => 'right',
);
$images_url = get_template_directory_uri() . '/images/skin/';
$post_style = Array(
    $images_url . '01.png' => 'post-default',
    $images_url . '02.png' => 'post-minimal',
    $images_url . '03.png' => 'post-classic-light',
    $images_url . '04.png' => 'post-classic-dark',
);
$size_url = get_template_directory_uri() . '/framework/images/post-size/';
$item_size = Array(
    $size_url . '01.png' => 'small',
    $size_url . '02.png' => 'medium',
    $size_url . '03.png' => 'large',
    $size_url . '04.png' => 'x-large',
);
$footerGrid = Array(
    '1' => '12',
    '2' => '6-6',
    '7' => '6-3-3',
    '70' => '3-3-6',
    '4' => '3-3-3-3'
);

global $data;
$sides = isset($data['custom_sidebar']) ? $data['custom_sidebar'] : ""; //get the slides array
$sidebar = array('Default sidebar');
if ($sides) {
    foreach ($sides as $side) {
        if ($side['title'] != "") {
            $sidebar = array_merge($sidebar, (array) $side['title']);
        }
    }
}

require_once FRAMEWORKPATH . '/framework.php';
require_once FRAMEWORKPATH . '/' . TT_SHORTNAME . '.php';
require_once TEMPLATEPATH . '/social-connect/social-connect.php';
require_once( ADMIN_PATH . 'functions/seo.php' );
require_once ( ADMIN_PATH . '/composer/js_composer.php');


if (!function_exists('get_post_image')) :

    function get_post_image() {
        global $post;
        $first_img = '';
        if (has_post_thumbnail($post->ID)) {
			$imgsize = 'medium';
			if(is_single())
				$imgsize='full';
			else {
				$isfeatured= get_post_meta($post->ID, 'themeton_additional_options', true);
				if(isset($isfeatured) && is_array($isfeatured) && isset($isfeatured['is_featured_post']))
					$imgsize='large';
			}

            $post_image_tumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $imgsize);

            return $post_image_tumb[0];
        }
        return $first_img;
    }

endif;

if (!function_exists('get_post_image_for_nextprev')) :

    function get_post_image_for_nextprev() {
        global $post;
        $first_img = '';
        if (has_post_thumbnail($post->ID)) {
            $post_image_tumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
            return $post_image_tumb[0];
        }
        return $first_img;
    }

endif;

if (!function_exists('get_post_first_image')) :

    function get_post_first_image() {
        global $post;
        $first_img = '';
        if ($post->post_content) {
            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
            $first_img = isset($matches[1][0]) ? $matches[1][0] : '';
        }
        return $first_img;
    }

endif;

if (!function_exists('get_post_content_image')) :

    function get_post_content_image() {
        global $post;
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        $first_img = isset($matches[1][0]) ? $matches[1][0] : '';
        return $first_img;
    }

endif;

register_nav_menus(array(
    'primary-menu' => __('Primary Navigation', 'themeton'),
    'footer-menu' => __('Footer Navigation', 'themeton')
));
add_action('widgets_init', 'widgets_init');
add_theme_support('post-thumbnails');
add_theme_support('post-formats', array('video', 'audio', 'quote', 'status', 'link'));
add_image_size('theme-thumb', 520, 497, true);
add_filter('widget_text', 'do_shortcode');
add_filter('wp_get_attachment_link', 'gallery_prettyPhoto');

if (!function_exists('gallery_prettyPhoto')) :

    function gallery_prettyPhoto($content) {
        // add checks if you want to add prettyPhoto on certain places (archives etc).
        return str_replace("<a", "<a title='' alt='' rel='prettyPhoto[x]'", $content);
    }

endif;

add_action('after_setup_theme', 'themeton_setup');
if (!function_exists('themeton_setup')) {

    function themeton_setup() {
        add_editor_style();
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        load_theme_textdomain('themeton', get_template_directory() . '/languages');
    }

}
/*
  if (!isset($content_width))
  $content_width = 900;
 */
if (!function_exists('widgets_init')) :

    function widgets_init() {
        global $footerGrid, $data;
        // Default sidebar.
        register_sidebar(array(
            'name' => __('Default sidebar', 'themeton'),
            'id' => 'default-sidebar',
            'description' => __('The default sidebar widget area', 'themeton'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Header sidebar.
        register_sidebar(array(
            'name' => __('Header sidebar', 'themeton'),
            'id' => 'header-sidebar',
            'description' => __('The header sidebar widget area', 'themeton'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s span3">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));


        // Footer sidebar
        if (isset($data['show_footer']) && $data['show_footer']) {
            $grid = (isset($data['footer_layout']) && $data['footer_layout'] != '') ? $data['footer_layout'] : '4';
            $grid = $footerGrid[$grid];
            $bagana = count(split('-', $grid)) - 1;
            $x = (940 - (30 * $bagana)) / 24;
            $i = 1;

            foreach (split('-', $grid) as $g) {

                $last = $i == ($bagana + 1) ? ' margin-right: 0px;' : ' margin-right: 30px;';
                register_sidebar(array(
                    'name' => __("Footer sidebar $i", "themeton"),
                    'id' => "footer-sidebar-$i",
                    'description' => __('The footer sidebar widget area', 'themeton'),
                    'before_widget' => '<div id="%1$s"  class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3 class="widget-title">',
                    'after_title' => '</h3>',
                ));

                $i++;
            }
        }
        // Custom Sidebar
        $name = 'custom_sidebar';
        if (!empty($data[$name])) {
            foreach ($data[$name] as $row) {
                if ($row != "" && $row['title'] != "") {
                    register_sidebar(array(
                        'name' => $row['title'],
                        'id' => $row['title'],
                        'description' => __('The page widget area', 'energy'),
                        'before_widget' => '<aside id="%1$s" class="dynamic_sidebar widget %2$s">',
                        'after_widget' => '</aside>',
                        'before_title' => '<h3 class="widget-title">',
                        'after_title' => '</h3><div class="widget-content">',
                    ));
                }
            }
        }
    }

endif;

if (!function_exists('mytheme_comment')) :

    function mytheme_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        print '<div class="comment-block">';
        ?>
        <div class="comment">
            <div class="comment-author">
                <?php echo get_avatar($comment, $size = '28'); ?>
                <span class="comment-author-link"><span class="author-link-span">
                        <?php print get_comment_author_link(); ?></span>
                </span>
                <div class="comment-meta">
                    <span class="comment-date"><?php printf(__('%1$s', 'themeton'), get_comment_date()) ?></span>
                    <span class="comment-replay-link"><?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="comment-body">
                <?php comment_text() ?>
            </div>
        </div><?php
    }

endif;

if (!function_exists('get_sticky_posts_count')) :

    function get_sticky_posts_count() {
        global $wpdb;
        $sticky_posts = array_map('absint', (array) get_option('sticky_posts'));
        return count($sticky_posts) > 0 ? $wpdb->get_var($wpdb->prepare("SELECT COUNT( 1 ) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND ID IN (" . implode(',', $sticky_posts) . ")")) : 0;
    }

endif;

if (!function_exists('give_linked_images_class')) :

    /**     * Attach a class to linked images' parent anchors * e.g. a img => a.img img */
    function give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '') {
        $classes = 'preload'; // separated by spaces, e.g. 'img image-link'
        //// check if there are already classes assigned to the anchor
        if (preg_match('/<a.*? class=".*?">/', $html)) {
            $html = preg_replace('/(<a.*? class=".*?)(".*?>)/', 'rel="prettyphoto" title="" $1 ' . $classes . '$2', $html);
        } else {
            $html = preg_replace('/(<a.*?)>/', '$1 class="preload" rel="prettyphoto" title="" >', $html);
        }
        return $html;
    }

    add_filter('image_send_to_editor', 'give_linked_images_class', 10, 8);
endif;

/*
 *  -------------- Added oembed instagram status ------------------------------------- */
if (!function_exists('wp_oembed_instagram')) :

    function wp_oembed_instagram($matches, $attr, $url, $rawattr) {
        global $post;
        $img_before = $img_after = "";
        if (is_single())
            $size = ( isset($attr['size']) ) ? $attr['size'] : 'l';
        else
            $size = ( isset($attr['size']) ) ? $attr['size'] : 'm';
        if (!is_single() && !is_page()) {
            $img_before = '<a title="' . get_the_title() . '" href="' . get_permalink() . '" class="preload iconInstagram item-preview item-click-modal">';
            $img_after = '</a>';
        }
        return apply_filters('embed_instagram', '<div class="instagram-photo clearfix"><div class="hover-content">' . $img_before . '<img src="http://instagr.am/p/' . $matches[2] . '/media?size=' . $size . '" alt="Instagram" id="instagram-' . $matches[2] . '" class="instagram-size-' . $size . '">' . $img_after . '<a class="instagram-link" href="' . $url . '"><img alt="Instagram" src="' . get_template_directory_uri() . '/images/instagram-icon.png"></a></div></div>', $matches, $attr, $url, $rawattr);
    }

    wp_embed_register_handler('instagram', '#http://(instagr\.am|instagram.com)/p/(.*)/#i', 'wp_oembed_instagram');
endif;

add_action( 'wp_footer', 'import_scripts' );
if (!function_exists('import_scripts')) :

    function import_scripts() {
        global $data;
        wp_register_script('bootstrap-1', get_template_directory_uri() . '/js/bootstrap.min.js');
        wp_register_script('easing-1', get_template_directory_uri() . '/js/jquery.easing.1.3.js');
        wp_register_script('validate-1', get_template_directory_uri() . '/js/jquery.validate.min.js');
        wp_register_script('jplayer', get_template_directory_uri() . '/js/jquery.jplayer.min.js');
        wp_register_script('preloader-1', get_template_directory_uri() . '/js/jquery.preloader.js');
        wp_register_script('nicescroll', get_template_directory_uri() . '/js/jquery.nicescroll.min.js');
        wp_register_script('flexslider-1', get_template_directory_uri() . '/js/jquery.flexslider-min.js');
        wp_register_script('plusIsotope', get_template_directory_uri() . '/js/jquery.isotope.min.js');
        wp_register_script('fbc-1', 'http://connect.facebook.net/en_US/all.js#xfbml=1&appId=' . (isset($data['facebook_app_id']) ? $data['facebook_app_id'] : ""));
        wp_register_script('infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js');
        wp_register_script('touchswipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js');
		wp_register_script('fitvids-responsive', get_template_directory_uri() . '/js/jquery.fitvids.js');
        wp_register_script('theme_script_1', get_template_directory_uri() . '/js/scripts.js');
        wp_register_script('prettyPhoto-1', get_template_directory_uri() . '/js/jquery.prettyPhoto.js');
		wp_register_script('buttons-1', get_template_directory_uri() . '/js/buttons.js');

        wp_enqueue_script('jquery');
        wp_enqueue_script('prettyPhoto-1');
        wp_enqueue_script('bootstrap-1');
        wp_enqueue_script('easing-1');
        wp_enqueue_script('validate-1');
        wp_enqueue_script('jplayer');
        wp_enqueue_script('preloader-1');
		if (!(isset($data['stop_nice_scroll']) && $data['stop_nice_scroll']))
			wp_enqueue_script('nicescroll');
        wp_enqueue_script('flexslider-1');
        wp_enqueue_script('plusIsotope');
        wp_enqueue_script('infinitescroll');
        if(is_mobile())
            wp_enqueue_script('touchswipe');
        if (isset($data['facebook_comment']) && $data['facebook_comment'])
            wp_enqueue_script('fbc-1');
		wp_enqueue_script('fitvids-responsive');
        if (isset($data['social_media']) && $data['social_media'])
            wp_enqueue_script('buttons-1');
        wp_enqueue_script('theme_script_1');
    }

endif;

if (!function_exists('get_format_audio_feature')) :

    function get_format_audio_feature($current_post_id) {
        global $post;
        if (get_post_meta($current_post_id, 'tt-audio-type', true) != 'url') {
            echo get_post_meta($current_post_id, 'tt-audio-embed', true);
        } else {
                    ?>
            <div id="jquery_jplayer_<?php echo $current_post_id; ?>" pid="<?php echo $current_post_id; ?>" class="jp-jplayer jp-jplayer-audio" src="<?php echo get_post_meta($current_post_id, 'tt-audio-url', true); ?>" style="width: 0px; height: 0px; "></div>
            <div class="jp-audio-container">
                <div class="jp-audio">
                    <div class="jp-type-single">
                        <div id="jp_interface_<?php echo $current_post_id; ?>" class="jp-interface">
                            <ul class="jp-controls">
                                <li><div class="seperator-first"></div></li>
                                <li><div class="seperator-second"></div></li>
                                <li><a href="#" class="jp-play" tabindex="1" style="display: block; ">play</a></li>
                                <li><a href="#" class="jp-pause" tabindex="1" style="display: none; ">pause</a></li>
                                <li><a href="#" class="jp-mute" tabindex="1">mute</a></li>
                                <li><a href="#" class="jp-unmute" tabindex="1" style="display: none; ">unmute</a></li>
                            </ul>
                            <div class="jp-progress-container">
                                <div class="jp-progress">
                                    <div class="jp-seek-bar" style="width: 100%; ">
                                        <div class="jp-play-bar" style="width: 1.18944845234691%; "></div>
                                    </div>
                                </div>
                            </div>
                            <div class="jp-volume-bar-container">
                                <div class="jp-volume-bar">
                                    <div class="jp-volume-bar-value" style="width: 80%; "></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

endif;

if (!function_exists('tt_get_filter_list')) :

//tt_get_post_format_filter() ->
    function tt_get_filter_list($isBlog = false) {
        global $data, $post;
        if (!isset($data['left_sidebar']) || $data['left_sidebar']) {
            ?>
            <!-- Start Filter --><?php
            if ($isBlog) {
                $filter = get_post_meta($post->ID, 'themeton_additional_options', true);
                if (isset($filter['show_filter'])) {
                    ?>
                    <div id="options" class="category-list clearfix">
                        <?php if ($data['full_width'] == 'fixed' || $data['full_width'] == 'only_grid') echo '<div class="container">'; ?>
                            <h3><?php _e('Post Filter', 'themeton') ?></h3>
                            <ul id="filters" class="option-set clearfix post-category" data-option-key="filter"><?php if (!empty($filter['filter_text'])) { ?>
                                    <li><a href="#filter" data-option-value="*" class="selected"><?php echo $filter['filter_text']; ?></a></li><?php
                    }
                    $filters = isset($filter['blog_categories']) ? $filter['blog_categories'] : get_categories();
                    foreach ($filters as $catSlug) {
                        if (!isset($filter['blog_categories'])) {
                            $catSlug = $catSlug->slug;
                        }
                        $category = get_term_by('slug', $catSlug, 'category');
                        if($category)
                        echo '<li class="hide"><a href="#filter" data-option-value=".category-' . $catSlug . '" title="' . $category->name . '" ' . ' class="post-category-item">' . $category->name . '</a></li>';
                    }
                    ?>
                            </ul>
                        <?php if ($data['full_width'] == 'fixed' || $data['full_width'] == 'only_grid') echo '</div>'; ?>
                    </div><?php
                }
            }
			if($isBlog) {
				$filter_tag = get_post_meta($post->ID, 'themeton_additional_options', true);
				if (isset($filter_tag['show_filter_tag'])) {
							?>
					<div id="options" class="tag-list clearfix">
						<h3><?php _e('Post Filter', 'themeton') ?></h3>
					<?php $tags = get_tags(); ?>
						<ul id="filters" class="option-set clearfix post-tag" data-option-key="filter">
							<li><a href="#tfilter" data-option-value="*" class="selected"><?php _e('Show all', 'themeton') ?></a></li><?php
					foreach ($tags as $tag) {
						echo'<li class="hide"><a href="#filter" data-option-value=".tag-' . $tag->slug . '" title="' . $tag->name . '" ' . ' class="post-tag-item">' . $tag->name . '</a></li>';
					}
					?>
						</ul>
					</div><?php
				}
			}
            ?>
            <!-- End Filter --><?php
        }
    }

endif;

if (!function_exists('tt_get_post_category_list')) :

    function tt_get_post_category_list() {
        if (get_the_category_list()) {
            echo get_the_category_list(', ');
        }
        if (is_single() && get_the_tag_list()) {
            echo " ";
            echo get_the_tag_list('<span>' . __('Tagged: ', 'themeton') . '</span>', ', ', '');
        }
    }

endif;

// Themeton Mega Menu
require_once FRAMEWORKPATH . '/admin/lib/themeton_mega_menu.php';
if (!function_exists('get_attachment_id_from_src')) :

    function get_attachment_id_from_src($image_src) {
        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
        $id = $wpdb->get_var($query);
        return $id;
    }

endif;

if (!function_exists('get_youtube_vimeo_thumb_url')) :

    function get_youtube_vimeo_thumb_url($embed) {
        $search = 'src="http://www.youtube.com/embed/';
        $posStart = strpos($embed, $search);
        $thumb_url = false;
        if ($posStart !== false) {
            $posStart+=strlen($search);
            $posEnd = (strpos($embed, '?', $posStart) > -1) ? strpos($embed, '?', $posStart) : strpos($embed, '"', $posStart);
            if ($posEnd !== false) {
                $thumb_url = substr($embed, $posStart, $posEnd - $posStart);
                $thumb_url = 'http://img.youtube.com/vi/' . $thumb_url . '/0.jpg';
            }
        }

        if ($thumb_url === false) {
            $search = 'src="http://player.vimeo.com/video/';
            $posStart = strpos($embed, $search);
            if ($posStart !== false) {
                $posStart+=strlen($search);
                $posEnd = strpos($embed, '?', $posStart);
                if ($posEnd !== false) {
                    $thumb_url = substr($embed, $posStart, $posEnd - $posStart);
                    $thumb_url = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . $thumb_url . ".php"));
                    $thumb_url = $thumb_url[0]['thumbnail_large'];
                }
            }
        }
        return $thumb_url;
    }

endif;

if (!function_exists('blog_open_graph_meta')) :

    function blog_open_graph_meta() {
        global $data, $post, $paged, $page;
        $ogImg = false;
//    if(is_page_template('page.php') || is_single()) {
        if (is_page() || is_single()) {
            $ogImg = get_post_image();
            if (!$ogImg) {
                $ogImg = get_post_first_image();
            }
            if (!$ogImg) {
                $ogImg = get_youtube_vimeo_thumb_url(get_post_meta($post->ID, 'tt-video-embed', true));
            }
            if (!$ogImg) {
                $slide_imgs = get_post_meta($post->ID, 'tt_slide_images', true);
                $ogImg = !empty($slide_imgs[0]['image']) ? $slide_imgs[0]['image'] : false;
            }
        }
        ?>
        <!-- START - Open Graph Meta -->
        <meta property='og:title' 	    content='<?php
        wp_title('|', true, 'right');
        bloginfo('name');
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && ( is_home() || is_front_page() ))
            echo " | $site_description";
        if ($paged >= 2 || $page >= 2)
            echo ' | ' . sprintf(__('Page %s', 'themeton'), max($paged, $page));
        ?>'/>
        <meta property='og:image' 	content='<?php echo $ogImg ? $ogImg : ''; ?>'/>
        <meta property='og:site_name'   content='<?php bloginfo('name'); ?>'/>
        <meta property='og:description' content='<?php echo get_bloginfo('description'); ?>'/>
        <!-- END   - Open Graph Meta --><?php
    }

endif;

// Fixing duplicating issue when has Ramdom post order
global $data;
if (!is_admin() && isset($data['order_type']) && $data['order_type'] == 'Random') {

    add_filter('posts_orderby', 'edit_posts_orderby');

    if (!function_exists('edit_posts_orderby')) :

        function edit_posts_orderby($orderby_statement) {
            if (isset($_SESSION['expiretime'])) {
                if ($_SESSION['expiretime'] < time())
                    session_unset();
            } else
                $_SESSION['expiretime'] = time() + 300;

            $seed = isset($_SESSION['seed']) ? $_SESSION['seed'] : '';
            if (empty($seed)) {
                $seed = rand();
                $_SESSION['seed'] = $seed;
            }
            $orderby_statement = 'RAND(' . $seed . ')';

            return $orderby_statement;
        }

    endif;
}

if (!function_exists('remove_category_list_rel')) :

// Remove rel attribute from the category list
    function remove_category_list_rel($output) {
        $output = str_replace(' rel="category tag"', '', $output);
        $output = str_replace(' rel="category"', '', $output);
        $output = str_replace(' rel="tag"', '', $output);
        return $output;
    }

    add_filter('wp_list_categories', 'remove_category_list_rel');
    add_filter('the_category', 'remove_category_list_rel');

// Feature Pointers
    add_action('admin_enqueue_scripts', 'tt_theme_feature_pointer_header');
endif;

if (!function_exists('tt_theme_feature_pointer_header')) :

    function tt_theme_feature_pointer_header() {
        global $pagenow;
        $enqueue = false;

        $dismissedStr = (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
        $dismissed = explode(',', $dismissedStr);

        // with activate istall option
        if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php') {
            $removed = str_replace(",tt_feature_pointer", "", $dismissedStr);
            $dismissed = explode(',', $removed);
            update_user_meta(get_current_user_id(), 'dismissed_wp_pointers', $removed);
        }

        if (!in_array('tt_feature_pointer', $dismissed)) {
            $enqueue = true;
            add_action('admin_print_footer_scripts', 'tt_feature_pointer');
        }

        if ($enqueue) {
            // Enqueue pointers
            wp_enqueue_script('wp-pointer');
            wp_enqueue_style('wp-pointer');
        }
    }

endif;

if (!function_exists('tt_feature_pointer')) :

    function tt_feature_pointer() {
        global $pagenowglobal;
        $back_end_pointer_message = array(
            'tt_option_group' => array(
                'selector' => '#toplevel_page_pressgrid-options',
                'content' => '<h3>pressgrid options panel</h3><p>Check out our admin panel where you have access to over 70+ options.  We have split these options up into 3 different sections to help you customize your site.</p><a class="button-primary" href="admin.php?page=pressgrid-options">next</a>'),
            'pressgrid-options' => array(
                'selector' => '#toplevel_page_pressgrid-options li.tt-theme-options',
                'content' => '<h3>Themes Options</h3><p>It has options relevant to the entire site such as logo, favicon, skin and more, without having to change any code. Ex: The Footer tab will let you customize everything about your footer choose between 4 different layouts!</p><a class="button-primary" href="admin.php?page=seooptions">next</a>'),
            'seooptions' => array(
                'selector' => '#toplevel_page_pressgrid-options li.tt-theme-seo',
                'content' => '<h3>SEO options</h3><p>If you fully configure Theme SEO option then your sites getting high traffic. The panel gives you control over title tags, noindex, meta tags, slugs, image and much more.</p><a class="button-primary" href="admin.php?page=comp-options">next</a>'),
            'comp-options' => array(
                'selector' => '#toplevel_page_pressgrid-options li.tt-theme-elements',
                'content' => '<h3>Theme Elements</h3><p>Select for which content types Theme Element (visual shortcode) should be available during post creation/editing. Also you can disable Theme Elements.</p><a class="button-primary" href="post-new.php?post_type=page">next</a>'),
            'tt_pointer' => array(
                'selector' => '.wpb_switch-to-composer',
                'content' => '<h3>Theme Elements</h3><p>It will save you tons of time working on the site content. Now you’ll be able to create complex layouts within minutes!</p>'),
        );
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) { <?php
        $tt_pointer_page = 'tt_option_group';
        if (isset($_REQUEST['post_type'])) {
            $tt_pointer_page = 'tt_pointer';
        } elseif (isset($_REQUEST['page'])) {
            $tt_pointer_page = $_REQUEST['page'];
        }

        if (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == 'slidercatalog' && isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'slider') {
            $tt_pointer_page = 'slider_adding';
        }
        ?>

                $page='<?php echo $tt_pointer_page; ?>';

                $('#toplevel_page_pressgrid-options>.wp-submenu li').each(function(i){
                    liClass='';
                    switch(i){
                        case 0: liClass="tt-theme-options";  break;
                        case 1: liClass="tt-theme-seo";      break;
                        case 2: liClass="tt-theme-elements"; break;
                        case 3: liClass="tt-theme-guide";    break;
                    }
                    $(this).addClass(liClass);
                });

                function tt_dismiss_wp_pointer(){
                    $.post( ajaxurl, {
                        pointer: 'tt_feature_pointer',
                        action: 'dismiss-wp-pointer'
                    });
                }

                function tt_open_wp_pointer(){
                    $('<?php echo $back_end_pointer_message[$tt_pointer_page]['selector']; ?>').pointer({
                        content: '<?php echo $back_end_pointer_message[$tt_pointer_page]['content']; ?>',
                        position: {
                            edge: 'left',
                            align: 'center'
                        },
                        close: function() { tt_dismiss_wp_pointer(); }
                    }).pointer('open');
                }

                switch($page){
                    case 'tt_option_group':
                    case 'pressgrid-options' :
                    case 'seooptions'     :
                    //                case 'guide'          : { $('#toplevel_page_pressgrid-options li.tt-theme-guide').pointer('open');    break; }
                case 'guide'          :
                case 'tt_pointer'     :
                case 'slider_adding'  :{tt_open_wp_pointer(); break;}
                case 'comp-options'   : {
                        $('.controls input').each(function(){
                            if($(this).attr('name')=='check_composer[page]' && $(this).attr('checked')!='checked'){
                                $(this).attr('checked','checked');
                                $('#c_of_save').click();
                            }
                        });
                        tt_open_wp_pointer();
                        break;
                    }
            }
        });
        </script><?php
    }

endif;

if (!function_exists('infiniteScroll')) :

// Infinite Scroll
    function infiniteScroll() {
        global $wp_query, $data;
        $pages = $wp_query->max_num_pages;
        $post_count = $wp_query->post_count;
        $post_found = $wp_query->found_posts;
        if (!$pages) {
            $pages = 1;
        }
        if (1 < $pages) {
            $pagination_type = !empty($data['pagination_type']) ? $data['pagination_type'] : false;
            if ($pagination_type == 'pagination') {
                $range = 2;
                $showitems = ($range * 2) + 1;
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                echo "<div class='pagination-container container'>";
                echo "<div class='row'>";
                echo "<div class='span12 pagination'>";
                echo '<ul class="pager-list">';
                if ($paged > 1)
                    echo '<li><a href="' . get_pagenum_link($paged - 1) . '" class="pager-item">' . __('Prev', 'themeton') . '</a></li>';
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
                    echo "<li><a href='" . get_pagenum_link(1) . "'><span class='pagination-number'>" . __('&laquo;', 'themeton') . "</span></a></li>";
                $nextPager = "";
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                        if ($paged == $i) {
                            echo "<li class='active'><a><span class='pagination-number'>" . $i . "</span></a></li>";
                            $nextPager = "next-page";
                        } else {
                            echo "<li><a href='" . get_pagenum_link($i) . "' class='inactive " . $nextPager . "' ><span class='pagination-number'>" . $i . "</span></a></li>";
                            $nextPager = "";
                        }
                    }
                }
                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
                    echo "<li><a href='" . get_pagenum_link($pages) . "'><span class='pagination-number'>" . __('&raquo;', 'themeton') . "</span></a></li>";
                if ($paged < $pages)
                    echo '<li><a href="' . get_pagenum_link($paged + 1) . '" class="pager-item">' . __('Next', 'themeton') . '</a></li>';
                echo "</ul>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }else {
                echo '<nav id="page_nav"><a href=""></a></nav>';
                if ($pagination_type == 'manual_infinite') {
                    ?>
                    <nav class="manual-infinite-scroll">
                        <div class="container"><div class="row">
                                <div class="span12">
                                    <div class="to-top">
                                        <a class="pager-item anchorLink" style="cursor:pointer;">
                                            <?php _e('Back to top', 'themeton') ?>
                                        </a>
                                    </div>
                                    <div class="infinite-button">
                                        <a class="next-items" style="cursor:pointer;"><?php _e('Load more', 'themeton'); ?></a>
                                    </div>
                                    <div class="remaining clearfix" data-count="<?php echo $post_count; ?>" data-found="<?php echo $post_found; ?>" data-many="<?php _e('remaining', 'themeton'); ?>" data-one="<?php _e('remaining', 'themeton'); ?>" data-no="<?php _e('No more posts', 'themeton'); ?>"></div>
                                </div>
						</div></div>
                    </nav><?php
                    }
                }
            }
        }

    endif;
    if (!function_exists('tt_prev_next_post')) :

        // Next prev post
        function tt_prev_next_post() {
            global $formatimg, $format;
            //Get Previous
            $next_post = get_next_post();
            if (!empty($next_post)) {
                query_posts('p=' . $next_post->ID);
                if (have_posts()) {
                    the_post();
                    $format = get_post_format();
                    $formatimg = $format == '' ? 'standart' : "format-$format";
                    $btnImg = get_post_image_for_nextprev();
                    if (!$btnImg) {
                        $btnImg = get_post_first_image();
                    }
                    if (!$btnImg) {
                        $btnImg = get_youtube_vimeo_thumb_url(get_post_meta($next_post->ID, 'tt-video-embed', true));
                    }
                                        ?>
                <div id="prev" class="<?php
                if (!$btnImg) {
                    echo 'no-thumb';
                }
                ?>">
                    <a href="<?php the_permalink(); ?>">
                        <div class="lightBoxNav navLeft"></div>
                    </a>
                    <a href="<?php the_permalink(); ?>" class="link-content">
                        <div class="prev_post"><h3 class="item-title"><?php the_title(); ?></h3><?php
                if ($btnImg) {
                    echo '<img src="' . $btnImg . '" alt="">';
                }
                ?>
                        </div>
                    </a>
                </div><?php
            }
            wp_reset_query();
        }
        //Get Next
        $prev_post = get_previous_post();
        if (!empty($prev_post)) {
            query_posts('p=' . $prev_post->ID);
            if (have_posts()) {
                the_post();
                $format = get_post_format();
                $formatimg = $format == '' ? 'standart' : "format-$format";
                $btnImg = get_post_image_for_nextprev();
                if (!$btnImg) {
                    $btnImg = get_post_first_image();
                }
                if (!$btnImg) {
                    $btnImg = get_youtube_vimeo_thumb_url(get_post_meta($prev_post->ID, 'tt-video-embed', true));
                }
                                        ?>
                <div id="next" class="<?php
                if (!$btnImg) {
                    echo 'no-thumb';
                }
                ?>">
                    <a href="<?php the_permalink(); ?>">
                        <div class="lightBoxNav navRight"></div>
                    </a>
                    <a href="<?php the_permalink(); ?>" class="link-content">
                        <div class="next_post">
                            <h3 class="item-title"><?php the_title(); ?></h3><?php
                if ($btnImg) {
                    echo '<img src="' . $btnImg . '" alt="">';
                }
                                        ?>    </div>
                    </a>
                </div><?php
            }
            wp_reset_query();
        }
    }

endif;

if (!function_exists('aq_resize')) :
	/**
	 * Title         : Aqua Resizer
	 * Description   : Resizes WordPress images on the fly
	 * Version       : 1.1.7
	 * Author        : Syamil MJ
	 * Author URI    : http://aquagraphite.com
	 */
    function aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

		// Validate inputs.
		if ( ! $url || ( ! $width && ! $height ) ) return false;

		// Caipt'n, ready to hook.
		if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'aq_upscale', 10, 6 );

		// Define upload path & dir.
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];

		$http_prefix = "http://";
		$https_prefix = "https://";

		/* if the $url scheme differs from $upload_url scheme, make them match
		   if the schemes differe, images don't show up. */
		if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
		}
		elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
		}


		// Check if $img_url is local.
		if ( false === strpos( $url, $upload_url ) ) return false;

		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = $upload_dir . $rel_path;

		// Check if img path exists, and is an image indeed.
		if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

		// Get image info.
		$info = pathinfo( $img_path );
		$ext = $info['extension'];
		list( $orig_w, $orig_h ) = getimagesize( $img_path );

		// Get image size after cropping.
		$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
		$dst_w = $dims[4];
		$dst_h = $dims[5];

		// Return the original image only if it exactly fits the needed measures.
		if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
			$img_url = $url;
			$dst_w = $orig_w;
			$dst_h = $orig_h;
		} else {
			// Use this to check if cropped image already exists, so we can return that instead.
			$suffix = "{$dst_w}x{$dst_h}";
			$dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
			$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

			if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
				// Can't resize, so return false saying that the action to do could not be processed as planned.
				return false;
			}
			// Else check if cache exists.
			elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
				$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
			}
			// Else, we resize the image and return the new resized image url.
			else {

				// Note: This pre-3.5 fallback check will edited out in subsequent version.
				if ( function_exists( 'wp_get_image_editor' ) ) {

					$editor = wp_get_image_editor( $img_path );

					if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
						return false;

					$resized_file = $editor->save();

					if ( ! is_wp_error( $resized_file ) ) {
						$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
						$img_url = $upload_url . $resized_rel_path;
					} else {
						return false;
					}

				} else {

					$resized_img_path = image_resize( $img_path, $width, $height, $crop ); // Fallback foo.
					if ( ! is_wp_error( $resized_img_path ) ) {
						$resized_rel_path = str_replace( $upload_dir, '', $resized_img_path );
						$img_url = $upload_url . $resized_rel_path;
					} else {
						return false;
					}

				}

			}
		}

		// Okay, leave the ship.
		if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'aq_upscale' );

		// Return the output.
		if ( $single ) {
			// str return.
			$image = $img_url;
		} else {
			// array return.
			$image = array (
				0 => $img_url,
				1 => $dst_w,
				2 => $dst_h
			);
		}

		return $image;
	}


	function aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
		if ( ! $crop ) return null; // Let the wordpress default function handle this.

		// Here is the point we allow to use larger image size than the original one.
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = $dest_w;
		$new_h = $dest_h;

		if ( ! $new_w ) {
			$new_w = intval( $new_h * $aspect_ratio );
		}

		if ( ! $new_h ) {
			$new_h = intval( $new_w / $aspect_ratio );
		}

		$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

		$crop_w = round( $new_w / $size_ratio );
		$crop_h = round( $new_h / $size_ratio );

		$s_x = floor( ( $orig_w - $crop_w ) / 2 );
		$s_y = floor( ( $orig_h - $crop_h ) / 2 );

		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}

endif;

if (!function_exists('user_bar')) :

    function user_bar($is_widget = false) {
//    tt_social_message('user_name_exists');
        global $data;
                                ?>
        <div class="user-bar <?php echo $is_widget ? 'user-widget clearfix' : ''; ?>"><?php
        if (is_user_logged_in()) {
            $log_out_url = isset($data['logout_redirect_page']) && $data['logout_redirect_page'] != 'no' && $data['logout_redirect_page'] != '' ? get_permalink($data['logout_redirect_page']) : home_url();
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_login = $current_user->user_login;
                                    ?>
                <div class="user-online pull-right">
                    <a href="<?php echo get_author_posts_url($user_id); ?>" class="tt-author2">
                        <div class="author-avatar">
            <?php tt_get_user_avatar(); ?>
                        </div>
                        <div class="author-content">
                            <span class="user-name"><?php echo $user_login; ?></span><b class="caret"></b>
                            <span class="user-caps"><?php echo key($current_user->caps); ?></span>
                        </div>
                    </a>
                    <ul class="user-bar-dropdown clearfix <?php echo $is_widget ? '' : 'dropdown-menu'; ?>">
                        <li><a href="<?php echo get_author_posts_url(get_current_user_id()); ?>" class="user-my-profile"><i class="icon-user"></i><?php _e('My profile', 'themeton'); ?></a></li>
                        <?php if (isset($data['profile_options_page']) && $data['profile_options_page'] !== 'no' && $data['profile_options_page'] !== '') { ?>
                            <li><a href="<?php echo get_permalink($data['profile_options_page']); ?>" class="user-my-settings"><i class="icon-wrench"></i><?php _e('My settings', 'themeton'); ?></a></li>
                        <?php } ?>
                        <?php if (isset($data['frontend_editor_page']) && $data['frontend_editor_page'] !== 'no' && $data['frontend_editor_page'] !== '') { ?>
                            <li><a href="<?php echo get_permalink($data['frontend_editor_page']); ?>" class="user-add-post"><i class="icon-pencil"></i><?php _e('Add Post', 'themeton'); ?></a></li>
            <?php } ?>
            <?php if (isset($data['draft_page']) && $data['draft_page'] != 'no' && $data['draft_page'] != '') { ?>
                            <li><a href="<?php echo get_permalink($data['draft_page']); ?>" class="user-draft-posts"><i class="icon-star"></i><?php _e('My drafts', 'themeton'); ?></a></li>
                <?php } ?>
            <?php if (isset($data['favorite_page']) && $data['favorite_page'] != 'no' && $data['favorite_page'] != '') { ?>
                            <li><a href="<?php echo get_permalink($data['favorite_page']); ?>" class="user-favorite-posts"><i class="icon-star"></i><?php _e('Favorite posts', 'themeton'); ?></a></li>
                <?php } ?>
                        <li class="divider"></li>
                        <li><a href="<?php echo wp_logout_url($log_out_url); ?>" class="user-log-out"><i class="icon-remove-sign"></i><?php _e('Log out', 'themeton'); ?></a></li>
                    </ul>
                </div><?php } else {
                ?>
                <div class="user-offline">
                    <div class="user-join">
            <?php _e('Not a Member?', 'themeton'); ?>
                        <a href="#">
                        <?php _e('Join Now', 'themeton'); ?>
                        </a>
                    </div>
                    <div class="user-login-buton pull-right"><?php
            if (isset($data['fb_connect']) && $data['fb_connect'] && isset($data['fb_app_id']) && !empty($data['fb_app_id']) && isset($data['fb_app_secret']) && !empty($data['fb_app_secret'])) {
                global $facebook;
                            ?><a href="<?php echo $facebook->getLoginUrl(array('scope' => 'email')); ?>" class="fb"><img alt="Login with facebook" src="<?php echo get_template_directory_uri(); ?>/images/facebook-login.png" /></a><?php
            }
            if (isset($data['tw_connect']) && $data['tw_connect'] && isset($data['tw_consumer_key']) && !empty($data['tw_consumer_key']) && isset($data['tw_consumer_secret']) && !empty($data['tw_consumer_secret'])) {
                echo'<a href="' . home_url() . '?tt_tw_redirect=true" class="tw"><img alt="Login with twitter" src="' . get_template_directory_uri() . '/images/twitter-login.png"/></a>';
            }
            if (!$is_widget) {
                            ?>
                            <a href="#" class="btn wp dropdown-toggle"><?php _e('Sign In', 'themeton'); ?></a><?php }
            ?>
                        <div class="user-form-container <?php echo $is_widget ? '' : 'dropdown-menu'; ?>">
                <?php user_login_form(); ?>
                        </div>
                    </div>
                </div><?php }
            ?>
        </div><?php
    }

endif;

if (!function_exists('style_search_form')) :

// Customize the search form
    function style_search_form($form) {
        $form = '<form method="get" id="searchform" class="form-search " action="' . home_url() . '/" >
            <div class="input-append">';
        $form .= '<button type="submit" id="searchsubmit"></button>';
        if (is_search()) {
            $form .='<input type="search" value="' . esc_attr(apply_filters('the_search_query', get_search_query())) . '" name="s" class="span2" id="appendedInputButton" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;"/>';
        } else {
            $form .='<input type="search" name="s" class="span2" id="appendedInputButton" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;"/>';
        }
        $form .= '</div>
            </form>';
        return $form;
    }

    add_filter('get_search_form', 'style_search_form');
endif;

if (!function_exists('exclude_pages_from_search')) :

    function exclude_pages_from_search($query) {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
        return $query;
    }

    add_filter('pre_get_posts', 'exclude_pages_from_search');
endif;

if (!function_exists('is_mobile')) :

    function is_mobile() {
        if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
            return true;
        else
            return false;
    }

endif;

if (!function_exists('adjustBrightness')) :

    function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Format the hex color string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        // Get decimal values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Adjust number of steps and keep it inside 0 to 255
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
        $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
        $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

        return '#' . $r_hex . $g_hex . $b_hex;
    }

endif;

if (!function_exists('getContrast50')) :

    function getContrast50($hexcolor) {
        return (hexdec($hexcolor) > (0xffffff / 2 + 0xffffff / 10)) ? 'light' : 'dark';
    }

endif;

if (!function_exists('tt_save_post')) :
// START - Auto Delete Posts
    function tt_save_post($post_id) {
        global $data;
        if (isset($data['auto_delete']) && $data['auto_delete']) {
            if (!wp_is_post_revision($post_id)) {
                if (get_post_meta($post_id, 'tt_insert', true)) {
                    if (get_post_status($post_id) == 'publish') {
                        update_post_meta($post_id, 'tt_insert', ('ok'));
                    }
                } else {
                    update_post_meta($post_id, 'tt_insert', (get_post_status($post_id) == 'publish' ? 'ok' : time()));
                }
            }
        }
    }
endif;

if (!function_exists('delete_expired_posts')) :

    function delete_expired_posts() {
        global $data;
        if (isset($data['auto_delete']) && $data['auto_delete'] && isset($data['auto_delete_day'])) {
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'any',
                'post_status' => 'any',
                'meta_query' => array(
                    array(
                        'key' => 'tt_insert',
                        'value' => 'ok',
                        'compare' => '!='
                    )
                )
            );
            $the_query = new WP_Query($args);
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $post_id = $the_query->post->ID;
                if ($post_tt_insert = get_post_meta($post_id, 'tt_insert', true)) {
                    if (is_numeric($post_tt_insert) && $post_tt_insert = intval($post_tt_insert)) {
                        $day = 24 * 60 * 60;
                        if ($post_tt_insert + ($data['auto_delete_day'] * $day) < time()) {
//                        wp_delete_post($post_id,true);
                            $post_id = wp_update_post(array('ID' => $post_id, 'post_status' => 'trash'));
                        }
                    } else {
                        update_post_meta($post_id, 'tt_insert', 'ok');
                    }
                }
            }
            wp_reset_postdata();
        }
    }

endif;
if (isset($data['auto_delete']) && $data['auto_delete'] && isset($data['auto_delete_day'])) {
    add_action('save_post', 'tt_save_post');
    add_action('init', 'delete_expired_posts');
}

// END   - Auto Delete Posts


if (!function_exists('my_comment_form')) {

    function my_comment_form($fields) {
        $fields['author'] =
                '<div class="control-group overlabel-wrapper">' .
                '<input type="text" name="author" id="author" class="span3 required" value="" tabindex="1" />' .
                '<label for="author" class="overlabel">' . __('Name', 'themeton') . ' (*)</label>' .
                '</div>';
        $fields['email'] =
                '<div class="control-group overlabel-wrapper">' .
                '<input type="text" name="email" id="email" class="required email span3" value="" tabindex="2"/>' .
                '<label for="email" class="overlabel">' . __('Email', 'themeton') . ' (*)</label>
            </div>';
        $fields['url'] =
                '<div class="control-group overlabel-wrapper">' .
                '<input type="text" name="url" id="url" class="span3" value="" tabindex="3" />' .
                '<label for="url" class="overlabel">' . __('Website', 'themeton') . '</label>' .
                '</div>';
        return $fields;
    }

    add_filter('comment_form_default_fields', 'my_comment_form');
}

if (!function_exists('custom_upload_mimes')) {
    add_filter('upload_mimes', 'custom_upload_mimes');

    function custom_upload_mimes($existing_mimes = array()) {
        $existing_mimes['ico'] = "image/x-icon";
        return $existing_mimes;
    }

}

function theme_time_ago() {

    global $post;

    $date = get_post_time('G', false, $post);

    if (empty($date)) {
        return __('Pending Post', 'themeton');
    }
    $chunks = array(
        array(60 * 60 * 24 * 365, __('year', 'themeton'), __('years', 'themeton')),
        array(60 * 60 * 24 * 30, __('month', 'themeton'), __('months', 'themeton')),
        array(60 * 60 * 24 * 7, __('week', 'themeton'), __('weeks', 'themeton')),
        array(60 * 60 * 24, __('day', 'themeton'), __('days', 'themeton')),
        array(60 * 60, __('hour', 'themeton'), __('hours', 'themeton')),
        array(60, __('minute', 'themeton'), __('minutes', 'themeton')),
        array(1, __('second', 'themeton'), __('seconds', 'themeton'))
    );

    if (!is_numeric($date)) {
        $time_chunks = explode(':', str_replace(' ', ':', $date));
        $date_chunks = explode('-', str_replace(' ', '-', $date));
        $date = gmmktime((int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0]);
    }

    $current_time = current_time('mysql', $gmt = 0);
    $newer_date = strtotime($current_time);

    // Difference in seconds
    $since = $newer_date - $date;

    // Something went wrong with date calculation and we ended up with a negative date.
    if (0 > $since)
        return __('sometime', 'themeton');

    /**
     * We only want to output one chunks of time here, eg:
     * x years
     * xx months
     * so there's only one bit of calculation below:
     */
    //Step one: the first chunk
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];

        // Finding the biggest chunk (if the chunk fits, break)
        if (( $count = floor($since / $seconds) ) != 0)
            break;
    }

    // Set output var
    $output = ( 1 == $count ) ? '1 ' . $chunks[$i][1] : $count . ' ' . $chunks[$i][2];

    if (!(int) trim($output)) {
        $output = '0 ' . __('seconds', 'themeton');
    }
    $output .= __(' ago', 'themeton');
    return $output;
}


//top nav
  register_nav_menus(array(
    'sideheader'=> __('Header Sidebar')
  ));

//function to get slug from url

function get_category_slug($url){
  $slug=substr($url,0,strlen($url)-1);
  $slug=substr($slug,strrpos($slug,"/")+1,strlen($slug));
  return $slug;
}

function get_menu_cat($menu_name)
{
  $topmenuargs = array( );
  $categories = array();
  $locations = get_nav_menu_locations();
  $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
  $categories = wp_get_nav_menu_items($menu,$topmenuargs );
  return $categories;
}

function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


/* ------------------------------------------
  Custom Post Type
------------------------------------------ */

function newsfeed_custom_post_type(){
  $labels =array(
    'name'=>'News Feed',
    'singular_name'=>'webnewsfeed',
    'add_new'=>'Add news feed',
    'all_items'=>'All Items',
    'edit_item'=>'Edit Item',
    'new_item'=>'New Item',
    'view_item'=>'View Item',
    'search_items'=>'Search Feed',
    'not_found'=>'No items Found',
    'not_found_in_trash'=>'No items Found in Trash',
    'parent_item_colon'=>'Parent Item'
  );
  $args=array(
    'labels'=>$labels,
    'public'=>true,
    'has_archive'=>true,
    'publicly_queryable'=>true,
    'query_var'=>true,
    'rewrite'=>true,
    'capability_type'=>'post',
    'hierarchical'=>false,
    'supports'=>array(
      'title',
      'editor',
      'excerpt',
      'thumbnail',
      'revisions',
    ),
    'taxonomies'=>array('category','post_tag'),
    'menu_position'=>5,
    'exclude_from_search'=>false
  );
  register_post_type('webbernewsfeed',$args);
}

add_action('init','newsfeed_custom_post_type');
