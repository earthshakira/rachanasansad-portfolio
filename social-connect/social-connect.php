<?php 
// START - Facebook Login
global $data, $facebook;
if (!isset($_SESSION)) { session_start(); }
if(isset($data['fb_connect']) && $data['fb_connect'] && isset($data['fb_app_id']) && !empty($data['fb_app_id']) && isset($data['fb_app_secret']) && !empty($data['fb_app_secret'])){
    require_once TEMPLATEPATH.'/social-connect/facebook/facebook.php';
    $facebook = new Facebook(array(
        'appId'  => $data['fb_app_id'],
        'secret' => $data['fb_app_secret'],
    ));
    // See if there is a user from a cookie
    $user = $facebook->getUser();
    if ($user) {
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
        } catch (FacebookApiException $e) {
            echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
            $user = null;
        }
    }
    
    if (!is_user_logged_in() && $user && (isset($_REQUEST['tt_fb_login'])||isset($_REQUEST['state'])&&isset($_REQUEST['code']))){
        $user_name= isset($user_profile['first_name'])?$user_profile['first_name']:false;
        $user_email=isset($user_profile['email'])   ?$user_profile['email']   :false;
        if ($user_email&&$user_id=email_exists($user_email)) {
            $user_avatar=false;
            if(isset($user_profile['id'])){
                try{
                    $rray=get_headers('http://graph.facebook.com/'.$user_profile['id'].'/picture?type=large');
                    $hd = $rray[5];
                    $user_avatar=substr($hd,strpos($hd,'http'));
                }catch(Exception $e){}
            }
            $current_user = get_user_by('email',$user_email);
            $user_id       =$current_user->ID;
            $user_login    =$current_user->user_login;                                        
            require('wp-blog-header.php');
            wp_set_current_user($user_id, $user_login);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user_login);
            if($user_avatar){update_user_meta($user_id, 'social_avatar',     $user_avatar);}
            wp_redirect( home_url() ); exit;
        }else{
            if($user_name&&username_exists($user_name)){
                $user_name=$user_profile['first_name'].$user_profile['last_name'];
                if(username_exists($user_name)){
                    $user_name=$user_profile['first_name'].'_'.$user_profile['last_name'];
                    if(username_exists($user_name)){
                        $user_name=$user_profile['username'];
                        if(username_exists($user_name)){
                            $user_name=false;
                        }
                    }
                }
            }
            if($user_name&&$user_email){
                $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                $user_id = wp_create_user( $user_name, $random_password, $user_email );
                if(isset($user_profile['first_name'])){update_user_meta($user_id, 'first_name', $user_profile['first_name'] );}
                if(isset($user_profile['last_name'])) {update_user_meta($user_id, 'last_name',  $user_profile['last_name']  );}
                if(isset($user_profile['link']))      {update_user_meta($user_id, 'fb_url',     $user_profile['link']       );}
                wp_redirect( home_url().'?tt_fb_login=true' ); exit;
            }else{
                tt_social_message('user_name_exists');
            }
        }
    }
}
// END   - Facebook Login
// START - Twitter Login
if(!is_user_logged_in() && isset($data['tw_connect']) && $data['tw_connect'] && isset($data['tw_consumer_key']) && !empty($data['tw_consumer_key']) && isset($data['tw_consumer_secret']) && !empty($data['tw_consumer_secret'])){
    require_once TEMPLATEPATH.'/social-connect/twitter/twitteroauth.php';
    define('CONSUMER_KEY',   $data['tw_consumer_key']);
    define('CONSUMER_SECRET',$data['tw_consumer_secret']);
    define('OAUTH_CALLBACK', home_url().'/?tt_tw_callback=true');
    if(isset($_REQUEST['tt_tw_callback'])){
        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
            $_SESSION['oauth_status'] = 'oldtoken';
            tt_social_session_destroy();
            wp_redirect( home_url().'?tt_tw_login=true' ); exit;
        }elseif(isset($_SESSION['oauth_token'])&&isset($_SESSION['oauth_token_secret'])&&isset($_REQUEST['oauth_verifier'])){
            /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

            /* Request access tokens from twitter */
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

            /* Save the access tokens. Normally these would be saved in a database for future use. */
            $_SESSION['access_token'] = $access_token;

            /* Remove no longer needed request tokens */
            unset($_SESSION['oauth_token']);
            unset($_SESSION['oauth_token_secret']);

            /* If HTTP response is 200 continue otherwise send to connect page to retry */
            if (200 == $connection->http_code) {
                /* The user has been verified and the access tokens can be saved for future use */
                $_SESSION['status'] = 'verified';
                wp_redirect( home_url().'?tt_tw_login=true' ); exit;
            } else {
                /* Save HTTP status for error dialog on connnect page.*/
                tt_social_session_destroy();
                wp_redirect( home_url().'?tt_tw_login=true' ); exit;
            }
        }else{
            tt_social_message('tw_key_error');
        }
    }elseif(isset($_REQUEST['tt_tw_redirect'])){
        /* Build TwitterOAuth object with client credentials. */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken(OAUTH_CALLBACK);
        if(isset($request_token['oauth_token'])&&isset($request_token['oauth_token_secret'])){
            /* Save temporary credentials to session. */
            $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            /* If last connection failed don't display authorization link. */
            switch ($connection->http_code) {
                case 200:
                    /* Build authorize URL and redirect user to Twitter. */
                    $url = $connection->getAuthorizeURL($token);
                    wp_redirect($url); exit;
                    break;
                default:
                    /* Show notification if something went wrong. */
                    echo 'Could not connect to Twitter. Refresh the page or try again later.';
            }
        }else{
            tt_social_message('tw_key_error');
        }
    }else{
        /* If access tokens are not available redirect to connect page. */
        if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
            tt_social_session_destroy();
            $content = false;
        }elseif(isset($_SESSION['access_token'])){
            /* Get user access tokens out of the session. */
            $access_token = $_SESSION['access_token'];

            /* Create a TwitterOauth object with consumer/user tokens. */
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

            /* If method is set change API call made. Test is called by default. */
            $content = $connection->get('account/verify_credentials');
        }else{
            tt_social_message('tw_key_error');
        }
    }
    if(isset($content->id)&&isset($content->name)&&isset($content->screen_name)&&isset($_REQUEST['tt_tw_login'])){
        $current_user=get_users(array('meta_key' => 'tw_id', 'meta_value' => $content->id));
        if ($current_user) {
            $current_user=$current_user[0];
            $user_id       =$current_user->ID;
            $user_login    =$current_user->user_login;                                        
            require('wp-blog-header.php');
            wp_set_current_user($user_id, $user_login);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user_login);
            if(isset($content->profile_image_url)){update_user_meta($user_id, 'social_avatar', $content->profile_image_url);}
            wp_redirect( home_url() ); exit;
        }else{
            $user_name= $content->screen_name;
            if(username_exists($user_name)){$user_name=false;}
            if($user_name){
                $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                $user_id = wp_create_user( $user_name, $random_password);
                update_user_meta($user_id, 'tw_id', $content->id );
                if(isset($content->name       ))      {update_user_meta($user_id, 'first_name', $content->name);}
                if(isset($content->screen_name))      {update_user_meta($user_id, 'tw_url', 'https://twitter.com/'.$content->screen_name);}
                if(isset($content->url))              {wp_update_user( array ('ID' => $user_id,'user_url'=>$content->url));}                
                if(isset($content->description))      {wp_update_user( array ('ID' => $user_id,'description'=>$content->description));}
                wp_redirect( home_url().'?tt_tw_login=true'); exit;
            }else{
                tt_social_message('user_name_exists');
            }
        }
    }
}
// END   - Twitter Login
// START - Social Message
function tt_social_message($msg_type=false){
    switch($msg_type){
        case 'user_name_exists':{ ?>
            <script type="text/javascript">
                jQuery(window).load(function(){
                    jQuery('body').append(
                        '<div class="modal social-message fade" tabindex="-1" role="dialog" style="top: 1%;margin-top: 0;opacity: 1;">'+
                            '<div class="alert alert-block fade alert-error" style="margin-bottom: 0;">'+
                                '<button type="button" class="close">×</button>'+
                                '<h4 class="alert-heading">Error !!!</h4>'+
                                '<p>User name already exists.</p>'+
                            '</div>'+
                        '</div>'
                    );
                    jQuery('.modal.social-message').css('top','30%');
                    jQuery(".modal.social-message .alert").css('opacity','1');
                    jQuery(".modal.social-message .alert .close").click(function(){jQuery('.modal.social-message').remove();});
                });
            </script><?php
            break;
        }
        case 'tw_key_error':{ ?>
            <script type="text/javascript">
                jQuery(window).load(function(){
                    jQuery('body').append(
                        '<div class="modal social-message fade" tabindex="-1" role="dialog" style="top: 1%;margin-top: 0;opacity: 1;">'+
                            '<div class="alert alert-block fade alert-error" style="margin-bottom: 0;">'+
                                '<button type="button" class="close">×</button>'+
                                '<h4 class="alert-heading">Error !!!</h4>'+
                                '<p>Check your Twitter Consumer Key and Twitter Consumer Secret.</p>'+
                            '</div>'+
                        '</div>'
                    );
                    jQuery('.modal.social-message').css('top','30%');
                    jQuery(".modal.social-message .alert").css('opacity','1');
                    jQuery(".modal.social-message .alert .close").click(function(){jQuery('.modal.social-message').remove();});
                });
            </script><?php
            break;
        }
    }
}
// END   - Social Message
// START - Social session_destroy
function tt_social_session_destroy(){
    unset($_SESSION['status']);
    unset($_SESSION['oauth_status']);
    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);
    unset($_SESSION['access_token']);
    unset($_SESSION['access_token']['oauth_token']);
    unset($_SESSION['access_token']['oauth_token_secret']);
}
// END   - Social session_destroy