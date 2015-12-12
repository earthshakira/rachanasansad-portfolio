<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TTRecentPostWidget extends WP_Widget {

    function TTRecentPostWidget() {
        $widget_ops = array('classname' => 'TTRecentPostWidget', 'description' => __('Themeton recent posts', 'themeton'));
        parent::WP_Widget(false, __('Themeton recent posts', 'themeton'), $widget_ops);
    }

    function widget($args, $instance) {
        global $postm, $data;
        extract(array(
            'title' => '',
            'theme' => '',
            'number_posts' => 5,
            'date_show' => false,
            'comment_show' => true,
            'post_liked' => false,
            'post_order' => 'latest',
            'post_type' => 'post'
        ));
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $post_count = 5;
        if (isset($instance['number_posts']))
            $post_count = $instance['number_posts'];
        $q['posts_per_page'] = $post_count;
        $cats = (array) $instance['post_category'];
        $q['paged'] = 1;
        $q['post_type'] = $instance['post_type'];
        if (count($cats) > 0) {
            $typ = 'category';
	    if ($instance['post_type'] != 'post')
		$typ = 'catalog';
            $catq = '';
            $sp = '';
            foreach ($cats as $mycat) {
                $catq = $catq . $sp . $mycat;
                $sp = ',';
            }
            $catq = split(',', $catq);
            $q['tax_query'] = Array(Array(
                    'taxonomy' => $typ,
                    'terms' => $catq,
                    'field' => 'id'
                )
            );
        }
        if ($instance['post_order'] == 'commented')
            $q['orderby'] = 'comment_count';
        else if ($instance['post_order'] == 'liked') {
            $q['orderby'] = 'meta_value_num';
            $q['meta_key'] = 'post_liked';
        }
        
        query_posts($q);
        if (isset($instance['theme']) && function_exists('render_post_' . $instance['theme']))
            call_user_func('render_post_' . $instance['theme'], $args, $title);
        else {
            if (isset($before_widget))
                echo $before_widget;
            if ($title != '')
                echo $args['before_title'] . $title . $args['after_title'];
            echo '<ul class="themetonrecentposts">';
            while (have_posts ()) : the_post();
                global $id;
                $instance['theme'] = 'post_thumbnailed';
                $instance['date_show'] = false;
                $instance['comment_show'] = true;
                $instance['post_liked'] = false;
                $liked = get_post_meta($id, 'post_liked', true) ? get_post_meta($id, 'post_liked', true) : 0;
                print '<li class="recent-news-item">';
                if (isset($instance['theme']) && $instance['theme'] == 'post_thumbnailed') {
                    $feat_img = tt_post_image(true, 'thumbnail');
                    if (is_array($feat_img)) {
                        $feat_img = $feat_img[0]['image'];
                    }
                    if ($feat_img == '') {
                        $feat_img = get_template_directory_uri() . "/images/avatar.jpg";
                    }
                    $thumb_width = 52;
                    $thumb_height = 52;
                    if (defined('TT_RECENT_POST_THUMB_SIZE')) {
                        $thumb_width = TT_RECENT_POST_THUMB_SIZE;
                        $thumb_height = TT_RECENT_POST_THUMB_SIZE;
                    }
                    echo '<img style="width:' . $thumb_width . 'px; height:' . $thumb_height . 'px" src="' . $feat_img . '" alt="' . get_the_title() . '"/>';
                }
                echo '<h6><a href="';
                the_permalink();
                echo '">';
                    the_title();
                echo '</a></h6>';
                if ($instance['date_show'])
                    echo '<p class="tab-meta">' . __('posted: ', 'themeton') . get_the_date() . '</p>';
                if ($instance['comment_show']) {
                    echo '<p class="tab-meta">';
					if (isset($data['facebook_comment']) && $data['facebook_comment']) {
						echo '<fb:comments-count href="'.get_permalink().'"></fb:comments-count>'.__(' comments', 'themeton');
					} else {
						comments_number(__('no comments', 'themeton'), __('one comment', 'themeton'), '% ' . __(' comments', 'themeton'));
					}
                    echo ' </p>';
                }
                if ($instance['post_liked'])
                    echo '<p class="tab-meta">' . __('liked: ', 'themeton') . $liked . '</p>';
                //  print '</div>';
                echo '<div class="clearfix"></div>';
                echo '</li>';
            endwhile;
            echo '</ul>';
            if (isset($after_widget))
                echo $after_widget;
        }
        wp_reset_query();
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags($new_instance['title']);

        if ($new_instance['post_type'] == 'post') {
	    $instance['post_category'] = $_REQUEST['post_category'];
	} else {
	    $tax = get_object_taxonomies($new_instance['post_type']);
	    $instance['post_category'] = $_REQUEST['tax_input'][$tax[0]];
	}

        $instance['number_posts'] = strip_tags($new_instance['number_posts']);
        $instance['post_type'] = strip_tags($new_instance['post_type']);
        $instance['post_order'] = strip_tags($new_instance['post_order']);
        //$instance['theme'] = strip_tags($new_instance['theme']);
        $instance['date_show'] = strip_tags($new_instance['date_show']);
        $instance['comment_show'] = strip_tags($new_instance['comment_show']);
        $instance['post_liked'] = strip_tags($new_instance['post_liked']);
        //$instance['sex'] = $new_instance['sex'];
        //$instance['show_sex'] = $new_instance['show_sex'];

        return $instance;
    }

    function form($instance) {

        //Output admin widget options form
        extract(shortcode_atts(array(
                    'title' => '',
                    'theme' => '',
                    'number_posts' => 5,
                    'date_show' => false,
                    'comment_show' => true,
                    'post_liked' => false,
                    'post_order' => 'latest',
                    'post_type' => 'post'
                        ), $instance));
        $defaultThemes = Array(Array("name" => 'Thumbnailed posts', 'user_func' => 'post_thumbnailed'),
            Array("name" => 'Default posts', 'user_func' => 'post_nonthumbnailed')
        );
        $themes = apply_filters('tt_recent_posts_widget_theme_list', $defaultThemes);
        $defaultPostTypes = Array(Array("name" => 'Post', 'post_type' => 'post'));
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>"  />
        </p>

<?php /*
          <p>
          <label for="<?php echo $this->get_field_id('date_show'); ?>">Show Date:</label>
          <input class="checkbox" type="checkbox" <?php checked($date_show, true); ?> value="1"  id="<?php echo $this->get_field_id('date_show'); ?>" name="<?php echo $this->get_field_name('date_show'); ?>"   />
          </p>
          <p>
          <label for="<?php echo $this->get_field_id('comment_show'); ?>">Show comment count:</label>
          <input class="checkbox" type="checkbox" <?php checked($comment_show, true); ?> value="1"  id="<?php echo $this->get_field_id('comment_show'); ?>" name="<?php echo $this->get_field_name('comment_show'); ?>"   />
          </p>
         */ ?>
        <p>
            <label for="<?php echo $this->get_field_id('post_order'); ?>">Post order:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('post_order'); ?>" name="<?php echo $this->get_field_name('post_order'); ?>">
                <option value="latest" <?php if ($post_order == 'latest')
                            print 'selected="selected"'; ?>>Latest posts</option>
                <option value="commented" <?php if ($post_order == 'commented')
                            print 'selected="selected"'; ?>>Most commented posts</option>
                <!--
                <option value="liked" <?php if ($post_order == 'liked')
                            print 'selected="selected"'; ?>>Most liked posts</option>
                -->
            </select>
        </p>

<?php
                $customTypes = apply_filters('tt_recent_posts_widget_type_list', $defaultPostTypes);
                if (count($customTypes) > 0) {
?>
                    <p>
                        <label for="<?php echo $this->get_field_id('post_type'); ?>">Post from:</label>
                <select rel="<?php echo $this->get_field_id('post_cats'); ?>" onChange="tt_get_post_terms(this);" class="widefat" id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
        <?php
                    foreach ($customTypes as $postType) {
        ?>
                        <option value="<?php print $postType['post_type'] ?>" <?php echo selected($post_type, $postType['post_type']); ?>><?php print $postType['name'] ?></option>
        <?php
                    }
        ?>
                </select>
            </p>
<?php
                }
?>

                <p>If you were not selected for cats, it will show all categories.</p>

                <div id="<?php echo $this->get_field_id('post_cats'); ?>" style="height:150px; overflow:auto; border:1px solid #dfdfdf;">

    <?php
                //$post_type='post';
                $tax = get_object_taxonomies($post_type);

                $selctedcat = false;
                if (isset($instance['post_category']) && $instance['post_category'] != '')
                    $selctedcat = $instance['post_category']; wp_terms_checklist(0, array('taxonomy' => $tax[0], 'checked_ontop' => false, 'selected_cats' => $selctedcat));
    ?>

            </div>

            <p>
                <label for="<?php echo $this->get_field_id('number_posts'); ?>">Number of posts to show:</label>
                <input  id="<?php echo $this->get_field_id('number_posts'); ?>" name="<?php echo $this->get_field_name('number_posts'); ?>" value="<?php echo $number_posts; ?>" size="3"  />
                </p>
<?php
            }

        }

        add_action('widgets_init', create_function('', 'return register_widget("TTRecentPostWidget");'));
        add_action('wp_ajax_themeton_recent_post_terms', 'get_post_type_terms');

        function get_post_type_terms() {
            $cat = 'post';
            if (isset($_REQUEST['post_format']) && $_REQUEST['post_format'] != '')
                $cat = $_REQUEST['post_format'];
            $tax = get_object_taxonomies($cat);
            wp_terms_checklist(0, array('taxonomy' => $tax[0], 'checked_ontop' => false, 'selected_cats' => false));
            die;
        }

//        add_filter('tt_recent_posts_widget_type_list', 'post_protfolio_type');
//        add_action('wp_ajax_get_portfolio_terms', 'post_type_portfolio_terms');
//
//        function post_type_portfolio_terms() {
//
//        }
//
//        function post_protfolio_type($types) {
//            $types[] = Array('name' => 'Portpolios', 'post_type' => 'portfolios');
//            return $types;
//        }
?>
