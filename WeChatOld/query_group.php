<?php
//创建json数据
$data = '{"openid":'.$openid.',"to_groupid":'.100.'}';
//移动分组的URL
$url = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=$access_token';
//执行结果
$result = https_request($url,$jsonmenu);

function https_request($url,$data = null){
	$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
	if(!empty($data)){
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
	}
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}
?>