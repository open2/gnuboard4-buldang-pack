<?
include_once("./_common.php");

// 4.11
@include_once("$board_skin_path/delete_all.head.skin.php");

$count_write = 0;
$count_comment = 0;

// �Ҵ��� - �������� �ʴ� �޽����� ���ؼ�
$tmp_array_undeleted = "";

$tmp_array = array();
if ($wr_id) // �Ǻ�����
    $tmp_array[0] = $wr_id;
else // �ϰ�����
    $tmp_array = $_POST[chk_wr_id];


// ����� �ڵ� ����
@include_once("$board_skin_path/delete_all.skin.php");


// �Ųٷ� �д� ������ �亯�ۺ��� ������ �Ǿ�� �ϱ� ������
for ($i=count($tmp_array)-1; $i>=0; $i--) 
{
    $write = sql_fetch(" select * from $write_table where wr_id = '{$tmp_array[$i]}' ");

    if ($is_admin == "super") // �ְ������ ���
        ;
    else if ($is_admin == "group") // �׷������
    {
        $mb = get_member($write[mb_id]);
        if ($member[mb_id] == $group[gr_admin]) // �ڽ��� �����ϴ� �׷��ΰ�?
        {
            if ($member[mb_level] >= $mb[mb_level]) // �ڽ��� ������ ũ�ų� ���ٸ� ���
                ;
            else {
                $tmp_array_undeleted .= $tmp_array[$i] . " ";
                continue;
            }
        } 
        else
            continue;
    } 
    else if ($is_admin == "board") // �Խ��ǰ������̸�
    {
        $mb = get_member($write[mb_id]);
        if ($member[mb_id] == $board[bo_admin]) // �ڽ��� �����ϴ� �Խ����ΰ�?
            if ($member[mb_level] >= $mb[mb_level]) // �ڽ��� ������ ũ�ų� ���ٸ� ���
                ;
            else {
                $tmp_array_undeleted .= $tmp_array[$i] . " ";
                continue;
            }
        else
            continue;
    } 
    else if ($member[mb_id] && $member[mb_id] == $write[mb_id]) // �ڽ��� ���̶��
    {
        ;
    } 
    else if ($wr_password && !$write[mb_id] && sql_password($wr_password) === $write[wr_password]) // �н����尡 ���ٸ�
    {
        ;
    } 
    else
        continue;   // �������� ���� �Ұ�

    $len = strlen($write[wr_reply]);
    if ($len < 0) $len = 0; 
    $reply = substr($write[wr_reply], 0, $len);

    // ���۸� ���Ѵ�.
    $sql = " select count(*) as cnt from $write_table
              where wr_reply like '$reply%'
                and wr_id <> '$write[wr_id]'
                and wr_num = '$write[wr_num]'
                and wr_is_comment = 0 ";
    $row = sql_fetch($sql);
    if ($row[cnt])
            continue;

    // ��������� ���� : ���۰� �ڸ�Ʈ���� ���������� ������Ʈ ���� �ʴ� ������ ��� �ּ̽��ϴ�.
    //$sql = " select wr_id, mb_id, wr_comment from $write_table where wr_parent = '$write[wr_id]' order by wr_id ";
    $sql = " select wr_id, mb_id, wr_is_comment from $write_table where wr_parent = '$write[wr_id]' order by wr_id ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) 
    {
        // �����̶��
        if (!$row[wr_is_comment]) 
        {
            // ���� ����Ʈ ����
            if (!delete_point($row[mb_id], $bo_table, $row[wr_id], '����'))
                insert_point($row[mb_id], $board[bo_write_point] * (-1), "$board[bo_subject] $row[wr_id] �ۻ���");

            // ���� ��õ ����Ʈ ����
            delete_point($row[mb_id], $bo_table, $row[wr_id], '��õ��');
            
            // �Ҵ��� - ��õ�� ����� ����Ʈ ����
            $sql = " select * from $g4[point_table] where po_rel_table = '$bo_table' and po_rel_id = '$row[wr_id]' and po_rel_action = '��õ' ";
            $result4 = sql_query($sql);
            while ($row4=sql_fetch_array($result4)) {
                delete_point($row4[mb_id], $bo_table, $row[wr_id], '��õ');
            }

            // ���ε�� ������ �ִٸ� ���ϻ���
            //$sql2 = " select * from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ";
            //$result2 = sql_query($sql2);
            //while ($row2 = sql_fetch_array($result2))
            //    @unlink("$g4[data_path]/file/$bo_table/$row2[bf_file]");
            
            // �������̺� �� ����
            //sql_query(" delete from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ");

            // ���ε�� ������ �ִٸ�
            $sql2 = " select * from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ";
            $result2 = sql_query($sql2);
            while ($row2 = sql_fetch_array($result2))
                // ���ϻ���
                @unlink("$g4[data_path]/file/$bo_table/$row2[bf_file]");
                
            // �������̺� �� ����
            sql_query(" delete from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ");

            // �Ҵ��� - cheditor �̹��� ����
            $sql = " select * from $g4[board_cheditor_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]'";
            $result3 = sql_query($sql);
            while ($row3=sql_fetch_array($result3)) {
                $file_path = $row3[bc_dir] . "/" . $row3[bc_file];
                @unlink($file_path);
                $sql_d = " delete from $g4[board_cheditor_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' and bc_url='$row3[bc_url]' ";
                sql_query($sql_d);
            }

            // �Ҵ��� - whaton ���� (���)
            $sql = " delete from $g4[whatson_table] where bo_table ='$bo_table' and wr_id = '$row[wr_id]' ";
            sql_query($sql);

            // �Ҵ��� - ��ü �������� ����
            $sql = " delete from $g4[notice_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ";
            sql_query($sql);

            // ����Ʈ�� ����
            sql_query(" delete from $g4[good_list_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ");

            $count_write++;
        } 
        else 
        {
            // �ڸ�Ʈ ����Ʈ ����
            if (!delete_point($row[mb_id], $bo_table, $row[wr_id], '�ڸ�Ʈ'))
                insert_point($row[mb_id], $board[bo_comment_point] * (-1), "$board[bo_subject] {$write[wr_id]}-{$row[wr_id]} �ڸ�Ʈ����");

            // �Ҵ��� - whaton ���� (�ڸ�Ʈ)
            $sql = " delete from $g4[whatson_table] where bo_table ='$bo_table' and wr_id = '$row[wr_id]' ";
            sql_query($sql);

            $count_comment++;
        }

        // ��õ������ ����
        $sql = " delete from $g4[board_good_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ";
        sql_query($sql);
    }

    // �Խñ� ����
    sql_query(" delete from $write_table where wr_parent = '$write[wr_id]' ");

    // �ֱٰԽù� ����
    sql_query(" delete from $g4[board_new_table] where bo_table = '$bo_table' and wr_parent = '$write[wr_id]' ");

    // ��ũ�� ����
    sql_query(" delete from $g4[scrap_table] where bo_table = '$bo_table' and wr_id = '$write[wr_id]' ");

    // �Ű��� ����
    sql_query(" delete from $g4[singo_table] where bo_table = '$bo_table' and wr_parent = '$write[wr_id]' ");

    // �������� ����
    $notice_array = explode("\n", trim($board[bo_notice]));
    $bo_notice = "";
    for ($k=0; $k<count($notice_array); $k++)
        if ((int)$write[wr_id] != (int)$notice_array[$k])
            $bo_notice .= $notice_array[$k] . "\n";
    $bo_notice = trim($bo_notice);
    sql_query(" update $g4[board_table] set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");
    $board[bo_notice] = $bo_notice;
    
    // �Ҵ��� - ��ü �������� ����
    $sql = " delete from $g4[notice_table] where bo_table = '$bo_table' and wr_id = '$write[wr_id]' ";
    sql_query($sql);

    // �Ҵ��� - �Լ�~ ����
    $sql = " delete from $g4[whatson_table] where bo_table ='$bo_table' and wr_id = '$write[wr_id]' ";
    sql_query($sql);
}

// �ۼ��� ����
if ($count_write > 0 || $count_comment > 0)
    sql_query(" update $g4[board_table] set bo_count_write = bo_count_write - '$count_write', bo_count_comment = bo_count_comment - '$count_comment' where bo_table = '$bo_table' ");

// �Ҵ��� - min_wr_num ������Ʈ
$result = sql_fetch(" select MIN(wr_num) as min_wr_num from $write_table ");
$sql = " update $g4[board_table] set min_wr_num = '$result[min_wr_num]' where bo_table = '$bo_table' ";
sql_query($sql);

// 4.11
@include_once("$board_skin_path/delete_all.tail.skin.php");

if ($tmp_array_undeleted)
    alert("�Խñ� id {$tmp_array_undeleted}��/�� �Խ����� ������ ���ų� ũ�� ������ ������ �� �����ϴ�.", "./board.php?bo_table=$bo_table&page=$page" . $qstr);
else
    goto_url("./board.php?bo_table=$bo_table&page=$page" . $qstr);
?>
