<?php
//$access_token="9_eE-9U8hP2HMIuQYFoZ8xYy5WPXp3a8w-ACAndqhJp61ZoM95ru9pFq3gg92oE4E_5CRvVXWGCAk49deJm7UZfGv8P5Gox2oa3E5Gt9424qJMj48qN5TRGUn1Tbprygc1GFwdMbsWz3OHeJZBAFAcADAEJE";
//$type = "image";  //声明上传的素材类型，这里以image
//$filepath = dirname(__FILE__)."\2.jpg";
//$filedata = array("media"  => "@".$filepath);
//$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";


$access_token="9_qKqs2SAqZ7F0QqNDagdLI7SE3UmdkOcUkNCg7ZCn_sMTU43ZqOTk-wpVLiX7viaHMpJCh-FJ7w3FmL8jxzRVHwTjzsAy6vw5ceW1z3gKLZvSCJIk7OpRxaDVhNmJzjn5JBAWuyWCAmeYj7HfJODfACAXCR";
$type = "image";
$file_path = dirname(__FILE__)."/2.jpg";
$file_data = array("media" => new \CURLFile($file_path));
$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";


$result = https_request($url, $file_data); 
var_dump($result); 
function https_request($url, $data = null)
{
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
