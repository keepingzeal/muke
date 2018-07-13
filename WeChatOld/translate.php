<?php
header('Content-type:text');
define("CURL_TIMEOUT",   20); 
define("APP_ID",         "1e1a3210b5153952"); //替换为您的应用ID
define("APP_KEY",        "1N7MjZpqW8fTDLC66uWBdd6nwAlOQwCh");//替换为您的密钥


function fanyi($content){
    $content = $content;
	$salt = rand(10000,99999);
	$sign = rawurlencode(md5(APP_ID.$content.$salt.APP_KEY));
	$url ="http://openapi.youdao.com/api?q=$content&from=auto&to=auto&appKey=".APP_ID."&salt=".$salt."&sign=$sign";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($output, true);
	if($result['error'] != 0){
		return $result["errorCode"];
	}
	$res = $result["translation"][0];
    return $res;
}

?>