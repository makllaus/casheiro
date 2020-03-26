<?php get_header(); ?>
	<div id="content-wrapper">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="content-main">
			<?php $mvp_featured_img = get_option('mvp_featured_img'); if ($mvp_featured_img == "true") { ?>
			<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
			<div id="featured-image">
				<?php the_post_thumbnail('post-thumb'); ?>
				<?php if(get_post_meta($post->ID, "mvp_photo_credit", true)): ?>
				<span class="photo-credit"><?php _e( 'Photo:', 'mvp-text' ); ?> <?php echo get_post_meta($post->ID, "mvp_photo_credit", true); ?></span>
				<?php endif; ?>
			</div><!--featured-image-->
			<?php } ?>
			<?php } ?>
			<div id="home-main">
				<div id="post-area" <?php post_class(); ?>>
					<h1 class="story-title"><?php the_title(); ?></h1>
					<?php $socialbox = get_option('mvp_social_box'); if ($socialbox == "true") { ?>
						<div class="social-sharing-top">
							<?php mvp_share_count(); ?>
							<a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>', 'facebookShare', 'width=626,height=436'); return false;" title="Share on Facebook"><div class="facebook-share"><span class="fb-but1"></span><p><?php _e( 'Share', 'mvp-text' ); ?></p></div></a>
							<a href="#" onclick="window.open('http://twitter.com/share?text=<?php the_title(); ?> -&url=<?php the_permalink() ?>', 'twitterShare', 'width=626,height=436'); return false;" title="Tweet This Post"><div class="twitter-share"><span class="twitter-but1"></span><p><?php _e( 'Tweet', 'mvp-text' ); ?></p></div></a>
							<a href="#" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?php the_permalink();?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'post-thumb' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>', 'pinterestShare', 'width=750,height=350'); return false;" title="Pin This Post"><div class="pinterest-share"><span class="pinterest-but1"></span><p><?php _e( 'Share', 'mvp-text' ); ?></p></div></a>
							<a href="#" onclick="window.open('https://plusone.google.com/_/+1/confirm?hl=en-US&url=<?php the_permalink() ?>', 'googleShare', 'width=626,height=436'); return false;" title="Share on Google+" target="_blank"><div class="google-share"><span class="google-but1"></span><p><?php _e( 'Share', 'mvp-text' ); ?></p></div></a>
							<a href="<?php comments_link(); ?>"><div class="social-comments"><p><?php comments_number(__( '0 comments', 'mvp-text'), __('1 comment', 'mvp-text'), __('% comments', 'mvp-text'));?></p></div></a>
						</div><!--social-sharing-top-->
					<?php } ?>
					<div id="content-area">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
					</div><!--content-area-->
				</div><!--post-area-->
			</div><!--home-main-->
		</div><!--content-main-->
		<?php get_sidebar(); ?>
		<?php endwhile; endif; ?>
	</div><!--content-wrapper-->
</div><!--main-wrapper-->
<?php get_footer(); ?>