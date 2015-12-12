<?php
global $blogConf;
get_template_part('page', 'defaults');
get_header();
the_post(); ?>
<!-- Start Page -->
<section id="page" class="clearfix">
    <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
            <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?>">
                <div class="header-page"><?php get_template_part('post', 'author'); ?></div>
                <div id="masonry" class="<?php if ($data['full_width'] == 'only_grid') echo 'container-fluid'; ?> clearfix">
                    <div class="mansonry-container">
                        <!-- Start Featured Article -->
                            <?php get_template_part('loop');?>
                        <!-- End Featured Article -->
                    </div>
                </div>
            </div>
    <?php if ($data['full_width'] == 'fixed') echo '</div></div>'; ?>
    <?php infiniteScroll(); ?>
</section>
<!-- End Page -->
<?php get_footer(); ?>