<?
include_once("./_common.php");

// �Ҵ��� - �Խ��� �����ڰ� ī�װ��� ��� ��~ �ѹ��� �ٲ�����ϴ�.

$tmp_array = array();
if ($wr_id) // �Ǻ� ����
    $tmp_array[0] = $wr_id;
else // �ϰ�����
    $tmp_array = $_POST[chk_wr_id];

$sca = mysql_real_escape_string(trim($_POST[sca]));
if ($sca == "" || $is_admin == "")
    alert("ī�װ� �ϰ� ���� ���� �Դϴ�.");

// �Ųٷ� �д� ������ delete_all.php�� �����߱� ����. �ٸ� ���� ����
for ($i=count($tmp_array)-1; $i>=0; $i--) 
{
    $sql = " update $write_table set ca_name='$sca' where wr_parent = '{$tmp_array[$i]}' ";
    sql_query($sql);
}

goto_url("$g4[bbs_path]/board.php?bo_table=$bo_table&page=$page" . $qstr);
?>
