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
					<img src="<?php echo ($_SERVER['HTTPS'] ? 'https' : 'http');?>://placehold.jp/eeeeee/cccccc/1000x585.png?text=No%20Image" alt="">
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

$area_children = get_term_children($pref_terms[0]->term_taxonomy_id,$pref_terms[0]->taxonomy);
$areas[] = $pref_terms[0]->term_taxonomy_id;
if($area_children){
	foreach($area_children as $item){
		$areas[] = $item;
	}
}
if($store_terms){
	foreach($store_terms as $item){
		$features[] = $item->term_id;
	}
}else{
	$terms = get_terms('store-term');
	foreach($terms as $item){
		$features[] = $item->term_id;
	}
}
if($store_terms){
	$tax_query = array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'pref-term',
			'field' => 'id',
			'terms' => $areas,
			'operator' => 'IN',
		),
		array(
			'taxonomy' => 'store-term',
			'field' => 'id',
			'terms' => $features,
			'operator' => 'IN',
		)
	);
}else{
	$tax_query = array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'pref-term',
			'terms' => $areas,
			'operator' => 'IN',
		),
		array(
			'taxonomy' => 'store-term',
			'terms' => $features,
			'operator' => 'NOT IN',
		)
	);
}

$args = array(
	'post_type' => 'store-list',
	'posts_per_page' => 5,
	'post__not_in' => array($post->ID),
	'orderby' => 'rand',
	'tax_query' => $tax_query
);

$shop_list_query = new WP_Query($args);

// var_dump($shop_list_query);

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
								$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
								$capture_img = get_the_post_thumbnail_url($post->ID, 'full') ? get_the_post_thumbnail_url($post->ID, 'full') : $no_img;
							?>

							<div class="swiper-slide">
								<a href="<?php the_permalink(); ?>">
									<div class="img">
										<p class="img_box" style="background:url(<?php echo $capture_img; ?>) center center / cover;"></p>
									</div>
									<?php
										$parent_terms = get_the_terms($post->ID, 'pref-term');
										foreach ($parent_terms as $terms){
											$get_term = $terms->name;
											$get_parent = $terms->parent;
											if ($get_parent !== 0) {
												$parent_term = get_term_by('term_id', $terms->parent, 'pref-term');
												$set_parent = $parent_term->name . ' ';
											} else {
												$set_parent = '';
											}
										}
									?>

									<p class="area"><?php echo $set_parent . $get_term; ?></p>

									<p><?php the_title(); ?></p>
								</a>
							</div>
							<?php endwhile; ?>
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