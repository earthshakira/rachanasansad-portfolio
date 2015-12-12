<?php
/**
 */

class WPBakeryShortCode_VC_Teaser_grid extends WPBakeryShortCode {

    protected function content( $atts, $content = null ) {
        global $blogConf;
        $grid_with_sidebar= strpos($blogConf['content_position'], 'with-sidebar')===false ? 0:1;
        $title = $grid_columns_count = $grid_teasers_count = $grid_layout = $grid_link = '';
        $grid_template = $grid_thumb_size = $grid_posttypes = $grid_categories = $posts_in = $posts_not_in = '';
        $grid_content = $el_class = $width = $orderby = $order = $el_position = $isotope_item = '';
        extract(shortcode_atts(array(
            'title' => '',
            'grid_columns_count' => 2,
            'grid_teasers_count' => 8,
            'grid_layout' => 'thumbnail', // title_thumbnail_text, thumbnail_title_text, thumbnail_text, thumbnail_title, thumbnail, title_text
            'grid_link' => 'link_post', // link_post, link_image, link_image_post, link_no
            'grid_template' => 'carousel', //grid, carousel
//            'grid_thumb_size' => 'thumbnail',
            'grid_thumb_height' => '200',
            'grid_posttypes' => '',
            'grid_categories' => '',
            'posts_in' => '',
            'posts_not_in' => '',
//            'grid_content' => 'teaser', // teaser, content
            'text_count'=> '150',
            'el_class' => '',
            'link_text' => '',
            'link_url' => '',
            'width' => '1/1',
            'orderby' => NULL,
            'order' => 'DESC',
            'el_position' => ''
        ), $atts));

        if ( $grid_template == 'grid' ) {
            wp_enqueue_script( 'isotope' );
            $isotope_item = 'isotope-item ';
        } else if ( $grid_template == 'carousel' ) {
            wp_enqueue_script( 'jcarousellite' );
            $isotope_item = '';
            $grid_layout = 'thumbnail';
        } else if ( $grid_template == 'block' ) {
            $grid_thumb_height = "60";
            $grid_columns_count = 1;
            $grid_layout = 'thumbnail_title_text';
        }

//        if ( $grid_link == 'link_image' || $grid_link == 'link_image_post' ) {
//            wp_enqueue_script( 'prettyphoto' );
//            wp_enqueue_style( 'prettyphoto' );
//        }


        $output = '';

        $el_class = $this->getExtraClass( $el_class );
        $width = wpb_translateColumnWidthToSpan( $width );
        $li_span_class = wpb_translateColumnsCountToSpanClass( $grid_columns_count );

        $query_args = array();

        $not_in = array();
        if ( $posts_not_in != '' ) {
            $posts_not_in = str_ireplace(" ", "", $posts_not_in);
            $not_in = explode(",", $posts_not_in);
        }


        //exclude current post/page from query
        if ( $posts_in == '' ) {
            global $post;
            array_push($not_in, $post->ID);
        }
        else if ( $posts_in != '' ) {
            $posts_in = str_ireplace(" ", "", $posts_in);
            $query_args['post__in'] = explode(",", $posts_in);
        }
        if ( $posts_in == '' || $posts_not_in != '' ) {
            $query_args['post__not_in'] = $not_in;
        }

        // Post teasers count
        if ( $grid_teasers_count != '' && !is_numeric($grid_teasers_count) ) $grid_teasers_count = -1;
        if ( $grid_teasers_count != '' && is_numeric($grid_teasers_count) ) $query_args['posts_per_page'] = $grid_teasers_count;

        // Post types
        $pt = array();
        if ( $grid_posttypes != '' ) {
            $grid_posttypes = explode(",", $grid_posttypes);
            foreach ( $grid_posttypes as $post_type ) {
                array_push($pt, $post_type);
            }
            $query_args['post_type'] = $pt;
        }

        // Narrow by categories
        if ( $grid_categories != '' ) {
            $grid_categories = explode(",", $grid_categories);
            $gc = array();
            foreach ( $grid_categories as $grid_cat ) {
                array_push($gc, $grid_cat);
            }
            $gc = implode(",", $gc);
            ////http://snipplr.com/view/17434/wordpress-get-category-slug/
            $query_args['category_name'] = $gc;

            $taxonomies = get_taxonomies('', 'object');
            $query_args['tax_query'] = array('relation' => 'OR');
            foreach ( $taxonomies as $t ) {
                if ( in_array($t->object_type[0], $pt) ) {
                    $query_args['tax_query'][] = array(
                        'taxonomy' => $t->name,//$t->name,//'portfolio_category',
                        'terms' => $grid_categories,
                        'field' => 'slug',
                    );
                }
            }
        }

        // Order posts
        if ( $orderby != NULL ) {
            $query_args['orderby'] = $orderby;
        }
        $query_args['order'] = $order;

        // Run query
        $my_query = new WP_Query($query_args);

        //global $_wp_additional_image_sizes;

        $teasers = '';
        while ( $my_query->have_posts() ) {
            $link_title_start = $link_image_start = $p_link = $link_image_end = $p_img_large = '';

            $my_query->the_post();
            $post_title = the_title("", "", false);
            $post_id = $my_query->post->ID;
            $teaser_post_type = 'posts_grid_teaser_'.$my_query->post->post_type . ' ';
            //$content = ( $grid_content == 'teaser' ) ? get_the_excerpt() : get_the_content();
            $content = get_the_content('read more');
            $content = substr($content,0,intval($text_count));
            $content = wpautop($content);
            $content = strip_tags(stripslashes($content));
            
            $link = '';
            $thumbnail = '';
            $category = get_the_term_list( $post->ID, array('category', 'catalog'), '', ', ', '');

            // Thumbnail logic
            if ( in_array($grid_layout, array('title_thumbnail_text', 'thumbnail_title_text', 'thumbnail_title_category', 'thumbnail_text', 'thumbnail_title', 'thumbnail', 'title_text') ) ) {
                $post_thumbnail = $p_img_large = '';
                //$attach_id = get_post_thumbnail_id($post_id);

                $post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post_id, 'thumb_size' => $grid_thumb_height, 'grid_columns_count' => ($grid_template == 'block')?5:$grid_columns_count, 'grid_with_sidebar' => ($grid_template == 'block')? 0:$grid_with_sidebar ));
                //print_r($post_thumbnail);
                $thumbnail = $post_thumbnail['thumbnail'];
                $p_img_large = $post_thumbnail['p_img_large'];
            }

            // Link logic
            if ( $grid_template != 'block' ) {
                $p_video = '';
                if ( $grid_link == 'link_image' || $grid_link == 'link_image_post' ) {
                    $p_video = get_post_meta($post_id, "_p_video", true);
                }
                $p_link = $p_img_large;               
                $link_image_start = '';
                $link_image_end .= '<a class="prettyphoto recent-view" href="'.$p_link.'" title="'.the_title_attribute('echo=0').'"><img src="'.get_template_directory_uri().'/images/recent-zoom.png"></a>';
                $link_image_end .= '<a class="recent-more" href="'.get_permalink($post_id).'" title="'.the_title_attribute('echo=0').'">'.__('View project', 'themeton').'</a>';
                $link_title_start = '<a class="link_title" href="'.get_permalink($post_id).'" title="'.sprintf( esc_attr__( 'Permalink to %s', 'js_composer' ), the_title_attribute( 'echo=0' ) ).'">';
                $link_title_end = '</a>';
            } else {
                $link_image_start = '<a class="link_title" href="'.get_permalink($post_id).'" title="'.sprintf( esc_attr__( 'Permalink to %s', 'js_composer' ), the_title_attribute( 'echo=0' ) ).'">';
                $link_title_start = '<a class="link_title" href="'.get_permalink($post_id).'" title="'.sprintf( esc_attr__( 'Permalink to %s', 'js_composer' ), the_title_attribute( 'echo=0' ) ).'">';
                $link_title_end = $link_image_end = '</a>';
            }
            $teasers .= '<li class="'.$isotope_item.$li_span_class.'">';
            // If grid layout is: Title + Thumbnail + Text
            if ( $grid_layout == 'title_thumbnail_text' ) {
                if ( $post_title ) 	{
                    $to_filter = '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
                    $teasers .= apply_filters('vc_teaser_grid_title', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "title" => $post_title, "media_link" => $p_link) );
                }
                if ( $thumbnail ) {
                    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
                    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
                }
                $teasers .= "<div class='content-block'>";
                if ( $content ) {
                    $to_filter = '<div class="entry-content">' . $content . '</div>';
                    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
                }
                $teasers .= "</div>";
            }
            // If grid layout is: Thumbnail + Title + Text
            else if ( $grid_layout == 'thumbnail_title_text' ) {                
                if ( $thumbnail ) {                    
                    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
                    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
                }
                $teasers .= "<div class='content-block'>";
                if ( $post_title ) 	{
                    $to_filter = '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
                    $teasers .= apply_filters('vc_teaser_grid_title', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "title" => $post_title, "media_link" => $p_link) );
                }
                if ( $content ) {
                    $to_filter = '<div class="entry-content">' . $content . '</div>';
                    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
                }
                $teasers .= "</div>";
                if ( $grid_template == 'block' ) {
                    reset_the_date();
                    $meta = '';
                    $meta .= '<div class="block-date"><i class="list-date"></i>'.get_the_date( 'j F, Y' ).'</div>';
                    //$meta .= '<div class="block-cats"><i class="list-tags"></i>'.get_the_term_list($post->ID, array('catalog', 'category'), '', ', ', '' )."</div>";
                    //$meta .= '<div class="block-author"><i class="list-user"></i>'.get_the_author_posts_link().'</div>';                    
                    $to_filter = '<div class="clearfix"></div><div class="block-meta">' . $meta . '</div>';
                    $teasers .= apply_filters('vc_teaser_grid_meta', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $meta, "media_link" => $p_link) );
                }                
            }
            // If grid layout is: Thumbnail + Title + Category
            else if ( $grid_layout == 'thumbnail_title_category' ) {
                if ( $thumbnail ) {
                    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
                    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
                }
                $teasers .= "<div class='content-block'>";
                if ( $post_title ) 	{
                    $to_filter = '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
                    $teasers .= apply_filters('vc_teaser_grid_title', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "title" => $post_title, "media_link" => $p_link) );
                }
                if ( $category ) {
                    $to_filter = '<div class="entry-category">' . $category . '</div>';
                    $teasers .= apply_filters('vc_teaser_grid_category', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
                }
                $teasers .= "</div>";
            }
            // If grid layout is: Thumbnail + Text
            else if ( $grid_layout == 'thumbnail_text' ) {
                if ( $thumbnail ) {
                    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
                    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
                }
                $teasers .= "<div class='content-block'>";
                if ( $content ) {
                    $to_filter = '<div class="entry-content">' . $content . '</div>';
                    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
                }
                $teasers .= "</div>";
            }
            // If grid layout is: Thumbnail + Title
            else if ( $grid_layout == 'thumbnail_title' ) {
                if ( $thumbnail ) {
                    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
                    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
                }
                $teasers .= "<div class='content-block'>";
                if ( $post_title ) {
                    $to_filter = '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
                    $teasers .= apply_filters('vc_teaser_grid_title', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "title" => $post_title, "media_link" => $p_link) );
                }
                $teasers .= "</div>";
            }
            // If grid layout is: Thumbnail
            else if ( $grid_layout == 'thumbnail' ) {
                if ( $thumbnail ) {
                    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
                    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
                }
            }
            // If grid layout is: Title + Text
            else if ( $grid_layout == 'title_text' ) {
                if ( $post_title ) 	{
                    $to_filter = '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
                    $teasers .= apply_filters('vc_teaser_grid_title', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "title" => $post_title, "media_link" => $p_link) );
                }
                if ( $content ) {
                    $to_filter = '<div class="entry-content">' . $content . '</div>';
                    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
                }
            }
            $teasers .= '</li> ' . $this->endBlockComment('single teaser');
        } // endwhile loop
        wp_reset_query();
        if($grid_template == 'block' && $link_text != ''){
            if(!preg_match_all('!https?://[\S]+!', $link_url, $matches))
                $link_url = "http://" . $link_url;
            $teasers .= '<li class="'.$isotope_item.$li_span_class.'"><div class="recentpost-link"><a href="'.$link_url.'">'.$link_text.'</a><i class="recentpost-link-icon"></i></div></li>';
        }
        $teasers .= '';

        if ( $teasers ) { $teasers = '<ul class="thumbnails wpb_thumbnails-fluid">'. $teasers .'</ul>'; }
        else { $teasers = __("Nothing found." , "js_composer"); }

        $posttypes_teasers = '';

        if ( is_array($grid_posttypes) ) {
            //$posttypes_teasers_ar = explode(",", $grid_posttypes);
            $posttypes_teasers_ar = $grid_posttypes;
            foreach ( $posttypes_teasers_ar as $post_type ) {
                $posttypes_teasers .= 'wpb_teaser_grid_'.$post_type . ' ';
            }
        }

        $grid_class = 'wpb_'.$grid_template . ' columns_count_'.$grid_columns_count . ' grid_layout-'.$grid_layout . ' '  . $grid_layout.'_'.$li_span_class . ' ' . 'columns_count_'.$grid_columns_count.'_'.$grid_layout . ' ' . $posttypes_teasers;

        if(is_page_template('page-home.php') && $grid_template != 'block'){
            $output .= '<div id="ponpon" class="row-fluid ponpon">';
            $output .= '<div class="wpb_content_element span12 home-heading wpb_text_column">';
            $output .= '<div class="wpb_wrapper">';
            $output .= '<h2 class="wpb_heading wpb_teaser_grid_heading">'.$title.'</h2>';
            if ( $grid_template == 'carousel' ) {
                $output .= apply_filters( 'vc_teaser_grid_carousel_arrows', '<div class="teaser_grid_arrow"><a href="#" class="prev">&larr;</a> <a href="#" class="next">&rarr;</a></div>' );
            }elseif($grid_template == 'grid' && $link_text != ''){
                if(!preg_match_all('!https?://[\S]+!', $link_url, $matches))
                    $link_url = "http://" . $link_url;
                $output .= '<div class="recentpost-link"><a href="'.$link_url.'">'.$link_text.'</a><i class="recentpost-link-icon"></i></div>';
            }
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

            $output .= '<div class="row-fluids">';
            $output .= '<div class="wpb_content_element span12 column_container">';
            $output .= '<div class="wpb_wrapper border">';
            $output .= '<div class="row-fluid">';
        }
        
        $output .= '<div class="wpb_teaser_grid wpb_content_element '.$grid_class.$width.$el_class.'">';
        if(is_page_template('page-home.php') && $grid_template == 'block'){
            $output .= '<h2 class="wpb_heading wpb_teaser_grid_heading">'.$title.'</h2>';
            $output .= '<div class="clearfix"></div>';
            $output .= '<div class="wpb_wrapper border">';
        } else {
            $output .= '<div class="wpb_wrapper">';
        }
        if(!is_page_template('page-home.php')){
            $output .= ($title != '' ) ? '<h2 class="wpb_heading wpb_teaser_grid_heading">'.$title.'</h2>' : '';
            if ( $grid_template == 'carousel' ) {
                $output .= apply_filters( 'vc_teaser_grid_carousel_arrows', '<div class="teaser_grid_arrow"><a href="#" class="prev">&larr;</a> <a href="#" class="next">&rarr;</a></div>' );
            }elseif($grid_template == 'grid' && $link_text != ''){
                if(!preg_match_all('!https?://[\S]+!', $link_url, $matches))
                    $link_url = "http://" . $link_url;
                $output .= '<div class="recentpost-link"><a href="'.$link_url.'">'.$link_text.'</a><i class="recentpost-link-icon"></i></div>';
            }
            $output .= '<div class="clearfix"></div>';
        }
        $output .= $teasers;
        $output .= '</div>'.$this->endBlockComment('.wpb_wrapper');
        if(is_page_template('page-home.php') && $grid_template == 'block'){
            $output .= '<div class="double-bg"><div class="left-sdw"></div><div class="right-sdw"></div><div class="repeat-sdw"></div></div>';
        }
        $output .= '</div>'.$this->endBlockComment($width);
        
        if(is_page_template('page-home.php') && $grid_template != 'block'){
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="double-bg"><div class="left-sdw"></div><div class="right-sdw"></div><div class="repeat-sdw"></div></div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}