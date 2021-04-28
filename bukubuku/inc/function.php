<?php

/******************************************************
 *
 * 独自関数
 *
 *******************************************************/
// デバッグ用
function pr($data)
{
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
}

// デバイス判定
function is_iphone()
{
	$is_iphone = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone');
	if ($is_iphone) {
		return true;
	} else {
		return false;
	}
}
function is_android()
{
	$is_android = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'Android');
	if ($is_android) {
		return true;
	} else {
		return false;
	}
}
function is_ipad()
{
	$is_ipad = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad');
	if ($is_ipad) {
		return true;
	} else {
		return false;
	}
}
function is_kindle()
{
	$is_kindle = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle');
	if ($is_kindle) {
		return true;
	} else {
		return false;
	}
}
function is_mobile()
{
	$useragents = array(
		'iPhone',          // iPhone
		'iPod',            // iPod touch
		'Android',         // 1.5+ Android
		'dream',           // Pre 1.5 Android
		'CUPCAKE',         // 1.5+ Android
		'blackberry9500',  // Storm
		'blackberry9530',  // Storm
		'blackberry9520',  // Storm v2
		'blackberry9550',  // Storm v2
		'blackberry9800',  // Torch
		'webOS',           // Palm Pre Experimental
		'incognito',       // Other iPhone browser
		'webmate'          // Other iPhone browser
	);
	$pattern = '/' . implode('|', $useragents) . '/i';
	return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}
function isBot()
{
	$bot_list = array(
		'Googlebot',
		'Yahoo! Slurp',
		'Mediapartners-Google',
		'msnbot',
		'bingbot',
		'MJ12bot',
		'Ezooms',
		'pirst; MSIE 8.0;',
		'Google Web Preview',
		'ia_archiver',
		'Sogou web spider',
		'Googlebot-Mobile',
		'AhrefsBot',
		'YandexBot',
		'Purebot',
		'Baiduspider',
		'UnwindFetchor',
		'TweetmemeBot',
		'MetaURI',
		'PaperLiBot',
		'Showyoubot',
		'JS-Kit',
		'PostRank',
		'Crowsnest',
		'PycURL',
		'bitlybot',
		'Hatena',
		'facebookexternalhit',
		'NINJA bot',
		'YahooCacheSystem',
	);
	$is_bot = false;
	foreach ($bot_list as $bot) {
		if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
			$is_bot = true;
			break;
		}
	}
	return $is_bot;
}


/******************************************************
 *
 * 画像までのパス
 *
 *******************************************************/
function img_dir()
{
	return get_template_directory_uri() . '/src/img/';
}


/******************************************************
 *
 * テーマまでの絶対パス
 *
 *******************************************************/
function theme_dir()
{
	return get_template_directory() . '/';
}


/******************************************************
 *
 * トップのurl
 *
 *******************************************************/
function home()
{
	return esc_url(home_url('/'));
}

/******************************************************
 *
 * トップのurl
 *
 *******************************************************/
add_filter('admin_body_class', 'add_admin_body_class');

function add_admin_body_class($classes)
{
	$classes .= img_dir();
	return $classes;
}


//カテゴリー　リンクを表示
function ahrcive_link($slug)
{
	if (empty($slug)) {
		return;
	}
	$category_id = get_category_by_slug($slug);
	$category_link = get_category_link($category_id->cat_ID);
	echo esc_url($category_link);
}

//親子関係のページ判定
function is_tree($slug)
{
	global $post;
	if (empty($slug)) {
		return;
	}
	$postlist = get_posts(array('posts_per_page' => 1, 'name' => $slug, 'post_type' => 'page'));
	$pageid = array();
	foreach ($postlist as $list) {
		$pageid[] = $list->ID;
	}
	if (is_page($slug)) return true;
	$anc = get_post_ancestors($post->ID);
	foreach ($anc as $ancestor) {
		if (is_page() && in_array($ancestor, $pageid)) {
			return true;
		}
	}
	return false;
}

//固定ページで親をもっているか判定
function is_subpage()
{
	global $post;
	if (is_page() && $post->post_parent) {
		$parentID = $post->post_parent;
		return $parentID;
	} else {
		return false;
	};
};

// 多次元配列の値が空かどうか
function custom_array_filter($var)
{
	$return = false;
	if (is_array($var)) {
		foreach ($var as $v) {
			if (!empty($v)) {
				$return = true;
			}
		}
	} elseif (!empty($var)) {
		$return = true;
	}
	return $return;
}

// mainタグのクラスを取得する
function get_main_class()
{
	if (is_home() || is_front_page()) {
		return 'top-main';
	} else {
		return 'sub-main';
	}
}

// 投稿の初めの画像を取得
function catch_that_image()
{
	global $post;
	$image_get = preg_match_all('/<img.+class=[\'"].*wp-image-([0-9]+).*[\'"].*>/i', $post->post_content, $matches);
	$image_id = $matches[1][0];
	if ($image_id) {
		$image = wp_get_attachment_image_src($image_id, 'blog-thumb');
		$first_img = $image[0];
	}

	if (empty($first_img)) {
		// 記事内で画像がなかったときのためのデフォルト画像を指定
		$first_img = "src/img/common/default.jpg";
	}
	return $first_img;
}

// 特定のページの編集画面をカスタマイズ
function my_slider_page_style()
{
	if (($post = get_post()) && ($post->ID === 9215)) {
		echo '<style>
		.page-title-action{display:none;}
		#post-body-content{display:none;}
		
		
		</style>'; // styles
	}
	if (($post = get_post()) && ($post->ID === 81)) {
		echo '<style>
		.edit-post-visual-editor{display:none;}
		.interface-interface-skeleton__content{background-color:#fff!important;}
		</style>'; // styles
	}
}

add_action('admin_enqueue_scripts', 'my_slider_page_style');

add_filter('use_block_editor_for_post', function ($use_block_editor, $post) {
	if ($post->post_type === 'page') {
		if (in_array($post->post_name, ['recruit'])) {
			remove_post_type_support('page', 'editor');
			return false;
		}
	}
	return $use_block_editor;
}, 10, 2);

function my_slider_page_script()
{
	if (($post = get_post()) && ($post->ID === 9215)) {
		echo '<script>
  $(".wp-heading-inline").text("スライダー画像を編集")
  </script>' . PHP_EOL;
	}
}
add_action('admin_print_footer_scripts', 'my_slider_page_script');


// お問い合わせフォームのバリデーション表示位置変更
function wpcf7_custom_item_error_position($items, $result)
{
	$class = 'wpcf7-custom-item-error';
	$names = array('privacy_policy');

	if (isset($items['invalidFields'])) {
		foreach ($items['invalidFields'] as $k => $v) {
			$orig = $v['into'];
			$name = substr($orig, strrpos($orig, ".") + 1);
			if (in_array($name, $names)) {
				$items['invalidFields'][$k]['into'] = ".{$class}.{$name}";
			}
		}
	}
	return $items;
}
add_filter('wpcf7_ajax_json_echo', 'wpcf7_custom_item_error_position', 10, 2);

wpcf7_add_shortcode('hurl', 'shortcode_hurl', true);
function shortcode_hurl()
{
	return home_url('/');
}

function ogp_setting(){
	global $post;
	if(is_single()){
		$type = 'article';
		$img = has_post_thumbnail() ? get_the_post_thumbnail_url() : get_template_directory_uri().'/src/img/common/og_img.jpg';
	}else{
		$type = 'website';
		$img = get_template_directory_uri().'/src/img/common/og_img.jpg';
	}
?>
<meta property="og:type" content="<?php echo $type;?>">
<meta property="og:url" content="<?php echo get_the_permalink();?>">
<meta property="og:image" content="<?php echo $img;?>">
<meta property="og:title" content="<?php echo get_the_title();?>">
<meta property="og:description" content="">
<meta property="og:locale" content="ja_JP">
<meta name="twitter:card" content="summary_large_image" />
<?php
}
// add_action('wp_head','ogp_setting');



/******************************************************
*
* キーワード検索にカテゴリ、タグ、メタを含める
*
*******************************************************/
add_filter('posts_search', function($search, $wp_query){
	if ( is_admin() || !is_search() ){ return $search; }
	if(empty(trim(mb_convert_kana(get_search_query(), 's'))) && !isset($_GET['t']) && !isset($_GET['category']) && !isset($_GET['area']) && !isset($_GET['features']) ){ return $search; }
	$search_words = explode(' ', str_replace('　', ' ', get_search_query()));
	$post_type = $_GET['post_type'] ? $_GET['post_type'] : 'store-list';
	if($_GET['s'] == ''){
		global $wpdb;
		$search = " AND post_type = '".$post_type."' ";
		if(isset($_GET['t'])){
			$search_word = '%' . $_GET['t'] . '%' ;
		}elseif(isset($_GET['category'])){
			$search_word =  '%' . get_term_by( 'slug' , $_GET['category'] , 'media-term')->name . '%' ;
		}elseif(isset($_GET['area'])){
			$tag    = get_term_by('slug', $_GET['area'], 'pref-term');
			$tag_id = $tag->term_id;
			$children = get_term_children($tag_id, 'pref-term');
			$search_word_list[] = $_GET['area'];
			if($children){
				foreach($children as $id){
					$search_word_list[] = get_term($id,'pref-term')->name;
				}
			}
		}elseif(isset($_GET['features'])){
			$search_word =  '%' . $_GET['features'] . '%' ;
		}
		if(isset($_GET['area'])){
			foreach ( $search_word_list as $word ) {
				$search_word = '%' . esc_sql( $word ) . '%';
				$term_query = "
					SELECT DISTINCT tr.object_id
					FROM {$wpdb->term_relationships} AS tr
					JOIN {$wpdb->term_taxonomy} AS tt
						ON tr.term_taxonomy_id = tt.term_taxonomy_id
					JOIN {$wpdb->terms} AS t
						ON tt.term_id = t.term_id
					WHERE t.name LIKE '{$search_word}'
					GROUP BY tr.object_id
				";
				if($word === end($search_word_list)){
					$search_list .= "{$wpdb->posts}.ID IN ($term_query)";
				}else{
					$search_list .= "{$wpdb->posts}.ID IN ($term_query) OR ";
				}
			}
			$search .= "AND ( $search_list ) ";
		}else{
			$term_query = "
				SELECT DISTINCT tr.object_id
				FROM {$wpdb->term_relationships} AS tr
				JOIN {$wpdb->term_taxonomy} AS tt
					ON tr.term_taxonomy_id = tt.term_taxonomy_id
				JOIN {$wpdb->terms} AS t
					ON tt.term_id = t.term_id
				WHERE t.name LIKE '{$search_word}'
				GROUP BY tr.object_id
			";
			$search .= "AND {$wpdb->posts}.ID IN ($term_query) ";
		}
	}elseif ( count($search_words) > 0 ) {
		global $wpdb;
		$search = " AND post_type = '".$post_type."' ";
		foreach ( $search_words as $word ) {
			$search_word = '%' . esc_sql( $word ) . '%';
			$meta_query = "
				SELECT DISTINCT post_id
				FROM {$wpdb->postmeta}
				WHERE meta_value LIKE '{$search_word}'
			";
			$term_query = "
				SELECT DISTINCT tr.object_id
				FROM {$wpdb->term_relationships} AS tr
				JOIN {$wpdb->term_taxonomy} AS tt
					ON tr.term_taxonomy_id = tt.term_taxonomy_id
				JOIN {$wpdb->terms} AS t
					ON tt.term_id = t.term_id
				WHERE t.name LIKE '{$search_word}'
				GROUP BY tr.object_id
			";
			$search .= "AND (
				{$wpdb->posts}.post_title LIKE '{$search_word}'
				OR {$wpdb->posts}.post_content LIKE '{$search_word}'
				OR {$wpdb->posts}.ID IN ($meta_query)
				OR {$wpdb->posts}.ID IN ($term_query)
			) ";
		}
	}
	return $search;
}, PHP_INT_MAX, 2);

	
//アクセス数を保存
function set_post_views($postID) {
	if(isBot()){return false;}
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}

function myPreGetPosts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}
	if ( $query->is_search() && $_GET['post_type'] == 'store-list' ||  $query->is_search() && !isset($_GET['post_type'])) {
		$query->set('posts_per_page', 5);
	}
	if ( $query->is_archive() && !empty(get_query_var('post_tag')) ) {
		$query->set('tag', get_query_var('post_tag'));
	}
	if ( $query->is_archive() ) {
		if( !empty(get_query_var('pref-term')) || !empty(get_query_var('store-term')) || get_query_var('post_type') == 'store-list' ){
			$query->set('posts_per_page', 5);
		}
	}
	if(isset($_GET['sort'])&&$_GET['sort'] == 'brows'){
		$query->set( 'meta_key', 'post_views_count' );
		$query->set('order', 'DESC');
		$query->set( 'orderby', 'meta_value' );
	}elseif(isset($_GET['sort'])&&$_GET['sort'] == 'desc'){
		$query->set('order', 'DESC');
		$query->set( 'orderby', 'title' );
	}elseif(isset($_GET['sort'])&&$_GET['sort'] == 'asc'){
		$query->set('order', 'ASC');
		$query->set( 'orderby', 'title' );
	}
 }
 add_action('pre_get_posts','myPreGetPosts');

 //検索ページのタイトルの書き換え
 function change_title_tag( $title ) {
  
	//条件分岐タグ等を使ってページにより $title を変更する処理
	if ( is_search() && $_GET['post_type'] == 'media-list' ) {
	  $title = 'メディア記事一覧';
	}elseif ( is_search() )  {
		$title = '店舗検索結果一覧';
	}
	
	return $title;
}
add_filter( 'pre_get_document_title', 'change_title_tag' );


//youtubeモーダル追加
function footer_action(){
?>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js?ver=5.7'></script>
<?php if(is_page('media') || is_page('movie') || is_single()):?>
<script src='<?php echo get_template_directory_uri();?>/src/js/modal-video.min.js'></script>
<script>
$(".js-modal-video").modalVideo({
	channel: 'youtube'
});
</script>
<?php endif;?>
<script src='https://www.youtube.com/iframe_api'></script>
<script src='<?php echo get_template_directory_uri();?>/src/js/lightbox.js'></script>
<script src='<?php echo get_template_directory_uri();?>/src/js/app.js'></script>
<?php
}

add_action('wp_footer', 'footer_action');

add_action('wp_print_scripts', function () {
	wp_deregister_script('autosave');
});

//本体の更新通知を非表示
add_filter("pre_site_transient_update_core", "__return_null");
//プラグインの更新通知を非表示
add_filter("pre_site_transient_update_plugins", "__return_null");
//テーマの更新通知を非表示
add_filter("pre_site_transient_update_themes", "__return_null");

function remove_menus() {
    remove_menu_page( 'edit.php' ); // 投稿.
}
add_action( 'admin_menu', 'remove_menus', 999 );


//店舗一覧の特徴を関数で作成

$feature_array = array(
	1 =>	'Wi-Fi使用可',
	2 => '30席以上あり',
	4 => '飲食持ち込み可',
	8 => '駐車場あり',
	16 => '電源あり',
	32 => '予約可',
	64 => '喫煙可',
	128 => '貸し切り可'
);

//フロント用
function features_list($post_id){
	global $feature_array;
	$_feature = get_post_meta($post_id, 'feature_check', false);
	if($_feature[0]){
		$feature_check_bit = array_sum($_feature[0]);
	}else{
		$feature_check_bit = 0;
	};
	$html = '<ul>';
	foreach($feature_array as $bit => $item){
		if ((int)$feature_check_bit & (int)$bit) {
			$class = 'active';
		 }else{
			$class = '';
		 }
		 $html .= '<li class="'.$class.'">'.$item.'</li>';
	}
	$html .= '</ul>';
	return $html;
}

//管理画面用
function features_admin_list($post_id){
	global $feature_array;
	$_feature = get_post_meta($post_id, 'feature_check', false);
	if($_feature[0]){
		$feature_check_bit = array_sum($_feature[0]);
	}else{
		$feature_check_bit = 0;
	};
	$html = '';
	foreach($feature_array as $bit => $item){
		if ((int)$feature_check_bit & (int)$bit) {
			$class = 'active';
			$checked = ' checked';
		 }else{
			$class = '';
			$checked = '';
		 }
		 $html .= '<label class="'.$class.'">';
		 $html .= '<input value="'.$bit.'" type="checkbox" name="feature_check[]"'.$checked.'>'.$item;
		 $html .= '</label>';
	}
	return $html;
}


//人気のタグ出力

$tag_arr = get_terms('post_tag');
if (is_array($tag_arr)){
	shuffle($tag_arr);
	$random_tags = array_chunk( $tag_arr , 15);
}
$make_tags = is_array($tag_arr) ? $random_tags[0] : $tag_arr;
function popular_tag($place){
	global $make_tags;
	$html = '<ul class="'.$place.'">';
	$i = 1;
	foreach((array)$make_tags as $tag){
		$html .= '<li><a href="' . home_url('/media-list/tag/') . $tag->slug . '"><span>#</span>' . $tag->name . '</a></li>';
		if( $place === 'footer' && $i % 5 === 0 && $tag !== end($make_tags)){
			$html .= '</ul><ul>';
		}
		$i++;
	}
	$html .= '</ul>';
	return $html;
}

function add_custom_widget1()
{
	echo '<ul class="custom_widget">
            <li><a href="admin.php?page=custom_menu_page"><div class="dashicons dashicons-admin-generic"></div><p>共通設定</p></a></li>
            <li><a href="admin.php?page=custom_submenu_page"><div class="dashicons dashicons-format-aside"></div><p>トップページ設定</p></a></li>
            <li><a href="admin.php?page=custompostsubmenu"><div class="dashicons dashicons-embed-photo"></div><p>メディアトップ設定</p></a></li>
          </ul>';
}
function add_custom_widget2()
{
	echo '<ul class="custom_widget">
            <li><a href="edit-tags.php?taxonomy=store-term&post_type=store-list"><div class="dashicons dashicons-post-status"></div><p>特徴カテゴリー<br>【店舗情報】</p></a></li>
            <li><a href="edit-tags.php?taxonomy=pref-term&post_type=store-list"><div class="dashicons dashicons-location-alt"></div><p>地域カテゴリー<br>【店舗情報】</p></a></li>
            <li><a href="edit-tags.php?taxonomy=post_tag&post_type=media-list"><div class="dashicons dashicons-edit-large"></div><p>人気のタグ<br>【シーシャメディア】</p></a></li>
            <li><a href="edit-tags.php?taxonomy=media-term&post_type=media-list"><div class="dashicons dashicons-edit-large"></div><p>記事カテゴリー<br>【シーシャメディア】</p></a></li>
          </ul>';
}
function add_custom_widget3()
{
	echo '<ul class="custom_widget">
            <li><a href="post-new.php?post_type=news-list"><div class="dashicons dashicons-welcome-write-blog"></div><p>NEWSを追加</p></a></li>
            <li><a href="post-new.php?post_type=store-list"><div class="dashicons dashicons-store"></div><p>新店を追加</p></a></li>
            <li><a href="post-new.php?post_type=media-list"><div class="dashicons dashicons-palmtree"></div><p>シーシャメディアの<br>記事を追加</p></a></li>
            <li><a href="post-new.php?post_type=movie-list"><div class="dashicons dashicons-playlist-video"></div><p>動画を追加</p></a></li>
            <li><a href="media-new.php"><div class="dashicons dashicons-admin-media"></div><p>メディアライブラリに追加</p></a></li>
          </ul>';
}
function add_my_widget()
{
	wp_add_dashboard_widget('custom_widget', '各種設定', 'add_custom_widget1');
	wp_add_dashboard_widget('custom_widget2', 'カテゴリー設定', 'add_custom_widget2');
	wp_add_dashboard_widget('custom_widget3', '新規投稿', 'add_custom_widget3');
}

add_action('wp_dashboard_setup', 'add_my_widget');


//no-imageの関数
function no_img($w,$h){
	$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/'.$w.'x'.$h.'.png?text=No%20Image';
	echo $no_img;
}