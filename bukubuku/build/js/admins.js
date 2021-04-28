import openUpload from './mediauploader.js';

//リロード等の読み込み時は初期状態からチェックが入っているため、関数で判定を行う
checkedLimit();
checkedLimit_area();
//チェック時に制限チェックの関数で判定を行う
$('#feature_box input').on('click', function () {
	checkedLimit();
});
$('#area_box input').on('click', function () {
	checkedLimit_area();
});

//イベントの他、初回読み込み時に実行したいため、関数にまとめる
function checkedLimit() {
	$('#feature_box input:checkbox').prop('disabled', false);
	var len = $('#feature_box input:checkbox:checked').length;
	//2つ目のチェックが入ったら、残りのチェックボックスを触れなくする
	if (len >= 9) {
		$('#feature_box input').not(':checked').prop('disabled', true);
	}
}
function checkedLimit_area() {
	$('#area_box input:checkbox').prop('disabled', false);
	var len = $('#area_box input:checkbox:checked').length;
	//規定数のチェックが入ったら、残りのチェックボックスを触れなくする
	if (len >= 15) {
		$('#area_box input').not(':checked').prop('disabled', true);
	}
}
$('#feature_box label').on('click', function () {
	var over_check = $(this).children('input').prop('disabled')
	console.log(over_check);
	if (over_check == true) {
		$('#over_err').show('fast')
		$('#over_err').addClass('error')
	} else {
		$('#over_err').hide('fast')
		$('#over_err').removeClass('error')
	}
})
$('#area_box label ,#area_box label input').on('click', function () {
	var area_over_check = $(this).children('input').prop('disabled')
	if (area_over_check == true) {
		$('#area_over_err').show('fast')
		$('#area_over_err').addClass('error')
	} else {
		$('#area_over_err').hide('fast')
		$('#area_over_err').removeClass('error')
	}
})

$('#taxonomy-pref-term input[type=checkbox]').each(function () {
	$(this).replaceWith($(this).clone().attr('type', 'radio'));
});

$('#taxonomy-pref-term #pref-termchecklist>li').each(function () {
	var text_sort = $(this).children('label').text()

	if (text_sort == ' 未分類') {

		$(this).attr('id', 'pref-term-99999')
	}
})
$('#taxonomy-pref-term #pref-termchecklist').html(
	$('#taxonomy-pref-term #pref-termchecklist>li').sort(function (a, b) {
		// 昇順
		if ($(a).attr('id') < $(b).attr('id')) {
			// 降順
			// if ($(a).text() < $(b).text()) {
			return 1;
		} else {
			return -1;
		}
	})
)
if ($('#taxonomy-pref-term input[type=radio]:checked').length == 0) {
	$('#pref-term-99999 input').attr("checked", "checked")
}

$('#r_bg').on('click', function () {
	$('.metabox-holder .logo_field').toggleClass('bg_re')
})

$('.fs-slug-contact-form-7-multi-step-module').remove();



var set_select = $('#select').val()
var set_hookahs = $('#hookahs').val()
$('.preview_box.box-1 ul li').each(function () {
	if ($(this).attr('data-set_no') == set_select) {
		$(this).css('display', 'flex')
	} else {
		$(this).css('display', 'none')
	}
})
$('.preview_box.box-2 ul li').each(function () {
	if ($(this).attr('data-set_no') == set_hookahs) {
		$(this).css('display', 'flex')
	} else {
		$(this).css('display', 'none')
	}
})
$(document).on('change load', '#select', function () {
	var set_select = $('#select').val()
	$('.preview_box.box-1 ul li').each(function () {
		if ($(this).attr('data-set_no') == set_select) {
			$(this).css('display', 'flex')
		} else {
			$(this).css('display', 'none')
		}
	})
})
$(document).on('change load', '#hookahs', function () {
	var set_hookahs = $('#hookahs').val()
	$('.preview_box.box-2 ul li').each(function () {
		if ($(this).attr('data-set_no') == set_hookahs) {
			$(this).css('display', 'flex')
		} else {
			$(this).css('display', 'none')
		}
	})
})

function img_check(named) {
	if ($(this).attr('data-set_no') == named) {
		$(this).css('display', 'flex')
	} else {
		$(this).css('display', 'none')
	}
}