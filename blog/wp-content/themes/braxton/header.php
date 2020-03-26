<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" >
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />

<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium-thumb' ); ?>
<meta property="og:image" content="<?php echo $thumb['0']; ?>" />
<?php } ?>

<?php if ( ! function_exists( '_wp_render_title_tag' ) ) { function theme_slug_render_title() { ?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php } add_action( 'wp_head', 'theme_slug_render_title' ); } ?>

<?php if(get_option('mvp_favicon')) { ?><link rel="shortcut icon" href="<?php echo get_option('mvp_favicon'); ?>" /><?php } ?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php $analytics = get_option('mvp_tracking'); if ($analytics) { echo stripslashes($analytics); } ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="site">
	<?php get_template_part('fly-menu'); ?>
	<div id="nav-top-wrap" class="left relative">
		<div id="nav-top-mid" class="relative">
			<div id="nav-top-cont" class="left relative">
				<?php if(get_option('mvp_logo_loc') == 'Small in navigation') { ?>
					<?php $mvp_leader_loc = get_option('mvp_leader_loc'); if($mvp_leader_loc == 'Above Navigation') { ?>
						<?php if(get_option('mvp_header_leader')) { ?>
							<div id="leaderboard-wrapper" class="logo-header">
								<?php $ad970 = get_option('mvp_header_leader'); if ($ad970) { echo stripslashes($ad970); } ?>
							</div><!--leaderboard-wrapper-->
						<?php } ?>
					<?php } ?>
				<?php } else if(get_option('mvp_logo_loc') == 'Left of leaderboard') { ?>
				<div id="leaderboard-wrapper" class="logo-leader">
					<?php $mvp_leader_loc = get_option('mvp_leader_loc'); if($mvp_leader_loc == 'Above Navigation') { ?>
						<?php if(get_option('mvp_header_leader')) { ?>
							<div id="leader-medium">
								<?php $ad970 = get_option('mvp_header_leader'); if ($ad970) { echo stripslashes($ad970); } ?>
							</div><!--leader-medium-->
						<?php } ?>
					<?php } ?>
					<div id="logo-medium" itemscope itemtype="http://schema.org/Organization">
						<?php if(get_option('mvp_logo')) { ?>
							<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_option('mvp_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
						<?php } else { ?>
							<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-medium.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
						<?php } ?>
					</div><!--logo-medium-->
				</div><!--leaderboard-wrapper-->
				<?php } else if(get_option('mvp_logo_loc') == 'Wide below leaderboard') { ?>
				<div id="leaderboard-wrapper" class="logo-header">
					<?php $mvp_leader_loc = get_option('mvp_leader_loc'); if($mvp_leader_loc == 'Above Navigation') { ?>
						<?php if(get_option('mvp_header_leader')) { ?>
							<div id="leader-large">
								<?php $ad970 = get_option('mvp_header_leader'); if ($ad970) { echo stripslashes($ad970); } ?>
							</div><!--leader-large-->
						<?php } ?>
					<?php } ?>
					<div id="logo-large" itemscope itemtype="http://schema.org/Organization">
						<?php if(get_option('mvp_logo')) { ?>
							<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_option('mvp_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
						<?php } else { ?>
							<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-large.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
						<?php } ?>
					</div><!--logo-large-->
				</div><!--leaderboard-wrapper-->
				<?php } ?>
			</div><!--nav-top-cont-->
		</div><!--nav-top-mid-->
	</div><!--nav-top-wrap-->
	<div id="nav-wrapper">
		<div class="nav-wrap-out">
		<div class="nav-wrap-in">
			<div id="nav-inner">
			<div class="fly-but-wrap left relative">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div><!--fly-but-wrap-->
			<?php $mvp_logo_loc = get_option('mvp_logo_loc'); if($mvp_logo_loc == 'Left of leaderboard' || $mvp_logo_loc == 'Wide below leaderboard') { ?>
				<div class="logo-small-fade" itemscope itemtype="http://schema.org/Organization">
					<?php if(get_option('mvp_logo')) { ?>
						<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_option('mvp_logo_nav'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
					<?php } else { ?>
						<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-small.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
					<?php } ?>
				</div><!--logo-small-fade-->
			<?php } else { ?>
				<div id="logo-small" itemscope itemtype="http://schema.org/Organization">
					<?php if(get_option('mvp_logo')) { ?>
						<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_option('mvp_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
					<?php } else { ?>
						<a itemprop="url" href="<?php echo home_url(); ?>"><img itemprop="logo" src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-small.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
					<?php } ?>
				</div><!--logo-small-->
			<?php } ?>
			<div id="main-nav">
				<?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
			</div><!--main-nav-->
			<div id="search-button">
				<img src="<?php echo get_template_directory_uri(); ?>/images/search-icon.png" />
			</div><!--search-button-->
			<div id="search-bar">
				<?php get_search_form(); ?>
			</div><!--search-bar-->
			</div><!--nav-inner-->
		</div><!--nav-wrap-in-->
		</div><!--nav-wrap-out-->
	</div><!--nav-wrapper-->
	<?php if ( is_page_template('page-home.php') ) { ?>
	<?php $mvp_slider = get_option('mvp_slider'); if ($mvp_slider == "true") { ?>
	<div id="featured-wrapper" class="iosslider">
		<ul class="featured-items slider">
			<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => get_option('mvp_slider_tags'), 'posts_per_page' => get_option('mvp_slider_num')  )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
			<li class="slide">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
				<?php the_post_thumbnail('post-thumb'); ?>
				<?php } else { ?>
				<img src="<?php echo get_template_directory_uri(); ?>/images/demo.jpg" alt="<?php the_title(); ?>" />
				<?php } ?>
				<?php if(get_post_meta($post->ID, "mvp_video_embed", true)): ?>
					<div class="video-button">
						<img src="<?php echo get_template_directory_uri(); ?>/images/video-but.png" alt="<?php the_title(); ?>" />
					</div><!--video-button-->
				<?php endif; ?>
				<div class="featured-text">
					<h3><?php $category = get_the_category(); echo $category[0]->cat_name; ?></h3>
					<?php if(get_post_meta($post->ID, "mvp_featured_headline", true)): ?>
					<h2><?php echo get_post_meta($post->ID, "mvp_featured_headline", true); ?></h2>
					<?php else: ?>
					<h2><?php the_title(); ?></h2>
					<?php endif; ?>
					<div class="featured-excerpt">
						<p><?php echo excerpt(25); ?></p>
					</div><!--featured-excerpt-->
				</div><!--featured-text-->
				</a>
			</li>
			<?php } endwhile; ?>
		</ul>
		<div class="featured-shade">
			<div class="left-shade"></div>
			<div class="right-shade"></div>
		</div><!--featured-shade-->
		<div class="prev">&lt;</div>
		<div class="next">&gt;</div>
	</div><!--featured-wrapper-->
	<?php } ?>
	<?php } elseif ( is_category() ) { ?>
	<?php $mvp_slider_cat = get_option('mvp_slider_cat'); if ($mvp_slider_cat == "true") { ?>
	<div id="featured-wrapper" class="iosslider">
		<ul class="featured-items slider">
			<?php global $do_not_duplicate; global $post; $current_category = single_cat_title("", false); $category_id = get_cat_ID($current_category); $cat_posts = new WP_Query(array('posts_per_page' => get_option('mvp_slider_cat_num'), 'cat' => $category_id )); while($cat_posts->have_posts()) : $cat_posts->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
			<li class="slide">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
				<?php the_post_thumbnail('post-thumb'); ?>
				<?php } else { ?>
				<img src="<?php echo get_template_directory_uri(); ?>/images/demo.jpg" alt="<?php the_title(); ?>" />
				<?php } ?>
				<?php if(get_post_meta($post->ID, "mvp_video_embed", true)): ?>
					<div class="video-button">
						<img src="<?php echo get_template_directory_uri(); ?>/images/video-but.png" alt="<?php the_title(); ?>" />
					</div><!--video-button-->
				<?php endif; ?>
				<div class="featured-text">
					<?php if(get_post_meta($post->ID, "mvp_featured_headline", true)): ?>
					<h2><?php echo get_post_meta($post->ID, "mvp_featured_headline", true); ?></h2>
					<?php else: ?>
					<h2><?php the_title(); ?></h2>
					<?php endif; ?>
					<div class="featured-excerpt">
						<p><?php echo excerpt(25); ?></p>
					</div><!--featured-excerpt-->
				</div><!--featured-text-->
				</a>
			</li>
			<?php } endwhile; ?>
		</ul>
		<div class="featured-shade">
			<div class="left-shade"></div>
			<div class="right-shade"></div>
		</div><!--featured-shade-->
		<div class="prev">&lt;</div>
		<div class="next">&gt;</div>
	</div><!--featured-wrapper-->
	<?php } ?>
	<?php } ?>
	<div id="body-wrapper">
		<?php if ( is_home() || is_front_page() ) { ?>
		<?php if(get_option('mvp_wall_ad')) { ?>
		<div id="wallpaper">
			<?php if(get_option('mvp_wall_url')) { ?>
			<a href="<?php echo get_option('mvp_wall_url'); ?>" class="wallpaper-link" target="_blank"></a>
			<?php } ?>
		</div><!--wallpaper-->
		<?php } ?>
		<?php } ?>
		<div id="main-wrapper">
			<?php $mvp_leader_loc = get_option('mvp_leader_loc'); if($mvp_leader_loc == 'Above Navigation') { } else { ?>
				<?php if(get_option('mvp_header_leader')) { ?>
					<div id="leaderboard-wrapper" class="leader-bottom">
						<?php $ad970 = get_option('mvp_header_leader'); if ($ad970) { echo stripslashes($ad970); } ?>
					</div><!--leaderboard-wrapper-->
				<?php } ?>
			<?php } ?>