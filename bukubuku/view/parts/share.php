<div class="share">
	<h4>店舗情報をシェアする</h4>
	<ul class="sns-container top d-flex justify-content-center flex-wrap">
		<?php 
		$url = get_the_permalink();
		$post_types = get_query_var('post_type');
		$feed_url = home() . $post_types . '/feed/'; 
		?>
		<li class="sns-box b-twitter"><a
				href="https://twitter.com/share?url=<?php echo $url; ?>&amp;text=<?php echo get_the_title(); ?>" target="_blank"
				rel="nofollow"><i class="fab fa-twitter"></i></a></li>
		<li class="sns-box b-facebook"><a href="http://www.facebook.com/share.php?u=<?php echo urlencode($url); ?>"
				target="_blank" rel="nofollow"><i class="fab fa-facebook-f"></i></a></li>
		<li class="sns-box b-hatena"><a
				href="http://b.hatena.ne.jp/add?mode=confirm&amp;url=<?php echo urlencode($url); ?>&amp;title=<?php echo get_the_title(); ?>"
				target="_blank" rel="nofollow"><span class="icon-hatenabook"><img
						src="<?php echo img_dir(); ?>common/b_hatena.png" alt=""></span></a></li>
		<li class="sns-box b-line"><a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode($url); ?>"
				rel="nofollow"><span class="icon-line"><img src="<?php echo img_dir(); ?>common/b_line.png" alt=""></span></a>
		</li>
		<li class="sns-box b-feedly"><a href="<?php echo $feed_url ;?>" target="blank" rel="nofollow"><span
					class="icon-feedly"><img src="<?php echo img_dir(); ?>common/rss.png" alt="RSSを購読する"></span></a></li>
	</ul>
</div>