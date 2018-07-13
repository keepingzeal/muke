<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 
 */
class MsgController extends CommonController
{
    /**
     * 校验接口的有效性
     * @Author   DaYang
     * @DateTime 2018-07-12
     * @version  1.0
     * @return   [type]     [description]
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        // 接口有效性的验证必定会接收到echoStr
        if ($echoStr) {
            // 校验Token 验证接口的有效性
            if ($this->checkSignature()) {
                echo $echoStr;
                exit;
            }
        } else {
            // 接口有效性通过验证
            $this->responseMsg();
        }
    }

    /**
     * 消息回复
     * @Author   DaYang
     * @DateTime 2018-07-12
     * @version  1.0
     * @return   [type]     [description]
     */
    public function responseMsg()
    {
        //获取微信公众平台推送过来的post数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        // 详情解释：微信开发文档（消息管理-->被动回复用户消息）
        // 假如服务器无法保证在五秒内处理并回复，必须做出下述回复
        //   ## 直接回复success（推荐方式）
        //   ## 直接回复空串，指字节长度为0的空字符串，而不是XML结构体中content字段的内容为空
        // 否则，将出现严重的错误提示。详见下面说明：
        //   ## 提示：向用户下发系统提示“该公众号暂时无法提供服务，请稍后再试”
        //   ## 1、开发者在5秒内未回复任何内容
        //   ## 2、开发者回复了异常数据，比如JSON数据等
        if (empty($postStr)) {
            echo 'success';
            exit;
        }

        //提取post数据.
        /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
           the best way is to check the validity of xml by yourself */
        // 校验xml有效性，防止
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $RX_TYPE = trim($postObj->MsgType);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        if(!empty( $keyword ))
        {
            $msgType = "text";
            $contentStr = "Welcome to wechat world!";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            return $resultStr;
        } else {
            echo "Input something...";
        }
    }

    /**
     * 有效性校验
     * @Author   DaYang
     * @DateTime 2018-07-12
     * @version  1.0
     * @return   [type]     [description]
     */
    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = 'weixin';
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
