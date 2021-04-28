<?php get_header(); ?>

<article class="movie">
	<section class="title">
		<h2><span class="en">MOVIE LIST</span>動画一覧</h2>
	</section>
	<?php echo breadcrumb(); ?>
<?php
	$args = new WP_Query(
		array(
			'post_type' => 'movie-list',
			'posts_per_page' => 12,
		)
	);
?>
	<main>
		<div class="container w-1000">
			<?php if ( $args->have_posts() ) : ?>
				<ul class="list d-flex flex-wrap">
					<?php while ( $args->have_posts() ) : ?>
					<?php $args->the_post(); ?>
						<li>
							<a href="javasctript:void(0);" class="js-modal-video" data-video-id="<?php echo get_post_meta($post->ID,'movie',true);?>">
								<div class="img">
									<figure><img src="http://img.youtube.com/vi/<?php echo get_post_meta($post->ID,'movie',true);?>/hqdefault.jpg" alt=""></figure>
								</div>
							</a>
							<p><a href="https://www.youtube.com/watch?v=<?php echo get_post_meta($post->ID,'movie',true);?>" target="_blank"><?php the_title();?></a></p>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</main>
</article>

<?php get_footer(); ?>