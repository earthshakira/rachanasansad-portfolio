/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($){
    jQuery.fn.sliderClone=function(option){
        var defaultValues={
            removeButton:'.removeButton',
            addButton:'#post_slide_image_add_button',
            slideCounter:'#post_slide_image_count_value',
            cropperId:''
        },
        clonedTemplate,
        clonerSlide=$(this);
        var options=$.extend(true,defaultValues, option);

        if(options.cropperId!=''){
            cropBtn=jQuery('#'+options.cropperId+'_cropper_content .crop_button');
            cropBtn.attr('container',options.cropperId);
            cropBtn.click(crop);
            jQuery('#'+options.cropperId+'_cropper_content .edit_button').click(cropperEdit);
        }
        cloneTemplate();
        // alert(options.addButton);
        $(options.addButton).click(addItem);
        initOptions();
        function cloneTemplate(){
            clonedTemplate=clonerSlide.children(':first-child').clone();
            clonerSlide.children(':first-child').remove();
        // alert(clonerSlide.attr('class'));
        }
        function removeItem(){
            jQuery(this).hide();
            jQuery(this).parent().slideUp(500, function(){
                jQuery(this).remove();                
                jQuery(options.slideCounter).val(clonerSlide.children().size());
                reOrder();
            });
        }
        function reOrder(){
            clonerSlide.children().each(reOrderIndex);
        }
        function reOrderIndex(index){
            jQuery(this).children(defaultValues.removeButton).click(removeItem);
            jQuery(this).find('input,textarea,select').each(function(){
                if(jQuery(this).attr('type')=='button'){
                    jQuery(this).attr('attr',jQuery(this).attr('rel').replace('#index#',index));
                }else{
                    args=(jQuery(this).attr('rel')+'').split(',');
                    //jQuery(this).attr('rel',jQuery(this).attr('name')+','+jQuery(this).attr('id'));
                    jQuery(this).attr('name',(args[0]+'').replace('#index#',index));
                    jQuery(this).attr('id',(args[1]+'').replace('#index#',index));
                }
                
            });
        }
        function browseSlideMediaWindow()
        {
            window.original_send_to_editor = window.send_to_editor;
            window.custom_editor = true;
            var pID = jQuery('#post_ID').val();
            if(pID==undefined){
                pID=1;
            }
            window.send_to_editor = function(html){
                imgurl = jQuery('img',html).attr('src');
                if (elementId != undefined) {
                    jQuery('#'+elementId).val(imgurl);
                    jQuery('#'+elementId+'_cropped').val('');
                } else {
                    window.original_send_to_editor(html);
                }
                elementId = undefined;
                tb_remove();
            };
            elementId = jQuery(this).attr('attr');
            formfield = jQuery('#'+jQuery(this).attr('attr')).attr('name');
            tb_show('Upload', 'media-upload.php?post_id=' + pID + '&type=image&TB_iframe=true',false);
        }
        function cropAjax(response){            
            if(response!='0'){
                jQuery('#'+options.cropperId+'_cropper_image_container').html('<img src="'+response+'">');
                cropped_slide=jQuery('#'+options.cropperId+'_cropper_id').val()+'';
                jQuery('#'+cropped_slide+'_cropped').val(response);
                jQuery('#'+options.cropperId+'_cropper_content .edit_button').css('display','block');
                tb_remove();
            }
        }
        function crop(){
            kk=jQuery('#'+ jQuery(this).attr('container')+'_cropper_image_container > img').data('Jcrop').tellSelect();
            myPostID=jQuery(this).attr('rel');            
            if(kk.w>0){
                dt={
                    action:jQuery('#solinoo').val(),
                    image_org:jQuery('#'+jQuery(this).attr('container')+'_cropper_image_org').val(),
                    image_x:kk.x,
                    image_y:kk.y,
                    image_w:kk.w,
                    image_h:kk.h,
                    myPost_id:myPostID
                };
                jQuery.post(ajaxurl, dt, cropAjax);
            }
        }
        function cropperEdit(){
            //  alert(post_id);
            //alert(jQuery('#'+options.cropperId+'_size_type option:selected').val());
            jQuery('#'+options.cropperId+'_cropper_content .edit_button').css('display','none');
            jQuery('#'+options.cropperId+'_cropper_image_container').html('<img src="'+jQuery('#'+options.cropperId+'_cropper_image_org').val()+'">');
            jQuery('#'+options.cropperId+'_cropper_image_container img').Jcrop(
            {
                aspectRatio:parseFloat(jQuery('#'+options.cropperId+'_size_type option:selected').attr('ratio'))
            }
            );
            
        }
        function cropSlideMediaWindow(){
            //alert(jQuery(this).attr('attr'));
            jQuery('#'+options.cropperId+'_cropper_image_org').val(jQuery('#'+jQuery(this).attr('attr')).val());
            jQuery('#'+options.cropperId+'_cropper_image').val(jQuery('#'+jQuery(this).attr('attr')+'_cropped').val());
            jQuery('#'+options.cropperId+'_cropper_id').val(jQuery(this).attr('attr')+'');
            if(jQuery('#'+options.cropperId+'_cropper_image').val()!=''){
                jQuery('#'+options.cropperId+'_cropper_image_container').html('<img src="'+jQuery('#'+options.cropperId+'_cropper_image').val()+'">');
                jQuery('#'+options.cropperId+'_cropper_content .edit_button').css('display','block');
            }else{
                cropperEdit();
            }
            tb_show('Crop Image','#TB_inline?inlineId='+options.cropperId+'_cropper_content',false);
            
            
        }
        function addItem(){
            newItem=clonedTemplate.clone();
            //newItem.children(defaultValues.removeButton).click(removeItem);
            newItem.css('display','none');
            initAfterAdd(newItem);
            clonerSlide.append(newItem);            
            newItem.slideDown();
            img_radio();
        }
        function initAfterAdd(itm){
            index=clonerSlide.children().size();
            jQuery(options.slideCounter).val(index+1);
            itm.children(defaultValues.removeButton).click(removeItem);
            jQuery(itm).find('input,textarea,select').each(function(){
                if(jQuery(this).attr('type')=='button'){
                    if(jQuery(this).hasClass('slideButtonEdit')){
                        jQuery(this).click(cropSlideMediaWindow);
                    }else
                        jQuery(this).click(browseSlideMediaWindow);
                    jQuery(this).attr('attr',jQuery(this).attr('rel').replace('#index#',index));
                }else{
                    jQuery(this).attr('rel',jQuery(this).attr('name')+','+jQuery(this).attr('id'));
                    jQuery(this).attr('name',jQuery(this).attr('name').replace('#index#',index));
                    jQuery(this).attr('id',jQuery(this).attr('id').replace('#index#',index));
                }
            });
        }
        function initOptions(){
            clonerSlide.children().each(initOption);
            jQuery(options.slideCounter).val(clonerSlide.children().size());
        }
        function initOption(index){
            jQuery(this).children(defaultValues.removeButton).click(removeItem);
            jQuery(this).find('input,textarea,select').each(function(){
                if(jQuery(this).attr('type')=='button'){
                    if(jQuery(this).hasClass('slideButtonEdit')){
                        jQuery(this).click(cropSlideMediaWindow);
                    }else
                        jQuery(this).click(browseSlideMediaWindow);
                    jQuery(this).attr('attr',jQuery(this).attr('rel').replace('#index#',index));
                }else{
                    jQuery(this).attr('rel',jQuery(this).attr('name')+','+jQuery(this).attr('id'));
                    jQuery(this).attr('name',jQuery(this).attr('name').replace('#index#',index));
                    jQuery(this).attr('id',jQuery(this).attr('id').replace('#index#',index));
                }
            });
        }
        jQuery('#tt_slider_element_box img.imgSelected').each(function(){
            jQuery(this).click();
        });

    };
})(jQuery);

function img_radio(){
    jQuery('input:radio').next('img').click(function(){
        jQuery(this).prev().attr('checked', true);
        name = jQuery(this).prev().attr('name');
        jQuery('input:radio[name="'+name+'"]').next('img').removeClass('imgSelected');
        jQuery(this).addClass('imgSelected');
    });
}