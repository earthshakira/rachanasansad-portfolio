jQuery(document).ready(function(){    
    
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
    jQuery('#cf-post-format-tabs').insertBefore(jQuery('#themeton_custom_post_format')).show();
//    jQuery('#themeton_custom_post_format').insertBefore(jQuery('#titlediv'));

    // tab switch
    jQuery('#cf-post-format-tabs a').live('click', function(e) {
            CF.postFormats.switchTab(this);
            e.stopPropagation();
            e.preventDefault();
    }).filter('.current').each(function() {
            CF.postFormats.switchWPFormat(jQuery(this).attr('href'));
    });
});