<?php get_header(); ?>

<article>
	<main class="top container">
		<?php get_template_part('view/parts/mv-top'); ?>
		<section class="news">
			<h2><span class="en">NEWS</span>新着情報</h2>
			<div class="list_box">
				<ul>
					<?php
					$args = array(
						'post_type'      => 'news-list',
						'taxonomy' => 'news-term',
						'term' => 'store-search',
						'posts_per_page' => 3,
					);
					$news_query = new WP_Query($args);
					?>
					<?php if ($news_query->have_posts()) : ?>
					<?php while ($news_query->have_posts()) : ?>
					<?php $news_query->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>">
							<time><?php echo get_the_date('Y.m.d'); ?></time>
							<p class="cat">店舗検索</p>
							<p><?php the_title(); ?></p>
						</a>
					</li>
					<?php endwhile; ?>
					<?php else : ?>
					<li>
						<p>公開中の新着情報はまだありません</p>
					</li>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</ul>
				<p class="more"><a href="<?php echo home(); ?>news-list/type/store-search/">お知らせ一覧へ</a></p>
			</div>
		</section>
	<?php
		$args = array(
			'post_type'      => 'store-list',
			'posts_per_page' => 5,
			'meta_key' => '_recommend',
			'meta_value' => 'true',
			'meta_compare' => 'LIKE'
		);
		$store_query = new WP_Query($args);
	?>
	<?php if ($store_query->have_posts()) : ?>
		<section class="recommend">
			<h2><span class="en">RECOMMEND</span>おすすめ店舗</h2>
			<ul class="d-flex flex-wrap">

				<?php while ($store_query->have_posts()) : $store_query->the_post(); ?>
				<?php
					$terms = get_the_terms($post->ID, 'pref-term');
					if ($terms) {
						foreach ($terms as $term) {
							$term_name[] = $term->name;
						}
					}
					$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
					$capture_img = get_the_post_thumbnail_url($post->ID, 'full') ? get_the_post_thumbnail_url($post->ID, 'full') : $no_img;
				?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<div class="img">
							<p class="img_box" style="background:url(<?php echo $capture_img; ?>) center center / cover;"></p>
						</div>
						<?php
								if ($terms[0]->name == '未分類') {
									echo '<p class="area">地域未登録</p>';
								} else {
									echo '<p class="area">' . $terms[0]->name . '</p>';
								}
								?>
						<p><?php the_title(); ?></p>
					</a>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
		</section>
	<?php endif; ?>
		<section class="search">
			<h2><span class="en">SEARCH</span>シーシャ店を探す</h2>
			<div class="area d-flex flex-wrap" id="area">
				<div class="title_box">
					<p><i class="fas fa-map-marker-alt"></i></p>
					<h3><span class="en">AREA</span>エリアから探す</h3>
				</div>
				<ul class="d-flex list flex-wrap">
					<?php $terms = get_terms('pref-term', array(
						'hide_empty' => true,
						'orderby' => 'ID',
						'posts_per_page' => 12,
						'parent' => 0
					));
					?>
					<?php if (!empty($terms)) : ?>
					<?php foreach ($terms as $term) : ?>
					<?php if ($term->name !== '未分類') : ?>
					<li><a href="<?php echo home(); ?>store-list/area/<?php echo $term->slug; ?>/"><?php echo $term->name; ?></a>
					</li>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="features d-flex flex-wrap" id="features">
				<div class="title_box">
					<p><i class="fas fa-list"></i></p>
					<h3><span class="en">FEATURES</span>特徴から探す</h3>
				</div>
				<?php
				$terms = get_option('checkbox');
				?>
				<?php if (!empty($terms)) : ?>
				<ul class="d-flex list flex-wrap">

					<?php foreach ($terms as $term_id => $value) : ?>
					<?php
						$term_data = get_term($term_id);
						$capture = get_term_meta($term_id, 'capture', true);
						$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						$capture_img = wp_get_attachment_image_url($capture, 'full', false) ? wp_get_attachment_image_url($capture, 'full', false) : $no_img;
					?>
					<li>
						<a href="<?php echo home(); ?>store-list/feature/<?php echo $term_data->slug; ?>/">
							<div class="img">
								<p class="img_box" style="background:url(<?php echo $capture_img; ?>) center center / cover;"></p>
							</div>
							<div>
								<p class="title"><?php echo $term_data->name; ?></p>
								<p><?php echo $term_data->description; ?></p>
							</div>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<?php get_search_form(); ?>
		</section>
		<section class="about">
			<div class="inner">
				<h2>シーシャとは</h2>
				<p>シーシャ（別名：水タバコ）とは、イスラム圏発祥の娯楽の一種です。<br>
					近年では日本でも多くの方に浸透し、注目されています。</p>
				<p>シーシャ（水タバコ）では、フルーツ系、紅茶系、ミント系など数百種類ものフレーバー（蜜漬けしたタバコの葉）が存在しており、その人好みや、その日の気分によって味を組み合わせる事が一つの楽しみでもあります。</p>
				<p>通常の紙たばことは違い、タール成分がない事から歯や壁紙の黄ばみなどを起こす事はなく男女共に注目され、そして愛されています。</p>
				<p>そんな、シーシャ（水タバコ）の魅力を【SHISHA-NAVI】では多くに人にお届けします。</p>
				<p class="more"><a href="#">シーシャをもっと知る</a></p>
			</div>
		</section>
		<section class="newpost">
			<h2><span class="en">NEW POST</span>新着記事</h2>
			<ul class="d-flex flex-wrap">
				<?php
				$args = array(
					'post_type'      => 'media-list',
					'posts_per_page' => 4,
				);
				$media_query = new WP_Query($args);
				?>
				<?php while ($media_query->have_posts()) : $media_query->the_post(); ?>
				<?php
					$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
					$capture_img = get_the_post_thumbnail_url($post->ID, 'full') ? get_the_post_thumbnail_url($post->ID, 'full') : $no_img;
				?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<div class="img">
							<p class="img_box" style="background:url(<?php echo $capture_img; ?>) center center / cover;"></p>
							<?php
								$terms = get_the_terms($post->ID, 'media-term');
								if ($terms) {
									$cat_name = $terms[0]->name;
								}
								?>
							<?php if ($cat_name) : ?>
							<p class="cat"><?php echo $cat_name; ?></p>
							<?php endif; ?>
						</div>
						<time datetime=""><?php echo get_the_date('Y.m.d'); ?></time>
						<p><?php the_title(); ?></p>
					</a>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
			<p class="more"><a href="<?php echo home(); ?>media-list/">全ての記事を見る</a></p>
		</section>
	</main>
</article>


<?php get_footer(); ?>