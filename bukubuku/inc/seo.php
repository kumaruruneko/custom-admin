<?php

/******************************************************
 *
 * SEO関連
 *
 *******************************************************/
//hreflang設置
function hreflang_tag()
{
	$page_url_get = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$search_url = array('index.php');
	$page_ok_url = str_replace($search_url, '', $page_url_get);
	$aiosp = get_option('aioseop_options'); // All in One SEO Pack 設定

	echo '<link rel="alternate" hreflang="ja-jp" href="' . $page_ok_url . '">' . "\n";
	echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' RSS Feed" href="' . get_bloginfo('rss2_url') . '">' . "\n";
	echo '<link rel="pingback" href="' . get_bloginfo('pingback_url') . '">' . "\n";

	if (!$aiosp['aiosp_can']) {
		echo '<link rel="canonical" href="' . $page_ok_url . '">' . "\n";
	}
}
add_action('wp_head', 'hreflang_tag', 5);


//title 設定
function change_title($return_value)
{
	if (is_home() || is_front_page()) {
		unset($return_value['tagline']);
	}
	return $return_value;
}
// add_filter('document_title_parts', 'change_title', 10, 1);


//meta 設定
function description_tag()
{
	global $post;

	$rel = "\n";

	$aioseop_options = get_option('aioseop_options');
	$description = get_bloginfo('description');

	if (is_home() || is_front_page()) {

		if (empty($aioseop_options['aiosp_home_description'])) {
			$rel .= '<meta name="description" content="' . $description . '">' . "\n";
		}
	} else {

		$as_d = (!empty($post->ID) && !empty(get_post_meta($post->ID, '_aioseop_description', true))) ? get_post_meta($post->ID, '_aioseop_description', true) : '';

		if (empty($as_d) && empty($post->post_excerpt)) {
			$rel .= '<meta name="description" content="' . $description . '">' . "\n";
		}
	}

	echo $rel;
}
// add_action('wp_head', 'description_tag');

// 構造化データマークアップ
function json_ld_markup()
{
	$page_url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$search_url = array('index.php');
	$page_url = str_replace($search_url, '', $page_url);

	$business = array(
		"@context" => "http://schema.org",
		"@type" => "LocalBusiness",
		"name" => "サンプル",
		"image" => get_template_directory_uri() . "/src/img/common/logo.png",
		"address" => array(
			"@type" => "PostalAddress",
			"streetAddress" => "サンプル",
			"addressLocality" => "サンプル",
			"addressRegion" => "サンプル",
			"postalCode" => "000-0000",
			"addressCountry" => "JP"
		),
		"telephone" => "0000-00-0000"
	);

	$site = array(
		"@context" => "http://schema.org",
		"@type" => "WebSite",
		"name" => get_bloginfo('name'),
		"url" => esc_url(home_url('/'))
	);

	$breadcrumb = breadcrumb_ld();


	echo '<script type="application/ld+json">' . json_encode($business) . '</script>';
	echo '<script type="application/ld+json">' . json_encode($site) . '</script>';

	if (!empty($breadcrumb)) {
		echo '<script type="application/ld+json">' . json_encode($breadcrumb) . '</script>';
	}
}
//add_action( 'wp_footer', 'json_ld_markup', 100 );

// JSON-LD パンくず
function breadcrumb_ld()
{
	global $post;
	$i = 1;
	$permalink = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	if (is_admin() || is_home() || is_front_page()) {
		return;
	}
	$str = array(
		"@context" => "http://schema.org",
		"@type" => "BreadcrumbList",
		"itemListElement" => array()
	);

	// トップページ
	$str['itemListElement'][] = array(
		"@type" => "ListItem",
		"position" => $i,
		"item" => array(
			"@id" => esc_url(home_url('/')),
			"name" => "TOP"
		)
	);
	$i++;

	// 検索結果
	if (is_search()) {

		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => get_search_query() . "の検索結果"
			)
		);
		$i++;

		// タグ検索
	} elseif (is_tag()) {

		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => single_tag_title('', false)
			)
		);
		$i++;

		// 404
	} elseif (is_404()) {

		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => "404 Not found"
			)
		);
		$i++;

		// 日付アーカイブ
	} elseif (is_date()) {

		if (get_query_var('day') != 0) {

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_year_link(get_query_var('year')),
					"name" => get_query_var('year') . "年"
				)
			);
			$i++;

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_month_link(get_query_var('year'), get_query_var('monthnum')),
					"name" => get_query_var('monthnum') . "月"
				)
			);
			$i++;

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => get_query_var('day') . "日"
				)
			);
			$i++;
		} elseif (get_query_var('monthnum') != 0) {

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_month_link(get_query_var('year')),
					"name" => get_query_var('year') . "年"
				)
			);
			$i++;

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => get_query_var('monthnum') . "月"
				)
			);
			$i++;
		} else {
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => get_query_var('year') . "年"
				)
			);
			$i++;
		}

		// カテゴリー別アーカイブ
	} elseif (is_category()) {

		$cat = get_queried_object();
		if ($cat->parent != 0) {
			$ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
			foreach ($ancestors as $ancestor) {
				$str['itemListElement'][] = array(
					"@type" => "ListItem",
					"position" => $i,
					"item" => array(
						"@id" => get_category_link($ancestor),
						"name" => get_cat_name($ancestor)
					)
				);
				$i++;
			}
		}
		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => $cat->name
			)
		);
		$i++;
	} elseif (is_author()) {

		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => get_the_author_meta('display_name', get_query_var('author'))
			)
		);
		$i++;
	} elseif (is_page()) {

		if ($post->post_parent != 0) {
			$ancestors = array_reverse(get_post_ancestors($post->ID));
			foreach ($ancestors as $ancestor) {
				$str['itemListElement'][] = array(
					"@type" => "ListItem",
					"position" => $i,
					"item" => array(
						"@id" => get_permalink($ancestor),
						"name" => get_the_title($ancestor)
					)
				);
				$i++;
			}
		}
		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => $post->post_title
			)
		);
		$i++;
	} elseif (is_attachment()) {

		if ($post->post_parent != 0) {
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_permalink($post->post_parent),
					"name" => get_the_title($post->post_parent)
				)
			);
			$i++;
		}
		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => $post->post_title
			)
		);
		$i++;
	} elseif (is_single()) {

		$categories = get_the_category($post->ID);
		if (!empty($categories)) {
			$cat = $categories[0];
			$cat_name = (is_singular('post')) ? $cat->cat_name : $cat->name;
			if ($cat->parent != 0) {
				$ancestors = (is_singular('post')) ? array_reverse(get_ancestors($cat->cat_ID, 'category')) : array_reverse(get_ancestors($cat->term_id, $term));
				foreach ($ancestors as $ancestor) {
					if (get_cat_name($ancestor) !== $obj->label) {
						$str['itemListElement'][] = array(
							"@type" => "ListItem",
							"position" => $i,
							"item" => array(
								"@id" => get_term_link($ancestor, $cat->taxonomy),
								"name" => get_cat_name($ancestor)
							)
						);
						$i++;
					}
				}
			}
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_term_link($cat->term_id, $cat->taxonomy),
					"name" => $cat_name
				)
			);
			$i++;
		}

		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => $post->post_title
			)
		);
		$i++;
	} elseif (is_tax()) {

		global $wp_query;
		$taxonomy = get_query_var('taxonomy');
		$post_type = $post->post_type;
		$cat = get_queried_object();
		if (empty($cat->term_id)) {

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => $cat->label
				)
			);
			$i++;
		} else {

			$cat_name = $cat->name;
			$obj = get_post_type_object($post_type);
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_post_type_archive_link($post_type),
					"name" => $obj->label
				)
			);
			$i++;
			if ($cat->parent != 0) {
				$ancestors = array_reverse(get_ancestors($cat->term_id, $cat->taxonomy));
				foreach ($ancestors as $ancestor) {
					if (get_cat_name($ancestor) !== $obj->label) {
						$str['itemListElement'][] = array(
							"@type" => "ListItem",
							"position" => $i,
							"item" => array(
								"@id" => get_term_link($ancestor, $cat->taxonomy),
								"name" => get_cat_name($ancestor)
							)
						);
					}
					$i++;
				}
			}
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => $cat_name
				)
			);
			$i++;
		}
	} elseif (is_post_type_archive()) {

		$cat = get_queried_object();
		if (empty($cat->term_id)) {

			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => $cat->label
				)
			);
			$i++;
		} else {

			$cat_name = $cat->name;
			$obj = get_post_type_object($post->post_type);
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => get_post_type_archive_link($post->post_type),
					"name" => $obj->label
				)
			);
			$i++;

			if ($cat->parent != 0) {
				$ancestors = array_reverse(get_ancestors($cat->term_id, $cat->taxonomy));
				foreach ($ancestors as $ancestor) {
					if (get_cat_name($ancestor) !== $obj->label) {
						$str['itemListElement'][] = array(
							"@type" => "ListItem",
							"position" => $i,
							"item" => array(
								"@id" => get_term_link($ancestor, $cat->taxonomy),
								"name" => get_cat_name($ancestor)
							)
						);
						$i++;
					}
				}
			}
			$str['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => $i,
				"item" => array(
					"@id" => $permalink,
					"name" => $cat_name
				)
			);
			$i++;
		}
	} else {

		$str['itemListElement'][] = array(
			"@type" => "ListItem",
			"position" => $i,
			"item" => array(
				"@id" => $permalink,
				"name" => wp_title('', false)
			)
		);
		$i++;
	}

	return $str;
}