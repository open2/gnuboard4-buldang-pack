<?
include_once("./_common.php");
include_once("$g4[path]/memo.config.php");

include_once("$g4[path]/head.sub.php");

// ȸ������ �˻��Ͽ� ȸ���� �ƴ� ��쿡�� �α��� �������� �̵��Ѵ�.
if (!$member[mb_id]) 
    alert_close("ȸ���� �Ű� �� �� �ֽ��ϴ�.");

// CSRF�� ���� ���ؼ�
$sg_reason = strip_tags($_POST[sg_reason]);

// ������ �Ű��� ������ Ȯ��
$sql = " select sg_datetime from $g4[singo_table] 
          where bo_table = '$bo_table' and wr_id = '$wr_id' and wr_parent = '$wr_parent' and sg_mb_id = '$member[mb_id]' ";
$row = sql_fetch($sql);
if ($row[sg_datetime]) 
    alert_close("�̹� �Ű��� ���Դϴ�. (�Ű��Ͻ� : $row[sg_datetime])");

$write_table = $g4['write_prefix'].$bo_table;
if ($bo_table == "@memo") {
    // �Ű����
    $sg_reason = "�������� �Ű� �Դϴ�";
} else if ($bo_table == "@user") {
    // �Ű����
    $sg_reason = "����� �Ű� �Դϴ�";
} else if ($bo_table == "hidden_comment") {
    // �Ű����
    $sg_reason = "hidden comment �Ű� �Դϴ�";
    $write = sql_fetch(" select bo_table, wr_id, mb_id, co_content as wr_subject from $g4[hidden_comment_table] where co_id = '$wr_id' ");
} else {
    $sql = " select count(*) as cnt from $write_table 
              where wr_id = '$wr_id' and wr_parent = '$wr_parent' ";
    $row = sql_fetch($sql);
    if (!$row[cnt])
        alert_close("�Ű��� �Խù��� �������� �ʽ��ϴ�.");
    
    // form���� �ѱ�� �� �����ؼ�...
    $wr_content = mysql_escape_string($write[wr_content]);
}

// ��ȸ���� ���� �Ű��� ��� $write[mb_id]�� ���� ���� ������ �ذ��ϱ� ���ؼ�...��..��...
if (!$write[mb_id])
    $write[mb_id] = "��ȸ��";

// �Ű� ���� ���
$sql = " insert into $g4[singo_table] 
            set mb_id = '$write[mb_id]',
                bo_table = '$bo_table',
                wr_id = '$wr_id',
                wr_parent = '$wr_parent',
                wr_subject = '$wr_subject',
                wr_content = '$wr_content',
                wr_ip = '$wr_ip',
                wr_datetime = '$wr_datetime',
                sg_mb_id = '$member[mb_id]',
                sg_reason = '$sg_reason',
                sg_datetime = '$g4[time_ymdhis]',
                sg_ip = '$remote_addr' ";
sql_query($sql);

// �Խñۿ� �Ű� ����
if ($bo_table == "@memo" and $bo_table == "@user") // ���� �Ǵ� ����� �Ű��� ���
{
}
else if ($bo_table == "hidden_comment")
{
    $sql = " update $g4[hidden_comment_table] set wr_singo = wr_singo + 1 where co_id = '$wr_id' ";
    sql_query($sql, false);
} else 
{
    $sql = " update $write_table set wr_singo = wr_singo + 1 where wr_id = '$wr_id' ";
    sql_query($sql, false);
}

// �Ű��� ����� ����Ʈ�� ����
if ($config[cf_singo_point_send])
    insert_point($mb_id, -$config[cf_singo_point_send], "�Ű�ó�� ����Ʈ", '@member', $mb_id, '�Ű�ó��');

// �Ű�� ����� ����Ʈ�� ����
if ($config[cf_singo_point_recv])
    insert_point($mb_id, -$config[cf_singo_point_recv], "�Ű�ó�� ����Ʈ", '@member', $mb_id, '�Ű�ó��');

// �Ű�� ����� ������ ������Ʈ (�Ű�Ǽ�, �Ű�� ��¥)
$sql = " update $g4[member_table] set mb_singo = mb_singo + 1, mb_singo_datetime = '$g4[time_ymdhis]'  where mb_id = '$write[mb_id]' ";
sql_query($sql, false);

//------------------------------------------------------------------------------------
// �Ű�� �Ǽ��� ��ȸ�̻��̸� ���������� ����
// ȸ���� ������ 1�� �����ϰ� �������ڸ� �����Ͽ� ������ ������
//------------------------------------------------------------------------------------
if (!isset($config[cf_singo_intercept_count]) || $config[cf_singo_intercept_count] == 0) $config[cf_singo_intercept_count] = 1000;
$sql = " select count(*) as cnt from $g4[singo_table] where mb_id = '$write[mb_id]' ";
$row = sql_fetch($sql);
if ($row[cnt] >= $config[cf_singo_intercept_count]) {
    // �����ڴ� �ڵ� - ���� ����
    //$sql = " update $g4[member_table] set mb_level = '1', mb_intercept_date = '".date("Ymd",$g4[server_time])."' where mb_id = '$write[mb_id]' ";
    //sql_query($sql);
    // �Ҵ� �ڵ� - ����� ����/����Ʈ �ʱ�ȭ
    $sql = " update $g4[member_table] set mb_level = '$config[cf_register_level]', mb_point = '$config[cf_register_point]' where mb_id = '$write[mb_id]' ";
    sql_query($sql);    
    insert_point($mb_id, -$member[mb_point], "�Ű�ó�� ����Ʈ����", '@member', $mb_id, '�Ű�ó��');
    insert_point($mb_id, $config[cf_register_point], "�Ű�ó�� ����Ʈ�ʱ�ȭ", '@member', $mb_id, '�Ű�ó��');
}
//$singo_count = $row['cnt']; // ��ü �Ű�� �Ǽ�

// �Ű�����, �Խ��ǰ�����/�׷������/����Ʈ �����ڿ��� ������ �߼� (�Ҵ��� ����2)
$memo_list = array();

$memo_list[] = $write[mb_id];// �Ű�� �Խñ��� �۾���
$memo_list[] = $config['cf_admin']; // ����Ʈ ������
if ($group['gr_admin'] && !in_array($group['gr_admin'], $memo_list)) // �׷������
    $memo_list[] = $group['gr_admin'];
if ($board['bo_admin'] && !in_array($board['bo_admin'], $memo_list)) // �Խ��ǰ�����
    $memo_list[] = $board['bo_admin'];

// �ڸ�Ʈ�� �Ű��� ���
if ($wr_id != $wr_parent) {
    // $write[wr_subject] ���� ������ �������� �־��ݴϴ�
    $result = sql_fetch(" select wr_subject from $write_table where wr_id = '$wr_parent' ");
    $write['wr_subject'] = $result['wr_subject'];
    // wr_id�� �ڸ�Ʈ�� ����
    $wr_id = $wr_id . "#c_" . $wr_parent;
    // �ڸ�Ʈ�� �ɼ����� html ��뼳��
}

foreach($memo_list as $memo_recv_mb_id) {

    $me_send_mb_id = $config['cf_admin']; // ����Ʈ ������ ���Ƿ� ������ �߼�
    
    // �����ڰ� �������� ��쿡��, �߽��ڸ� �Ű��ڷ� ����
    if ($memo_recv_mb_id == $config['cf_admin'])
        $me_send_mb_id = $member['mb_id'];

    // �Ű�� url
    if ($bo_table == 'hidden_comment') {
        $sg_url = "$g4[bbs_path]/board.php?bo_table=$write[bo_table]&wr_id=$write[wr_id]&h_id=$wr_id";
    } else {
        $sg_url = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$wr_id";
    }

    // �Ű���
    $me_memo = "�Ű�� �Խñ� - <a href=\'$sg_url\' target=new>$write[wr_subject]</a><br>�Խñ��� �Ű����� - {$sg_reason}<br><br>�ش� �Խñ��� �Ű��뿡 ���ǰ� �ִ� ��� ��ڿ��� �����Ͻñ� �ٶ��ϴ�."; // �޸𳻿�

    // �Ű�� ����
    $me_subject = "$write[mb_id] ���� �Խñ��� �Ű�Ǿ����ϴ�"; // �޸�����
    if ($row[cnt] >= $config[cf_singo_intercept_count]) {
        $me_subject .= "<br><br>$write[mb_id]���� �Ű�Ƚ���� $config[cf_singo_intercept_count]ȸ�� �ʰ��Ͽ�, ����ڷ��� �� ����Ʈ�� �ʱ�ȭ �Ǿ����ϴ�";
    }

    // �Ű���� ���⸦ html��
    $html = "html1";

    // ���� INSERT (������) 
    $sql = " insert into $g4[memo_recv_table] 
                    ( me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_subject, memo_type, memo_owner, me_file_local, me_file_server, me_option ) 
             values ('$memo_recv_mb_id', '$me_send_mb_id', '$g4[time_ymdhis]', '$me_memo', '$me_subject', 'recv', '$memo_recv_mb_id', '', '', '$html,$secret,$mail' ) 
          "; 
    sql_query($sql); 
    $me_id = mysql_insert_id(); 

    // ������ ���� ����, ���� ���� ��¥�� ������Ʈ
    $sql = " update $g4[member_table]
                set mb_memo_unread=mb_memo_unread+1, mb_memo_call_datetime='$g4[time_ymdhis]' 
              where mb_id = '$me_recv_mb_id' ";
    sql_query($sql);

    // ���� ���� �˸� ���
    if ($mb_memo_call)
    {
        $sql = " update $g4[member_table]
                    set mb_memo_call = concat(mb_memo_call, concat(' ', '$me_send_mb_id'))
                  where mb_id = '$me_recv_mb_id' ";
        sql_query($sql);
    }
}

// ����� �ڵ� ����
@include_once ("$g4[path]/skin/member/$config[cf_member_skin]/singo_popin_update.skin.php");

?>

<?
if ($bo_table == "@memo" or $bo_table == "@user") { // ����.����� �Ű� �ƴ� ��쿡�� Ȯ��
    ;
} else if ($bo_table == "hidden_comment") {
?>
<script type="text/javascript">
alert("�Խù��� �Ű��Ͽ����ϴ�.\n\n����� Ȯ�� �� �ش� �Խù��� ���ؼ� ������ġ�� �ϰڽ��ϴ�.\n\n�����մϴ�.");
opener.document.location.href = "<?="board.php?bo_table=$write[bo_table]&wr_id=$write[wr_id]"?>";
window.close();
</script>
<?
} else {
?>
<script type="text/javascript">
alert("�Խù��� �Ű��Ͽ����ϴ�.\n\n����� Ȯ�� �� �ش� �Խù��� ���ؼ� ������ġ�� �ϰڽ��ϴ�.\n\n�����մϴ�.");
opener.document.location.href = "<?="board.php?bo_table=$bo_table&wr_id=$wr_id"?>";
window.close();
</script>
<? } ?>
