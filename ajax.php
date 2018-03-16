<?php
require_once "mysqli.php";
$type = $_POST['type'];
//type==1游戏结束type==2再来一次（开始）
if($type==1){
	$openid = $_POST['openid'];
	$nickname = $_POST['nickname'];
	$headimgurl = $_POST['headimgurl'];
	$score = $_POST['score'];
	//查询当前排名；$total['total']
	$sql = "select count(id) as total from games_sdddp 
	where score >= '$score'";
	$res = $_mysqli->query($sql);
	$total = $res -> fetch_assoc();
	//$ranking==当前排名
	$ranking = $total['total']+1;
	//查询数据库历史记录；
	$sql = "select score,count from games_sdddp 
	where openid = '$openid'";
	$res = $_mysqli->query($sql);
	$total = $res -> fetch_assoc();
	$count = $total['count']+1;
//	第一次进入游戏
	if(empty($total['score'])){
		$sql = "insert into games_sdddp (openid,nickname,headimgurl,score,count) VALUE
		('$openid','$nickname','$headimgurl','$score','1')";
		$res = $_mysqli->query($sql);
		$type = '1';
	}elseif($total['score']<$score){
//		更新分数
		$sql = "update games_sdddp set score='$score',nickname='$nickname',headimgurl='$headimgurl',count='$count' where openid = '$openid'";
		$res = $_mysqli->query($sql);
		$type = '1';
	}else{
//		更新次数
		$sql = "update games_sdddp set score='$score',nickname='$nickname',headimgurl='$headimgurl',count='$count' where openid = '$openid'";
//		$sql = "update games_sdddp set count='$count' where openid = '$openid'";
		$res = $_mysqli->query($sql);
		$type = '0';
		$_mysqli->close();
		$json_arr = array("type"=>$type,"info"=>"游戏结束");
		$json_obj = json_encode($json_arr);
		echo $json_obj;
		exit();
	}
	$_mysqli->close();
	if(!empty($res)){
		$json_arr = array("type"=>$type,"score"=>$score,"ranking"=>$ranking);
	}else{
		$json_arr = array("info"=>"数据库操作失败，请重试");		
	}
}elseif($type==2){
	$openid = $_POST['openid'];
	$nickname = $_POST['nickname'];
	$headimgurl = $_POST['headimgurl'];
	$belong = $_POST['belong'];
	$sql = "select score,heart,belong from games_sdddp where openid = '$openid'";
	$res = $_mysqli->query($sql);
	$total = $res -> fetch_assoc();
	if(empty($total['score'])){
//		$belong==链接分享源
		$sql = "insert into games_sdddp (openid,nickname,headimgurl,score,heart,count) VALUE
		('$openid','$nickname','$headimgurl','1','5','0')";
		$res = $_mysqli->query($sql);
		$heart2 = 5;
	}else{
		$heart2 = $total['heart'];
	}
//	判断该分享是否有效
	if(empty($total['belong'])||$total['belong']=='0'){
//		更新当前用户信息
		$sql = "update games_sdddp set belong='$belong' where openid = '$openid'";
		$res = $_mysqli->query($sql);
//		赠予分享人次数
//		var_dump($res);
//		exit;
		$sql = "update games_sdddp set heart=heart+1  where openid = '$belong'";
//		var_dump($sql);
//		exit;
		$res = $_mysqli->query($sql);

	}
	if($heart2>0){
		$heart2 = $heart2-1;
		$sql = "update games_sdddp set heart='$heart2' where openid = '$openid'";
		$res = $_mysqli->query($sql);
		$json_arr = array("type"=>2,"heart"=>$heart2,"info"=>"游戏开始：<i class='icon icon-heart'></i> 可玩次数-1");
	}else{
		$json_arr = array("info"=>"可玩次数用完，分享给好友赚取次数~");
	}
	$_mysqli->close();
}else{
	$json_arr = array("info"=>"抱歉，参数有丢失~");
}
	$json_obj = json_encode($json_arr);
	echo $json_obj;
?>