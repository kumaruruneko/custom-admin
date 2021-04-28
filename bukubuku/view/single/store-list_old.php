<?php get_header(); ?>

<article class="shops">
	<?php echo breadcrumb(); ?>
	<?php
	$post_id = get_the_ID();
	$pref_terms = get_the_terms($post->id, 'pref-term');
	$store_terms = get_the_terms($post->id, 'store-term');
	$get_post_data = get_post_custom($post->id);
	$addressformap = $get_post_data['detail_fields_1'][0];
	$addressformap_pre = get_post_meta($post->ID, 'detail_fields_1_1', true);
	if ($addressformap_pre) {
		$addressformap = $addressformap_pre;
	}
	$addressformapencode = urlencode(mb_convert_encoding($addressformap, "UTF-8", "auto"));
	?>

	<main>
		<div class="container w-1000">
			<div class="d-flex pb-4 flex-wrap ">
				<?php if ($pref_terms) : ?>
				<?php if ($pref_terms[0]->parent !== 0) {
						$parent_area = get_term_by('term_id', $pref_terms[0]->parent, 'pref-term');
						$parent_area = $parent_area->name;
						$child_area = $pref_terms[0]->name;
					} else {
						$parent_area = $pref_terms[0]->name;
					}; ?>
				<dl class="area mr-5 single">
					<dt>エリア</dt>
					<dd>

						<span class="mr-2"><a
								href="<?php echo home(); ?>/store-list/area/<?php echo $parent_area; ?>/"><?php echo $parent_area; ?></a></span>
						<?php if ($child_area) : ?>
						<span class="mr-2"><a
								href="<?php echo home(); ?>/store-list/area/<?php echo $parent_area; ?>/<?php echo $child_area; ?>/"><?php echo $child_area; ?></a></span>
					</dd>
					<?php endif; ?>
				</dl>
				<?php endif; ?>
				<dl class="features single">
					<dt>特徴</dt>
					<?php if (!empty($store_terms)) : ?>
					<dd>
						<?php foreach ($store_terms as $value) {
								echo '<span class="mr-2"><a href="' . home_url() . '/store-list/feature/' . $value->slug . '/">' . $value->name . '</a></span>';
							}; ?>
					</dd>
					<?php endif; ?>
				</dl>
			</div>
			<h3 class="shopname"><?php echo get_the_title($post->ID); ?></h3>
			<div class="img">
				<figure>
					<?php if (has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('post-thumbnail', array('class' => 'img-fluid', 'size' => 'full')); ?>
					<?php else : ?>
					<img src="http://placehold.jp/eeeeee/cccccc/1000x585.png?text=No%20Image" alt="">
					<?php endif; ?>
				</figure>
			</div>
			<ul class="sns d-flex justify-content-end">
				<?php
				$tw = $get_post_data['sns_1'][0];
				$fb = $get_post_data['sns_2'][0];
				$insta = $get_post_data['sns_3'][0];
				?>
				<?php if ($tw) : ?>
				<li><a href="<?php echo $get_post_data['sns_1'][0]; ?>"><i class="fab fa-twitter"></i></a></li>
				<?php endif; ?>
				<?php if ($fb) : ?>
				<li><a href="<?php echo $get_post_data['sns_2'][0]; ?>"><i class="fab fa-facebook-f"></i></a></li>
				<?php endif; ?>
				<?php if ($insta) : ?>
				<li><a href="<?php echo $get_post_data['sns_3'][0]; ?>"><i class="fab fa-instagram"></i></a></li>
				<?php endif; ?>
			</ul>
			<div class="info">
				<div class="table">
					<ul>
						<li>
							<dl>
								<dt>住所</dt>
								<dd><?php echo $get_post_data['detail_fields_1'][0]; ?>
									<?php if ($addressformap) : ?>
									<a href="#map" class="map">MAP</a>
									<?php endif; ?>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>電話番号</dt>
								<dd><?php echo $get_post_data['detail_fields_2'][0]; ?></dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>営業時間</dt>
								<dd><?php echo $get_post_data['detail_fields_3'][0]; ?></dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>休業日</dt>
								<dd><?php echo $get_post_data['detail_fields_4'][0]; ?></dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>料金</dt>
								<?php $textarea = nl2br($get_post_data['detail_fields_5'][0]); ?>
								<dd><?php echo $textarea; ?></dd>
							</dl>
						</li>
					</ul>
				</div>
				<p>
					<?php global $post;
					echo nl2br($post->post_content); ?>
				</p>
				<div class="shop_tags">
					<?php echo features_list($post->ID); ?>
				</div>
				<?php if ($addressformap) : ?>
				<div class="map" id="map">
					<iframe src="https://maps.google.co.jp/maps?q=<?php echo $addressformapencode; ?>&z=16&output=embed"
						style="border:0;" allowfullscreen="" loading="lazy"></iframe>
				</div>
				<?php endif; ?>
				<?php
				$sub_img_array = get_post_meta($post->ID, 'sub_img', false);
				?>

				<?php if (!empty($sub_img_array[0][0])) : ?>
				<div class="swiper-container">
					<!-- Additional required wrapper -->
					<div class="swiper-wrapper">
						<!-- Slides -->
						<?php foreach ($sub_img_array[0] as $value) : ?>
						<div class="swiper-slide">
							<?php $image_url = wp_get_attachment_url($value);; ?>
							<a href="<?php echo $image_url; ?>" rel="lightbox">
								<div class="img">
									<p class="img_box" style="background:url(<?php echo $image_url; ?>) center center / cover;"></p>
								</div>
							</a>
						</div>
						<?php endforeach; ?>
					</div>

					<div class="swiper-button-prev" id="prev_btn"></div>
					<div class="swiper-button-next" id="next_btn"></div>
				</div>
				<?php endif; ?>
			</div>

			<?php
			//現在の記事に紐付いているターム一覧を取得
			$diff_terms_this = get_the_terms($post->ID, 'store-term'); ?>
			<?php if (!empty($diff_terms_this)) : ?>
			<?php	// 現在の記事の[name]だけを取り出し配列に格納
				foreach ($diff_terms_this as $name_array_1) : ?>
			<?php $set_array_1[] = $name_array_1->name; ?>
			<?php endforeach; ?>
			<?php
				$args = array(
					'posts_per_page' => -1,
					'post_type' => 'store-list',
					'post__not_in' => array($post_id),
				);
				$my_posts = get_posts($args);
				?>
			<?php
				//店舗カテゴリーの記事を全件回すけど現在の記事は省く
				foreach ($my_posts as $post) : setup_postdata($post); ?>

			<?php
					//全記事ごとに属するタームを取得
					$diff_terms_current = get_the_terms($post->ID, 'store-term'); ?>


			<?php
					if (!empty($diff_terms_current)) {
						//比較対象となる各記事のタームの[name]を格納
						$set_array_2 = []; //初期化
						foreach ($diff_terms_current as $name_array_2) {
							$set_array_2[] = $name_array_2->name;
						};
					?>

			<?php
						if (!empty($set_array_2)) {
							//現在の記事のタームと各記事のタームを比較
							$diff_check = array_diff($set_array_1, $set_array_2);
						} else {
							$diff_check = [];
						}

						?>

			<?php
						//一つでも一致するタームがあれば
						if (!empty($diff_check)) {
							//記事IDを配列に格納
							$get_store_id[] = $post->ID;
							
						}
				//表示する記事IDの配列を持って下部へ
			
					}

					?>

			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>


			<?php
				$args = array(
					'post_type' => 'store-list',
					'post__in' => $get_store_id,
					'posts_per_page' => 5,
					'orderby' => 'rand',
					'post__not_in' => array($post->ID), //念のため、もう一度現在の記事を省く
					'tax_query' => array(
						array(
							'taxonomy' => 'pref-term', //地域カテゴリーでも一致する
							'field' => 'slug',
							'terms' => $pref_terms[0]->slug,
							'operator' => 'IN',
						)
					)
				);

				$shop_list_query = new WP_Query($args);
				?>
			<?php if ($shop_list_query->have_posts()) : ?>
			<div class="near">
				<div class="title">
					<h3>この店舗の近くの店舗</h3>
					<div class="btn_area">
						<ul>
							<li>
								<div class="swiper-button-prev" id="shop_prev"></div>
							</li>
							<li>
								<div class="swiper-button-next" id="shop_next"></div>
							</li>
						</ul>
					</div>
				</div>

				<div class="shop_slider swiper-container">
					<!-- Additional required wrapper -->
					<div class="swiper-wrapper">
						<!-- Slides -->
						<?php while ($shop_list_query->have_posts()) : ?>


						<?php $shop_list_query->the_post(); ?>
						<?php
							$capture_img = get_the_post_thumbnail_url($post->ID, 'full') ? get_the_post_thumbnail_url($post->ID, 'full') : 'http://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						?>

						<div class="swiper-slide">
							<a href="<?php the_permalink(); ?>">
								<div class="img">
									<p class="img_box" style="background:url(<?php echo $capture_img; ?>) center center / cover;"></p>
								</div>
								<?php
											$parent_terms = get_the_terms($post->ID, 'pref-term');
											if ($pref_terms[0]->parent !== 0) {
												// $parent_terms = get_term_by('term_id', $pref_terms[0]->parent, 'pref-term');
												$parent_area = $parent_terms->slug;
												$parent_name = $parent_terms->name;
											} else {
												$parent_area = $pref_terms[0]->slug;
												$parent_name = $pref_terms[0]->name;
											};
											?>
								<?php foreach ($parent_terms as $terms) : ?>
								<?php
												$get_term = $terms->name;
												$get_parent = $terms->parent;
												if ($get_parent !== 0) {
													$parent_term = get_term_by('term_id', $terms->parent, 'pref-term');
													$set_parent = $parent_term->name . ' ';
												} else {
													$set_parent = '';
												}
												?>

								<?php endforeach; ?>

								<p class="area"><?php echo $set_parent . $get_term; ?></p>

								<p><?php the_title(); ?></p>
							</a>
						</div>
						<?php endwhile; ?>
						<?php else : ?>

						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<?php get_template_part('view/parts/share'); ?>
		</div>
	</main>
</article>


<?php get_footer(); ?>