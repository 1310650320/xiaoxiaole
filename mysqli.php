<?php
	//header ( 'Content-Type:text/html; charset=utf-8;' );
	$_mysqli = new mysqli('localhost','root','root','duiduipen');
	if(mysqli_connect_errno()){
	   echo '数据库连接出现了错误，错误的信息是：'.mysqli_connect_error();
	   exit();
	}

	$_mysqli -> set_charset('utf8');