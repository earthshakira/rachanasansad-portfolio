<?php
// START - Remove subscriber admin bar
$blogusers = get_users(array('role'=>'subscriber','meta_key'=>'show_admin_bar_front','meta_value'=>'true','meta_compare'=>'='));
foreach($blogusers as $bloguser){update_user_meta($bloguser->ID,'show_admin_bar_front','false');}
// END   - Remove subscriber admin bar
if (!function_exists('get_user_role_by_id')){
    //Get user role by id
    function get_user_role_by_id($id) {
        global $wp_roles;
        $curr_user = get_userdata($id);
        $roles = $curr_user->roles;
        $role = array_shift($roles);
        return isset($wp_roles->role_names[$role]) ? strtolower(translate_user_role($wp_roles->role_names[$role] )) : false;
    }
}
if ( ! function_exists( 'tt_get_user_avatar' ) ) :
    //Get Gravatar Image
    function tt_get_user_avatar($current_user=false,$size='96'){
        if($current_user===false){$current_user  = wp_get_current_user();}
        if($social_avatar = get_the_author_meta('social_avatar',$current_user->ID)){
           echo '<img alt="" src="'.$social_avatar.'" class="avatar" width="'.$size.'" height="'.$size.'">';
        }else{
            echo get_avatar( $current_user->user_email ,$size);
        }
    }
endif;
//Update Subscriber Role
$role = get_role('subscriber');
$role->add_cap('upload_files');
$role->add_cap('edit_posts');
$role->add_cap('edit_published_posts');
//Add User custom field
if(is_user_logged_in()&&get_user_role_by_id(get_current_user_id())=='administrator'){
    if ( ! function_exists( 'tt_add_custom_user_profile_fields' ) ){
        function tt_add_custom_user_profile_fields( $user ) { 
            if(get_user_role_by_id($user->ID)!='administrator'){ ?>
                <table id="custom_user_field_table" class="form-table">
                    <tr id="custom_user_field_row">
                        <th>
                            <label for="custom_field"><?php _e('Enable auto publish', 'themeton'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="enable_auto_publish" id="enable_auto_publish" value="true" <?php if(esc_attr(get_the_author_meta( 'enable_auto_publish', $user->ID ))==='true'){echo "checked";} ?> />
                            <span class="description"><?php _e('Regular user posts content with Draft status. If you activate this field, user can add posts published. This options just for your more trusted users.', 'themeton'); ?></span>
                        </td>
                    </tr>
                </table><?php
            }
        }
    }
    add_action( 'show_user_profile', 'tt_add_custom_user_profile_fields' );
    add_action( 'edit_user_profile', 'tt_add_custom_user_profile_fields' );
    if ( ! function_exists( 'tt_save_custom_user_profile_fields' ) ){
        function tt_save_custom_user_profile_fields( $user_id ) {
            if ( !current_user_can( 'edit_user', $user_id ) ){return FALSE;}
            update_user_meta( $user_id, 'enable_auto_publish', isset($_POST['enable_auto_publish'])?$_POST['enable_auto_publish']:'false' );
        }
    }
    add_action( 'personal_options_update', 'tt_save_custom_user_profile_fields' );
    add_action( 'edit_user_profile_update', 'tt_save_custom_user_profile_fields' );
    if ( ! function_exists( 'tt_field_placement_js' ) ){
        function tt_field_placement_js() {
            $screen = get_current_screen();
            if ( $screen->id != "profile" && $screen->id != "user-edit" ) return; ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    field = $('#custom_user_field_row').remove();
                    field.insertBefore('#password');
                });
            </script><?php
        }
    }
    add_action( 'admin_head', 'tt_field_placement_js' );
}
// START - User forms
if ( ! function_exists( 'user_login_form' ) ) :
function user_login_form() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    ?>   
    <div class="user-login-form">
       
        <form name="loginform" id="loginform" action="<?php echo site_url('wp-login.php', 'login_post'); ?>" method="post">
            <p>
                <label for="user_login">
    <?php _e('Username', 'themeton'); ?><br>
                    <input type="text" name="log" id="user_login" class="input required" value="" size="20" tabindex="10">
                </label>
            </p>
            <p>
                <label for="user_pass">
    <?php _e('Password', 'themeton'); ?><br>
                    <input type="password" name="pwd" id="user_pass" class="input required" value="" size="20" tabindex="20">
                </label>
            </p>
            <p class="forgetmenot">
                <label for="rememberme">
                    <input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90">
    <p class="rememberme"><?php _e('Remember Me', 'themeton'); ?></p>
                </label>
            </p>
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button-primary btn" loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Log In','themeton'); ?>" value="<?php _e('Log In', 'themeton'); ?>" tabindex="100">
                <input type="hidden" name="redirect_to" value="<?php echo $pageURL; ?>">
                <input type="hidden" name="testcookie" value="1">
            </p>
        </form>
        <p id="nav">
            <a href="<?php echo site_url('wp-login.php?action=register', 'login'); ?>" class="link-register"><?php _e('Register', 'themeton'); ?></a> |
            <a href="<?php echo site_url('wp-login.php?action=lostpassword', 'login'); ?>" class="link-lost" title="<?php _e('Password Lost and Found', 'themeton'); ?>"><?php _e('Lost your password?', 'themeton'); ?></a>
        </p>
    </div><?php
}endif;
if ( ! function_exists( 'user_reset_form' ) ) :
function user_reset_form() {
    ?>
    <div id="user-reset-form">
        <p class="message alert"><?php _e('Please enter your username or email address. You will receive a link to create a new password via email.', 'themeton'); ?></p>
        <form name="lostpasswordform" id="lostpasswordform" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post'); ?>" method="post">
            <p>
                <label for="user_login"><?php _e('Username or E-mail:', 'themeton'); ?><br>
                    <input type="text" name="user_login" id="user_login" class="input required" value="" size="20" tabindex="10"></label>
            </p>
            <input type="hidden" name="redirect_to" value="">
            <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button-primary btn" loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Get New Password','themeton'); ?>" value="<?php _e('Get New Password', 'themeton'); ?>" tabindex="100"></p>
        </form>
        <p id="nav">
            <a href="<?php echo site_url('wp-login.php', 'login'); ?>" class="link-login"><?php _e('Log in', 'themeton'); ?></a>
            | <a href="<?php echo site_url('wp-login.php?action=register', 'login'); ?>" class="link-register"><?php _e('Register', 'themeton'); ?></a>
        </p>
    </div><?php
}endif;

if ( ! function_exists( 'user_register_form' ) ) :
function user_register_form() {
    global $data;
    ?>
    <div id="user-register-form">
        <p class="message register alert"><?php _e('Register For This Site', 'themeton'); ?></p>
        <form name="registerform" id="registerform" action="<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>" method="post">
            <p>
                <label for="user_login"><?php _e('Username', 'themeton'); ?><br>
                    <input type="text" name="user_login" id="user_login" class="input required" value="" size="20" tabindex="10"></label>
            </p>
            <p>
                <label for="user_email"><?php _e('E-mail', 'themeton'); ?><br>
                    <input type="email" name="user_email" id="user_email" class="input required" value="" size="25" tabindex="20"></label>
            </p>
            <p id="reg_passmail"><?php _e('A password will be e-mailed to you.', 'themeton'); ?></p>
            <br class="clear">
            <input type="hidden" name="redirect_to" value="">
            <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button-primary btn" loading-text="<?php _e('Loading...','themeton'); ?>" val-text="<?php _e('Register','themeton'); ?>" value="<?php _e('Register', 'themeton'); ?>" tabindex="100"></p>
        </form>
        <p id="nav">
            <a href="<?php echo site_url('wp-login.php', 'login'); ?>" class="link-login"><?php _e('Log in', 'themeton'); ?></a> |
            <a href="<?php echo site_url('wp-login.php?action=lostpassword', 'login'); ?>" class="link-lost" title="<?php _e('Password Lost and Found', 'themeton'); ?>"><?php _e('Lost your password?', 'themeton'); ?></a>
        </p>
    </div><?php
}endif;
// END   - User forms
if ( ! function_exists( 'get_attachment_id_by_src' ) ) :
function get_attachment_id_by_src($image_src) {
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
    $id = $wpdb->get_var($query);
    return $id;
}endif;
/*
 * Universal ThemeTon featured image function
 */
if ( ! function_exists( 'post_image_show_auto_size' ) ) :
function post_image_show_auto_size($permalink = false, $noimage = false, $custom_link = '') {
    global $post, $format, $single, $customlink, $data, $is_featured_post;
    $customlink['klass']=$slide_imgs=$videoIcon = "";
	
	$size = array(
		'small' => array(175, 370),
		'medium' => array(214, 448),
		'large' => array(272, 564),
		'x-large' => array(370, 760)
	);
	if(isset($data['item_size']) && $data['item_size']!='') {
		$size = $size[$data['item_size']];
		$size = ($is_featured_post!='') ? $size[1] : $size[0];
	} else {
		$size = 221;
	}
    $post_image_tumb = get_post_image();

    $image_meta = 'tt_slide_images';
    if ($image_meta != '')
        $slide_imgs = get_post_meta($post->ID, $image_meta, true);

    if (!has_post_thumbnail($post->ID) && !($slide_imgs != '' && count($slide_imgs) > 0)) {
        if ($format == 'video') {
            $videoThumb = get_youtube_vimeo_thumb_url(get_post_meta($post->ID, 'tt-video-embed', true));
            $youtube='src="http://www.youtube.com/embed/';
            $vimeo='src="http://player.vimeo.com/video/';
            if(strpos(get_post_meta($post->ID, 'tt-video-embed', true),$youtube)) {
                $videoIcon = '<div class="instagram-link"><img src="'.get_template_directory_uri().'/images/youtube-icon.png"></div>';
            } else if(strpos(get_post_meta($post->ID, 'tt-video-embed', true),$vimeo)) {
                $videoIcon = '<div class="instagram-link"><img src="'.get_template_directory_uri().'/images/vimeo-icon.png"></div>';
            }
            
            if ($videoThumb !== false) {
                $post_image_tumb = $videoThumb;
            }
        }
    }

    if (!empty($post_image_tumb) || $slide_imgs!=''&&count($slide_imgs) > 0) {
        $lrg_img = $post_image_tumb;
        $klass = $format == 'video' ? 'iconVideo' : 'iconImage'; ?>
        <div class="item-image">
            <div class="entry-image clearfix">
                <div class="hover-content"> <?php
                    if (!empty($post_image_tumb) || $slide_imgs!=''&&count($slide_imgs) == 1){
                        $ilink = $lrg_img;
                        $pre_classes = "preload imgSmall item-preview ";
                        $classes = $pre_classes;
                        if(!empty($post_image_tumb)){
                            $classes .= $klass;
                        }elseif($slide_imgs != '' && isset($slide_imgs[0])) {
                            $post_image_tumb=$slide_imgs[0]['image'];
                            $ilink = $lrg_img;
                            $classes .= $klass;
                        }
                        $rell = count($slide_imgs) > 1 ? "prettyPhoto[" . $post->ID . "]" : 'prettyPhoto';
                        if (!$permalink) {
                            $ilink = $customlink['enable'] == 'true' ? $customlink['url'] : get_permalink($post->ID);
                            $rell = '';
                        } 
						if (!is_single() && !is_page()) { ?>
							<a title="<?php the_title(); ?>" href="<?php print $ilink; ?>"<?php echo $customlink['target'];?> class="<?php print $classes.$customlink['klass']; ?> item-click-modal" rel="<?php print $rell; ?>">
                            <img src="<?php print $post_image_tumb; ?>" alt="" style="width:100%; height:auto;" />
							</a>
                        <?php } else { ?>
							<img src="<?php print $post_image_tumb; ?>" alt="" style="width:100%; height:auto;" />
						<?php }
                        echo $videoIcon;
                    }elseif($slide_imgs != '' && count($slide_imgs) > 1) { ?>
                        <div class="flexslider">
                            <ul class="slides item-click-modal<?php echo $customlink['klass'];?>"><?php
                                foreach ($slide_imgs as $immg) {
                                    $ilink = $immg['image'];
                                    $pre_classes = "preload imgSmall item-preview ";
                                    $classes = $pre_classes;
                                    $classes .= $klass;
                                    $rell = count($slide_imgs) > 1 ? "prettyPhoto[" . $post->ID . "]" : 'prettyPhoto';
                                    if (!$permalink) {
                                        $ilink = $customlink['enable'] == 'true' ? $customlink['url'] : get_permalink($post->ID);
                                        $rell = '';
                                        //$classes = $pre_classes . 'iconPost';
                                    } ?>
                                    <li>
                                        <?php if (!is_single() && !is_page()) { ?>
                                            <a title="<?php the_title(); ?>"<?php echo $customlink['target'];?> class="<?php print $classes; ?>" href="<?php echo $ilink; ?>" rel="<?php print $rell; ?>">
                                            <img src="<?php echo aq_resize($immg['image'], $size); ?>" alt="" style="width:100%; height:auto;" />
											</a>
											<?php } else { ?>
                                            <img src="<?php echo $immg['image']; ?>" alt="" style="width:100%; height:auto;" />
                                        <?php } ?>
                                    </li><?php
                                } ?>
                            </ul>
                        </div><?php
                    } ?>
                </div>
            </div>
        </div><?php
        return true;
    } else {
        global $blogConf;
        $bool = (isset($blogConf['hide_content']) && $blogConf['hide_content'] == true) ? true : false;
        if ($bool) {
            get_template_part('post', 'title');
        }
    }
    return false;
}endif;

$tt_framework = new wp_tt_framework();
$tt_framework->init();

if ($tt_framework->admin) {
    global $sidebar;
    
    $postSlide = Array(
        'name' => __('Themeton featured image', 'themeton'),
        'id' => 'post_slider',
        'type' => 'post',
        'crop' => false,
    );
    $tt_framework->admin->addSlideMeta($postSlide);

    $pageSlide = Array(
        'name' => __('Themeton featured image', 'themeton'),
        'id' => 'post_slider',
        'type' => 'page',
        'crop' => false,
    );
    $tt_framework->admin->addSlideMeta($pageSlide);
    
    $tt_framework->admin->addMeta(Array(
        'type' => 'page',
        'title' => 'Page additional options',
        'meta_boxes' => Array(
            'hide_pagetitle' => Array('name' => 'hide_pagetitle', 'rel' => 'default,page-template-blog.php', 'type' => 'checkbox', 'description' => __('If turn it ON, your page shows with title on top.', 'themeton'), 'title' => __('Show page title?', 'themeton'), 'std' => 'checked'),
            'teaser_text' => Array('name' => 'teaser_text', 'rel' => 'default,page-template-blog.php,page-template-archive.php', 'type' => 'textarea', 'description' => __('Please enter teaser your text here.', 'themeton'), 'title' => __('Teaser text', 'themeton')),
            'meta_author' => Array('name' => 'meta_author', 'rel' => 'default', 'type' => 'selectbox', 'description' => __('If turn it ON, post show with author link on page', 'themeton'), 'title' => __('Show post author ?', 'themeton')),
            'show_filter' => Array('name' => 'show_filter', 'show' => '', 'rel' => 'page-template-blog.php', 'type' => 'checkbox', 'description' => __('If turn it ON, this page shows with <tt>Post Filter</tt> on left sidebar.', 'themeton'), 'title' => __('Show post filter?', 'themeton'), 'std' => 'checked'),
			'show_filter_tag' => Array('name' => 'show_filter_tag', 'rel' => 'page-template-blog.php,page-template-portfolio.php', 'type' => 'checkbox', 'description' => __('If turn it ON, this page shows with <tt>Post Filter by Tag</tt> on top area. And those tags show by their active posts because if your site has hundred of tags, hard to show them always. That\'s why those tags show by their active posts.', 'themeton'), 'title' => __('Show post filter by Tag?', 'themeton'), 'std' => ''),
            'filter_text' => Array('name' => 'filter_text', 'type' => 'text', 'rel' => 'page-template-blog.php', 'description' => __('Filter text (show all) does\'t show if you leave it empty', 'themeton'), 'title' => __('Show All text', 'themeton'), 'std' => __('Show all', 'themeton')),
            'custom_layout' => Array('name' => 'custom_layout', 'rel' => 'default', 'type' => 'layouts', 'description' => __('Choose the sidebar layout for this specific post. You can choose Full width or Right sidebar.', 'themeton'), 'options' => Array('select sidebar position' => $s_layout), 'title' => __('Page layout', 'themeton'), 'std' => '1-1-1'),
            'custom_sidebar' => Array('name' => 'custom_sidebar', 'show' => '#custom_layout', 'rel' => 'default', 'type' => 'select', 'title' => __('Select sidebar', 'themeton'), 'std' => 'default', 'options' => $sidebar, 'description' => 'Please select sidebar here. You can add custom sidebar for your need on Sidebar tab of <tt>Theme Options</tt> panel.'),
            'blog_categories' => Array('name' => 'blog_categories', 'rel' => 'page-template-blog.php', 'title' => __('Including blog categories', 'themeton'), 'type' => 'terms', 'term' => 'category', 'std' => '', 'description' => __('Selecte categories include in this page. If you didn\'t select anyone from those categories, page shows with posts from all the categories.', 'themeton')),
            'posts_perpage' => Array('name' => 'posts_perpage', 'rel' => 'page-template-blog.php', 'title' => __('Pagination number', 'themeton'), 'type' => 'text', 'std' => '12', 'description' => __('The number is used for posts as displaying a limited number of results on this page.', 'themeton')),
            'hide_content' => Array('name' => 'hide_content', 'rel' => 'page-template-blog.php', 'type' => 'checkbox', 'description' => __('If turn it ON, posts show without content on this page.', 'themeton'), 'title' => __('Hide content?', 'themeton')),
            'post_contshow' => Array('name' => 'post_contshow', 'rel' => 'page-template-blog.php', 'title' => 'Content control of posts', 'type' => 'select', 'std' => 'Full', 'options' => Array('Hide', 'Full', '5', '10', '20', '30', '40', '50', '60', '70', '80', '90', '100'), 'description' => '- <tt>Full</tt> means show post contents and If there have excerpts or read more split on content of posts, content shows for those. <br>- Number selections are count of words splitting of this blog posts.'),
            'order_type' => Array('name' => 'order_type', 'rel' => 'page-template-blog.php', 'type' => 'select', 'std' => 'category', 'options' => Array('Date', 'Date ASC', 'Title', 'Title ASC', 'Random', 'Most liked'), 'description' => __('Select order type of posts. If you select Random order here, your posts show random ordering every 5 minutes. If we don\'t know current random order, database query rendering new random order for your next page (pagination result) and we will see duplicate posts sometimes.', 'themeton'), 'title' => __('Order type', 'themeton')),
        )
    ));

    $tt_framework->admin->addMeta(Array(
        'type' => 'post',
        'title' => 'Post additional options',
        'meta_boxes' => Array(
            'custom_bg' => Array('name' => 'custom_bg', 'show' => '#bg_color,#dark_light,#post_style', 'type' => 'checkbox', 'description' => __('If turn this option ON, your current post background color will define your selected color on Blog/Category page.', 'themeton'), 'title' => __('Custom background color?', 'themeton')),
            'bg_color' => Array('name' => 'bg_color', 'type' => 'color', 'description' => __('Please choose custom color here.', 'themeton'), 'title' => __('Background color', 'themeton'), 'std' => '#FFFFFF'),
            'post_meta' => Array('name' => 'post_meta', 'type' => 'checkbox', 'description' => __('If turn it ON, post will show without meta on single', 'themeton'), 'title' => __('Hide post meta?', 'themeton')),
            'meta_author' => Array('name' => 'meta_author', 'type' => 'selectbox', 'description' => __('If turn it ON, post will show WITH author link on blog/category pages', 'themeton'), 'title' => __('Show Post Author ?', 'themeton')),
            'image_hide' => Array('name' => 'image_hide', 'type' => 'selectbox', 'description' => __('If turn it ON, your post will show with Featured Image in single post entered.', 'themeton'), 'title' => __('Show featured images on single?', 'themeton')),
            'is_featured_post' => Array('name' => 'is_featured_post', 'type' => 'checkbox', 'description' => __('If turn it ON, your post size displays larger than regular posts.', 'themeton'), 'title' => __('Is this featured?', 'themeton')),
            'custom_link' => Array('name' => 'custom_link', 'type' => 'checkbox', 'show' => '#custom_link_url,#custom_link_target', 'title' => __('Custom link', 'themeton'), 'description' => 'If it turned ON, this post will link to custom link.'),
            'custom_link_url' => Array('name' => 'custom_link_url', 'type' => 'text', 'title' => __('Custom link url', 'themeton'), 'description' => 'Enter your URL here.'),
            'custom_link_target' => Array('name' => 'custom_link_target', 'type' => 'select', 'title' => __('Custom link target', 'themeton'), 'options' => array('_self', '_blank'), 'description' => 'Select link target. _blank will be opened new window.'),
            'custom_layout' => Array('name' => 'custom_layout', 'rel' => 'default', 'type' => 'layouts', 'description' => __('Choose the sidebar layout for this specific post. You can choose Full width or Right sidebar.', 'themeton'), 'options' => Array('select sidebar position' => $s_layout), 'title' => __('Page layout', 'themeton'), 'std' => '1-1-1'),
			'custom_sidebar' => Array('name' => 'custom_sidebar', 'type' => 'select', 'title' => __('Select sidebar', 'themeton'), 'std' => 'default', 'options' => $sidebar, 'description' => 'Please select sidebar here. You can add custom sidebar for your need on Sidebar tab of <tt>Theme Options</tt> panel.'),
        )
    ));
}

if ( ! function_exists( 'showBrief' ) ) :
function showBrief($str, $length) {
    $str = strip_tags($str);
    $str = explode(" ", $str);
    return implode(" ", array_slice($str, 0, $length));
}endif;

if (isset($_REQUEST['like_it'])) {
    global $wpdb;
    $ip = $_SERVER['REMOTE_ADDR'];
    $pid = $_REQUEST['like_it'];
    $liked = get_post_meta($pid, 'post_liked', true);
    if (!isset($_COOKIE['liked-' . $pid])) {

        if ($liked == '') {
            $liked = 1;
            add_post_meta($pid, 'post_liked', 1);
            $lk = 1;
        } else {
            $lk = (intval($liked) + 1);
            update_post_meta($pid, 'post_liked', $lk);
        }
        setcookie('liked-' . $pid, 1);
    }
    print $lk . ' like';
    if ($lk > 1)
        echo's';
    die;
}

if (isset($_REQUEST['fav_it'])) {
    global $wpdb;
    $pid = $_REQUEST['fav_it'];
    $user_id = get_current_user_id();
    $favorite = get_user_meta($user_id, 'post_favorite', true);

    if ($favorite == '') {
        add_user_meta($user_id, 'post_favorite', array($pid));
    } else {
        if(in_array($pid, $favorite)) {
            $tmp=array();
            foreach ($favorite as $k ){
                if($pid!=$k){
                    $tmp[]=$k;
                }
            }
            $favorite=$tmp;            
        } else {
            array_unshift($favorite, $pid);
        }
        update_user_meta($user_id, 'post_favorite', $favorite);
    }
    die;
}

if (isset($_REQUEST['tt_add_post'])) {
    global $data;
    if(is_user_logged_in()){
        $current_user = wp_get_current_user();
        $post_id      = isset($_REQUEST['post_id'])&&!empty($_REQUEST['post_id']) ? $_REQUEST['post_id']:0 ;

        $default_post_status = (isset($data['default_posts_status'])&&$data['default_posts_status'] ? $data['default_posts_status']:'draft');
        $default_post_status = (esc_attr(get_the_author_meta( 'enable_auto_publish', $current_user->ID ))==='true')?'publish':$default_post_status;
        if($post_id!=0){
            $post_data = get_post($post_id, ARRAY_A);
            if($post_data['post_status']=='publish'){$default_post_status='publish';}
        }
        $post_tags     =isset($_REQUEST['post_tags'])     ?$_REQUEST['post_tags']     :'';
        $post_title    =isset($_REQUEST['post_title'])    ?$_REQUEST['post_title']    :'';
        $post_slug     =isset($_REQUEST['post_slug'])     ?$_REQUEST['post_slug']     :'';
        $post_format   =isset($_REQUEST['post_format'])   ?$_REQUEST['post_format']   :'standard';
        $post_content  =isset($_REQUEST['post_content'])  ?$_REQUEST['post_content']  :'';
        $post_excerpt  =isset($_REQUEST['post_excerpt'])  ?$_REQUEST['post_excerpt']  :'';
        $post_category =isset($_REQUEST['post_category']) ?$_REQUEST['post_category'] :array(1) ;
        $post_image_url=isset($_REQUEST['post_image_url'])?$_REQUEST['post_image_url']:'';
        $post_status   =isset($_REQUEST['delete'])&&$_REQUEST['delete'] ? 'trash':$default_post_status;
        $post_image_id =$post_image_url!=''? get_attachment_id_by_src($post_image_url):'';
        
        $post_args = array(
            'post_title'    => $post_title,
            'post_name'     => $post_slug,
            'post_status'   => $post_status,
            'post_content'  => $post_content,
            'post_excerpt'  => $post_excerpt,
            'post_type'     => 'post',
            'post_author'   => $current_user->ID,
            'tags_input'    => $post_tags,
            'post_category' => $post_category
        );
        if($post_status=='trash'&&$post_id>0){
            $isPostAuthor=false;
            query_posts( 'p='.$post_id );
            if( have_posts() ){the_post();if($post->post_author == $current_user->ID){$isPostAuthor=true;}}
            wp_reset_query();
            if($isPostAuthor){
                $post_id = wp_update_post(array('ID'=>$post_id,'post_status'=>$post_status));
            }else{
                $post_id=false;
                $post_status=__('Not your post.','themeton');
            }
        }elseif($post_id==0){
            $post_id = wp_insert_post($post_args);
            set_post_format( $post_id , $post_format );
            $post_data = get_post($post_id, ARRAY_A);
            echo '<div class="hide"><div class="id">'.$post_id.'</div><div class="slug">'.$post_data['post_name'].'</div></div>';
        } else {
            $isPostAuthor=false;
            query_posts( 'p='.$post_id );
            if( have_posts() ){the_post();if($post->post_author == $current_user->ID){$isPostAuthor=true;}}
            wp_reset_query();
            if($isPostAuthor){
                $post_args['ID'] = $post_id;
                $post_id = wp_update_post($post_args);
                set_post_format( $post_id , $post_format );
                $post_data = get_post($post_id, ARRAY_A);
                echo '<div class="hide"><div class="id">'.$post_id.'</div><div class="slug">'.$post_data['post_name'].'</div></div>';
            }else{
                $post_id=false;
                $post_status=__('Not your post.','themeton');
            }
        }

        if($post_id && $post_image_id && get_post_thumbnail_id($post_id) != $post_image_id){set_post_thumbnail($post_id, $post_image_id);}
        
        require_once FRAMEWORKPATH . '/includes/admin/post_format.php';
        save_post_format_meta($post_id);
        
        if($post_id){
            _e('Post status: ','themeton');
            if($post_status=='trash'){
                echo 'Deleted. %delete%';
            }else{
                echo $post_status.'%update%';
            }
        }else{
            _e('Failed!!! ','themeton');
            echo $post_status;
        }
    }else{
        _e('Not loged in.','themeton');
    }
    die;
}

if (isset($_POST['tt_user_profile'])){
    global $data;
    if(is_user_logged_in()){
        $current_user = wp_get_current_user();
        $user_id      = $current_user->ID;
        if($_POST['user_id']==$user_id){
            $update_first   = update_user_meta($user_id, 'first_name', $_POST['user_firstname'] );
            $update_last    = update_user_meta($user_id, 'last_name',  $_POST['user_lastname']  );
            $update_fb_url  = update_user_meta($user_id, 'fb_url',     $_POST['user_fb_url']    );
            $update_tw_url  = update_user_meta($user_id, 'tw_url',     $_POST['user_tw_url']    );
            $update_email   = wp_update_user( array ('ID' => $user_id,'user_email'=>$_POST['user_email'] ) );
            $update_url     = wp_update_user( array ('ID' => $user_id,'user_url'=>$_POST['user_url'] ) );
            $update_aim     = wp_update_user( array ('ID' => $user_id,'aim'=>$_POST['user_aim'] ) );
            $update_yim     = wp_update_user( array ('ID' => $user_id,'yim'=>$_POST['user_yim'] ) );
            $update_jabber  = wp_update_user( array ('ID' => $user_id,'jabber'=>$_POST['user_jabber'] ) );
            $update_desc    = wp_update_user( array ('ID' => $user_id,'description'=>$_POST['user_description'] ) );
            $update_password= empty($_POST['new_password'])?true:wp_update_user( array ('ID' => $user_id,'user_pass'=>$_POST['new_password'] ) );
            if($update_first||$update_last||$update_email||$update_url||$update_aim||$update_yim||$update_jabber||$update_desc||$update_fb_url||$update_tw_url||$update_password){
                _e('Profile updated','themeton');
            }else{
                _e('Profile not updated','themeton');
            }
        }else{
            _e('Not your profile. Refresh this page.','themeton');
        }
    }else{
        _e('Not loged in','themeton');
    }
    die;
}

// Get User Forms
if (isset($_POST['tt_get_user_login_form']))      {if(is_user_logged_in()){user_bar();}else{user_login_form();}      die;}
if (isset($_POST['tt_get_user_reset_form']))      {if(is_user_logged_in()){user_bar();}else{user_reset_form();}      die;}
if (isset($_POST['tt_get_user_register_form']))   {if(is_user_logged_in()){user_bar();}else{user_register_form();}   die;}
if (isset($_POST['tt_get_current_user_gravatar'])){tt_get_user_avatar(); die;} 

//User Bar
class TT_User_bar extends WP_Widget{
    function __construct() {
        $params=array(
            'discription'=>'Themeton User Bar Description',
            'name'       =>'Themeton User Bar'
        );
        parent::__construct('tt_user_bar','TT_User_bar',$params);
    }
    
    public function form($instance){
        extract($instance); ?>
        <p>
            <label for="">Title:</label>
            <input 
                class="widefat"
                id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php if(isset($title)) echo esc_attr($title); ?>"
                />
        </p>    
            <?php        
    }
    
    public function widget($args, $instance){
        extract($args);
        extract($instance);
        $title=apply_filters('widget_title',$title);
        echo $before_widget;
            echo empty($title)?'':$before_title.$title.$after_title;
            user_bar(true);
        echo $after_widget;
            
    }
}
register_widget('TT_User_bar'); 



function tt_breadcrumbs() {

	$showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
	$delimiter = ''; // delimiter between crumbs
	$home = __('Home', 'themeton'); // text for the 'Home' link
	$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
	$before = '<li class="active">'; // tag before the current crumb
	$after = '</li>'; // tag after the current crumb

	global $post;
	$homeLink = get_bloginfo('url');

	if (is_home() || is_front_page()) {

		if ($showOnHome == 1) 
			echo '<ul class="tt-breadcrumb"><li><a href="' . $homeLink . '">' . $home . '</a></li>';

	} else {

		echo '<ul class="tt-breadcrumb"><li><a href="' . $homeLink . '">' . $home . '</a></li>';

		if ( is_category() ) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				echo '<li>'.get_category_parents($thisCat->parent, TRUE, '</li><li>');
				echo __('Archive by category','themeton') .' "' . single_cat_title('', false) . '"' . $after;
			} else {
				echo $before . __('Archive by category','themeton') .' "' . single_cat_title('', false) . '"' . $after;
			}
		} elseif(is_tax('portfolios')){
				echo $before . __('Archive by portfolio','themeton') .' "' . single_cat_title('', false) . '"' . $after;
		} elseif(is_tax()){
				$term = get_queried_object();
				$tax = get_taxonomy($term->taxonomy);
				echo $before . __('Archive by ','themeton') .$tax->labels->singular_name.' "' . single_cat_title('', false) . '"' . $after;
		} elseif ( is_search() ) {
		echo $before . __('Search results for','themeton') .' "' . get_search_query() . '"' . $after;
		} elseif ( is_day() ) {
		echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
		echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li>';
		echo $before . get_the_time('d') . $after;

		} elseif ( is_month() ) {
		echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
		echo $before . get_the_time('F') . $after;

		} elseif ( is_year() ) {
		echo $before . get_the_time('Y') . $after;

		} elseif ( is_single() && !is_attachment() ) {
		if ( get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			$slug = $post_type->rewrite;
			echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
			if ($showCurrent == 1) echo $before . get_the_title() . $after;
		} else {
			$cat = get_the_category(); $cat = isset($cat[0]) ? $cat[0] : 1;
			$cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
			echo '<li>'.$cats.'</li>';
			if ($showCurrent == 1) echo $before . get_the_title() . $after;
		}

		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
		$post_type = get_post_type_object(get_post_type());
		echo $before . $post_type->labels->singular_name . $after;

		} elseif ( is_attachment() ) {
		$parent = get_post($post->post_parent);
		$cat = get_the_category($parent->ID); $cat = $cat[0];
		echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
		if ($showCurrent == 1) echo $before . get_the_title() . $after;

		} elseif ( is_page() && !$post->post_parent ) {
		if ($showCurrent == 1) echo $before . get_the_title() . $after;

		} elseif ( is_page() && $post->post_parent ) {
		$parent_id  = $post->post_parent;
		$breadcrumbs = array();
		while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
			$parent_id  = $page->post_parent;
		}
		$breadcrumbs = array_reverse($breadcrumbs);
		for ($i = 0; $i < count($breadcrumbs); $i++) {
			echo $breadcrumbs[$i];
			if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
		}
		if ($showCurrent == 1) echo $before . get_the_title() . $after;

		} elseif ( is_tag() ) {
		echo $before . __('Posts tagged','themeton').' "' . single_tag_title('', false) . '"' . $after;

		} elseif ( is_author() ) {
		global $author;
		$userdata = get_userdata($author);
		echo $before . __('Articles posted by','themeton').' ' . $userdata->display_name . $after;

		} elseif ( is_404() ) {
		echo $before . __('Error 404','themeton') . $after;
		}

		if ( get_query_var('paged') ) {
		if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
		echo __('Page','themeton') . ' ' . get_query_var('paged');
		if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}

		echo '</ul>';

	}
} // end breadcrumbs()

?>