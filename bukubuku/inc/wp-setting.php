<?php

/******************************************************
 *
 * 管理バーを非表示にする
 *
 *******************************************************/
function my_function_admin_bar()
{
	return false;
}
add_filter('show_admin_bar', 'my_function_admin_bar');


/******************************************************
 *
 * 特定のテーマ機能をサポートする
 *
 *******************************************************/
function original_theme_setup()
{
	// コメントフォーム、検索フォーム等をHTML5のマークアップに
	add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
	// タイトルタグ追加
	add_theme_support('title-tag');
	// 投稿キャプチャー画像を追加。
	add_theme_support('post-thumbnails');
	add_image_size('gallery', 290, 200, true);
	add_image_size('collection', 460, 99999);
	add_image_size('collection-thumb', 208, 99999);
	add_image_size('blog-thumb', 81, 81, true);
}
add_action('after_setup_theme', 'original_theme_setup');

/******************************************************
 *
 * 固定ページの画像パスを相対パスへ
 *
 *******************************************************/
function replaceImagePath($arg)
{


	$dir = get_template_directory_uri();
	$search = array(
		'"src/img/',
	);
	$replace = array(
		'"' . $dir . '/src/img/',
	);
	$content = str_replace($search, $replace, $arg);
	return $content;
}
add_action('the_content', 'replaceImagePath');
add_filter('the_editor_content', 'replaceImagePath', 10, 2);


/******************************************************
 *
 * 固定ページのみ自動整形機能を無効化します。
 *
 *******************************************************/
function disable_page_wpautop()
{
	if (is_page()) remove_filter('the_content', 'wpautop');
}
//add_action( 'wp', 'disable_page_wpautop' );


/******************************************************
 *
 * 本文からの抜粋機能
 *
 *******************************************************/
// 抜粋字数を指定する
function custom_excerpt_length($length)
{

	if (is_home() || is_front_page()) {
		$return = 45;
	} else {
		$return = 150;
	}
	return $return;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// 本文からの抜粋末尾の文字列を指定する
function custom_excerpt_more($more)
{
	return '...';
}
add_filter('excerpt_more', 'custom_excerpt_more');

/******************************************************
 *
 * wordpressの不要な記述を削除
 *
 *******************************************************/
function disable_emoji()
{
	// 特殊記号　画像変換を停止
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_head', 'rest_output_link_wp_head');
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	// 短縮URLの表示を停止
	remove_action('wp_head', 'wp_shortlink_wp_head');
	// 前後の記事URLの表示を停止
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	// Windows Live Writer とのリンクを停止
	remove_action('wp_head', 'wlwmanifest_link');
	// RSDのリンクを停止
	remove_action('wp_head', 'rsd_link');
	// DNSプリフェッチのリンクを停止
	remove_action('wp_head', 'wp_resource_hints', 2);
}
add_action('init', 'disable_emoji');

/******************************************************
 *
 * 投稿画面のカテゴリの順番を変えない
 *
 *******************************************************/
function my_wp_terms_checklist_args($args, $post_id)
{
	$args['checked_ontop'] = false;
	return $args;
}
add_filter('wp_terms_checklist_args', 'my_wp_terms_checklist_args', 10, 2);


/******************************************************
 *
 * クエリ書き換え
 *
 *******************************************************/
function my_default_query($query)
{
	if (is_admin() || !$query->is_main_query()) {
		return;
	}
	if (is_post_type_archive('catalog')) {
		$query->set('posts_per_page', 15);
	}
}
add_action('pre_get_posts', 'my_default_query', 1);


/******************************************************
 *
 * 自動保存機能無効
 *
 *******************************************************/
function disable_autosave()
{
	wp_deregister_script('autosave');
}
add_action('wp_print_scripts', 'disable_autosave');
if (!defined('WP_POST_REVISIONS')) {
	define('WP_POST_REVISIONS', false);
}


/******************************************************
 *
 * セキュリティ関連
 *
 *******************************************************/
// WordPressバージョン情報を消す
function remove_wp_version()
{
	remove_action('wp_head', 'wp_generator');
}
add_action('init', 'remove_wp_version');

// スタイル・スクリプトのバージョン情報を消す
function remove_src_ver($src)
{
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
//add_filter( 'style_loader_src', 'remove_src_ver', 9999 );
//add_filter( 'script_loader_src', 'remove_src_ver', 9999 );

// 不要なページを無効化
function remove_page_view($query)
{
	if (!is_admin()) {
		if (is_author() || is_attachment() ) {
			$query->set_404();
			status_header(404);
			nocache_headers();
		}
	}
}
add_filter('parse_query', 'remove_page_view');

/******************************************************
 *
 * テキストエディタカスタマイズ
 *
 *******************************************************/
// テキストエディタにスタイルを指定
/*add_editor_style( get_template_directory_uri().'/src/css/editor.css' );

// テキストエディタにフォントサイズ変更ボタン追加
function editor_add_buttons($array) {
	array_push($array, 'fontsizeselect');
	array_push($array, 'styleselect');
	return $array;
}
add_filter('mce_buttons', 'editor_add_buttons');

function custom_editor_settings( $initArray ) {
		$class = array(
			array(
				'title' => 'フレックス',
				'block' => 'div',
				'classes' => 'flex'
			)
		);
		$initArray['body_class'] = 'editor';
		$initArray['wpautop'] = false;
		$initArray['indent'] = true;
		$initArray['fontsize_formats'] = '10px 12px 14px 16px 18px 24px 36px';
		//$initArray['style_formats'] = json_encode($class);
		$initArray['valid_elements'] = '*[*]';
		$initArray['extended_valid_elements'] = '*[*]';
		return $initArray;
}
add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );

/******************************************************
*
* ウィジェット
*
*******************************************************/
function custom_widgets_init()
{
	register_sidebar(array(
		'name'          => 'Main Widget Area',
		'id'            => 'sidebar-1',
		'description'   => 'サイドバーに表示されます',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
}
add_action('widgets_init', 'custom_widgets_init');

/******************************************************
 *
 * その他
 *
 *******************************************************/
// 管理画面の表示設定にセクションとフィールドを追加
function eg_settings_api_init()
{

	// セクションを追加
	add_settings_section('eg_setting_section', '', '', 'reading');

	// その新しいセクションの中に
	// 新しい設定の名前と関数を指定しながらフィールドを追加
	add_settings_field(
		'download_btn',
		'アプリ一覧ページのダウンロードボタン',
		'eg_setting_callback_function',
		'reading',
		'eg_setting_section'
	);
	// 新しい設定が $_POST で扱われ、コールバック関数が <input> を
	// echo できるように、新しい設定を登録
	register_setting('reading', 'download_btn');
}
function eg_setting_callback_function()
{
	echo '<label><input name="download_btn" id="download_btn" type="checkbox" value="1"' . checked(1, get_option('download_btn'), false) . ' /> 表示しない</label>';
}
//add_action( 'admin_init', 'eg_settings_api_init' );



//ページ表示前
function read_page_variable()
{
	ob_start(function ($buffer) {
		// スタイル・スクリプトの不要な記述を消す
		$buffer = str_replace(array(' type="text/javascript"', " type='text/javascript'"), '', $buffer);
		$buffer = str_replace(array(' type="text/css"', " type='text/css'"), '', $buffer);
		// 画像の参照をURL参照に変更
		$buffer = str_replace(array('src="src/', 'src="/src/'), 'src="' . get_template_directory_uri() . '/src/', $buffer);
		$buffer = str_replace(array('href="src/', 'href="/src/'), 'href="' . get_template_directory_uri() . '/src/', $buffer);
		return $buffer;
	});
}
add_action('template_redirect', 'read_page_variable');

//日本語を英数字に
function auto_post_slug($slug, $post_ID, $post_status, $post_type)
{
	if (preg_match('/(%[0-9a-f]{2})+/', $slug)) {
		$slug = utf8_uri_encode($post_type) . '-' . $post_ID;
	}
	return $slug;
}
add_filter('wp_unique_post_slug', 'auto_post_slug', 10, 4);

// 画像の切り抜き位置指定
function custom_image_resize_dimensions($payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop)
{
	if (false) return $payload;
	if ($crop) {
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = min($dest_w, $orig_w);
		$new_h = min($dest_h, $orig_h);

		if (!$new_w) $new_w = intval($new_h * $aspect_ratio);
		if (!$new_h) $new_h = intval($new_w / $aspect_ratio);

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = ($orig_w - $crop_w) / 2;
		$s_y = ($orig_h - $crop_h) / 4;
	} else {
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = ($orig_w - $crop_w) / 2;
		$s_y = ($orig_h - $crop_h) / 4;

		list($new_w, $new_h) = wp_constrain_dimensions($orig_w, $orig_h, $dest_w, $dest_h);
	}
	if ($new_w >= $orig_w && $new_h >= $orig_h) return false;
	return array(0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h);
}
add_filter('image_resize_dimensions', 'custom_image_resize_dimensions', 10, 6);

// bodyにCSSクラスを追加
function custom_class_names($classes)
{
	if (is_page()) {
		global $post;
		if (preg_match("/^[a-zA-Z0-9_-]+$/", $post->post_name)) {
			$classes[] = $post->post_name;
		}
	}
	return $classes;
}
add_filter('body_class', 'custom_class_names');
