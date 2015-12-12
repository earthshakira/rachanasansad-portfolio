<?php
global $featured_image_printed, $blogConf, $post;
$featured_image_printed = true; ?>
<div class="item-quote">
    <blockquote>
        <p><?php print get_post_meta($post->ID, 'tt_quote_text', true); ?></p>
        <cite><?php
            $quote_link = get_post_meta($post->ID, 'tt_quote_link', true);
            $quote_target = get_post_meta($post->ID, 'tt_quote_target', true);
            if ($quote_link != '')
                print '<a href="' . $quote_link . '" target="_' . $quote_target . '">';
            print get_post_meta($post->ID, 'tt_quote_author', true);
            if ($quote_link != '')
                print '</a>'; ?>
        </cite>
    </blockquote>
</div>
