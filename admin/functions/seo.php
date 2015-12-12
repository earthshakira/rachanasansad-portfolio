<?php

if (is_admin()) {
    global $seo;
    if (isset($seo['seo_on']) && $seo['seo_on']) {
        $keyword = "";
        $title = "";
        $description = "";
        if (isset($_GET['action']) && isset($_GET['post'])) {
            $post = get_post($_GET['post']);
            $type = get_post_type($_GET['post']);
            $seo_option = get_post_meta($_GET['post'], 'seo_options', true);
            if ($type == 'post') {
                if (!isset($seo_option['seo_keyword']) || $seo_option['seo_keyword'] == "") {
                    if ($seo['category_keyword'])
                        $keyword .= seo_replace('[category]', (array) $post);
                    if ($seo['tag_keyword']) {
                        $tag = seo_replace('[tag]', (array) $post);
                        $keyword .= ( $keyword != '' && $tag != '') ? (", " . $tag) : ($keyword == '' ? $tag : "");
                    }
                    if ($seo['title_keyword'])
                        $keyword .= $keyword != '' ? (", " . str_replace(' ', ', ', $post->post_title)) : str_replace(' ', ', ', $post->post_title);
                }
                if (!isset($seo_option['seo_title']) || $seo_option['seo_title'] == "")
                    $title = seo_replace($seo['post_title'], (array) $post);
                if (!isset($seo_option['seo_desc']) || $seo_option['seo_desc'] == "")
                    $description = seo_replace($seo['post_desc'], (array) $post);
            } elseif ($type == 'page') {
                if (!isset($seo_option['seo_keyword']) || $seo_option['seo_keyword'] == "") {
                    if ($seo['title_keyword'])
                        $keyword .= $keyword != '' ? (", " . str_replace(' ', ', ', $post->post_title)) : str_replace(' ', ', ', $post->post_title);
                }
                if (!isset($seo_option['seo_title']) || $seo_option['seo_title'] == "")
                    $title = seo_replace($seo['page_title'], (array) $post);
                if (!isset($seo_option['seo_desc']) || $seo_option['seo_desc'] == "")
                    $description = seo_replace($seo['page_desc'], (array) $post);
            } elseif ($type == 'portfolios') {
                if (!isset($seo_option['seo_keyword']) || $seo_option['seo_keyword'] == "") {
                    if ($seo['category_keyword'])
                        $keyword .= seo_replace('[term_title]', (array) $post);
                    if ($seo['title_keyword'])
                        $keyword .= $keyword != '' ? (", " . str_replace(' ', ', ', $post->post_title)) : str_replace(' ', ', ', $post->post_title);
                }
                if (!isset($seo_option['seo_title']) || $seo_option['seo_title'] == "")
                    $title = seo_replace($seo['portfolio_title'], (array) $post);
                if (!isset($seo_option['seo_desc']) || $seo_option['seo_desc'] == "")
                    $description = seo_replace($seo['portfolio_desc'], (array) $post);
            }
        }
        $meta_boxes = Array(
            'seo_title' => array('name' => 'seo_title', 'title' => __('SEO title', 'themeton'), 'description' => __('The <tt>Title</tt> field contains the information that will appear in the &lt;title&gt; tag of the page’s HTML. This is the title that appears at the top of your browser window, and is the title that is visible in search engine results pages (SERPs). For optimum SEO purposes, this should be a unique value for each and every page.', 'themeton'), 'type' => 'text', 'std' => $title),
            'seo_desc' => Array('name' => 'seo_desc', 'title' => __('SEO description', 'themeton'), 'description' => __('This field contains the information that will appear in the ‘description’ meta tag of the HTML. Typically this information will appear as a page summary on SERPs, and best practices dictate that this be unique for every page as well.', 'themeton'), 'type' => 'textarea', 'std' => $description),
            'seo_keyword' => Array('name' => 'seo_keyword', 'title' => __('SEO keywords', 'themeton'), 'description' => __('The <tt>Keywords</tt> field contains the information that will appear in the ‘keywords’ meta tag of the HTML. Best practices dictate that the list of keywords should be unique for each page, and ideally be comma-separated with each word capitalized.', 'themeton'), 'type' => 'textarea', 'std' => $keyword),
            'seo_index' => Array('name' => 'seo_index', 'title' => __('SEO index', 'themeton'), 'description' => __('This option helps you to specify the way your page will be crawled by the search engine.', 'themeton'), 'type' => 'checkbox', 'std' => 'checked'),
            'seo_follow' => Array('name' => 'seo_follow', 'title' => __('SEO follow', 'themeton'), 'description' => __('This option helps you to specify the way your page will be crawl the rest of the pages.', 'themeton'), 'type' => 'checkbox', 'std' => 'checked'),
        );
              
        $tt_framework = new wp_tt_framework();
        $tt_framework->init();

        if ($tt_framework->admin) {
            if (isset($seo['seo_metabox']['post']) && $seo['seo_metabox']['post']) {
                $seo_post = Array(
                    'type' => 'post',
                    'title' => __('SEO Options', 'themeton'),
                    'id' => 'seo_options',
                    'priority' => 'advanced',
                    'meta_boxes' => $meta_boxes
                );
                $tt_framework->admin->addMeta($seo_post);
            }

            if (isset($seo['seo_metabox']['page']) && $seo['seo_metabox']['page']) {
                $seo_page = Array(
                    'type' => 'page',
                    'title' => __('SEO Options', 'themeton'),
                    'id' => 'seo_options',
                    'priority' => 'advanced',
                    'meta_boxes' => $meta_boxes
                );
                $tt_framework->admin->addMeta($seo_page);
            }
        }
    }
}

function seo_terms($id, $taxonomy) {
    // If we're on a specific tag, category or taxonomy page, return that and bail.
    if (is_category() || is_tag() || is_tax()) {
        global $wp_query;
        $term = $wp_query->get_queried_object();
        return $term->name;
    }

    $output = '';
    $terms = get_the_terms($id, $taxonomy);
    if ($terms) {
        foreach ($terms as $term) {
            $output .= isset($term->name) ? $term->name . ', ' : '';
        }
        return rtrim(trim($output), ',');
    }
    return '';
}

function seo_replace($string, $args, $omit = array()) {
    global $wp_query;

    $string = strip_tags($string);
    // Let's see if we can bail super early.
    if (strpos($string, '[') === false && strpos($string, ']') === false)
        return trim(preg_replace('/\s+/', ' ', $string));

    $simple_replacements = array(
        '[sitename]' => get_bloginfo('name'),
        '[sitedesc]' => get_bloginfo('description'),
        '[currenttime]' => date('H:i'),
        '[currentdate]' => date('M jS Y'),
        '[currentmonth]' => date('F'),
        '[currentyear]' => date('Y'),
    );

    foreach ($simple_replacements as $var => $repl) {
        $string = str_replace($var, $repl, $string);
    }

    $defaults = array(
        'ID' => '',
        'name' => '',
        'post_author' => '',
        'post_content' => '',
        'post_date' => '',
        'post_content' => '',
        'post_excerpt' => '',
        'post_modified' => '',
        'post_title' => '',
        'taxonomy' => '',
        'term_id' => '',
    );

    $pagenum = get_query_var('paged');
    if ($pagenum === 0) {
        if ($wp_query->max_num_pages > 1)
            $pagenum = 1;
        else
            $pagenum = '';
    }

    if (isset($args['post_content']))
        $args['post_content'] = $args['post_content'];
    if (isset($args['post_excerpt']))
        $args['post_excerpt'] = $args['post_excerpt'];

    $r = (object) wp_parse_args($args, $defaults);

    // Only global $post on single's, otherwise some expressions will return wrong results.
    if (is_singular() || ( is_front_page() && 'posts' != get_option('show_on_front') )) {
        global $post;
    }

    // Let's do date first as it's a bit more work to get right.
    if ($r->post_date != '') {
        $date = mysql2date(get_option('date_format'), $r->post_date);
    } else {
        if (get_query_var('day') && get_query_var('day') != '') {
            $date = get_the_date();
        } else {
            if (single_month_title(' ', false) && single_month_title(' ', false) != '') {
                $date = single_month_title(' ', false);
            } else if (get_query_var('year') != '') {
                $date = get_query_var('year');
            } else {
                $date = '';
            }
        }
    }

    $replacements = array(
        '[date]' => $date,
        '[title]' => stripslashes($r->post_title),
        '[excerpt]' => (!empty($r->post_excerpt) ) ? strip_tags($r->post_excerpt) : substr(strip_shortcodes(strip_tags($r->post_content)), 0, 155),
        '[category]' => seo_terms($r->ID, 'category'),
        '[category_description]' => strip_tags(category_description()), // ? trim(strip_tags(get_term_field( 'description', $r->term_id, $r->taxonomy ))) : 'abs',
        '[tag_description]' => !empty($r->taxonomy) ? trim(strip_tags(get_term_field('description', $r->term_id, $r->taxonomy))) : '',
        '[term_description]' => !empty($r->taxonomy) ? trim(strip_tags(get_term_field('description', $r->term_id, $r->taxonomy))) : '',
        '[term_title]' => seo_terms($r->ID, 'catalog'),
        '[tag]' => seo_terms($r->ID, 'post_tag'),
        //'%%id%%'					=> $r->ID,
        '[author_name]' => get_the_author_meta('display_name', !empty($r->post_author) ? $r->post_author : get_query_var('author')),
        '[searchphrase]' => esc_html(get_query_var('s')),
        '[pagenumber]' => ( get_query_var('paged') != 0 ) ? 'Page ' . get_query_var('paged') : '',
        '[author_description]' => get_the_author_meta('description', !empty($r->post_author) ? $r->post_author : get_query_var('author')),
    );

    foreach ($replacements as $var => $repl) {
        if (!in_array($var, $omit))
            $string = str_replace($var, $repl, $string);
    }

    if (strpos($string, '[') === false && strpos($string, ']') === false) {
        $string = preg_replace('/\s\s+/', ' ', $string);
        return trim($string);
    }
}

function seo_images($content) {
    return preg_replace_callback('/<img[^>]+/', 'seo_images_replace', $content);
}

add_filter('the_content', 'seo_images', 100);

function seo_images_replace($matches) {
    global $post, $seo;

    # take care of unsusal endings
    $matches[0] = preg_replace('|([\'"])[/ ]*$|', '\1 /', $matches[0]);

    ### Normalize spacing around attributes.
    $matches[0] = preg_replace('/\s*=\s*/', '=', substr($matches[0], 0, strlen($matches[0]) - 2));
    ### Get source.

    preg_match('/src\s*=\s*([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/', $matches[0], $source);

    $saved = $source[2];

    ### Swap with file's base name.
    preg_match('%[^/]+(?=\.[a-z]{3}\z)%', $source[2], $source);
    ### Separate URL by attributes.
    $pieces = preg_split('/(\w+=)/', $matches[0], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    ### Add missing pieces.

    if (!in_array('title=', $pieces) || $seo['override_title']) {
        $titletext = isset($source[0]) ? str_replace("[image_name]", $source[0], $seo['image_title']) : '';
        $titletext = seo_replace($titletext, (array) $post);

        if (!in_array('title=', $pieces)) {
            array_push($pieces, ' title="' . $titletext . '"');
        } else {
            $key = array_search('title=', $pieces);
            $pieces[$key + 1] = '"' . $titletext . '" ';
        }
    }

    if (!in_array('alt=', $pieces) || $seo['override_alt']) {
        $alttext = isset($source[0]) ? str_replace("[image_name]", $source[0], $seo['image_alt']) : '';
        $alttext = seo_replace($alttext, (array) $post);

        if (!in_array('alt=', $pieces)) {
            array_push($pieces, ' alt="' . $alttext . '"');
            if (is_page())
                $i = 1;
        } else {
            $key = array_search('alt=', $pieces);
            $pieces[$key + 1] = '"' . $alttext . '" ';
        }
    }

    return implode('', $pieces) . ' /';
}

?>