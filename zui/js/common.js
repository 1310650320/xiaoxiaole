//屏幕监控
var zl_height=$(window).height();
var zl_width=$(window).width();
var zl_top=$(window).scrollTop();
$(window).resize(function(){
	zl_height=$(this).height();
	zl_width=$(this).width();
});
$(window).scroll(function(){
	zl_top=$(this).scrollTop();
});

// 按钮互动事件
if ($('.loading-btn').length>0 || $('.count-btn').length>0){
$(document).ready(function(){
	$('.loading-btn').click(function() {
		var $btn = $(this);
		$btn.button('loading');
		setTimeout(function() {$btn.button('reset');}, 2000);
	});
	$('.count-btn').click(function() {
		var $btn = $(this);
		var btn_time = 30;
		$btn.button('loading');
		setTimeout(function() {reset_time();}, 1000);
		function reset_time() {
			if(btn_time>0){
				btn_time=btn_time-1;
				$btn.html(btn_time+'s');
				setTimeout(function() {reset_time();}, 1000);
			}else{
				$btn.button('reset');
			}
		}
	});
});
}
// 按下提示事件
if ($('.message-button').length>0){
$(document).ready(function(){
	$('.message-button').click(function() {
		var v_color=$(this).data('color');
		var v_content=$(this).data('content');
		var v_placement=$(this).data('placement');
		var v_icon=$(this).data('icon');
		if (!v_color){
			var v_color='primary';
		}
		if (!v_content){
			var v_content='按下按钮';
		}
		if (!v_icon){
			var v_icon='ok-sign';
		}
		if (!v_placement){
			var v_placement='center';
		}
		new $.zui.Messager(v_content, {
		icon: v_icon,
		placement: v_placement,
		type: v_color // 定义颜色主题
		}).show();
	});
});
}

// 底部按钮事件
if ($('.kj-bottom-btn').length>0){
	$(document).ready(function(){
			$(window).scroll(function(){
				var height=$('.kj-bottom-btn').offset().top;
				var top=zl_top+zl_height;
				if (height<top){
					$('.kj-bottom-btn').removeClass('active');
				}else{
					$('.kj-bottom-btn').addClass('active');
				}
			});
	});
}

// 顶部按钮事件
if ($('.kj-home').length>0){
	$(document).ready(function(){
			$(window).scroll(function(){
				var height2=$('#musicControl').offset().top;
				if (height2<zl_top){
					$('.kj-home').addClass('active');
				}else{
					$('.kj-home').removeClass('active');
				}
			});
	});
}


// 滚动动画事件
if ($('.wow').length>0){
document.writeln("<link rel=\'stylesheet\' href=\'zui/lib/wow/animate.min.css\'>");
document.writeln("<script src=\'zui/lib/wow/wow.min.js\'></script>");
var wx_wow = new Array();
$(document).ready(function(){
	wx_wow = new WOW({
		boxClass:     'wow',      // 需要执行动画的元素的 class（默认为wow）
		animateClass: 'animated', // 动画CSS类（默认为animated）
		offset:       0,          // 触发动画时的元素距离（默认为0）
		mobile:       true,       // 在移动设备上触发动画（默认为true）
		live:         true,       // 异步加载内容（默认为true）
		callback:     function(box) {
		}
	});
	wx_wow.init();
	//wx_wow.start(); //重载效果
	//data-wow-duration 改变动画时间
	//data-wow-delay 延迟在动画开始之前
	//data-wow-offset 距离开始动画(有关浏览器底部)
	//data-wow-iteration 动画重复的次数
});
}

// 音效事件
if ($('#musicAn1').length>0 || $('#mc_play').length>0 ){
	$(document).ready(function () {
		$('.musicAn').click(function() {
			if (navigator.vibrate) {
				navigator.vibrate(200);
			} else if (navigator.webkitVibrate) {
				navigator.webkitVibrate(200);
			}
		});
		$('.musicAn1').click(function() {
			$('#musican1').get(0).play();
			if (navigator.vibrate) {
				navigator.vibrate(50);
			} else if (navigator.webkitVibrate) {
				navigator.webkitVibrate(50);
			}
			$('#music_play_filter').hide();
		});
		$('.musicAn2').click(function() {
			$('#musican2').get(0).play();
			if (navigator.vibrate) {
				navigator.vibrate(200);
			} else if (navigator.webkitVibrate) {
				navigator.webkitVibrate(200);
			}
			$('#music_play_filter').hide();
		});
	});
	function play_music(){
	if ($('#mc_play').hasClass('on')){
		$('#mc_play audio').get(0).pause();
		$('#mc_play').attr('class','stop');
		if (navigator.vibrate) {
			navigator.vibrate(200);
		} else if (navigator.webkitVibrate) {
			navigator.webkitVibrate(200);
		}
	}else{
		$('#mc_play audio').get(0).play();
		$('#mc_play').attr('class','on');
	}
	$('#music_play_filter').hide();
	event.stopPropagation(); //阻止冒泡 
	}
	function just_play(id){
		if ($('#mc_play').length>0){ 
			$('#mc_play audio').get(0).play();
		}
		$('#mc_play').attr('class','on');
		if (typeof(id)!='undefined'){
			$('#music_play_filter').hide();
		}
		event.stopPropagation(); //阻止冒泡 
	} 
	document.addEventListener('DOMContentLoaded',function (){
		function audioAutoPlay(){
			just_play();
			document.addEventListener("WeixinJSBridgeReady", function () {
				just_play();
			}, false);
		}
		audioAutoPlay();
	});
}

// 加载事件
if($('.kj-loading-html').length>0){
$(document).ready(function(){
    setTimeout(loading,300);
    function loading() {
      $('html').removeClass('kj-loading-html');
	}
});
}