<?php

add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options(){

//Theme Shortname
$shortname = "mvp";

//Populate the options array
global $tt_options;
$tt_options = get_option('of_options');

if ( is_admin() ) {

//Access the WordPress Categories via an Array
$tt_categories = array();  
$tt_categories_obj = get_categories('hide_empty=0');
foreach ($tt_categories_obj as $tt_cat) {
$tt_categories[$tt_cat->cat_ID] = $tt_cat->cat_name;}
$categories_tmp = array_unshift($tt_categories, "Select a category:");

$home_layout = array("Blog","Widgets");

$logo_loc = array("Small in navigation","Left of leaderboard","Wide below leaderboard");

$feat_post = array("Featured Posts 1","Featured Posts 2","Featured Posts 3");

$admin_images = get_template_directory_uri() . '/admin/images/';

$leader_loc = array("Above Navigation","Below Navigation");

}

/*-----------------------------------------------------------------------------------*/
/* Create The Custom Site Options Panel
/*-----------------------------------------------------------------------------------*/
$options = array(); // do not delete this line - sky will fall

/* General Settings */
$options[] = array( "name" => __('General','mvp-text'),
			"type" => "heading");

if (isset($logo_loc)) {
$options[] = array( "name" => __('Logo Location','mvp-text'),
			"desc" => __('Set the location of your logo.','mvp-text'),
			"id" => $shortname."_logo_loc",
			"std" => "Small in navigation",
			"type" => "select",
			"options" => $logo_loc);
}

$options[] = array( "name" => __('Logo','mvp-text'),
			"desc" => __('Select a file to appear as the logo for your site.-Nu.l.l.2.4.N.e.t','mvp-text'),
			"id" => $shortname."_logo",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => __('Logo in Navigation','mvp-text'),
			"desc" => __('If you are displaying your logo above the navigation, you can upload a separate logo that will appear in the floating navigation bar as you scroll down the page on desktop computers. It will also appear on mobile and tablet devices. The maximum recommended size is 185x54.','mvp-text'),
			"id" => $shortname."_logo_nav",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => __('Custom Favicon','mvp-text'),
			"desc" => __('Upload a 16x16px PNG/GIF image that will represent your website\'s favicon.','mvp-text'),
			"id" => $shortname."_favicon",
			"std" => "",
			"type" => "upload");


$options[] = array( "name" => __('Tracking Code','mvp-text'),
			"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
			"id" => $shortname."_tracking",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => __('Custom CSS','mvp-text'),
			"desc" => "Enter your custom CSS here. You will not lose any of the CSS you enter here if you update the theme to a new version.",
			"id" => $shortname."_customcss",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => __('Number of posts per page','mvp-text'),
			"desc" => "Set the number of posts per page that you want displayed on the Homepage Blog and the Latest News Template.",
			"id" => $shortname."_posts_num",
			"std" => "10",
			"type" => "text");

$options[] = array( "name" => __('Toggle Responsiveness','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the responsiveness of the theme.",
			"id" => $shortname."_respond",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Toggle Sticky Sidebar','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the Sticky Sidebar feature.",
			"id" => $shortname."_sticky_sidebar",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Toggle Infinite Scroll','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the Infinite Scroll feature.-N-u.l-l.2.4-N.e.t",
			"id" => $shortname."_infinite_scroll",
			"std" => "true",
			"type" => "checkbox");


/* Theme Color Settings */
$options[] = array( "name" => __('Colors','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Primary Theme Color','mvp-text'),
			"desc" => __('Primary color for the site.','mvp-text'),
			"id" => $shortname."_primary_theme",
			"std" => "#f00a71",
			"type" => "color");

$options[] = array( "name" => __('Main Menu Background Color','mvp-text'),
			"desc" => __('The background color of the main menu.','mvp-text'),
			"id" => $shortname."_menu_color",
			"std" => "#ffffff",
			"type" => "color");

$options[] = array( "name" => __('Fly-Out Button Color','mvp-text'),
			"desc" => __('The color of the Fly-Out Menu Button Lines.','mvp-text'),
			"id" => $shortname."_fly_but",
			"std" => "#555555",
			"type" => "color");

$options[] = array( "name" => __('Primary Link Color','mvp-text'),
			"desc" => __('Primary link color for the site.','mvp-text'),
			"id" => $shortname."_link_color",
			"std" => "#f00a71",
			"type" => "color");

/* Font Settings */
$options[] = array( "name" => __('Fonts','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Attention','mvp-text'),
			"desc" => "",
			"id" => $shortname."_attention_home_slider",
			"std" => "You can select from over 600 Google Fonts to set the various fonts for your site. You can browse the list of Google Fonts here: https://www.google.com/fonts . You can also enter standard web fonts like Arial and Georgia as well.",
			"type" => "info");

$options[] = array( "name" => __('Featured Slider Headline Font','mvp-text'),
			"desc" => __('Enter the font name for the main headline in the Featured Slider section on the homepage and category pages.','mvp-text'),
			"id" => $shortname."_slider_headline",
			"std" => "Vidaloka",
			"type" => "text");

$options[] = array( "name" => __('Main Menu Font','mvp-text'),
			"desc" => __('Enter the font name for the main menu.','mvp-text'),
			"id" => $shortname."_menu_font",
			"std" => "Raleway",
			"type" => "text");

$options[] = array( "name" => __('General Headline Font','mvp-text'),
			"desc" => __('Enter the font name for the general headline font used in widgets, archive pages and page/post titles.','mvp-text'),
			"id" => $shortname."_headline_font",
			"std" => "Playfair Display",
			"type" => "text");

$options[] = array( "name" => __('Header Font','mvp-text'),
			"desc" => __('Enter the font name for the headers around the site.-N-u-l-l-2.4.N-e-t','mvp-text'),
			"id" => $shortname."_header_font",
			"std" => "Oswald",
			"type" => "text");


/* Featured Slider Settings */
$options[] = array( "name" => __('Home Featured Slider','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Attention','mvp-text'),
			"desc" => "",
			"id" => $shortname."_attention_home_slider",
			"std" => "In order to utilize these functions, you will have to set up your homepage as a static page. Please refer to the Installing Demo Data section of the documentation for more information.",
			"type" => "info");

$options[] = array( "name" => __('Show Featured Slider?','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the Featured Slider from the homepage.",
			"id" => $shortname."_slider",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Featured Slider Tag Slug','mvp-text'),
			"desc" => __('Posts with this Tag will be displayed in the Featured Slider at the top of the homepage.','mvp-text'),
			"id" => $shortname."_slider_tags",
			"std" => "featured",
			"type" => "text");

$options[] = array( "name" => __('Maximum Featured Slider Items','mvp-text'),
			"desc" => "Set the maximum number of items (posts) to appear in the Featured Slider.",
			"id" => $shortname."_slider_num",
			"std" => "6",
			"type" => "text");


/* Homepage Featured Settings */
$options[] = array( "name" => __('Home Featured Posts','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Attention','mvp-text'),
			"desc" => "",
			"id" => $shortname."_attention_home_featured",
			"std" => "In order to utilize these functions, you will have to set up your homepage as a static page. Please refer to the Installing Demo Data section of the documentation for more information.",
			"type" => "info");

$options[] = array( "name" => __('Show Featured Posts Section?','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the Featured Posts Section from the homepage.",
			"id" => $shortname."_featured_posts",
			"std" => "true",
			"type" => "checkbox");

if (isset($feat_post)) {
$options[] = array( "name" => __('Featured Posts Layout','mvp-text'),
			"desc" => __('Choose your layout for the Featured Posts section.-N-.u-l-l-2.4.N.e.t','mvp-text'),
			"id" => $shortname."_feat_post",
			"std" => "Featured Posts 1",
			"type" => "select",
			"options" => $feat_post);
}

if (isset($tt_categories)) {
$options[] = array( "name" => __('Featured Posts 1 Left Category','mvp-text'),
			"desc" => __('If you are using the Featured Posts 1 option, posts with this Category will be displayed to the left of the Featured Post on the homepage.','mvp-text'),
			"id" => $shortname."_featured_left",
			"std" => "Fashion",
			"type" => "select",
			"options" => $tt_categories);
}

if (isset($tt_categories)) {
$options[] = array( "name" => __('Featured Posts 1 Right Category','mvp-text'),
			"desc" => __('If you are using the Featured Posts 1 option, posts with this Category will be displayed to the right of the Featured Post on the homepage.','mvp-text'),
			"id" => $shortname."_featured_right",
			"std" => "Beauty",
			"type" => "select",
			"options" => $tt_categories);
}


/* Homepage Body Settings */
$options[] = array( "name" => __('Home Body Layout','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Attention','mvp-text'),
			"desc" => "",
			"id" => $shortname."_attention_home_body",
			"std" => "In order to utilize these functions, you will have to set up your homepage as a static page. Please refer to the Installing Demo Data section of the documentation for more information.",
			"type" => "info");

if (isset($home_layout)) {
$options[] = array( "name" => __('Homepage Layout','mvp-text'),
			"desc" => __('Select your layout for the body of the homepage.','mvp-text'),
			"id" => $shortname."_home_layout",
			"std" => "1",
			"type" => "select",
			"options" => $home_layout);
}

$options[] = array( "name" => __('Homepage Blog Heading','mvp-text'),
			"desc" => "Set the heading above the blog layout on the homepage.",
			"id" => $shortname."_blog_header",
			"std" => "The Latest",
			"type" => "text");

if (isset($admin_images)) {
$options[] = array( "name" => __('Homepage Blog Layout','mvp-text'),
			"desc" => __('If you chose the Blog-style homepage layout, you can choose between three different layout options: The large image, the horizontal list, or the two columns.','mvp-text'),
			"id" => $shortname."_blog_layout",
			"std" => "large",
			"type" => "images",
			"options" => array(
				'large' => $admin_images . 'large.gif',
				'list' => $admin_images . 'list.gif',
				'columns' => $admin_images . 'columns.gif'
				));
}


/* Article Settings */
$options[] = array( "name" => __('Article Settings','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Show Featured Image In Posts?','mvp-text'),
			"desc" => __('Uncheck this box if you would like to remove the featured image thumbnail from all posts.','mvp-text'),
			"id" => $shortname."_featured_img",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Show Social Sharing Buttons?','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the social sharing buttons from all posts and pages.",
			"id" => $shortname."_social_box",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Show Author Info?','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the author info box from the bottom of the posts.",
			"id" => $shortname."_author_box",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Show Previous/Next Post Links?','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the links to the previous/next posts below each article.",
			"id" => $shortname."_prev_next",
			"std" => "true",
			"type" => "checkbox");


/* Category Settings */
$options[] = array( "name" => __('Category Pages','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Show Featured Slider','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the Featured Slider from the category pages.",
			"id" => $shortname."_slider_cat",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Maximum Featured Slider Items','mvp-text'),
			"desc" => "Set the maximum number of items (posts) to appear in the Featured Slider.",
			"id" => $shortname."_slider_cat_num",
			"std" => "3",
			"type" => "text");

if (isset($admin_images)) {
$options[] = array( "name" => __('Category Body Layout','mvp-text'),
			"desc" => __('Choose between three different layout options for your category pages: The large image, the horizontal list, or the two columns.','mvp-text'),
			"id" => $shortname."_category_layout",
			"std" => "list",
			"type" => "images",
			"options" => array(
				'large' => $admin_images . 'large.gif',
				'list' => $admin_images . 'list.gif',
				'columns' => $admin_images . 'columns.gif'
				));
}


/* Social Media Settings */
$options[] = array( "name" => __('Social Media','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Facebook','mvp-text'),
			"desc" => "Enter your Facebook Page URL here.",
			"id" => $shortname."_facebook",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Twitter','mvp-text'),
			"desc" => "Enter your Twitter URL here.",
			"id" => $shortname."_twitter",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Pinterest','mvp-text'),
			"desc" => "Enter your Pinterest URL here.",
			"id" => $shortname."_pinterest",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Instagram','mvp-text'),
			"desc" => "Enter your Instagram URL here.",
			"id" => $shortname."_instagram",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Google Plus','mvp-text'),
			"desc" => "Enter your Google Plus URL here.",
			"id" => $shortname."_google",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Youtube','mvp-text'),
			"desc" => "Enter your Youtube URL here.",
			"id" => $shortname."_youtube",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Linkedin','mvp-text'),
			"desc" => "Enter your Linkedin URL here.",
			"id" => $shortname."_linkedin",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Custom RSS Link','mvp-text'),
			"desc" => "If you want to replace the default RSS link with a custom RSS link (like Feedburner), enter the URL here.",
			"id" => $shortname."_rss",
			"std" => "",
			"type" => "text");


/* Ad Management Settings */
$options[] = array( "name" => __('Ad Management','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Attention','mvp-text'),
			"desc" => "",
			"id" => $shortname."_attention_ad",
			"std" => "The 300x250 ads are controlled via a Widget.",
			"type" => "info");

if (isset($leader_loc)) {
$options[] = array( "name" => __('Leaderboard Location','mvp-text'),
			"desc" => __('Set the location of your leaderboard.','mvp-text'),
			"id" => $shortname."_leader_loc",
			"std" => "Below Navigation",
			"type" => "select",
			"options" => $leader_loc);
}

$options[] = array( "name" => __('Header Leaderboard Ad Code','mvp-text'),
			"desc" => "Enter your ad code (Eg. Google Adsense) for the 970x90 ad area. You can also place a 728x90 ad in this spot.",
			"id" => $shortname."_header_leader",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => __('Footer Leaderboard Ad Code','mvp-text'),
			"desc" => "Enter your ad code (Eg. Google Adsense) for the 970x90 ad area. You can also place a 728x90 ad in this spot.",
			"id" => $shortname."_footer_leader",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => __('Responsive Ad Area Below Article','mvp-text'),
			"desc" => "Enter your ad code (Eg. Google Adsense) to activate the responsive ad area that will be displayed below the content of each article.",
			"id" => $shortname."_article_ad",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => __('Wallpaper Ad Image URL','mvp-text'),
			"desc" => "Enter the URL for your wallpaper ad image. Wallpaper ad code should be a minimum of 1280px wide. Please see the theme documentation for more on wallpaper ad specifications.",
			"id" => $shortname."_wall_ad",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Wallpaper Ad URL','mvp-text'),
			"desc" => "Enter the URL for your wallpaper ad click-through.",
			"id" => $shortname."_wall_url",
			"std" => "",
			"type" => "text");


/* Footer Settings */
$options[] = array( "name" => __('Footer Info','mvp-text'),
			"type" => "heading");

$options[] = array( "name" => __('Show Footer Info Box?','mvp-text'),
			"desc" => "Uncheck this box if you would like to remove the Footer Info Box.",
			"id" => $shortname."_footer_info",
			"std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Footer Logo','mvp-text'),
			"desc" => __('Select a file to appear as the logo in the Footer Info Box.','mvp-text'),
			"id" => $shortname."_logo_footer",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => __('Footer Info Text','mvp-text'),
			"desc" => "Enter any text to display in the Footer Info Box.",
			"id" => $shortname."_footer_text",
			"std" => "<p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem?</p><p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet.</p>",
			"type" => "textarea");

$options[] = array( "name" => __('Copyright Text','mvp-text'),
			"desc" => "Here you can enter any text you want (eg. copyright text)",
			"id" => $shortname."_copyright",
			"std" => "Copyright &copy; 2013 Braxton Theme. Theme by MVP Themes, powered by Wordpress.",
			"type" => "textarea");



update_option('of_template',$options);

update_option('of_shortname',$shortname);

}
}
?>