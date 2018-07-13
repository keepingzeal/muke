<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        dump(get_access_token());
	$appid = 'wx7f6da6770478867b';
    	$secret = 'eda033fb2e34226ad681e7dd2a73361e';
    	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
	$val = file_get_contents($url);
	dump($val);
    }

    /**
     * 获取AccessToken方法
     * @Author   Kz
     * @DateTime 2018-07-11
     * @version  1.0
     * @return   JSON
     */
    public function getAccessToken()
    {
    	dump(get_access_token());
        

    }
}
