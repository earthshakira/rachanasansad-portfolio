<?php 
if(is_user_logged_in()){
    $current_user = wp_get_current_user();
    $user_id       =$current_user->ID;
    $user_login    =$current_user->user_login;
    $user_firstname=$current_user->user_firstname;
    $user_lastname =$current_user->user_lastname;
    $user_email    =$current_user->user_email;
    $user_url      =$current_user->user_url;
    $user_aim      =get_the_author_meta('aim',$current_user->ID);
    $user_yim      =get_the_author_meta('yim',$current_user->ID);
    $user_jabber   =get_the_author_meta('jabber',$current_user->ID);
    $user_fb_url   =get_the_author_meta('fb_url',$current_user->ID);
    $user_tw_url   =get_the_author_meta('tw_url',$current_user->ID);
    
    
    
    $user_description=get_the_author_meta('description',$current_user->ID); ?>

        <form method="post" action="#" id="frontend_user_form" class="form-horizontal">
            <div class="control-group author-avatar">
                <label class="control-label"><?php tt_get_user_avatar(); ?></label>
                <div class="controls">
                    <?php if(!get_the_author_meta('social_avatar',$user_id)){ ?>
                        <a target="_blank" href="https://en.gravatar.com/profiles/edit/?noclose#your-images"><span><?php _e('Change Avatar','themeton'); ?></span></a>
                        <span class="help-block">Note: Avatar is auto taken in Gravatar.com. If you insert your registered email in our email section then you uploaded avatar will be displayed in this section.</span>
                    <?php } ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_firstname"><?php _e('First name','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_firstname" name="user_firstname" placeholder="Type first name"   value="<?php echo $user_firstname;  ?>">
                </div>
            </div>
             <div class="control-group">
                <label class="control-label" for="user_lastname"><?php _e('Last name','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_lastname" name="user_lastname"   placeholder="Type last name"    value="<?php echo $user_lastname;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_email"><?php _e('E-mail','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_email" name="user_email"         placeholder="Type e-mail"       value="<?php echo $user_email;  ?>" class="required">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_url"><?php _e('Website','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_url" name="user_url"   placeholder="Type Website"    value="<?php echo $user_url;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_fb_url"><?php _e('Facebook URL','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_fb_url" name="user_fb_url"   placeholder="Type Facebook URL"    value="<?php echo $user_fb_url;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_tw_url"><?php _e('Twitter URL','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_tw_url" name="user_tw_url"   placeholder="Type Twitter URL"    value="<?php echo $user_tw_url;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_aim"><?php _e('AIM','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_aim" name="user_aim"   placeholder="Type AIM"    value="<?php echo $user_aim;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_yim"><?php _e('Yahoo IM','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_yim" name="user_yim"   placeholder="Type Yahoo IM"    value="<?php echo $user_yim;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_jabber"><?php _e('Jabber / Google Talk','themeton'); ?></label>
                <div class="controls">
                    <input type="text" id="user_jabber" name="user_jabber"   placeholder="Type Jabber / Google Talk"    value="<?php echo $user_jabber;  ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php _e('Biographical Info', 'themeton'); ?></label>
                <div class="controls">
                    <textarea name="user_description" cols="30" rows="5" placeholder="Type Biographical Info"><?php echo $user_description;?></textarea>                    
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="new_password"><?php _e('New password','themeton'); ?></label>
                <div class="controls">
                    <input type="password" id="new_password"     name="new_password"     placeholder="Type new password"     value="" autocomplete="off">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="confirm_password"><?php _e('Confirm password','themeton'); ?></label>
                <div class="controls">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Type confirm password" value="" autocomplete="off">
                </div>
            </div>
            
            <input type="hidden" name="user_id"      value="<?php echo $user_id; ?>">
            <div class="control-group">
                <label class="control-label">
                    <div class="message   hide"></div>
                </label>
                <div class="controls">
                    <input type="submit" class="btn" loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Update profile','themeton'); ?>" value="<?php _e('Update profile','themeton'); ?>">
                </div>
            </div>
        </form>
<?php
}else{
    header("location:".home_url());
} ?>