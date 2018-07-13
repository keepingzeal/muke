<?php
$appid = "wx7f6da6770478867b";
$appsecret = "eda033fb2e34226ad681e7dd2a73361e";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";


/*下面是一个cURL会话过程，通过这个会话可以返回一段字符串{"********"}
这就是我们要获得的Access Token了。在调用高级功能接口的时候就靠它。这个过程用的时候直接引用就好，
不需要深究，这个cURL系统相关函数有点多而且复杂。*/


$ch = curl_init();   //初始化CURL连接

/*curl_setopt(),它可以通过设定CURL函数库定义的选项来定制HTTP请求。*/
curl_setopt($ch, CURLOPT_URL, $url);   //CURLOPT_URL 指定请求的URL；


curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);   //对认证证书来源的检查，FALSE 禁止 cURL 验证对等证书
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);   // 从证书中检查SSL加密算法是否存在


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // 获取的信息以文件流的形式返回


$output = curl_exec($ch);   //执行CURL会话

curl_close($ch);    //关闭CURL连接
$jsoninfo = json_decode($output, true);  //json_decode : 对 JSON 格式的字符串进行解码,并且把它转换为 PHP 变量
$access_token = $jsoninfo["access_token"];
//var_dump($access_token);

?>
