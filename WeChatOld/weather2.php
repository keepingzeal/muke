<?php
getAirQualityChina("zhuhai");
function getAirQualityChina($city){
	$url = "http://www.pm25.in/api/querys/so2.json?avg=true&stations=no&city=".urlencode($city)."&token=5j1znBVAsnSf5xQyNQyq";
	
			$ch=curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$output=curl_exec($ch);
			curl_close($ch);
		 $cityAir = json_decode($output,true);
    		var_dump($cityAir);
		 if(isset($cityAir['error'])){
			 return $cityAir['error'];
		 }else{
			 $result = "空气质量指数(AQI):".$cityAir[0]['aqi']."\n".
			           "空气质量等级：".$cityAir[0]['area']."\n".
					   "细颗粒物(pm2.5):".$cityAir[0]['so2']."\n".
					   "细颗粒物(pm2.5)24H均值:".$cityAir[0]['so2_24h']."\n".
					   "城市名称：".$cityAir[0]['area']."\n".
					   "更新时间：".preg_replace("/([a-zA-z])/i","",$cityAir[0]['time_point']);
             
					   $aqiArray = array();
             
					   $aqiArray[] = array("Title" =>$cityAir[0]['area']."空气质量","Description" => $result,"PicUrl" =>"","Url" => "");
					   return $aqiArray;
		 }
}
?>