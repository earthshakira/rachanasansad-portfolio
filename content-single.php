<?php
global $blogConf, $single_content_type, $data, $social_position, $isFrontendEditorPage, $isProfileOptionsPage;
$single_content_type[0] = isset($single_content_type[0]) ? $single_content_type[0] : 'item';
$single_content_type[1] = isset($single_content_type[1]) ? $single_content_type[1] : 'meta';
?>
<!-- Start Page -->
<?php
if (is_single() && isset($data['hide_next_prev_button']) && $data['hide_next_prev_button']) {
    if (!is_mobile()) {
        tt_prev_next_post();
    }
}
?>
<section id="page" class="clearfix content-single">
    <div class="container"><div class="row">
    <div class="<?php if ($data['full_width'] == 'fixed'|| $data['full_width'] == 'only_grid') echo 'span12'; ?> content-container">
		<?php if ($data['full_width'] == 'fixed'|| $data['full_width'] == 'only_grid') echo '<div class="row">'; ?>
            <div class="<?php echo (isset($isFrontendEditorPage) && $isFrontendEditorPage) ? 'span12' : $blogConf['content_span']; ?>">
                <div class="<?php echo $single_content_type[0]; ?>-single item-not-inited clearfix"><?php
    if (isset($isFrontendEditorPage) && $isFrontendEditorPage || isset($isProfileOptionsPage) && $isProfileOptionsPage) {
        if (is_user_logged_in()) {
            if ($isFrontendEditorPage) {
                get_template_part('frontend', 'edit');
            } elseif ($isProfileOptionsPage) {
                get_template_part('frontend', 'profile-option');
            }
        } else {
            _e('You must be logged in !', 'themeton');
        }
    } elseif (is_author()) {
        
    } else {
        if (is_single() || $blogConf['hide_pagetitle'] || (isset($blogConf['teaser_text']) && $blogConf['teaser_text'] != '')) {
            ?>
                            <header id="<?php echo $single_content_type[0]; ?>-single-title-1" class="clearfix"><?php if (is_single() || $blogConf['hide_pagetitle']) { ?>
                                    <h2 class="item-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('%s', 'themeton'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2><?php
                    }
                    if ($single_content_type[1] == 'meta') {
                        if (!isset($blogConf['post_meta']) || !$blogConf['post_meta']) {
                    ?>
                                        <span class="item-single-meta"><a><?php echo theme_time_ago(); ?></a> <?php _e('by', 'themeton'); ?> <?php the_author_posts_link(); ?> <?php _e('in', 'themeton'); ?> <?php tt_get_post_category_list(); ?>
                                            <?php
                                            if (is_user_logged_in()) {
                                                $user_id = get_current_user_id();
                                                $favorite = get_user_meta($user_id, 'post_favorite', true);
                                                $fav_text = __('Favorite', 'themeton');
                                                if ($favorite != '') {
                                                    if (in_array($post->ID, $favorite)) {
                                                        echo '<a class="favorite-post star-post" href="' . home_url() . '/?p=' . $post->ID . '" title="' . $fav_text . '"></a>';
                                                    } else {
                                                        echo '<a class="favorite-post" href="' . home_url() . '/?p=' . $post->ID . '" title="' . $fav_text . '"></a>';
                                                    }
                                                } else {
                                                    echo '<a class="favorite-post" href="' . home_url() . '/?p=' . $post->ID . '" title="' . $fav_text . '"></a>';
                                                }
                                            }
                                            ?>
                                        </span><?php
                        }
                    }
                    if (isset($blogConf['teaser_text']) && $blogConf['teaser_text'] != '') {
                                        ?>
                                    <div class="page-teaser">
                                        <p><?php echo do_shortcode($blogConf['teaser_text']); ?></p>
                                    </div><?php }
                                    ?>
                            </header><?php }
                                ?>
                        <!-- Social Integrate -->
                        <?php if (isset($data['social_position']) && isset($social_position[$data['social_position']]) && $social_position[$data['social_position']] == 'top') get_template_part('post', 'socials'); ?>
                        <section><?php
					if($blogConf['image_hide']) {
							get_template_part('post', 'featuredimage');
					}
                        ?>
                            <div class="item-content">
                                <?php get_template_part('post', 'adsense'); ?>
                                <?php the_content(); ?>
                                <?php get_template_part('post', 'adsense-bottom'); ?>
                                <?php get_template_part('post', 'edit'); ?>
                            </div>
                            <!-- Social Integrate -->
                            <?php if ($social_position[$data['social_position']] != 'top') get_template_part('post', 'socials'); ?>
                            <?php get_template_part('post', 'author'); ?>
                            <?php get_template_part('post', 'comment'); ?>
                        </section><?php }
                        ?>
                </div>
            </div>
            <?php if (!(isset($isFrontendEditorPage) && $isFrontendEditorPage)) { ?>
                <div class="<?php echo $blogConf['sidebar_span']; ?>">
                    <?php get_sidebar(); ?>
                </div>
            <?php } ?>
		<?php if ($data['full_width'] == 'fixed'|| $data['full_width'] == 'only_grid') echo '</div>'; ?>
    </div>
    </div></div>
</section>
<!-- End Page -->