<?php
//补充代码，实现通过代码实现access_token的获取，注意：不能以$access_token = "XXX"的形式实现

//以下二选一
//临时
$appid = "wx7f6da6770478867b";
$appsecret = "eda033fb2e34226ad681e7dd2a73361e";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";



$output = https_request($url);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];
var_dump($access_token);
//$access_token = "9_QJluZUTkkqY30P4V48IheePkjGG3-8Qf-RuyQCFSaNrdOpgmwPbYMYMt77Zhgyu8yDthcPa3yabIQvaRE2QGC9ycTLagLgkmsi_n_lk0VerQE3ngD4juZZsZGod76TIZ551qCP5k_JlpQxzLMXQiAJARCV";

// $qrcode = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 10000}}}';

//永久
$qrcode = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 1000}}}';

$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
$result = https_request($url,$qrcode);
$jsoninfo = json_decode($result, true);
$ticket = $jsoninfo["ticket"];
var_dump($ticket);
function https_request($url, $data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
?>
