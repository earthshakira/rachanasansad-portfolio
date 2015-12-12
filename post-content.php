<?php
global $blogConf, $isBlog;
global $more;
$more = false;

if (isset($blogConf['post_contshow'])&&$blogConf['post_contshow'] != 'Full') {
    if ($blogConf['post_contshow'] != 'Hide') {
	echo '<div class="entry-content">';
	echo '<p>';
	if(has_excerpt()) {
		echo showBrief(strip_shortcodes(get_the_excerpt()), $blogConf['post_contshow']);
	} else {
		echo showBrief(strip_shortcodes(get_the_content(__('Read more', 'themeton'))), $blogConf['post_contshow']);
	}
	echo '</p>';
	echo '</div>'; }
} else if(has_excerpt()) {
    echo '<div class="entry-content">';
            the_excerpt();
    echo '</div>';
} else {
    echo '<div class="entry-content">';
		if (isset($isBlog) && $isBlog == true) 
			the_content(__('Read more', 'themeton')); 
		else 
			echo showBrief(strip_shortcodes(get_the_content()), 20). " <a href='". get_permalink()."'>".__('Read more', 'themeton') ." ...</a>";
    echo '</div>';
}
