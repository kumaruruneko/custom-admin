// キャッシュを格納
var setMedia = [];
/******************************************************
				GETの値を取得する
*******************************************************/
function getParam(type) {
	var url   = location.href;
	var parameters    = url.split('?');
	if (parameters.length < 1){ return; }
	var params   = parameters[1].split('&');
	var paramsArray = [];
	for ( var i = 0; i < params.length; i++ ) {
		var neet = params[i].split('=');
		paramsArray.push(neet[0]);
		paramsArray[neet[0]] = neet[1];
	}
	var categoryKey = paramsArray[type];
	return categoryKey;
}

/******************************************************
				カスタム投稿別にメディアの種類を変える
*******************************************************/
function postTypelibrary(){
	var posttype=getParam('post_type');
	var lib = 'image';
	if (posttype==undefined){ return lib; }
	switch ( posttype ) {
	case 'app':
	default:
		lib =  'image';
		break;

	}
	return lib;
}

/******************************************************
				投稿にメディアアップローダーを作成
*******************************************************/
function mediaUpload(type){
	var custom_uploader;
	if ( !type ){ type = postTypelibrary(); }
	custom_uploader = wp.media({
		title  : 'ファイルを選択',
		/* ライブラリの一覧は画像のみにする */
		library: {
			type: type
		},
		frame : 'select',
		button: {
			text: '決定'
		},
		/* 選択できる画像は 1 つだけにする */
		multiple: false
	});
	return custom_uploader;
}

/******************************************************
				メディアアップローダーを開く
*******************************************************/
export default function openUpload(btn){
	if ( !btn ){ return; }

	var type = ( $(btn).attr('data-type') )? $(btn).attr('data-type'): postTypelibrary();
	var parent = $(btn).parents('.mediaupload');
	var input = parent.find('[type="hidden"]');
	var box = parent.find('.img_box');
	var targetId = 'target_'+$(btn.type).index($(btn));

	// キャッシュを表示する
	if (setMedia[targetId]) {
		setMedia[targetId].open();
		return;
	}

	setMedia[targetId] = mediaUpload(type);
	setMedia[targetId].on('select', function() {
		var images = setMedia[targetId].state().get('selection');
		/* file の中に選択された画像の各種情報が入っている */
		images.each(function(file) {

			if ( type != file.attributes.type ){
				alert('アップロードできない形式です。');
				return;
			}
			input.val('');
			box.empty();

			input.val(file.id);

			// /* プレビュー用に選択されたサムネイル画像を表示 */

			if (file.attributes.type=='audio'){
				box.append('<img src="' + file.attributes.image.src + '" alt="サムネイル画像" width="'+file.attributes.image.width+'" class="middle">&nbsp;<span>'+file.attributes.title+'</span>');
			} else {
				box.append('<img src="' + file.attributes.url + '" alt="サムネイル画像" class="img-fluid">');
				box.removeClass('select');
			}
		});
	});
	setMedia[targetId].open();
}
