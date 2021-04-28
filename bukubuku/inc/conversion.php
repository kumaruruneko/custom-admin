<?php

/*-------------------------------------------*/
/*	アドワーズ　コンバージョン設定
/*-------------------------------------------*/

// head内トラッキングコード
function set_tracking_code_head() {
	if ( !is_user_logged_in() ) {

		$code = <<<__SCRIPT__
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-799642466"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-799642466');
</script>
<script>
window.addEventListener('load',function(){
	jQuery('[href="tel:08008000830"]').click(function(){
	  gtag('event', 'conversion', {'send_to': 'AW-799642466/x5ItCOSTxoQBEOKmpv0C'});
	})
})
</script>
__SCRIPT__;
		//echo $code;

	}
}
add_action('tracking_code_head', 'set_tracking_code_head');

// body内トラッキングコード
function set_tracking_code_body() {
	if ( is_page('catalog-present') ) {

		$code = <<<__SCRIPT__
<script>
  gtag('event', 'conversion', {'send_to': 'AW-799642466/xRnHCP3-1IQBEOKmpv0C'});
</script>
__SCRIPT__;
		//echo $code;

	}
}
add_action('tracking_code_body', 'set_tracking_code_body');

// 電話コンバージョン
function tel_conversion(){
	$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	if( is_mobile() ){
		echo ' onclick="gtag_report_conversion('.$url.');"';
	}
	return;
}
