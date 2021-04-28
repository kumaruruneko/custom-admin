<?php get_header(); ?>


<article class="shoplist">
	<section class="title">
		<h2><span class="en">SHOP LIST</span>店舗一覧</h2>
	</section>
	<div class="container">
		<?php echo breadcrumb(); ?>
		<?php
		$area_type = $_GET['area'];
		$features_type = $_GET['features'];
		$cat_area_id = get_term_by('slug', $area_type, 'pref-term');

		if ($area_type) {
			$title_text = '【エリア】　' . $area_type . '　での';
			$get_term = get_term_by('slug', $area_type, 'pref-term');
		}
		if ($features_type) {
			$title_text =
				'【特徴】　' . $features_type . '　での';
			$get_term = get_term_by('slug', $features_type, 'store-term');
		}
		?>
		<div class="d-lg-flex justify-content-center">
			<main class="w-1000">
				<div class="search">
					<h3><?php echo $title_text; ?>検索結果一覧</h3>
					<div class="search_detail">
						<div>
							<?php if ($features_type) : ?>
							<dl class="features">
								<dt><?php echo $features_type; ?></dt>
								<dd></dd>
							</dl>
							<?php endif; ?>
							<?php if ($area_type) : ?>
							<?php if ($get_term->parent !== 0) {
									$parent_area = get_term_by('term_id', $get_term->parent, 'pref-term');
									$child_area = $get_term->name;
									$parent_area = $parent_area->name;
								} else {
									$parent_area = $get_term->name;
								}; ?>

							<dl class="area">
								<dt><?php echo $parent_area; ?></dt>
								<?php if ($child_area) : ?>
								<dd><?php echo $child_area; ?></dd>
								<?php endif; ?>
							</dl>
							<?php endif; ?>
						</div>
						<select name="" id="">
							<option value="">新着順</option>
						</select>
					</div>
				</div>
				<ul class="list">
					<?php

					$args = array(
						'post_type'      => 'store-list',
						'paged' => $paged,
						'posts_per_page' => 5,
						'tax_query' => array(
							array(
								'taxonomy' => $get_term->taxonomy,
								'field' => 'slug',
								'terms' => $get_term->slug,
							)
						)
					);
					if (empty($get_term)) {
						unset($args['tax_query']);
					}
					$shop_list_query = new WP_Query($args);
					$max_num_pages = $shop_list_query->max_num_pages;
					?>
					<?php if ($shop_list_query->have_posts()) : ?>
					<?php while ($shop_list_query->have_posts()) : ?>
					<?php $shop_list_query->the_post(); ?>
					<?php $fields_all = get_post_custom($post->id);; ?>
					<li>
						<p class="name"><?php the_title(); ?></p>
						<div class="img">
							<figure> <?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('post-thumbnail', array('class' => 'img-fluid', 'size' => 'full')); ?>
								<?php else : ?>
								<img src="http://placehold.jp/eeeeee/cccccc/400x300.png?text=No%20Image" alt="">
								<?php endif; ?>
							</figure>
						</div>
						<div class="info">
							<ul>
								<li>
									<?php 
									$post_terms = get_the_terms($post->id, 'pref-term');
									?>

									<?php if ($post_terms[0]->parent !== 0) {
												$parent_area = get_term_by('term_id', $post_terms[0]->parent, 'pref-term');
												$child_area = $post_terms[0]->name;
												$parent_name = $parent_area->name;
											} else {
												$parent_area = $post_terms[0]->name;
												$child_area = '';
											}; ?>
									<?php if($parent_name) :?>
									<dl class="area">
										<dt><?php echo $parent_name; ?></dt>
										<?php if ($child_area) : ?>
										<dd><?php echo $child_area; ?></dd>
										<?php endif; ?>
									</dl>
									<?php endif ;?>
									<?php
											$post_terms = get_the_terms($post->id, 'store-term');
											?>
									<dl class="features">
										<dt>特徴</dt>

										<dd>
											<?php foreach ($post_terms as $name) : ?>
											<span><?php echo $name->name; ?></span>
											<?php endforeach; ?>
										</dd>

									</dl>
								</li>
								<li class="address"><i
										class="fas fa-map-marker-alt"></i><?php echo $fields_all['detail_fields_1'][0]; ?></li>
								<li class="time">
									<dl>
										<dt>営業時間</dt>
										<dd><?php echo $fields_all['detail_fields_3'][0]; ?></dd>
									</dl>
								</li>
							</ul>
						</div>
						<div class="shop_tags">
							<ul>
								<?php
										$feature_array = [];
										$feature_array = [
											1 =>	'Wi-Fi使用可',
											2 => '30席以上あり',
											4 => '飲食持ち込み可',
											8 => '駐車場あり',
											16 => '電源あり',
											32 => '予約可',
											64 => '喫煙可',
											128 => '貸し切り可',
										];
										$_feature = get_post_meta($post->ID, 'feature_check', false);
										if (is_null($_feature[0])) {
											$feature_check_bit = 0;
										} else {
											$feature_check_bit = array_sum($_feature[0]);
										}

										?>

								<?php foreach ($feature_array as $bit => $name) : ?>
								<?php if ((int)$feature_check_bit & (int)$bit) {
												$class = 'active';
												$checked = "checked";
											} else {
												$class = '';
												$checked = "";
											}
											?>

								<li class="<?php echo $class; ?>"><?php echo $name; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<p class="to_detail"><a href="<?php permalink_link(); ?>">店舗詳細を見る</a></p>
					</li>
					<?php endwhile; ?>
					<?php else : ?>
					<li>
						<h4 class="title mt-5">このカテゴリーで公開中の情報はありません</h4>
					</li>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</ul>
				<?php
				if ($shop_list_query->max_num_pages > 1) {
					$paginate_args = array(
						'base' => home_url('/shops/') . '%_%',
						'format' => 'page/%#%/',
						'current' => max(1, $paged),
						'mid_size' => 4,
						'next_text' => '次のページ',
						'prev_text' => '前のページ',
						'type' => 'list',
						'total' => $shop_list_query->max_num_pages
					);

					$paginate_links = paginate_links($paginate_args);
					echo $paginate_links;
				}; ?>

				<?php wp_reset_postdata(); ?>
			</main>
			<aside>
				<section>
					<h3>KEYWORD</h3>
					<form action=""><input type="text"><button><i class="fas fa-search"></i></button></form>
				</section>
				<section class="link">
					<h3>エリア</h3>
					<nav>
						<ul>
							<?php $terms = get_terms('pref-term', array(
								'hide_empty' => true,
								'orderby' => 'ID',
								'parent' => 0
							));
							?>
							<?php foreach ($terms as $term) : ?>
							<li>
								<a href="<?php echo home(); ?>shops?area=<?php echo $term->slug; ?>"><?php echo $term->name; ?></a>
								<?php
									$term_id = $term->term_id;
									$child_terms = get_term_children($term_id, 'pref-term');
									?>
								<?php if (!empty($child_terms)) : ?>
								<ul>
									<?php $taxonomy_name = 'pref-term';
											$terms = get_terms($taxonomy_name, array(
												'parent' => $term_id,
												'hide_empty' => false,
												'orderby' => 'term_order',
											));; ?>

									<?php foreach ($terms as $child_term) : ?>
									<li>
										<a
											href="<?php echo home(); ?>shops?area=<?php echo $child_term->slug; ?>"><?php echo $child_term->name; ?></a>
									</li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
							</li>
							<?php endforeach; ?>
						</ul>
					</nav>
				</section>
				<section class="link">
					<h3>特徴</h3>
					<nav>
						<ul>
							<?php
							$terms = get_option('checkbox');

							?>
							<?php foreach ($terms as $term_id => $value) : ?>
							<?php $term_data = get_term($term_id);; ?>
							<li><a
									href="<?php echo home(); ?>shops?features=<?php echo $term_data->slug; ?>"><?php echo $term_data->name; ?></a>
							</li>
							<?php endforeach; ?>
						</ul>
					</nav>
				</section>
			</aside>
		</div>
	</div>
</article>


<?php get_footer(); ?>