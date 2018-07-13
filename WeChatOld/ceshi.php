<?php 
header("Content-type: text/html; charset=utf-8");
//构建二维码参数
$data = array(
        '4001',
        '4002',
        '4003',
        '4004',
        '4005',
        '4006',
        '4007',
        '4008',
        '4009',
        '4010',
        '4011',
        '4012',
        '4013',
        '4014',
        '4015',
        );
//循环创建二维码
$AppId = 'wx7f6da6770478867b';//appid  填写自己的appid
$AppSecret = 'eda033fb2e34226ad681e7dd2a73361e'; //AppSecret  填写自己的AppSecret
$Access_Token = get_access_token($AppId,$AppSecret); //调用独立的get_access_token获取Access_Token

if(empty($Access_Token)){
    exit('access_token获取失败！');
}
/*通过循环做以下工作：
1、生成以元素命名的多个文件；
2、判断文件夹images是否存在，不存在则创建；
3、若images文件夹存在且为空，创建永久二维码并下载
*/
foreach($data as $val){ 
    $filename = $val.'_qrcode.jpg'; //新文件名字
    if(!file_exists('images/')){    //file_exists() 函数检查文件或目录是否存在
        mkdir ("./images");//创建二维码存放文件夹
    }else{
        if(!file_exists('images/'.$filename)){     
        //获取永久的ticket
        $qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id":'.$val.'}}}';
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$Access_Token";
        $resulstd = https_post($url,$qrcode);
        $jsoninfo = json_decode($resulstd,true);
        $ticket = $jsoninfo['ticket'];
        //下载二维码图片
        $urls = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        $imageinfo = downloadimagefromweixin($urls);
        $local_file = fopen('./images/'.$filename, 'w');
        //如果没有打开文件，进行写入操作
            if(false !==$local_file){
                if(false !==fwrite($local_file, $imageinfo['body'])){
                    fclose($local_file);
                }
            }
        }else{
            //已经存在的二维码不执行
        }
    }
}
/**
 * 获取token
 */
function get_access_token($AppId,$AppSecret){
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$AppId.'&secret='.$AppSecret;
    $result = json_decode(file_get_contents($url));
    //可以缓存起来 expires_in 过期时间 access_token
    return $result->access_token;
    
}
function https_post($url,$data=null){
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    if(!empty($data)){
        curl_setopt($curl, CURLOPT_POST,1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
    }
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
//获得二维码图片
function downloadimagefromweixin($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_NOBODY,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $package = curl_exec($ch);
    $httpinfo = curl_getinfo($ch);
    curl_close($ch);
    return array_merge(array('body'=>$package),array('header'=>$httpinfo));
}
echo '二维码批量生成成功';
?>