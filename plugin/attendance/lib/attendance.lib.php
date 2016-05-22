<?php

//타임별 데이타 가져옴
function attendance_get_type($v){

	switch($v){
		case "1" : $return = "묵"; break;
		case "2" : $return = "찌"; break;
		case "3" : $return = "빠"; break;
	}

    return $return;
}

// 연승, 연패, 연무 등 포인트를 계산함
function attendance_get_successive($point,$successive){
	global $att;

	$tmp_successive = explode(",", $att['attendance_successive']);
	for ($i=0; $i<count($tmp_successive); $i++) {
		if($successive == trim($tmp_successive[$i])){
			$return = $point * $successive;
			break;
		}else{
			$return = $point;
		}
	}

    return $return;
}

if(!function_exists('sql_value')){

	//셀렉트 결과에서 하나의 값만 가져옴
	function sql_value($query, $a=0, $b=0){

	  $result = sql_query($query);

	  return @mysql_result($result, $a, $b);

	}

}

?>
