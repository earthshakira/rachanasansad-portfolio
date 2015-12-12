<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function show_clean_custom_options($widget, $return, $instance) {
    $pages = get_posts(array('post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 999, 'orderby' => 'title', 'order' => 'ASC'));
    $cats = get_categories();
    $page_types = array('front' => 'Front', 'home' => 'Blog', 'archive' => 'Archives', 'single' => 'Single Post', '404' => '404', 'search' => 'Search');
    $customFonts = get_option('custom_cufon_fonts');
?>
       
<p>
    <label for="<?php echo $widget->get_field_id('themeton_widget_showhide'); ?>"><?php _e('Show/hide widget', 'themeton') ?></label>
    <select name="<?php echo $widget->get_field_name('themeton_widget_showhide'); ?>" id="<?php echo $widget->get_field_id('themeton_widget_showhide'); ?>">
        <option value="0" <?php echo isset($instance['themeton_widget_showhide']) ? selected($instance['themeton_widget_showhide'], '0') : '0'; ?>>Hide on selected</option>
        <option value="1" <?php echo isset($instance['themeton_widget_showhide']) ? selected($instance['themeton_widget_showhide'], '1') : '1'; ?>>Show on selected</option>
    </select>

</p>

<p><b>Pages</b>
</p>
<select name="<?php echo $widget->get_field_name('themeton_widget_showhide_values'); ?>[]" style="height: auto;" multiple="multiple" size="10">
    <option value="0">None</option>
    <optgroup label="Miscellaneous">
        <?php foreach ($page_types as $key => $label) {
        ?>
            <option value="pagetype-<?php print $key; ?>" <?php
            if (isset($instance['themeton_widget_showhide_values']) && $instance['themeton_widget_showhide_values'] != '')
                if (in_array('pagetype-' . $key, $instance['themeton_widget_showhide_values']))
                    print 'selected="selected"';
        ?>><?php echo $label . ' ' . __('Page', 'themeton'); ?></option>
                <?php } ?>
    </optgroup>
    <optgroup label="Pages">
        <?php foreach ($pages as $page) {
        ?>
                <option value="page-<?php print $page->ID; ?>" <?php
                if (isset($instance['themeton_widget_showhide_values']) && $instance['themeton_widget_showhide_values'] != '')
                    if (in_array('page-' . $page->ID, $instance['themeton_widget_showhide_values']))
                        print 'selected="selected"';
        ?>><?php print $page->post_title; ?></option>
                <?php } ?>
    </optgroup>
    <optgroup label="Categories">
        <?php foreach ($cats as $cat) {
        ?>
                <option value="cat-<?php print $cat->cat_ID; ?>" <?php
                if (isset($instance['themeton_widget_showhide_values']) && $instance['themeton_widget_showhide_values'] != '')
                    if (in_array('cat-' . $cat->cat_ID, $instance['themeton_widget_showhide_values']))
                        print 'selected="selected"'; ?>><?php print $cat->cat_name; ?></option>
                <?php } ?>
    </optgroup>
</select>


<?php
        }

        function clean_custom_widget_show($instance, $widget, $args) {
	    wp_reset_query();
            $isShow = false;
            if (isset($instance['themeton_widget_showhide_values']) && $instance['themeton_widget_showhide_values'] != '') {
                if (is_home ())
                    $isShow = in_array('pagetype-home', $instance['themeton_widget_showhide_values']);
                else if (is_front_page ())
                    $isShow = in_array('pagetype-front', $instance['themeton_widget_showhide_values']);
                else if (is_archive ())
                    $isShow = in_array('pagetype-archive', $instance['themeton_widget_showhide_values']);
                else if (is_category ())
                    $isShow = in_array('cat-' . get_query_var('cat'), $instance['themeton_widget_showhide_values']);
                else if (is_404 ())
                    $isShow = in_array('pagetype-404', $instance['themeton_widget_showhide_values']);
                else if (is_search ())
                    $isShow = in_array('pagetype-search', $instance['themeton_widget_showhide_values']);
                else if (is_single ()) {
                    $isShow = in_array('pagetype-single', $instance['themeton_widget_showhide_values']);
                    if (!$isShow)
                        foreach (get_the_category () as $cat) {
                            if ($isShow)
                                continue;
                            $isShow = in_array('cat-' . $cat->cat_ID, $instance['themeton_widget_showhide_values']);
                        }
                }else {
                    global $wp_query;
                    $post_id = $wp_query->get_queried_object_id();
                    $isShow = in_array('page-' . $post_id, $instance['themeton_widget_showhide_values']);
                }
            }
            $titleClass = '';            
            if (isset($instance['themeton_widget_showhide']) && $instance['themeton_widget_showhide'] == '1')
                $isShow = !$isShow;
            if (!$isShow)
                $widget->widget($args, $instance);
            return false;
        }

        function clean_update_widget_options($instance, $new_instance, $old_instance) {
            $instance['themeton_widget_showhide_values'] = $new_instance['themeton_widget_showhide_values'];
            //$instance['title_font'] = $new_instance['title_font'];
            $instance['themeton_widget_showhide'] = $new_instance['themeton_widget_showhide'];
            return $instance;
        }

        add_filter('in_widget_form', 'show_clean_custom_options', 0, 3);
        add_filter('widget_update_callback', 'clean_update_widget_options', 10, 3);
        add_filter('widget_display_callback', 'clean_custom_widget_show', 10, 3);
?>
