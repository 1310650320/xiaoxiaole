<?php

class env_sichem{



    static $_config;
    static $_G;

    static $_openid;
    static $_user;
    static $_mid;
    static function init(){

        self::$_config = include __DIR__ .'/config.php';
        self::$_mid = self::$_config['MID'];
        $_G = [];
        self::$_G = &$_G;
        $_G['isHTTPS'] = ($_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS']) != 'off') ? true : false;
        $_G['FULL_URL'] = ('http'.($_G['isHTTPS'] ? 's' : '') .'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        self::inituser( self::$_config['MID'] );
    }

    static function inituser($_mid){

        $openid_auth = self::getcookie( 'openid_'.$_mid );

        if(!self::is_weixin()) return;

        if($openid_auth) {
            $openid_deauth = self::SING_auth($openid_auth, 'DECODE', md5($_SERVER['HTTP_USER_AGENT']));
            $preFix = substr($openid_deauth , 0 , 4);
            if($preFix != '2018'){
                return ;
            }
            $openid = substr($openid_deauth , 4 );
        }else{
            if($_GET['token']){
                $tokenStr = self::SING_auth($_GET['token'], 'DECODE',md5( env_sichem::$_config['USERAUTH_KEY'].$_SERVER['HTTP_USER_AGENT']));
//               var_dump($_SERVER['HTTP_USER_AGENT']);
                list($openid) = explode("\t", $tokenStr);
//                $openid = self::getcookie('BDed_HeaderKey_'.$_mid);
//                var_dump(explode("\t", $tokenStr));
//                exit;
                if($openid){
                    $HeaderKey = self::SING_auth("2018".$openid, 'ENCODE', md5($_SERVER['HTTP_USER_AGENT']));
                    setcookie( 'openid_'.$_mid ,$HeaderKey);
                    $url=self::wc_curPageURL();
                    var_dump($url);
                    header('Location:'.$url);
                    exit;
                }
            }
        }

        if($openid){
            self::$_openid = $openid;

        }

        if(!$openid){
            $url = rtrim( self::$_config['USERAUTH_URL'],'/').'?mid='.$_mid.'&url='.urlencode( self::$_G['FULL_URL'])."&host=". self::$_config['USERAUTH_HOST'];
            //$url = rtrim(SC_WKQ_USER,'/').'/'.$_mid.'?url='.urlencode($_G['FULL_URL']);
            if($_GET['code']) $url .= '&code='.$_GET['code'];
            // echo $url."\n";
            // exit;
            header('Location: ' . $url);
            exit;
        }
    }
//
    static function jssdk(){
         $url=self::curPageURL();
//         $url=urldecode($url);
//        var_dump($url);
//        exit;
        $returnData = self::socketReturn("Weixin/JsSDK/SDK",array("url" =>$url,"mid"=>self::$_config['MID']));
        return array("Error" => 0, "data" => $returnData);

    }
    static function curPageURL()
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
}
   static  function wc_curPageURL()
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

//    获取用户信息
    static function getUser(){

        if(!self::$_user ){
            if(self::$_openid ){
                $userinfo=self::openid2data(self::$_mid,self::$_openid);
//                var_dump($userinfo);
//                exit;
//                未关注用户获取不到信息
                if(empty($userinfo['data']['nickname'])){
                    $userinfo['data']['nickname']='匿名';
                }
                if(empty($userinfo['data']['headimgurl'])){
                    $userinfo['data']['headimgurl']='/headimgurl.jpg';
                }
//
                setcookie('nickname',$userinfo['data']['nickname']);
                setcookie('headimgurl',$userinfo['data']['headimgurl']);
                self::$_user = [
                    'nickname' => $userinfo['data']['nickname'],
                    'headimgurl' => $userinfo['data']['headimgurl']
                ];
            }
        }

        return self::$_user;
    }

    static function SING_auth($txt, $operation = 'ENCODE', $key = '')
    {
        $key	= $key ? $key : $GLOBALS['phpcms_auth_key'];
        $txt	= $operation == 'ENCODE' ? $txt : base64_decode($txt);

        $len	= strlen($key);
        $code	= '';
        for($i=0; $i<strlen($txt); $i++){
            $k		= $i % $len;
            $code  .= $txt[$i] ^ $key[$k];
        }
        $code = $operation == 'DECODE' ? $code : base64_encode($code);
//        var_dump($code);
//        exit;
        return $code;
    }
    /** 根据mid和openid获取会员信息
     * @param $mid
     * @param $openid
     * @return array|mixed
     */
    static function openid2data($mid,$openid){
        $returnData =self::socketReturn("Weixin/User/getUser",array("mid" => $mid, "openid" => $openid));
//        var_dump($returnData);
//        exit;
        if($returnData && $returnData["errCode"] == 0 && $returnData["data"] && $returnData["data"]['subscribe'] == 0){
//            return array('errCode' =>-1, 'msg' => '未关注公众号');

       }
        return $returnData;
    }
    static function socketReturn($url, $arr = array(),$socketNum = 1){
//        var_dump($url);
//        exit;
        $arr['mid'] = $arr['mid'] ? $arr['mid'] : trim(strip_tags($_GET['mid']));

        $btime = microtime(true);
        require_once  __DIR__ .'/socket.class.php';

        $obj = new Socket($socketNum);
        $returnData=$obj->query($url,$arr);
//        var_dump($returnData);
//        exit;
        $etime = microtime(true);

        if($returnData){
            $returnData['askTime'] = array("btime" => $btime,"etime" => $etime);
            return $returnData;
        }else{
            return array('errCode' => 9002, 'msg' => '网络繁忙');
        }
    }

    static function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )	return true;
        return false;
    }

    static function getcookie($key) {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';
    }
}

//
env_sichem::init();
