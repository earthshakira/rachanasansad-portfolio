/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function(jQuery){
    jQuery.fn.changeDynamic_count=function(cnt,temp,title,klass){
        lnt=jQuery(this).children().length;
        if(lnt<cnt){
            for(i=lnt+1;i<=cnt;i++){
                customAccord=temp.clone();
                customAccord.find('input, textarea').each(function(){
                    jQuery(this).attr('id',jQuery(this).attr('rel').replace('#',i));
                });
                customAccord.find('.'+klass).html(title+i);
                customAccord.find('.'+klass).click(function(){
                    jQuery(this).next().toggle('slow');
                });
                jQuery(this).append(customAccord);
            }
        }else{
            for(i=cnt;i<lnt;i++){
                jQuery(this).children(':last-child').remove();
            }
        }
    }

})(jQuery);

