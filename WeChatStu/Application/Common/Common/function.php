<?php
if (!function_exists('http_curl')) {
	/**
	 * 封装curl请求
	 * @Author   Kz
	 * @DateTime 2018-07-11
	 * @version  1.0
	 * @param    [type]     $url     请求地址
	 * @param    array      $data    传参
	 * @param    string     $method  提交请求方式
	 * @param    string     $retType 返回数据格式
	 * @return   [type]              array
	 */
	function http_curl($url, $data=array(), $method='get', $retType='json') {
		if (empty($url)) {
			return '参数为空';
		}
		// 开始会话（资源数据格式）
    	$ch = curl_init();
    	// 设置参数
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	// 禁用服务端验证
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	// 判断请求方式
    	if ($method != 'get') {
    		// 设置请求方式
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		// 添加请求参数
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	}
    	// 执行会话
    	$res=curl_exec($ch);
    	// 关闭会话
    	curl_close($ch);

    	if ($retType == 'json') {
    		$val = json_decode($res, true);
    		return $val;
    	}
    	return $res;
	}
}

if (!function_exists('get_access_token')) {
	/**
	 * 封装获取AccessToken函数
	 * @Author   Kz
	 * @DateTime 2018-07-11
	 * @version  1.00
	 * @return   [type]     access_token
	 */
	function get_access_token() {
		$appid = 'wx7f6da6770478867b';
    	$secret = 'eda033fb2e34226ad681e7dd2a73361e';
    	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
    	// include './Extends/MyMemcache.php';
    	include_once './Extends/MyMemcache.php';
    	$obj = new \MyMemcache('127.0.0.1');
    	$val = $obj->get($appid);
    	// 获取缓存文件
    	// $val = S($appid);
    	if (!$val) {
	    	$res = http_curl($url);
	    	$val = $res['access_token'];
	    	$obj->set($appid, $val, 7000);
    	}
    	return $val;
	}

    function Tget_access_token() {
        $appid = 'wx7f6da6770478867b';
        $secret = 'eda033fb2e34226ad681e7dd2a73361e';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
        $res = http_curl($url);
        $val = $res['access_token'];
        return $val;
    }
}
