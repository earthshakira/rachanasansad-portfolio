<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryShortCode_VC_Accordion_tab extends WPBakeryShortCode {


    protected function content( $atts, $content = null ) {
        $title = '';

        extract(shortcode_atts(array(
            'title' => __("Section", "js_composer")
        ), $atts));

        $output = '';

        $output .=  '<div class="wpb_accordion_section group">';
        $output .=  '<h3><a href="#">'.$title.'</a></h3>';
        //$output .=  '<div><div class="row-fluid">';
        $output .= '<div>';
        $output .= wpb_js_remove_wpautop($content);
        $output .= '</div>';
        //$output .= '</div></div>';
        $output .= '</div>' . $this->endBlockComment('.wpb_accordion_section');

        return $output;
    }

    public function contentAdmin( $atts, $content = null ) {
        $title = '';
        $defaults = array( 'title' => __('Section', 'js_composer') );
        extract( shortcode_atts( $defaults, $atts ) );

        return '<div class="group">
		<h3><a href="#">'.$title.'</a></h3>
		<div>
			<div class="row-fluid wpb_column_container not-column-inherit">
				'. do_shortcode($content) . WPBakeryVisualComposer::getInstance()->getLayout()->getContainerHelper() . '
			</div>
		</div>
	    </div>';
    }
}

class WPBakeryShortCode_VC_Accordion extends WPBakeryShortCode {

    public function __construct($settings) {
        parent::__construct($settings);
        WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_accordion_tab' ) );
    }

    protected function content( $atts, $content = null ) {
        wp_enqueue_style( 'ui-custom-theme' );
        wp_enqueue_script('jquery-ui-accordion');
        $title = $interval = $width = $el_position = $el_class = '';
        //
        extract(shortcode_atts(array(
            'title' => '',
            'interval' => 0,
            'width' => '1/1',
            'el_position' => '',
            'el_class' => ''
        ), $atts));
        $output = '';

        $el_class = $this->getExtraClass($el_class);
        $width = wpb_translateColumnWidthToSpan($width);

        $output .= '<div class="wpb_accordion wpb_content_element '.$width.$el_class.' not-column-inherit">'; //data-interval="'.$interval.'"
        $output .= '<div class="wpb_wrapper wpb_accordion_wrapper ">';
        $output .= ($title != '' ) ? '<h2 class="wpb_heading wpb_accordion_heading">'.$title.'</h2>' : '';
        $output .= wpb_js_remove_wpautop($content);
        $output .= '</div>'.$this->endBlockComment('.wpb_wrapper');
        $output .= '</div>'.$this->endBlockComment($width);

        //
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function contentAdmin( $atts, $content ) {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                if ( is_string($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = __($param['value'], "js_composer");
                } else {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));

        $output = '';

        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $param_value = '';
            eval("\$param_value = \$".$param['param_name'].";");

            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        //$elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $tmp = '';
        if ( isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '' ) {
            if ( $content != '' ) {
                $custom_markup = str_ireplace("%content%", $tmp.$content, $this->settings["custom_markup"]);
            } else if ( $content == '' && isset($this->settings["default_content"]) && $this->settings["default_content"] != '' ) {
                $custom_markup = str_ireplace("%content%", $this->settings["default_content"], $this->settings["custom_markup"]);
            }
            //$output .= do_shortcode($this->settings["custom_markup"]);
            $iner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $output = $elem;

        return $output;
    }
}