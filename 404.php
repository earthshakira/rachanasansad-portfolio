<?php
/*
 * @package themeton
 */
get_template_part('page', 'defaults');
$blogConf['title'] = __('This is somewhat embarrassing, isn&rsquo;t it?', 'themeton');
get_header();
global $blogConf;
?>
<!-- Start Page -->
<section id="page" class="clearfix">
   <div class="container"><div class="row">
        <div class="span12 content-container">
            <div class="row">
                <div class="<?php echo $blogConf['content_span']; ?>">                
                    <div class="item-single">
                        <div class="not-found border">
                            <h1><?php _e('Not Found', 'themeton'); ?></h1>
                            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'themeton'); ?></p>
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
                <div class="<?php echo $blogConf['sidebar_span']; ?>"><?php
                            $blogConf['sidebar_position'] = "right";
                            get_sidebar();
                            ?>
                </div>
            </div>
        </div>
    </div></div>
</div>
</section>
<!-- End Page --><?php get_footer(); ?>