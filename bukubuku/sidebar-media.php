
<aside>
	<section>
		<h3>KEYWORD</h3>
		<form method="get" action="<?php echo home(); ?>">
			<input type="text" value="" name="s">
			<button><i class="fas fa-search"></i></button>
			<input type="hidden" value="media-list" name="post_type">
		</form>
	</section>
	<?php
		$cats = get_terms('media-term');
	?>
	<?php if ( $cats ) : ?>
		<section class="link">
			<h3>カテゴリ</h3>
			<nav>
				<ul>
					<?php foreach ( $cats as $cat ) : ?>
						<li><a href="<?php echo home_url();?>/media-list/category/<?php echo $cat->slug;?>/"><?php echo $cat->name;?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</section>
	<?php endif; ?>
	<?php
		$terms = get_terms('post_tag');
	?>
	<?php if ( $terms ) : ?>
		<section class="tags">
			<h3>人気タグ</h3>
			<nav>
				<?php echo(popular_tag('side'));?>
			</nav>
		</section>
	<?php endif; ?>
<?php
global $wpdb;
$select = "SELECT DISTINCT * FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id WHERE post_type = 'media-list' AND meta_key = 'post_views_count' ORDER BY CAST(meta_value AS SIGNED) DESC LIMIT 0,5";
$ranking = $wpdb->get_results($wpdb->prepare($select));
?>
	<section class="ranking">
		<h3>人気記事ランキング</h3>
		<ul>
			<?php if($ranking):?>
				<?php $i = 1;?>
				<?php foreach($ranking as $item):?>
					<?php
						$cats = get_the_terms($item->ID,'media-term');
						$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						$capture_img = get_the_post_thumbnail_url($item->ID,'full') ? get_the_post_thumbnail_url($item->ID,'full') : $no_img;
					?>
					<li>
						<a href="<?php echo get_permalink($item->ID);?>/">
							<div class="img">
								<p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
								<img src="<?php echo get_template_directory_uri();?>/src/img/common/rank<?php echo $i;?>.png" alt="" class="rank">
							</div>
							<?php
								if($cats){
									echo '<p class="cat">'.$cats[0]->name.'</p>';
								}
							?>
							<p><?php echo $item->post_title;?></p>
						</a>
					</li>
				<?php $i++;?>
				<?php endforeach;?>
			<?php endif; ?>
		</ul>
	</section>
</aside>