<?php

/******************************************************
 *
 * ページネーション
 *
 *******************************************************/
function wp_pagination($pages = '', $range = 5)
{

	//ページ情報の取得
	global $paged;
	if (empty($paged)) $paged = 1;

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}

	$page_center = ceil($range / 2);
	$page_minus = $page_center - 1;
	$page_plus = ($range % 2 == 0) ? $page_minus + 1 : $page_minus;
	$pag_col = ($pages - $range) + 1;

	if (1 != $pages) {
		echo '<ul class="pagination justify-content-center">';
		//先頭へ
		//echo '<li class="page-item"><a href="'.get_pagenum_link(1).'" class="page-link">&lt;&lt;最新</a></li>';
		// 前へ
		echo '<li class="page-item first">';
		if ($paged > 1) {
			echo '<a href="' . get_pagenum_link($paged - 1) . '" class="page-link"><i class="fas fa-angle-left"></i></a>';
		}
		echo '</li>';
		//番号つきページ送りボタン
		if ($pages < $range) {
			for ($i = 1; $i <= $pages; $i++) {
				print_page_loop($paged, $i);
			}
		} elseif ($paged <= $page_center) {
			for ($i = 1; $i <= $range; $i++) {
				print_page_loop($paged, $i);
			}
		} elseif (($paged + $page_minus) >= $pages) {
			for ($i = $pag_col; $i <= $pages; $i++) {
				print_page_loop($paged, $i);
			}
		} else {
			for ($i = ($paged - $page_minus); $i <= ($paged + $page_plus); $i++) {
				print_page_loop($paged, $i);
			}
		}
		// 次へ
		echo '<li class="page-item last">';
		if ($paged < $pages) {
			echo '<a href="' . get_pagenum_link($paged + 1) . '" class="page-link"><i class="fas fa-angle-right"></i></a>';
		}
		echo '</li>';
		//最後尾へ
		//echo '<li class="page-item"><a href="'.get_pagenum_link($pages).'" class="page-link">古い&gt;&gt;</a></li>';
		echo '</ul>';
	}
}
function print_page_loop($active, $value)
{
	if ($active == $value) {
		echo '<li class="page-item active"><a class="page-link">' . $value . '</a></li>';
	} else {
		echo '<li class="page-item"><a href="' . get_pagenum_link($value) . '" class="page-link">' . $value . '</a></li>';
	}
}

function base_post_nav()
{
	global $post;

	$previous = (is_attachment()) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
	$next     = get_adjacent_post(false, '', false);

	if (!$next && !$previous) {
		return;
	}
	echo '<nav class="navigation post-navigation" role="navigation"><div class="nav-links">';
	previous_post_link('%link', '<span class="meta-nav">&larr;</span> %title');
	next_post_link('%link', '%title <span class="meta-nav">&rarr;</span>');
	echo '</div></nav>';
}