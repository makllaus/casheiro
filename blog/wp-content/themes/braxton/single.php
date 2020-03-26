<?php get_header(); ?>
	<div id="content-wrapper">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true); if ($mvp_post_temp == "fullwidth") { ?>
		<?php } else { ?>
		<div id="content-main">
		<?php } ?>
			<?php $mvp_featured_img = get_option('mvp_featured_img'); if ($mvp_featured_img == "true") { ?>
				<?php if(get_post_meta($post->ID, "mvp_video_embed", true)): ?>
					<?php echo get_post_meta($post->ID, "mvp_video_embed", true); ?>
				<?php else: ?>
					<?php $mvp_show_hide = get_post_meta($post->ID, "mvp_featured_image", true); if ($mvp_show_hide == "hide") { ?>
					<?php } else { ?>
						<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
						<div id="featured-image" itemscope itemtype="http://schema.org/Article">
							<?php $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true); if ($mvp_post_temp == "fullwidth") { ?>
								<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), '' ); ?>
							<?php } else { ?>
								<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'post-thumb' ); ?>
							<?php } ?>
							<img itemprop="image" src="<?php echo $thumb['0']; ?>" />
							<?php if(get_post_meta($post->ID, "mvp_photo_credit", true)): ?>
							<span class="photo-credit"><?php echo get_post_meta($post->ID, "mvp_photo_credit", true); ?></span>
							<?php endif; ?>
						</div><!--featured-image-->
						<?php } ?>
					<?php } ?>
				<?php endif; ?>
			<?php } ?>
			<div id="home-main">
				<div id="post-area" itemscope itemtype="http://schema.org/Article" <?php post_class(); ?>>
					<h3 class="story-cat"><?php $category = get_the_category(); echo $category[0]->cat_name; ?></h3>
					<h1 class="story-title" itemprop="name"><?php the_title(); ?></h1>
					<div id="post-info">
						<?php _e( 'By', 'mvp-text' ); ?>&nbsp;<span class="author" itemprop="author"><?php the_author_posts_link(); ?></span>&nbsp;|&nbsp;<time class="post-date" itemprop="datePublished" datetime="<?php the_time('Y-m-d'); ?>" pubdate><?php the_time(get_option('date_format')); ?></time>
					</div><!--post-info-->
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
						<?php if(get_option('mvp_article_ad')) { ?>
							<div id="article-ad">
								<?php $articlead = get_option('mvp_article_ad'); if ($articlead) { echo stripslashes($articlead); } ?>
							</div><!--article-ad-->
						<?php } ?>
						<div class="post-tags">
							<span class="post-tags-header"><?php _e( 'Related Items', 'mvp-text' ); ?></span><?php the_tags('','','') ?>
						</div><!--post-tags-->
					</div><!--content-area-->
				</div><!--post-area-->
					<?php $author = get_option('mvp_author_box'); if ($author == "true") { ?>
					<div id="author-wrapper">
						<div id="author-info">
							<?php echo get_avatar( get_the_author_meta('email'), '100' ); ?>
							<div id="author-text">
								<span class="author-name"><?php the_author_posts_link(); ?></span>
								<p><?php the_author_meta('description'); ?></p>
								<ul>
									<?php $authordesc = get_the_author_meta( 'facebook' ); if ( ! empty ( $authordesc ) ) { ?>
									<li class="fb-item">
										<a href="http://www.facebook.com/<?php the_author_meta('facebook'); ?>" alt="Facebook" class="fb-but" target="_blank"></a>
									</li>
									<?php } ?>
									<?php $authordesc = get_the_author_meta( 'twitter' ); if ( ! empty ( $authordesc ) ) { ?>
									<li class="twitter-item">
										<a href="http://www.twitter.com/<?php the_author_meta('twitter'); ?>" alt="Twitter" class="twitter-but" target="_blank"></a>
									</li>
									<?php } ?>
									<?php $authordesc = get_the_author_meta( 'pinterest' ); if ( ! empty ( $authordesc ) ) { ?>
									<li class="pinterest-item">
										<a href="http://www.pinterest.com/<?php the_author_meta('pinterest'); ?>" alt="Pinterest" class="pinterest-but" target="_blank"></a>
									</li>
									<?php } ?>
									<?php $authordesc = get_the_author_meta( 'googleplus' ); if ( ! empty ( $authordesc ) ) { ?>
									<li class="google-item">
										<a href="<?php the_author_meta('googleplus'); ?>" alt="Google Plus" class="google-but" target="_blank"></a>
									</li>
									<?php } ?>
									<?php $authordesc = get_the_author_meta( 'instagram' ); if ( ! empty ( $authordesc ) ) { ?>
									<li class="instagram-item">
										<a href="http://www.instagram.com/<?php the_author_meta('instagram'); ?>" alt="Instagram" class="instagram-but" target="_blank"></a>
									</li>
									<?php } ?>
									<?php $authordesc = get_the_author_meta( 'linkedin' ); if ( ! empty ( $authordesc ) ) { ?>
									<li class="linkedin-item">
										<a href="http://www.linkedin.com/company/<?php the_author_meta('linkedin'); ?>" alt="Linkedin" class="linkedin-but" target="_blank"></a>
									</li>
									<?php } ?>
								</ul>
							</div><!--author-text-->
						</div><!--author-info-->
					</div><!--author-wrapper-->
					<?php } ?>
				<?php $prev_next = get_option('mvp_prev_next'); if ($prev_next == "true") { ?>
				<div class="prev-next-wrapper">
					<div class="prev-post">
						<?php previous_post_link('&larr; '.__('Previous Story', 'mvp-text').' %link', '%title', TRUE); ?>
					</div><!--prev-post-->
					<div class="next-post">
						<?php next_post_link(''.__('Next Story', 'mvp-text').' &rarr; %link', '%title', TRUE); ?>
					</div><!--next-post-->
				</div><!--prev-next-wrapper-->
				<?php } ?>
				<?php getRelatedPosts(); ?>
				<?php comments_template(); ?>
			</div><!--home-main-->
		<?php $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true); if ($mvp_post_temp == "fullwidth") { ?>
		<?php } else { ?>
		</div><!--content-main-->
		<?php get_sidebar(); ?>
		<?php } ?>
		<?php endwhile; endif; ?>
	</div><!--content-wrapper-->
</div><!--main-wrapper-->
<?php get_footer(); ?>