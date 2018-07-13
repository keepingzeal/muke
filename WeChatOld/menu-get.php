<?php
$appid = "wx7f6da6770478867b";
$appsecret = "eda033fb2e34226ad681e7dd2a73361e";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";



$output = https_request($url);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];
$jsonmenu = '{
      "button":[
     {    
          "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },

      {
           "name":"菜单",
           "sub_button":[
           {    
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
               "type":"view",
               "name":"视频",
               "url":"http://v.qq.com/"
            },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]

 }';


$url = "https://api.weixin.qq.com/cgi-bin/menu-get/create?access_token=".$access_token;
$result = https_request($url,$jsonmenu);
var_dump($result);

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