<?php
include_once("./_common.php");

$at_type = strip_tags($_POST['at_type']);
$at_memo = strip_tags($_POST['at_memo']);

if (!$is_member) alert("ȸ���� �̿��ϽǼ� �ֽ��ϴ�.","{$g4[bbs_path]}/login.php?url=".urlencode("{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr));

if($att['attendance_gnu'])
	$levelname = $att[attendance_level];
else
	$levelname = sql_value("select ln_name{$att[attendance_level]} from $g4[levelname_table] where ln_level{$att[attendance_level]} = '$att[attendance_level]'");

if ($member[mb_level] < $att[attendance_level]) alert($levelname.' ���� �̻� ȸ���� �̿��ϽǼ� �ֽ��ϴ�.');
if (!$at_type) alert('������������ �Ѿ���� �ʾҽ��ϴ�.');
if (!$at_memo) alert('������ �Ѿ���� �ʾҽ��ϴ�.');

// �⼮ �ð� üũ
if (date("H:i:s") < $att['attendance_start_time'] || date("H:i:s") > $att['attendance_end_time']) {
    alert("�⼮ �ð��� �ƴմϴ�.");
}

$ip_cnt = sql_value("select count(at_id) as cnt from $g4[attendance_plugin_table] where at_ip = '$_SERVER[REMOTE_ADDR]' and at_date = '$g4[time_ymd]'");
$mb_cnt = sql_value("select count(at_id) as cnt from $g4[attendance_plugin_table] where mb_id = '$member[mb_id]' and at_date = '$g4[time_ymd]'");

if($ip_cnt >= $att[attendance_ip]) alert("�ش� �����Ƿ� {$ip_cnt}ȸ �̹� �⼮�ϼ̽��ϴ�.");
if($mb_cnt >= $att[attendance_number]) alert("�ش� ���̵�� {$mb_cnt}ȸ �̹� �⼮�ϼ̽��ϴ�.");

$default_type = rand(1,3); // �ּ� �ִ��� ����
$attendance_win_point = rand($att['attendance_win_start_point'],$att['attendance_win_end_point']); // �ּ� �ִ��� �� ����Ʈ
$attendance_tie_point = rand($att['attendance_tie_start_point'],$att['attendance_tie_end_point']); // �ּ� �ִ��� ���º� ����Ʈ
$attendance_loss_point = rand($att['attendance_loss_start_point'],$att['attendance_loss_end_point']); // �ּ� �ִ��� �� ����Ʈ

// ��
if ( ($at_type =='1' && $default_type =='3') || ($at_type =='2' && $default_type =='1') || ($at_type =='3' && $default_type =='2')) {
	$point = $attendance_loss_point;
	$victory = 1;
	$victory_str = "���ϼ̽��ϴ�.";
}

// ���º�
if ( ($at_type =='1' && $default_type =='1') || ($at_type =='2' && $default_type =='2') || ($at_type =='3' && $default_type =='3')) {
	$point = $attendance_tie_point;
	$victory = 2;
	$victory_str = "���º��Դϴ�.";
}

//  ��
if ( ($at_type =='1' && $default_type =='2') || ($at_type =='2' && $default_type =='3') || ($at_type =='3' && $default_type =='1')) {
	$point = $attendance_win_point;
	$victory = 3;
	$victory_str = "�¸��ϼ̽��ϴ�.";
}

// ���� ��� ��������
$yesterday = date("Y-m-d", $g4['server_time'] - (1 * 86400));
$sql = " select at_victory,at_successive from $g4[attendance_plugin_table] where mb_id = '$member[mb_id]' order by at_datetime desc";
$row = sql_fetch($sql);

// ���� �⼮�ߴٸ�
if($row[at_victory]){
	// ������ ����� ������ ����� ���ٸ�
	if($row[at_victory] == $victory)
		$successive = $row['at_successive'] + 1;
	else
		$successive = 1;
}else{
	$successive = 1;
}

// ����, ����, ���� �� ����Ʈ�� �����
$tmp_point = attendance_get_successive($point,$successive);
if ($point != $tmp_point){
	$point = $tmp_point;
	$victory_str = "���� ".$victory_str;
}

if (substr_count($at_memo, "&#") > 50) {
    alert("���뿡 �ùٸ��� ���� �ڵ尡 �ټ� ���ԵǾ� �ֽ��ϴ�.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
    exit;
}


// �ܾ� ���͸�
$filters = explode(",", $config['cf_filter']);
for ($i=0; $i<count($filters); $i++) {
    $s = trim($filters[$i]); // ���ʹܾ��� �յ� ������ ����
    if (stristr($at_memo, $s)) {
        alert("���� �����ܾ�(\'{$s}\')�� ���ԵǾ� �ֽ��ϴ�.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
        exit;
    }
    if (stristr($at_memo, $s)) {
        alert("���뿡 �����ܾ�(\'{$s}\')�� ���ԵǾ� �ֽ��ϴ�.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
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

insert_point($member[mb_id], $point, "$g4[time_ymd] ".attendance_get_type($at_type)."���� {$victory_str}", "@attendance", $member['mb_id'], "{$at_id}-".uniqid(""));

alert("���� \"".attendance_get_type($default_type)."\" ���� \"".attendance_get_type($at_type)."\" �� $victory_str \\n\\n{$point}����Ʈ ȹ��", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);

?>
