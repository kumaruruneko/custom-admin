<?php

get_header();

global $post;

// var_dump($wp_query);

set_post_views($post->ID);

$folder = 'view/parts/';

if( !empty(get_query_var('pref-term')) || !empty(get_query_var('store-term')) || get_query_var('post_type') == 'store-list' ){
    $file=$folder.'search_shop';
}elseif(get_query_var('post_type') == 'news-list' || !empty(get_query_var('news-term'))){
    $file=$folder.'news-list';
}else{
    $file=$folder.'search_media';
}

$folder = 'view/archive/';

if( locate_template( $file.'.php' )=='' ){

	$file = $folder.'default';

}

get_template_part( $file );

get_footer();

