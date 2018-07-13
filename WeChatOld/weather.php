<?php

// var_dump(getWeatherInfo("深圳"));

function getWeatherInfo($cityName)
{
    if ($cityName == "" || (strstr($cityName, "+"))){
        return "发送天气+城市，例如'天气深圳'";
    }
    //读取百度数据
	$url = "http://api.map.baidu.com/telematics/v3/weather?location=".urlencode($cityName)."&output=json&ak=mGPil6tGeiawIUFkb4IenlpsXvLVvOrK";
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output, true);
    
    //如果返回的数据包含错误信息，则提取返回数据中的status参数内容作为返回结果
    if ($result["error"] != 0){
        return $result["status"];
    }
    $curHour = (int)date('H',time());   //获取当前的时间的'小时'
    $weather = $result["results"][0];    //初始化weather，值为返回结果中"results"的所有数据
   

   $weatherArray[] = array("Title" =>$weather['currentCity']."天气预报", "Description" =>"", "PicUrl" =>"", "Url" =>"");   //构建输出内容
    
	
	
	for ($i = 0; $i < count($weather["weather_data"]); $i++) {      //通过循环，将返回结果中"weather_data"的各组逐项填入
        $weatherArray[] = array("Title"=>
            $weather["weather_data"][$i]["date"]."\n".
            $weather["weather_data"][$i]["weather"]." ".
            $weather["weather_data"][$i]["wind"]." ".
            $weather["weather_data"][$i]["temperature"],
        "Description"=>"", 
//通过当前时间“小时”的判断，在6时到18时用的是白天图片，其余为夜间图片
		"PicUrl"=>(($curHour >= 6) && ($curHour < 18))?$weather["weather_data"][$i]["dayPictureUrl"]:$weather["weather_data"][$i]["nightPictureUrl"], "Url"=>"");
    }
    
	
	
	
	
	return $weatherArray;
}





?>