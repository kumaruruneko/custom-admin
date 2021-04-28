<?php
$mv = get_option('mv');
$mv_sp = get_option('mv_sp');
$mv_img = wp_get_attachment_image($mv, 'full', false, array('class' => 'img-fluid'));
$mv_sp_img = wp_get_attachment_image($mv_sp, 'full', false, array('class' => 'img-fluid'));
?>
<div class="mv">
	<div class="img text-center">
		<?php echo $mv_img; ?>
	</div>
	<?php get_search_form(); ?>
</div>
<div class="mv mv_sp">
	<div class="img text-center">
		<?php echo $mv_sp_img; ?>
	</div>
	<?php get_search_form(); ?>
</div>