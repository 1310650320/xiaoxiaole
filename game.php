<?phprequire_once 'env_sichem/init.php';require_once 'function.php';require_once 'config.php';$topic_type = $topic['type'];$topic_name = $topic['name'];$openid=env_sichem::$_openid;$user = env_sichem::getUser();//var_dump($user);//exit;$wx_nickname= $user['nickname'];$wx_headimgurl= $user['headimgurl'];$wx_sdk= env_sichem::jssdk();$wx_sdk=$wx_sdk['data']['data']['config'];$appid=$wx_sdk['appId'];$timestamp=$wx_sdk['timestamp'];$nonceStr= $wx_sdk['nonceStr'];$signature=$wx_sdk['signature'];if(empty($_GET["belong"])){	$belong='0';}else{	$belong=$_GET["belong"];}if(empty($openid)){	// require_once 'oauth.php';}else{	require_once 'mysqli.php';	//查询人数；$total['total']	$sql = "select heart from games_sdddp where openid = '$openid'";	$res = $_mysqli->query($sql);	$total = $res -> fetch_assoc();	if(empty($total['heart'])){		$wx_heart = '5';	}else{		$wx_heart = $total['heart'];	}	$_mysqli->close();}?><!DOCTYPE html><html><head>	<meta charset="UTF-8">	<meta name="viewport" content="target-densitydpi=device-dpi,width=520,user-scalable=no">	<title><?echo($topic_name)?></title>    <!-- 默认极速核心 -->    <meta name="renderer" content="webkit"/>    <meta http-equiv="Cache-Control" content="no-siteapp"/>	<!-- Add to homescreen for Chrome on Android -->	<meta name="mobile-web-app-capable" content="yes"/>	<!-- Add to homescreen for Safari on iOS -->	<meta name="apple-mobile-web-app-capable" content="yes"/>	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>	<link rel="stylesheet" href="zui/css/zui.min.css"/>	<link rel="stylesheet" href="zui/css/common.css"/>	<link rel="stylesheet" href="style.css"/></head><style>	body{		background: url(img/<? echo($topic_type) ?>/game_top.jpg) center top no-repeat,url(img/<? echo($topic_type) ?>/game_bottom.jpg) center bottom no-repeat;	}	#grid .tile.type-1 {		background:url(img/<? echo($topic_type) ?>/an_01.png);		background-size:cover;	}	#grid .tile.type-2 {		background:url(img/<? echo($topic_type) ?>/an_02.png);		background-size:cover;	}	#grid .tile.type-3 {		background:url(img/<? echo($topic_type) ?>/an_03.png);		background-size:cover;	}	#grid .tile.type-4 {		background:url(img/<? echo($topic_type) ?>/an_04.png);		background-size:cover;	}	#grid .tile.type-all {		background:url(img/<? echo($topic_type) ?>/an_05.png);		background-size:cover;	}</style><body id="divSnow-1"><div id="stage">	<div class="lipin">		<span id="score">0</span>		<img src="img/<? echo($topic_type) ?>/lipin.png">	</div>	<div class="progress progress-striped active" id="time">		<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">		</div>		<div id="progress-text"></div>		<img src="img/<? echo($topic_type) ?>/nl.png">	</div>	<div id="grid"></div></div><div class="layer" id="startLayer">	<div class="layer-ul wow fadeInDown">		<h3><img src="img/<? echo($topic_type) ?>/xin.png"> 可玩次数：<span><?=$wx_heart?></span></h3>		<h4>分享给好友，朋友参与游戏即可多获取一次游戏机会！</h4>		<button type="button" class="btn btn-danger musicAn2" id="btnStart">开始挑战</button>&nbsp;&nbsp;		<button type="button" id="share" class="btn btn-warning musicAn1">分享好友</button>	</div>	<img src="img/<? echo($topic_type) ?>/tc.png" class="wow fadeInDown"></div><div class="layer" id="overLayer">	<div class="layer-ul wow fadeInDown">		<h3><img src="img/<? echo($topic_type) ?>/xin.png"> 可玩次数：<span id="startHeart"><?=$wx_heart?></span></h3>		<h4>分享给好友，朋友参与游戏即可多获取一次游戏机会！</h4>		<button type="button" class="btn btn-danger musicAn2" id="btnAgain">再来一次</button>&nbsp;&nbsp;		<button type="button" id="share" class="btn btn-warning musicAn1">分享好友</button>	</div>	<img src="img/<? echo($topic_type) ?>/tc.png" class="wow fadeInDown"></div>	<div class="an-share-wk"> 	<img src="zui/img/<? echo($topic_type) ?>/fenxiang.png"></div><div class="an-victory-wk"> 	<div align="right"><img src="zui/img/<? echo($topic_type) ?>/fenxiang.png"></div>	<div class="an-victory-bt"><img src="zui/img/<? echo($topic_type) ?>/success.png"></div>	<div class="an-victory-tx"><img src="<?=$wx_headimgurl?>"></div>	<div class="an-victory-wz">		<h3>恭喜超过自己最好成绩</h3>		<p>当前得分，当前排名</p>		<a href="index.php" class="btn btn-danger break" type="button">确定</a>	</div>	<div class="an-victory-sg"><img src="zui/img/<? echo($topic_type) ?>/light.png"></div></div><div class="fanhui">	<a href="index.php" class="wow bounceIn" data-wow-delay="1s"><img src="img/<? echo($topic_type) ?>/fh.png"></a></div><div id="musicControl" class="wow bounceIn" data-wow-delay=".8s">	<a href="javascript:void(0)" id="mc_play" class="stop" onclick="play_music();">		<audio id="musicfx" loop>			<source src="img/<? echo($topic_type) ?>/game.mp3" type="audio/mpeg">		</audio>	</a></div><div id="musicAn1">	<audio id="musican1">		<source src="zui/img/<? echo($topic_type) ?>/an1.mp3" type="audio/mpeg">	</audio></div><div id="musicAn2">	<audio id="musican2">		<source src="zui/img/<? echo($topic_type) ?>/an2.mp3" type="audio/mpeg">	</audio></div><div style="display: none;">	<audio id="music_move">		<source src="img/<? echo($topic_type) ?>/yidong.mp3" type="audio/mpeg">	</audio>	<audio id="music_error">		<source src="img/<? echo($topic_type) ?>/cuowu.mp3" type="audio/mpeg">	</audio>	<audio id="music_remove">		<source src="img/<? echo($topic_type) ?>/xiaochu.mp3" type="audio/mpeg">	</audio></div>	<div id="divSnow-1" class="htmleaf-content canvas-1"></div><script src="zui/lib/jquery/jquery.js"></script><script src="zui/js/zui.min.js"></script><script src="zui/js/common.js"></script><script src="js/Websnowjq.js"></script><script src="js/hammer.min.js"></script><script src="js/app.js"></script><script type="text/javascript">$(document).ready(function() {	$("#divSnow-1").websnowjq();	$('#share').click(function() {		$('.an-share-wk').addClass('active');	});	$('.an-share-wk').click(function() {		$('.an-share-wk').removeClass('active');	});	$("#btnStart").click(function() {		$.ajax({		  type:'post',		  url:'ajax.php',		  data:{'type':'2','openid':'<?=$openid?>','nickname':'<?=$wx_nickname?>','headimgurl':'<?=$wx_headimgurl?>','belong':'<?=$belong?>'},          dataType: 'json',		  success:function(data){			  if (data.type==2){				new $.zui.Messager(data.info, {				type: 'danger',				placement: 'center'				}).show();				$("#startHeart").html(data.heart);				$("#startLayer").hide();				DDP.play(true);				DDP.cleanOut();			  }else{				alert(data.info);				window.location.href="index.php";			  }		  }		});	});	$("#btnAgain").click(function() {		$.ajax({		  type:'post',		  url:'ajax.php',		  data:{'type':'2','openid':'<?=$openid?>','nickname':'<?=$wx_nickname?>','headimgurl':'<?=$wx_headimgurl?>','belong':'<?=$belong?>'},          dataType: 'json',		  success:function(data){			  if (data.type==2){				new $.zui.Messager(data.info, {				type: 'danger',				placement: 'center'				}).show();				$("#startHeart").html(data.heart);				$("#overLayer").hide();				DDP.play();			  }else{				alert(data.info);				window.location.href="index.php";			  }		  }		});	});});function wx_game_start(){//开始};function wx_game_over(e){//结束	var score = e;	$.ajax({	  type:'post',	  url:'ajax.php',	  data:{'type':'1','score':score,'openid':'<?=$openid?>','nickname':'<?=$wx_nickname?>','headimgurl':'<?=$wx_headimgurl?>'},	  dataType: 'json',	  success:function(data){		  if (data.type==1){			$('.an-victory-wz p').html('当前得分'+data.score+'，当前排名'+data.ranking);			$('.an-victory-wk').addClass('active');		  }else{			new $.zui.Messager(data.info, {			placement: 'center'			}).show();		  }	  }	});};function wx_game_move(){//移动	$('#music_move').get(0).play();};function wx_game_error(){//错误	$('#music_error').get(0).play();};function wx_game_remove(){//消除	$('#music_remove').get(0).play();};</script><script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script><script>	wx.config({		debug: false, //		appId:'<?=$appid?>', //{$appid}		timestamp:'<?=$timestamp?>' ,//{$timestamp}		nonceStr:'<?=$nonceStr?>',//{$nonceStr}		signature:'<?=$signature?>',//{$signature}		jsApiList:['onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2	});	wx.ready(function(){//		alert('1');		//分享给朋友		wx.onMenuShareAppMessage({			title:'快来一起玩耍', //			desc: '没有描述', //分享描述			link:'http://happy.test.weikaiqi.com/index.php?belong=<?=$openid?>', // 分享链接			imgUrl:'img/<?=$topic_type?>/ml.png' , // 分享图标			type: 'link', // 分享类型,music、video或link，不填默认为link			success: function () {				alert('分享成功');			},			cancel: function () {				// 用户取消分享后执行的回调函数				 alert('取消分享');			}		});		//分享到朋友圈		wx.onMenuShareTimeline({			title:'快来一起玩耍', //			desc: '没有描述', //分享描述			link:'http://happy.test.weikaiqi.com/index.php?belong=<?=$openid?>', // 分享链接			imgUrl:'img/<?=$topic_type?>/ml.png' , // 分享图标			success: function () {				alert('分享成功');// 用户确认分享后执行的回调函数			},			cancel: function () {				// 用户取消分享后执行的回调函数			}		});	});</script></body></html>