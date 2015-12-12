<?php
global $data, $blogConf, $formatimg, $format, $query, $isBlog, $query_string, $customlink, $isFavoritePage, $isDraftPage, $is_featured_post;

$order_type = isset($data['order_type']) ? ($data['order_type'] != 'Date' ? $data['order_type'] : 'Date') : 'Date';
if (isset($isBlog) && $isBlog == true && isset($blogConf['order_type']))
    $order_type = $blogConf['order_type'];
$order = "";
if ($order_type != 'Date') {
    if ($order_type == 'Date ASC')
        $order = '&order=ASC';
    elseif ($order_type == 'Title')
        $order = '&orderby=title&order=DESC';
    elseif ($order_type == 'Title ASC')
        $order = '&orderby=title&order=ASC';
    elseif ($order_type == 'Random')
        $order = '&orderby=rand';
    elseif ($order_type == 'Most liked')
        $order = '&meta_key=post_liked&orderby=meta_value_num';
}
$post_status = "";
if (is_author() && is_user_logged_in()) {
    $post_status = "&post_status=publish,pending,draft";
}

if (isset($isBlog) && $isBlog == true) {
    query_posts($query . $order);
} elseif($isDraftPage){
    $current_user = wp_get_current_user();
    if ( 0 != $current_user->ID ) {
	query_posts(
            array(
                'author' => $current_user->ID,
                'post_status' => 'draft',
                'ignore_sticky_posts' => 1
            )
	);
    }
} elseif ($isFavoritePage) {
    $favorite = get_user_meta(get_current_user_id(), 'post_favorite');
	if(!empty($favorite[0]))
		query_posts(array('post__in' => $favorite[0], 'ignore_sticky_posts' => 1));
} else {
    query_posts($query_string . $order . $post_status);
}

$sequence = 0;

if (have_posts()) {
    while (have_posts()) : the_post();
        $sequence++;
        if (is_author() && is_user_logged_in() && ($post->post_status === 'pending' || $post->post_status === 'draft') && $post->post_author != get_current_user_id()) {
            continue;
        }
        $post_options = get_post_meta($post->ID, 'themeton_additional_options', true);
        ?> <script>console.log('<?php echo json_encode($post_options)?>')</script> <?php
        $format = get_post_format();
        ?> <script>console.log('<?php echo $format?>')</script> <?php

        $formatimg = $format == '' ? 'standart' : "format-$format";
        $no_content = (isset($blogConf['hide_content']) && $blogConf['hide_content'] == true) ? "no-content" : "";
        $color = $class = '';
        if (isset($post_options['custom_bg']) && $post_options['custom_bg']) {
            $color = $post_options['bg_color'];
            if (!empty($color)) {
                $class = "post-" . strtolower(getContrast50($color)) . " ";
                $color = 'style="background-color: ' . $color . '"';
            }
        } else {
            $args = array('orderby' => 'name');
            $terms = wp_get_post_terms($post->ID, 'category', $args);
            $option = get_option("taxonomy_" . $terms[0]->term_id);
            $color = isset($option['bg_color']) ? $option['bg_color'] : '';
            if (!empty($color)) {
                $class = "post-" . strtolower(getContrast50($color)) . " ";
                $color = 'style="background-color: ' . $option['bg_color'] . '"';
            }
        }

        if (isset($post_options['custom_link'])) {
            $customlink['enable'] = 'true';
            $customlink['url'] = $post_options['custom_link_url'];
            if (!preg_match_all('!https?://[\S]+!', $customlink['url'], $matches))
                $customlink['url'] = "http://" . $customlink['url'];
            $customlink['target'] = ' target="' . $post_options['custom_link_target'] . '"';
        } else {
            $customlink['url'] = get_permalink();
            $customlink['enable'] = $customlink['target'] = "";
        }

        $is_featured_post = (isset($post_options['is_featured_post']) && $post_options['is_featured_post']) ? "article-featured" : "";
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class("$is_featured_post $no_content item-hidden item-article item-margin item-not-inited"); ?> data-title="<?php the_title();
        echo " | " . get_option('blogname');
        ?>" data-permalink="<?php the_permalink(); ?>">
            <div <?php echo $color; ?> class="item <?php echo $class;
        echo (get_post_format() === false ? 'standard' : get_post_format()) . '-post';
        ?> <?php
        if (isset($post_options['is_featured_post']) && $post_options['is_featured_post']) {
            echo"item-featured";
        }
        ?>" >

                <div class="item-content"><?php if($format!='quote'){get_template_part('post', 'title');
                }else {
                  echo '<h2 class="item-title"><a class="item-link" style="cursor:default">';
                  the_title();
                  echo '</a></h2>';
                } ?></div>
                <?php get_template_part('post', 'featuredimage'); ?>
                <?php if ($no_content == "") { ?>
                    <div class="item-content">
                    <?php if($format!='quote')get_template_part('post', 'content'); ?>
                    </div>
                    <?php
                    $author = false;
                    if($author) { ?>
                        <div class="item-author-content clearfix"><?php
                            $user_info = get_userdata($post->post_author);
                            $user_id       = $user_info->ID;
                            $user_login    = $user_info->user_login; ?>
                            <div class="author-avatar">
                                <?php echo tt_get_user_avatar( $user_info ); ?>
                            </div>
                            <div class="author-content">
                                <span class="author-name"><?php _e('Author: ', 'themeton'); ?>
                        <?php if (is_author()) the_author(); else the_author_posts_link(); ?>
                                </span>
                                <span class="published-date"><?php echo theme_time_ago(); ?></span>
                            </div>
                        </div>
            <?php } ?>

        <?php } ?>
            </div>
        </article><?php
        if (isset($data['adsense']) && !empty($data['adsense']) && is_array($data['adsense'])) {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $pager = (get_query_var('posts_per_page')) ? get_query_var('posts_per_page') : 10;
            foreach ($data['adsense'] as $ads) {
                if ($ads['position'] == (($paged-1)*$pager+$sequence+1)) {

                    echo '<article id="post-id-'.$sequence.'" class="post adsense type-post status-publish item-article item-margin isotope-item item-hidden ';
                    echo !empty($ads['featured']) ? "article-featured" : "";
                    echo '"';
					echo !(empty($ads['width']) && $ads['width']!='') ? " style='width:".$ads['width']."px;max-width:100%;' " : "";
					echo '>';
                    echo '<div class="item">';
                    echo do_shortcode($ads['title']);
                    echo '</div></article>';
                    break;
                }
            }
        }

    endwhile;
} else {
    ?>
    <div class="header-page">
        <div class="item-single clearfix">
            <div class="entry-content">
    <?php _e("No post", "themeton"); ?>
            </div>
        </div>
    </div>
    <?php
}
