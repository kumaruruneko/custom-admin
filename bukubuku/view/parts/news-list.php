<?php get_header(); ?>

<article class="news">
	<section class="title">
		<h2><span class="en">NEWS</span>新着情報</h2>
	</section>
    <?php echo breadcrumb();?>
	<main>
		<div class="container">
			<ul class="tab_design">
				<?php if (empty(get_query_var('news-term'))) : ?>
				<li class="active"><a href="<?php echo home(); ?>news-list">ALL</a></li>
				<?php else : ?>
				<li class=""><a href="<?php echo home(); ?>news-list">ALL</a></li>
				<?php endif; ?>
				<?php $terms = get_terms('news-term', array(
					'hide_empty' => false,
					'orderby' => 'ID'
				));
				?>
				<?php foreach ($terms as $term) : ?>

				<?php if ($term->slug == get_query_var('news-term')) {
						$active = 'active';
					} else {
						$active = '';
					}; ?>
				<li class="<?php echo $active; ?>"><a
						href="<?php echo home(); ?>news-list/type/<?php echo $term->slug; ?>/"><?php echo $term->name; ?></a></li>
				<?php endforeach; ?>
			</ul>
            <?php if(have_posts()): ?>
                <ul class="list">
                    <?php while(have_posts()): the_post(); ?>
                    <?php
                        $cat = get_the_terms($post->ID,'media-term');
                        $tags = get_the_tags();
						$no_img = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://placehold.jp/eeeeee/cccccc/430x300.png?text=No%20Image';
						$capture_img = get_the_post_thumbnail_url($post->ID,'full') ? get_the_post_thumbnail_url($post->ID,'full') : $no_img;
                    ?>
                        <li>
                            <a href="<?php echo get_the_permalink();?>">
                                <div class="img">
                                    <p class="img_box" style="background:url(<?php echo $capture_img;?>) center center / cover;"></p>
                                </div>
                                <div class="info">
									<p>
										<time><?php echo get_the_date('Y.m.d'); ?></time>
										<?php
											$terms = get_the_terms($post->ID, 'news-term');
											if(!empty($terms)){
												$term_name = $terms[0]->name;
											}else{
												$term_name = '未分類';
											}
										?>
										<span class="cat"><?php echo $term_name; ?></span>
									</p>
									<p class="title"><?php the_title(); ?></p>
									<p>
										<?php echo mb_strimwidth(get_the_excerpt(), 0, 300, "...");?>
									</p>
                                </div>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>検索された記事はありません。</p>
            <?php endif; ?>
            <?php
            global $wp_query;
			$link = get_query_var('news-term') ? 'type/' . get_query_var('news-term') . '/' : '' ;
            if ($wp_query->max_num_pages > 1) {
                $paginate_args = array(
                    'base' => home_url('/news-list/') . $link . '%_%',
                    'format' => 'page/%#%/',
                    'current' => max(1, $paged),
                    'mid_size' => 4,
                    'next_text' => '次のページ',
                    'prev_text' => '前のページ',
                    'type' => 'list',
                    'total' => $wp_query->max_num_pages
                );

                $paginate_links = paginate_links($paginate_args);
                echo $paginate_links;
            }; ?>

		</div>
	</main>
</article>


<?php get_footer(); ?>