<?
include_once("./_common.php");

if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

if (!$member[mb_id]) 
  alert("ȸ���� ����� �� �ֽ��ϴ�");

if (!$ms_id)
  alert("��ũ�� ���̵� �������� �ʾҽ��ϴ�.");

//if (!$memo_edit)
//  alert("������ �޸� �Էµ��� �ʾҽ��ϴ�.");
  
$memo_edit = addslashes($memo_edit);

$sql = " update $g4[scrap_table] set ms_memo = '$memo_edit' where ms_id = '$ms_id' and mb_id = '$member[mb_id]' ";
sql_query($sql, FALSE);

goto_url("$g4[bbs_path]/scrap.php?head_on=$head_on&mnb=$mnb&snb=$snb");
?>
