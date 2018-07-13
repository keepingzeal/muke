<?php
/**
 * 微信公众平台-翻译功能源代码
 * ================================

 */

//define your token
header('Content-type:text');
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();   //将wechatCallbackapiTest 类实例化 
if (isset($_GET['echostr'])) {
    $wechatObj->valid();   //使用-》访问类中valid方法，用来验证开发模式 
}else{
    $wechatObj->responseMsg();
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

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
              
            //   提取发送与接收方
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
               
               
            //提取所接收到的文本信息
            $keyword = trim($postObj->Content);
                $time = time();
               
            //文本形式的XML格式包
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				
             //翻译内容非空
            if(!empty( $keyword ))
                {
              		$msgType = "text";

					$str_trans = mb_substr($keyword,0,2,"UTF-8");   //从字符串第一位开始到第三位结束
					$str_valid = mb_substr($keyword,0,-2,"UTF-8");  //从字符串倒数第0个字符开始到倒数第三个
					if($str_trans == '翻译' && !empty($str_valid)){
				
						$word = mb_substr($keyword,2,202,"UTF-8");
						//调用有道词典
						$contentStr = $this->youdaoDic($word);
						
						//调用百度词典
						//$contentStr = $this->baiduDic($word)."【百度】";

					}else {
						$contentStr = "感谢您关注【K2001J的接口测试号】"."\n"."HAVE FUN！！";
					}
					
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }

	public function youdaoDic($word){

		$keyfrom = "k2001j";	//申请APIKEY时所填表的网站名称的内容
		$apikey = "1183994368";  //从有道申请的APIKEY
		
		//有道翻译-xml格式
		$url_youdao = 'http://fanyi.youdao.com/fanyiapi.do?keyfrom='.$keyfrom.'&key='.$apikey.'&type=data&doctype=xml&version=1.1&q='.$word;
		
		//把 XML 文档载入对象中
        $xmlStyle = simplexml_load_file($url_youdao);
		
		//提取错误提示
        $errorCode = $xmlStyle->errorCode;
		
        //提取翻译的结果
		$paras = $xmlStyle->translation->paragraph;

		if($errorCode == 0){
			return $paras;
		}else{
			return "无法进行有效的翻译";
		}
		
		
		
		//有道翻译-json格式
		$url_youdao = 'http://fanyi.youdao.com/fanyiapi.do?keyfrom='.$keyfrom.'&key='.$apikey.'&type=data&doctype=json&version=1.1&q='.$word;
		
		$jsonStyle = file_get_contents($url_youdao);   //file_get_contents() 函数把整个文件读入一个字符串中

		$result = json_decode($jsonStyle,true);
		
		$errorCode = $result['errorCode'];
		
		$trans = '';

		if(isset($errorCode)){

			switch ($errorCode){
				case 0:
					$trans = $result['translation']['0'];
					break;
				case 20:
					$trans = '要翻译的文本过长';
					break;
				case 30:
					$trans = '无法进行有效的翻译';
					break;
				case 40:
					$trans = '不支持的语言类型';
					break;
				case 50:
					$trans = '无效的key';
					break;
				default:
					$trans = '出现异常';
					break;
			}
		}
		return $trans;
		
	}

	/*/百度翻译
	public function baiduDic($word,$from="auto",$to="auto"){
		
		//首先对要翻译的文字进行 urlencode 处理
		$word_code=urlencode($word);
		
		//注册的API Key
		$appid="O1IyaDAfnLPAIemNuG9kSdwq";
		
		//生成翻译API的URL GET地址
		$baidu_url = "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=".$appid."&q=".$word_code."&from=".$from."&to=".$to;
		
		$text=json_decode($this->language_text($baidu_url));

		$text = $text->trans_result;

		return $text[0]->dst;
	}
		
	//百度翻译-获取目标URL所打印的内容
	public function language_text($url){

		if(!function_exists('file_get_contents')){

			$file_contents = file_get_contents($url);

		}else{
				
			//初始化一个cURL对象
			$ch = curl_init();

			$timeout = 5;

			//设置需要抓取的URL
			curl_setopt ($ch, CURLOPT_URL, $url);

			//设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

			//在发起连接前等待的时间，如果设置为0，则无限等待
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

			//运行cURL，请求网页
			$file_contents = curl_exec($ch);

			//关闭URL请求
			curl_close($ch);
		}

		return $file_contents;
	}
		*/
	
}

?>