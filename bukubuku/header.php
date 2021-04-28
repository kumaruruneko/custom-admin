<!DOCTYPE html>
<html lang="ja">

<?php
if (is_single()) {
	$prefix = 'article';
} else {
	$prefix = 'website';
}
// global $wp_query;
// var_dump($wp_query);

?>

<head
	prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# <?php echo $prefix; ?>: https://ogp.me/ns/<?php echo $prefix; ?>#">
	<?php do_action('tracking_code_head'); ?>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php wp_head(); ?>
</head>

<body class="drawer drawer--right">

	<header>
		<div class="fixed-top bg-white">
			<div class="container">
				<section class="header_top d-flex justify-content-between align-items-center">
					<?php 
						$copy = get_option('copy');
					?>
					<h1><?php echo $copy ;?></h1>
					<div class="d-flex pc_nav">
						<?php
						wp_nav_menu(array(
							'theme_location' => 'main-menu',
							'container' => 'nav',
							'menu_class' => 'd-flex',

						));
						?>
						<?php
						$tw = get_option('tw');
						$insta = get_option('insta');
						$logo = get_option('logo');
						$logo_img = wp_get_attachment_image($logo, 'full', false, array('class' => 'img-fluid'));
						?>
						<ul class="sns d-flex">
							<?php if(!empty($tw)):?>
							<li><a target="_blank" href="<?php echo $tw; ?>"><i class="fab fa-twitter"></i></a></li>
							<?php endif;?>
							<?php if(!empty($insta)):?>
							<li><a target="_blank" href="<?php echo $insta; ?>"><i class="fab fa-instagram"></i></a></li>
							<?php endif;?>
						</ul>
					</div>
				</section>
				<nav class="drawer-nav">
					<h3>店舗検索</h3>
					<?php if(!is_page('media') && get_post_type() !== 'media-list' ||  !empty(get_query_var('pref-term')) || !empty(get_query_var('store-term')) || get_query_var('post_type') == 'store-list' ):?>
					<form method="get" id="searchform" action="<?php echo home(); ?>">
						<input type="text" value="" name="s"><button><i class="fas fa-search"></i></button>
						<input type="hidden" value="store-list" name="post_type">
					</form>
					<?php endif;?>
					<ul class="navbar-nav">
						<li class="accordion">
							<a href="javascript:void(0);" class="accordion-toggle">地域から探す<i class="fas fa-chevron-down"></i></a>
							<ul class="accordion-menu" role="menu">
								<?php $terms = get_terms('pref-term', array(
									'hide_empty' => true,
									'orderby' => 'ID',
									'parent' => 0
								));
								?>
								<?php foreach ($terms as $term) : ?>
								<?php if($term->name !== '未分類') :?>
								<li><a href="<?php echo home(); ?>store-list/area/<?php echo $term->slug; ?>/"><?php echo $term->name; ?></a></li>
								<?php
											$term_id = $term->term_id;
											$child_terms = get_term_children($term_id, 'pref-term');
										?>
								<?php if (!empty($child_terms)) : ?>
								<?php $taxonomy_name = 'pref-term';
												$terms = get_terms($taxonomy_name, array(
													'parent' => $term_id,
													'hide_empty' => true,
													'orderby' => 'term_order',
												));
											?>
								<?php foreach ($terms as $child_term) : ?>
								<li><a href="<?php echo home(); ?>store-list/area/<?php echo $term->slug; ?>/<?php echo $child_term->slug; ?>/"><span
											class="sky font-weight-bold mr-2">-</span><?php echo $child_term->name; ?></a></li>
								<?php endforeach; ?>
								<?php endif; ?>
								<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</li>
						<li class="accordion">
							<a href="javascript:void(0);" class="accordion-toggle">特徴から探す<i class="fas fa-chevron-down"></i></a>
							<ul class="accordion-menu" role="menu">
								<?php $terms = get_terms('store-term', array(
									'hide_empty' => true,
									'orderby' => 'ID'
								));
								?>
								<?php foreach ($terms as $term) : ?>
								<li><a href="<?php echo home(); ?>store-list/feature/<?php echo $term->slug; ?>"><?php echo $term->name; ?></a>
								</li>
								<?php endforeach; ?>
							</ul>
						</li>
					</ul>
					<?php if(is_page('media') || get_post_type() === 'media-list'):?>
					<h3>記事検索</h3>
					<form method="get" id="searchform" action="<?php echo home(); ?>">
						<input type="text" value="" name="s"><button><i class="fas fa-search"></i></button>
						<input type="hidden" value="media-list" name="post_type">
					</form>
					<ul class="navbar-nav">
						<li class="accordion">
							<a href="javascript:void(0);" class="accordion-toggle">カテゴリから探す<i class="fas fa-chevron-down"></i></a>
							<ul class="accordion-menu" role="menu">
								<?php
										$cats = get_terms('media-term');
									?>
								<?php foreach ( $cats as $cat ) : ?>
								<li><a
										href="<?php echo home_url();?>/media-list/category/<?php echo $cat->slug;?>/"><?php echo $cat->name;?></a>
								</li>
								<?php endforeach; ?>
							</ul>
						</li>
					</ul>
					<?php endif;?>
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'main-menu',
								'container' => 'nav',
							)
						);
					?>
					<?php if(!is_page('media') && get_post_type() !== 'media-list'):?>
					<p class="to_media"><a href="<?php echo home(); ?>media">BUKUBUKUメディア</a></p>
					<?php endif;?>
				</nav>
				<nav class="navbar navbar-expand-lg navbar-white">
					<a class="navbar-brand" href="<?php echo home(); ?>">
						<?php echo $logo_img ;?>
					</a>
					<button class="navbar-toggler drawer-toggle drawer-hamburger" type="button">
						<span class="drawer-hamburger-icon"></span>
					</button>
					<div id="navbarNav4" class="collapse navbar-collapse">
						<ul class="navbar-nav">
							<li class="nav-item active">
								<span class="nav-ttl">店舗検索</span>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">地域から探す<i
										class="fas fa-chevron-down"></i></a>
								<div class="dropdown-menu" role="menu">
									<?php $terms = get_terms('pref-term', array(
										'hide_empty' => true,
										'orderby' => 'ID',
										'parent' => 0
									));
									?>
									<?php foreach ($terms as $term) : ?>
									<?php if($term->name !== '未分類') :?>
									<div>
										<h4><a href="<?php echo home(); ?>store-list/area/<?php echo $term->slug; ?>/"><?php echo $term->name; ?></a>
										</h4>
										<?php
												$term_id = $term->term_id;
												$child_terms = get_term_children($term_id, 'pref-term');
											?>
										<?php if (!empty($child_terms)) : ?>
										<?php $taxonomy_name = 'pref-term';
													$terms = get_terms($taxonomy_name, array(
														'parent' => $term_id,
														'hide_empty' => true,
														'orderby' => 'term_order',
													));
												?>
										<ul>
											<?php foreach ($terms as $child_term) : ?>
											<li>
												<a
													href="<?php echo home(); ?>store-list/area/<?php echo $term->slug; ?>/<?php echo $child_term->slug; ?>/"><?php echo $child_term->name; ?></a>
											</li>
											<?php endforeach; ?>
										</ul>
										<?php endif; ?>
									</div>
									<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">特徴から探す<i
										class="fas fa-chevron-down"></i></a>
								<div class="dropdown-menu" role="menu">
									<ul class="d-flex flex-wrap features">
										<?php $terms = get_terms('store-term', array(
											'hide_empty' => true,
											'orderby' => 'ID'
										));
										?>
										<?php foreach ($terms as $term) : ?>
										<li><a href="<?php echo home(); ?>store-list/feature/<?php echo $term->slug;?>/"><?php echo $term->name; ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</li>
						</ul>
						<?php if(is_page('media') || get_post_type() === 'media-list'):?>
						<ul class="navbar-nav">
							<li class="nav-item active">
								<span class="nav-ttl">記事検索</span>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">カテゴリから探す<i
										class="fas fa-chevron-down"></i></a>
								<?php
									$cats = get_terms('media-term');
								?>
								<div class="dropdown-menu" role="menu">
									<ul class="d-flex flex-wrap features">
										<?php foreach ( $cats as $cat ) : ?>
										<li><a
												href="<?php echo home_url();?>/media-list/category/<?php echo $cat->slug;?>/"><?php echo $cat->name;?></a>
										</li>
										<?php endforeach; ?>
									</ul>
								</div>
							</li>
						</ul>
						<?php endif;?>
						<form method="get" action="<?php echo home(); ?>">
							<input type="text" value="" name="s"><button><i class="fas fa-search"></i></button>
							<?php
							if (is_page('media') || get_post_type() === 'media-list') {
								echo '<input type="hidden" value="media-list" name="post_type">';
							}else{
								echo '<input type="hidden" value="store-list" name="post_type">';
							}
							?>
						</form>
						<?php if(!is_page('media') && get_post_type() !== 'media-list'):?>
						<p class="to_media"><a href="<?php echo home(); ?>media">BUKUBUKUメディア</a></p>
						<?php endif;?>
					</div>
				</nav>
			</div>
		</div>
	</header>