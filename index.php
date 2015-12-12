<?php get_template_part('page', 'defaults'); ?>
<?php get_header(); ?>
<!-- Start Page -->
<section id="page" class="clearfix loading">
    <div id="page-container">
        <?php if ($data['full_width'] == 'fixed') echo '<div class="container"><div class="row">'; ?>
            <div class="<?php if ($data['full_width'] == 'fixed') echo 'span12'; ?>">
			
				<?php global $data;
				$show = false;
				if(isset($data['teaser_title']) && $data['teaser_title'] !='')
					$show = true;
				if(isset($data['teaser_text']) && $data['teaser_text'] !='')
					$show = true;
					
				if($show) {
				?>
				<div class="header-page">
					<?php echo (isset($data['teaser_title']) && $data['teaser_title'] !='')? "<h2 class='item-title'>".$data['teaser_title']."</h2>" : ""; ?>
					<?php echo (isset($data['teaser_text']) && $data['teaser_text'] !='')? "<div class='page-teaser'><p>".do_shortcode($data['teaser_text'])."</p></div>" : ""; ?>
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
		<?php if ($data['full_width'] == 'fixed') echo '</div></div>'; ?>			
    </div>
    <?php infiniteScroll(); ?>
</section>
<!-- End Page -->
<?php get_footer(); ?>