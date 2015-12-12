<?php
get_template_part('single', 'config');
get_header(); 
global $blogConf, $single_content_type;
if (have_posts ()) { the_post();
    $single_content_type=array('item','meta');
    $format = get_post_format();
    $formatimg = $format == '' ? 'standart' : "format-$format";
    get_template_part('content', 'single');
}
get_footer(); ?>