<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryShortCode_VC_Button extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
        $color = $size = $icon = $target = $href = $el_class = $title = '';
        extract(shortcode_atts(array(
            'color' => 'btn',
            'size' => '',
            'icon' => 'none',
            'target' => '_self',
            'href' => '',
            'el_class' => '',
            'title' => __('Text on the button', "js_composer")
        ), $atts));
        $output = '';
        $a_class = '';

        if ( $el_class != '' ) {
            $tmp_class = explode(" ", $el_class);
            if ( in_array("prettyphoto", $tmp_class) ) {
                wp_enqueue_script( 'prettyphoto' );
                wp_enqueue_style( 'prettyphoto' );
                $a_class .= ' prettyphoto'; $el_class = str_ireplace("prettyphoto", "", $el_class);
            }
            if ( in_array("pull-right", $tmp_class) && $href != '' ) { $a_class .= ' pull-right'; $el_class = str_ireplace("pull-right", "", $el_class); }
            if ( in_array("pull-left", $tmp_class) && $href != '' ) { $a_class .= ' pull-left'; $el_class = str_ireplace("pull-left", "", $el_class); }
        }

        if ( $target == 'same' || $target == '_self' ) { $target = ''; }
        $target = ( $target != '' ) ? ' target="'.$target.'"' : '';

        $color = ( $color != '' ) ? ' '.$color : '';
        $size = ( $size != '' ) ? ' '.$size : '';
        $icon = ( $icon != '' && $icon != 'none' ) ? ' '.$icon : '';
        $i_icon = ( $icon != '' ) ? ' <i class="icon"> </i>' : '';

        $el_class = $this->getExtraClass($el_class);

        $output .= '<button class="btn wpb_button '.$color.$size.$icon.$el_class.'">'.$title.$i_icon.'</button>';

        if ( $href != '' ) {
            $output = '<a class="wpb_button_a'.$a_class.'" title="'.$title.'" href="'.$href.'"'.$target.'>' . $output . '</a>';
        }

        return $output . $this->endBlockComment('button') . "&nbsp;";
    }
}

class WPBakeryShortCode_VC_Cta_button extends WPBakeryShortCode {

    protected function content( $atts, $content = null ) {
        $color = $icon = $size = $target = $href = $title = $call_text = $position = $el_class = '';
        extract(shortcode_atts(array(
            'color' => 'btn',
            'icon' => 'none',
            'size' => '',
            'target' => '',
            'href' => '',
            'title' => __('Text on the button', "js_composer"),
            'call_text' => '',
            'position' => 'cta_align_right',
            'el_class' => '',
            'show_button' => 'show',
            'button_margin_top' => '0'
        ), $atts));
        $output = '';

        $el_class = $this->getExtraClass($el_class);

        if ( $target == 'same' || $target == '_self' ) { $target = ''; }
        if ( $target != '' ) { $target = ' target="'.$target.'"'; }

        $icon = ( $icon != '' && $icon != 'none' ) ? ' '.$icon : '';
        $i_icon = ( $icon != '' ) ? ' <i class="icon"> </i>' : '';
        $size = ( $size != '' ) ? ' '.$size : '';

        $a_class = '';
        if ( $el_class != '' ) {
            $tmp_class = explode(" ", $el_class);
            if ( in_array("prettyphoto", $tmp_class) ) {
                wp_enqueue_script( 'prettyphoto' );
                wp_enqueue_style( 'prettyphoto' );
                $a_class .= ' prettyphoto'; $el_class = str_ireplace("prettyphoto", "", $el_class);
            }
        }

                
        $button = '<button style="margin-top:'.$button_margin_top.';" class="btn '.$color.$size.$icon.'">'.$title.$i_icon.'</button>';
        if ( $href != '' ) {
            $button = '<a class="wpb_button'.$a_class.'" href="'.$href.'"'.$target.'>' . $button . '</a>';
            $widthConf='';
        }
        if($show_button==='hiden'){
            $button='';
            $widthConf='style="width: 100%;"';
        }

        $output .= '<div class="wpb_call_to_action wpb_content_element clearfix '.$position.$el_class.'">';
        if ( $position != 'cta_align_bottom' ) $output .= $button;
        $output .= '<h2 class="wpb_call_text" '.$widthConf.'>'. $call_text . '</h2>';
        if ( $position == 'cta_align_bottom' ) $output .= $button;
        $output .= '</div>' . $this->endBlockComment('.wpb_call_to_action');

        return $output;
    }
}