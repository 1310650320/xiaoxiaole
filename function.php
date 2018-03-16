<?php
function input($data=NULL){

	$input = $_SERVER['REQUEST_METHOD'];
	
	if(!empty($data)){ // 获取指定值
		if($input == 'POST'){
			return inputFilter($_POST[$data]);
		}else{
			return inputFilter($_GET[$data]);
		}
	}else{ //获取所有值
		if($input == 'POST'){
			foreach($_POST as $k=>$v){
				$_POST[$k] = inputFilter($v);
			}
			return $_POST;
		}else{
			foreach($_GET as $k=>$v){
				$_GET[$k] = inputFilter($v);
			}
			return $_GET;
		}
	}
}


function inputFilter($content){

	if(is_string($content) ){ 
		return check_safe($content); 
	} 
	elseif(is_array($content)){ 
		foreach ($content as $key => $val ) { 
			$content[$key] = check_safe($val); 
		} 
		return $content; 
	} 
	elseif(is_object($content)) { 
		$vars = get_object_vars($content); 
		foreach($vars as $key=>$val) { 
			$content->$key = check_safe($val); 
		} 
		return $content; 
	} 
	else{ 
		return $content; 
	}
}


function check_safe($content){

	
	if(!get_magic_quotes_gpc()){	
		$content = addslashes($content);  //数据库
	}
	$content = htmlspecialchars($content,ENT_QUOTES);
	//$content = str_replace("_", "\_", $content);    // 把 '_'过滤掉    
  	//$content = str_replace("%", "\%", $content);    // 把 '%'过滤掉    
  	//$content = nl2br($content);    // 回车转换    
  	//$content = str_replace('%20','',$content);
    //$content = str_replace('%27','',$content);
   //  $content = str_replace('%2527','',$content);
   //  $content = str_replace('*','',$content);
   //  $content = str_replace('"','&quot;',$content);
     //$content = str_replace("'",'',$content);
     //$content = str_replace('"','',$content);
     //$content = str_replace(';','',$content);
     //$content = str_replace('<','&lt;',$content);
     //$content = str_replace('>','&gt;',$content);
     //$content = str_replace("{",'',$content);
     //$content = str_replace('}','',$content);
   //  $content = str_replace('\\','',$content);
  	
  	return $content;
}


/*
	* 检测字符串长度是否在mix-max区间；
	* 
	* @access public 
	* @param $str  string 要洁厕的字符串  
	* @param $mix  int 最小长度 
	* @param $max  int 最大长度 
	* @return int 返回类型 true:正常；0：过短；2：过长; 3:检测对象不是字符串
						   4: 长度参数不是数字类型 
*/
function check_string_length($str,$mix,$max){

	if(!is_string($str)){
		return false;
	}
	
	if(!is_numeric($mix) or !is_numeric($max)){
		return false;
	}
	
	$length = strlen($str);
	
	if(strlen($str) < $mix){
		return false;
	}
	
	if(strlen($str) > $max){
		return false;
	}
	
	if($length<=$max and $length>=$mix){
		return true;
	}
}


  /**
     * @desc arraySort php二维数组排序 按照指定的key 对数组进行排序
     * @param array $arr 将要排序的数组
     * @param string $keys 指定排序的key
     * @param string $type 排序类型 asc | desc
     * @return array
     */
function arraySort($arr, $keys, $type = 'asc') { //二维数组排序
		if(empty($arr)){
			return false;
		}
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v){
            $keysvalue[$k] = $v[$keys];
        }
        $type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
           $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }

/*
二维数组 多字段排序

$list = sortByCols($list, array(  
    'parent' => SORT_ASC,   
    'value' => SORT_DESC,  
)); 
$list 数组
$field 字段
*/
function sortByCols($list,$field){  
    $sort_arr=array();  
    $sort_rule='';  
    foreach($field as $sort_field=>$sort_way){  
        foreach($list as $key=>$val){  
            $sort_arr[$sort_field][$key]=$val[$sort_field];  
        }  
        $sort_rule .= '$sort_arr["' . $sort_field . '"],'.$sort_way.',';  
    }  
    if(empty($sort_arr)||empty($sort_rule)){ return $list; }  
    eval('array_multisort('.$sort_rule.' $list);');//array_multisort($sort_arr['parent'], 4, $sort_arr['value'], 3, $list);  
    return $list;  
} 
	
	
//  对象转数组

function object_array($array)
{
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return $array;
}

// 检测date("Y-m-d H:i:s") 格式的日期是否正确
function check_Datetime($str, $format = "Y-m-d H:i:s"){
    $time = strtotime($str);  //转换为时间戳
    $checkstr = date($format, $time); //在转换为时间格式
    if($str == $checkstr){
        return true;
    }else{
        return false;
    }
}


//检测手机号格式 -----------------------------------------------------
function shoujihao_check($tel,$returnType=NULL){ 
	$isMob="/^1[3-8]{1}[0-9]{9}$/";
	$isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";
	
		if(!preg_match($isMob,$tel) && !preg_match($isTel,$tel))
		{
			if(empty($returnType)){
			    echo "'手机号码格式不正确'";
			    exit;
			}else{
				return false;
			}
		}else{
			return true;
		}
	
}

// 提示
function js_alert_href($tishi,$URL){
	echo "<script language='javascript'>alert('".$tishi."');location.href='".(''.$URL.'')."';</script>"; 
}

function js_alert_back($tishi){
	echo "<script language='javascript'>alert('".$tishi."');history.back();</script>"; 
}

// 微信浏览器
function is_weixin(){ 
	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false and strpos($_SERVER['HTTP_USER_AGENT'], ' QQBrowser') == false ) {
			return true;
	}	

	return false;
}


//  获取当前页面完整URL 包括URL
function get_thisPageUrl() 
{
  $pageURL = 'http';
 

  $pageURL .= "://";
 
  if ($_SERVER["SERVER_PORT"] != "80") 
  {
    $pageURL .= $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"];
  } 
  else
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}

// 获取域名
function getDomain(){
$pageURL = 'http';
 

  $pageURL .= "://";
  $pageURL .= $_SERVER["SERVER_NAME"];
 
  return $pageURL;
}



//  获取完整的URL，包括参数
/* function get_thisPageUrl()
{
  $pageURL = 'http';
 
  if ($_SERVER["HTTPS"] == "on") 
  {
    $pageURL .= "s";
  }
  $pageURL .= "://";
 
  if ($_SERVER["SERVER_PORT"] != "80") 
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  } 
  else
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
} */


//  获取没有参数的URL
function get_thisPageUrl_1()
{
  $pageURL = 'http';
 
  if ($_SERVER["HTTPS"] == "on") 
  {
    $pageURL .= "s";
  }
  $pageURL .= "://";
 
  $this_page = $_SERVER["REQUEST_URI"];
   
  // 只取 ? 前面的内容
  if (strpos($this_page, "?") !== false)
  {
    $this_pages = explode("?", $this_page);
    $this_page = reset($this_pages);
  }
 
  if ($_SERVER["SERVER_PORT"] != "80") 
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $this_page;
  } 
  else
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . $this_page;
  }
  return $pageURL;
}

function getIP()
{
	global $ip;
	if (getenv("HTTP_CLIENT_IP"))
	$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
	$ip = getenv("REMOTE_ADDR");
	else $ip = "Unknow";
	return $ip;
}

?>