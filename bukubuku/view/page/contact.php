<?php get_header(); ?>
<article class="compcontactany">
	<section class="title">
		<h2><span class="en">CONTACT</span>お問い合わせ</h2>
	</section>
	<?php echo breadcrumb(); ?>
	<main class="container w-1000">
		<section class="info">
			<p class="text step1">当メディアサイトに関する<br class="sp">ご相談や各種お問い合せは<br>こちらの問い合わせフォームより<br class="sp">お願い致します。</p>
			<?php echo do_shortcode('[contact-form-7 id="58" title="お問い合わせ"]'); ?>
		</section>
	</main>
</article>


<?php get_footer(); ?>