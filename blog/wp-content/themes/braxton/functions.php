<?php

/////////////////////////////////////
// Theme Setup
/////////////////////////////////////

if ( ! function_exists( 'mvp_setup' ) ) {
function mvp_setup(){
	load_theme_textdomain('mvp-text', get_template_directory() . '/languages');
	load_theme_textdomain('theia-post-slider', get_template_directory() . '/languages');
	load_theme_textdomain('framework_localize', get_template_directory() . '/languages');

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
}
}
add_action('after_setup_theme', 'mvp_setup');

/////////////////////////////////////
// Enqueue Javascript/CSS Files
/////////////////////////////////////

if ( ! function_exists( 'mvp_scripts_method' ) ) {
function mvp_scripts_method() {
	global $wp_styles;
	wp_enqueue_style( 'mvp-style', get_stylesheet_uri() );
	wp_enqueue_style( 'mvp-iecss', get_stylesheet_directory_uri() . "/css/iecss.css", array( 'mvp-style' )  );
	$wp_styles->add_data( 'mvp-iecss', 'conditional', 'lt IE 10' );
	wp_enqueue_style( 'reset', get_template_directory_uri() . '/css/reset.css' );
	wp_enqueue_style( 'mvp-fontawesome', '//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css' );
	$mvp_respond = get_option('mvp_respond'); if ($mvp_respond == "true") { if (isset($mvp_respond)) {
	wp_enqueue_style( 'media-queries', get_template_directory_uri() . '/css/media-queries.css' );
	} }
	wp_register_script('iosslider', get_template_directory_uri() . '/js/jquery.iosslider.js', array('jquery'), '', true);
	wp_register_script('braxton', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '', true);
	wp_register_script('infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.js', array('jquery'), '', true);
	wp_register_script('respond', get_template_directory_uri() . '/js/respond.min.js', array('jquery'), '', true);
	wp_register_script('retina', get_template_directory_uri() . '/js/retina.js', array('jquery'), '', true);
	wp_register_script('stickymojo', get_template_directory_uri() . '/js/stickyMojo.js', array('jquery'), '', true);

	wp_enqueue_script('iosslider');
	wp_enqueue_script('braxton');
	wp_enqueue_script('respond');
	wp_enqueue_script('retina');
	$mvp_infinite_scroll = get_option('mvp_infinite_scroll'); if ($mvp_infinite_scroll == "true") { if (isset($mvp_infinite_scroll)) {
	wp_enqueue_script('infinitescroll');
	} }
	$mvp_sticky_sidebar = get_option('mvp_sticky_sidebar'); if ($mvp_sticky_sidebar == "true") { if (isset($mvp_sticky_sidebar)) {
	wp_enqueue_script('stickymojo');
	} }

}
}
add_action('wp_enqueue_scripts', 'mvp_scripts_method');

/////////////////////////////////////
// Google Fonts
/////////////////////////////////////

if ( ! function_exists( 'mvp_studio_fonts_url' ) ) {
function mvp_studio_fonts_url() {
    $font_url = '';

    if ( 'off' !== _x( 'on', 'Google font: on or off', 'studio' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Montserrat:400|Quicksand:300,400|Vidaloka|Playfair Display:400,700,900|Open Sans Condensed:300,700|Open Sans:400,800|Oswald:300,400,700|Raleway:200,300,400,500,600,700,800&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
    }
    return $font_url;
}
}

if ( ! function_exists( 'mvp_studio_scripts' ) ) {
function mvp_studio_scripts() {
    wp_enqueue_style( 'studio-fonts', mvp_studio_fonts_url(), array(), '1.0.0' );
}
}
add_action( 'wp_enqueue_scripts', 'mvp_studio_scripts' );

/////////////////////////////////////
// Theme Options
/////////////////////////////////////

require_once(TEMPLATEPATH . '/admin/admin-functions.php');
require_once(TEMPLATEPATH . '/admin/admin-interface.php');
require_once(TEMPLATEPATH . '/admin/theme-settings.php');

if ( ! function_exists( 'my_wp_head' ) ) {
function my_wp_head() {
	$bloginfo = get_template_directory_uri();
	$primarytheme = get_option('mvp_primary_theme');
	$mainmenu = get_option('mvp_menu_color');
	$flybut = get_option('mvp_fly_but');
	$link = get_option('mvp_link_color');
	$wallad = get_option('mvp_wall_ad');
	$slider_headline = get_option('mvp_slider_headline');
	$menu_font = get_option('mvp_menu_font');
	$headline_font = get_option('mvp_headline_font');
	$header_font = get_option('mvp_header_font');
	$google_slider = preg_replace("/ /","+",$slider_headline);
	$google_menu = preg_replace("/ /","+",$menu_font);
	$google_headlines = preg_replace("/ /","+",$headline_font);
	$google_header = preg_replace("/ /","+",$header_font);
	echo "
<style type='text/css'>

@import url(//fonts.googleapis.com/css?family=$google_slider:100,200,300,400,500,600,700,800,900|$google_menu:100,200,300,400,500,600,700,800,900|$google_headlines:100,200,300,400,500,600,700,800,900|$google_header:100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese);

#nav-wrapper,
#nav-top-wrap {
	background: $mainmenu;
	}

.fly-but-wrap span {
	background: $flybut;
	}

#main-nav .menu li a:hover,
#main-nav .menu li.current-menu-item a,
#search-button:hover,
.home-widget h3.widget-cat,
span.post-tags-header,
.post-tags a:hover,
.tag-cloud a:hover,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range {
	background: $primarytheme;
	}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce span.onsale,
.woocommerce #respond input#submit.alt,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt,
.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover {
	background-color: $primarytheme;
	}

.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover {
	opacity: 0.8;
	}

#main-nav .menu-item-home a:hover {
	background: $primarytheme !important;
	}

#top-story-left h3,
#top-story-right h3 {
	border-bottom: 3px solid $primarytheme;
	}

a, a:visited,
ul.top-stories li:hover h2,
ul.top-stories li:hover h2,
#top-story-middle:hover h2,
#top-story-wrapper:hover #feat2-main-text h2,
#feat1-left-wrapper:hover .feat1-left-text h2,
#feat1-right-wrapper:hover .feat1-right-text h2,
ul.split-columns li:hover h2,
.home-widget ul.wide-widget li:hover h2,
.home-widget ul.home-list li:hover h2,
h3.story-cat,
h3.story-cat a,
.sidebar-widget ul.home-list li:hover h2,
.sidebar-widget ul.wide-widget li:hover h2,
.sidebar-widget ul.split-columns li:hover h2,
#footer-nav .menu li a:hover,
.footer-widget ul.home-list li:hover h2,
.footer-widget ul.wide-widget li:hover h2,
.footer-widget ul.split-columns li:hover h2,
.prev,
.next {
	color: $link;
	}

#wallpaper {
	background: url($wallad) no-repeat 50% 0;
	}

.featured-text h2,
.featured-text h2 a,
.feat1-main-text h2,
.feat1-main-text h2 a {
	font-family: '$slider_headline', serif;
	}

#main-nav .menu li a,
#main-nav .menu li:hover ul li a {
	font-family: '$menu_font', sans-serif;
	}

#top-story-left h2,
#top-story-right h2,
.feat1-left-text h2,
.feat1-right-text h2,
#feat2-main-text h2,
#middle-text h2,
ul.split-columns li h2,
.home-widget .wide-text h2,
.home-widget .home-list-content h2,
h1.story-title,
.sidebar-widget .wide-text h2,
.sidebar-widget ul.split-columns li h2,
.footer-widget .wide-text h2,
.footer-widget ul.split-columns li h2,
#post-404 h1 {
	font-family: '$headline_font', serif;
	}

#top-story-left h3,
#top-story-right h3,
#middle-text h3,
h1.home-widget-header,
#woo-content h1.page-title,
h3.home-widget-header,
h1.archive-header,
h3.story-cat,
h3.story-cat a,
#content-area h1,
#content-area h2,
#content-area h3,
#content-area h4,
#content-area h5,
#content-area h6,
h4.post-header,
h3.sidebar-widget-header,
h3.footer-widget-header {
	font-family: '$header_font', sans-serif;
	}

@media screen and (max-width: 767px) and (min-width: 480px) {

	.sidebar-widget .home-list-content h2 {
		font-family: '$headline_font', serif;
		}

	}

#menufication-outer-wrap.menufication-transition-in #menufication-scroll-container {
	overflow-y: auto !important;
	}

</style>
	";
}
}
add_action( 'wp_head', 'my_wp_head' );

/////////////////////////////////////
// Register Widgets
/////////////////////////////////////

if ( !function_exists( 'mvp_sidebars_init' ) ) {
	function mvp_sidebars_init() {
		register_sidebar(array(
			'id' => 'homepage-widget',
			'name' => 'Homepage Widget Area',
			'before_widget' => '<div id="%1$s" class="home-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="home-widget-header">',
			'after_title' => '</h3>',
		));

		register_sidebar(array(
			'id' => 'sidebar-home-widget',
			'name' => 'Sidebar Home Widget Area',
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<span class="sidebar-widget-header"><h3 class="sidebar-widget-header">',
			'after_title' => '</h3></span>',
		));

		register_sidebar(array(
			'id' => 'sidebar-widget',
			'name' => 'Sidebar Widget Area',
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<span class="sidebar-widget-header"><h3 class="sidebar-widget-header">',
			'after_title' => '</h3></span>',
		));

		register_sidebar(array(
			'id' => 'sidebar-woo-widget',
			'name' => 'WooCommerce Sidebar Widget Area',
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<span class="sidebar-widget-header"><h3 class="sidebar-widget-header">',
			'after_title' => '</h3></span>',
		));

		register_sidebar(array(
			'id' => 'footer-widget',
			'name' => 'Footer Widget Area',
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="footer-widget-header">',
			'after_title' => '</h3>',
		));

	}
}
add_action( 'widgets_init', 'mvp_sidebars_init' );

include("widgets/widget-ad.php");
include("widgets/widget-ad125.php");
include("widgets/widget-columns.php");
include("widgets/widget-facebook.php");
include("widgets/widget-large.php");
include("widgets/widget-list.php");
include("widgets/widget-social.php");
include("widgets/widget-tags.php");

/////////////////////////////////////
// Register Custom Menus
/////////////////////////////////////

if ( !function_exists( 'register_menus' ) ) {
function register_menus() {
	register_nav_menus(
		array(
			'main-menu' => __( 'Main Menu', 'mvp-text' ),
			'mobile-menu' => __( 'Mobile Menu', 'mvp-text' ),
			'footer-menu' => __( 'Footer Menu', 'mvp-text' ),)
	  	);
	  }
}
add_action( 'init', 'register_menus' );

/////////////////////////////////////
// Register Custom Background
/////////////////////////////////////

$custombg = array(
	'default-color' => 'f5f5f5',
);
add_theme_support( 'custom-background', $custombg );

/////////////////////////////////////
// Register Thumbnails
/////////////////////////////////////

if ( function_exists( 'add_theme_support' ) ) {
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 660, 400, true );
add_image_size( 'post-thumb', 660, 400, true );
add_image_size( 'medium-thumb', 400, 242, true );
add_image_size( 'small-thumb', 270, 164, true );
}

/////////////////////////////////////
// Title Meta Data
/////////////////////////////////////

add_theme_support( 'title-tag' );

function mvp_filter_home_title(){
if ( ( is_home() && ! is_front_page() ) || ( ! is_home() && is_front_page() ) ) {
    $mvpHomeTitle = get_bloginfo( 'name', 'display' );
    $mvpHomeDesc = get_bloginfo( 'description', 'display' );
    return $mvpHomeTitle . " - " . $mvpHomeDesc;
}
}
add_filter( 'pre_get_document_title', 'mvp_filter_home_title');

/////////////////////////////////////
// Add Bread Crumbs
/////////////////////////////////////

if ( !function_exists( 'dimox_breadcrumbs' ) ) {
function dimox_breadcrumbs() {

  $delimiter = '/';
  $home = 'Home'; // text for the 'Home' link
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb

  if ( !is_home() && !is_front_page() || is_paged() ) {

    echo '<div id="crumbs">';

    global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . single_cat_title('', false) . $after;

    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_search()) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;

    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;

    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;

    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;

    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page', 'mvp-text') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

    echo '</div>';

  }
}
}

/////////////////////////////////////
// Add Custom Meta Box
/////////////////////////////////////

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'mvp_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'mvp_post_meta_boxes_setup' );

/* Meta box setup function. */
if ( !function_exists( 'mvp_post_meta_boxes_setup' ) ) {
function mvp_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'mvp_add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'mvp_save_video_embed_meta', 10, 2 );
	add_action( 'save_post', 'mvp_save_featured_headline_meta', 10, 2 );
	add_action( 'save_post', 'mvp_save_photo_credit_meta', 10, 2 );
	add_action( 'save_post', 'mvp_save_featured_image_meta', 10, 2 );
	add_action( 'save_post', 'mvp_save_post_template_meta', 10, 2 );
}
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
if ( !function_exists( 'mvp_add_post_meta_boxes' ) ) {
function mvp_add_post_meta_boxes() {

	add_meta_box(
		'mvp-video-embed',			// Unique ID
		esc_html__( 'Video/Audio Embed', 'mvp-text' ),		// Title
		'mvp_video_embed_meta_box',		// Callback function
		'post',					// Admin page (or post type)
		'normal',				// Context
		'high'					// Priority
	);

	add_meta_box(
		'mvp-featured-headline',			// Unique ID
		esc_html__( 'Featured Headline', 'mvp-text' ),		// Title
		'mvp_featured_headline_meta_box',		// Callback function
		'post',					// Admin page (or post type)
		'normal',				// Context
		'high'					// Priority
	);

	add_meta_box(
		'mvp-photo-credit',			// Unique ID
		esc_html__( 'Photo Credit', 'mvp-text' ),		// Title
		'mvp_photo_credit_meta_box',		// Callback function
		'post',					// Admin page (or post type)
		'normal',				// Context
		'high'					// Priority
	);

	add_meta_box(
		'mvp-post-template',			// Unique ID
		esc_html__( 'Post Template', 'mvp-text' ),		// Title
		'mvp_post_template_meta_box',		// Callback function
		'post',					// Admin page (or post type)
		'side',					// Context
		'core'					// Priority
	);

	add_meta_box(
		'mvp-featured-image',			// Unique ID
		esc_html__( 'Featured Image Show/Hide', 'mvp-text' ),		// Title
		'mvp_featured_image_meta_box',		// Callback function
		'post',					// Admin page (or post type)
		'side',					// Context
		'core'					// Priority
	);

}
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_featured_headline_meta_box' ) ) {
function mvp_featured_headline_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'mvp_featured_headline_nonce' ); ?>

	<p>
		<label for="mvp-featured-headline"><?php _e( "Add a custom featured headline that will be displayed in the featured slider.", 'example' ); ?></label>
		<br />
		<input class="widefat" type="text" name="mvp-featured-headline" id="mvp-featured-headline" value="<?php echo esc_html__( get_post_meta( $object->ID, 'mvp_featured_headline', true ) ); ?>" size="30" />
	</p>

<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_video_embed_meta_box' ) ) {
function mvp_video_embed_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'mvp_video_embed_nonce' ); ?>

	<p>
		<label for="mvp-video-embed"><?php _e( "Enter your video or audio embed code.", 'mvp-text' ); ?></label>
		<br />
		<textarea class="widefat" name="mvp-video-embed" id="mvp-video-embed" cols="50" rows="5"><?php echo esc_html__( get_post_meta( $object->ID, 'mvp_video_embed', true ) ); ?></textarea>
	</p>

<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_photo_credit_meta_box' ) ) {
function mvp_photo_credit_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'mvp_photo_credit_nonce' ); ?>

	<p>
		<label for="mvp-photo-credit"><?php _e( "Add a photo credit for the featured image.", 'mvp-text' ); ?></label>
		<br />
		<input class="widefat" type="text" name="mvp-photo-credit" id="mvp-photo-credit" value="<?php echo esc_html__( get_post_meta( $object->ID, 'mvp_photo_credit', true ) ); ?>" size="30" />
	</p>

<?php }
}
/* Display the post meta box. */
if ( !function_exists( 'mvp_post_template_meta_box' ) ) {
function mvp_post_template_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'mvp_post_template_nonce' ); $selected = esc_html__( get_post_meta( $object->ID, 'mvp_post_template', true ) ); ?>

	<p>
		<label for="mvp-post-template"><?php _e( "Select from the default post template or a full-width template.", 'mvp-text' ); ?></label>
		<br /><br />
		<select class="widefat" name="mvp-post-template" id="mvp-post-template">
            		<option value="default" <?php selected( $selected, 'default' ); ?>>Default</option>
            		<option value="fullwidth" <?php selected( $selected, 'fullwidth' ); ?>>Full-width</option>
        	</select>
	</p>
<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_featured_image_meta_box' ) ) {
function mvp_featured_image_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'mvp_featured_image_nonce' ); $selected = esc_html__( get_post_meta( $object->ID, 'mvp_featured_image', true ) ); ?>

	<p>
		<label for="mvp-featured-image"><?php _e( "Select to show or hide the featured image from automatically displaying in this post.", 'mvp-text' ); ?></label>
		<br /><br />
		<select class="widefat" name="mvp-featured-image" id="mvp-featured-image">
            		<option value="show" <?php selected( $selected, 'show' ); ?>>Show</option>
            		<option value="hide" <?php selected( $selected, 'hide' ); ?>>Hide</option>
        	</select>
	</p>
<?php }
}

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_video_embed_meta' ) ) {
function mvp_save_video_embed_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['mvp_video_embed_nonce'] ) || !wp_verify_nonce( $_POST['mvp_video_embed_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['mvp-video-embed'] ) ? balanceTags( $_POST['mvp-video-embed'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'mvp_video_embed';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_featured_headline_meta' ) ) {
function mvp_save_featured_headline_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['mvp_featured_headline_nonce'] ) || !wp_verify_nonce( $_POST['mvp_featured_headline_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['mvp-featured-headline'] ) ? balanceTags( $_POST['mvp-featured-headline'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'mvp_featured_headline';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_photo_credit_meta' ) ) {
function mvp_save_photo_credit_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['mvp_photo_credit_nonce'] ) || !wp_verify_nonce( $_POST['mvp_photo_credit_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['mvp-photo-credit'] ) ? balanceTags( $_POST['mvp-photo-credit'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'mvp_photo_credit';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_post_template_meta' ) ) {
function mvp_save_post_template_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['mvp_post_template_nonce'] ) || !wp_verify_nonce( $_POST['mvp_post_template_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['mvp-post-template'] ) ? balanceTags( $_POST['mvp-post-template'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'mvp_post_template';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_featured_image_meta' ) ) {
function mvp_save_featured_image_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['mvp_featured_image_nonce'] ) || !wp_verify_nonce( $_POST['mvp_featured_image_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['mvp-featured-image'] ) ? balanceTags( $_POST['mvp-featured-image'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'mvp_featured_image';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/////////////////////////////////////
// Add Content Limit
/////////////////////////////////////

if ( !function_exists( 'excerpt' ) ) {
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
}

if ( !function_exists( 'content' ) ) {
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
}

/////////////////////////////////////
// Social Shares
/////////////////////////////////////

if (!function_exists('get_fb')) {
function get_fb( $post_id ) {

	// Check for transient
	if ( ! ( $count = get_transient( 'get_fb' . $post_id ) ) ) {

    // Do API call
    $response = wp_remote_retrieve_body( wp_remote_get( 'http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls=' . urlencode( get_permalink( $post_id ) ), array(

		'sslverify' => false,
		'compress' => true,
		'timeout' => 5

	) ) );

    // If error in API call, stop and don't store transient
    if ( is_wp_error( $response ) )
      return 'error';

    // Decode JSON
    $json = json_decode( $response, true );

    // Set total count
    if(isset($json[0])){
    $count = absint( $json[0]['total_count'] );
	} else { }

	// Set transient to expire every 30 minutes
	set_transient( 'get_fb' . $post_id, absint( $count ), 30 * MINUTE_IN_SECONDS );

	}

 return absint( $count );
} }

if (!function_exists('get_plusones')) {
function get_plusones( $post_id )  {

	// Check for transient
	if ( ! ( $count = get_transient( 'get_plusones' . $post_id ) ) ) {

   $args = array(
            'method' => 'POST',
            'headers' => array(
                // setup content type to JSON
                'Content-Type' => 'application/json'
            ),
            // setup POST options to Google API
            'body' => json_encode(array(
                'method' => 'pos.plusones.get',
                'id' => 'p',
                'method' => 'pos.plusones.get',
                'jsonrpc' => '2.0',
                'key' => 'p',
                'apiVersion' => 'v1',
                'params' => array(
                    'nolog'=>true,
                    'id'=> get_permalink( $post_id ),
                    'source'=>'widget',
                    'userId'=>'@viewer',
                    'groupId'=>'@self'
                )
             )),
             // disable checking SSL certificates
		'compress' => true,
            	'sslverify'=>false,
		'timeout' => 5
        );

    // retrieves JSON with HTTP POST method for current URL
    $json_string = wp_remote_post("https://clients6.google.com/rpc", $args);

    if (is_wp_error($json_string)){
        // return zero if response is error
        return "0";
    } else {
        $json = json_decode($json_string['body'], true);
        // return count of Google +1 for requsted URL
        $count = intval( $json['result']['metadata']['globalCounts']['count'] );
    }

	// Set transient to expire every 30 minutes
	set_transient( 'get_plusones' . $post_id, absint( $count ), 30 * MINUTE_IN_SECONDS );

	}

 return absint( $count );
} }

if (!function_exists('get_pinterest')) {
function get_pinterest( $post_id ) {

	// Check for transient
	if ( ! ( $count = get_transient( 'get_pinterest' . $post_id ) ) ) {

    // Do API call
    $response = wp_remote_retrieve_body( wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?url=' . urlencode( get_permalink( $post_id ) ), array(

		'sslverify' => false,
		'compress' => true,
		'timeout' => 5

	) ) );

    // If error in API call, stop and don't store transient
    if ( is_wp_error( $response ) )
      return 'error';
	$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $response);
    // Decode JSON
    $json = json_decode( $json_string );

    // Set total count
    $count = absint( $json->count );

	// Set transient to expire every 30 minutes
	set_transient( 'get_pinterest' . $post_id, absint( $count ), 30 * MINUTE_IN_SECONDS );

	}

 return absint( $count );
} }

if (!function_exists('mvp_share_count')) {
function mvp_share_count() {

	$post_id = get_the_ID(); ?>

<?php $soc_tot = get_fb( $post_id ) + get_plusones( $post_id ) + get_pinterest( $post_id ); if ($soc_tot > 999999999) {
		$soc_format = number_format($soc_tot / 1000000000, 1) . 'B';
	} else if ($soc_tot > 999999) {
		$soc_format = number_format($soc_tot / 1000000, 1) . 'M';
	} else if ($soc_tot > 999) {
        	$soc_format = number_format($soc_tot / 1000, 1) . 'K';
	} else {
		$soc_format = $soc_tot;
   	}
?>
	<div class="share-count">
	<?php if($soc_format==0) { ?><?php } elseif($soc_format==1) { ?><span class="social-count-num"><?php echo $soc_format; ?></span><span class="social-count-text"><?php _e( 'share', 'mvp-text' ); ?></span><?php } else { ?><span class="social-count"><span class="social-count-num"><?php echo $soc_format; ?></span> <span class="social-count-text"><?php _e( 'shares', 'mvp-text' ); ?></span><?php } ?>
	</div><!--share-count-->

<?php } }

/////////////////////////////////////
// Comments
/////////////////////////////////////

if ( !function_exists( 'mvp_comment' ) ) {
function mvp_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div class="comment-wrapper" id="comment-<?php comment_ID(); ?>">
			<div class="comment-inner">
				<div class="comment-avatar">
					<?php echo get_avatar( $comment, 46 ); ?>
				</div>
				<div class="commentmeta">
					<p class="comment-meta-1">
						<?php printf( __( '%s ', 'mvp-text'), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
					</p>
					<p class="comment-meta-2">
						<?php echo get_comment_date(); ?> <?php _e( 'at', 'mvp-text'); ?> <?php echo get_comment_time(); ?>
						<?php edit_comment_link( __( 'Edit', 'mvp-text'), '(' , ')'); ?>
					</p>
				</div>
				<div class="text">
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<p class="waiting_approval"><?php _e( 'Your comment is awaiting moderation.', 'mvp-text' ); ?></p>
					<?php endif; ?>

					<div class="c">
						<?php comment_text(); ?>
					</div>
				</div><!-- .text  -->
				<div class="clear"></div>
				<div class="comment-reply"><span class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span></div>
			</div><!-- comment-inner  -->
		</div><!-- comment-wrapper  -->
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'mvp-text' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'mvp-text' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
}

/////////////////////////////////////
// Popular Posts
/////////////////////////////////////

if ( !function_exists( 'popularPosts' ) ) {
function popularPosts($num) {
    global $wpdb;

    $posts = $wpdb->get_results("SELECT comment_count, ID, post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , $num");

    foreach ($posts as $post) {
        setup_postdata($post);
        $id = $post->ID;
        $title = $post->post_title;
        $count = $post->comment_count;

        if ($count != 0) {
            $popular .= '<li>';
            $popular .= '<a href="' . get_permalink($id) . '" title="' . $title . '">' . $title . '</a> ';
            $popular .= '</li>';
        }
    }
    return $popular;
}
}

/////////////////////////////////////
// Related Posts
/////////////////////////////////////

if ( !function_exists( 'getRelatedPosts' ) ) {
function getRelatedPosts( $count=3) {
    global $post;
    $orig_post = $post;

    $tags = wp_get_post_tags($post->ID);
    if ($tags) {
        $tag_ids = array();
        foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
        $args=array(
            'tag__in' => $tag_ids,
            'post__not_in' => array($post->ID),
            'posts_per_page'=> $count, // Number of related posts that will be shown.
            'ignore_sticky_posts'=>1
        );
        $my_query = new WP_Query( $args );
        if( $my_query->have_posts() ) { ?>
            <div id="related-posts">
            	<h4 class="post-header"><span class="post-header"><?php _e( 'You may also like...', 'mvp-text' ); ?></span></h4>
			<ul>
            		<?php while( $my_query->have_posts() ) { $my_query->the_post(); ?>
            			<li>
                		<div class="related-image">
					<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('medium-thumb'); ?></a>
					<?php } ?>
				</div><!--related-image-->
				<div class="related-text">
					<a href="<?php the_permalink() ?>" class="main-headline"><?php the_title(); ?></a>
				</div><!--related-text-->
            			</li>
            		<?php }
            echo '</ul></div>';
        }
    }
    $post = $orig_post;
    wp_reset_query();
}
}

/////////////////////////////////////
// Pagination
/////////////////////////////////////

if ( !function_exists( 'pagination' ) ) {
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>".__( 'Page', 'mvp-text' )." ".$paged." ".__( 'of', 'mvp-text' )." ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; ".__( 'First', 'mvp-text' )."</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; ".__( 'Previous', 'mvp-text' )."</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">".__( 'Next', 'mvp-text' )." &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>".__( 'Last', 'mvp-text' )." &raquo;</a>";
         echo "</div>\n";
     }
}
}

/////////////////////////////////////
// Add/Remove User Contact Info
/////////////////////////////////////

if ( !function_exists( 'new_contactmethods' ) ) {
function new_contactmethods( $contactmethods ) {
    $contactmethods['facebook'] = 'Facebook'; // Add Facebook
    $contactmethods['twitter'] = 'Twitter'; // Add Twitter
    $contactmethods['pinterest'] = 'Pinterest'; // Add Pinterest
    $contactmethods['googleplus'] = 'Google Plus'; // Add Google Plus
    $contactmethods['instagram'] = 'Instagram'; // Add Instagram
    $contactmethods['linkedin'] = 'LinkedIn'; // Add LinkedIn

    return $contactmethods;
}
}
add_filter('user_contactmethods','new_contactmethods',10,1);

/////////////////////////////////////
// Social Media Javascript
/////////////////////////////////////

if ( !function_exists( 'mvp_wp_footer' ) ) {
function mvp_wp_footer() {

?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	// Main Menu Dropdown Toggle
	$('.menu-item-has-children a').click(function(event){
	  event.stopPropagation();
	  location.href = this.href;
  	});

	$('.menu-item-has-children').click(function(){
    	  $(this).addClass('toggled');
    	  if($('.menu-item-has-children').hasClass('toggled'))
    	  {
    	  $(this).children('ul').toggle();
	  $('.fly-nav-menu').getNiceScroll().resize();
	  }
	  $(this).toggleClass('tog-minus');
    	  return false;
  	});

	// Main Menu Scroll
	$(window).load(function(){
	  $('.fly-nav-menu').niceScroll({cursorcolor:"#888",cursorwidth: 7,cursorborder: 0,zindex:999999});
	});

});
</script>

<?php $mvp_infinite_scroll = get_option('mvp_infinite_scroll'); if ($mvp_infinite_scroll == "true") { if (isset($mvp_infinite_scroll)) { ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
"use strict";
$('.infinite-content').infinitescroll({
	navSelector: ".nav-links",
	nextSelector: ".nav-links a:first",
	itemSelector: ".infinite-post",
	  loading: {
		msgText: "<?php esc_html_e( 'Loading more posts...', 'mvp-text' ); ?>",
		finishedMsg: "<?php esc_html_e( 'Sorry, no more posts', 'mvp-text' ); ?>"
	  }
});
});
//]]>
</script>
<?php } } ?>

<?php }

}
add_action( 'wp_footer', 'mvp_wp_footer' );

/////////////////////////////////////
// Site Layout
/////////////////////////////////////

if ( !function_exists( 'mvp_site_layout' ) ) {
function mvp_site_layout() {

?>

<style type="text/css">

<?php global $post; $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true); if ($mvp_post_temp == "fullwidth") { ?>
@media screen and (min-width: 1002px) {

	#featured-image {
		height: 400px;
		padding-bottom: 0;
		}

	#featured-image img {
		margin-top: -5%;
		}

	span.photo-credit {
		bottom: 0;
		}

}
<?php } ?>

<?php $mvp_leader_loc = get_option('mvp_leader_loc'); $mvp_logo_loc = get_option('mvp_logo_loc'); if($mvp_leader_loc == 'Above Navigation' && $mvp_logo_loc == 'Wide below leaderboard') { ?>
#logo-large {
	margin-top: 0;
	}
<?php } ?>

<?php $mvp_leader_loc = get_option('mvp_leader_loc'); $mvp_logo_loc = get_option('mvp_logo_loc'); if($mvp_leader_loc == 'Above Navigation' && $mvp_logo_loc == 'Small in navigation') { ?>
#leaderboard-wrapper {
	margin: 20px 0;
	}
<?php } ?>
</style>

<?php if(get_option('mvp_customcss')) { ?>
<style type="text/css">
<?php $customcss = get_option('mvp_customcss'); if ($customcss) { echo stripslashes($customcss); } ?>
</style>
<?php } ?>

<?php }

}

add_action( 'wp_head', 'mvp_site_layout' );

/////////////////////////////////////
// Remove Pages From Search Results
/////////////////////////////////////

if ( !is_admin() ) {

function mvp_SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}

add_filter('pre_get_posts','mvp_SearchFilter');

}

/////////////////////////////////////
// Miscellaneous
/////////////////////////////////////

// Set Content Width
if ( ! isset( $content_width ) ) $content_width = 620;

// Add RSS links to <head> section
add_theme_support( 'automatic-feed-links' );

add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}

// Prevents double posts on second page

add_filter('redirect_canonical','pif_disable_redirect_canonical');

function pif_disable_redirect_canonical($redirect_url) {
    if (is_singular()) $redirect_url = false;
return $redirect_url;
}

/////////////////////////////////////
// Sidekick
/////////////////////////////////////

define('SK_SUBSCRIPTION_ID',1692);
define('SK_ENVATO_PARTNER', 'lbnI5q+neeVc8V0U8DVnDXrcrA3dSiyiMMOwHP8a9Lk=');
define('SK_ENVATO_SECRET', 'RqjBt/YyaTOjDq+lKLWhL10sFCMCJciT9SPUKLBBmso=');

/////////////////////////////////////
// WooCommerce
/////////////////////////////////////

add_theme_support( 'woocommerce' );

?>