<?php
//
// 响应用户消息
// 微信公众账号响应给用户的不同消息类型
//
header('Content-type:text');
define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
  $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()    //获取用户消息内容，并对该消息类型进行判断
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            //用户发送的消息类型判断
            switch ($RX_TYPE)
            {
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                default:
                    $result = "unknow msgs type: ".$RX_TYPE;
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    
    private function receiveText($object)      
    {
     
        $keyword = trim($object->Content);
        if($keyword == "授权"){              //对关键词的判断，通过关键词确定所需回复消息的类型
            //回复文本消息
            $content = "xxxx <a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7f6da6770478867b&redirect_uri=http://1.kzeale.applinzi.com/oauth2.php&response_type=code&scope=snsapi_userinfo&state=state=1#wechat_redirect\">oauth授权</a>";          //构建回复的内容
            $result = $this->transmitText($object, $content);     //调用相关的XML格式化
        }
       
        return $result;
    }
    
    /*
     * 回复文本消息
     */
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    


}
?>