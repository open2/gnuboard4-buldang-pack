<?php

//Ÿ�Ӻ� ����Ÿ ������
function attendance_get_type($v){

	switch($v){
		case "1" : $return = "��"; break;
		case "2" : $return = "��"; break;
		case "3" : $return = "��"; break;
	}

    return $return;
}

// ����, ����, ���� �� ����Ʈ�� �����
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

	//����Ʈ ������� �ϳ��� ���� ������
	function sql_value($query, $a=0, $b=0){

	  $result = sql_query($query);

	  return @mysql_result($result, $a, $b);

	}

}

?>
