<footer class="">
	<div class="to_top"><a href="javascript:void(0);"><i class="fas fa-chevron-up"></i></a></div>
	<?php
	$f_logo = get_option('f_logo');
	$f_logo_img = wp_get_attachment_image($f_logo, 'full', false, array('class' => 'img-fluid'));
	?>
	<div class="container d-lg-flex flex-wrap">
		<section class="footer_top d-md-flex justify-content-between">
			<p class="logo">
				<a href="<?php echo home_url();?>"><?php echo $f_logo_img; ?></a>
			</p>
			<div class="right_area d-flex justify-content-between">
				<nav>
					<?php
					wp_nav_menu(array(
						'theme_location' => 'main-menu',
						'container' => 'nav',
						'menu_class' => 'd-md-flex',

					));
					?>
				</nav>
				<ul class="icon d-flex">
					<?php
						$tw = get_option('tw');
						$insta = get_option('insta');
					?>
					<?php if(!empty($tw)):?>
						<li><a target="_blank" href="<?php echo $tw; ?>"><i class="fab fa-twitter"></i></a></li>
					<?php endif;?>
					<?php if(!empty($insta)):?>
						<li><a target="_blank" href="<?php echo $insta; ?>"><i class="fab fa-instagram"></i></a></li>
					<?php endif;?>
				</ul>
			</div>
		</section>
		<section class="footer_left d-md-flex">
			<div>
				<h2><span class="en">STORE</span>店舗を探す</h2>
				<nav>
					<ul>
						<li><a href="<?php echo home(); ?>#area">エリアから探す</a></li>
						<li><a href="<?php echo home(); ?>#features">特徴から探す</a></li>
					</ul>
				</nav>
			</div>
			<div>
				<h2><span class="en">MEDIA CATEGORY</span>記事を探す</h2>
				<nav>
					<ul>
						<?php $terms = get_terms('media-term', array(
							'hide_empty' => false,
							'orderby' => 'ID'
						));
						?>
						<?php foreach ($terms as $term) : ?>
						<li><a
								href="<?php echo home(); ?>media-list/category/<?php echo $term->slug; ?>/"><?php echo $term->name; ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>
		</section>
		<section class="footer_right">
			<?php if (is_page('media') || get_post_type() === 'media-list') : ?>
			<?php
				$tags = get_terms('post_tag');
				$tag_i = 0;
				$title = 'TAG';
				$sub_title = '人気のタグ';
				?>
			<div>
				<h2><span class="en"><?php echo $title; ?></span><?php echo $sub_title; ?></h2>
				<nav class="d-md-flex">
					<?php echo(popular_tag('footer'));?>
				</nav>
			</div>
			<?php else : ?>
			<?php
				$area = get_option('area');
				$area_i = 0;
				$title = 'AREA';
				$sub_title = '人気のエリア';
				?>
			<div>
				<h2><span class="en"><?php echo $title; ?></span><?php echo $sub_title; ?></h2>
				<nav class="d-md-flex">
					<ul>
						<?php
							foreach ($area as $key => $value) {
								$parent_id = get_term($key, 'pref-term')->parent;
								$parent_name =  get_term($parent_id, 'pref-term')->name ? get_term($parent_id, 'pref-term')->name . '　' : '' ;
								$parent_slug = get_term($parent_id, 'pref-term')->slug ? get_term($parent_id, 'pref-term')->slug . '/' : '';
								echo '<li><a href="' . home_url() . '/store-list/area/' . $parent_slug . get_term($key, 'pref-term')->slug . '/">' . $parent_name . get_term($key, 'pref-term')->name . '</a></li>';
								$area_i++;
								if ($value === end($area)) {
									echo '</ul>';
								} elseif (($area_i % 5) == '0') {
									echo '</ul><ul>';
								}
							}
							?>
					</ul>
				</nav>
			</div>
			<?php endif; ?>
		</section>
	</div>
	<p class="text-center white copyright"><small><a href="<?php echo home();?>">(c) 2021 BUKUBUKU</a></small></p>
</footer>

<?php wp_footer(); ?>
</body>

</html>