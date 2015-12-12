/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    jQuery('.seo_index').iphoneStyle({
        checkedLabel:      'index',
        uncheckedLabel:    'noindex',
        defaultOffWidth:20,
        defaultOnWidth:71
    });
	jQuery('.seo_follow').iphoneStyle({
        checkedLabel:      'follow',
        uncheckedLabel:    'nofollow',
        defaultOffWidth:20,
        defaultOnWidth:71
    });
    jQuery('.theme_check_optional').iphoneStyle({
        defaultOffWidth:41,
        defaultOnWidth:33
    });
    
    jQuery('select.iphone_select').each(function(){
        if(jQuery(this).parents('.postbox').is('.closed')){
            var button = jQuery(this);
            button.parents('.postbox').children('.hndle,.handlediv').bind('clickoutside',function(e){
                    button.iphoneStyleTriToggle();
            });
        }else{
            jQuery(this).iphoneStyleTriToggle();
        }
    });
    
    
});
function tt_get_post_terms(pare){
    jQuery.post(ajaxurl, {
        'action':'themeton_recent_post_terms',
        'post_format':jQuery(pare).val()
        }, function(response) {
        jQuery('#'+jQuery(pare).attr('rel')).html(response);
        jQuery('.ajax-loading').css('visibility','hidden');
    });

}
function sendMessage(msg){
    jQuery('#tt-message').html(msg);
    jQuery('#tt-message').show('slow');
    setTimeout(function(){
        jQuery('#tt-message').hide('slow');
    }, 4000)
}
jQuery(document).ready(function(){    
    jQuery('#themeton_custom_post_slider_images').sliderClone({

        });
    jQuery('#shortcode_generator_list > div').css('display','none');
    jQuery('#shortcode_selector').change(function(){
        jQuery('#shortcode_generator_list > div').css('display','none');
        jQuery('#shortcode_'+jQuery(this).val()).css('display','block');
    });

    jQuery('#poststuff').each(function(){
        var selector0 = "#page_template";
        var dondogmaa="#themeton_additional_options .tt_meta_option";
        

        var container = jQuery(this),
        superselector = container.find(selector0);
        var template = superselector.val();
        if(template!=undefined){
            jQuery(dondogmaa).fadeOut();
            jQuery(dondogmaa+'[rel*="'+template+'"]').fadeIn();
            jQuery(selector0).bind('change', function(){                
                var template = jQuery(selector0).val();
                jQuery(dondogmaa).fadeOut();
                jQuery(dondogmaa+'[rel*="'+template+'"]').fadeIn();                
            });
        }
    });
    
    addUserFunc(function(parentHtml){
        jQuery( parentHtml+".size-slider" ).each(function (){
            var sz=jQuery(this).parent().children("input").val();
            //$(this).parent().children(".size-resizer").css('font-size',sz+'px');
            jQuery(this).parent().children("span").html(sz+'px');
            //alert($(this).parent().attr("class"));
            jQuery(this).slider({
                range: "min",
                value: sz,
                min: 0,
                max: 100,
                slide:function(event,ui){
                    jQuery(this).parent().children("input").val(ui.value);
                    jQuery(this).parent().parent().children('.title').children().css('font-size',ui.value+'px');
                    //$(this).parent().children(".size-resizer").css('font-size',ui.value+'px');
                    jQuery(this).parent().children("span").html(ui.value+'px');
                }
            });
        });
    },'');
    
    
    jQuery('.tt_tab_content').tabs();
    if(typeof window.tb_remove == 'function') {
            window.tb_remove = function() {
            // replace the previous function with the new one
                    jQuery("#TB_window").fadeOut("fast",function(){jQuery('#TB_window,#TB_overlay,#TB_HideSelect').unload("#TB_ajaxContent").unbind().remove();});
            }
    }
    
    
    var CF = CF || {};
    CF.postFormats = function($) {
        return {
                switchTab: function(clicked) {
                        var $this = jQuery(clicked),
                                $tab = $this.closest('li');

                        if (!$this.hasClass('current')) {
                                $this.addClass('current');
                                $tab.siblings().find('a').removeClass('current');
                                this.switchWPFormat($this.attr('href'));
                        }
                },
                switchWPFormat: function(formatHash) {
                        jQuery(formatHash).trigger('click');
                        switch (formatHash) {
                                case '#post-format-0':
                                case '#post-format-status':
                                case '#post-format-link':
                                case '#post-format-video':
                                case '#post-format-quote':
                                case '#post-format-audio':
                        }
                }
        };
    }(jQuery);
    
    // move tabs in to place
    jQuery('#cf-post-format-tabs').insertBefore(jQuery('form#post')).show();
    jQuery('#themeton_custom_post_format').insertBefore(jQuery('#titlediv'));

    // tab switch
    jQuery('#cf-post-format-tabs a').live('click', function(e) {
            CF.postFormats.switchTab(this);
            e.stopPropagation();
            e.preventDefault();
    }).filter('.current').each(function() {
            CF.postFormats.switchWPFormat(jQuery(this).attr('href'));
    });
});

var definedUserFunctions=[];
function addUserFunc(fn,parentHtml){
    definedUserFunctions.push(fn);
    fn(parentHtml);
}
function callUserFunc(parentHtml){
    for(i=0;i<definedUserFunctions.length;i++){
        definedUserFunctions[i](parentHtml);
    }
}

function browseMediaWindow(param)
{
    window.original_send_to_editor = window.send_to_editor;
    window.custom_editor = true;
    var pID = jQuery('#post_ID').val();
    if(pID==undefined){
        pID=1;
    }
    window.send_to_editor = function(html){
	//alert(html);
        imgurl = jQuery(html).attr('href');
        if (elementId != undefined) {
            jQuery('#'+elementId).val(imgurl);
        } else {
            window.original_send_to_editor(html);
        }
        elementId = undefined;
        tb_remove();
    };
    elementId = param;
    formfield = jQuery('#'+param).attr('name');
    tb_show('Upload', 'media-upload.php?post_ID=' + pID + '&type=image&TB_iframe=true',false);
}

window.original_send_to_editor = window.send_to_editor;
window.custom_editor = true;