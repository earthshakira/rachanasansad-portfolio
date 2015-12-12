<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists('logo_init')) :
function logo_init() {
    global $data;
    echo '<a id="logo" class="brand" href="' . home_url() . '">';
    if ($data['logo_image'] == '') {
        echo '<h1 class="site-name">';
        bloginfo('name');
        echo '</h1>';
    } else {
        echo '<img class="logo-img" src="' . $data['logo_image'] . '" alt="'.get_bloginfo('name').'" />';
    }
    echo '</a>';
}endif;

if (!function_exists('tt_post_image')) :
function tt_post_image($use_post_image = false, $wp_size='large') {
    global $post;
    $slide_imgs = get_post_meta($post->ID, 'tt_slide_images', true);
    if ($slide_imgs != '' && count($slide_imgs) > 0) {
        return $slide_imgs;
    }
    if (has_post_thumbnail($post->ID)) {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $wp_size);
        return $thumb[0];
        if ($use_post_image) {
            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
            $first_img = isset($matches[1][0]) ? $matches[1][0] : '';
            return $first_img;
        }
    }
    return '';
}endif;

if (!function_exists('current_title')) :
function current_title() {
    global $page, $paged, $seo, $post;
    if(isset($seo['seo_on']) && $seo['seo_on']) {
        $keyword = "";
        $description = get_bloginfo('description');
        if(is_home() || is_front_page()) {
            $title = seo_replace($seo['home_title'], (array) $post);
            $description = seo_replace($seo['home_desc'], (array) $post);
            $keyword = seo_replace($seo['keyword_home'], (array) $post);
            if(is_page()){
                $seo_option = get_post_meta($post->ID, 'seo_options', true);
                if(isset($seo_option['seo_title']) && $seo_option['seo_title']!='') {
                    $title .= " ".seo_replace($seo_option['seo_title'], (array) $post);
                }
                if(isset($seo_option['seo_desc']) && $seo_option['seo_desc']!='') {
                    $description .= " ".seo_replace($seo_option['seo_desc'], (array) $post);
                }
                if(isset($seo_option['seo_keyword']) && $seo_option['seo_keyword']!='') {
                    $keyword .= " ".$seo_option['seo_keyword'];
                }
            }
        } elseif(is_category()){
            $title = seo_replace($seo['category_title'], (array) $post);
            $description = seo_replace($seo['category_desc'], (array) $post);
            $keyword = seo_replace($seo['keyword_category'], (array) $post);
        } elseif(is_tax()){
            $title = seo_replace($seo['tax_title'], (array) $post);
            $description = seo_replace($seo['tax_desc'], (array) $post);
            $keyword = seo_replace($seo['keyword_tax'], (array) $post);
        } elseif(is_archive()){
            $title = seo_replace($seo['archive_title'], (array) $post);
            $description = seo_replace($seo['archive_desc'], (array) $post);
            $keyword = isset($seo['keyword_archive'])?seo_replace($seo['keyword_archive'], (array) $post):"";
        } elseif(is_author()){
            $title = seo_replace($seo['author_title'], (array) $post);
            $description = seo_replace($seo['author_desc'], (array) $post);
            $keyword = seo_replace($seo['keyword_author'], (array) $post);
        } elseif(is_search()){
            $title = seo_replace($seo['search_title'], (array) $post);
            $description = seo_replace($seo['search_title'], (array) $post);
            $keyword = seo_replace($seo['keyword_search'], (array) $post);
        } elseif(is_404()){
            $title = seo_replace($seo['404_title'], (array) $post);
            $description = seo_replace($seo['404_desc'], (array) $post);
            $keyword = seo_replace($seo['keyword_404'], (array) $post);
        } else {
            $seo_option = get_post_meta($post->ID, 'seo_options', true);
            if(isset($seo_option['seo_title']) && $seo_option['seo_title']!='') {
                $title = seo_replace($seo_option['seo_title'], (array) $post);
            } else {
                if(is_page()) {
                    $title = seo_replace($seo['page_title'], (array) $post);
                } elseif(is_singular('portfolios')){
                    $title = seo_replace($seo['portfolio_title'], (array) $post);
                } elseif(is_single()){
                    $title = seo_replace($seo['post_title'], (array) $post);
                }
            }
            if(isset($seo_option['seo_desc']) && $seo_option['seo_desc']!='') {
                $description = seo_replace($seo_option['seo_desc'], (array) $post);
            } else {
                if(is_page()) {
                    $description = seo_replace($seo['page_desc'], (array) $post);
                } elseif(is_singular('portfolios')){
                    $description = seo_replace($seo['portfolio_desc'], (array) $post);
                } elseif(is_single()){
                    $description = seo_replace($seo['post_desc'], (array) $post);
                }
            }

            if(is_singular('portfolios')){
                if(!isset($seo_option['seo_keyword']) || $seo_option['seo_keyword'] == "") {
                    if($seo['category_keyword'])
                        $keyword .= seo_replace('[term_title]', (array) $post);
                    if($seo['title_keyword'])
                        $keyword .=  $keyword !='' ? (", ".str_replace(' ', ', ', $post->post_title)) : str_replace(' ', ', ', $post->post_title);
                } else {
                    $keyword = $seo_option['seo_keyword'];
                }
            } elseif(is_single()){
                if(!isset($seo_option['seo_keyword']) || $seo_option['seo_keyword'] == "") {
                    if($seo['category_keyword'])
                        $keyword .= seo_replace('[category]', (array) $post);
                    if($seo['tag_keyword']){
                        $tag = seo_replace('[tag]', (array) $post);
                        $keyword .=  ($keyword!=''&&$tag!='') ? (", ".$tag) : ($keyword=='' ? $tag : "");
                    }
                    if($seo['title_keyword'])
                        $keyword .=  $keyword !='' ? (", ".str_replace(' ', ', ', $post->post_title)) : str_replace(' ', ', ', $post->post_title);
                } else {
                    $keyword = $seo_option['seo_keyword'];
                }
            } elseif(is_page()){
                if(!isset($seo_option['seo_keyword']) || $seo_option['seo_keyword'] == "") {
                    if($seo['title_keyword'])
                        $keyword .=  $keyword !='' ? (", ".str_replace(' ', ', ', $post->post_title)) : str_replace(' ', ', ', $post->post_title);
                } else {
                    $keyword = $seo_option['seo_keyword'];
                }
            }
        }
              print "<title>".$title."</title>"; ?>

        <?php print "<meta name=\"description\" content=\"" . $description . "\" />"; ?>

        <?php print "<meta name=\"keywords\" content=\"" . $keyword . "\" />\n";
    } else {
        print "<title>";
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'themeton'), max( $paged, $page ) );
        print "</title>";
    }
}endif;

if (!function_exists('favicon')) :
function favicon() {
    global $data;
    if (isset($data['favicon']) && $data['favicon'] != '') {
        echo "<link rel=\"shortcut icon\" href=\"" . $data['favicon'] . "\"/>\n";
    } else {
        echo "<link rel=\"shortcut icon\" href=\"".get_template_directory_uri()."/images/favicon.ico\"/>\n";
    }
}endif;

if (!function_exists('meta_robots')) :
function meta_robots() {
    global $seo, $post;
    if(isset($seo['seo_on']) && $seo['seo_on']) {
        if(is_home() || is_front_page()) {
            $seo['home_robot_index'] = $seo['home_robot_index'] ? 'index' : 'noindex';
            $seo['home_robot_follow'] = $seo['home_robot_follow'] ? 'follow' : 'nofollow';
            if ($seo['home_robot_index'] == 'index' && $seo['home_robot_follow'] == 'follow') {
                $indexation = "";
            } else {
                $indexation = $seo['home_robot_index'].",".$seo['home_robot_follow'];
            }
            if(isset($seo['home_robot_other'])){
                foreach($seo['home_robot_other'] as $i => $j){
                    $indexation .= $indexation=="" ? $i : (",".$i);
                }
            }
        } else if(is_single() || is_page()) {
            $seo_option = get_post_meta($post->ID, 'seo_options', true);
            if(isset($seo_option['seo_index']) || isset($seo_option['seo_follow'])) {
                $seo_option['seo_index'] = isset($seo_option['seo_index']) ? 'index' : 'noindex';
                $seo_option['seo_follow'] = isset($seo_option['seo_follow']) ? 'follow' : 'nofollow';
                if ($seo_option['seo_index'] == 'index' && $seo_option['seo_follow'] == 'follow') {
                    $indexation = "";
                } else {
                    $indexation = $seo_option['seo_index'].",".$seo_option['seo_follow'];
                }
            } else {
                $seo['robot_index'] = $seo['robot_index'] ? 'index' : 'noindex';
                $seo['robot_follow'] = $seo['robot_follow'] ? 'follow' : 'nofollow';
                if ($seo['robot_index'] == 'index' && $seo['robot_follow'] == 'follow') {
                    $indexation = "";
                } else {
                    $indexation = $seo['robot_index'].",".$seo['robot_follow'];
                }
            }
            if(isset($seo['robot_other'])){
                foreach($seo['robot_other'] as $i => $j){
                    $indexation .= $indexation=="" ? $i : (",".$i);
                }
            }
        } else {
            $seo['robot_index'] = $seo['robot_index'] ? 'index' : 'noindex';
            $seo['robot_follow'] = $seo['robot_follow'] ? 'follow' : 'nofollow';
            if ($seo['robot_index'] == 'index' && $seo['robot_follow'] == 'follow') {
                $indexation = "";
            } else {
                $indexation = $seo['robot_index'].",".$seo['robot_follow'];
            }
            if(isset($seo['robot_other'])){
                foreach($seo['robot_other'] as $i => $j){
                    $indexation .= $indexation=="" ? $i : (",".$i);
                }
            }
        }
        if($indexation!="")
            print "<meta name=\"robots\" content=\"" . $indexation . "\" />\n";
    }
}endif;


if (!function_exists('navigation')) :
function navigation() {
    $combo = array(
        'theme_location' => 'primary-menu',
        'menu_id' => 'main-menu-mobile',
        'container' => false,
        'items_wrap' => '<select id="%1$s" class="%2$s"><option value="">' . __('Select page', 'themeton') . '</option>%3$s</select>',
        'menu_class' => 'main-menu-mobile',
        'echo' => true,
        'link_after' => '',
        'link_before' => '',
        'fallback_cb' => 'nomobilemenu',
        'after' => '</option>',
        'depth' => 0,
        'walker' => new combonavWalker()
    );
    $mega = array(
        'theme_location' => 'primary-menu',
        'container' => '',
        'depth' => 3,
        'container_class' => 'clearfix',
        'fallback_cb' => 'nomenu',
        'menu_id' => 'menu',
        'menu_class' => 'menu',
        'walker' => new TT_Menu_Frontend()
    );
    wp_nav_menu($combo);
    wp_nav_menu($mega);
}endif;

if (!function_exists('footer_navigation')) :
function footer_navigation() {
    wp_nav_menu(array('theme_location' => 'footer-menu',
        'menu_id' => 'footer-menu',
        'container' => false,
        'menu_class' => '',
        'echo' => true,
        'depth' => 0));
}endif;

if (!function_exists('nomenu')) :
function nomenu() {
    echo "<ul id='menu' class='menu'>"; wp_list_pages('title_li='); echo "</ul>";
}endif;

if (!function_exists('nomobilemenu')) :
function nomobilemenu() {
    global $post; ?>
    <select id="main-menu-mobile" class="main-menu-mobile">
        <option value=""><?php _e('Select page', 'themeton'); ?></option><?php
        query_posts( 'post_type=page' );
        while(have_posts()){the_post();
            echo '<option value="'.get_permalink().'" id="menu-item-mobile-'.$post->ID.'">'.get_the_title().'</option>';
        }
        wp_reset_query(); ?>
    </select>
    <?php
}endif;

if (!function_exists('start_el')) :
class combonavWalker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
        $indent = ( $depth ) ? str_repeat("&nbsp;&nbsp;&nbsp;", $depth) : '';
        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = '';
        $output .= '<option value="' . esc_attr($item->url) . '" id="menu-item-mobile-' . $item->ID . '"' . $value . $class_names . '>';
        if ($depth != 0) {
            //$description = "";
        }
        //$item_output = $args->before;
        $output .= $indent . $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        //$item_output .= $args->after;
        $output .='</option>';
        $item->parent_field = '';
        //$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    function start_lvl(&$output) {}
    function end_lvl(&$output) {}
    function end_el(&$output, $item, $depth) {
        $output .= "\n";
    }
}endif;

if (!function_exists('copyright_text')) :
function copyright_text() {
    print '<div class="copyrights">' . stripslashes(get_the_option('copyrighttext')) . '</div>';
}endif;

if (!function_exists('reset_the_date')) :
function reset_the_date() {
    global $previousday;
    $previousday = '';
}endif;

?>
