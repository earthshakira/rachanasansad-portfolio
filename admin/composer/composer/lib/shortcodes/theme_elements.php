<?php
/**
 * An example of how to write WPBakery Visual Composer custom shortcode
 *
 * To create shortcodes for visual composer you need to complete 2 steps.
 *
 * 1. Create new class which extends WPBakeryShortCode.
 * If you are not familiar with OOP in php, don't worry follow this instruction and we will guide you how to
 * create valid shortcode for visual composer without learning OOP.
 *
 * 2. Need to create configurations by using wpb_map function.
 *
 * Shortcode class.
 * Shortcode class extends WPBakeryShortCode abstract class.
 * Correct name for shortcode class should look like WPBakeryShortCode_YOUR_SHORTCODE_HERE.
 * YOUR_SHORTCODE_HERE must contain only latin letters, numbers and symbol "_".
*/

/**
 * Shortcode class example "Hello World"
 *
 * Lets pretend that we want to create shortcode with this structure: [my_hello_world foo="bar"]Shortcode content here[/my_hello_world]
 */

/* This shortcode is used for tables
---------------------------------------------------------- */
class WPBakeryShortCode_tt_table extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {
        $output = '';
        extract(shortcode_atts(array(
            'table_name' => 'default table',
            'table_content' => '<table><thead><tr><th>#</th><th>First Name</th><th>Last Name</th><th>Username</th></tr></thead><tbody><tr><td>1</td><td>Mark</td><td>Otto</td><td>@mdo</td></tr><tr><td>2</td><td>Jacob</td><td>Thornton</td><td>@fat</td></tr><tr><td>3</td><td>Larry</td><td>the Bird</td><td>@twitter</td></tr></tbody></table>',
            'width' => '1/1',
            'el_class' => '',
            'el_position' =>''
        ), $atts));
        $width = wpb_translateColumnWidthToSpan($width);
        $extra_class=$el_class;
        $el_class="";
        switch($table_name){
            case'striped_table':   $el_class="table-striped";                               break;
            case'bordered_table':  $el_class="table-bordered";                              break;
            case'condensed_table': $el_class="table-condensed";                             break;
            case'combine_them_all':$el_class="table-striped table-bordered table-condensed";break;
        }
        // START - temp bug repair
        $table_content=substr($table_content, strpos($table_content, '<table'));
        // END   - temp bug repair
        $output = str_ireplace('<table>', '<table class="table '.$el_class.'">', $table_content);
        $output = '<div class="'.$width.' '.$extra_class.'">' . $output . '</div>';
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
        $output = $custom_markup = $width = '';
        if ( $content != NULL ) { $content = wpautop(stripslashes($content)); }

        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //var_dump($param['value']);
                if ( isset($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = is_string($param['value']) ? __($param['value'], "js_composer") : $param['value'];
                } else {
                    $shortcode_attributes[$param['param_name']] = '';
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));		


        $output = $this->getElementHolder($width);
        //  START - Customize
        $tt_el_class="";
        switch($table_name){
            case'striped_table':   $tt_el_class="table-striped";                               break;
            case'bordered_table':  $tt_el_class="table-bordered";                              break;
            case'condensed_table': $tt_el_class="table-condensed";                             break;
            case'combine_them_all':$tt_el_class="table-striped table-bordered table-condensed";break;
        }
        //  END - Customize

        // START - temp bug repair
        $table_content=substr($table_content, strpos($table_content, '<table'));
        // END   - temp bug repair
        
        //START - table HTML
        $tmp  = str_ireplace('<table>', '<table class="table '.$tt_el_class.'">', $table_content);
        $iner = '<div class="'.$width.'">' . $tmp . '</div>';
        //END   - table HTML
        foreach ($this->settings['params'] as $param) {
            $param_value = $$param['param_name'];
            //var_dump($param_value);
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        
        $output = str_ireplace('%wpb_element_content%', $iner, $output);
        return $output;
    }
}



/* This shortcode is used for progress bars
---------------------------------------------------------- */
class WPBakeryShortCode_tt_progress extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {
        $output = '';

        extract(shortcode_atts(array(
            'progress_name' => 'basic',
            'progress_size' => '50',
            'width' => '1/1',
            'el_class' => '',
            'el_position' =>''
        ), $atts));
        $width = wpb_translateColumnWidthToSpan($width);

        $extra_class=$el_class;
        $el_class="";
        switch($progress_name){
            case'basic':   $el_class=" progress";                        break;
            case'striped': $el_class=" progress progress-striped";       break;
            case'animated':$el_class=" progress progress-striped active";break;
        }

        $output .= '<div class=" '.$width.$el_class.' '.$extra_class.'" style="min-height: 18px;">
                        <div class="bar" style="width: '.$progress_size.'%;"></div>
                    </div>';
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
         $output = $custom_markup = $width = '';
        if ( $content != NULL ) { $content = wpautop(stripslashes($content)); }

        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //var_dump($param['value']);
                if ( isset($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = is_string($param['value']) ? __($param['value'], "js_composer") : $param['value'];
                } else {
                    $shortcode_attributes[$param['param_name']] = '';
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));		


        $output = $this->getElementHolder($width);
        //  START - Customize
        $tt_el_class="progress";
        switch($progress_name){
            case'striped': $tt_el_class=" progress progress-striped";       break;
            case'animated':$tt_el_class=" progress progress-striped active";break;
        }      
        //  END - Customize

        //START - progress HTML
        $iner .= '<div class=" '.$tt_el_class.'">
                        <div class="bar" style="width: '.$progress_size.'%;"></div>
                    </div>';
        //END   - progress HTML
        foreach ($this->settings['params'] as $param) {
            $param_value = $$param['param_name'];
            //var_dump($param_value);
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        
        $output = str_ireplace('%wpb_element_content%', $iner, $output);
        return $output;
    }
}



/* This shortcode is used for Hero unit
---------------------------------------------------------- */
class WPBakeryShortCode_tt_hero_unit extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {
        $output = '';
        extract(shortcode_atts(array(
            'hero_unit_title' => 'Hello, world!',
            'hero_unit_tagline' => 'This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.',
            'hero_unit_button_text' => 'Learn more',
            'hero_unit_button_url' => '#',
            'width' => '1/1',
            'el_class' =>'',
            'el_position' =>''
        ), $atts));
        $width = wpb_translateColumnWidthToSpan($width);
        $extra_class=$el_class;
        $el_class='hero-unit';
        $output .= '<div class="'.$width.' '.$el_class.'  '.$extra_class.'">
                        <h1>'.$hero_unit_title.'</h1>
                        <p>'.$hero_unit_tagline.'</p>
                        <p>
                            <a href="'.$hero_unit_button_url.'" class="btn btn-primary btn-medium">'.$hero_unit_button_text.'</a>
                        </p>
                    </div>';
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
        $output = $custom_markup = $width = '';
        if ( $content != NULL ) { $content = wpautop(stripslashes($content)); }

        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //var_dump($param['value']);
                if ( isset($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = is_string($param['value']) ? __($param['value'], "js_composer") : $param['value'];
                } else {
                    $shortcode_attributes[$param['param_name']] = '';
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));		


        $output = $this->getElementHolder($width);
        $tt_el_class='hero-unit';
        //START - hero unit HTML
        $iner = '<div class="'.$width.' '.$tt_el_class.'">
                        <h1 class="tt_title">'.$hero_unit_title.'</h1>
                        <p class="tt_tagline">'.$hero_unit_tagline.'</p>
                        <p class="tt_btn">
                            <a href="'.$hero_unit_button_url.'" class="btn btn-primary btn-medium">'.$hero_unit_button_text.'</a>
                        </p>
                    </div>';
        //END   - hero unit HTML
        foreach ($this->settings['params'] as $param) {
            $param_value = $$param['param_name'];
            //var_dump($param_value);
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        
        $output = str_ireplace('%wpb_element_content%', $iner, $output);
        return $output;
    }
}



/* This shortcode is used for popovers
---------------------------------------------------------- */
class WPBakeryShortCode_tt_popover extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {
        $output = '';
        extract(shortcode_atts(array(
            'popover_title' => 'Popover title',
            'popover_content' => 'And here\'s some amazing content. It\'s very engaging. right?',
            'popover_text' => 'hover for popover',
            'width' => '1/1',
            'el_class' =>'',
            'el_position' =>''
        ), $atts));
        $width = wpb_translateColumnWidthToSpan($width);
        $extra_class=$el_class;
        $el_class='well';
        $output .= '<div class="'.$width.' '.$extra_class.'">
                        <a href="#" class="'.$el_class.' btn btn-danger" rel="popover" data-content="'.$popover_content.'" data-original-title="'.$popover_title.'">'.$popover_text.'</a>
                    </div><p style="display:none;"><script>jQuery(".'.$el_class.'").popover();</script></p>';
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
        $output = $custom_markup = $width = '';
        if ( $content != NULL ) { $content = wpautop(stripslashes($content)); }

        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //var_dump($param['value']);
                if ( isset($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = is_string($param['value']) ? __($param['value'], "js_composer") : $param['value'];
                } else {
                    $shortcode_attributes[$param['param_name']] = '';
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));		


        $output = $this->getElementHolderNoElClass($width);
       
        //START - popover HTML
        $tt_el_class='well';
        $iner = '<div class="'.$width.'">
                        <a href="#" class="'.$tt_el_class.' btn btn-danger" rel="popover" data-content="'.$popover_content.'" data-original-title="'.$popover_title.'">'.$popover_text.'</a>
                    </div><script>jQuery(".'.$tt_el_class.'").popover();</script>';
        //END   - popover HTML
        foreach ($this->settings['params'] as $param) {
            $param_value = $$param['param_name'];
            //var_dump($param_value);
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        
        $output = str_ireplace('%wpb_element_content%', $iner, $output);
        return $output;
    }
}



/* This shortcode is used for Tooltips
---------------------------------------------------------- */
class WPBakeryShortCode_tt_tooltip extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {
        $output = '';
        extract(shortcode_atts(array(
            'tooltip_text' => 'Hover here',
            'tooltip_content' => 'Tooltip content',
            'width' => '1/1',
            'el_class' =>'',
            'el_position' =>''
        ), $atts));
        $width = wpb_translateColumnWidthToSpan($width);
        $extra_class=$el_class;
        $el_class='target_tooltip';
        $output .= '<div class="'.$width.' '.$extra_class.'">
                        <a href="#"  class="'.$el_class.'" rel="tooltip" data-original-title="'.$tooltip_content.'">'.$tooltip_text.'</a>
                    </div><p style="display:none;"><script>jQuery(".'.$el_class.'").tooltip();</script></p>';
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
         $output = $custom_markup = $width = '';
        if ( $content != NULL ) { $content = wpautop(stripslashes($content)); }

        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //var_dump($param['value']);
                if ( isset($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = is_string($param['value']) ? __($param['value'], "js_composer") : $param['value'];
                } else {
                    $shortcode_attributes[$param['param_name']] = '';
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));		


        $output = $this->getElementHolderNoElClass($width);
        
        //START - tooltip HTML
        $tt_el_class='target_tooltip';
        $iner = '<div class="'.$width.'">
                        <a href="#"  class="'.$tt_el_class.'" rel="tooltip" data-original-title="'.$tooltip_content.'">'.$tooltip_text.'</a>
                    </div><script>jQuery(".'.$tt_el_class.'").tooltip();</script>';
        //END   - tooltip HTML
        foreach ($this->settings['params'] as $param) {
            $param_value = $$param['param_name'];
            //var_dump($param_value);
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        
        $output = str_ireplace('%wpb_element_content%', $iner, $output);
        return $output;
    }
}



/* This shortcode is used for Spaces
---------------------------------------------------------- */
class WPBakeryShortCode_tt_space extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {
        $output = '';
        extract(shortcode_atts(array(
            'height' => '15px',
            'width' => '1/1',
            'el_class' =>'',
            'el_position' =>''
        ), $atts));
        $width = wpb_translateColumnWidthToSpan($width);
        $extra_class=$el_class;
        $el_class='tt_space';
        $output .= '<div class="'.$extra_class.'"><div style="margin-top:'.$height.';"></div></div>';
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
         $output = $custom_markup = $width = '';
        if ( $content != NULL ) { $content = wpautop(stripslashes($content)); }

        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //var_dump($param['value']);
                if ( isset($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = is_string($param['value']) ? __($param['value'], "js_composer") : $param['value'];
                } else {
                    $shortcode_attributes[$param['param_name']] = '';
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));		


        $output = $this->getElementHolderNoElClass($width);
        
        //START - tooltip HTML
        $tt_el_class='tt_space';
        $iner = '<div class="'.$width.'">
                    <div class="'.$tt_el_class.'" style="margin-top:'.$height.';"></div>
                </div>';
        //END   - tooltip HTML
        foreach ($this->settings['params'] as $param) {
            $param_value = $$param['param_name'];
            //var_dump($param_value);
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        
        $output = str_ireplace('%wpb_element_content%', $iner, $output);
        return $output;
    }
}

/////////////////////////////////////////////////////
//////////////////////// MAP ////////////////////////
/////////////////////////////////////////////////////
/* Table
---------------------------------------------------------- */
wpb_map( array(
    "name"		=> __("Table", "js_composer"),
    "base"		=> "tt_table",
    "wrapper_class"     => "table-wrapper",
    "icon"              => "icon-tt-table",
    "params"            => array(
        array(
            "type" => "dropdown",
            "heading" => __("Select table style", "js_composer"),
            "param_name" => "table_name",
            "value" => array('Default table', 'Striped table', 'Bordered table', 'Condensed table', 'Combine them all'),
            "description" => __('
                <p><b>Default table: </b>No styles, just columns and rows</p>
                <p><b>Striped table: </b>Only horizontal lines between rows</p>
                <p><b>Bordered table: </b>Rounds corners and adds outer border</p>
                <p><b>Condensed table: </b>Adds light gray background color to odd rows (1, 3, 5, etc)</p>
                <p><b>Combine them all!: </b>Cuts vertical padding in half, from 8px to 4px, within all td and th elements</p>
            ', "js_composer")
        ),
        array(
            "type" => "exploded_textarea",
//            "type" => "textarea_html",
            "heading" => __("Table content", "js_composer"),
            "param_name" => "table_content",
            "value" => "<table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                    </tr>
                </tbody>
                </table>",
            "description" => __("Table content.", "js_composer")
        ),
        array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
));
/* Progress bars
---------------------------------------------------------- */
wpb_map( array(
    "name"		=> __("Progress bars", "js_composer"),
    "base"		=> "tt_progress",
    "class"		=> "progress",
    "icon"              => "icon-tt-progress",
    "wrapper_class"     => "progress-wrapper",
    "params"            => array(
        array(
            "type" => "dropdown",
            "heading" => __("Select progress style", "js_composer"),
            "param_name" => "progress_name",
            "value" => array('Basic', 'Striped', 'Animated'),
            "description" => __('
                <p><b>Basic: </b>Default progress bar with a vertical gradient.</p>
                <p><b>Striped: </b>Uses a gradient to create a striped effect (no IE).</p>
                <p><b>Animated: </b>Takes the striped example and animates it (no IE).</p>
            ', "js_composer")
        ), 
        array(
            "type" => "textfield",
            "heading" => __("Progress size", "js_composer"),
            "param_name" => "progress_size",
            "value" => "100",
            "description" => __("Enter progress size. Example: 0-100", "js_composer")
        ),
        array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    ),
    "js_callback" => array("init" => "ttProgressInitCallBack")
));
/* Hero unit
---------------------------------------------------------- */
wpb_map( array(
    "name"		=> __("Hero unit", "js_composer"),
    "base"		=> "tt_hero_unit",
    "class"		=> "hero-unit",
    "icon"              => "icon-tt-hero-unit",
    "wrapper_class"     => "hero-unit-wrapper",
    "params"            => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "js_composer"),
            "param_name" => "hero_unit_title",
            "value" => "Hello, world!",
            "description" => __("Hero unit title", "js_composer")
        ),
        array(
            "type" => "exploded_textarea",
            "heading" => __("Tagline", "js_composer"),
            "param_name" => "hero_unit_tagline",
            "value" => "This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.",
            "description" => __("Tagline", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Button text", "js_composer"),
            "param_name" => "hero_unit_button_text",
            "value" => "Learn more",
            "description" => __("Button text", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Button URL", "js_composer"),
            "param_name" => "hero_unit_button_url",
            "value" => "#",
            "description" => __("Button URL", "js_composer")
        ),
        array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
));
/* Popovers
---------------------------------------------------------- */
wpb_map( array(
    "name"		=> __("Popover", "js_composer"),
    "base"		=> "tt_popover",
    "class"		=> "popover",
    "icon"              => "icon-tt-popover",
    "wrapper_class"     => "popover-wrapper",
    "params"            => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "js_composer"),
            "param_name" => "popover_title",
            "value" => "A Title",
            "description" => __("Popover title", "js_composer")
        ),
        array(
            "type" => "exploded_textarea",
            "heading" => __("Content", "js_composer"),
            "param_name" => "popover_content",
            "value" => "And here's some amazing content. It's very engaging. right?",
            "description" => __("Popover content", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Popover text", "js_composer"),
            "param_name" => "popover_text",
            "value" => "hover for popover",
            "description" => __("Popover text", "js_composer")
        ),
        array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
));
/* Tooltips
---------------------------------------------------------- */
wpb_map( array(
    "name"		=> __("Tooltip", "js_composer"),
    "base"		=> "tt_tooltip",
    "class"		=> "tooltip",
    "icon"              => "icon-tt-tooltip",
    "wrapper_class"     => "tooltip-wrapper",
    "params"            => array(
        array(
            "type" => "textfield",
            "heading" => __("Text", "js_composer"),
            "param_name" => "tooltip_text",
            "value" => "Hover here",
            "description" => __("Tooltip title", "js_composer")
        ),
        array(
            "type" => "exploded_textarea",
            "heading" => __("Content", "js_composer"),
            "param_name" => "tooltip_content",
            "value" => "Tooltip content",
            "description" => __("Tooltip content", "js_composer")
        ),
        array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
));
/* Space
---------------------------------------------------------- */
wpb_map( array(
    "name"		=> __("Space", "js_composer"),
    "base"		=> "tt_space",
    "class"		=> "space",
    "icon"              => "icon-tt-space",
    "wrapper_class"     => "space-wrapper",
    "params"            => array(
        array(
            "type" => "textfield",
            "heading" => __("Space heigh", "js_composer"),
            "param_name" => "height",
            "value" => "15px",
            "description" => __("Enter space height", "js_composer")
        ),
        array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
)); ?>