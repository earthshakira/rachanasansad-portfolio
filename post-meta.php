<?php global $formatimg, $format, $data;?>
<?php if(!isset($data['comment_like_show']) || $data['comment_like_show']) { ?>
<footer><?php
    if(isset($data['post_comment'])&&$data['post_comment']) {
        if (comments_open ()) {
			if (isset($data['facebook_comment']) && $data['facebook_comment']) {
				//echo "<span >";
				echo '<fb:comments-count href="'.get_permalink().'"></fb:comments-count>';
				//echo "</span>";
			} else {
				$comment_count = get_comments_number('0', '1', '%');
				if ($comment_count == 0) {
					$comment_trans = __('No comment', 'themeton');
				} elseif ($comment_count == 1) {
	//            $comment_trans = __('One comment', 'themeton');
					$comment_trans = __('1 comment', 'themeton');
				} else {
					$comment_trans = $comment_count . ' ' . __('comments', 'themeton');
				} ?>
				<a href="<?php comments_link(); ?>" data-count="<?php echo $comment_count; ?>" title="<?php echo $comment_trans; ?>" class="footer-meta meta-comment"><?php echo $comment_trans; ?></a><?php
			} 
        } 
    }
    
    $lk = get_post_meta($post->ID, 'post_liked', true);
    $lk = ($lk == '')?'0':$lk;
    if (!isset($_COOKIE['liked-' . $post->ID])) { ?>
        <a href="<?php echo home_url(); ?>/?p=<?php print $post->ID ?>" data-count="<?php echo $lk; ?>" class="footer-meta-like meta-like"><?php
    } else {
        print '<a data-count="'.$lk.'" class="footer-meta-like meta-like liked">';
    }
    $lk = get_post_meta($post->ID, 'post_liked', true);
    $lk = ($lk == '')?'0':$lk; 
    echo $lk." ";
    if(intval($lk)>1){_e('likes', 'themeton');}
	else {_e('like', 'themeton');}
    ?></a><?php 
    if(isset($data['frontend_editor_page'])&&$data['frontend_editor_page']!=='no'&&get_current_user_id()==$post->post_author){
        //Edit My Post
        $tmp_txt=__('Edit', 'themeton');
        $editPageURL =get_permalink( $data['frontend_editor_page'] );
        $editPageURL.=strpos($editPageURL, '?')===false?'?':'&';
        $editPageURL.='edit_id='.$post->ID;
        echo'<a class="edit-my-post" href="'.$editPageURL.'" data-text="'.$tmp_txt.'" title="'.$tmp_txt.'">'.$tmp_txt.'</a>';
        //Delete My Post
        $tmp_txt=__('Delete', 'themeton');
        $deletePageURL =home_url();
        $deletePageURL.=strpos($deletePageURL, '?')===false?'?':'&';
        $deletePageURL.='tt_add_post=true&delete=true&post_id='.$post->ID;
        echo'<a class="delete-my-post" href="#" data-text="'.$tmp_txt.'" title="'.$tmp_txt.'" data-href="'.$deletePageURL.'">'.$tmp_txt.'</a>';
    } 
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $favorite = get_user_meta($user_id, 'post_favorite', true);
        $fav_text = __('Favorite', 'themeton');
        if ($favorite != '') {
            if(in_array($post->ID, $favorite)) {
                echo '<a class="favorite-post star-post" href="'. home_url() .'/?p='. $post->ID .'" title="'.$fav_text.'"></a>';
            } else {
                echo '<a class="favorite-post" href="'. home_url() .'/?p='. $post->ID .'" title="'.$fav_text.'"></a>';
            }
        } else {      
            echo '<a class="favorite-post" href="'. home_url() .'/?p='. $post->ID .'" title="'.$fav_text.'"></a>';
        }
    }
    
    
    
    ?>
</footer>
<?php } ?>