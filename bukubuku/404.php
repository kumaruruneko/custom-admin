<?php get_header(); ?>

<section class="editor">
	<div class="container d-flex flex-column align-items-center text-center">
		<h2>404 NOT FOUND<small class="ml-3 text-primary">ページがみつかりません</small></h2>
		<p class="">見つからない場合は準備中のページの可能性がございます。<br>更新されるまでしばらくお待ち下さい。</p>
		<p class="mb-0"><a href="<?php echo esc_url(home_url('/')); ?>">TOPへ戻る &#8594;</a></p>
	</div>
</section>

<?php get_footer(); ?>