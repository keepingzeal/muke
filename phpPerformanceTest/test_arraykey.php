<?php


$statr = current_time();
$i = 0;
$arr = range(1, 200000);
while ( $i < 200000) {
	++$i;
	isset( $arr[$i] );
	// array_key_exists( $i, $arr );
}
$end = current_time();
echo "Lost Time:".number_format($end-$statr, 3)*1000;
echo "\n";

/**
 * microtime()	返回当前 Unix 时间戳的微秒数
 * @return [type] [description]
 */
function current_time() {
	list( $usec, $sec ) = explode( " ", microtime() );
	return ((float)$usec + (float)$sec);
}
?>