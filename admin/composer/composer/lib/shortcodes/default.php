<?php
/**
 */

/* This shortcode is used for columns and text containers output
---------------------------------------------------------- */

class WPBakeryShortCode_VC_Column_text extends WPBakeryShortCode {

    public function content( $atts, $content = null ) {

        $el_class = $width = $el_position = '';

        extract(shortcode_atts(array(
            'el_class' => '',
            'el_position' => '',
            'width' => '1/2'
        ), $atts));

        $output = '';

        $el_class = $this->getExtraClass($el_class);
        $width = wpb_translateColumnWidthToSpan($width);

        $el_class .= ' wpb_text_column';

        $output .= '<div class="wpb_content_element '.$width.$el_class.'">';
        $output .= '<div class="wpb_wrapper">';
        $output .= wpb_js_remove_wpautop($content);
        $output .= '</div>' . $this->endBlockComment('.wpb_wrapper');
        $output .= '</div>' . $this->endBlockComment($width);

        //
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}



class WPBakeryShortCode_VC_Separator extends WPBakeryShortCode {

    protected function content( $atts, $content = null ) {
        $with_line = '';
        extract(shortcode_atts(array(
            'with_line' => '',
        ), $atts));
        $output = '';
        $output .= '<div class="wpb_separator wpb_content_element "></div>'.$this->endBlockComment('separator');
        return $output;
    }
}

class WPBakeryShortCode_VC_Text_separator extends WPBakeryShortCode {

    protected function content( $atts, $content = null ) {
        $title = $title_align = $el_class = '';
        extract(shortcode_atts(array(
            'title' => __("Title", "js_composer"),
            'title_align' => 'separator_align_center',
            'el_class' => ''
        ), $atts));
        $output = '';
        $extra = '';

        $output .= '<div class="vc_text_separator wpb_content_element '.$title_align.' '.$el_class.'"><div>'.$title.'</div></div>'.$this->endBlockComment('separator');

        return $output;
    }
}

class WPBakeryShortCode_VC_Message extends WPBakeryShortCode {

    protected function content( $atts, $content = null ) {
        $color = '';
        extract(shortcode_atts(array(
            'color' => 'alert-info',
        ), $atts));
        $output = '';
        if ($color == "alert-block") $color = "";

        $output .= '<div class="alert wpb_content_element '.$color.'"><div class="messagebox_text">'.wpb_js_remove_wpautop($content).'</div></div>'.$this->endBlockComment('alert box');
        //$output .= '<div class="wpb_vc_messagebox message '.$color.'"><div class="messagebox_text">'.wpb_js_remove_wpautop($content).'</div></div>';
        return $output;
    }
}



class WPBakeryShortCode_VC_Toggle extends WPBakeryShortCode {

    protected function content( $atts, $content = null ) {
        $title = $el_class = $open = null;
        extract(shortcode_atts(array(
            'title' => __("Click to toggle", "js_composer"),
            'el_class' => '',
            'open' => 'false'
        ), $atts));
        $output = '';

        $el_class = $this->getExtraClass($el_class);
        $open = ( $open == 'true' ) ? ' wpb_toggle_title_active' : '';
        $el_class .= ( $open == ' wpb_toggle_title_active' ) ? ' wpb_toggle_open' : '';

        $output .= '<h4 class="wpb_toggle'.$open.'">'.$title.'</h4><div class="wpb_toggle_content'.$el_class.'">'.wpb_js_remove_wpautop($content).'</div>'.$this->endBlockComment('toggle');

        return $output;
    }
}

class WPBakeryShortCode_VC_Widget_sidebar extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
        $el_position = $title = $width = $el_class = $sidebar_id = '';
        extract(shortcode_atts(array(
            'el_position' => '',
            'title' => '',
            'width' => '1/1',
            'el_class' => '',
            'sidebar_id' => ''
        ), $atts));
        if ( $sidebar_id == '' ) return null;

        $output = '';
        $el_class = $this->getExtraClass($el_class);
        $width = wpb_translateColumnWidthToSpan($width);

        ob_start();
        dynamic_sidebar($sidebar_id);
        $sidebar_value = ob_get_contents();
        ob_end_clean();

        $sidebar_value = trim($sidebar_value);
        $sidebar_value = (substr($sidebar_value, 0, 3) == '<li' ) ? '<ul>'.$sidebar_value.'</ul>' : $sidebar_value;
        //
        $output .= '<div class="wpb_widgetised_column wpb_content_element '.$width.$el_class.'">';
        $output .= '<div class="wpb_wrapper">';
        $output .= ($title != '' ) ? '<h2 class="wpb_heading wpb_widgetised_column_heading">'.$title.'</h2>' : '';
        $output .= $sidebar_value;
        $output .= '</div>'.$this->endBlockComment('.wpb_wrapper');
        $output .= '</div>'.$this->endBlockComment($width);
        //
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}