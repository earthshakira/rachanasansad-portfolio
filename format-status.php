<?php
global $featured_image_printed, $blogConf, $post;
$featured_image_printed = true;

echo '<div class="entry-status clearfix">';
echo apply_filters('the_content', get_post_meta($post->ID, 'tt-status-embed', true));
echo '</div>';
?>
