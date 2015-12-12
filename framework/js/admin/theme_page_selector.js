jQuery(document).ready(function() {

/*  COLOR PICKER
 *  --------------------------------------------------------------------------*/
    
    var colors=jQuery('input.post_colorpicker');
    colors.each(function(index){
        var picker=jQuery('<div class="myColorPicker" rel='+jQuery(this).attr('id')+'></div>');

        jQuery(picker).ColorPicker({
            color: jQuery(this).val(),
            onShow: function (colpkr) {
                jQuery(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                jQuery(colpkr).fadeOut(500);
                return false;
            },
            onChange: function (hsb, hex, rgb,el) {
                jQuery('#'+picker.attr('rel')).css('backgroundColor', '#' + hex);
                jQuery('#'+picker.attr('rel')).val('#' +hex);
            }
        });
        jQuery(this).after(picker);
    });

    jQuery(".toggle_title").toggle(
        function(){
            jQuery(this).addClass('toggle_active');
            jQuery(this).siblings('.toggle_content').slideDown("fast");
        },
        function(){
            jQuery(this).removeClass('toggle_active');
            jQuery(this).siblings('.toggle_content').slideUp("fast");
        }
    );

/*  SHORTCODE IMAGE(RADIO) CLICK SHOW & HIDE
 *  --------------------------------------------------------------------------*/
	
    jQuery('.tt-show-hide').click(function() {
        var datashow = jQuery(this).attr('data-show');
        var datahide = jQuery(this).attr('data-hide');
        if(datashow != undefined) {
            jQuery(datashow).slideDown();
        }
        if(datahide != undefined) {
            jQuery(datahide).slideUp();
        }        
    });

/*  IMAGE(RADIO) SELECTOR
 *  --------------------------------------------------------------------------*/

    img_radio();

/*  ADDITIONAL CHECKBOX SHOW HIDE
 *  --------------------------------------------------------------------------*/

    jQuery('.check-show-hide').each(function() {
        var datashow = jQuery(this).attr('data-show');
        var datahide = jQuery(this).attr('data-hide');
        if(jQuery(this).is(':checked')){
            jQuery(datahide).hide();
            jQuery(datashow).show();
        }
        else {
            jQuery(datashow).hide();
            jQuery(datahide).show();
        }
    });
    jQuery('.check-show-hide').change(function() {
        var datashow = jQuery(this).attr('data-show');
        var datahide = jQuery(this).attr('data-hide');
        if(jQuery(this).is(':checked')){
            jQuery(datahide).fadeOut();
            jQuery(datashow).fadeIn();
        }
        else {            
            jQuery(datahide).fadeIn();
            jQuery(datashow).fadeOut();
        }
    });

/*  SELECTION OF ADDITIONAL OPTION
 *  --------------------------------------------------------------------------*/
    jQuery('#themeton_additional_options .form-table').each(function(){

        var selector1 = "#style_shortcodeColumns0";
        var selector3 = "#style_shortcodecallout0";
        var class4 = ".Columns1, .column_content";
        var class6 = ".icon_size";
        var class7 = ".styledvideo";
        var class8 = ".recentpostsScroll, .recentposttext";
        var class9 = ".call-button";
        var class10 = ".call-title";
        
        var father_selector = "#shortcode_selector";
        jQuery(father_selector).bind('change', function(){
            if(jQuery(father_selector).val() == "columns") {
                jQuery(class4).slideUp();
            }
            else if(jQuery(father_selector).val() == "service") {
                jQuery(class6).slideUp();
            }
            else if(jQuery(father_selector).val() == "video") {
                jQuery(class7).slideUp();
            }
            else if(jQuery(father_selector).val() == "recent_post") {
                jQuery(class8).slideUp();
            }
            else if(jQuery(father_selector).val() == "callout") {
                jQuery(class10).slideUp();
            }
        });
        
        /* --------------------------------- COLUMNS ----------------------------------- */

        var template1 = jQuery(selector1).val();
        jQuery(selector1).bind('change', function(){
            var template1 = jQuery(selector1).val();
            if(template1=='2 columns' || template1=='3 columns' || template1=='4 columns' || template1=='5 columns' || template1=='6 columns'){
                jQuery(class4).slideUp();
            } else {
                jQuery(class4).slideDown();
            }
        });

        /* ---------------------------------- CALLOUT ---------------------------------- */

        var template3 = jQuery(selector3).val();
        jQuery(selector3).bind('change', function(){
            var template3 = jQuery(selector3).val();
            if(template3=='content and button'){
                jQuery(class9).slideDown();
                jQuery(class10).slideUp();
            } else if(template3=='content and title'){
                jQuery(class10).slideDown();
                jQuery(class9).slideUp();
            } else {
                jQuery(class9+', '+class10).slideDown();
            } 
        })
    });
	
		jQuery('input:radio[name="custom_layout"]').each(function(){
            if(jQuery(this).attr('checked') == 'checked') {
                if(jQuery(this).val() == '0-1-1')
                    jQuery('#custom_sidebar').slideUp();
                else
                    jQuery('#custom_sidebar').slideDown();
            }
        });
    
        jQuery('input:radio[name="custom_layout"]').next('img').click(function(){
            if(jQuery(this).prev().val() == '0-1-1')
                jQuery('#custom_sidebar').slideUp();
            else
                jQuery('#custom_sidebar').slideDown();
        });
    
});
    