<?php global $data, $blogConf; ?>
<section id="page" class="clearfix">
     <?php if (isset($data['full_width']) && $data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
                <div class="<?php if (isset($data['full_width']) && $data['full_width'] == 'fixed') echo 'span12'; ?>">
                <?php if ($blogConf['hide_pagetitle'] || $blogConf['teaser_text'] != "") { ?>
                    <div class="header-page">
                        <?php if ($blogConf['hide_pagetitle']) { ?>
                            <h2 class="item-title">
                                <?php echo $blogConf['title']; ?>
                            </h2>
                            <?php
                        }
                        if ($blogConf['teaser_text'] != "") {
                            ?>
                            <div class="page-teaser">
                                <p><?php echo $blogConf['teaser_text']; ?></p>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if ( is_user_logged_in() ) {
                        $blogConf['hide_author'] = true; ?>
                        <div class="header-page"><?php get_template_part('post', 'author'); ?></div>
                <?php } ?>
                
                <div id="masonry" class="<?php if ($data['full_width'] == 'only_grid') echo 'container-fluid'; ?> clearfix">
                    <div class="mansonry-container">
                        <!-- Start Featured Article -->
                        <?php
                            if ( is_user_logged_in() ) {
                                get_template_part('loop');
                            } else { ?>
                                <div class="header-page content-container">
                                    <div class="item-single clearfix">
                                        <div class="entry-content">
                                                <span style="font-weight: bold"><?php _e('You must be logged in !', 'themeton'); ?></span>
                                                <?php
                                                    if (isset($data['fb_connect']) && $data['fb_connect'] && isset($data['fb_app_id']) && !empty($data['fb_app_id']) && isset($data['fb_app_secret']) && !empty($data['fb_app_secret'])) {
                                                        global $facebook;
                                                    ?><a href="<?php echo $facebook->getLoginUrl(array('scope' => 'email')); ?>" class="fb"><img alt="Login with facebook" src="<?php echo get_template_directory_uri(); ?>/images/facebook-login.png" /></a><?php
                                                    }
                                                    if (isset($data['tw_connect']) && $data['tw_connect'] && isset($data['tw_consumer_key']) && !empty($data['tw_consumer_key']) && isset($data['tw_consumer_secret']) && !empty($data['tw_consumer_secret'])) {
                                                        echo'<a href="' . home_url() . '?tt_tw_redirect=true" class="tw"><img alt="Login with twitter" src="' . get_template_directory_uri() . '/images/twitter-login.png"/></a>';
                                                    }
                                                    
                                                ?>
                                                <br/><br/>
                                                <?php user_login_form(); ?>
                                        </div>
                                    </div>
                                </div>
                        <?php } ?>
                        <!-- End Featured Article -->
                    </div>
                </div>
            </div>
    <?php if (isset($data['full_width']) && $data['full_width'] == 'fixed') echo '</div></div>'; ?>
    <?php infiniteScroll(); ?>
</section>