<?php
include_once("./_common.php");

$at_id = (int) strip_tags($_GET['at_id']);

if (!$is_admin) alert('�����ڸ� �̿��ϽǼ� �ֽ��ϴ�.');
if (!$at_id) alert('���� �����ϴ�.');

$row = sql_fetch(" select mb_id,at_successive from $g4[attendance_plugin_table] where at_id = '$at_id' ");
if(!$row[mb_id]) alert('�ش� �ڷᰡ �����ϴ�.');

sql_query(" delete from $g4[attendance_plugin_table] where at_id = '$at_id' "); 
sql_query(" delete from $g4[point_table] where po_rel_table = '@attendance' and po_rel_id = '$row[mb_id]' and SUBSTRING_INDEX(po_rel_action,'-',1) = '$at_id'  "); 

// ����Ʈ ������ ���� ���ϰ�
$sql = " select sum(po_point) as sum_po_point from {$g4['point_table']} where mb_id = '$row[mb_id]' ";
$row = sql_fetch($sql);
$sum_point = $row['sum_po_point'];

// ����Ʈ UPDATE
$sql = " update {$g4['member_table']} set mb_point = '$sum_point' where mb_id = '$row[mb_id]' ";
$result = sql_query($sql);

if($row[at_successive] > 1)
	sql_query(" update $g4[attendance_successive_plugin_table] set as_successive = as_successive -1, as_datetime='$g4[time_ymdhis]' where mb_id='$row[mb_id]' ");

alert("�����Ǿ����ϴ�.", "{$g4[attendance_path]}/attendance.php?s_date=$s_date" . $qstr);
?>
