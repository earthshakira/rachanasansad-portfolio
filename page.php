<?php
get_template_part('single', 'config');
get_header();
global $blogConf, $show, $single_content_type, $data, $isFrontendEditorPage, $isProfileOptionsPage, $isFavoritePage, $isDraftPage;
if (have_posts ()) { the_post();
    $isFrontendEditorPage=isset($data['frontend_editor_page'])&&$data['frontend_editor_page']==$post->ID ? true:false;
    $isProfileOptionsPage=isset($data['profile_options_page'])&&$data['profile_options_page']==$post->ID ? true:false;
    $isFavoritePage      =isset($data['favorite_page'])       &&$data['favorite_page']       ==$post->ID ? true:false;
	$isDraftPage      =isset($data['draft_page'])       &&$data['draft_page']       ==$post->ID ? true:false;
    $single_content_type=array('item','teaser');
    if($isFavoritePage || $isDraftPage){get_template_part('frontend','favorite');}
    else{get_template_part('content', 'single');}
}
get_footer();