<?
include_once("./_common.php");
include_once("$g4[path]/memo.config.php");

if (!$gr_name)
  alert("�׷� �̸��� �Էµ��� �ʾҽ��ϴ�.");
  
$gr_name  = $_POST['gr_name'];

$sql = " select count(*) as cnt from $g4[memo_group_table] where gr_name = '$gr_name' ";
$result = sql_fetch($sql);
if ($result['cnt'] > 0)
  alert("$gr_name�� �̹� ��ϵ� �׷� �Դϴ�. �ٸ� �̸��� �Է��� �ּ���");

$sql = " insert $g4[memo_group_table] set mb_id = '$member[mb_id]', gr_name = '$gr_name', gr_datetime = now() ";
sql_query($sql, $error);

goto_url("$g4[bbs_path]/memo.php?kind=memo_group_admin");
?>