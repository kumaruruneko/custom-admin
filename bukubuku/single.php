<?php

get_header();

global $post;
set_post_views($post->ID);

// var_dump($post);

$folder = 'view/single/';

$slug = get_post_type();

$file=$folder.$slug;

if( locate_template( $file.'.php' )=='' ){

	$file = $folder.'default';

}

get_template_part( $file );

get_footer();

