var $readMore='More';
var $user_login_buton_clicked=false;
var $container = '';
var $tt_rewidth_called = false;
var $tt_single_sidebar;
var $commentResult=false;
var $tt_body_scroll=false;
var flexResize=false;
var $inf_count=0;
var $inf_found=0;
var inf_remaining=0;
var $inf_remaining_many='';
var $inf_remaining_one='';
var $inf_remaining_no='';
var $wheelInfiniteEnabled=true;
var $currentURLback=window.location.toString().split("#")[0];
var $currentURL = $currentURLback + (($currentURLback.lastIndexOf("/")==$currentURLback.length-1) ? '' : '/');
jQuery(document).ready(function(){

    tt_home_uri = tt_home_uri + ((tt_home_uri.lastIndexOf("/")==tt_home_uri.length-1) ? '' : '/');
    /* Backup ReadMore Text */
    $readMore = jQuery('.read-more').html();
    // pagination
    tt_pagination();
    // Repair Menu Class
    jQuery('.current-menu-item').addClass('active');

    $tt_single_sidebar=(jQuery('body').hasClass('single-post') || jQuery('body').hasClass('page-template-default') || jQuery('body').hasClass('error404') || jQuery('body').hasClass('search-no-results'))?true:false;
    // jQuery Isotope
    if($tt_single_sidebar){
        $container      = jQuery('.masonry-widgets');
        $container_item = '.widget';
    }else{
        $container      = jQuery('.mansonry-container');
        $container_item = '.item-article';
    }
    $container.isotope({
        itemSelector: $container_item,
        masonry: {
			columnWidth: 1
        }
    });
    tt_init();
    setTimeout(function(){tt_item_show();},5000);

	//Responsive video scripts
	jQuery("article .item-media, section#page .item-media").fitVids();
});

jQuery(window).load(function(){
    /* Scroll top check */
    scroll_top_check();
    jQuery(window).bind('mousewheel', function() {scroll_top_check();});
    jQuery(window).bind('keydown',    function(e){if(e.keyCode==40){scroll_top_check();}});

    tt_rewidth();
    tt_item_show();
    jQuery(window).resize(function(){
        //Header-top
        tt_header_top();
        var $beforeWidth, $afterWidth;
        if(flexResize){
            setTimeout(function(){flexResize=false;},1000);
        }else{
            $beforeWidth=jQuery(window).width();
            setTimeout(function(){
                $afterWidth=jQuery(window).width();
                if($beforeWidth==$afterWidth && !$tt_rewidth_called){
                    $tt_rewidth_called=true;
                    tt_rewidth();
                    jQuery('.flex-direction-nav .next').click();
                    setTimeout(function(){$tt_rewidth_called=false;},1000);
                }
            },1000);
        }
        setTimeout(function(){if($tt_body_scroll) {$tt_body_scroll.resize();}},1000);
    });
    ////////////infinitescroll//////////////////
     if(jQuery('#page_nav').html()){
        $container.infinitescroll({
                navSelector  : '#page_nav',    /* selector for the paged navigation      */
                nextSelector : '#page_nav a',  /* selector for the NEXT link (to page 2) */
                itemSelector : '.item-article',/* selector for all items you'll retrieve */
/*                debug        : true, */
                loading: {
                    finished:false,
                    msgText: tt_infinite_loadingMsg,
                    finishedMsg: tt_infinite_finishedMsg,
                    img: tt_infinite_img
                },
                errorCallback: function(){
                    jQuery('.next-items').fadeOut('slow');
                }
            },
            /* call Isotope as a callback */
            function( newElements ){
                $wheelInfiniteEnabled=true;
                $container.isotope('appended', jQuery( newElements ));
                tt_init_single();
                tt_rewidth();
                tt_item_show();
                /* Readding Bootstrap script */
                if(typeof initThemeElements === 'function'){initThemeElements();}
                infinite_remaining();
                /* Load complete */
                jQuery('nav.manual-infinite-scroll').removeClass('infinite-scroll-loading');
            }
        );
        if(jQuery('.next-items').html()){
            jQuery(window).unbind('.infscr');
            jQuery('body').addClass('use-manual-infinite-scroll');

            if($container){
                $inf_count    =parseInt(jQuery('.remaining').attr('data-count'));
                $inf_found    =parseInt(jQuery('.remaining').attr('data-found'));
                inf_remaining =$inf_found;
                $inf_remaining_many=jQuery('.remaining').attr('data-many');
                $inf_remaining_one =jQuery('.remaining').attr('data-one');
                $inf_remaining_no  =jQuery('.remaining').attr('data-no');
                infinite_remaining();
                jQuery('.next-items').click(function(){
                    /* Load start */
                    jQuery('nav.manual-infinite-scroll').addClass('infinite-scroll-loading');
                    $container.infinitescroll('retrieve');
                });
            }
        }else{
            // No Vertical Scroll
            jQuery(window).bind('mousewheel', function() {if(!hasScroll(document.body, 'vertical')&&$wheelInfiniteEnabled){$wheelInfiniteEnabled=false;jQuery('nav.manual-infinite-scroll').addClass('infinite-scroll-loading');$container.infinitescroll('retrieve');}});
            jQuery(window).bind('keydown',    function(e){if(!hasScroll(document.body, 'vertical')&&e.keyCode==40&&$wheelInfiniteEnabled){$wheelInfiniteEnabled=false;jQuery('nav.manual-infinite-scroll').addClass('infinite-scroll-loading');$container.infinitescroll('retrieve');}});
        }
    }

    // Filter
    var $optionSets = jQuery('#options .option-set'),
    $optionLinks = $optionSets.find('a');
    $optionLinks.click(function(){
        var $this = jQuery(this);
        // don't proceed if already selected
        //if ( $this.hasClass('selected') ){return false;}
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
        key = $optionSet.attr('data-option-key'),
        value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
            // changes in layout modes need extra logic
            changeLayoutMode( $this, options )
        } else {
            // otherwise, apply new options
            $container.isotope( options );
        }
        setTimeout(function(){if($tt_body_scroll) {$tt_body_scroll.resize();}},1500);
        return false;
    });
    // Header Top
    tt_header_top();
    // Open Graph Meta Defaults
    jQuery("meta[property*=title]").after("<meta property='og:url' content='"+$currentURLback+"'/>");

	// Social options sharethis
	if(social_media == '1')
		tt_social_share();


    try {
        //  Slider - TOUCH SWIPE
        //Assign handlers to the simple direction handlers.
        var swipeOptions={
            swipe:swipe,
            threshold:0
        }
        //Enable swiping...
        jQuery("body.single .item-content").swipe( swipeOptions );

        //Swipe handlers.
        //The only arg passed is the original touch event object
        function swipe(event, direction, distance, duration, fingerCount){
            switch(direction){
                case 'left'  :
                    if(duration>300){location.href=jQuery('#next a.link-content').attr('href');}
                    break;
                case 'right' :
                    if(duration>300){location.href=jQuery('#prev a.link-content').attr('href');}
                    break;
                case 'up'    :
    //                jQuery(window).scrollTop(jQuery(window).scrollTop()+100);
                    break;
                case 'down'  :
    //                jQuery(window).scrollTop(jQuery(window).scrollTop()-100);
                    break;
            }
        }
    } catch(e){}

    // Add Media button
    jQuery('.wp-media-buttons>a').click(function(e){
        e.preventDefault();
        browseMediaWindow();
        return false;
    });
});

// Init Single Scripts
// --------------------------------------------------------------------
function tt_init_single(){
    // Init NotInited Items
    jQuery(".item-not-inited").each(function(){
        $currentArticle=jQuery(this);
        // IMAGE ICON OVERLAY
        // This will select the items which should include the image overlays
        $currentArticle.find("div.entry-image, div.instagram-photo, div.entry-gallery").each(function(){
            var	ctnr = jQuery(this).find('a.item-preview');
            var cntrDiv=jQuery(this);
            // insert the overlay image
            ctnr.each(function(){
                if(jQuery(this).children('img')){
                    if(jQuery(this).hasClass('iconImage')){
                        jQuery(this).append(jQuery('<div class="image-overlay"><div class="iconImage"></div></div>'));
                    }else if(jQuery(this).hasClass('iconVideo')){
                        jQuery(this).append(jQuery('<div class="image-overlay"><div class="iconVideo"></div></div>'));
                    }else if(jQuery(this).hasClass('iconInstagram')){
                        jQuery(this).append(jQuery('<div class="image-overlay"><div class="iconInstagram"></div></div>'));
                    }
                }

                var overImg = jQuery(this).children('.image-overlay');
                if(jQuery.browser.msie && parseInt(jQuery.browser.version, 10) < 6){
                // IE sucks at fading PNG's with gradients so just use show hide
                }else{
                    // make sure it's not visible to start
                    overImg.css('display','none');
                    jQuery(this).hover(function(){overImg.fadeIn('fast');},function(){overImg.fadeOut('fast');});
                }

            })
//            var overImg = ctnr.children('.image-overlay');
//            if(jQuery.browser.msie && parseInt(jQuery.browser.version, 10) < 6){
//            // IE sucks at fading PNG's with gradients so just use show hide
//            }else{
//                // make sure it's not visible to start
//                overImg.css('display','none');
//                ctnr.hover(function(){overImg.fadeIn('fast');},function(){overImg.fadeOut('fast');});
//            }
        });

        // Image Pre loader
        if(jQuery.browser.msie){
            $currentArticle.find('.preload').removeClass('preload');
        }else{
            $currentArticle.find('.preload').preloadImages({
                showSpeed: 500,   // length of fade-in animation, 500 is default
                easing: 'easeInQuad'   // optional easing, if you don't have any easing scripts - delete this option
            });
        }
        // Ajax Like
        $currentArticle.find('.meta-like').live('click',function(e){
            var currentLike=jQuery(this);
            if(currentLike.attr('href')!='#' && !currentLike.hasClass('liked')){
                jQuery.post(jQuery(this).attr('href').replace("?p=","?like_it="), function(response) {
                    currentLike.attr('data-count',parseInt(currentLike.attr('data-count'))+1);
                    currentLike.removeAttr('href').addClass('liked').html(currentLike.closest('footer').hasClass('min')?currentLike.attr('data-count'):response);
                });
            }
            return false;
        });
        // Ajax Favorite
        $currentArticle.find('.favorite-post').live('click',function(e){
            var currentFav=jQuery(this);
            if(currentFav.attr('href')!='#' && !currentFav.hasClass('star-post')){
                jQuery.post(jQuery(this).attr('href').replace("?p=","?fav_it="), function(response) {
                    currentFav.addClass('star-post');
                });
            } else if(currentFav.attr('href')!='#' && currentFav.hasClass('star-post')){
                jQuery.post(jQuery(this).attr('href').replace("?p=","?fav_it="), function(response) {
                    alert(response);
                    currentFav.removeClass('star-post');
                });
            }
            return false;
        });
        // Ajax Delete My Post
        $currentArticle.find('a.delete-my-post').click(function(e){
            e.preventDefault();
            var currentDelete=jQuery(this);
            jQuery('body').append(
                '<div class="modal social-message fade" tabindex="-1" role="dialog" style="top: 1%;margin-top: 0;opacity: 1;">'+
                    '<div class="modal-header delete-modal">'+
                        '<button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">&times;</button>'+
                        '<h3>Are you sure delete this post?</h3>'+
                    '</div>'+
                    '<div class="modal-body">'+
                        '<p>If you are sure press "Delete now" or want to keep this post press "No"</p>'+
                    '</div>'+
                    '<div class="modal-footer">'+
                        '<a href="#" class="btn cancel btn-mini">No</a>'+
                        '<a href="#" class="btn ok btn-primary btn-mini">Delete Now</a>'+
                    '</div>'+
                '</div>'
            );
            jQuery('.modal.social-message').css('top','30%');
            jQuery(".modal.social-message ").css('opacity','1');
            jQuery(".modal.social-message .cancel").click(function(e){e.preventDefault();jQuery('.modal.social-message').remove();});
            jQuery(".modal.social-message .ok").click(function(e){
                e.preventDefault();
                jQuery('.modal.social-message .modal-body p').html('Loading...');
                jQuery(".modal.social-message .modal-footer .ok").remove();
                jQuery(".modal.social-message .cancel.btn-mini").html('Ok');
                if(currentDelete.attr('data-href')){
                    jQuery.ajax({
                        type: "POST",
                        url:  currentDelete.attr('data-href'),
                        success: function(response){
                            if(response.toString().search('%delete%')>=0){
                                response=response.toString().replace('%delete%','');
                                jQuery(currentDelete).closest('article').remove();
                                if($container){$container.isotope('reLayout');}
                            }
                            jQuery('.modal.social-message .modal-body p').html(response);
                        }
                    });
                }
            });
        });
        // Players
        $currentArticle.find('.jp-jplayer-audio').each(function(){
            jQuery(this).jPlayer({
                ready: function () {
                    jQuery(this).jPlayer("setMedia", {
                        mp3: jQuery(this).attr('src')
                    });
                },
                wmode:"window",
                swfPath: tt_theme_uri + "/js",
                cssSelectorAncestor: "#jp_interface_"+jQuery(this).attr('pid'),
                supplied: "mp3"
            });
        });
        // YouTube vMode Fix
        $currentArticle.find("iframe").each(function(){
            var ifr_source = jQuery(this).attr('src');
            if( typeof ifr_source != 'undefined') {
                var pos = ifr_source.indexOf("youtube.com");
                if(pos > -1) {
                    var wmode = "?autohide=1&wmode=opaque";
                    var posQuestionMark = ifr_source.indexOf("?");
                    if(posQuestionMark > -1)
                        wmode = "&autohide=1&wmode=opaque";
                    jQuery(this).attr('src',ifr_source+wmode);
                }
            }
        });
        //	Flex Slider
        $currentArticle.find('.flexslider').flexslider({
            prevText: "←",           //String: Set the text for the "previous" directionNav item
            nextText: "→",
            animation: 'slide',
            pauseOnAction: false
        });
        // Theme Elements
        $currentArticle.find(".well").popover();
        $currentArticle.find(".target_tooltip").tooltip();
        // Show Category Filter
        if(jQuery('.category-list').html()){
            jQuery('.category-list li.hide').each(function(){
                $currentFilterClass=jQuery(this).children('a').attr('data-option-value').replace('.','');
                if($currentArticle.hasClass($currentFilterClass)){jQuery(this).removeClass('hide');}
            });
        }
		/* Show Tag Filter */
        if(jQuery('.tag-list').html()){
            jQuery('.tag-list li.hide').each(function(){
                $currentFilterClass=jQuery(this).children('a').attr('data-option-value').replace('.','');
                if($currentArticle.hasClass($currentFilterClass)){
                    jQuery(this).removeClass('hide');
                }
            });
        }

         //	PRETTY PHOTO
        jQuery("a[rel^='prettyPhoto']").prettyPhoto({
            deeplinking:false,
                    social_tools: false
        });

        // Item inited
        $currentArticle.removeClass('item-not-inited');
    }).promise().done(function(){
        // Forms init
        formInit();
        setTimeout(function(){if($tt_body_scroll){$tt_body_scroll.resize();}},1000);
        //Facebook Comment Reset
        if(typeof FB != 'undefined'){FB.XFBML.parse();}
    });
}
// Init Scripts
// --------------------------------------------------------------------
function tt_init(){
    // Userbar Widget Repair Z-index
    jQuery('aside>.user-bar').each(function(){jQuery(this).closest('aside').css('z-index','10')});
    // Login form toggle dropDown
    jQuery('.user-bar .user-login-buton>a.btn.wp').click(function(){
        $user_login_buton=jQuery(this).parent();
        if($user_login_buton.hasClass('open')){
            $user_login_buton.removeClass('open');
            $user_login_buton.find('.user-form-container').hide();
        }else{
            $user_login_buton.addClass('open');
            $user_login_buton.find('.user-form-container').show();
        }
        return false;
    });
    // Close user bar
    jQuery('.user-bar .user-login-buton').click(function(){
        $user_login_buton_clicked=true;
    });
    jQuery('body').click(function(){
        if($user_login_buton_clicked){
            $user_login_buton_clicked=false;
        }else{
            jQuery('.user-bar .user-login-buton').each (function(){
                if(jQuery(this).hasClass('open')){
                    jQuery(this).removeClass('open').find('.user-form-container').hide();
                }
            });
            jQuery('.user-bar .user-online').removeClass('open');
        }
    });
    // User menu toggle dropDown
    jQuery('.user-bar .user-online .tt-author2').click(function(e){e.preventDefault();jQuery(this).closest('.user-bar').find('.user-online').toggleClass('open'); return false;});
    // Join form show dropDown
    jQuery('.user-bar .user-join>a').click(function(){
        $containerUserBar=jQuery(this).closest('.user-bar');
        $containerUserBar.find('.user-login-buton').addClass('open');
        $containerUserBar.find('.user-login-buton .link-register').click();
        jQuery('body').ajaxComplete(function() {$containerUserBar.find('.user-login-buton .user-form-container').show();});
    });
    // FB Login
    jQuery('.user-bar .user-login-buton>a.btn.fb').click(function(e) {
//        e.preventDefault();
//        window.open( jQuery(this).attr('href'),'','scrollbars=no,menubar=no,height=400,width=800,resizable=yes,toolbar=no,status=no');
    });
    // Prev button hover
    jQuery('#prev').hover(function(){
        $animCntnt=jQuery(this).find('.link-content .prev_post');
        $animCntnt.stop().animate({left:0},'slow');
    },function(){
        $animCntnt=jQuery(this).find('.link-content .prev_post');
        $animCntntPaddingRL = parseInt($animCntnt.css('padding-left').replace('px',''))+parseInt($animCntnt.css('padding-right').replace('px',''));
        $animCntntBorderR  = $animCntnt.css('border-right-width')==''?'0':$animCntnt.css('border-right-width');
        $animCntntBorderL  = $animCntnt.css('border-left-width') ==''?'0':$animCntnt.css('border-left-width');
        $animCntntBorderRL = parseInt($animCntntBorderR.replace('px','')) + parseInt($animCntntBorderL.replace('px',''));
        $animWidth=$animCntnt.width()+$animCntntPaddingRL+$animCntntBorderRL;
        $animCntnt.stop().animate({left:'-'+$animWidth+'px'},'slow');
    });
    // Next button hover
    jQuery('#next').hover(function(){
        $animCntnt=jQuery(this).find('.link-content .next_post');
        $animCntnt.stop().animate({right:0},'slow');
    },function(){
        $animCntnt=jQuery(this).find('.link-content .next_post');
        $animCntntPaddingRL = parseInt($animCntnt.css('padding-left').replace('px',''))+parseInt($animCntnt.css('padding-right').replace('px',''));
        $animCntntBorderR  = $animCntnt.css('border-right-width')==''?'0':$animCntnt.css('border-right-width');
        $animCntntBorderL  = $animCntnt.css('border-left-width') ==''?'0':$animCntnt.css('border-left-width');
        $animCntntBorderRL = parseInt($animCntntBorderR.replace('px','')) + parseInt($animCntntBorderL.replace('px',''));
        $animWidth=$animCntnt.width()+$animCntntPaddingRL+$animCntntBorderRL;
        $animCntnt.stop().animate({right:'-'+$animWidth+'px'},'slow');
    });
    // MOBILE MENU
    jQuery('#main-menu-mobile').change(function(){if(jQuery(this).val() !== null){document.location.href = jQuery(this).val()}});
    //Mega-Menu-Start
    var temp, menu = jQuery("#navigation .menu");
    menu.find("li").hover(function(){
        jQuery(this).children('.children').hide().slideDown('normal');
        if(jQuery(this).hasClass('mega-item'))
            jQuery(this).children('.children').find('.children').hide().slideDown('normal');
        try{
            $tmp=(jQuery(this).children('.children').offset().left+jQuery(this).children('.children').width())-(jQuery("#header").offset().left+jQuery("#header").width());
            if($tmp>0){
                $childrenPaddingRL = parseInt(jQuery(this).children('.children').css('padding-left').replace('px',''))+parseInt(jQuery(this).children('.children').css('padding-right').replace('px',''));
                $childrenBorderR  = jQuery(this).children('.children').css('border-right-width')==''?'0':jQuery(this).children('.children').css('border-right-width');
                $childrenBorderL  = jQuery(this).children('.children').css('border-left-width') ==''?'0':jQuery(this).children('.children').css('border-left-width');
                $childrenBorderRL = parseInt($childrenBorderR.replace('px','')) + parseInt($childrenBorderL.replace('px',''));
                $tmp=$tmp+$childrenPaddingRL+$childrenBorderRL;
                jQuery(this).children('.children').css("left","-"+$tmp+"px");
                jQuery(this).children('.children::before').css("left","70px");
            }
        }
        catch(e){}
    },function(){jQuery(this).children('.children').stop(true,true).hide();});
    menu.children("li").each(function(){
        temp = jQuery(this);
        if(temp.children().hasClass("children"))
            temp.addClass("showdropdown");
        jQuery('ul.children ul.children').each(function(){
            jQuery(this).closest('li').addClass('has-children');
        });
        if(temp.hasClass('rel'))
            temp.find('.children').append('<span class="mg-menu-tip" style="width:'+temp.width()+'px"></span>');
        else
            temp.find('.children').append('<span class="mg-menu-tip" style="left:'+(temp.position().left-20)+'px;width:'+temp.width()+'px"></span>');
    });
    menu.find(".children.columns").each(function(){
        $countItems=1;
        jQuery(this).children(".mega-item").each(function(){
            temp = jQuery(this);
            if(temp.hasClass("clearleft")){
                $countItems=4;
            }else if(($countItems%3)==1 && $countItems!=1){
                temp.addClass("clearleft");
            }
            $countItems++;
        });
    });
    //Mega-Menu-End

	// Nice scroll bar initial
	if(stop_nice_scroll != '1')
		$tt_body_scroll = $tt_body_scroll?$tt_body_scroll:jQuery("html").niceScroll({mousescrollstep:20,horizrailenabled:false});

	// Header Top
    tt_header_top();
    //Init Single
    tt_init_single();
    // GO TO TOP
    jQuery('.anchorLink').click(function() {
        jQuery('body,html').animate({
            scrollTop:0
        },'slow');
    });
}
// Pagination
// --------------------------------------------------------------------
function tt_pagination(){
    inf_url = window.location.toString();
    inf_url = inf_url.search("#")>=0?inf_url.substring(0,inf_url.lastIndexOf("#")):inf_url;
    ++$infinitescroll;
    inf_url = inf_url.lastIndexOf("?")>=0 ? inf_url.replace('?',"?paged="+$infinitescroll+"&") : inf_url+"?paged="+$infinitescroll;
    jQuery('#page_nav a').attr('href',inf_url);
}
/* Post Single Modal */
/* -------------------------------------------------------------------- */
function tt_get_comments(postURL){
    jQuery.ajax({
        type: "POST",
        url: postURL,
        success: function(data){
            $commentResult=data;
        }
    });
}
// Post Show
// --------------------------------------------------------------------
function tt_item_show(){
    jQuery('.content-container').removeClass("hide");
    jQuery('#page.loading').removeClass("loading");
    jQuery('.mansonry-container>article.item-hidden').each(function(i){jQuery(this).delay(i*300).fadeIn('fast',function(){jQuery(this).removeClass('item-hidden');});}).promise().done(function(){ jQuery('#options a.selected').click(); setTimeout(function(){if($container){mediaSizeRepair();$container.isotope('reLayout');}},5000);});
    setTimeout(function(){if($container){mediaSizeRepair();$container.isotope('reLayout');}},1000);
}
//Header-top
// --------------------------------------------------------------------
function tt_header_top(){
    if(is_header_fixed=='fixed'){
        jQuery('.wrapper').css( 'padding-top', jQuery('.navbar-fixed-top').height());
        jQuery('.navbar-fixed-top').css('position','fixed');
    }else{
        jQuery('.wrapper').css( 'padding-top','');
    }
}
// Rewidth
// --------------------------------------------------------------------
function tt_rewidth(){
    if(typeof jQuery('.mansonry-container>article').css('margin-left')!='undefined'){
        jQuery('.mansonry-container>article').each(function(){
            // Read More Resize
            $width = jQuery(this).width();
            $commentCount = jQuery(this).find('.meta-comment')    .html() ? parseInt(jQuery(this).find('.meta-comment')    .attr('data-count')) : false;
            $likeCount    = jQuery(this).find('.footer-meta-like').html() ? parseInt(jQuery(this).find('.footer-meta-like').attr('data-count')) : false;
            if($width>250){
                jQuery(this).find('footer').removeClass('min');
                jQuery(this).find('footer .meta-comment').html(($commentCount==0 ? 'No' : $commentCount)+' comment'+($commentCount>1 ? 's' : ''));
                jQuery(this).find('footer .footer-meta-like').html($likeCount+' like'+($likeCount>1 ? 's' : ''));
                jQuery(this).find('footer .read-more').html($readMore);
                jQuery(this).find('.twt-border .view-details').css('display','');
                jQuery(this).find('.twt-border .twt-follow-button').css('display','');
                jQuery(this).find('footer .delete-my-post').html(jQuery(this).find('footer .delete-my-post').attr('data-text'));
                jQuery(this).find('footer .edit-my-post').html(jQuery(this).find('footer .edit-my-post').attr('data-text'));
            }else{
                jQuery(this).find('footer')              .addClass('min');
                jQuery(this).find('footer .meta-comment').html($commentCount);
                jQuery(this).find('footer .footer-meta-like')   .html($likeCount);
                jQuery(this).find('footer .read-more')   .html('→');
                jQuery(this).find('footer .delete-my-post, footer .edit-my-post').html('');
                jQuery(this).find('.twt-border .view-details').css('display','none');
                jQuery(this).find('.twt-border .twt-follow-button').css('display','none');
            }
        }).promise().done(function(){tt_rewidth_done();});
    }
}
// Ajax comment submit contact form
// -------------------------------------------------------------------
function ajaxComment(theForm){
    var result = '', c = '';
    jQuery('#loader').fadeIn();
    var formData = jQuery(theForm).serialize();
    var actionURL;
    actionURL = jQuery('#commentform').attr('action');
    jQuery.ajax({
        type: "POST",
        url:  actionURL,
        data: formData,
        success: function(response){
            tt_get_comments(jQuery('#commentform').attr('data-comment-link'));
            var i = setInterval(function() {
                if ($commentResult!=false) {
                    if($commentResult) {
                        jQuery('#comments').html(jQuery($commentResult).find('#comments').html());
                        c='success';
                        result='Success';
                        formInit();
                    } else {
                        c='error';
                        result='No coment';
                    }
                    jQuery('#LoadingGraphic').fadeOut('fast', function() {
                        jQuery('#Note').removeClass('success').removeClass('error').text('');
                        jQuery('#Note').show('fast');
                        jQuery('#Note').html(result).addClass(c).slideDown('fast');
                    });
                    $commentResult=false;
                    clearInterval(i);
                }
            }, 40);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        c = 'error';
        switch(jqXHR.status){
            case 500: {result='Duplicate comment detected; it looks as though you’ve already said that!';break;}
            default:  {result='not';}
        }
        jQuery('#LoadingGraphic').fadeOut('fast', function() {
                jQuery('#Note').removeClass('success').removeClass('error').text('');
                jQuery('#Note').show('fast');
                jQuery('#Note').html(result).addClass(c).slideDown('fast');
        }); // end loading image fadeOut
    });
    return false;
}
// Ajax Frontend Post Form
// -------------------------------------------------------------------
function ajaxFrontendPostForm(theForm){
    jQuery(theForm).find('.post_content').val(tinyMCE.get('textareaid2').getContent());
    var result = '', c = '';
    var formData = jQuery(theForm).serialize();
    var actionURL = jQuery('#frontend_post_form').attr('action');

    jQuery(theForm).find('.message').addClass('hide');
    jQuery(theForm).find('input[type="submit"]').each(function(){
        jQuery(this).attr('disabled','disabled');
        jQuery(this).val(jQuery(this).attr('loading-text'));
    });

    jQuery.ajax({
        type: "POST",
        url:  actionURL,
        data: 'tt_add_post=true&'+formData,
        success: function(response){
            $permalink=jQuery(theForm).attr('data-permalink');
            if(response.toString().search('%delete%')>=0){
                response=response.toString().replace('%delete%','');
                tinyMCE.get('textareaid2').setContent('');
                jQuery(theForm).find('textarea[name="tt_format_audio_embed"]').val('');
                jQuery(theForm).find('textarea[name="tt_format_video"]').val('');
                jQuery(theForm).find('textarea[name="tt_quote_text"]').val('');
                jQuery(theForm).find('textarea[name="post_excerpt"]').val('');
                jQuery(theForm).find('#tt_format_link_title').val('');
                jQuery(theForm).find('#tt_format_audio_url').val('');
                jQuery(theForm).find('#tt_format_status').val('');
                jQuery(theForm).find('#tt_quote_author').val('');
                jQuery(theForm).find('#tt_format_link').val('');
                jQuery(theForm).find('.post_image_url').val('');
                jQuery(theForm).find('#tt_quote_link').val('');
                jQuery(theForm).find('.post_content').val('');
                jQuery(theForm).find('.post_title').val('');
                jQuery(theForm).find('.post_slug').val('');
                if(jQuery(theForm).attr('data-type')=='edit'){
                    jQuery(theForm).find('.submit-add-post').removeClass('hide');
                    jQuery(theForm).find('.submit-update-delete-post').addClass('hide');
                    jQuery(theForm).find('input[name=post_id]').val('');
                    jQuery(theForm).attr('data-type','add');
                }
                tt_history($permalink);
            }else if(response.toString().search('%update%')>=0){
                response=response.toString().replace('%update%','');
                if(jQuery(theForm).attr('data-type')=='add'){
                    jQuery(theForm).find('.submit-add-post').addClass('hide');
                    jQuery(theForm).find('.submit-update-delete-post').removeClass('hide');
                    jQuery(theForm).find('input[name=post_id]')  .val(jQuery('.id',  response).text());
                    jQuery(theForm).attr('data-type','edit');
                }
                jQuery(theForm).find('input[name=post_slug]').val(jQuery('.slug',response).text());
                $permalink+=$permalink.indexOf('?')<0?'?':'&';
                $permalink+='edit_id='+jQuery('.id',response).text();
                tt_history($permalink);
            }
            jQuery(theForm).find('input[type="submit"]').each(function(){
                jQuery(this).removeAttr('disabled');
                jQuery(this).val(jQuery(this).attr('val-text'));
            });
            jQuery(theForm).find('.message').html(response);
            jQuery(theForm).find('.message').removeClass('hide');
        }
    });
    return false;
}
jQuery.fn.overlabel = function() {
    this.each(function(index) {
        var label = jQuery(this);
        var field;
        var id = this.htmlFor || label.attr('for');
        if (id && (field = document.getElementById(id))) {
            var control = jQuery(field);
            label.addClass("overlabel-apply");
            if (field.value !== ''){label.css("display", "none");}
            control.focus(function () {
                label.css("display", "none");
            }).blur(function () {
                if (this.value === '') {
                    label.css("display", "block");
                }
            });
            label.click(function() {
                var label = jQuery(this);
                var field;
                var id = this.htmlFor || label.attr('for');
                if (id && (field = document.getElementById(id))) {
                    field.focus();
                }
            });
        }
    });
};
// User Form init
// -------------------------------------------------------------------
function userFormInit(){
    jQuery(document).ready(function(){
        // Init Forms
        jQuery('.user-bar .link-register').unbind('click').bind('click', function(e){e.preventDefault();$containerUserBar=jQuery(this).closest('.user-bar');jQuery.ajax({type:"POST", url: tt_home_uri,  data:'tt_get_user_register_form=true', success:function(response){$containerUserBar.find('.user-form-container').html(response);userFormInit();}});});
        jQuery('.user-bar .link-login')   .unbind('click').bind('click', function(e){e.preventDefault();$containerUserBar=jQuery(this).closest('.user-bar');jQuery.ajax({type:"POST", url: tt_home_uri,  data: 'tt_get_user_login_form=true',   success:function(response){$containerUserBar.find('.user-form-container').html(response);userFormInit();}});});
        jQuery('.user-bar .link-lost')    .unbind('click').bind('click', function(e){e.preventDefault();$containerUserBar=jQuery(this).closest('.user-bar');jQuery.ajax({type:"POST", url:  tt_home_uri, data: 'tt_get_user_reset_form=true',   success:function(response){$containerUserBar.find('.user-form-container').html(response);userFormInit();}});});
        // Submit Forms
        jQuery("#loginform").validate({submitHandler: function(theForm){
            formData = jQuery(theForm).serialize();
            actionURL = jQuery(theForm).attr('action');
            jQuery(theForm).find('input[type="submit"]').each(function(){
                jQuery(this).attr('disabled','disabled');
                jQuery(this).val(jQuery(this).attr('loading-text'));
            });
            jQuery.ajax({type:"POST", url:actionURL, data:formData, success:function(response){
                    jQuery('.user-bar #login_error').remove();
                    if(jQuery(response).find('#login_error').html()){
                        $error=jQuery(response).find('#login_error');
                        $error.find('a').remove();
                        $error=$error.html();
                        jQuery(theForm).closest('.user-bar').find('p.message').html('');
                        jQuery(theForm).closest('.user-bar').find('.user-login-form').prepend('<div id="login_error" class="alert alert-error">'+$error+'</div>');
                        jQuery(theForm).find('input[type="submit"]').each(function(){
                            jQuery(this).removeAttr('disabled');
                            jQuery(this).val(jQuery(this).attr('val-text'));
                        });
                    }else{location.reload();}
                }
            });
            return false;
        }});
        jQuery("#registerform").validate({submitHandler: function(theForm){
            formData = jQuery(theForm).serialize();
            actionURL = jQuery(theForm).attr('action');
            jQuery(theForm).find('input[type="submit"]').each(function(){
                jQuery(this).attr('disabled','disabled');
                jQuery(this).val(jQuery(this).attr('loading-text'));
            });
            jQuery.ajax({type:"POST", url:actionURL, data:formData, success:function(response){
                    jQuery('.user-bar #login_error').remove();
                    if (jQuery(response).find('#login_error').html()) {
                        $error=jQuery(response).find('#login_error');
                        $error.find('a').remove();
                        $error=$error.html();
                        jQuery(theForm).closest('.user-bar').find('p.message').html('');
                        jQuery(theForm).closest('.user-bar').find('#login_error').remove();
                        jQuery(theForm).closest('.user-bar').find('#user-register-form').prepend('<div id="login_error" class="alert alert-error">'+$error+'</div>');
                    }else if(jQuery(response).find('p.message').html()){
                        $message=jQuery(response).find('p.message');
                        $message.find('a').remove();
                        $message=$message.html();
                        jQuery.ajax({type:"POST",url:tt_home_uri,data:'tt_get_user_login_form=true',success: function(response) {jQuery(theForm).closest('.user-bar').find('.user-form-container').html(response);userFormInit();jQuery('#loginform').closest('.user-bar').find('p.message').html($message);}});
// Start - For demo
//                    }else if (jQuery(response).find('p.error').html()) {
//                        $error=jQuery(response).find('p.error');
//                        $error.find('a').remove();
//                        $error=$error.html();
//                        jQuery(theForm).closest('.user-bar').find('p.message').html('');
//                        jQuery(theForm).closest('.user-bar').find('#login_error').remove();
//                        jQuery(theForm).closest('.user-bar').find('#user-register-form').prepend('<div id="login_error" class="alert alert-error">'+$error+'</div>');
//                    }else if(jQuery(response).find('.mu_register').html()){
//                        $message=jQuery(response).find('p.mu_register');
//                        $message.find('a').remove();
//                        $message=$message.html();
//                        jQuery.ajax({type:"POST",url:tt_home_uri,data:'tt_get_user_login_form=true',success: function(response) {jQuery(theForm).closest('.user-bar').find('.user-form-container').html(response);userFormInit();jQuery('#loginform').closest('.user-bar').find('p.message').html($message);}});
// End  - For demo
                    }
                    jQuery(theForm).find('input[type="submit"]').each(function(){
                        jQuery(this).removeAttr('disabled');
                        jQuery(this).val(jQuery(this).attr('val-text'));
                    });
                }
            });
            return false;
        }});
        jQuery("#lostpasswordform").validate({submitHandler: function(theForm){
            formData = jQuery(theForm).serialize();
            actionURL = jQuery(theForm).attr('action');
            jQuery(theForm).find('input[type="submit"]').each(function(){
                jQuery(this).attr('disabled','disabled');
                jQuery(this).val(jQuery(this).attr('loading-text'));
            });
            jQuery.ajax({
                type: "POST",
                url:  actionURL,
                data: formData,
                success: function(response) {
                    jQuery('.user-bar #login_error').remove();
                    if (jQuery(response).find('#login_error').html()) {
                        $error=jQuery(response).find('#login_error');
                        $error.find('a').remove();
                        $error=$error.html();
                        jQuery(theForm).closest('.user-bar').find('p.message').html('');
                        jQuery(theForm).closest('.user-bar').find('#login_error').remove();
                        jQuery(theForm).closest('.user-bar').find('#user-reset-form').prepend('<div id="login_error" class="alert alert-error">'+$error+'</div>');
                    }else if(jQuery(response).find('p.message').html()){
                        $message=jQuery(response).find('p.message');
                        $message.find('a').remove();
                        $message=$message.html();
                        jQuery.ajax({type:"POST",url:tt_home_uri,data:'tt_get_user_login_form=true',success: function(response) {jQuery(theForm).closest('.user-bar').find('.user-form-container').html(response);userFormInit();jQuery('#loginform').closest('.user-bar').find('p.message').html($message);}});
                    }
                    jQuery(theForm).find('input[type="submit"]').each(function(){
                        jQuery(this).removeAttr('disabled');
                        jQuery(this).val(jQuery(this).attr('val-text'));
                    });
                }
            });
            return false;
        }});
        // Update User Profile Setting Form
        jQuery("#frontend_user_form").validate({submitHandler: function(theForm){
            formData  = jQuery(theForm).serialize();
            actionURL = jQuery(theForm).attr('action');
            if(jQuery(theForm).find('#new_password').val()!=jQuery(theForm).find('#confirm_password').val()){
                jQuery(theForm).find('.message').removeClass('hide');
                jQuery(theForm).find('.message').html('Password is wrong');
            }else{
                jQuery(theForm).find('.message').addClass('hide');
                jQuery(theForm).find('input[type="submit"]').each(function(){
                    jQuery(this).attr('disabled','disabled');
                    jQuery(this).val(jQuery(this).attr('loading-text'));
                });
                jQuery.ajax({
                    type: "POST",
                    url:  actionURL,
                    data: 'tt_user_profile=true&'+formData,
                    success: function(response) {
                        jQuery(theForm).find('.message').html(response);

                        jQuery.ajax({
                            type: "POST",
                            url:  tt_home_uri,
                            data: 'tt_get_current_user_gravatar=true',
                            success: function(response) {
                                jQuery(theForm).find('input#new_password').val('');
                                jQuery(theForm).find('input#confirm_password').val('');
                                jQuery(theForm).find('.author-avatar>.control-label').html(response);
                                jQuery(theForm).find('input[type="submit"]').each(function(){
                                    jQuery(this).removeAttr('disabled');
                                    jQuery(this).val(jQuery(this).attr('val-text'));
                                });
                                jQuery(theForm).find('.message').removeClass('hide');
                            }
                        });
                    }
                });
            }
            return false;
        }});
        // Over Label
        jQuery("label.overlabel").overlabel();
    });
}
// Forms init
// -------------------------------------------------------------------
function formInit(){
    // User Form Init
    userFormInit();
    // comment-reply-link add btn
    jQuery('.comment-reply-link').each(function(){jQuery(this).addClass('btn btn-mini');});
    jQuery('#submit').each(function(){jQuery(this).addClass('btn');});
    jQuery("#commentform").validate({submitHandler: function(form){ajaxComment(form);return false;}});
    jQuery("#frontend_post_form").validate({submitHandler: function(form){ajaxFrontendPostForm(form);return false;}});
    jQuery("label.overlabel").overlabel();
}
// Flex slider repairing
// -------------------------------------------------------------------
function mediaSizeRepair(){
    $additionalSelector= '';
    jQuery($additionalSelector+'.flex-viewport').each(function(){
        $currentFlexViewPort=jQuery(this);
        $currentFlexViewPort.find('li').each(function(){
            jQuery(this).find('a').removeClass('preload');
            jQuery(this).find('img').css({opacity: 1, visibility: 'visible'});
        }).promise().done(function(){ flexResize=true; jQuery(window).resize();});
    });

    jQuery('.jp-audio-container').each(function(){
        jQuery(this).find('.jp-progress-container').width( (jQuery(this).width()-149<0)?0:(jQuery(this).width()-149) );
        jQuery(this).find('.jp-progress').width( (jQuery(this).width()-152<0)?0:(jQuery(this).width()-152) );
    });
}
// Apple checker
// -------------------------------------------------------------------
function isIPhone() {return (navigator.platform.indexOf("iPhone")!=-1);}
function isIPad()   {return (navigator.platform.indexOf("iPad")!=-1);}
function isIPod()   {return (navigator.platform.indexOf("iPod")!=-1);}
function isIDevice(){return isIPhone()||isIPad()||isIPod() ;}
// Social Share
// -------------------------------------------------------------------
function tt_social_share(){
    if(typeof(stButtons)!='undefined'){
        stButtons.locateElements();  // Parse ShareThis markup
        stLight.options({publisher: sharethis_key});
    }
}
// ReWidth Done
// -------------------------------------------------------------------
function tt_rewidth_done() {
    if($container){$container.isotope('reLayout');}
    // Nice Scroll
    if($tt_body_scroll){$tt_body_scroll.resize();}
    setTimeout(function(){if($tt_body_scroll){$tt_body_scroll.resize();}},3000);
    // Flex slider repairing
    mediaSizeRepair();
    /* Twitter min Width bug */
    jQuery('iframe.twitter-tweet').css('min-width','50px').contents().find('img').attr('height','auto');
}
/* Has Scroll */
/* ------------------------------------------------------------------- */
function hasScroll(el, direction){
    direction = (direction === 'vertical') ? 'scrollTop' : 'scrollLeft';
    var result = !! el[direction];

    if(!result){
        el[direction] = 1;
        result = !!el[direction];
        el[direction] = 0;
    }
    return result;
}

/* Browse Frontend Media Window */
/* ------------------------------------------------------------------- */
function browseMediaWindow(param){
    window.original_send_to_editor = window.send_to_editor;
    window.custom_editor = true;
    var pID = jQuery('input[name="post_id"]').val();
    if(pID==undefined){pID=1;}
    window.send_to_editor = function(html){
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
    tb_show('Upload', tt_home_uri+'wp-admin/media-upload.php?post_ID=' + pID + '&type=image&TB_iframe=true',false);
    jQuery('#TB_window').css('width','670px');
    jQuery('#TB_window').css('margin-top','0');
    jQuery('#TB_window iframe').css('width','670px');
//    if(jQuery('.navbar-fixed-top').css('position')=='fixed'){
//        jQuery('#TB_window').css('top',(40+parseInt(jQuery('.navbar-fixed-top').height()))+"px");
//    }
}

/* Calculate Infinite Remaining Posts */
/* ------------------------------------------------------------------- */
function infinite_remaining(){
    inf_remaining-=$inf_count;
    if(inf_remaining>0){
        jQuery(".remaining").html(inf_remaining+" "+((inf_remaining>1)?$inf_remaining_many:$inf_remaining_one));
    }else{
        jQuery(".remaining").html($inf_remaining_no);
        jQuery('.next-items').css('opacity','0');
    }
}

/* Scroll top check */
/* ------------------------------------------------------------------- */
function scroll_top_check(){setTimeout(function(){if(jQuery(window).scrollTop()>0){jQuery('body').addClass('tt-scrolled');}else{jQuery('body').removeClass('tt-scrolled');}},1000);}

/* History push */
/* ------------------------------------------------------------------- */
function tt_history(URL){if(!jQuery.browser.msie){window.history.pushState("", "", URL);}}
