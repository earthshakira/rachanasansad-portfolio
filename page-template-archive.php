<?php
/* template name: Archive */ 
get_template_part('single', 'config');

global $blogConf, $isBlog;

get_header();
the_post();
?>
<!-- Start Page -->
<section id="page" class="clearfix">
    <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
            <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?>">
                <div class="header-page archive">
                    <header id="item-single-title" class="clearfix">
                        <h2 class="item-title">
                            <?php the_title();?>
                        </h2>
                        <div class="page-teaser">
                            <p><?php echo $blogConf['teaser_text'];?></p>
                        </div>                
                    </header>
                    <div class="item-author clearfix">
                        <h4><span><?php _e('Last 10 Posts', 'themeton');?></span></h4>
                        <ul>
                            <?php 
                            query_posts('posts_per_page=10');
                            if (have_posts()) {
                                while (have_posts()) : the_post();
                                    echo "<li><a href='".get_permalink()."'>".get_the_title()."</a></li>";
                                endwhile;
                            } 
                            ?>
                        </ul>
                    </div>
                    <div class="item-author clearfix">
                        <h4><span><?php _e('Archives by Month', 'themeton');?></span></h4>
                        <ul>
                            <?php wp_get_archives(array('type' => 'monthly')); ?>
                        </ul>
                    </div>
                    <div class="item-author clearfix">
                        <h4><span><?php _e('Archives by Category', 'themeton');?></span></h4>
                        <ul>
                            <?php wp_list_categories( 'title_li=0' );?>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="masonry" class="<?php if ($data['full_width'] == 'only_grid') echo 'container-fluid'; ?> clearfix">
                    <div class="mansonry-container">
                        <!-- Start Featured Article --><?php
                                        $isBlog = true;
                                        $query = 'post_type=post';
                        if (have_posts ()) the_post();

                        rewind_posts();
                        get_template_part('loop'); ?>
                        <!-- End Featured Article -->
                    </div>
                </div>
            </div>
	<?php if ($data['full_width'] == 'fixed') echo '</div></div>'; ?>
</section>
<!-- End Page -->
<?php get_footer(); ?>