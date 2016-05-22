<?php
include_once("./_common.php");

$at_type = strip_tags($_POST['at_type']);
$at_memo = strip_tags($_POST['at_memo']);

if (!$is_member) alert("회원만 이용하실수 있습니다.","{$g4[bbs_path]}/login.php?url=".urlencode("{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr));

if($att['attendance_gnu'])
	$levelname = $att[attendance_level];
else
	$levelname = sql_value("select ln_name{$att[attendance_level]} from $g4[levelname_table] where ln_level{$att[attendance_level]} = '$att[attendance_level]'");

if ($member[mb_level] < $att[attendance_level]) alert($levelname.' 레벨 이상 회원만 이용하실수 있습니다.');
if (!$at_type) alert('가위바위보가 넘어오지 않았습니다.');
if (!$at_memo) alert('내용이 넘어오지 않았습니다.');

// 출석 시간 체크
if (date("H:i:s") < $att['attendance_start_time'] || date("H:i:s") > $att['attendance_end_time']) {
    alert("출석 시간이 아닙니다.");
}

$ip_cnt = sql_value("select count(at_id) as cnt from $g4[attendance_plugin_table] where at_ip = '$_SERVER[REMOTE_ADDR]' and at_date = '$g4[time_ymd]'");
$mb_cnt = sql_value("select count(at_id) as cnt from $g4[attendance_plugin_table] where mb_id = '$member[mb_id]' and at_date = '$g4[time_ymd]'");

if($ip_cnt >= $att[attendance_ip]) alert("해당 아이피로 {$ip_cnt}회 이미 출석하셨습니다.");
if($mb_cnt >= $att[attendance_number]) alert("해당 아이디로 {$mb_cnt}회 이미 출석하셨습니다.");

$default_type = rand(1,3); // 최소 최대의 숫자
$attendance_win_point = rand($att['attendance_win_start_point'],$att['attendance_win_end_point']); // 최소 최대의 승 포인트
$attendance_tie_point = rand($att['attendance_tie_start_point'],$att['attendance_tie_end_point']); // 최소 최대의 무승부 포인트
$attendance_loss_point = rand($att['attendance_loss_start_point'],$att['attendance_loss_end_point']); // 최소 최대의 패 포인트

// 패
if ( ($at_type =='1' && $default_type =='3') || ($at_type =='2' && $default_type =='1') || ($at_type =='3' && $default_type =='2')) {
	$point = $attendance_loss_point;
	$victory = 1;
	$victory_str = "패하셨습니다.";
}

// 무승부
if ( ($at_type =='1' && $default_type =='1') || ($at_type =='2' && $default_type =='2') || ($at_type =='3' && $default_type =='3')) {
	$point = $attendance_tie_point;
	$victory = 2;
	$victory_str = "무승부입니다.";
}

//  승
if ( ($at_type =='1' && $default_type =='2') || ($at_type =='2' && $default_type =='3') || ($at_type =='3' && $default_type =='1')) {
	$point = $attendance_win_point;
	$victory = 3;
	$victory_str = "승리하셨습니다.";
}

// 저번 결과 가져오기
$yesterday = date("Y-m-d", $g4['server_time'] - (1 * 86400));
$sql = " select at_victory,at_successive from $g4[attendance_plugin_table] where mb_id = '$member[mb_id]' order by at_datetime desc";
$row = sql_fetch($sql);

// 어제 출석했다면
if($row[at_victory]){
	// 어제의 결과와 오늘의 결과가 같다면
	if($row[at_victory] == $victory)
		$successive = $row['at_successive'] + 1;
	else
		$successive = 1;
}else{
	$successive = 1;
}

// 연승, 연패, 연무 등 포인트를 계산함
$tmp_point = attendance_get_successive($point,$successive);
if ($point != $tmp_point){
	$point = $tmp_point;
	$victory_str = "연속 ".$victory_str;
}

if (substr_count($at_memo, "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
    exit;
}


// 단어 필터링
$filters = explode(",", $config['cf_filter']);
for ($i=0; $i<count($filters); $i++) {
    $s = trim($filters[$i]); // 필터단어의 앞뒤 공백을 없앰
    if (stristr($at_memo, $s)) {
        alert("제목에 금지단어(\'{$s}\')가 포함되어 있습니다.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
        exit;
    }
    if (stristr($at_memo, $s)) {
        alert("내용에 금지단어(\'{$s}\')가 포함되어 있습니다.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
        exit;
    }
}

$sql = " insert into $g4[attendance_plugin_table] 
            set 
                mb_id='$member[mb_id]', 
                po_point='$point',
                at_type='$at_type', 
                at_default_type='$default_type', 
                at_victory='$victory', 
                at_successive='$successive', 
                at_memo='$at_memo', 
                at_date='$g4[time_ymd]', 
                at_datetime='$g4[time_ymdhis]', 
                at_ip = '$_SERVER[REMOTE_ADDR]' ";
sql_query($sql);

$at_id = mysql_insert_id();

$sql_successive = " select mb_id from $g4[attendance_successive_plugin_table] where mb_id = '$member[mb_id]' ";
$row_successive = sql_fetch($sql_successive);

if(!$row_successive[mb_id])
	sql_query(" insert into $g4[attendance_successive_plugin_table] set  mb_id='$member[mb_id]', as_victory='$victory', as_successive='$successive', as_datetime='$g4[time_ymdhis]' ");
else
	sql_query(" update $g4[attendance_successive_plugin_table] set as_victory='$victory', as_successive='$successive', as_datetime='$g4[time_ymdhis]' where mb_id='$row_successive[mb_id]' ");

insert_point($member[mb_id], $point, "$g4[time_ymd] ".attendance_get_type($at_type)."으로 {$victory_str}", "@attendance", $member['mb_id'], "{$at_id}-".uniqid(""));

alert("상대는 \"".attendance_get_type($default_type)."\" 나는 \"".attendance_get_type($at_type)."\" 로 $victory_str \\n\\n{$point}포인트 획득", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);

?>
