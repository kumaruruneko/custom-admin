<div class="form">
	<form method="get" id="searchform" action="<?php echo home(); ?>">
		<ul class="d-flex align-items-center flex-wrap">
			<li class="title">
				<h3>キーワードで探す</h3>
			</li>
			<li class="input_box">
				<input type="text" value="" name="s" class="s" placeholder="店舗名、地域、エリア、ジャンルなどを記入して検索！">
			</li>
			<li class="btn_box"><button>SEARCH</button></li>
		</ul>
		<input type="hidden" name="post_type" value="store-list">
	</form>
</div>