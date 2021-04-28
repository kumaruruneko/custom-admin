<?php

get_header();

$folder = 'view/page/';

$slug = $post->post_name;

$parent = is_subpage();

if (!empty($parent)) {
	$ancestors = array_reverse(get_post_ancestors($post->ID));
	foreach ($ancestors as $ancestor) {
		$parent_slug = get_page($ancestor)->post_name;
		$folder .= $parent_slug . '/';
	}
}

$file = $folder . $slug;

if (is_search()) {
	$file = 'search';
}

if (locate_template($file . '.php') == '') {

	$file = $folder . 'default';
}

get_template_part($file);

get_footer();