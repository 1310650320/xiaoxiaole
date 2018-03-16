<?php
// echo "1";
// exit;
class Socket
{
    private $config1;
    private static $asynclist;
    private static $client;
    private $config;
    private $connectIp;
    private $connectPort;


    function __construct($config)
    {
//       echo '2';
//        exit;
        global $_G;
        global $_G;
//        $this->config = $config;
        // Sing_Packet::init($config);
        $this->config['timeout'] = 3;
        $this->config['SW_DATACOMPRESS_FLAG'] = true;
        $this->config['SW_DATASIGEN_FLAG'] = true;
        $this->config['SW_DATASIGEN_SALT'] = '=&$*#@(*&%(@';
        $this->connectIp ='121.40.176.204';// $config['host'];
        $this->connectPort = '8999';//$config['port'];
        require_once  __DIR__ .'/packet.class.php';

        packet::init($this->config);
    }

    function query($module = '', $param = array())
    {
        $data = $this->singleAPI($module, $param, 1);
//        var_dump($data);
//        exit;
        if ($data['code'] == 0) {
            return $data['result'];
        } else {
            throw new \Exception('code：' . $data['code']);
            //return [];
        }
    }

    //get current client
    private function getClientObj()
    {
        //config obj key
        $key = "";
        //using spec
        $clientKey = trim($this->connectIp) . "_" . trim($this->connectPort);
        //set the current client key
        $this->currentClientKey = $clientKey;
        $connectHost = $this->connectIp;
        $connectPort = $this->connectPort;

        if (!isset(self::$client[$clientKey])) {

            $client = new \swoole_client(SWOOLE_SOCK_TCP);
            $client->set(array(
                'open_length_check' => 1,
                'package_length_type' => 'N',
                'package_length_offset' => 0,
                'package_body_offset' => 4,
                'package_max_length' => 1024 * 1024 * 2,
                'open_tcp_nodelay' => 1,
                'socket_buffer_size' => 1024 * 1024 * 4,
            ));

            if (!$client->connect($connectHost, $connectPort, $this->config['timeout'])) {
//                 echo '1';
//                exit;
                //connect fail
                $errorCode = $client->errCode;
//                var_dump($errorCode);
//                exit;
                if ($errorCode == 0) {
                    $msg = "connect fail.check host dns.";
                    $errorCode = -1;
                } else {
                    $msg = \socket_strerror($errorCode);
                }

                if ($key !== "") {
                    //put the fail connect config to block list
                    $this->serverConfigBlock[$this->connectGroup][$key] = 1;
                }

                throw new \Exception($msg . " " . $clientKey, $errorCode);
            }

            self::$client[$clientKey] = $client;
        }

        //success
        return self::$client[$clientKey];
    }

    /**
     * 单api请求
     * @param  string $name api地址
     * @param  array $param 参数
     * @param  int $mode
     * @param  int $retry 通讯错误时重试次数
     * @param  string $ip 要连得ip地址，如果不指定从现有配置随机个
     * @param  string $port 要连得port地址，如果不指定从现有配置找一个
     * @return mixed  返回单个请求结果
     * @throws \Exception unknow mode type
     */
    public function singleAPI($name, $param = array(), $mode = 0, $retry = 0, $ip = "", $port = "")
    {
        //get guid
        $this->guid = $this->generateGuid();

        $packet = array(
            'path_info' => $name,
            'request_method' => '',
            'param' => $param,
            'guid' => $this->guid,
        );

        switch ($mode) {
            case 0:
                $packet["type"] = 0;
                break;
            case 1:
                $packet["type"] = 1;
                break;
            //case DoraConst::SW_MODE_ASYNCRESULT:
            //$packet["type"] = DoraConst::SW_MODE_ASYNCRESULT_SINGLE;
            //break;
            //这种模式以后再做开发
            default:
                throw new \Exception("unknow mode have been set", 100099);
                break;
        }

//       $obj=new Packet();
        $sendData = packet::packEncode($packet);
//        var_dump($packet);
        $result = $this->doRequest($sendData, $packet["type"]);
//        var_dump($result);
//        exit;
        //retry when the send fail
        while ((!isset($result["code"]) || $result["code"] != 0) && $retry > 0) {
            $result = $this->doRequest($sendData, $packet["type"]);
            $retry--;
        }

        if ($this->guid != $result["guid"]) {
            return array('code' => 100100, 'msg' => 'guid wront please retry..');
        }
        if (!empty($result['data']['guid'])) unset($result['data']['guid']);
        return $result['data'];
    }

    private function doRequest($sendData, $type)
    {
        //get client obj
        try {
            $client = $this->getClientObj();
//            var_dump($client);
//            exit;
        } catch (\Exception $e) {
            SING_error::exception_error($e);
            $data = packet::packFormat($this->guid, $e->getMessage(), $e->getCode());
            return $data;
        }

        $ret = $client->send($sendData);
//        var_dump($ret);
//        exit;
        //ok fail
        if (!$ret) {
//            var_dump($ret);
//            exit;
            $errorcode = $client->errCode;

            //destroy error client obj to make reconncet
            self::$client[$this->currentClientKey]->close(true);
            unset(self::$client[$this->currentClientKey]);
            // mark the current connection cannot be used, try another channel
            $this->serverConfigBlock[$this->connectGroup][$this->currentClientKey] = 1;

            if ($errorcode == 0) {
                $msg = "connect fail.check host dns.";
                $errorcode = -1;
                $packet = Sing_Packet::packFormat($this->guid, $msg, $errorcode);
            } else {
                $msg = \socket_strerror($errorcode);
                $packet = Sing_Packet::packFormat($this->guid, $msg, $errorcode);
            }

            return $packet;
        }

        //if the type is async result will record the guid and client handle
        //if ($type == DoraConst::SW_MODE_ASYNCRESULT_MULTI || $type == DoraConst::SW_MODE_ASYNCRESULT_SINGLE) {
        //    self::$asynclist[$this->guid] = $client;
        //}
//      var_dump($client);
//        exit;
        //recive the response
        $data = $this->waitResult($client);

        $data["guid"] = $this->guid;
        return $data;
    }

    //for the loop find the right result
    //save the async result to the asyncresult static var
    //return the right guid request
    private function waitResult($client)
    {
        while (1) {

            $result = @$client->recv();
//            var_dump($result);
//            exit;
            if ($result !== false && $result != "") {

                $data = packet::packDecode($result);
                //if the async result first deploy success will
                if ($data["data"]["guid"] != $this->guid) {
                    // this data was not we want
                    //it's may the async result
                    //when the guid on the asynclist and have isresult =1  on data is async result
                    //when the guid on the asynclist not have isresult field ond data is first success deploy msg

                    if (isset(self::$asynclist[$data["data"]["guid"]]) && isset($data["data"]["isresult"]) && $data["data"]["isresult"] == 1) {

                        //ok recive an async result
                        //remove the guid on the asynclist
                        unset(self::$asynclist[$data["data"]["guid"]]);

                        //add result to async result
                        self::$asynresult[$data["data"]["guid"]] = $data["data"];
                        self::$asynresult[$data["data"]["guid"]]["fromwait"] = 1;
                    } else {
                        //not in the asynclist drop this packet
                        continue;
                    }
                } else {
                    //founded right data
                    return $data;
                }
            } else {
                //time out
                $packet = packet::packFormat($this->guid, "the recive wrong or timeout", 100009);
//                var_dump($packet);
//                exit;
                return $packet;
            }
        }
    }


    private function generateGuid()
    {
        //to make sure the guid is unique for the async result
        while (1) {
            $guid = md5(microtime(true) . mt_rand(1, 1000000) . mt_rand(1, 1000000));
            //prevent the guid on the async list
            if (!isset(self::$asynclist[$guid])) {
                return $guid;
            }
        }
    }
}
?>