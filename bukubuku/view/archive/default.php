<div class="editor section">
	<div class="container">
		<?php if (have_posts()) : ?>
			<div class="row">
				<?php while (have_posts()) : the_post(); ?>
					<article class="col-md-4 d-flex">
						<a href="<?php the_permalink(); ?>" class="card w-100">
							<?php
								if( !empty($contents[0]['img']) ){
									echo wp_get_attachment_image( $contents[0]['img'], 'event_thumb', false, array('class'=>'card-img-top') );
								}else{
									echo '<img src="src/img/common/dummy-event.jpg" alt="" class="card-img-top">';
								}
								if( has_post_thumbnail() ){
									the_post_thumbnail( 'full', array('class'=>'img-fluid d-block mx-auto') );
								}else{
									echo '<img src="src/img/common/dummy.jpg" alt="" class="img-fluid d-block mx-auto">';
								}
							?>
							<div class="card-body">
								<h2 class="card-title"><?php the_title(); ?></h2>
								<p class="card-text"><?php echo $excerpt; ?></p>
							</div>
						</a>
					</article>
				<?php endwhile;?>
			</div>
			<?php wp_pagination(); ?>
		<?php else: ?>
			<div class="text-center h3">新着情報はありません。</div>
		<?php endif;?>
	</div>
</div>
