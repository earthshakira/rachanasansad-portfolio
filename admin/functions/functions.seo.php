<?php

add_action('init', 'seo_options');

if (!function_exists('seo_options')) {

    function seo_options() {

        $general = array(
            'Select',
            '[sitename]',
            '[sitedesc]',
            '[currenttime]',
            '[currentdate]',
            '[currentmonth]',
            '[currentyear]',
        );
        $general_post = array(
            '[date]',
            '[title]',
            '[excerpt]',
            '[author_name]',
        );

        $post_title = array_merge($general, $general_post);
        $post_title[] = "[category]";
        $post_title[] = "[tag]";

        $page_title = array_merge($general, $general_post);
        $page_title[] = "[pagenumber]";


        $portfolio_title = array_merge($general, $general_post);
        $portfolio_title[] = "[term_title]";

        $tax_title = $general;
        $tax_title[] = "[term_title]";
        $tax_title[] = "[term_description]";
        $tax_title[] = "[pagenumber]";

        $cat_title = $general;
        $cat_title[] = "[category]";
        $cat_title[] = "[category_description]";
        $cat_title[] = "[pagenumber]";

        $search_title = $general;
        $search_title[] = "[searchphrase]";
        $search_title[] = "[pagenumber]";

        $author_title = $general;
        $author_title[] = "[author_name]";
        $author_title[] = "[author_description]";
        $author_title[] = "[pagenumber]";

        $archive_title = $general;
        $archive_title[] = "[pagenumber]";

        $image_title = array("Select", "[title]", "[image_name]", "[category]", "[tag]");

        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */

        global $seo_options;
        $seo_options = array();

        /* ----------------------------- ON PFF SETTINGS ----------------------------------- */

        $seo_options[] = array("name" => "On Off",
            "type" => "heading");

        $seo_options[] = array("name" => "SEO option",
            "desc" => "If you have a plan to use some of SEO plugin instead of Theme SEO, you should turn it OFF. Then theme doesn't print any additional section for SEO o HTML.",
            "id" => "seo_on",
            "std" => 1,
            "folds" => 1,
            "type" => "checkbox");

        $seo_options[] = array("name" => "SEO metabox",
            "desc" => "Select for which content types SEO metabox should be available during post creation/editing.",
            "id" => "seo_metabox",
            "std" => array('post', 'page'),
            "type" => "multicheck",
            "fold" => "seo_on",
            "options" => array("post" => "Post", "page" => "Page"));

        /* -----------------------------  Indexation SETTINGS ----------------------------------- */

        $seo_options[] = array("name" => "Indexation",
            "type" => "heading");

        $seo_options[] = array("name" => "Home page",
            "desc" => "",
            "id" => "home_robot_index",
            "class" => "seo_index",
            "std" => 1,
            "type" => "checkbox");

        $seo_options[] = array("name" => "",
            "desc" => "",
            "id" => "home_robot_follow",
            "std" => 1,
            "class" => "seo_follow",
            "type" => "checkbox");

        $seo_options[] = array("name" => "",
            "desc" => "",
            "id" => "home_robot_other",
            "std" => "",
            "type" => "multicheck",
            "options" => array("noodp" => "noodp", "noydir" => "noydir", "noarchive" => "noarchive", "nosnippet" => "nosnippet"));

        $seo_options[] = array("name" => "Category, archives, search page index",
            "desc" => "",
            "id" => "robot_index",
            "class" => "seo_index",
            "std" => 1,
            "type" => "checkbox");

        $seo_options[] = array("name" => "",
            "desc" => "",
            "id" => "robot_follow",
            "class" => "seo_follow",
            "std" => 1,
            "type" => "checkbox");

        $seo_options[] = array("name" => "",
            "desc" => "",
            "id" => "robot_other",
            "std" => "",
            "type" => "multicheck",
            "options" => array("noodp" => "noodp", "noydir" => "noydir", "noarchive" => "noarchive", "nosnippet" => "nosnippet"));

        $seo_options[] = array("name" => "index desc",
            "id" => "index_desc",
            "std" => "
                INDEX - option helps you to specify the way your page will be crawled by the search engine.<br/>NOINDEX - prevents the page from being included in the index.
                <p>FOLLOW - This option helps you to specify the way your page will be crawl the rest of the pages.<br/>NOFOLLOW - prevents Googlebot from following any links on the page. (Note that this is different from the link-level NOFOLLOW attribute, which prevents Googlebot from following an individual link.)</p>
                <p>NOARCHIVE - prevents a cached copy of this page from being available in the search results.</p>
                <p>NOSNIPPET - prevents a description from appearing below the page in the search results, as well as prevents caching of the page.</p>
                NOODP - blocks the <a href='http://www.dmoz.org/'>Open Directory Project</a> description of the page from being used in the description that appears below the page in the search results.",
            "type" => "info");

        /* ----------------------------- Title and Description SETTINGS ----------------------------------- */

        $seo_options[] = array("name" => "Page title",
            "type" => "heading");

        $seo_options[] = array("name" => "index desc",
            "id" => "title_desc",
            "std" => "The <tt>Title</tt> field contains the information that will appear in the &lt;title&gt; tag of the page’s HTML. This is the title that appears at the top of your browser window, and is the title that is visible in search engine results pages (SERPs). For optimum SEO purposes, this should be a unique value for each and every page.",
            "type" => "info");

        $seo_options[] = array("name" => "Home page title",
            "desc" => "Home page title format.",
            "id" => "home_title",
            "std" => "[sitename] | [sitedesc]",
            "type" => "seo",
            "options" => $page_title);

        $seo_options[] = array("name" => "Post title",
            "desc" => "Single post title format.",
            "id" => "post_title",
            "std" => "[title] | [sitename]",
            "type" => "seo",
            "options" => $post_title);

        $seo_options[] = array("name" => "Page title",
            "desc" => "Simple page title format.",
            "id" => "page_title",
            "std" => "[title] | [sitename]",
            "type" => "seo",
            "options" => $page_title);

        $seo_options[] = array("name" => "Portfolio title",
            "desc" => "Single portfolio title format.",
            "id" => "portfolio_title",
            "std" => "[title] | [sitename]",
            "type" => "seo",
            "options" => $portfolio_title);

        $seo_options[] = array("name" => "Category title",
            "desc" => "Category archives title format.",
            "id" => "category_title",
            "std" => "[category] | [sitename] | [pagenumber]",
            "type" => "seo",
            "options" => $cat_title);

        $seo_options[] = array("name" => "Author archives title",
            "desc" => "Author archives title format.",
            "id" => "author_title",
            "std" => "[author_name] | [sitename] | [pagenumber]",
            "type" => "seo",
            "options" => $author_title);

        $seo_options[] = array("name" => "Date archives title",
            "desc" => "Date archives title format.",
            "id" => "archive_title",
            "std" => "[currentyear] | [currentmonth] | [pagenumber]",
            "type" => "seo",
            "options" => $archive_title);

        $seo_options[] = array("name" => "Search pages title",
            "desc" => "Search pages title format.",
            "id" => "search_title",
            "std" => "[searchphrase] | [sitename] | [pagenumber]",
            "type" => "seo",
            "options" => $search_title);

        $seo_options[] = array("name" => "404 page title",
            "desc" => "404 page title format.",
            "id" => "404_title",
            "std" => "NOT FOUND | [sitename]",
            "type" => "seo",
            "options" => $general);

        /* ----------------------------- Title and Description SETTINGS ----------------------------------- */

        $seo_options[] = array("name" => "Description Meta",
            "type" => "heading");

        $seo_options[] = array("name" => "index desc",
            "id" => "desc_desc",
            "std" => "The <tt>Description</tt> contains the information that will appear in the ‘description’ meta tag of the HTML. Typically this information will appear as a page summary on SERPs, and best practices dictate that this be unique for every page as well.",
            "type" => "info");

        $seo_options[] = array("name" => "Home description",
            "desc" => "Home page description.",
            "id" => "home_desc",
            "std" => "[sitedesc]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Post description",
            "desc" => "Default post description.",
            "id" => "post_desc",
            "std" => "[excerpt]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Page description",
            "desc" => "Default page description",
            "id" => "page_desc",
            "std" => "[excerpt]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Portfolio description",
            "desc" => "Default portfolio description.",
            "id" => "portfolio_desc",
            "std" => "[excerpt]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Category description",
            "desc" => "Category description.",
            "id" => "category_desc",
            "std" => "[category_description]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Portfolio archive description",
            "desc" => "Portfolio archives description.",
            "id" => "tax_desc",
            "std" => "[term_description]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Archives description",
            "desc" => "Date archives description.",
            "id" => "archive_desc",
            "std" => "[sitedesc]",
            "type" => "textarea");

        $seo_options[] = array("name" => "Author archives description",
            "desc" => "Author archives description.",
            "id" => "author_desc",
            "std" => "[author_description]",
            "type" => "textarea");

        /* -----------------------------  Keyword SETTINGS ----------------------------------- */

        $seo_options[] = array("name" => "Keyword",
            "type" => "heading");

        $seo_options[] = array("name" => "index desc",
            "id" => "desc_desc",
            "std" => "The <tt>Keywords</tt> field contains the information that will appear in the ‘keywords’ meta tag of the HTML. Best practices dictate that the list of keywords should be unique for each page, and ideally be comma-separated with each word capitalized.",
            "type" => "info");

        $seo_options[] = array("name" => "Category keyword",
            "desc" => "If turn it ON, it will include category names to keyword that selected your post.",
            "id" => "category_keyword",
            "std" => 1,
            "type" => "checkbox");

        $seo_options[] = array("name" => "Tag keyword",
            "desc" => "If turn it ON, it will include tag names to keyword that included in your post.",
            "id" => "tag_keyword",
            "std" => 1,
            "type" => "checkbox");

        $seo_options[] = array("name" => "Title keyword",
            "desc" => "If turn it ON, it will include title text to keyword of your post.",
            "id" => "title_keyword",
            "std" => "",
            "type" => "checkbox");

        $seo_options[] = array("name" => "Home page keyword",
            "desc" => "You need to provide here keywords especially for your home page. Also it extends your current page keywords if you selected any page on the front.",
            "id" => "keyword_home",
            "std" => "",
            "type" => "textarea");

        $seo_options[] = array("name" => "Category page keyword",
            "desc" => "Those keywords show on all the category pages extends their metas",
            "id" => "keyword_category",
            "std" => "",
            "type" => "textarea");

        $seo_options[] = array("name" => "Portfolio archive keyword",
            "desc" => "Those keywords show on all the portfolio pages extends their metas",
            "id" => "keyword_tax",
            "std" => "",
            "type" => "textarea");

        $seo_options[] = array("name" => "Author archives keyword",
            "desc" => "eThose keywords show on all the author pages extends their metas",
            "id" => "keyword_author",
            "std" => "",
            "type" => "textarea");

        $seo_options[] = array("name" => "404 page keyword",
            "desc" => "404 page keyword.",
            "id" => "keyword_404",
            "std" => "",
            "type" => "textarea");

        $seo_options[] = array("name" => "Search result keyword",
            "desc" => "Those keywords show on all the search result pages extends their metas",
            "id" => "keyword_search",
            "std" => "",
            "type" => "textarea");

        /* ----------------------------- Title and Description SETTINGS ----------------------------------- */

        $seo_options[] = array("name" => "Image optimization",
            "type" => "heading");

        $seo_options[] = array("name" => "index desc",
            "id" => "image_desc",
            "std" => "This section automatically updates all images with proper ALT and TITLE attributes. If your images do not have ALT and TITLE already set, the option will add them according the options you set. 
                      <p>ALT attribute is important part of search engine optimization. It describes your images to search engine and when a user searches for a certain image this is a key determining factor for a match.</p>
                      TITLE attribute play lesser role but is important for visitors as this text will automatically appear in the tooltip when mouse is over the image.",
            "type" => "info");

        $seo_options[] = array("name" => "Override TITLE",
            "desc" => "Override default Wordpress image title format.",
            "id" => "override_title",
            "std" => "",
            "type" => "checkbox");

        $seo_options[] = array("name" => "Custom Image TITLE",
            "id" => "image_title",
            "std" => "",
            "type" => "seo",
            "options" => $image_title);

        $seo_options[] = array("name" => "Override ALT",
            "desc" => "Override default Wordpress image 'alt' attribute.",
            "id" => "override_alt",
            "std" => "",
            "type" => "checkbox");

        $seo_options[] = array("name" => "Custom Image ALT",
            "desc" => "Override image alternate text.",
            "id" => "image_alt",
            "std" => "",
            "type" => "seo",
            "options" => $image_title);

        /* -----------------------------  Documantation ----------------------------------- */

        $seo_options[] = array("name" => "Documentation",
            "type" => "heading");

        $seo_options[] = array("name" => "info",
            "id" => "info_desc",
            "std" => "
<h3>About SEO</h3><hr>
<p><b>Search engine optimization</b> (<b>SEO</b>) - the process of making a site 'search engine-friendly' also known as 'SEO' is probably the most important aspect of website design. Many, many commercial websites are designed and set up by people who know little or nothing about search engine optimization — how to give the search engines what they need to see when they index your site.</p>
<p>It is the process of improving the visibility of a <a href='/wiki/Website' title='Website'>website</a> or a <a href='/wiki/Web_page' title='Web page'>web page</a> in <a href='/wiki/Search_engine' title='Search engine' class='mw-redirect'>search engines</a>' 'natural,' or un-paid ('<a href='/wiki/Organic_search' title='Organic search'>organic</a>' or 'algorithmic'), <a href='/wiki/Search_engine_results_page' title='Search engine results page'>search results</a>. In general, the earlier (or higher ranked on the search results page), and more frequently a site appears in the search results list, the more visitors it will receive from the search engine's users. SEO may target different kinds of search, including <a href='/wiki/Image_search' title='Image search' class='mw-redirect'>image search</a>, <a href='/wiki/Local_search_(Internet)' title='Local search (Internet)'>local search</a>, <a href='/wiki/Video_search' title='Video search' class='mw-redirect'>video search</a>, <a href='/wiki/Academic_databases_and_search_engines' title='Academic databases and search engines' class='mw-redirect'>academic search</a>,<sup id='cite_ref-aseo_0-0' class='reference'><a href='#cite_note-aseo-0'><span>[</span>1<span>]</span></a></sup> news search and industry-specific <a href='/wiki/Vertical_search' title='Vertical search'>vertical search</a> engines.</p>
<p>As an <a href='/wiki/Internet_marketing' title='Internet marketing'>Internet marketing</a> strategy, SEO considers how search engines work, what people search for, the actual search terms or keywords typed into search engines and which search engines are preferred by their targeted audience. Optimizing a website may involve editing its content and <a href='/wiki/HTML' title='HTML'>HTML</a> and associated coding to both increase its relevance to specific keywords and to remove barriers to the <a href='/wiki/Web_crawler' title='Web crawler'>indexing activities</a> of search engines. Promoting a site to increase the number of <a href='/wiki/Backlinks' title='Backlinks' class='mw-redirect'>backlinks</a>, or inbound links, is another SEO tactic.</p>
<p>The acronym 'SEOs' can refer to 'search engine optimizers,' a term adopted by an industry of <a href='/wiki/Consultants' title='Consultants' class='mw-redirect'>consultants</a> who carry out optimization projects on behalf of clients, and by employees who perform SEO services in-house. Search engine optimizers may offer SEO as a stand-alone service or as a part of a broader marketing campaign. Because effective SEO may require changes to the <a href='/wiki/HTML' title='HTML'>HTML</a> source code of a site and site content, SEO tactics may be incorporated into <a href='/wiki/Website' title='Website'>website</a> development and <a href='/wiki/Website_design' title='Website design' class='mw-redirect'>design</a>. The term 'search engine friendly' may be used to describe website designs, <a href='/wiki/Menu_(computing)' title='Menu (computing)'>menus</a>, <a href='/wiki/Content_management_systems' title='Content management systems' class='mw-redirect'>content management systems</a>, images, videos, <a href='/wiki/Shopping_cart_software' title='Shopping cart software'>shopping carts</a>, and other elements that have been optimized for the purpose of search engine exposure.</p>
Resource : <a href='http://en.wikipedia.org/wiki/Search_engine_optimization'>Wikipedia</a>
<h3>Keyword replacements</h3><hr>
<p>These tags can be included and will be replaced by site SEO when a page is displayed.</p>
<table>
<tbody>
        <tr>
		<th>[date]</th>
		<td>Replaced with the date of the post/page</td>
	</tr>
	<tr class='alt'>
		<th>[title]</th>
		<td>Replaced with the title of the post/page</td>
	</tr>
	<tr>
		<th>[sitename]</th>
		<td>The site's name</td>
	</tr>
	<tr class='alt'>
		<th>[sitedesc]</th>
		<td>The site's tagline / description</td>
	</tr>
	<tr>
		<th>[excerpt]</th>
		<td>Replaced with the post/page excerpt (or auto-generated if it does not exist)</td>
	</tr>
	<tr class='alt'>
		<th>[category]</th>
		<td>Replaced with the post categories (comma separated)</td>
	</tr>
	<tr>
		<th>[category_description]</th>
		<td>Replaced with the category description</td>
	</tr>
	<tr class='alt'>
		<th>[tag]</th>
		<td>Replaced with the current tag/tags</td>
	</tr>
	<tr>
		<th>[tag_description]</th>
		<td>Replaced with the tag description</td>
	</tr>
	<tr class='alt'>
		<th>[term_description]</th>
		<td>Replaced with the term description</td>
	</tr>
	<tr>
		<th>[term_title]</th>
		<td>Replaced with the term name</td>
	</tr>
	<tr class='alt'>
		<th>[author_name]</th>
		<td>Replaced with the post/page author's 'nicename'</td>
	</tr>
	<tr>
		<th>[author_description]</th>
		<td>Replaced with the post/page author's description</td>
	</tr>
        <tr class='alt'>
		<th>[searchphrase]</th>
		<td>Replaced with the current search phrase</td>
	</tr>
	<tr>
		<th>[currenttime]</th>
		<td>Replaced with the current time</td>
	</tr>
	<tr class='alt'>
		<th>[currentdate]</th>
		<td>Replaced with the current date</td>
	</tr>
	<tr>
		<th>[currentmonth]</th>
		<td>Replaced with the current month</td>
	</tr>
	<tr class='alt'>
		<th>[currentyear]</th>
		<td>Replaced with the current year</td>
	</tr>
	<tr>
		<th>[pagenumber]</th>
		<td>Replaced with the current page number</td>
	</tr>
	<tr class='alt'>
		<th>[image_name]</th>
		<td>Replaced with the current image name</td>
	</tr>
</tbody>
</table>

",
            "type" => "info");
    }

}