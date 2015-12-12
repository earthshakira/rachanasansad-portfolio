<?php
global $blogConf;
get_template_part('page', 'defaults');

$blogConf['title'] = __("Category", "themeton") . " : " . single_cat_title("", false);
get_header(); ?>
<!-- Start Page -->
<section id="page" class="clearfix">
    <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
            <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?>">
                <div class="header-page">
                    <h2 class="item-title">
                        <?php echo substr($blogConf['title'],11);?>
                    </h2>
                    <div class="page-teaser">
                        <?php echo category_description();?>
                    </div>
                </div>
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
