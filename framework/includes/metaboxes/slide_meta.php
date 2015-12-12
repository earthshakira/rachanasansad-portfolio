<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TTSlideMetaBox {

    var $metaConfiguration = Array(
        'name' => 'Slide images1',
        'id' => 'slider_meta_box1',
        'type' => 'post',
        'crop' => true
    );

    function construct($config) {
        $this->metaConfiguration = array_merge($this->metaConfiguration, $config);
        add_action('admin_menu', array(&$this, 'init'));
        add_action('save_post', array(&$this, 'saveMeta'));
    }

    function init() {
        // print $this->metaConfiguration['id'];

        add_meta_box($this->metaConfiguration['id'], __($this->metaConfiguration['name']), array(&$this, 'renderHtml'), $this->metaConfiguration['type'], 'normal');
    }

    function saveMeta($post_id) {
        if (!isset($_POST[$this->metaConfiguration['id'] . '_noncename']) || !wp_verify_nonce($_POST[$this->metaConfiguration['id'] . '_noncename'], plugin_basename(__FILE__)))
            return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        if (!current_user_can('edit_post', $post_id))
            return $post_id;
        if (isset($_POST['post_type']) && $this->metaConfiguration['type'] == $_POST['post_type']) {
            if (isset($_REQUEST['portfolio_slider_type'])) {
                if (get_post_meta($post_id, 'tt_slide_size') == '') {
                    add_post_meta($post_id, 'tt_slide_size', $_REQUEST['portfolio_slider_type'], true);
                } else {
                    update_post_meta($post_id, 'tt_slide_size', $_REQUEST['portfolio_slider_type']);
                }
            }
            if (isset($_POST[$this->metaConfiguration['id'] . '_image_count_value']) && $_POST[$this->metaConfiguration['id'] . '_image_count_value'] != '0' || isset($_POST[$this->metaConfiguration['id'] . '_count_value']) && $_POST[$this->metaConfiguration['id'] . '_count_value'] != '') {
                $counter = intval($_POST[$this->metaConfiguration['id'] . '_count_value']);
                $customSliderMeta = Array();
                for ($i = 0; $i < $counter; $i++) {
                    // $psd = $_POST[$this->metaConfiguration['id']][$i];
                    if ($_POST[$this->metaConfiguration['id']][$i]['image'] != '') {
                        $customSliderMeta[] = Array('image' => stripslashes($_POST[$this->metaConfiguration['id']][$i]['image']), 'image_thumb' => $_POST[$this->metaConfiguration['id']][$i]['image_cropped']);
                    }
                }
                if (get_post_meta($post_id, 'tt_slide_images') == '') {
                    add_post_meta($post_id, 'tt_slide_images', $customSliderMeta, true);
                } else {
                    update_post_meta($post_id, 'tt_slide_images', $customSliderMeta);
                }
            } else {
                delete_post_meta($post_id, 'tt_slide_images');
            }
        }
    }

    function renderHtml() {
        global $post;
        if ($this->metaConfiguration['crop']) {
            if ($this->metaConfiguration['size']['type'] == 'static') {
                ?>
                <table class="form-table">
                    <tr>
                        <th><label>Content width</label></th>
                        <td>
                            <select name="<?php print $this->metaConfiguration['type'] ?>_slider_type" id="<?php print $this->metaConfiguration['type'] ?>_slider_size_type">
                                <?php
                                $size_meta = get_post_meta($post->ID, 'tt_slide_size', true);
                                foreach ($this->metaConfiguration['size']['values'] as $tkey => $tval) {
                                    ?>
                                    <option ratio="<?php print $tval['ratio']; ?>" value="<?php print $tval['value']; ?>" <?php
                    if ($tval['value'] == $size_meta)
                        print 'selected';
                                    ?> ><?php print $tkey; ?></option>
                                        <?php } ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php
            }
        }
        ?>
        <div id="tt_<?php echo $this->metaConfiguration['id']; ?>">
            <?php
            global $post;
            $this->singleHtml(Array());
            $imgs = get_post_meta($post->ID, 'tt_slide_images', true);
            //$imgs = get_post_meta($post->ID, 'tt_slider_elements', true);
            if ($imgs != '')
                foreach ($imgs as $img) {
                    $this->singleHtml($img);
                }
            ?>
        </div>
        <?php if ($this->metaConfiguration['crop']) {
            ?>
            <div id="<?php echo $this->metaConfiguration['id']; ?>_cropper_content" style="display: none;">

                <div class="slide_meta_crop">

                    <input type="hidden" id="solinoo" value="<?php echo $this->metaConfiguration['id'] . '_crop'; ?>">
                    <div>
                        <input id="<?php echo $this->metaConfiguration['id']; ?>_cropper_image_org" type="hidden" value="">
                        <input id="<?php echo $this->metaConfiguration['id']; ?>_cropper_image" type="hidden" value="">
                        <input id="<?php echo $this->metaConfiguration['id']; ?>_cropper_id" type="hidden" value="">
                        <div style="width:100%;height:350px;overflow: auto;" id="<?php echo $this->metaConfiguration['id']; ?>_cropper_image_container">
                        </div>
                    </div>
                    <input type="button" class="edit_button" value="edit">
                    <input type="button" rel="<?php print $post->ID; ?>" class="crop_button" id="ratio_save" value="Crop">

                </div>

            </div>
            <?php
        }
        ?>
        <input type="hidden" id="<?php echo $this->metaConfiguration['id']; ?>_count_value" name="<?php echo $this->metaConfiguration['id']; ?>_count_value">
        <?php
        wp_nonce_field(plugin_basename(__FILE__), $this->metaConfiguration['id'] . '_noncename');
        ?>
        <input type="button" id="<?php echo $this->metaConfiguration['id']; ?>_add_button" class="add:the-list:newmeta button" tabindex="9" value="<?php _e('Add another', 'themeton'); ?>"/>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#tt_<?php print $this->metaConfiguration['id'] ?>').sliderClone({
                    addButton:'#<?php print $this->metaConfiguration['id'] ?>_add_button',
                    slideCounter:'#<?php print $this->metaConfiguration['id'] ?>_count_value',
                    cropperId:'<?php print $this->metaConfiguration['id'] ?>'
                });
            });
        </script>
        <?php
    }

    function singleHtml($option) {
        $cropParams = '';
        if ($this->metaConfiguration['crop']) {
            if (isset($this->metaConfiguration['size']['ratio']) && $this->metaConfiguration['size']['ratio']) {
                $cropParams .=' ratio="' . $this->metaConfiguration['size']['ratio'] . '"';
            }
        }
        
        ?>
        <div class="post-slider-image">
            <div class="removeButton"></div>
            <table class="form-table" width="100%">
                <tr class="featured_image rowitem">
                    <th style="width:15%;"><?php _e('Slide image', 'themeton'); ?></th>
                    <td>
                        <input type="text" name="<?php echo $this->metaConfiguration['id'] ?>[#index#][image]" id="<?php echo $this->metaConfiguration['id'] ?>_#index#_image" value="<?php print isset($option['image']) ? $option['image'] : ''; ?>" size="30" tabindex="30">
                        <input type="hidden" name="<?php echo $this->metaConfiguration['id'] ?>[#index#][image_cropped]" id="<?php echo $this->metaConfiguration['id'] ?>_#index#_image_cropped" value="<?php print isset($option['image_thumb'])?$option['image_thumb']:''; ?>" size="30" tabindex="30">
                        <input class="button" rel="<?php echo $this->metaConfiguration['id'] ?>_#index#_image" type="button" value="<?php _e('Upload', 'themeton'); ?>" >
                        <?php if ($this->metaConfiguration['crop']) {
                            ?>
                            <input class="button slideButtonEdit" rel="<?php echo $this->metaConfiguration['id'] ?>_#index#_image" type="button" <?php echo $cropParams ?> value="Edit/Crop">

                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    function slide_image_crop() {
        global $wpdb;
        $filepath = $_REQUEST['image_org'];
        $myPostId = $_REQUEST['myPost_id'];
        $query = "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' and guid='$filepath'";
        $id = $wpdb->get_var($query);

        if ($id != 0) {
            $img_x = $_REQUEST['image_x'];
            $img_y = $_REQUEST['image_y'];
            $img_w = $_REQUEST['image_w'];
            $img_h = $_REQUEST['image_h'];
            $src_file_path = get_attached_file($id);
            $dist_file = str_replace(basename($src_file_path), 'slider-' . $myPostId . '-' . basename($src_file_path), $src_file_path);
            $ff = wp_crop_image($src_file_path, $img_x, $img_y, $img_w, $img_h, $img_w, $img_h, false, $dist_file);
            if ($ff) {
                $filepath = str_replace(basename($src_file_path), 'slider-' . $myPostId . '-' . basename($src_file_path), $filepath);
                die($filepath);
            }else
                die('0');
        } else {
            die('0');
        }
    }

}