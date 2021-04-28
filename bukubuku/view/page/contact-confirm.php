<?php get_header(); ?>
<article class="compcontactany">
	<section class="title">
		<h2><span class="en">CONTACT</span>お問い合わせ</h2>
	</section>
	<?php
	$page_name = 'お問い合わせ';
	get_template_part('view/parts/breadcrumb', null, $page_name);
	?>
	<main class="container w-1000">
		<section class="info">
			<p class="text step2">入力内容をご確認ください</p>
			<?php echo do_shortcode('[contact-form-7 id="61" title="お問い合わせ_確認画面"]'); ?>
		</section>
	</main>
</article>

<?php get_footer(); ?>