<article class="category">
    <section class="title">
        <?php if(get_query_var('media-term')):?>
            <?php $term = get_term_by( 'slug' , get_query_var('media-term') , 'media-term');?>
            <h2><span class="en"><?php echo strtoupper(str_replace('-', ' ', get_query_var('media-term')));?></span><?php echo $term->name;?></h2>
        <?php else:?>
            <h2><span class="en">SEARCH</span>検索結果</h2>
        <?php endif;?>
    </section>
    <?php echo breadcrumb();?>
    <div class="d-lg-flex justify-content-center">
        <main class="w-1000">
            <h3>記事一覧</h3>
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
                                    <?php if($cat){echo '<p class="cat">'.$cat[0]->name.'</p>';}?>
                                    <p class="title"><?php the_title();?></p>
                                    <?php
                                        if($tags){
                                            $tag_html = '<ul class="tag">';
                                            foreach($tags as $tag){
                                                $tag_html .= '<li class="mr-2"><span>#</span>'.$tag->name.'</li>';
                                            }
                                            $tag_html .= '</ul>';
                                            echo $tag_html;
                                        }
                                    ?>
                                    <ul class="detail d-flex">
                                        <li>
                                            <dl class="d-flex mr-4">
                                                <dt><i class="fas fa-pen sky mr-2"></i></dt>
                                                <dd><time datetime="<?php the_time('Y-m-d');?>"><?php the_time('Y.m.d');?></time></dd>
                                            </dl>
                                        <li>
                                        </li>
                                            <dl class="d-flex mr-2">
                                                <dt><i class="fas fa-sync sky mr-2"></i></dt>
                                                <dd><time datetime="<?php the_modified_date('Y-m-d');?>"><?php the_modified_date('Y.m.d');?></time></dd>
                                            </dl>
                                        </li>
                                    </ul>
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
            $category = get_query_var('media-term') ? '/category/' . get_query_var('media-term') : '' ;
            $tag = get_query_var('post_tag') ? '/tag/' . get_query_var('post_tag') : '' ;
            if ($wp_query->max_num_pages > 1) {
                $paginate_args = array(
                    'base' => home_url('/media-list') . $category . $tag . '%_%',
                    'format' => '/page/%#%/',
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
        </main>
        <?php get_sidebar('media');?>
    </div>
</article>

