<?php
/*
 * template name: Blog
 */
global $isBlog;
$isBlog = true;
get_template_part('page', 'config');
get_header();
the_post();
?>
<!-- Start Page -->
<section id="page" class="clearfix loading">
    <div id="page-container">
        <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
            <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?>">
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
                                    <p><?php echo do_shortcode($blogConf['teaser_text']); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (get_the_content() != '' || has_excerpt()) { ?>
                        <div class="content-container hide">
                            <div class="item-single clearfix">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="masonry" class="<?php if ($data['full_width'] == 'only_grid') echo 'container-fluid'; ?> clearfix">
                        <div class="mansonry-container">
                            <!-- Start Featured Article -->
                            <?php get_template_part('loop'); ?>
                            <!-- End Featured Article -->
                        </div>
                    </div>
                </div>
		<?php if ($data['full_width'] == 'fixed') echo '</div></div>'; ?>    </div>
    <?php infiniteScroll(); ?>
</section>
<!-- End Page -->
<?php
get_footer();
?>