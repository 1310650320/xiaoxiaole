<?php

require_once 'env_sichem/init.php';
require_once 'function.php';
require_once 'config.php';
$topic_type = $topic['type'];
$topic_name = $topic['name'];
$openid=env_sichem::$_openid;
$user = env_sichem::getUser();

$wx_nickname= $user['nickname'];
$wx_headimgurl= $user['headimgurl'];
$wx_sdk= env_sichem::jssdk();
$wx_sdk=$wx_sdk['data']['data']['config'];
$appid=$wx_sdk['appId'];
$timestamp=$wx_sdk['timestamp'];
$nonceStr= $wx_sdk['nonceStr'];
$signature=$wx_sdk['signature'];
//var_dump($wx_sdk);
//exit;

//var_dump($wx_sdk);
//exit;
if(empty($_GET["belong"])){
	$belong='0';
}else{
	$belong=$_GET["belong"];
}
if(empty($openid)){
	exit;
}else{
	require_once 'mysqli.php';
	//查询人数；$total['total']
	$sql = "select heart from games_sdddp where openid = '$openid'";
	$res = $_mysqli->query($sql);
	$total = $res -> fetch_assoc();
	if(empty($total['heart'])){
		$wx_heart = '5';
	}else{
		$wx_heart = $total['heart'];
	}
	$_mysqli->close();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title><?echo($topic_name)?></title>
    <!-- 默认极速核心 -->
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
	<!-- Add to homescreen for Chrome on Android -->
	<meta name="mobile-web-app-capable" content="yes"/>
	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<link rel="stylesheet" href="zui/css/zui.min.css"/>
	<link rel="stylesheet" href="zui/css/common.css"/>
	<link rel="stylesheet" href="index.css"/>
</head>
<style>
	body{
		background: url(img/<? echo($topic_type) ?>/game_top.jpg) center top no-repeat,url(img/<? echo($topic_type) ?>/game_bottom.jpg) center bottom no-repeat;
	}
	#musicControl a { display:block;width:30px;height:30px;overflow:hidden;background:url('zui/img/<? echo($topic_type) ?>/mcbg.png') no-repeat;background-size:100%;}
</style>
<body id="divSnow-1">
<div class="m">
	<div class="gamelogo"><img src="img/<?echo($topic_type)?>/title.png" class="wow fadeInDown"></div>
	<div class="gameml"><img src="img/<?echo($topic_type)?>/ml.png"  class="wow bounceInRight"></div>
	<a class="gamean musicAn1" href="game.php?belong=<?echo($belong)?>">
		<h3>进入游戏</h3>
		<img src="zui/img/<?echo($topic_type)?>/an.png">
	</a>
	<a class="gamean musicAn1" data-remote="explain.php" data-toggle="modal" data-title="游戏说明">
		<h3>游戏说明</h3>
		<img src="zui/img/<?echo($topic_type)?>/an.png">
	</a>
	<a class="gamean musicAn1" data-remote="ranking.php?openid=<?=$openid?>&belong=<?=$belong?>" data-toggle="modal" data-title="排行榜">
		<h3>排行榜</h3>
		<img src="zui/img/<?echo($topic_type)?>/an.png">
	</a>
	<a class="gamean musicAn1" href="javascript:void(0)" id="share">
		<h3>找朋友比赛</h3>
		<img src="zui/img/<?echo($topic_type)?>/an.png">
	</a>
	
	<div id="musicControl" class="wow bounceIn" data-wow-delay=".8s">
		<a href="javascript:void(0)" id="mc_play" class="stop" onclick="play_music();">
			<audio id="musicfx" loop>
				<source src="img/<?echo($topic_type)?>/music.mp3" type="audio/mpeg">
			</audio>
		</a>
	</div>
</div>
<div class="game-footer">
	技术支持：示剑网络
</div>
<div class="an-share-wk">
 	<img src="zui/img/<?echo($topic_type)?>/fenxiang.png">
</div>

<div id="musicAn1">
	<audio id="musican1">
		<source src="zui/img/<?echo($topic_type)?>/an1.mp3" type="audio/mpeg">
	</audio>
</div>
<div id="musicAn2">
	<audio id="musican2">
		<source src="zui/img/<?echo($topic_type)?>/an2.mp3" type="audio/mpeg">
	</audio>
</div>
	
<div id="divSnow-1" class="htmleaf-content canvas-1"></div>

<script src="zui/lib/jquery/jquery.js"></script>
<script src="zui/js/zui.min.js"></script>
<script src="zui/js/common.js"></script>
<script src="js/Websnowjq.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#divSnow-1").websnowjq();
	$('#share').click(function() {
		$('.an-share-wk').addClass('active');
	});
	$('.an-share-wk').click(function() {
		$('.an-share-wk').removeClass('active');
	});
});
</script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

	wx.config({
		debug: false, //
		appId:'<?=$appid?>', //{$appid}
		timestamp:'<?=$timestamp?>' ,//{$timestamp}
		nonceStr:'<?=$nonceStr?>',//{$nonceStr}
		signature:'<?=$signature?>',//{$signature}
		jsApiList:['onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
    wx.ready(function(){
//		alert('1');
		//分享给朋友
		wx.onMenuShareAppMessage({
			title:' 快来一起玩耍', //
			desc: '没有描述', //分享描述
			link:'http://happy.test.weikaiqi.com/index.php?belong=<?=$openid?>', // 分享链接
			imgUrl:'img/<?=$topic_type?>/ml.png' , // 分享图标
			type: 'link', // 分享类型,music、video或link，不填默认为link
			success: function () {
				alert('分享成功');
			},
			cancel: function () {
				// 用户取消分享后执行的回调函数
				// alert('取消分享');
			}
		});
		//分享到朋友圈
		wx.onMenuShareTimeline({
			title:' 快来一起玩耍', //
			desc: '没有描述', //分享描述
			link:'http://happy.test.weikaiqi.com/index.php?belong=<?=$openid?>', // 分享链接
			imgUrl:'img/<?=$topic_type?>/ml.png' , // 分享图标
			success: function () {
				// 用户确认分享后执行的回调函数
			},
			cancel: function () {
				// 用户取消分享后执行的回调函数
			}
		});
	});

</script>
</body>
</html>