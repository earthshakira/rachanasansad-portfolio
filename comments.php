<?php
global $blogConf, $data;
$faceBookDefaultOption = Array(
    'use' => 'false',
    'appId' => '',
    'per_page' => '5'
);
// Do not delete these lines
if (comments_open ()) { ?>
    <div id="comments" class="<?php //echo $blogConf['content_span']; ?> comment-box border"><?php
        if ($data['facebook_comment']) { ?>
            <div id="fb-root"></div>
            <div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="<?php echo isset($data['comment_perpage']) ? $data['comment_perpage'] : "4"; ?>" data-width="640"></div><?php
        } else {
            if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])){
                die('Please do not load this page directly. Thanks!');
            }
            if (post_password_required ()) { ?>
                <p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'themeton'); ?></p><?php
                return;
            } ?>
            <!-- You can start editing here. --><?php
            if (have_comments ()) { ?>
                <h4 class="comment-box-title">
                    <?php printf(_n(__('One Response to', 'themeton') . ' %2$s', '%1$s ' . __('Responses to', 'themeton') . ' %2$s', get_comments_number()), number_format_i18n(get_comments_number()), '&#8220;' . get_the_title() . '&#8221;'); ?>
                </h4><hr />
                <div class="comment-list">
                    <?php wp_list_comments(array('style' => 'div', 'callback' => 'mytheme_comment')); ?>
                </div><!-- post-comments -->
                <div class="navigation">
                    <div class="left"><?php previous_comments_link() ?></div>
                    <div class="right"><?php next_comments_link() ?></div>
                </div><?php
            }else{ // this is displayed if there are no comments so far
                if (comments_open ()) {
                        //If comments are open, but there are no comments.
                 }else{
                    // comments are closed
                 }
            }
            if(comments_open ()){
                $fields=array();
                $fields[ 'comment_notes_before' ]=$fields[ 'comment_notes_after' ] = '';
                $fields[ 'label_submit' ]=__('Submit Comment', 'themeton');
                $fields[ 'comment_field' ] = 
                    '<div class="control-group overlabel-wrapper">'.
                        '<textarea name="comment" id="comment" class="input-xlarge span5 required" rows="8" tabindex="4"></textarea>'.
                        '<label for="comment" class="overlabel">'.__('Comment', 'themeton').' (*)</label>'.
                    '</div>';
                $fields[ 'title_reply' ] = '<h4 id="reply-title">'.__('Leave a Comment', 'themeton').'</h4><hr />';
                $fields[ 'title_reply_to' ] = '<h4 id="reply-title">'.__('Leave a Reply to %s', 'themeton').'</h4><hr />';                
                $fields[ 'cancel_reply_link' ] = __('Click here to cancel reply.','themeton');
                comment_form($fields);
                
            } // if you delete this the sky will fall on your head
        } ?>
    </div><?php
}