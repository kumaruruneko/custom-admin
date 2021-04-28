<section>
	<h3>CATEGORY</h3>
	<h4>記事の種類<i class="fas fa-chevron-circle-down"></i></h4>
	<ul>
		<?php
		$args = array(
			'parent' => 0,
			'orderby' => 'term_order',
			'order' => 'DISC',
			'taxonomy' => 'news-term'
		);
		$categories = get_categories($args);
		?>

		<?php foreach ($categories as $category) : ?>

		<li>
			<a
				href="<?php echo home(); ?>news?cate=<?php echo $category->slug; ?>&term=<?php echo $category->taxonomy; ?>"><?php echo $category->name; ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
	<h4>店舗<i class="fas fa-chevron-circle-down"></i></h4>
	<ul>
		<?php
		$args = array(
			'parent' => 0,
			'orderby' => 'term_order',
			'order' => 'DISC',
			'taxonomy' => 'store-term',
			'hide_empty' => '0'
		);
		$categories = get_categories($args);
		?>

		<?php foreach ($categories as $category) : ?>

		<li>
			<a
				href="<?php echo home(); ?>news?cate=<?php echo $category->slug; ?>&term=<?php echo $category->taxonomy; ?>"><?php echo $category->name; ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</section>
<section>
	<h3>ARCHIVE</h3>

	<?php
	$year_prev = null;
	$months = $wpdb->get_results("SELECT DISTINCT MONTH( post_date ) AS month ,
	 YEAR( post_date ) AS year,
	  COUNT( id ) as post_count FROM $wpdb->posts  WHERE post_status = 'publish' and post_date <= now( ) and post_type = 'news-list' GROUP BY month , 
		year ORDER BY post_date DESC");
	foreach ($months as $month) :
		$year_current = $month->year;
		if ($year_current != $year_prev) {
			if ($year_prev != null) { ?>
	</ul>
	<?php } ?>

	<h4><?php echo $month->year; ?>年<i class="fas fa-chevron-circle-down"></i></h4>
	<ul>
		<?php } ?>
		<li>
			<a
				href="<?php echo home(); ?>news?set_year=<?php echo $month->year; ?>&set_month=<?php echo date("m", mktime(0, 0, 0, $month->month, 1, $month->year)) ?>">
				<?php echo date("n", mktime(0, 0, 0, $month->month, 1, $month->year)) ?>月
				(<?php echo $month->post_count; ?>)
			</a>
		</li>
		<?php $year_prev = $year_current;
	endforeach; ?>
	</ul>

</section>