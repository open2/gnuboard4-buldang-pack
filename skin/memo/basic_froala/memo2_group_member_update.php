<?
include_once("./_common.php");
include_once("$g4[path]/memo.config.php");

$gr_mb_id   = $_POST[gr_mb_id];
$gr_id      = $_POST[gr_id];

$row = get_member($gr_mb_id);

if ((!$row[mb_id] || $row[mb_leave_date] || $row[mb_intercept_date]) && !$is_admin) {
    alert("ȸ�����̵� \'".$gr_mb_id."\' ��(��) ����(�Ǵ� ��������)���� �ʴ� ȸ�����̵� �̰ų� Ż��, �������ܵ� ȸ�����̵� �Դϴ�.\\n\\n�׷쿡 �߰��� �� �����ϴ�.");
}
  
$sql = " select count(*) as cnt from $g4[memo_group_member_table] where gr_id = '$gr_id' and gr_mb_id = '$gr_mb_id' ";
$result = sql_fetch($sql);
if ($result[cnt] > 0)
  alert("$gr_mb_id�� �̹� ��ϵ� ���̵� �Դϴ�.");

$sql = " select count(*) as cnt from $g4[member_table] where mb_id = '$gr_mb_id' ";
$result = sql_fetch($sql);
if ($result[cnt] == 0)
  alert("$gr_mb_id�� ��ϵ��� ���� ȸ�� ���̵� �Դϴ�.");

$sql = " insert $g4[memo_group_member_table] set gr_id = '$gr_id', gr_mb_id = '$gr_mb_id', gr_mb_datetime = now() ";
sql_query($sql, $error);

goto_url("$g4[bbs_path]/memo.php?kind=memo_group&gr_id=$gr_id");
?>