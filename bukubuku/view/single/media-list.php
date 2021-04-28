<?php get_header(); ?>

<article class="mediadetail">
	<?php echo breadcrumb(); ?>
	<?php
	$cats = get_the_terms($post->ID, 'media-term');
	?>

	<div class="d-lg-flex justify-content-center">
		<main class="w-1000">
			<div>
				<div class="d-flex align-items-center flex-wrap">
					<?php if ($cats) : ?>
					<dl class="features d-md-flex align-items-center">
						<dt>記事のカテゴリ</dt>
						<dd>
							<?php foreach ($cats as $cat) {
									echo '<span class="mr-2"><a href="' . home_url() . '/media-list/category/' . $cat->slug . '/">' . $cat->name . '</a></span>';
								} ?>
						</dd>
					</dl>
					<?php endif; ?>
					<dl class="d-flex mr-2">
						<dt><i class="fas fa-pen sky mr-2"></i></dt>
						<dd><time datetime="<?php the_time('Y-m-d'); ?>"
								class="gray font-weight-bold"><?php the_time('Y.m.d'); ?></time></dd>
					</dl>
					<dl class="d-flex mr-2">
						<dt><i class="fas fa-sync sky mr-2"></i></dt>
						<dd><time datetime="<?php the_modified_date('Y-m-d'); ?>"
								class="gray font-weight-bold"><?php the_modified_date('Y.m.d'); ?></time></dd>
					</dl>
				</div>
				<?php
				$tags = get_the_tags();
				if($tags):
			?>
				<div>
					<dl class="tags d-md-flex">
						<dt>タグ</dt>
						<dd>
							<?php foreach($tags as $tag){echo '<span class="mr-2"><a href="'.home_url().'/media-list/tag/'.$tag->slug.'/"><span class="hash">#</span>'.$tag->name.'</a></span>';}?>
						</dd>
					</dl>
				</div>
				<?php endif;?>
				<h3 class="newsname"><?php the_title(); ?></h3>
				<div class="img">
					<figure>
						<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('post-thumbnail', array('class' => 'img-fluid', 'size' => 'full')); ?>
						<?php else : ?>
						<img src="<?php echo ($_SERVER['HTTPS'] ? 'https' : 'http');?>://placehold.jp/eeeeee/cccccc/1000x585.png?text=No%20Image" alt="">
						<?php endif; ?>
					</figure>
				</div>
				<div class="info">
					<?php $contents_text = the_content(); ?>
					<?php echo nl2br($contents_text); ?>
				</div>
				<?php
				$areaname = "radio_show";
				$id = $post->ID;
				$radio_show = get_post_meta($id, $areaname, true);
				$comment_show = get_post_meta($id, 'comment', true);
				?>
				<?php if ($radio_show == 'true') : ?>
				<section class="review">
					<h4>レビュー評価</h4>
					<ul>
						<?php
							$id = $post->ID;
							$review = get_post_meta($id, 'review', false);
							?>
						<?php foreach ($review[0] as $value) : ?>
						<li>
							<dl>
								<dt><?php echo $value["'review_title'"]; ?></dt>
								<dd>
									<?php $review_star = $value["'review_star'"]; ?>
									<div class="star">
										<ul class="d-flex">
											<li><i class="fas fa-star"></i></li>
											<li><i class="fas fa-star"></i></li>
											<li><i class="fas fa-star"></i></li>
											<li><i class="fas fa-star"></i></li>
											<li><i class="fas fa-star"></i></li>
										</ul>
										<ul class="d-flex on">
											<?php for ($i = 1; $i <= $review_star; $i++) : ?>
											<li class="w-10"><i class="fas fa-star"></i></li>
											<?php endfor; ?>
										</ul>
									</div>
									<p>星<?php echo $review_star; ?></p>
								</dd>
							</dl>
						</li>
						<?php endforeach; ?>
					</ul>
					<div class="comment">
						<h5>コメント</h5>
						<p>
							<?php echo nl2br($comment_show); ?>
						</p>
					</div>
				</section>
				<?php endif; ?>
				<?php
				$relation_movie = get_post_meta($post->ID, 'relation_movie', true);
				$relation_title = get_post_meta($post->ID, 'relation_title', true);
				?>
				<?php if ($relation_movie) : ?>
				<section class="movie">
					<h4>この記事の関連動画</h4>
					<div class="youtube">
						<a href="javasctript:void(0);" class="js-modal-video" data-video-id="<?php echo $relation_movie; ?>">
							<div class="img">
								<figure><img src="http://img.youtube.com/vi/<?php echo $relation_movie; ?>/hqdefault.jpg" alt="">
								</figure>
							</div>
						</a>
					</div>
					<?php if ($relation_title) : ?>
					<p class="movie_title"><a target="_blank"
							href="https://www.youtube.com/watch?v=<?php echo $relation_movie; ?>"><?php echo $relation_title; ?></a>
					</p>
					<?php endif; ?>
				</section>
				<?php endif; ?>
				<h4 class="share_title">記事をシェアする</h4>
				<?php get_template_part('view/parts/share'); ?>
				<div class="pager">
					<ul>
						<li class="to_prev"><?php previous_post_link('%link', '前のページ') ?></li>
						<li class="to_next"><?php next_post_link('%link', '次のページ') ?></li>
					</ul>
				</div>
			</div>
		</main>
		<?php get_sidebar('media'); ?>
	</div>
</article>


<?php get_footer(); ?>