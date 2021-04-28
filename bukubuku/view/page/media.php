<article>
	<main class="mediapage container">
		<?php echo breadcrumb(); ?>
		<div class="mv">
			<?php
			$media_mv = get_option('select');
			$args = array(
				'posts_per_page' => 1, // 表示する投稿数
				'post_type' => 'media-list', // 取得する投稿タイプのスラッグ
				'p' => $media_mv,
			);
			$media_posts = get_posts($args);
			?>
			<?php foreach ($media_posts as $post) : setup_postdata($post); ?>
			<?php
				$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/1200x560.png?text=No%20Image';
				$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
			?>
			<div class="img">
				<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
			</div>
			<div class="info">
				<p class="title"><?php the_title(); ?></p>
				<p class="more"><a href="<?php the_permalink(); ?>/">記事詳細を見る</a></p>
			</div>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<section class="pickup">
			<div class="title">
				<h2><span class="en">PICKUP</span></h2>
				<div class="btn_area">
					<ul>
						<li>
							<div class="swiper-button-prev" id="pickup_prev"></div>
						</li>
						<li>
							<div class="swiper-button-next" id="pickup_next"></div>
						</li>
					</ul>
				</div>
			</div>
			<div class="pickup_slider swiper-container">
				<!-- Additional required wrapper -->
				<div class="swiper-wrapper">
					<!-- Slides -->
					<?php
					$args = array(
						'posts_per_page' => 8, // 表示する投稿数
						'post_type' => 'media-list', // 取得する投稿タイプのスラッグ
						'meta_key' => 'post_views_count',
						'orderby' => 'meta_value',
						'order' => 'DESC'
					);
					$media_posts = get_posts($args);
					?>
					<?php if (is_array($media_posts)) : ?>
					<?php foreach ($media_posts as $post) : setup_postdata($post); ?>
					<?php
						$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
					?>
					<div class="swiper-slide">
						<a href="<?php the_permalink(); ?>/">
							<div class="img">
								<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
							</div>
							<div class="info">
								<?php
										$tarms =  get_the_terms($post->ID, 'media-term');
										?>
								<?php if (is_array($tarms)) : ?>
									<?php foreach ($tarms as $tarm) : ?>
										<?php $media_term = $tarm->name; ?>
										<p class="cat"><?php echo $media_term; ?></p>
									<?php endforeach; ?>
								<?php endif; ?>
								<p class="title"><?php the_title(); ?></p>
								<ul class="tags">
									<?php
													$tags = get_the_terms($post->ID, 'post_tag');
													?>
									<?php if (is_array($tags)) : ?>
									<?php foreach ($tags as $tag) : ?>
									<?php $media_tag = $tag->name;; ?>
									<li><span>#</span><?php echo $media_tag; ?></li>
									<?php endforeach; ?>
									<?php endif; ?>
								</ul>
							</div>
						</a>
					</div>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				</div>
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
					$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
				?>
				<li>
					<a href="<?php the_permalink(); ?>/">
						<div class="img">
							<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
						</div>
						<p class="area">
							<?php if ($term_name[0]) {
										echo $term_name[0];
									} else {
										echo '地域なし';
									} ?>
						</p>
						<p><?php the_title(); ?></p>
					</a>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>

			</ul>
			<p class="more"><a href="<?php echo home(); ?>#area">シーシャ店舗を検索する</a></p>
		</section>
		<?php endif; ?>
		<section class="news">
			<h2><span class="en">NEWS</span>新着情報</h2>
			<ul class="tab_design tab">
				<li class="active"><a href="javascript:void(0);">ALL</a></li>
				<li><a href="javascript:void(0);">店舗検索</a></li>
				<li><a href="javascript:void(0);">メディア</a></li>
			</ul>
			<ul class="tab_list">
				<li class="active">
					<div class="list_box">
						<ul>
							<?php
							$args = array(
								'post_type'      => 'news-list',
								'taxonomy' => 'news-term',
								'posts_per_page' => 3,
							);
							$news_query = new WP_Query($args);
							?>
							<?php if ($news_query->have_posts()) : ?>
							<?php while ($news_query->have_posts()) : ?>
							<?php $news_query->the_post(); ?>
							<?php
									$post_terms = get_the_terms($post->id, 'news-term');
									if (empty($post_terms[0]->name)) {
										$cat_term = '未分類';
									} else {
										$cat_term = $post_terms[0]->name;
									}
									?>

							<li>
								<a href="<?php the_permalink(); ?>/">
									<time datetime="2021-01-01"><?php echo get_the_date('Y.m.d'); ?></time>
									<p class="cat"><?php echo $cat_term; ?></p>
									<p class="title"><?php the_title(); ?></p>
								</a>
							</li>

							<?php endwhile; ?>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
						</ul>
						<p class="more"><a href="<?php echo home(); ?>news-list/">お知らせ一覧へ</a></p>
					</div>
				</li>
				<li>
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
								<?php $post_terms = get_the_terms($post->id, 'news-term'); ?>
								<a href="<?php the_permalink(); ?>">
									<time datetime="2021-01-01"><?php echo get_the_date('Y.m.d'); ?></time>
									<p class="cat"><?php echo $post_terms[0]->name; ?></p>
									<p class="title"><?php the_title(); ?></p>
								</a>
							</li>
							<?php endwhile; ?>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
						</ul>
						<p class="more"><a href="<?php echo home(); ?>news-list/type/store-search/">お知らせ一覧へ</a></p>
					</div>
				</li>
				<li>
					<div class="list_box">
						<ul>
							<?php
							$args = array(
								'post_type'      => 'news-list',
								'taxonomy' => 'news-term',
								'term' => 'media',
								'posts_per_page' => 3,
							);
							$news_query = new WP_Query($args);
							?>
							<?php if ($news_query->have_posts()) : ?>
							<?php while ($news_query->have_posts()) : ?>
							<?php $news_query->the_post(); ?>
							<li>
								<?php $post_terms = get_the_terms($post->id, 'news-term'); ?>
								<a href="<?php the_permalink(); ?>">
									<time datetime="2021-01-01"><?php echo get_the_date('Y.m.d'); ?></time>
									<p class="cat"><?php echo $post_terms[0]->name; ?></p>
									<p class="title"><?php the_title(); ?></p>
								</a>
							</li>
							<?php endwhile; ?>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
						</ul>
						<p class="more"><a href="<?php echo home(); ?>news-list/type/media/">お知らせ一覧へ</a></p>
					</div>
				</li>
			</ul>
		</section>

		<?php
	$args = array(
		'post_type'      => 'media-list',
		'posts_per_page' => 6,
		'tax_query' => array(
			array(
				'taxonomy' => 'media-term', // タクソノミースラッグを指定
				'field' => 'slug',
				'terms' => 'flovar', // タームスラッグを指定
			)
		)
	);
	$news_query = new WP_Query($args);
?>
		<?php if ($news_query->have_posts()) : ?>
		<section class="flavor">
			<h2><span class="en">SHISHA FLAVOR</span>フレーバー</h2>
			<ul class="list d-flex flex-wrap">
				<?php while ($news_query->have_posts()) : ?>
				<?php $news_query->the_post(); ?>
				<?php
					$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
					$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
				?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<div class="img">
							<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
							<?php $post_terms = get_the_terms($post->id, 'media-term'); ?>
							<p class="cat"><?php echo $post_terms[0]->name; ?></p>
						</div>
						<time datetime=""><?php echo get_the_date('Y.m.d'); ?></time>
						<p><?php the_title(); ?></p>
						<ul class="tags">
							<?php
									$tags = get_the_terms($post->ID, 'post_tag');
									?>
							<?php if (is_array($tags)) : ?>
							<?php foreach ($tags as $tag) : ?>
							<?php $media_tag = $tag->name;; ?>
							<li><span>#</span><?php echo $media_tag; ?></li>
							<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</a>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
			<p class="more"><a href="<?php echo home(); ?>media-list/category/flovar/">もっと見る</a></p>
		</section>
		<?php endif; ?>
		<section class="items">
			<h2><span class="en">HOOKAH ITEM / BOTTLE</span>フーカーアイテム・ボトル</h2>
			<div class="item_mv">
				<div class="img">
					<?php
						$hookahs_mv = get_option('hookahs');
						$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						$capture_img = get_the_post_thumbnail_url($hookahs_mv,'full') ? get_the_post_thumbnail_url($hookahs_mv,'full') : $no_img;
					?>
					<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
				</div>
				<div class="info">
					<p class="title"><?php echo get_the_title($hookahs_mv); ?></p>
					<p class="to_detail"><a href="<?php echo get_the_permalink($hookahs_mv); ?>/">記事詳細を見る</a></p>
				</div>
			</div>
			<ul class="list d-flex flex-wrap">
				<?php
				$args = array(
					'post_type'      => 'media-list',
					'posts_per_page' => 3,
					'tax_query' => array(
						array(
							'taxonomy' => 'media-term', // タクソノミースラッグを指定
							'field' => 'slug',
							'terms' => 'hookah-item-bottle', // タームスラッグを指定
						)
					)
				);
				$news_query = new WP_Query($args);
				?>
				<?php if ($news_query->have_posts()) : ?>
				<?php while ($news_query->have_posts()) : ?>
				<?php $news_query->the_post(); ?>
				<?php
					$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
					$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
				?>
				<li>
					<a href="<?php the_permalink(); ?>/">
						<div class="img">
							<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
							<?php $post_terms = get_the_terms($post->id, 'media-term'); ?>
							<p class="cat"><?php echo $post_terms[0]->name; ?></p>
						</div>
						<time datetime=""><?php echo get_the_date('Y.m.d'); ?></time>
						<p><?php the_title(); ?></p>
						<ul class="tags">
							<?php
									$tags = get_the_terms($post->ID, 'post_tag');
									?>
							<?php if (is_array($tags)) : ?>
							<?php foreach ($tags as $tag) : ?>
							<?php $media_tag = $tag->name;; ?>
							<li><span>#</span><?php echo $media_tag; ?></li>
							<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</a>
				</li>
				<?php endwhile; ?>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
			<p class="more"><a href="<?php echo home(); ?>media-list/category/hookah-item-bottle/">もっと見る</a></p>
		</section>
		<section class="howto">
			<h2><span class="en">HOW TO SHISHA</span>シーシャの使い方</h2>
			<ul class="list d-flex flex-wrap">
				<?php
				$args = array(
					'post_type'      => 'media-list',
					'posts_per_page' => 6,
					'tax_query' => array(
						array(
							'taxonomy' => 'media-term', // タクソノミースラッグを指定
							'field' => 'slug',
							'terms' => 'how-to-shisha', // タームスラッグを指定
						)
					)
				);
				$news_query = new WP_Query($args);
				?>
				<?php if ($news_query->have_posts()) : ?>
				<?php while ($news_query->have_posts()) : ?>
				<?php $news_query->the_post(); ?>
				<li>
				<?php
					$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
					$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
				?>
					<a href="<?php the_permalink(); ?>">
						<div class="img">
							<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
							<?php $post_terms = get_the_terms($post->id, 'media-term'); ?>
							<p class="cat"><?php echo $post_terms[0]->name; ?></p>
						</div>
						<time datetime=""><?php echo get_the_date('Y.m.d'); ?></time>
						<p></p><?php the_title(); ?></p>
						<ul class="tags">
							<?php
									$tags = get_the_terms($post->ID, 'post_tag');
									?>
							<?php if (is_array($tags)) : ?>
							<?php foreach ($tags as $tag) : ?>
							<?php $media_tag = $tag->name;; ?>
							<li><span>#</span><?php echo $media_tag; ?></li>
							<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</a>
				</li>
				<?php endwhile; ?>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
			<p class="more"><a href="<?php echo home(); ?>media-list/category/how-to-shisha/">もっと見る</a></p>
		</section>
		<section class="search">
			<h2><span class="en">SEARCH</span>記事検索</h2>
			<div class="features d-lg-flex">
				<div class="title_box">
					<p><i class="fas fa-list"></i></p>
					<h3><span class="en">CATEGORY</span>記事カテゴリ</h3>
				</div>
				<ul class="d-flex list flex-wrap">
					<?php
					$terms = get_terms(
						'media-term',
						array(
							'hide_empty' => false,
							'orderby' => 'ID',
						)
					);
					?>
					<?php foreach ($terms as $term) : ?>
					<li>
						<a href="<?php echo home(); ?>/media-list/category/<?php echo $term->slug; ?>/">
							<?php
								$term_id = $term->term_id;
								$term_data = get_term($term_id);
								$capture = get_term_meta($term_id, 'capture', true);
								$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
								$capture_img = wp_get_attachment_image_url($capture, 'full', false) ? wp_get_attachment_image_url($capture, 'full', false) : $no_img;
							?>
							<div class="img">
								<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
							</div>
							<div>
								<p class="title"><?php echo $term->name; ?></p>
							</div>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="tag d-lg-flex">
				<div class="title_box">
					<p><i class="fas fa-tag"></i></p>
					<h3><span class="en">TAG</span>人気のタグ</h3>
				</div>
				<ul class="d-flex list flex-wrap">
					<?php
					$tags = get_terms('post_tag');
					?>
					<?php if (is_array($tags)) : ?>
					<?php foreach ($tags as $tag) : ?>
					<?php $media_tag = $tag->name; ?>
					<li><a
							href="<?php echo home(); ?>media-list/tag/<?php echo $tag->slug; ?>/"><span>#</span><?php echo $media_tag; ?></a>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="form">
				<form method="get" action="<?php echo home(); ?>">
					<ul class="d-flex align-items-center flex-wrap">
						<li class="title">
							<h3>キーワードで探す</h3>
						</li>
						<li class="input_box"><input type="text" name="s" placeholder="店舗名、地域、エリア、ジャンルなどを記入して検索！"></li>
						<li class="btn_box"><button>SEARCH</button></li>
					</ul>
					<input type="hidden" name="post_type" value="media-list">
				</form>
			</div>
		</section>

		<?php
		$args = array(
			'post_type' => 'movie-list',
			'posts_per_page' => 3,
		);
		$movie = new WP_Query($args);
		?>

		<?php if ($movie->have_posts()) : ?>
		<section class="movie">
			<h2><span class="en">MOVIE</span>新着動画</h2>
			<div class="d-md-flex movie_box">
				<?php for ($i = 0; $i < 3; $i++) : $movie->the_post(); ?>
				<?php if ($i == 0) : ?>
				<div class="left">
					<div class="youtube">
						<a href="javasctript:void(0);" class="js-modal-video"
							data-video-id="<?php echo get_post_meta($post->ID, 'movie', true); ?>">
							<div class="img">
								<figure><img
										src="http://img.youtube.com/vi/<?php echo get_post_meta($post->ID, 'movie', true); ?>/hqdefault.jpg"
										alt=""></figure>
							</div>
						</a>
					</div>
				</div>
				<div class="right">
					<?php else : ?>
					<div class="movie<?php echo $i + 1; ?>">
						<?php if ($post) : ?>
						<a href="javasctript:void(0);" class="js-modal-video"
							data-video-id="<?php echo get_post_meta($post->ID, 'movie', true); ?>">
							<div class="img">
								<figure><img
										src="http://img.youtube.com/vi/<?php echo get_post_meta($post->ID, 'movie', true); ?>/hqdefault.jpg"
										alt=""></figure>
							</div>
						</a>
						<?php else : ?>
						<div class="img">
							<figure><img class="img-fluid" src="<?php echo ($_SERVER['HTTPS'] ? 'https' : 'http');?>://placehold.jp/eeeeee/cccccc/640x360.png?text=No%20Image">
							</figure>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php endfor; ?>
					<p class="more"><a href="<?php echo home(); ?>movie">MORE</a></p>
				</div>
			</div>
		</section>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
		<section class="contact">
			<h3><span class="en">CONTACT</span>お問い合わせ</h3>
			<p class="text">当メディアサイトに関するご相談や各種お問い合せは<br>こちらの問い合わせフォームよりお願い致します。</p>
			<p class="to_link"><a href="<?php echo home() ;?>contact">お問い合わせフォーム</a></p>
		</section>
	</main>
</article>