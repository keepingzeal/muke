<?php
	function setLocation($openid, $locationX, $locationY) {
		$mmc = memcache_init();
		if($mmc = memcache_init()){	//初始化MC链接
			$location = array("locationX" => $locationX, "locationY" => $locationY);
			memcache_set($mmc, $openid, json_encode($location), 60);
			return "位置已缓存";
		} else {
			return "未启用缓存服务";
		}
	}

	function getLocation($openid){
		$mmc = memcache_init();
		if($mmc == true ){	//初始化MC链接
			$location = memcache_get($mmc, $openid);
			if(!empty($location)){
				return json_decode($location,true);
			}else{
				return "请先发送位置";
			}
		} else {
			return "未启用缓存服务";
		}
	}
?>