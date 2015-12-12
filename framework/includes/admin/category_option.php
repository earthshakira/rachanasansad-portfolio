<?php
global $category_options, $post_style;

$category_options = Array(
    'bg_color' => Array('name' => 'bg_color', 'title' => __('Category color', 'themeton'), 'type' => 'color', 'std' => '#FFF', 'description' => __('The background color of posts in this category.', 'themeton')),
);

function category_meta_render_add($metaa) {
    foreach ($metaa as $meta) {
        $value = $meta["std"];
        print '<div class="form-field">';
        print '<label for="term_meta[' . $meta['name'] . ']">' . $meta['title'] . '</label>';
        switch ($meta['type']) {
            case 'color':
                print '<input name="term_meta_' . $meta['name'] . '" class="post_colorpicker" id="term_meta_' . $meta['name'] . '" type="text" value="' . $value . '" style="width:80px;background-color:' . $value . '">';
                break;
            case 'radio':
                foreach ($meta['options'] as $meta_options) {
                    print '<input class="cat-radio" type="radio" name="term_meta[' . $meta['name'] . ']" value="' . $meta_options . '" ';
                    print $meta_options == $value ? 'checked ' : '';
                    print '><label class="cat-label">' . $meta_options . '</label>';
                }
                break;
            case 'radio_image':
                foreach ($meta['options'] as $tt => $opt) {
                    foreach ($opt as $layout_item) {
                        $val = str_replace('-', '', $layout_item['value']);
                        ?>
                        <div class="tt_layout_image">
                            <input type="radio" id="<?php echo $meta['name'] . $val; ?>" name="<?php echo $meta['name']; ?>" value="<?php echo $layout_item['value']; ?>" <?php echo $value == $layout_item['value'] ? 'checked ' : ''; ?>>
                            <img title="<?php echo $layout_item['title']; ?>" src="<?php echo $layout_item['image']; ?>" alt="" <?php echo $value == $layout_item['value'] ? 'class="imgSelected" ' : ''; ?> />
                        </div>
                        <?php
                    }
                }
                break;
        }
        print '<br /><p>' . $meta['description'] . '</p>';
        print '</div>';
    }
}

function category_meta_render_edit($metaa, $t_id) {
    $options = get_option("taxonomy_$t_id");
    foreach ($metaa as $meta) {
        $value = isset($options[$meta['name']]) ? $options[$meta['name']] : '';
        print '<tr class="form-field">';
        print '<th scope="row" valign="top"><label for="term_meta[' . $meta['name'] . ']">' . $meta['title'] . '</label></th>';
        print '<td>';
        switch ($meta['type']) {
            case 'color':
                print '<input name="term_meta_' . $meta['name'] . '" class="post_colorpicker" id="term_meta_' . $meta['name'] . '" type="text" value="' . $value . '" style="width:80px;background-color:' . $value . '">';
                break;
            case 'radio':
                foreach ($meta['options'] as $meta_options) {
                    print '<input class="cat-radio" type="radio" name="term_meta[' . $meta['name'] . ']" value="' . $meta_options . '" ';
                    print $meta_options == $value ? 'checked ' : '';
                    print '><label class="cat-label">' . $meta_options . '</label>';
                }
                break;
            case 'radio_image':
                foreach ($meta['options'] as $tt => $opt) {
                    foreach ($opt as $layout_item) {
                        $val = str_replace('-', '', $layout_item['value']);
                        ?>
                        <div class="tt_layout_image">
                            <input type="radio" id="<?php echo $meta['name'] . "_" . $val; ?>" name="term_meta[<?php echo $meta['name']; ?>]" value="<?php echo $layout_item['value']; ?>" <?php echo $value == $layout_item['value'] ? 'checked ' : ''; ?>>
                            <img title="<?php echo $layout_item['title']; ?>" src="<?php echo $layout_item['image']; ?>" alt="" <?php echo $value == $layout_item['value'] ? 'class="imgSelected" ' : ''; ?> />
                        </div>
                        <?php
                    }
                }
                break;
        }
        print '<br /><span class="description">' . $meta['description'] . '</span>';
        print '</td>';
        print '</tr>';
    }
}

global $taxname;
$taxname = 'category';

add_action($taxname . '_add_form_fields', 'slider_add_fields', 10, 2);
add_action('created_' . $taxname, 'save_extra_taxonomy_fields', 10, 2);
add_action($taxname . '_edit_form_fields', 'slider_edit_fields', 10, 2);
add_action('edited_' . $taxname, 'save_extra_taxonomy_fields', 10, 2);

//add extra fields to custom taxonomy edit form callback function
function slider_add_fields($tag) {
    global $category_options;
    $t_id = isset($tag) && isset($tag->term_id) ? $tag->term_id : '';
    $term_meta = get_option("taxonomy_$t_id");
    category_meta_render_add($category_options, $t_id);
}

//add extra fields to custom taxonomy edit form callback function
function slider_edit_fields($tag) {
    global $category_options;
    $t_id = isset($tag) && isset($tag->term_id) ? $tag->term_id : '';
    $term_meta = get_option("taxonomy_$t_id");
    category_meta_render_edit($category_options, $t_id);
}

// save extra taxonomy fields callback function
function save_extra_taxonomy_fields($term_id) {
    if (isset($_POST['term_meta'])) {
        $t_id = $term_id;
        //$term_meta = get_option("taxonomy_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        if (isset($_POST['term_meta_bg_color'])) {
            $term_meta['bg_color'] = $_POST['term_meta_bg_color'];
        }
        update_option("taxonomy_$t_id", $term_meta);
    } else if (isset($_POST['term_meta_bg_color'])) {
        $term_meta['bg_color'] = $_POST['term_meta_bg_color'];
        update_option("taxonomy_$term_id", $term_meta);
    }
}