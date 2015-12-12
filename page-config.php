<?php

global $post, $blogConf, $query, $paged, $data;

$optionss = get_post_meta($post->ID, 'themeton_additional_options', true);
$blogConf['image_width'] = 580;
$blogConf['image_height'] = isset($optionss['image_height']) ? $optionss['image_height'] : 200;
$blogConf['layout'] = '2-1-1';

$layout_options = split("-", $blogConf['layout']);
$blogConf['column'] = 'sixteen';
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
    if ($data['full_width'] == 'full') {
        $blogConf['sidebar_span'] = "";
    }
    if (isset($optionss['custom_sidebar'])) {
        $blogConf['sidebar'] = $optionss['custom_sidebar'];
    }
}

$blogConf['title'] = $post->post_title;

$blogConf['hide_pagetitle'] = isset($optionss['hide_pagetitle']) ? true : false;
$blogConf['hide_content'] = isset($optionss['hide_content']) ? true : false;
$blogConf['teaser_text'] = $optionss['teaser_text'];
$blogConf['post_contshow'] = isset($optionss['post_contshow']) ? $optionss['post_contshow'] : 'Full';
$blogConf['post_titleshow'] = isset($optionss['post_titleshow']) ? false : true;

$query = "";
$paged = 1;
if (get_query_var('paged')) {
    $paged = get_query_var('paged');
}
if ($paged == 1 && get_query_var('page')) {
    $paged = get_query_var('page');
}

if (isset($optionss['posts_perpage']))
    $query .= "posts_per_page=" . $optionss['posts_perpage'] . "&paged=$paged";
if (isset($optionss['blog_categories'])) {
    $includecats = implode(',', (array) $optionss['blog_categories']);
    $includecats = $includecats ? "&category_name='" . $includecats . "'" : '';
    $query .= $includecats;
}

if (is_page() && isset($optionss['custom_color']))
    $blogConf['custom_color'] = $optionss['custom_color'];
if (is_page() && isset($optionss['body_bgimage']) && $optionss['body_bgimage'] != '') {
    $blogConf['background'] = isset($optionss['body_bgcolor']) ? ($optionss['body_bgcolor'] . ' ') : '';
    $blogConf['background'] .= "url(" . $optionss['body_bgimage'] . ") " . $optionss['body_bgrepeat'] . " " . $optionss['body_bgattachment'] . " " . $optionss['body_bgposition'];
}

// Ordering option
$blogConf['order_type'] = 'Date';
if (isset($optionss['order_type']) && $optionss['order_type'] != 'Date') {
    $blogConf['order_type'] = $optionss['order_type'] != 'Date' ? $optionss['order_type'] : 'Date';
}
?>