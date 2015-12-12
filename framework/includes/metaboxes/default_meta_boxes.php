<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class XacDefaultMetabox {

    var $metaConfiguration = Array(
        'title' => 'Additional options',
        'id' => 'themeton_additional_options',
        'type' => 'page',
        'meta_boxes' => Array()
    );
    var $metaValues;

    function construct($config) {
        $this->metaConfiguration = array_merge($this->metaConfiguration, $config);
        add_action('admin_menu', array(&$this, 'init'));
        add_action('save_post', array(&$this, 'saveMeta'));
    }

    function init() {
        add_meta_box($this->metaConfiguration['id'], __($this->metaConfiguration['title']), array(&$this, 'renderHtml'), $this->metaConfiguration['type'], 'normal');
    }

    function renderHtml() {
        global $post, $shortname;
        $this->metaValues = get_post_meta($post->ID, $this->metaConfiguration['id'], true);
        wp_nonce_field(plugin_basename(__FILE__), $this->metaConfiguration['id'] . '_noncename');
        print '<div class="tt_meta_box">';
        foreach ($this->metaConfiguration['meta_boxes'] as $metakey => $metabox) {
            if (function_exists($shortname . '_metabox_' . $metabox['type'])) {
                call_user_func($shortname . '_metabox_' . $metabox['type']);
            } else {
                print '<div class="tt_meta_option" id="' . $metakey . '" rel="';
                if (isset($metabox['rel'])) {
                    print $metabox['rel'];
                } else {
                    print '';
                }
                print '">';
                print '<div class="caption">' . $metabox['title'] . '</div>';
                if (method_exists($this, 'render_meta_' . $metabox['type'])) {
                    print "<div class='option'>";
                    call_user_func(array(&$this, 'render_meta_' . $metabox['type']), $metabox);
                    print '</div>';
                    print '<div class="desc">' . (isset($metabox['description']) ? $metabox['description'] : '') . '</div><div style="clear:both;"></div>';
                } else {
                    print 'meta not found' . $metabox['type'];
                }
                print '</div>';
            }
        }
        print '</div>';
    }

    function render_meta_sidebar($meta) {
        $sidebars = get_the_option('custom_sidebars');
        if ($sidebars) {
            $sides = split('\|\|', $sidebars);
            $sides = array_merge(array('Default sidebar'), $sides);
            print '<select name="' . $meta['name'] . '">';
            foreach ($sides as $row) {
                if ($row != "") {
                    print '<option value="' . $row . '"';
                    if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']] == $row)
                        print ' selected';
                    print '>' . $row . '</option>';
                }
            }
            print '</select>';
        }
    }

    function render_meta_text($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        print '<input type="text" name="' . $meta['name'] . '" value="' . $value . '" size="45" />';
    }

    function render_meta_separator($meta) {
        print ' <hr>';
    }

    function render_meta_select($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        print '<select name="' . $meta['name'] . '">';
        foreach ($meta['options'] as $meta_options) {
            print '<option value="' . $meta_options . '" ';
            print $meta_options == $value ? 'selected ' : '';
            print '>' . $meta_options . '</option>';
        }
        print '</select>';
    }

    function render_meta_radio($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }

        foreach ($meta['options'] as $meta_options) {
            print '<input type="radio" style="margin-right:5px;" name="' . $meta['name'] . '" value="' . $meta_options . '" ';
            print $meta_options == $value ? 'checked ' : '';
            print '><span style="margin-right:20px;">' . $meta_options . '</span>';
        }
    }

    function render_meta_postselect($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        $posts = get_posts('numberposts=-1');
        print '<select name="' . $meta['name'] . '">';
        foreach ($posts as $post) {
            print '<option value="' . $post->ID . '" ';
            print $post->ID == $value ? 'selected ' : '';
            print '>' . $post->post_title . '</option>';
        }
        print '</select>';
    }

    function render_meta_textarea($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        print '<textarea name="' . $meta['name'] . '" rows="4" cols="45">' . $value . '</textarea>';
    }
    
    function render_meta_selectbox($meta) {
	$meta['options'] = array('true', 'false', 'default');
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
	    $value = $this->metaValues[$meta['name']];
	} else {
	    $value = 'default';
	}
	print '<select class="iphone_select" name="' . $meta['name'] . '">';
	foreach ($meta['options'] as $meta_options) {
	    print '<option value="' . $meta_options . '" ';
	    print $meta_options == $value ? 'selected ' : '';
	    print '>' . $meta_options . '</option>';
	}
	print '</select>';
	print ' <small>' . $meta['description'] . '</small>';
    }

    function render_meta_checkbox($meta) {

        $value = '';
        $klass = (isset($meta['show']) && ($meta['show'] != '')) ? (" class='check-show-hide theme_check_optional' data-show='" . $meta['show'] . "'") : "class='theme_check_optional'";
        if (isset($meta['name']) && isset($this->metaValues[$meta['name']])) {
            if ($this->metaValues[$meta['name']]) {
                if ($this->metaValues[$meta['name']] == 'true')
                    $value = 'checked';
            }
        } else if (isset($meta['std']) && $this->metaValues == '') {
            $value = $meta['std'];
        }
        print '<input type="checkbox" name="' . $meta['name'] . '"' . $klass . ' value="true" ' . $value . ' /></td><td style="float: left;width:570px;">';
    }

    function render_meta_categories($meta) {
        if ($this->metaValues[$meta['name']]) {
            $selected = split(",", $this->metaValues[$meta['name']]);
        }
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC'
        );
        $categories = get_categories($args);
        foreach ($categories as $category) {
            print '<input class="theme_check" type="checkbox" name="' . $meta['name'] . '[]" id="' . $category->cat_ID . '" value="' . $category->name . '" ';
            if ($selected)
                print in_array($category->cat_ID, $selected) ? 'checked' : '';
            print '> ' . $category->name . '<br/>';
        }
    }

    function render_meta_catalogs($meta) {
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC'
        );
        $value = isset($this->metaValues[$meta['name']]) ? $this->metaValues[$meta['name']] : '';
        print '<select name="' . $meta['name'] . '">';
        //$catalogs = get_categories($args);
        $catalogs = get_terms('slidercatalog');
        foreach ($catalogs as $catalog) {
            print '<option value="' . $catalog->term_id . '" ';
            print $catalog->term_id == $value ? 'selected ' : '';
            print '>' . $catalog->name . '</option>';
        }
        print '</select>';
    }

    function render_meta_terms($meta) {
        $taxonomy = $meta['term'];
        if (!$taxonomy) {
            $taxonomy = 'category';
        }
        $args = array('taxonomy' => $taxonomy);
        $tax_terms = get_terms($taxonomy, $args);
        $i = 0;
        foreach ($tax_terms as $tax_term) {
            $tax_checked = '';

            if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
                $checkedValues = (array) $this->metaValues[$meta['name']];
                if (in_array($tax_term->slug, $checkedValues)) {
                    $tax_checked = 'checked';
                }
            }
            //if($tax_term->slug==)
            print '<input id="radio' . $i . '" ' . $tax_checked . ' type="checkbox" name="' . $meta['name'] . '[]" value="' . $tax_term->slug . '"/> <label for="radio' . $i . '" class="white">' . $tax_term->name . '</label><br/>';
            $i++;
        }
    }

    function saveMeta($post_id) {
		global $pagenow;
        if (!isset($_POST[$this->metaConfiguration['id'] . '_noncename']) || !wp_verify_nonce($_POST[$this->metaConfiguration['id'] . '_noncename'], plugin_basename(__FILE__)))
            return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        if (!current_user_can('edit_post', $post_id))
            return $post_id;
        if (isset($_POST['post_type']) && $this->metaConfiguration['type'] == $_POST['post_type'] && ($pagenow == 'post-new.php' OR $pagenow == 'post.php')) {
            $myDefaultMeta = Array();
            foreach ($this->metaConfiguration['meta_boxes'] as $metakey => $metabox) {
                if (isset($_POST[$metabox['name']]))
                    $myDefaultMeta[$metabox['name']] = ($_POST[$metabox['name']]);
            }
            if (get_post_meta($post_id, $this->metaConfiguration['id']) == '') {
                add_post_meta($post_id, $this->metaConfiguration['id'], $myDefaultMeta, true);
            } else {
                update_post_meta($post_id, $this->metaConfiguration['id'], $myDefaultMeta);
            }
        }
    }

    function render_meta_layouts($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        foreach ($meta['options'] as $tt => $opt) {
            echo '<ul class="tt_layout_wrapper">';
            foreach ($opt as $layout_item) {
                $val = str_replace('-', '', $layout_item['value']);
                echo '<li>';
                echo '<div class="layout_item">';
                ?>
                <div class="tt_layout_image">
                    <input type="radio" id="<?php echo $meta['name'] . $val; ?>" name="<?php echo $meta['name']; ?>" value="<?php echo $layout_item['value']; ?>" <?php echo $value == $layout_item['value'] ? 'checked ' : ''; ?>>
                    <img title="<?php echo $layout_item['title']; ?>" src="<?php echo $layout_item['image']; ?>" alt="" <?php echo $value == $layout_item['value'] ? 'class="imgSelected" ' : ''; ?> />
                </div>
                <?php
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
            echo '<div class="clearfix"></div>';
        }
    }

    function render_meta_media($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        print '<input type="text" id="tt_image_' . $meta['name'] . '" name="' . $meta['name'] . '" value="' . $value . '"/>';
        echo '<input type="button" class="button" value="upload" onclick="browseMediaWindow(\'tt_image_' . $meta['name'] . '\');">';
    }

    function render_meta_color($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        print '<input type="text" id="tt_image_' . $meta['name'] . '" name="' . $meta['name'] . '" value="' . $value . '" class="post_colorpicker" style="background-color:' . $value . '"/>';
    }

    function render_meta_pattern($meta) {
        if (isset($this->metaValues[$meta['name']]) && $this->metaValues[$meta['name']]) {
            $value = $this->metaValues[$meta['name']];
        } elseif (isset($meta['std'])) {
            $value = $meta['std'];
        } else {
            $value = '';
        }
        foreach ($meta['options'] as $pattern_item) {
            $val = str_replace('-', '', $pattern_item['value']);
            ?>
            <div class="tt_pattern">
                <input type="radio" id="<?php echo $meta['name'] . $val; ?>" name="<?php echo $meta['name']; ?>" value="<?php echo $pattern_item['image']; ?>" <?php echo $value == $pattern_item['image'] ? 'checked ' : ''; ?>>
                <div style="width:150px;height:122px;background: url(<?php echo $pattern_item['image']; ?>) #FFF;" <?php echo $value == $pattern_item['image'] ? 'class="imgSelected" ' : ''; ?> /></div>
            </div>
            <?php
        }
    }

}
?>
