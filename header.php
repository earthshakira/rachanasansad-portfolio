<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <?php current_title(); ?>
        <?php meta_robots(); ?>
        <?php favicon(); ?>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- Mobile Specific Metas
          ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- IMPORTING CSS FILES -->
                <link rel="stylesheet" href="<?php print get_template_directory_uri() . '/css/bootstrap.min.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php print get_template_directory_uri() . '/css/bootstrap.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php print get_stylesheet_directory_uri() . '/style.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php print get_template_directory_uri() . '/css/bootstrap-responsive.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php print get_template_directory_uri() . '/css/responsive.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php print get_template_directory_uri() . '/css/prettyPhoto.css'; ?>" type="text/css" />
		<link rel="stylesheet" href="<?php print get_stylesheet_directory_uri() . '/css/options.css'; ?>" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <?php
        global $data, $item_size, $post_style, $blogConf, $isBlog;
        $item_size_keys = array_keys($item_size);
        $data['item_size'] = isset($data['item_size']) && $data['item_size'] ? $data['item_size'] : $item_size_keys[1];
        $data['item_size'] = $item_size[$data['item_size']];
        ?>
        <script type="text/javascript">
            var tt_home_uri     = '<?php echo home_url(); ?>';
            var tt_theme_uri    = '<?php echo get_template_directory_uri(); ?>';
            var tt_infinite_img = '<?php echo get_template_directory_uri() . '/images/ajax-loader.gif'; ?>';
            var $infinitescroll =  <?php echo (isset($paged) && $paged) ? $paged : 1; ?>;
            var tt_infinite_loadingMsg  = '<?php _e('Loading the next set of posts...', 'themeton'); ?>';
            var tt_infinite_finishedMsg = '<?php _e('No more pages to load.', 'themeton'); ?>';
			var social_media = '<?php echo isset($data['social_media']) ? $data['social_media'] : ""; ?>';
            var sharethis_key = '<?php echo isset($data['sharethis_key']) ? $data['sharethis_key'] : "0"; ?>';
            var is_header_fixed = '<?php if(isset($data['fixed_header']) && $data['fixed_header']) { echo "fixed"; } ?>';
			var stop_nice_scroll = '<?php if(isset($data['stop_nice_scroll']) && $data['stop_nice_scroll']) { echo "1"; } ?>';
        </script>
        <!-- IMPORTING JS FILES -->
		<?php blog_open_graph_meta(); ?>
        <?php wp_head(); ?>
    </head><?php
        $post_skin = isset($data['post_skin']) ? $post_style[$data['post_skin']] : '';
        $full_page = (isset($blogConf['layout']) && $blogConf['layout'] == '0-1-1') ? 'page-fullwidth' : '';
        $modal_mode = (isset($data['modal_mode']) && !$data['modal_mode']) ? 'no-modal' : '';
        $wrapper_class = (isset($data['left_sidebar']) && !$data['left_sidebar']) ? ' full' : '';
        ?>
    <body <?php body_class("$post_skin $modal_mode $full_page {$data['item_size']}"); ?>>
        <!-- Start Header -->
        <header id="header" class='clearfix'><?php
            $tt_header_styles="";
            if(is_admin_bar_showing()){
                $tt_header_styles="top:28px;";
            }
?>
            <div class="navbar navbar-fixed-top" style="<?php echo $tt_header_styles; ?>" >
                <div class="header-inner clearfix">

                <?php if ($data['full_width'] == 'fixed' || $data['full_width'] == 'only_grid') echo '<div class="container clearfix">'; ?>
                  <div class="row">
                    <div class="col-lg-6 col-md-4 btn-logo-container clearfix">
                        <a href='<?php echo home_url(); ?>'><img src="<?php print get_template_directory_uri() . '/images/logo_head.png'; ?>" class="img-responsive"></img></a>
                    </div>
                    <div class="col-lg-6 col-md-8">
                      <div class="row">
                        <div class="col-sm-4 col-sm-offset-4 search-content-top col-xs-6">
                          <?php if(isset($data['show_breadcrumb']) && $data['show_breadcrumb']) { tt_breadcrumbs(); }
                else { get_search_form(); }?>
                        </div>
                        <div class="col-sm-4 social-icons col-xs-6">   <ul>
                                <li><a href="mailto:contact@rachanasansad.edu.in" class="social-btn-round" title="Email"><i class='fa fa-envelope'></i></a></li>
                                <li><a href="http://rachanasansad.edu.in" class="social-btn-round" title="Email"><i class='fa fa-globe'></i></a></li>
                                <li><a href="https://www.facebook.com/pages/Rachana-Sansad/114928985188914" class="social-btn-round" title="Email"><i class='fa fa-facebook'></i></a></li>
                              </ul></div>
                      </div>
                      <div class="row" style='text-align:center'>
                        <div class="col-sm-12 nav-primary-container " >
                          <!--start of primary nav -->
                                                <div id="options" class="category-list"  >
                                                 <?php
                                                 $categoriestop = get_menu_cat('sideheader');
                                                 $topurlarray=array();
                                                 foreach($categoriestop as $category){
                                                 array_push($topurlarray,$category->url);
                                                 }
                                                 //add data here

                                                 $categories=get_menu_cat('primary-menu');

                                                 if(is_front_page()||in_array(current_page_url(),$topurlarray))
                                                 {
                                                 ?>
                                                         <ul id="filters" class="option-set post-category"  data-option-key="filter">
                                                 <?php
                                                 foreach ($categories as $category) {

                                                 echo'<li><a href="#filter" data-option-value=".category-' . get_category_slug($category->url). '" title="' . $category->title . '" ' . ' class="post-category-item" >' . $category->title . '</a></li>';
                                                 }
                                                 }
                                                 else
                                                 {
                                                 ?>
                                                         <ul id="filters" class=" post-category"  >
                                                 <?php
                                                 foreach ($categories as $category) {
                                                 echo'<li ><a href="'.$category->url.'" title="' . $category->title . '" ' . ' class="post-category-item" >' . $category->title . '</a></li>';
                                                 }
                                                 }
                                                 ?>
                                                       </ul>
                                                   </div>
                                                 <?php tt_get_filter_list($isBlog); ?>
                                                 <!--end of primary nav -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php if ($data['full_width'] == 'fixed' || $data['full_width'] == 'only_grid') echo '</div>'; ?>

                <div class='nav-container' >
                        <?php if ($data['full_width'] == 'fixed' || $data['full_width'] == 'only_grid') echo '<div class="container clearfix">'; ?>
                          <nav class='dept-nav row'>
                            <div id="options" class="category-list top-bar" style='text-align:center'>
                    <?php //accuire categories

                    $categories = get_menu_cat('primary-menu');
                    $mainurlarray=array();
                    foreach($categories as $category){
                      array_push($mainurlarray,$category->url);
                    }

                    $categoriestop = get_menu_cat('sideheader');
                    //$locations = get_nav_menu_locations();
                    //add data here

                    if(is_front_page()||in_array(current_page_url(),$mainurlarray))
                    {
                      ?>
                                  <ul id="top" class="option-set post-category"  data-option-key="filter">
                      <?php
                      $licount=0;
                    foreach($categoriestop as $category) {
                        echo'<li><a href="#filter" data-option-value=".category-' . get_category_slug($category->url). '" title="' . $category->title . '" ' . ' class="post-category-item" >' .get_category_slug($category->url). '</a></li>';
                        //$licount++;
                        if($licount>=6)
                        {
                          echo '<br>';
                          $licount=0;
                        }
                    }
                    }
                    else
                    {
                      ?>
                                            <ul id="top" class=" post-category"  >
                                <?php
                                $licount=0;
                                foreach ($categoriestop as $category) {
                                    echo'<li ><a href="'.$category->url.'" title="' . $category->title . '" ' . ' class="post-category-item" >' . get_category_slug($category->url). '</a></li>';
                                    //$licount++;
                                    if($licount>=6)
                                    {
                                      echo '<br>';
                                      $licount=0;
                                    }
                                }
                              }


                              ?>
                                          </ul>
                            </div>

                          </nav>

                        <?php if ($data['full_width'] == 'fixed' || $data['full_width'] == 'only_grid') echo '</div>'; ?>
                      </div>


                </div>
            </div>
        </header>
        <!-- End Header -->
        <!-- Start Wrapper -->
        <div class="wrapper<?php echo $wrapper_class; ?>">
