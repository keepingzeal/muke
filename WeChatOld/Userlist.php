<?php
$access_token = "9_yQsRgBh56mfkBqJCgLw4PZv_teU5NfxYEEjJjWDBQp5ORIBOjQ3lc5vnXxyO-d-ei8MEQ-6b3tOP4AMKSsERT1hBwk7mehXVtTgh6T2AM7Y6ttLu4ZKlAft6UkUeYzKiJImqerw78_VfJ_6UKQEiAHACIW";
$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
$result = https_request($url);
$jsoninfo = json_decode($result,true);
var_dump($result);
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
