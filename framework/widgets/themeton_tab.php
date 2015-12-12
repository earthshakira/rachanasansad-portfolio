<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ThemetonTabContainerWidget extends WP_Widget {

    // var $widget_ops = array( 'classname' => 'TwitterWidget', 'description' => 'An example widget that displays a person\'s name and sex.' );
    // var $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'TwitterWidget-widget' );
    function ThemetonTabContainerWidget() {
        $widget_ops = array('classname' => 'ThemetonTabContainerWidget', 'description' => 'Recent posts with Thumbnail.');
        // $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'TwitterWidget-widget');
        // parent::WP_Widget(false, $name = 'WenderHost Subpages', $widget_ops);
        parent::WP_Widget(false, 'Themeton tabbed widgets', $widget_ops);
    }

    function widget($args, $instance) {
        global $shortname;
        global $wp_registered_sidebars;
        extract($args);
        echo $before_widget;
        if($wp_registered_sidebars[$instance['sidebar']]){            
            dynamic_sidebar($instance['sidebar']);
        }
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['sidebar'] = strip_tags($new_instance['sidebar']);

        return $instance;
    }

    function form($instance) {

        //Output admin widget options form
        extract(shortcode_atts(array(
                    'title' => '',
                    'number_posts' => 5,
                    'post_type' => 'post'
                        ), $instance));
        $defaultThemes = Array(Array("name" => 'Thumbnailed posts', 'user_func' => 'post_thumbnailed'),
            Array("name" => 'Default posts', 'user_func' => 'post_nonthumbnailed')
        );
        $themes = apply_filters('tt_recent_posts_widget_theme_list', $defaultThemes);
        $defaultPostTypes = Array(Array("name" => 'Post', 'post_type' => 'post'));
        $custom_widgets = get_the_option('custom_sidebars');
        $custom_widgets = split('\|\|', $custom_widgets);
?>

        <p>
            <label for="<?php echo $this->get_field_id('sidebar'); ?>">Show Sidebar:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('sidebar'); ?>" name="<?php echo $this->get_field_name('sidebar'); ?>">
        <?php
        foreach ($custom_widgets as $custom_widget) {
            if ($custom_widget != "") {
        ?>
                <option value="<?php print $custom_widget ?>" <?php echo selected($instance['sidebar'], $custom_widget); ?>><?php print $custom_widget ?></option>
        <?php
            }
        }
        ?>
    </select>
</p>
<?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("ThemetonTabContainerWidget");'));
?>
