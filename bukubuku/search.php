<?php get_header(); ?>

<?php
	if(isset($_GET['post_type']) && $_GET['post_type'] == 'media-list'){
		get_template_part('view/parts/search_media');
	}else{
		get_template_part('view/parts/search_shop');
	}
?>
<?php
get_footer(); ?>