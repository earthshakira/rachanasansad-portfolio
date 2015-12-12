<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class twitterwidget extends WP_Widget {

    // var $widget_ops = array( 'classname' => 'TwitterWidget', 'description' => 'An example widget that displays a person\'s name and sex.' );
    // var $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'TwitterWidget-widget' );
    function twitterwidget() {
        $widget_ops = array('classname' => 'twitterwidget', 'description' => 'Your twitter recent post list here.');
        // $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'TwitterWidget-widget');
        // parent::WP_Widget(false, $name = 'WenderHost Subpages', $widget_ops);
        parent::WP_Widget(false, 'recent Twitter posts', $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title']);
        $post_count = $instance['number_posts'];
        $username = $instance['user'];
        if (!$post_count) {
            $post_count = 3;
        }
        $sex = isset($instance['sex']) ? $instance['sex'] : '';
        $show_sex = isset($instance['show_sex']) ? $instance['show_sex'] : false;
?>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.tweet.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".tweet").tweet({
                    join_text: "auto",
                    avatar_size: 40,
                    username: "<?php print $username; ?>",
                    count: <?php print $post_count; ?>,
                    auto_join_text_default: "we said,",
                    auto_join_text_ed: "we",
                    auto_join_text_ing: "we were",
                    auto_join_text_reply: "we replied",
                    auto_join_text_url: "we were checking out",
                    loading_text: "loading tweets..."
                }).ajaxStop(function(){
                    $('.tweet').vTicker({
                        speed: 500,
                        pause: 10000,
                        showItems: 3,
                        animation: 'fade',
                        mousePause: true,
                        height: 0,
                        direction: 'up'
                    });
                });
            });
        </script>

<?php
        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Title of widget (before and after defined by themes). */
        if ($title)
            echo $before_title . $title . $after_title;
        echo '<div class="tweet"></div>';
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['user'] = strip_tags($new_instance['user']);
        $instance['number_posts'] = strip_tags($new_instance['number_posts']);
        //$instance['sex'] = $new_instance['sex'];
        //$instance['show_sex'] = $new_instance['show_sex'];

        return $instance;
    }

    function form($instance) {
        //Output admin widget options form
        extract(shortcode_atts(array(
                    'title' => '',
                    'number_posts' => 3
                        ), $instance));
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"  />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('user'); ?>">User:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" value="<?php echo $instance['user']; ?>"  />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_posts'); ?>">Number of twitter posts to show:</label>
            <input  id="<?php echo $this->get_field_id('number_posts'); ?>" name="<?php echo $this->get_field_name('number_posts'); ?>" value="<?php echo $instance['number_posts']; ?>" size="3"  />
        </p>
<?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget("TwitterWidget");'));
?>
