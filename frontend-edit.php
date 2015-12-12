<?php 
wp_enqueue_style('thickbox');
wp_enqueue_style('tt-checkbox-iphonestyle', FRAMEWORKURL . '/css/iphone-checkbox.css');
wp_enqueue_style('frontend', FRAMEWORKURL . '/css/frontend.css');
wp_enqueue_script('thickbox');
wp_enqueue_script('tt-checkbox-iphonestyle', FRAMEWORKURL . '/js/iphone-style-checkboxes.js', array('jquery'));

$post_id=(isset($_REQUEST['edit_id'])&&$_REQUEST['edit_id'])?$_REQUEST['edit_id']:0;
$post_title='';
$post_slug='';
$post_image_url='';
$post_content='';
$post_excerpt='';
$post_p_format='standard';
$post_category=array();
global $frontedit, $blogConf;
$frontedit = true;

require_once FRAMEWORKPATH . '/includes/admin/post_format.php';
    
if($post_id){
    query_posts( 'p='.$post_id );
    if( have_posts() ){the_post();
        $post_title=get_the_title();
        $post_slug=$post->post_name;
        $post_image_url=get_post_image();
        $post_content=get_the_content();
        $post_excerpt=has_excerpt()?get_the_excerpt():'';
        if(get_post_format()){$post_p_format=get_post_format();}
        foreach((get_the_category()) as $category){$post_category[]=$category->cat_ID;}
    }
    wp_reset_query();
} ?>
<form method="post" action="#" id="frontend_post_form" data-permalink="<?php the_permalink();?>" data-type="<?php echo $post_id?'edit':'add'; ?>" class="form-horizontal">
    <div class="row-fluid">
        <div class="span9">
			<?php global $data;
			if(!isset($data['front_postformat']) || $data['front_postformat']) { ?>
				<div class="control-group">
					<?php
					$p_format_arr = array('standard');
					if ( current_theme_supports( 'post-formats' ) ) {
						$post_formats = get_theme_support( 'post-formats' );
						if ( is_array( $post_formats[0] ) ) {
							foreach($post_formats[0] as $p_format){
								$p_format_arr[]=$p_format;
							}
						}
					} ?>
					<div class="cf-nav">
						<ul id="post-formats-select" class="clearfix"><?php
							foreach($p_format_arr as $p_format){
								$is_checked=($p_format===$post_p_format)?'checked="checked"':'';                            
								echo'<li><label class="radio"><input type="radio" name="post_format" class="post-format" id="post-format-'.$p_format.'" value="'.$p_format.'" '.$is_checked.'/> '.ucfirst($p_format).'</label></li>';
							} ?>
						</ul>
					</div>
					<?php tt_custom_post_format_box($post_id);?>
				</div>
			<?php } ?>
            
            <div class="control-group">
                <label class="control-label" for="post_title"><?php _e('Title','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="post_title" name="post_title"     value="<?php echo $post_title;    ?>" class="post_title required"     placeholder="Type title">
                </div>
            </div>
            <div class="control-group input-append">
                <label class="control-label" for="post_image_url"><?php _e('Featured image','themeton'); ?></label>
                <div class="controls feature-container">
                    <input type="text" name="post_image_url" value="<?php echo $post_image_url;?>" class="post_image_url" placeholder="Image URL" id="post_image_url" style="margin-right:1px;float:left;"/>
                    <a name="post_image_upload" class="btn" href="#" onclick='browseMediaWindow("post_image_url");' style="float: right;"><?php _e('image upload','themeton');?></a>
                </div>
            </div>
            <div class="control-group input-append">
                <label class="control-label" for="post_excerpt"><?php _e('Excerpt','themeton'); ?></label>
                <div class="controls feature-container">
                    <textarea name="post_excerpt" class="post_excerpt" placeholder="Type Excerpt"><?php echo $post_excerpt; ?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="post_slug"><?php _e('Slug','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="post_slug" name="post_slug"     value="<?php echo $post_slug;    ?>" class="post_slug"     placeholder="Type Slug">
                </div>
            </div>
            <div class="control-group">
                <textarea name="post_content" class="post_content" style="display: none;"><?php echo $post_content; ?></textarea><?php 
                $args = array(
                        'textarea_name' => 'textareaid2',
                        'wpautop' => true,
                        'media_buttons' => true,
                        'editor_class' => 'frontend',
                        'textarea_rows' => 5,
                        'tabindex' => 1,
                );
                wp_editor( '', 'textareaid2', $args ); ?>
            </div>             
            
            <div class="control-group">
                <label class="control-label">
                    <div class="message   hide"></div>
                </label>
                <div class="controls  submit-update-delete-post <?php echo $post_id?'':'hide'; ?>">
                    <input type="submit" name="delete" loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Delete post','themeton'); ?>" class="btn" value="<?php _e('Delete post','themeton'); ?>">
                    <input type="submit" name="update" loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Update post','themeton'); ?>" class="btn" value="<?php _e('Update post','themeton'); ?>">
                </div>
                <div class="controls submit-add-post <?php echo $post_id?'hide':''; ?>">
                    <input type="submit" name="add"    loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Add post','themeton'); ?>"    class="btn" value="<?php _e('Add post','themeton'); ?>">
                </div>
            </div>
        </div>
        <div class="span3">
        	<div class="add-post-category">
	            <ul class="post_category"><?php
					global $data;
	                $args = array(
	                    'type'                     => 'post',
	                    'orderby'                  => 'name',
	                    'order'                    => 'ASC',
	                    'hide_empty'               => 0,
	                );
	                $categories = get_categories( $args );
					if(isset($data['exclude_categories'])) {
						foreach($categories as $category){
							if(!isset($data['exclude_categories'][$category->cat_ID])){
								$is_checked=(in_array($category->cat_ID,$post_category))?'checked="checked"':'';
								echo'<li><label class="checkbox"><input type="checkbox" name="post_category[]" value="'.$category->cat_ID.'" '.$is_checked.'/> '.$category->name.'</label></li>';
							}
						}
					} else {
						foreach($categories as $category){							
							$is_checked=(in_array($category->cat_ID,$post_category))?'checked="checked"':'';
							echo'<li><label class="checkbox"><input type="checkbox" name="post_category[]" value="'.$category->cat_ID.'" '.$is_checked.'/> '.$category->name.'</label></li>';
						}
					} ?>
	            </ul>
	            <input type="hidden" name="post_id" value="<?php echo $post_id?$post_id:''; ?>">
	            <input type="hidden" name="post_type" value="post"/>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.theme_check_optional').iphoneStyle({defaultOffWidth:41, defaultOnWidth:33});
        jQuery('.check-show-hide').change(function() {
            var datashow = jQuery(this).attr('data-show');
            var datahide = jQuery(this).attr('data-hide');
            if(jQuery(this).is(':checked')){jQuery(datahide).fadeOut();jQuery(datashow).fadeIn();}
            else{jQuery(datahide).fadeIn();jQuery(datashow).fadeOut();}
        });
        jQuery('.check-show-hide').change();
    });
    jQuery(window).load(function(){
        // Init Editor content
        var $tt_frnt_cntnt = jQuery('<div />');
        $tt_frnt_cntnt.html(jQuery('#frontend_post_form textarea.post_content').val());
        $tt_frnt_cntnt.find("span[id*=more-]").after("<!--more-->").remove();
        jQuery('#frontend_post_form textarea.post_content').val($tt_frnt_cntnt.html());
        tinyMCE.get('textareaid2').setContent($tt_frnt_cntnt.html());
    });
</script>