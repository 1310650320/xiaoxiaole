<?php
require_once 'mysqli.php';
$openid = $_GET['openid'];
$belong = $_GET['belong'];
if(empty($openid)){
	$_mysqli->close();
	echo "参数错误，请刷新后重试";
	exit();
}

// 自己 

$sql = "select nickname,headimgurl,score,addtime from games_sdddp where belong='$openid' or openid='$openid' order by score desc,addtime asc";
$res = $_mysqli->query($sql);
if(!empty($res)){
	while($row = $res->fetch_assoc()){
		$mes[] = $row;
	}
}else{
	$mes[0]='';
}


if(!empty($belong)){
	// 好友 
	$sql = "select nickname,headimgurl,score,addtime from games_sdddp where belong = '$belong' order by score desc,addtime asc where";
	$res = $_mysqli->query($sql);
	if(!empty($res)){
		while($row = $res->fetch_assoc()){
			$friends[] = $row;
		}
	}else{
		$friends[0]='';
	}

}

// 排行 
$sql = "select nickname,headimgurl,score,addtime from games_sdddp order by score desc,addtime asc LIMIT 20";
$res = $_mysqli->query($sql);
if(!empty($res)){
	while($row = $res->fetch_assoc()){
		$rankings[] = $row;
	}
}else{
	$rankings[0]='';
}


$_mysqli->close();
?>
<div class="paihang" style="position: relative">
	<ul class="nav nav-tabs">
		<li class="active"><a href="###" data-target="#tab2Content1" data-toggle="tab">我邀请的好友</a></li>
		<?php
		if(!empty($belong)){
		?>
		<li><a href="###" data-target="#tab2Content2" data-toggle="tab">好友排行</a></li>
		<?php
		}
		?>
		<li><a href="###" data-target="#tab2Content3" data-toggle="tab">总排行</a></li>
	</ul>
	<div class="tab-content">
	  <div class="tab-pane fade active in" id="tab2Content1">
		<ul class="clearfix">
			<?php
			$i = 0;
			foreach($mes as $k=>$v){
				$i++;
			?>
				<li data-toggle='tooltip' data-placement='top' title="<?=$v['addtime']?>">
					<span><img src="<?=$v['headimgurl']?>"></span>
					<strong><?=$v['nickname']?></strong> 闯关<b><?=$v['score']?></b> 排名<b><?=$i?></b>
					<i class='icon icon-time'></i>
				</li>
			<?php
			}
			?>
		</ul>
	  </div>
	<?php
	if(!empty($belong)){
	?>
	  <div class="tab-pane fade" id="tab2Content2">
		<ul class="clearfix">
			<?php
			$i = 0;
			foreach($friends as $k=>$v){
				$i++;
			?>
				<li data-toggle='tooltip' data-placement='top' title="<?=$v['addtime']?>">
					<span><img src="<?=$v['headimgurl']?>"></span>
					<strong><?=$v['nickname']?></strong> 闯关<b><?=$v['score']?></b> 排名<b><?=$i?></b>
					<i class='icon icon-time'></i>
				</li>
			<?php
			}
			?>
		</ul>
	  </div>
	<?php
	}
	?>
	  <div class="tab-pane fade" id="tab2Content3">
		<ul class="clearfix">
			<?php
			$i = 0;
			foreach($rankings as $k=>$v){
				$i++;
			?>
				<li data-toggle='tooltip' data-placement='top' title="<?=$v['addtime']?>">
					<span><img src="<?=$v['headimgurl']?>"></span>
					<strong><?=$v['nickname']?></strong> 闯关<b><?=$v['score']?></b> 排名<b><?=$i?></b>
					<i class='icon icon-time'></i>
				</li>
			<?php
			}
			?>
		</ul>
	  </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
});
</script>