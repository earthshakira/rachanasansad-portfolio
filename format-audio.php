<?php
global $featured_image_printed, $post;
$featured_image_printed = true;
?>
<div class="entry-audio clearfix">
    <?php get_format_audio_feature($post->ID); ?>
</div>