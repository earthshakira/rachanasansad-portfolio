<?php
global $featured_image_printed,$optionss, $post;
$featured_image_printed = true;
$url = get_post_meta($post->ID, 'link-url', true);
$target = get_post_meta($post->ID, 'link-target', true);
if(!preg_match_all('!https?://[\S]+!', $url, $matches))
    $url = "http://" . $url;
echo '<h2 class="link-text">'.get_post_meta($post->ID, 'link-title', true).'</h2>';
echo '<a href="' . $url . '" target="_' . $target . '"><span class="sub-title">- ' . $url . '</span></a>';
?>
								