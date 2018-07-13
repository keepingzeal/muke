<?php
$access_token = "9_84wdU1sdbeBSGaAd-JJj53GOfh3ZDIdbO1vWBIjeMJn4LU29qFULfxTLBzYbxETBmzFVneadATsOjGA-wqv3DonjJ90uQHQG92s9kfWYFi7g22r8PqeipZD0MFeBpc4EVgZCRUlHEGo6ld1lLWBcABAHSX";
$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=ohQFSw7aYPfg9Fy_AxF3KM-91DQM&lang=zh_CN";
$result = https_request($url);
$jsoninfo = json_decode($result,true);
var_dump($jsoninfo["city"]);


function https_request($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    if(curl_errno($ch)){ return 'ERROR'.curl_error($ch);}
    curl_close($ch);
    return $output;
}
