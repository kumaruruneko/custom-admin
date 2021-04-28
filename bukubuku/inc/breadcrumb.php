<?php
//パンくずリスト
function breadcrumb()
{

	global $post;
	$str = '';

	if (!is_home() && !is_front_page() && !is_page(array('application', 'maintenance')) && !is_admin()) { /* !is_admin は管理ページ以外という条件分岐 */

		$str .= '<div class="breadcrumb container">';
		$str .= '<ul class="d-flex flex-wrap">';
		$str .= '<li><a href="' . home_url() . '/">HOME</a></li>';

		if (is_search()) {
			if($_GET['category']){
				$str .= '<li><a href="'.home_url().'/media/">シーシャメディア</a></li>';
				$str .= '<li>「' . get_term_by( 'slug' , $_GET['category'] , 'media-term')->name . '」で検索した結果</li>';
			}elseif($_GET['t']){
				$str .= '<li><a href="'.home_url().'/media/">シーシャメディア</a></li>';
				$str .= '<li>「' . $_GET['t'] . '」で検索した結果</li>';
			}elseif($_GET['area']){
				$str .= '<li>「' . $_GET['area'] . '」の店舗一覧</li>';
			}elseif($_GET['features']){
				$str .= '<li>「' . $_GET['features'] . '」の店舗一覧</li>';
			}elseif( isset($_GET['post_type']) && $_GET['post_type'] == 'store-list'){
				$str .= '<li>店舗一覧</li>';
			}elseif( isset($_GET['post_type']) && $_GET['post_type'] == 'media-list'){
				$str .= '<li>検索結果</li>';
			}elseif(get_search_query()){
				$str .= '<li>「' . get_search_query() . '」で検索した結果</li>';
			}
		} elseif (is_tag()) {
			
			$str .= '<li>タグ : ' . single_tag_title('', false) . '</li>';
		} elseif (is_404()) {

			$str .= '<li>404 Not found</li>';
		} elseif (is_date()) {

			if (get_query_var('day') != 0) {

				$str .= '<li><a href="' . get_year_link(get_query_var('year')) . '">' . get_query_var('year') . '年</a></li>';
				$str .= '<li><a href="' . get_month_link(get_query_var('year'), get_query_var('monthnum')) . '">' . get_query_var('monthnum') . '月</a></li>';
				$str .= '<li>' . get_query_var('day') . '日</li>';
			} elseif (get_query_var('monthnum') != 0) {

				$str .= '<li><a href="' . get_year_link(get_query_var('year')) . '">' . get_query_var('year') . '年</a></li>';
				$str .= '<li>' . get_query_var('monthnum') . '月</li>';
			} else {

				$str .= '<li>' . get_query_var('year') . '年</li>';
			}
		} elseif (is_category()) {

			$cat = get_queried_object();
			$cat_id = $cat->term_id;
			if ($cat->parent != 0) {
				$ancestors = array_reverse(get_ancestors($cat->term_id, 'category'));
				$cat_id = $ancestors[0];
			}
			$str .= '<li>' . get_cat_name($cat_id) . '</li>';
		} elseif (is_author()) {

			$str .= '<li>投稿者 : ' . get_the_author_meta('display_name', get_query_var('author')) . '</li>';
		} elseif (is_page('movie')) {

			$str .= '<li><a href="'.home_url().'/media/">シーシャメディア</li>';
			$str .= '<li>' . $post->post_title . '</li>';
		} elseif (is_page()) {

			if ($post->post_parent != 0) {
				$ancestors = array_reverse(get_post_ancestors($post->ID));
				foreach ($ancestors as $ancestor) {
					$str .= '<li><a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
				}
			}
			$str .= '<li>' . $post->post_title . '</li>';
		} elseif (is_attachment()) {

			if ($post->post_parent != 0) {
				$str .= '<li><a href="' . get_permalink($post->post_parent) . '">' . get_the_title($post->post_parent) . '</a></li>';
			}
			$str .= '<li>' . $post->post_title . '</li>';
		} elseif (is_single()) {

			if (is_singular('post')) {

				$categories = get_the_category($post->ID);

				if (!empty($categories)) {

					$cat = $categories[0];
					
					if ($cat->parent != 0) {
						$ancestors = (is_singular('post')) ? array_reverse(get_ancestors($cat->cat_ID, 'category')) : array_reverse(get_ancestors($cat->term_id, $term));
						/*foreach($ancestors as $ancestor){
											$str.='<li><a href="'. get_term_link($ancestor,$cat->taxonomy).'">'. get_cat_name($ancestor). '</a></li>';
									}*/
						$cat = get_category($ancestors[0], false);
					}
					$str .= '<li><a href="' . home() . 'news">' . $cat->name . '</a></li>';
				}
			} else {
				$obj = get_post_type_object(get_post_type());
				if($obj->name == 'store-list'){
					$breadcrumb_slug = '?s=&post_type=store-list';
					$cats = get_the_terms($post->ID, 'pref-term');
					$parent_term = get_term($cats[0]->parent , 'pref-term');
					$post_single_link = home_url('/'). $obj->name;
						$str .= '<li><a href="' . $post_single_link . '">' . '店舗一覧' . '</a></li>';
					if ($cats[0]->parent != 0) {
						$str .= '<li><a href="' . $post_single_link . '/area/' . $parent_term->slug . '/">' . $parent_term->name . '</a></li>';
						$str .= '<li><a href="' . $post_single_link . '/area/' . $parent_term->slug . '/' . $cats[0]->slug . '/">' . $cats[0]->name . '</a></li>';
					} else {
						$str .= '<li><a href="' . $post_single_link . '/' . $cats[0]->slug . '/">' . $cats[0]->name . '</a></li>';
					}
				}elseif($obj->name == 'media-list'){
					$breadcrumb_slug = 'media';
					$cat = get_queried_object();
					$cats = get_the_terms($post->ID,'media-term');
					$str .= '<li><a href="' . home() . $breadcrumb_slug . '">' . $obj->label . '</a></li>';
					$str .= '<li><a href="' . home() . 'media-list/' . '">' . '記事一覧' . '</a></li>';
					$str .= '<li><a href="' . home() . 'media-list/category/' . $cats[0]->slug .'">' . $cats[0]->name . '</a></li>';
				
				}else{
					$cat = get_queried_object();
					$cats = get_the_terms($post->ID, 'news-term');

				$news_cat = $cats[0]->slug;


					$breadcrumb_slug = 'news';
					$str .= '<li><a href="' . home() . $breadcrumb_slug . '">' . $obj->label . '</a></li>';
					if ($news_cat) {
						$str .= '<li><a href="' . home() . 'news-list' . '/type/' . $news_cat . '">' . $cats[0]->name . '</a></li>';
					}else{
						$str .= '<li><a href="' . home() . 'news-list">' . '未分類' . '</a></li>';
					}
				
				}
				
			}
			$str .= '<li>' . $post->post_title . '</li>';
		} elseif (is_tax()) {

			global $wp_query;
			$taxonomy = get_taxonomy(get_query_var('taxonomy'));
			$post_type = $taxonomy->object_type[0];
			$cat = get_queried_object();
			if (empty($cat->term_id)) {
				$cat_name = $cat->label;
				$str .= '<li>' . $cat_name . '</li>';
			} else {
				$cat_name = $cat->name;
				$obj = get_post_type_object($post_type);
				if($obj->label == '店舗情報'){
					$obj_label = '店舗一覧';
				}else{
					$obj_label = $obj->label;
				}
				
				if($post_type == 'media-list'){
					$post_archive_link = home_url() . '/media/';
					$post_archive_list_link = home_url() . '/media-list/';
					
					$str .= '<li><a href="' . $post_archive_link . '">' . $obj_label . '</a></li>';
					$str .= '<li><a href="' . $post_archive_list_link . '">' . '記事一覧' . '</a></li>';
				}else{
					$post_archive_link = get_post_type_archive_link($post_type);
					$str .= '<li><a href="' . $post_archive_link . '">' . $obj_label . '</a></li>';
				}
				
				if ($cat->parent != 0) {
					$ancestors = array_reverse(get_ancestors($cat->term_id, $cat->taxonomy));
					$parent_term = get_term($cat->parent);
					foreach ($ancestors as $ancestor) {
						
						if (get_cat_name($ancestor) !== $obj->label) {
							$str .= '<li><a href="' . get_term_link($ancestor, $cat->taxonomy) . '">' . $parent_term->name . '</a></li>';
						}
					}
				}
				$str .= '<li>' . $cat_name . '</li>';
			}
		} elseif (is_archive()) {

			$cat = get_queried_object();
			$tags = get_the_tags();
			if (empty($cat->term_id)) {
				$tag_post = get_query_var('post_tag');
			
				if($tag_post){
					$tag_slug = basename($tag_post);
					$tag_data = get_tags(array('slug' => $tag_post));
					$cat_name = $tag_data[0]->name;
					$str .= '<li><a href="' . home_url() . '/media/">シーシャメディア</a></li>';
					$str .= '<li><a href="' . home() . 'media-list/' . '">' . '記事一覧' . '</a></li>';
					$str .= '<li>' . $cat_name . '</li>';
			
				}else{
					if($cat->name == 'media-list'){
						$str .= '<li><a href="' . home_url() . '/media/">シーシャメディア</a></li>';
						$str .= '<li>' . '記事一覧' . '</li>';
					}else{
						if ($cat->name == 'news-list'){
							$str .= '<li>' . 'NEWS' . '</li>';
						}else{
							$str .= '<li>' . '店舗一覧' . '</li>';
						}
						
					}
					
				}
			} else {
				$cat_name = $cat->name;
				$obj = get_post_type_object($post->post_type);
				$str .= '<li><a href="' . get_post_type_archive_link($post->post_type) . '">' . $obj->label . '</a></li>';
				
				if ($cat->parent != 0) {
					$ancestors = array_reverse(get_ancestors($cat->term_id, $cat->taxonomy));
					foreach ($ancestors as $ancestor) {
					
						if (get_cat_name($ancestor) !== $obj->label) {
							$str .= '<li><a href="' . get_term_link($ancestor, $cat->taxonomy) . '">' . get_cat_name($ancestor) . '</a></li>';
						}
					}
				}
				$str .= '<li>' . $cat_name . '</li>';
			}
		} else {

			$str .= '<li>' . wp_title('', false) . '</li>';
		}

		$str .= '</ul></div>';
	}

	echo $str;
}