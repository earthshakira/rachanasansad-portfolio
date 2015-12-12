<?php

global $featured_image_printed, $blogConf, $is_featured_post, $data;
$data['item_size'] = !empty($data['item_size']) ? $data['item_size'] : "";
$format = get_post_format();
$videoimg = false;
if(!is_single()&&$format == 'video') {
	if($is_featured_post=="") {
		if($data['item_size']!="large" && $data['item_size']!="x-large") {
			$videoimg = true;
		}
	}
}
if ((false === $format || $format == 'image') || (!is_single() && $videoimg == true)) {
    $permalink = is_single() ? true : false;
    $featured_image_printed = post_image_show_auto_size($permalink);
    if($format == 'video' && $featured_image_printed === false)
    {
        echo '<div class="item-media">';
        get_template_part('format', $format);
        echo '</div>';
    }
} else {
    echo '<div class="item-media">';
    get_template_part('format', $format);
    echo '</div>';
} ?>