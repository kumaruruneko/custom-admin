<aside>
	<section>
		<h3>KEYWORD</h3>
		<form method="get" action="<?php echo home(); ?>">
			<input type="text" value="" name="s" class="s" id="s">
			<button><i class="fas fa-search"></i></button>
			<input type="hidden" value="shop-list" name="post_type">
		</form>
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
				<?php if($term->name !== '未分類') :?>
				<li>
					<a href="<?php echo home(); ?>/store-list/area/<?php echo $term->slug; ?>/"><?php echo $term->name; ?></a>
					<?php
						$term_id = $term->term_id;
						$child_terms = get_term_children($term_id, 'pref-term');
						?>
					<?php if (!empty($child_terms)) : ?>
					<ul>
						<?php $taxonomy_name = 'pref-term';
								$terms = get_terms($taxonomy_name, array(
									'parent' => $term_id,
									'hide_empty' => true,
									'orderby' => 'term_order',
								)); ?>

						<?php foreach ($terms as $child_term) : ?>
						<li>
							<a
								href="<?php echo home(); ?>/store-list/area/<?php echo $term->slug; ?>/<?php echo $child_term->slug; ?>"><?php echo $child_term->name; ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</nav>
	</section>
	<?php
$args = array(
	'parent' => 0,
	'orderby' => 'term_order',
	'order' => 'DISC',
	'taxonomy' => 'store-term',
	'hide_empty' => true
);
$features = get_categories($args);
?>
	<?php if ( $features ) : ?>
	<section class="link">
		<h3>特徴</h3>
		<nav>
			<ul>
				<?php foreach ($features as $feature) : ?>
				<?php if($feature->count !== 0) :?>
				<li>
					<a href="<?php echo home(); ?>/store-list/feature/<?php echo $feature->slug; ?>/"><?php echo $feature->name; ?></a>
				</li>
				<?php endif ;?>
				<?php endforeach; ?>
			</ul>
		</nav>
	</section>
	<?php endif;?>
</aside>