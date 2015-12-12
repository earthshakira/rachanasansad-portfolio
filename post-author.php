<?php
global $blogConf, $isFavoritePage;
if ($blogConf['hide_author']) { ?>
    <div class="item-author clearfix">
        <div class="panel-outer"><?php
            //$post_author_id=get_query_var('author');
			if($isFavoritePage) {
				$current_user  = wp_get_current_user();
				$post_author_id= $current_user->ID;
				tt_get_user_avatar(false,'70');
				echo '<h3><a href="'.get_author_posts_url($post_author_id).'" title="Posts by admin">';
				echo $current_user->display_name;
				echo "</a></h3>";
			} else {
				$post_author_id=$post->post_author;
				tt_get_user_avatar(get_userdata($post_author_id),'70'); 
				echo "<h3>";
				if (is_author()) the_author(); else the_author_posts_link();
				echo "</h3>";
			}
			?>            
            <p><?php
                $description = get_the_author_meta('description',$post_author_id);
                if ($description != '')
                    echo $description;
                else
                    _e('The author didnt add any Information to his profile yet', 'themeton'); ?>
            </p>
            <ul class="author-meta clearfix"><?php
                echo '<li><span class="author-meta-title">Published:</span> '.count_user_posts( $post_author_id ).' '.__('posts','themeton').'</li>';
                wp_reset_postdata(); 
                
                if(get_the_author_meta('url',$post_author_id)){
                    echo'<li><span class="author-meta-title">'.__('Web:','themeton').'</span> <a href="'.get_the_author_meta('url',$post_author_id).'" target="_blank">'.get_the_author_meta('url',$post_author_id).'</a></li>';
                } ?>
            </ul>
        </div>
        <ul class="social"><?php
            if(get_the_author_meta('fb_url',$post_author_id)){ ?>
                <li class="facebook">
                    <a href="<?php echo get_the_author_meta('fb_url',$post_author_id); ?>" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/facebook-login.png" alt=""></a>
                </li><?php
            }
            if(get_the_author_meta('tw_url',$post_author_id)){ ?>
                <li class="twitter">
                    <a href="<?php echo get_the_author_meta('tw_url',$post_author_id); ?>" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/twitter-login.png" alt=""></a>
                </li><?php
            }
			if(get_the_author_meta('aim',$post_author_id)){ ?>
                <li class="aim">
                    <a href="<?php echo get_the_author_meta('aim',$post_author_id); ?>" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/logo-aim.png" alt=""></a>
                </li><?php
            }
			if(get_the_author_meta('yim',$post_author_id)){ ?>
                <li class="aim">
                    <a href="<?php echo get_the_author_meta('yim',$post_author_id); ?>" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/logo-yim.png" alt=""></a>
                </li><?php
            }
			if(get_the_author_meta('jabber',$post_author_id)){ ?>
                <li class="aim">
                    <a href="<?php echo get_the_author_meta('jabber',$post_author_id); ?>" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/logo-google.png" alt=""></a>
                </li><?php
            }
			?>
        </ul>
    </div><?php
}