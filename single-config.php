<?php

global $post, $blogConf, $data;
$optionss = get_post_meta($post->ID, 'themeton_additional_options', true);
$blogConf['title'] = get_the_title();
if (is_page())
    $blogConf['hide_pagetitle'] = isset($optionss['hide_pagetitle']) ? true : false;
$blogConf['image_hide'] = isset($optionss['image_hide']) ? true : false;
$blogConf['post_meta'] = isset($optionss['post_meta']) ? true : false;
$blogConf['hide_author'] = isset($optionss['meta_author']) ? true : false;
$blogConf['layout'] = isset($optionss['custom_layout']) ? $optionss['custom_layout'] : '1-1-1';
$layout_options = split("-", $blogConf['layout']);
$blogConf['image_height'] = isset($optionss['image_height']) ? $optionss['image_height'] : 200;
$blogConf['image_width'] = 640;
$blogConf['teaser_text'] = isset($optionss['teaser_text']) ? $optionss['teaser_text'] : '';
$blogConf['content_position'] = "";
$blogConf['content_span'] = "span12";
$blogConf['sidebar_span'] = "span3";
if ($layout_options[0] != '0') {
    $blogConf['sidebar_position'] = $layout_options[0] == '2' ? "right" : "left";
    $blogConf['content_position'] = $layout_options[0] == '2' ? "left" : "right";
    $blogConf['content_position'] .= " with-sidebar";
    $blogConf['content_span'] = "span9";
    $blogConf['sidebar_span'] = "span3";
    if (isset($data['sidebar_width']) && $data['sidebar_width'] == "grid4") {
        $blogConf['content_span'] = "span8";
        $blogConf['sidebar_span'] = "span4";
    }
    if (isset($optionss['custom_sidebar'])) {
        $blogConf['sidebar'] = $optionss['custom_sidebar'];
    }
}
$blogConf['hide_author'] = false;
if (!isset($optionss['meta_author']) || $optionss['meta_author'] == 'default') {
    if (!isset($data['about_author_show']) || $data['about_author_show']) {
        $blogConf['hide_author'] = true;
    }
} elseif ($optionss['meta_author'] == 'true') {
    $blogConf['hide_author'] = true;
}
if(is_page()) {
    $blogConf['hide_author'] = false;
    if (!isset($optionss['meta_author']) || $optionss['meta_author'] == 'default') {
        if (isset($data['about_author_show_page']) && $data['about_author_show_page']) {
            $blogConf['hide_author'] = true;
        }
    } elseif ($optionss['meta_author'] == 'true') {
        $blogConf['hide_author'] = true;
    }
}
$blogConf['image_hide'] = false;
if (!isset($optionss['image_hide']) || $optionss['image_hide'] == 'default') {
    if (!isset($data['image_hide']) || $data['image_hide']) {
        $blogConf['image_hide'] = true;
    }
} elseif ($optionss['image_hide'] == 'true') {
    $blogConf['image_hide'] = true;
}
?>