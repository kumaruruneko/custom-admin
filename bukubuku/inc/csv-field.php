<?php

$post_id = $post->ID;
//メタボックスの作成
function add_my_custom_meta_box()
{
	add_meta_box(
		'custom_setting',  //編集画面の HTML 要素の id 属性
		'機種情報入力',  //画面上に表示されるタイトル文字列
		'my_meta_box_markup', //HTML を出力するコールバック関数（表示用関数）名
		'dashboard',
		'normal',
		'low',
		//入力エリアの HTML を出力する表示用関数の第2パラメータを使って参照する値を指定
		array(
			'title_key' => 'my_title',   //カスタムフィールドのキー
			'title_label' => 'ページのタイトル',  //項目別のタイトル
			'description_key' => 'my_description',   //カスタムフィールドのキー
			'description_label' => 'ページの説明'  //項目別のタイトル
		)
	);
}
add_action('add_meta_boxes', 'add_my_custom_meta_box');

//入力エリアの HTML を出力する関数（第2引数 $tb を指定）
function my_meta_box_markup($post, $tb)
{
	wp_nonce_field('meta_box_title_and_description_action', 'my_meta_box_nonce');
	$cf_title_key = trim($tb['args']['title_key']);  //'my_title'
	$cf_title_label = trim($tb['args']['title_label']);  //'ページのタイトル'
	$cf_title_value = esc_html(get_post_meta($post->ID, $cf_title_key, true));  //カスタムフィールド my_title の値
	$cf_description_key = trim($tb['args']['description_key']);  //'my_description'
	$cf_description_label = trim($tb['args']['description_label']);  //'ページの説明'
	$cf_description_value = esc_html(get_post_meta($post->ID, $cf_description_key, true));  //カスタムフィールド my_description の値
?>
<?php
	// テスト用配列
	$storeArry_set = ['cs0' => [
		"'cs_name'" => null,
		"'cs_type'" => null,
		"'mc'" => [
			'' => '',
		],
	],];
	
	$storeArrys = get_post_meta($post->ID, 'cs', false);
	if (isset($storeArrys[0])) {
		$set_array = $storeArrys[0];
	} else {
		$set_array = $storeArry_set;
	}

	?>

<?php $i = 0; ?>
<form id="cs_form" method="post">
	<?php foreach ($set_array as $value) : ?>

	<div data-set_val="cs<?php echo "['cs" . $i . "']"; ?>['mc']" class="tb_box" id="tb_box-<?php echo $i; ?>">
		<h3>
			レート別：<input class="cs_rate" type="text" placeholder="4円パチンコ・5円スロットなど"
				name="cs<?php echo "['cs" . $i . "']"; ?>['cs_name']" value="<?php echo  $value["'cs_name'"]; ?>" size="50">

			<?php
					$check = $value["'cs_type'"];
					if ($check == 'pachinko') {
						$p_check = 'checked';
					} else {
						$p_check = '';
					}
					if ($check == 'slot') {
						$s_check = 'checked';
					} else {
						$s_check = '';
					}
					?>
			遊技台種別：
			<label class="cs_type-p-l" for="pachi<?php echo $i; ?>">
				<input class="cs_type-p" <?php echo $p_check; ?> type="radio" id="pachi<?php echo $i; ?>"
					name="cs<?php echo "['cs" . $i . "']"; ?>['cs_type']" value="pachinko">パチンコ</label>
			<label class="cs_type-s-l" for="slot<?php echo $i; ?>">
				<input class="cs_type-s" <?php echo $s_check; ?> type="radio" id="slot<?php echo $i; ?>"
					name="cs<?php echo "['cs" . $i . "']"; ?>['cs_type']" value="slot">スロット</label>

			<div class="btn_group">
				<button type="button" class="btn btn-primary add_rate">レートを追加</button>
				<button type="button" class="btn btn-primary add_mc">機種を追加</button>
				<button type="button" class="btn btn-primary btn-success del_rate">レートを削除</button>
			</div>
		</h3>

		<?php $ii = 0; ?>
		<?php if (!is_array($value["'mc'"])) {
					$value["'mc'"] = array($value["'mc'"] => null);
				}; ?>

		<?php foreach ($value["'mc'"] as $key => $data) :  ?>

		<div>
			<span></span>
			<dl>
				<dt>機種名：
					<input required type="text" name="cs<?php echo "['cs" . $i . "']"; ?>['mc']" value="<?php echo $key; ?>"
						size="100" data-base_val="cs<?php echo "['cs" . $i . "']"; ?>['mc']" />

				</dt>
				<dd>設置台数：<input required type="text" name="cs<?php echo "['cs" . $i . "']"; ?>['mc']"
						value="<?php echo $data; ?>" size="10" data-base_val="cs<?php echo "['cs" . $i . "']"; ?>['mc']" />
				</dd>
				<dd><button type="button" class="btn btn-primary btn-success del_mc">削除</button></dd>
			</dl>
		</div>
		<?php $ii++;
				endforeach; ?>
	</div>
</form>
<?php $i++;
		endforeach; ?>
<?php
	$storeArry = get_post_meta($post->ID, 'cs', false);
?>
<!-- <pre><?php print_r($storeArry[0]); ?></pre>
<pre><?php print_r($storeArrys[0]); ?></pre> -->
<?php
}

//入力された情報の保存
function save_my_custom_meta_box($post_id)
{
	//HTML出力用関数で設定した nonce を取得
	$my_meta_box_nonce = isset($_POST['my_meta_box_nonce']) ? $_POST['my_meta_box_nonce'] : null;
	//nonce を検証し値が正しくなければ return（何もしない）
	if (!wp_verify_nonce($my_meta_box_nonce, 'meta_box_title_and_description_action')) {
		return;
	}
	//自動保存ルーチンかどうか。（記事の自動保存処理として呼び出された場合の対策）
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	//ユーザーが編集権限を持っていない場合は何もしない
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	//カスタムフィールドのキーの配列
	$my_cf_keys = ['my_title', 'my_description'];

	//add_meta_box の第4パラメータ（$screen）で指定した投稿タイプを確認
	if ($_POST['post_type'] == 'store-list') {
		//各カスタムフィールドのキーで繰り返し
		// foreach ($my_cf_keys as $cf_key) {
		// 	if (isset($_POST[$cf_key])) {
		// 		update_post_meta($post_id, $cf_key, $_POST[$cf_key]);
		// 	}
		// }
		if (!empty($_POST['cs'])) { //題名が入力されている場合
			update_post_meta($post_id, 'cs', $_POST['cs']); //値を保存
		} else { //題名未入力の場合
			// delete_post_meta($post_id, 'cs'); //値を削除
		}
	}
}
add_action('save_post', 'save_my_custom_meta_box'); 



















// function add_custom_fields(){
// add_meta_box(
// 'custom_setting', //編集画面セクションのHTML ID
// '機種情報', //編集画面セクションのタイトル、画面上に表示される
// 'insert_custom_fields', //編集画面セクションにHTML出力する関数
// 		'store-list', //投稿タイプ名
// 		'normal' //編集画面セクションが表示される部分
// );
// }
// add_action('admin_menu', 'add_custom_fields');


// カスタムフィールドの入力エリア
// function insert_custom_fields(){
// global $post;
// 	$post_ID = $post->ID;
//get_post_meta関数を使ってpostmeta情報を取得

// 	        echo '<section>';
// 					echo '<h3>コース種別：<input type="text" placeholder="4円パチンコ・5円スロットなど" name="tb-01[grp]" value="' . get_post_meta($post->ID, 'tb-01[grp]', true) . '" size="50">遊技台種別：<input checked type="radio" id="pachi" name="tb-01[type]" value="pachinko"><label for="pachi">パチンコ</label><input type="radio" id="slot" name="tb-01[type]" value="slot"><label for="slot">スロット</label></h3>';
// 					echo '<dl>';
// 						echo '<dt>機種名：<input type="text" name="tb-01[name]" value="' . get_post_meta($post->ID, 'tb-01[name]', true) . '" size="100" /></dt>';
// 						echo '<dd>設置台数：<input type="text" name="tb-01[units]" value="' . get_post_meta($post->ID, 'tb-01[units]', true) . '" size="10" /></dd>';
// 					echo '</dl>';
// 					echo '</section>';
// 	              $get_tb = get_post_meta($post->ID , 'tb-01' , false);
// 					echo '<pre>' . print_r($get_tb) .'</pre>';
// }
// function save_custom_fields( $post_id ) {

// 	foreach ($_POST['tb-01'] as $key => $value) {
		
			
// 				$terms = [];
// 				foreach ($value as $v) {
// 					if ((int)$v != 0) {
// 						$terms[] = (int)$v;
// 					}
// 				}
// 			}
		
		
// 		if (empty($value) || is_array($value) && empty(array_filter($value))) {
// 			delete_post_meta($post_id, '_' . $key);
			
// 		}
// 		$meta_value = (is_array($value)) ? array_filter($value) : $value;
// 		update_post_meta($post_id, '_' . $key, $meta_value);
// 	}


	// if(!empty($_POST['tb-01'])){ //入力されている場合
	// 	update_post_meta($post_id, 'tb-01', $_POST['tb-01'] ); //値を保存
	// }else{ //未入力の場合
	// 	delete_post_meta($post_id, 'tb-01'); //値を削除
	// }
// 	if(!empty($_POST['tb_type-01'])){ //入力されている場合
// 		update_post_meta($post_id, 'tb_type-01', $_POST['tb_type-01'] ); //値を保存
// 	}else{ //未入力の場合
// 		delete_post_meta($post_id, 'tb_type-01'); //値を削除
// 	}
// 	if(!empty($_POST['tb_name-01'])){ //入力されている場合
// 		update_post_meta($post_id, 'tb_name-01', $_POST['tb_name-01'] ); //値を保存
// 	}else{ //未入力の場合
// 		delete_post_meta($post_id, 'tb_name-01'); //値を削除
// 	}
// 	if(!empty($_POST['tb_units-01'])){ //入力されている場合
// 		update_post_meta($post_id, 'tb_units-01', $_POST['tb_units-01'] ); //値を保存
// 	}else{ //未入力の場合
// 		delete_post_meta($post_id, 'tb_units-01'); //値を削除
// 	}
// }
// add_action('save_post', 'save_custom_fields');