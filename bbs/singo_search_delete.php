<?
include_once("./_common.php");

$singo_href = "./singo_search.php";
$href = "./login.php?$qstr&url=".urlencode("$singo_href");

// ȸ���� ����� �����ϰ�
if (!$is_member) 
{
    echo "<script type='text/javascript'>alert('ȸ���� �����մϴ�.'); top.location.href = '$href';</script>";
    exit;
}

if (!$sg_id) 
{
    echo "<script type='text/javascript'>alert('�������� ������û �Դϴ�.'); top.location.href = '$singo_href';</script>";
    exit;
}

// �Ű����̺��� �Խ��� ���̺�� ���̵� �о�
$sql = " select bo_table, wr_id, mb_id from $g4[singo_table] where sg_id = '$sg_id' ";
$row = sql_fetch($sql);

if ($row[bo_table] == "@memo") {
    ;
} else {
    // �׷�, �Խ��� ������ ���� ��������
    $board = sql_fetch(" select bo_admin, gr_id from $g4[board_table] where bo_table = '$row[bo_table]' ");
    $group = sql_fetch(" select gr_admin from $g4[group_table] where gr_id = '$board[gr_id]' ");
}

// �Ű� �ڷḦ ����
if ($is_admin == 'super' or $board['bo_admin'] == $member['mb_id'] or $group['gr_admin'] == $member['mb_id'])
    $member_sql = "";
else
    $member_sql = " and sg_mb_id = '$member[mb_id]' ";

$sql = " delete from $g4[singo_table] where sg_id = '$sg_id' $member_sql ";
sql_query($sql);

// �Ű� �ʵ��� �Ű� ī��Ʈ�� �����Ѵ�
if ($row[bo_table] == "@memo") {
    ;
} else {
    $sql = " select count(*) as cnt from $g4[singo_table] where bo_table = '$row[bo_table]' and wr_id = '$row[wr_id]' ";
    $sg_result = sql_fetch($sql);
    $write_table = $g4['write_prefix'].$row[bo_table];
    $sql = " update $write_table set wr_singo = '$sg_result[cnt]' where wr_id = '$row[wr_id]' ";
    sql_query($sql);
}
    
goto_url("$singo_href");
?>
