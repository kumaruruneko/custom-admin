import 'bootstrap';
import 'jquery-drawer';
import Swiper from 'swiper';
(function ($) {

	var url = location.href// URLの取得
	var path = location.pathname// パスの取得
	var param = location.search// パラメーターの取得
	var anc = location.hash// ページ内アンカーの取得
	path = path.replace('sample_iemamori/', '');// 本番環境では削除
	if (url == 'http://example.com/') { }
	if (path.match(/cart\//)) { }
	if (path == '/login.html') {
		Action.checkFullPageBackgroundImage();
	}
	if (param == '?search=123') { }
	if (anc == '#anchor01') { }

	$('.drawer').drawer();

	var swiper = new Swiper('.info .swiper-container', {
		spaceBetween: 20,
		effect: 'slide',
		slidesPerView: 4,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '#next_btn',
			prevEl: '#prev_btn',
		},
		// autoplay: {
		//     delay: 5000,
		// },
		loop: false,
		breakpoints: {
			767: {
				slidesPerView: 2,
			},
		}
	});

	var swiper = new Swiper('.shop_slider', {
		spaceBetween: 20,
		effect: 'slide',
		slidesPerView: 3,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '#shop_next',
			prevEl: '#shop_prev',
		},
		// autoplay: {
		//     delay: 5000,
		// },
		loop: false,
		breakpoints: {
			640: {
				slidesPerView: 2,
			},
		}
	});
	var swiper = new Swiper('.pickup_slider', {
		spaceBetween: 65,
		effect: 'slide',
		slidesPerView: 2.7,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '#pickup_next',
			prevEl: '#pickup_prev',
		},
		// autoplay: {
		//     delay: 5000,
		// },
		loop: true,
		breakpoints: {
			1000: {
				slidesPerView: 'auto',
			},
			1400: {
				slidesPerView: 1.7,
			},
		}
	});

})(jQuery); import 'bootstrap';

$(function () {
	$('a.page_link').on('click', function () {
		var speed = 500;
		var href = $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top - 60;
		$("html, body").animate({ scrollTop: position }, speed, "swing");
		return false;
	});
	$('.to_top a').on('click', function () {
		var speed = 500;
		$("html, body").animate({ scrollTop: 0 }, speed, "swing");
		return false;
	});
	$('.ranking .more').on('click', function () {
		$(this).fadeOut('fast');
		$('.down5').slideToggle();
	});
	$('.accordion dt').on('click', function () {
		$(this).next('dd').slideToggle();
		$(this).toggleClass('active');
	});
	$('.accordion .accordion-toggle').on('click', function () {
		$(this).next('.accordion-menu').slideToggle();
		$(this).toggleClass('active');
	});
	$('.tab li').on('click', function () {
		var index = $(this).index();
		$('.tab li').removeClass('active');
		$('.tab_list li').removeClass('active');
		$(this).addClass('active');
		$('.tab_list>li').eq(index).addClass('active');
	});
	$('#sort').on('change', function () {
		var url = $(this).children('option:selected').val();
		location.href = url;
	});
	$('.dropdown-toggle').each(function () {
		var left = $(this).offset().left;
		$(this).next('div.dropdown-menu').css('left', -left);
	});
	$(window).on('resize', function () {
		$('.dropdown-toggle').each(function () {
			var left = $(this).offset().left;
			$(this).next('div.dropdown-menu').css('left', -left);
		});
	});
	var w_height = $('body').height() - $(window).height();
	$(window).on('scroll', function () {
		var s_top = $(this).scrollTop();
		if (s_top == 0) {
			$('header .navbar').css('border', 'none');
		} else {
			$('header .navbar').css('border-bottom', '1px solid #ddd');
		}
		if (s_top > 500) {
			$('.to_top').fadeIn();
		} else {
			$('.to_top').fadeOut();
		}
		if (s_top > w_height - 500) {
			$('.to_top').css({ 'position': 'absolute', 'top': '-60px', 'bottom': 'inherit' });
		} else {
			$('.to_top').css({ 'position': 'fixed', 'top': 'inherit', 'bottom': '20px' });
		}
	});
});
var ref = document.referrer;
if (ref.indexOf('contact-confirm') != -1) {
	// jQuery('.your-accept').remove();
	jQuery('input[name=your-accept]').prop('checked', true);
	jQuery('input[type=submit]').prop('disabled', false);
}