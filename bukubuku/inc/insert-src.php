<?php

/*-------------------------------------------*/
/*    管理画面で使用したいcss・jsを読み込む
/*-------------------------------------------*/
function add_dashboard()
{
	add_action('admin_enqueue_scripts', 'my_admin_style');
	add_action('admin_enqueue_scripts', 'my_admin_script');
}
// 管理画面　css
function my_admin_style()
{
	
	wp_enqueue_style('admin', get_template_directory_uri() . '/src/css/admin.css', false, '0.0.3');
	wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.1.0/css/all.css');
}
// 管理画面　js
function my_admin_script()
{
	wp_enqueue_media();
	wp_enqueue_script('upload', get_template_directory_uri() . '/src/js/mediauploader.js', array('jquery'), '0.0.2', true);
	wp_enqueue_script('admins', get_template_directory_uri() . '/src/js/admins.js', array('jquery'), '0.0.4', true);
	$js_array = array(
		'theme'   => theme_dir(),
		'home'   => home(),
		'img'   => img_dir()
	);
	wp_localize_script('admin', 'admin', $js_array);
}
add_action('admin_menu', 'add_dashboard');


/*-------------------------------------------*/
/*    フロント画面で使用したいcss・jsを読み込む
/*-------------------------------------------*/
function add_front_theme()
{
	add_action('wp_enqueue_scripts', 'my_front_style');
	add_action('wp_enqueue_scripts', 'my_front_script');
}
// フロント画面　css
function my_front_style()
{

	$dir = get_template_directory_uri() . '/src/css/';
	
	wp_enqueue_style('fonts',  'https://fonts.googleapis.com/css2?family=Oswald&display=swap', false, '0.0.2');
	wp_enqueue_style('lightbox', $dir . 'lightbox.css', false, '0.0.2');
	if(is_page('media') || is_page('movie') || is_singular('media-list')){
		wp_enqueue_style('movie', $dir . 'modal-video.min.css', false);
	}
	wp_enqueue_style('core', $dir . 'style.css', false, '0.0.4');
}
// フロント画面　js
function my_front_script()
{

	$dir = get_template_directory_uri() . '/src/js/';
	// wp_deregister_script('jquery');
	// wp_enqueue_script('lightbox', $dir . 'lightbox.js', array('jquery'), '0.0.1', true);
	// wp_enqueue_script('youtube', 'https://www.youtube.com/iframe_api', array('jquery'), '0.0.1', true);
	// wp_enqueue_script('app', $dir . 'app.js', array('jquery'), '0.0.1', true);

}
add_action('after_setup_theme', 'add_front_theme');