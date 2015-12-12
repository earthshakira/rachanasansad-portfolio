<?php
/*
 * @package themeton
 */
get_template_part('page', 'defaults');
$blogConf['sidebar_position']=' right';
get_header();
global $blogConf; 
$blogConf['title'] = __("Tag", "themeton") . " : " . single_cat_title("", false);
?>
<section id="page" class="clearfix">
    <!-- Start Page -->
    <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
            <?php
            if (have_posts ()) { ?>
                <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?>">
                    <div class="header-page">
                        <h2 class="item-title">
                            <?php print $blogConf['title']; ?>
                        </h2>
                    </div>
                    <div id="masonry" class="<?php if ($data['full_width'] == 'only_grid') echo 'container-fluid'; ?> clearfix">
                        <div class="mansonry-container">
                            <!-- Start Featured Article --><?php
                            if (have_posts ()) the_post();

                            rewind_posts();
                            get_template_part('loop'); ?>
                            <!-- End Featured Article -->
                        </div>
                    </div>
                </div><?php
            } else { ?>
                <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?> content-container">
                    <div class="row">
                        <div class="<?php echo $blogConf['content_span']; ?>">
                            <div class="item-single ">
                                <article class="not-found border">
                                    <div class="not-found-message">
                                        <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themeton'); ?></p>
                                    </div>
                                    <div class="widget_search"><?php get_search_form(); ?></div>
                                </article>
                            </div>
                        </div>
                        <div class="span3"><?php
                            $blogConf['sidebar_position'] = "right";
                            get_sidebar(); ?>
                        </div>
                    </div>
                </div><?php
            } ?>
        <?php if ($data['full_width'] == 'fixed') echo '</div></div>'; ?>
    <?php infiniteScroll(); ?>
    <!-- End Page -->
</section><?php
get_footer(); ?>