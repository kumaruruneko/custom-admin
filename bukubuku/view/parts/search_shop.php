<article class="shoplist">
	<section class="title">
		<h2><span class="en">SHOP LIST</span>店舗一覧</h2>
	</section>
	<?php echo breadcrumb();?>
	<div class="container">
		<div class="d-lg-flex justify-content-center">
			<main class="w-1000">
				<div class="search">
					<?php 
								if(isset($get_area)){
									$search_title = $get_area;
								}elseif(isset($get_feature)){
									$search_title = $get_feature;
								}else{
									$search_title = '検索結果';
								}
								?>
					<h3><?php echo $search_title ;?>一覧</h3>
					<div class="search_detail">
						<div>
							<?php if(isset($_GET['features'])):?>
							<dl class="features d-flex">
								<dt>特徴</dt>
								<dd><span><?php echo $_GET['features'];?></span></dd>
							</dl>
							<?php endif;?>
							<?php if(isset($_GET['area'])):?>
							<?php
                                    $get_term = get_term_by('slug', $_GET['area'], 'pref-term');
                                    if ($get_term->parent !== 0) {
                                        $parent_area = get_term_by('term_id', $get_term->parent, 'pref-term');
                                        $child_area = $get_term->name;
                                        $parent_area = $parent_area->name;
                                    } else {
                                        $parent_area = $get_term->name;
                                    };
                                ?>

							<dl class="area">
								<dt><?php echo $parent_area; ?></dt>
								<?php if ($child_area) : ?>
								<dd><?php echo $child_area; ?></dd>
								<?php endif; ?>
							</dl>
							<?php endif; ?>
						</div>
						<select name="" id="sort">
							<?php
                                $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                                if(isset($_GET['sort'])){
                                    $url = str_replace('?sort=brows','',$url);
                                    $url = str_replace('?sort=brows','',$url);
                                    $url = str_replace('?sort=desc','',$url);
                                    $url = str_replace('&sort=asc','',$url);
                                    $url = str_replace('&sort=desc','',$url);
                                    $url = str_replace('&sort=asc','',$url);
                                }
								$para = isset($_GET['s']) ? '&' : '?';
                            ?>
							<option value="<?php echo $url;?>" <?php if(!isset($_GET['sort'])){echo ' selected';}?>>新着順</option>
							<option value="<?php echo $url.$para;?>sort=brows"
								<?php if(isset($_GET['sort'])&&$_GET['sort'] == 'brows'){echo ' selected';}?>>閲覧順</option>
							<option value="<?php echo $url.$para;?>sort=desc"
								<?php if(isset($_GET['sort'])&&$_GET['sort'] == 'desc'){echo ' selected';}?>>五十音(昇順)</option>
							<option value="<?php echo $url.$para;?>sort=asc"
								<?php if(isset($_GET['sort'])&&$_GET['sort'] == 'asc'){echo ' selected';}?>>五十音(降順)</option>
						</select>
					</div>
				</div>
				<?php if(have_posts()): ?>
				<ul class="list">
					<?php while(have_posts()): the_post(); ?>
					<?php
						$features = get_the_terms($post->ID,'store-term');
						$shop_area = get_the_terms($post->ID,'pref-term');
						if($shop_area[0]->parent != ''){
							$pref = get_term($shop_area[0]->parent)->name;
							$area = $shop_area[0]->name;
						}else{
							$pref = $shop_area[0]->name;
							$area = '';
						}
						$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
					?>
					<li>
						<p class="name"><a href="<?php echo get_the_permalink();?>"><?php the_title();?></a></p>
						<div class="img">
							<a href="<?php echo get_the_permalink();?>">
								<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
							</a>
						</div>
						<div class="info">
							<ul>
								<li>
									<?php if(isset($shop_area) && $shop_area != ''):?>
									<dl class="area">
										<dt><?php echo $pref;?></dt>
										<dd><?php echo $area;?></dd>
									</dl>
									<?php endif;?>
									<?php if(isset($features) && $features != ''):?>
									<dl class="features">
										<dt>特徴</dt>
										<dd>
											<?php
                                                    foreach($features as $feature){
                                                        echo '<span>'.$feature->name.'</span>';
                                                    }
                                                ?>
										</dd>
									</dl>
									<?php endif;?>
								</li>
								<li class="address"><i
										class="fas fa-map-marker-alt"></i><?php echo get_post_meta($post->ID,'detail_fields_1',true);?></li>
								<li class="time">
									<dl>
										<dt>営業時間</dt>
										<dd><?php echo get_post_meta($post->ID,'detail_fields_3',true);?></dd>
									</dl>
								</li>
							</ul>
						</div>
						<div class="shop_tags">
							<?php echo features_list($post->ID);?>
						</div>
						<p class="to_detail"><a href="<?php echo get_the_permalink();?>">店舗詳細を見る</a></p>
					</li>
					<?php endwhile; ?>
				</ul>
				<?php else: ?>
				<p>検索された店舗はありません。</p>
				<?php endif; ?>
				<?php
                global $wp_query;
				$area = get_query_var('pref-term') ? '/area/' . get_query_var('pref-term') : '' ;
				$feature = get_query_var('store-term') ? '/feature/' . get_query_var('store-term') : '' ;
				if ($wp_query->max_num_pages > 1) {
					$paginate_args = array(
						'base' => home_url('/store-list') . $area . $feature . '%_%',
						'format' => '/page/%#%/',
						'current' => max(1, $paged),
						'mid_size' => 4,
						'next_text' => '次のページ',
						'prev_text' => '前のページ',
						'type' => 'list',
						'total' => $wp_query->max_num_pages
					);

					$paginate_links = paginate_links($paginate_args);
					echo $paginate_links;
				}; ?>
			</main>
			<?php get_sidebar('shop');?>
		</div>
	</div>
</article>