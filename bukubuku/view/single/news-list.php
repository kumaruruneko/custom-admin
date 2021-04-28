<?php get_header(); ?>

<article class="newsdetail">
	<section class="title">
		<h2><span class="en">NEWS</span>新着情報</h2>
	</section>
	<?php echo breadcrumb();?>
	<main>
		<div class="container w-1000">
			<div class="d-flex align-items-center">
				<dl class="features">
					<?php
						$terms = get_the_terms($post->ID, 'news-term');
						if(!empty($terms)){
							$term_name = $terms[0]->name;
						}else{
							$term_name = '未分類';
						}
					?>
					<dt><?php echo $term_name; ?></dt>
					<dd></dd>
				</dl>
				<dl class="d-flex mr-2">
					<dt><i class="fas fa-pen sky mr-2"></i></dt>
					<dd><time datetime="<?php echo get_the_date('Y.m.d'); ?>" class="gray font-weight-bold"><?php echo get_the_date('Y.m.d'); ?></time></dd>
				</dl>
				<dl class="d-flex mr-2">
					<dt><i class="fas fa-sync sky mr-2"></i></dt>
					<dd><time
							datetime="<?php echo get_the_modified_date('Y.m.d'); ?>" class="gray font-weight-bold"><?php echo get_the_modified_date('Y.m.d'); ?></time>
					</dd>
				</dl>
			</div>
			<h3 class="newsname"><?php echo get_the_title(); ?></h3>
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
				<?php echo $post->post_content; ?>
			</div>

			<?php get_template_part('view/parts/share') ;?>
			<div class="pager">
				<ul>
					<?php
					$prev_post = get_previous_post();
					if (!empty($prev_post)) {
						$url_p = get_permalink($prev_post->ID);
					}
					$prev_post = get_next_post();
					if (!empty($prev_post)) {
						$url_n = get_permalink($prev_post->ID);
					}
					?>
					<?php if (!empty($url_p)) : ?>
					<li class="to_prev"><a href="<?php echo $url_p; ?>">前のページ</a></li>
					<?php endif; ?>
					<?php if (!empty($url_n)) : ?>
					<li class="to_next"><a href="<?php echo $url_n; ?>">次のページ</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</main>
</article>


<?php get_footer(); ?>