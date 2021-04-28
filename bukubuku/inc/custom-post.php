<?php

/***********************************************************
 * カスタム投稿
 ***********************************************************/
//カスタムフィールドを追加
add_action('admin_menu', 'add_mycustom_fields');
add_action('save_post', 'save_mycustom_fields');

//カスタム投稿タイプでカスタムフィールドを表示
function add_mycustom_fields()
{
	add_meta_box('my_contents', 'コンテンツ', 'my_custom_fields', array('case'));
	add_meta_box('my_guidelines', '募集要項', 'my_custom_fields', array('recruit'));
	add_meta_box('my_environment', '勤務環境', 'my_custom_fields', array('recruit'));
	add_meta_box('my_other', '備考', 'my_custom_fields', array('recruit'));
	add_meta_box('my_process', '応募選考', 'my_custom_fields', array('recruit'));
}

//カスタムフィールドの値をチェック
function save_mycustom_data($key, $post_id)
{
	if (isset($_POST["custom_data"][$key])) {
		$data = $_POST["custom_data"][$key];
	} else {
		$data = '';
	}

	//-1になると項目が変わったことになるので、項目を更新する
	if (empty($data)) {
		delete_post_meta($post_id, $key);
	} elseif (strcmp($data, get_post_meta($post_id, $key, true)) != 0) {
		update_post_meta($post_id, $key, $data);
	}
}

//カスタムフィールドの値を保存
function save_mycustom_fields($post_id)
{
	global $post;

	if (isset($_POST['custom_data']) && $_POST['custom_data_flg'] == 1) {
		foreach ($_POST['custom_data'] as $key => $value) {
			if (is_array($value)) {
				update_post_meta($post_id, $key, array_merge(array_filter($value, 'custom_array_filter')));
			} else {
				save_mycustom_data($key, $post_id);
			}
		}
	}
	$extend_edit_field_noncename = filter_input(INPUT_POST, 'extend_edit_field_noncename');
	if (isset($post->ID) && !wp_verify_nonce($extend_edit_field_noncename, plugin_basename(__FILE__))) {
		return $post->ID;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
}

// カテゴリが指定していなければデフォルトの値を指定
function save_app_cat($post_id)
{
	if (isset($_POST['custom_data']) && $_POST['custom_data_flg'] == 1) {
		if ($parent_id = wp_is_post_revision($post_id)) {
			$post_id = $parent_id;
		}

		$defaultcat = get_term_by('name', 'android', 'app_category');

		if (!has_term(array('android', 'iphone'), 'app_category', $post_id)) {
			remove_action('save_post_app', 'save_app_cat');
			wp_set_object_terms($post_id, $defaultcat->term_id, 'app_category');
			add_action('save_post_app', 'save_app_cat');
		}
	}
}



// カスタムフィールドの表示テンプレート指定
function my_custom_fields($post, $metabox)
{
	switch ($post->post_type) {
		case 'case':
			require_once(dirname(__DIR__) . '/admin/field/case.php');
			break;
	}
}

// 下書きのpost_IDを取得
function get_preview_id($post_id)
{
	global $post;
	$preview_id = 0;
	if (isset($post) && get_the_ID() == $post_id && is_preview() && $preview = wp_get_post_autosave($post->ID)) {
		$preview_id = $preview->ID;
	}
	return $preview_id;
}

// プレビュー時、meta情報をプレビュー用のmeta情報に置き換え
function get_preview_postmeta($return, $post_id, $meta_key, $single)
{
	if ($preview_id = get_preview_id($post_id)) {
		if ($post_id != $preview_id) {
			$meta = maybe_unserialize(get_post_meta($preview_id, $meta_key, $single));
			if (is_array($meta)) {
				$meta = array(array_filter($meta));
			}
			$return = $meta;
		}
	}
	return $return;
}
add_filter('get_post_metadata', 'get_preview_postmeta', 10, 4);

// カスタムフィールドテンプレートの送信形式による値を取得してプレビューのmeta情報を書き換え
function save_preview_postmeta($post_ID)
{
	global $wpdb;

	if (wp_is_post_revision($post_ID)) {
		if (!empty($_REQUEST['custom_data']) && is_array($_REQUEST['custom_data'])) {
			foreach ($_REQUEST['custom_data'] as $key => $val) {
				add_metadata('post', $post_ID, $key, maybe_serialize($val));
				//update_post_meta( $post_ID, $key, $val );
			}
		}

		do_action('save_preview_postmeta', $post_ID);
	}
}
add_action('wp_insert_post', 'save_preview_postmeta');

// 一覧にカラムを追加する
function staff_cpt_columns($columns)
{
	$new_columns = array();
	foreach ($columns as $key => $value) {
		$new_columns[$key] = $value;
		if ($key == 'title') {
			$new_columns['staff_store'] = '店舗';
		}
	}
	return $new_columns;
}
function cmp($a, $b)
{
	// "b"は必ず比較で負ける
	if ($a == "date") return 1;
	if ($b == "date") return -1;

	// 他は自然な処理
	if ($a == $b) {
		return 0;
	} else {
		return $a > $b ? 1 : -1;
	}
}
//add_filter('manage_staff_posts_columns' , 'staff_cpt_columns' );
// カスタムフィールドの内容を表示
function add_column($column_name, $post_id)
{
	if ($column_name == 'staff_store') {
		$stitle = get_post_meta($post_id, 'store', true);
		echo $stitle[0];
	}
}
//add_action( 'manage_staff_posts_custom_column', 'add_column', 10, 2 );

// カスタム投稿のパーマリンクをIDに変更
function custom_post_type_link($link, $post)
{
	$pref_terms = get_the_terms($post->ID, 'pref-term');
	$cats = get_the_terms($post->ID,'media-term');
	$news_cats = get_the_terms($post->ID,'news-term');
	if ($pref_terms[0]->parent !== 0) {
		$area = $pref_terms[0]->name;
		$parent_id = $pref_terms[0]->parent;
		$i = 0;
		while($i<99){
			$pref = get_term_by('term_id', $parent_id, 'pref-term');
			$area = $pref->name . '/' . $area;
			$parent_id = $pref->parent;
			if($parent_id == 0){
				break;
			}
		}
	} else {
		$area = $pref_terms[0]->name ;
	};
	if($cats){
		$cat = $cats[0]->slug;
	}else{
		$cat = 'none';
	}
	if($news_cats){
		$news_cat = $news_cats[0]->slug;
	}else{
		$news_cat = 'none';
	}
	if ($post->post_type === 'store-list' && $area) {
		return home_url('/store-list/' . $area . '/' . $post->post_name);
	} elseif ($post->post_type === 'media-list' && $cat) {
		return home_url('/media-list/' . $cat . '/' . $post->post_name);
	} elseif ($post->post_type === 'news-list' && $news_cat) {
		return home_url('/news-list/' . $news_cat . '/' . $post->post_name);
	} else {
		return $link;
	}
}
add_filter( 'post_type_link', 'custom_post_type_link',1 , 2 );
function custom_rewrite_rules_array($rules)
{
	// var_dump($rules);
	$new_rules = array(
		'news-list/type/(.+?)/page/([0-9]{1,})/?$' => 'index.php?news-term=$matches[1]&paged=$matches[2]',
		'news-list/type/(.+?)/?$' => 'index.php?news-term=$matches[1]',
		'news-list/page/([0-9]{1,})/?$' => 'index.php?post_type=news-list&paged=$matches[1]',
		'news-list/(.+?)/([^/]+)/?$' => 'index.php?news-term=$matches[1]&news-list=$matches[2]',
		'media-list/tag/(.+?)/page/([0-9]{1,})/?$' => 'index.php?post_type=media-list&post_tag=$matches[1]&paged=$matches[2]',
		'media-list/tag/(.+?)/?$' => 'index.php?post_type=media-list&post_tag=$matches[1]',
		'media-list/category/(.+?)/page/([0-9]{1,})/?$' => 'index.php?media-term=$matches[1]&paged=$matches[2]',
		'media-list/category/(.+?)/?$' => 'index.php?media-term=$matches[1]',
		'media-list/(.+?)/([^/]+)/?$' => 'index.php?media-term=$matches[1]&media-list=$matches[2]',
		'store-list/page/([0-9]{1,})/?$' => 'index.php?post_type=store-list&paged=$matches[1]',
		'store-list/feature/(.+?)/page/([0-9]{1,})/?$' => 'index.php?store-term=$matches[1]&paged=$matches[2]',
		'store-list/feature/(.+?)/?$' => 'index.php?store-term=$matches[1]',
		'store-list/area/(.+?)/page/([0-9]{1,})/?$' => 'index.php?pref-term=$matches[1]&paged=$matches[2]',
		'store-list/area/(.+?)/?$' => 'index.php?pref-term=$matches[1]',
		'store-list/(.+?)/([^/]+)/?$' => 'index.php?pref-term=$matches[1]&store-list=$matches[2]',
	);

	return $new_rules + $rules;
}
add_filter( 'rewrite_rules_array', 'custom_rewrite_rules_array' );

add_action('init', 'create_post_type');
function create_post_type()
{
	register_post_type('news-list', [ // 投稿タイプ名の定義
		'labels' => [
			'name'          => 'NEWS', // 管理画面上で表示する投稿タイプ名
			'singular_name' => 'news-list',    // カスタム投稿の識別名

		],
		'public'        => true,  // 投稿タイプをpublicにするか
		'has_archive'   => true, // アーカイブ機能ON/OFF
		'menu_position' => 5,     // 管理画面上での配置場所
		'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
		'supports' => array('title', 'editor', 'thumbnail'),
	]);
	register_post_type('store-list', [ // 投稿タイプ名の定義
		'labels' => [
			'name'          => '店舗情報', // 管理画面上で表示する投稿タイプ名
			'singular_name' => 'store',    // カスタム投稿の識別名
			'add_new'       => '新店追加',
			'add_new_item'  => '新しい店舗を設定',
		],
		'public'        => true,  // 投稿タイプをpublicにするか
		'has_archive'   => true, // アーカイブ機能ON/OFF
		'menu_position' => 5,     // 管理画面上での配置場所
		'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
		'supports' => array('title', 'editor', 'thumbnail'),
	]);
	register_post_type('media-list', [ // 投稿タイプ名の定義
		'labels' => [
			'name'          => 'シーシャメディア', // 管理画面上で表示する投稿タイプ名
			'singular_name' => 'media',    // カスタム投稿の識別名
			'add_new'       => '記事を追加',
			'add_new_item'  => '新しい記事を追加',
		],
		'public'        => true,  // 投稿タイプをpublicにするか
		'has_archive'   => true, // アーカイブ機能ON/OFF
		'menu_position' => 5,     // 管理画面上での配置場所
		'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
		'supports' => array('title', 'editor', 'thumbnail'),
	]);
	register_post_type('movie-list', [ // 投稿タイプ名の定義
		'labels' => [
			'name'          => '動画リスト', // 管理画面上で表示する投稿タイプ名
			'singular_name' => 'movie',    // カスタム投稿の識別名
			'add_new'       => '動画を追加',
			'add_new_item'  => '新しい動画を追加',
		],
		'public'        => true,  // 投稿タイプをpublicにするか
		'has_archive'   => true, // アーカイブ機能ON/OFF
		'menu_position' => 5,     // 管理画面上での配置場所
		'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
		'supports' => array('title'),
	]);
}
function add_taxonomy()
{
	register_taxonomy(
		'news-term',
		'news-list',
		array(
			'label' => 'NEWSカテゴリー',
			'singular_label' => 'NEWSカテゴリー',
			'labels' => array(
				'all_items' => 'NEWSカテゴリー一覧',
				'add_new_item' => 'NEWSカテゴリーを追加'
			),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'hierarchical' => true
		)
	);
	register_taxonomy(
		'store-term',
		'store-list',
		array(
			'label' => '特徴カテゴリー',
			'singular_label' => '特徴カテゴリー',
			'labels' => array(
				'all_items' => '特徴カテゴリー一覧',
				'add_new_item' => '特徴カテゴリーを追加'
			),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'store-list/feature',
				'hierarchical' => true,
			)
		)
	);
	register_taxonomy(
		'pref-term',
		'store-list',
		array(
			'label' => '地域カテゴリー',
			'singular_label' => '地域カテゴリー',
			'labels' => array(
				'all_items' => '地域カテゴリー一覧',
				'add_new_item' => '地域カテゴリーを追加'
			),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'store-list/area',
				'hierarchical' => true,
			)
		)
	);
	register_taxonomy(
		'media-term',
		'media-list',
		array(
			'label' => '記事カテゴリー',
			'singular_label' => '記事カテゴリー',
			'labels' => array(
				'all_items' => '記事カテゴリー一覧',
				'add_new_item' => '記事カテゴリーを追加'
			),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'media-list/category',
				'hierarchical' => true,
			)
		)
	);
	register_taxonomy(
		'post_tag', 
		'media-list',
		array(
			'label' => '人気のタグ',
			'singular_label' => '人気のタグ',
			'labels' => array(
				'all_items' => '人気のタグ一覧',
				'add_new_item' => '人気のタグを追加'
			),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'media-list/tag',
				'hierarchical' => true,
			)
		)
	);
}
add_action('init', 'add_taxonomy');


add_action("store-term_add_form_fields", function ($tag) {
	require_once(dirname(__DIR__) . '/admin/field/store-term.php');
});
add_action("store-term_edit_form", function ($tag) {

	require_once(dirname(__DIR__) . '/admin/field/store-term-edit.php');
});
add_action("media-term_add_form_fields", function ($tag) {
	require_once(dirname(__DIR__) . '/admin/field/media-term.php');
});
add_action("media-term_edit_form", function ($tag) {

	require_once(dirname(__DIR__) . '/admin/field/media-term-edit.php');
});



function save_terms($term_id)
{
	if (array_key_exists('capture', $_POST)) {
		update_term_meta($term_id, 'capture', $_POST['capture']);
	}
}
add_action('create_term', 'save_terms');  //新規追加用フック
add_action('edit_terms', 'save_terms');   //編集ページ用フック