<?php
global $blogConf;
get_template_part('page', 'defaults');

if (is_day()) :
    $blogConf['title'] = __('Daily Archives', 'themeton') . ' : ' . get_the_date();
elseif (is_month()) :
    $blogConf['title'] = __('Monthly Archives', 'themeton') . ' : ' . get_the_date('F Y');
elseif (is_year()) :
    $blogConf['title'] = __('Yearly Archives', 'themeton') . ' : ' . get_the_date('Y');
else :
    $blogConf['title'] = __('Blog Archives', 'themeton');
endif;

get_header();
?>
<!-- Start Page -->
<section id="page" class="clearfix">
    <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
        <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?> ">
            <div class="header-page">
                <h2 class="item-title">
                    <script>console.log('<?php echo json_encode($blogConf) ?>')</script>
                    <?php post_type_archive_title();//$blogConf['title']; ?>
                </h2>
            </div>
            <div id="masonry" class="<?php if ($data['full_width'] == 'only_grid') echo 'container-fluid'; ?> clearfix">
                <div class="mansonry-container">
                    <!-- Start Featured Article --><?php
                    if (have_posts())
                        the_post();

                    rewind_posts();
                    get_template_part('loop');
                    ?>
                    <!-- End Featured Article -->
                </div>
            </div>
        </div>
    <?php if ($data['full_width'] == 'fixed') echo '</div></div>'; ?>
    <?php infiniteScroll(); ?>
</section>
<!-- End Page -->
<?php get_footer(); ?>
