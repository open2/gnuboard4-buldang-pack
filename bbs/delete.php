<?
include_once("./_common.php");

//$wr = sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");

@include_once("$board_skin_path/delete.head.skin.php");

if ($is_admin)
{
    if (!($token && get_session("ss_delete_token") == $token)) 
        alert("��ū ������ ���� �Ұ��մϴ�.");
}

if ($is_admin == "super") // �ְ������ ���
    ;
else if ($is_admin == "group") { // �׷������
    $mb = get_member($write[mb_id]);
    if ($member[mb_id] != $group[gr_admin]) // �ڽ��� �����ϴ� �׷��ΰ�?
        alert("�ڽ��� �����ϴ� �׷��� �Խ����� �ƴϹǷ� ������ �� �����ϴ�.");
    else if ($member[mb_level] < $mb[mb_level]) // �ڽ��� ������ ũ�ų� ���ٸ� ���
        alert("�ڽ��� ���Ѻ��� ���� ������ ȸ���� �ۼ��� ���� ������ �� �����ϴ�.");
} else if ($is_admin == "board") { // �Խ��ǰ������̸�
    $mb = get_member($write[mb_id]);
    if ($member[mb_id] != $board[bo_admin]) // �ڽ��� �����ϴ� �Խ����ΰ�?
        alert("�ڽ��� �����ϴ� �Խ����� �ƴϹǷ� ������ �� �����ϴ�.");
    else if ($member[mb_level] < $mb[mb_level]) // �ڽ��� ������ ũ�ų� ���ٸ� ���
        alert("�ڽ��� ���Ѻ��� ���� ������ ȸ���� �ۼ��� ���� ������ �� �����ϴ�.");
} else if ($member[mb_id]) {
    if ($member[mb_id] != $write[mb_id])
        alert("�ڽ��� ���� �ƴϹǷ� ������ �� �����ϴ�.");
} else {
    if ($write[mb_id])
        alert("�α��� �� �����ϼ���.", "$g4[bbs_path]/login.php?url=".urlencode("$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$wr_id"));
    else if (sql_password($wr_password) !== $write[wr_password])
        alert("�н����尡 Ʋ���Ƿ� ������ �� �����ϴ�."); 
}

// �Ҵ��� - ���������� �̵��� ���ؼ� �߰��� �ڵ�, /bbs/move_update.php�� �ϴܺο� �ڵ带 ����� �մϴ�
$recycle = "";
if ($config[cf_use_recycle] && $board[bo_use_recycle]) {

    // $config[cf_recycle_table]�� ���� ���� ������ ������ �����ش�
    if ($config[cf_recycle_table] == "")
        alert("�����ڿ��� - ������Խ����� �������� �ʾҽ��ϴ�. ������ �⺻ȯ�漳������ �������� �������ּ���.");

    // ����/�̵��� ���� log�� ������ �ʰ� ����
    $config[cf_use_copy_log] = 0;

    // ������ �Խ���
    $board['bo_move_bo_table'] = $config[cf_recycle_table];

    // recycle action���� ����
    $recycle = "recycle";
    
    // �Խñۿ� ���� �������� ������ �ƴϸ�, return
    if ($write[wr_id] !== $write[wr_parent])
        alert("���ۿ� ���ؼ��� ������ �۾� �Դϴ�");

    // �̺κ��� �Ʒ����� ������ �� �Դϴ�. ������ ������ �κ��� �ڿ�...
    // ������ ����Ʈ�� ���� �մϴ�.
    $sql = " select wr_id, mb_id, wr_is_comment from $write_table where wr_parent = '$write[wr_id]' order by wr_id ";
    $result_del = sql_query($sql);

    include_once("./move2_update.php");

    // recycle action - �ֽű��� �����
    $bn = sql_fetch(" select * from $g4[board_new_table] where bo_table = '$move_bo_table' and wr_id = '$insert_id' ");
    sql_query(" delete from $g4[board_new_table] where bo_table = '$move_bo_table' and wr_id = '$insert_id' ");
    
    // �Խ��ǿ��� ������ wr_id�� ã�ƾ�¡
    $sql = " select wr_parent from $move_write_table where wr_id='$insert_id' ";
    $res2 = sql_fetch($sql);
    
    // recycle action - recycle �Խ��ǿ� �۾���
    $sql = " insert into $g4[recycle_table]
                set 
                    rc_bo_table     = '$config[cf_recycle_table]',
                    rc_mb_id        = '$member[mb_id]',
                    rc_wr_id        = '$res2[wr_parent]',
                    rc_wr_parent    = '$res2[wr_parent]',
                    rc_parent_mb_id = '$bn[parent_mb_id]',
                    mb_id           = '$write[mb_id]',
                    bo_table        = '$board[bo_table]',
                    wr_id           = '$write[wr_id]',
                    wr_is_comment   = '$write[wr_is_comment]',
                    bn_id           = '$bn[bn_id]',
                    rc_datetime     = '$g4[time_ymdhis]' ";
    sql_query($sql);

    // �̺κ��� �Ʒ����� ������ �� �Դϴ�. ������ ������ �κ��� ����...
    // ������ ����Ʈ�� ���� �մϴ�.
    while ($row = sql_fetch_array($result_del)) 
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

            // �Ҵ��� - cheditor �̹��� ����
            $sql = " select * from $g4[board_cheditor_table] where bo_table = '$bo_table' and wr_id = '$write[wr_id]'";
            $result3 = sql_query($sql);
            while ($row3=sql_fetch_array($result3)) {
                $file_path = $row3[bc_dir] . "/" . $row3[bc_file];
                @unlink($file_path);
                $sql_d = " delete from $g4[board_cheditor_table] where bo_table = '$bo_table' and wr_id = '$write[wr_id]' and bc_url='$row3[bc_url]' ";
                sql_query($sql_d);
            }

            // �Ҵ��� - whaton ���� (���)
            $sql = " delete from $g4[whatson_table] where bo_table ='$bo_table' and wr_id = '$row[wr_id]' ";
            sql_query($sql);

            // �Ҵ��� - ��ü �������� ����
            $sql = " delete from $g4[notice_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ";
            sql_query($sql);

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

    goto_url($opener_href . $qstr);
}

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
if ($row[cnt] && !$is_admin)
    alert("�� �۰� ���õ� �亯���� �����ϹǷ� ���� �� �� �����ϴ�.\\n\\n�켱 �亯�ۺ��� �����Ͽ� �ֽʽÿ�.");

// �ڸ�Ʈ �޸� ������ ���� ����
$sql = " select count(*) as cnt from $write_table
          where wr_parent = '$wr_id'
            and mb_id <> '$member[mb_id]'
            and wr_is_comment = 1 ";
$row = sql_fetch($sql);
if ($row[cnt] >= $board[bo_count_delete] && !$is_admin)
    alert("�� �۰� ���õ� �ڸ�Ʈ�� �����ϹǷ� ���� �� �� �����ϴ�.\\n\\n�ڸ�Ʈ�� {$board[bo_count_delete]}�� �̻� �޸� ������ ������ �� �����ϴ�.");


// ����� �ڵ� ����
@include_once("$board_skin_path/delete.skin.php");


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
        $sql2 = " select * from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]' ";
        $result2 = sql_query($sql2);
        while ($row2 = sql_fetch_array($result2))
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

// �Ҵ��� - �Ű��� ������Ʈ
//sql_query(" delete from $g4[singo_table] where bo_table = '$bo_table' and wr_parent = '$write[wr_id]' ");
$sg_notes = "$member[mb_nick]($member[mb_id]) - $g4[time_ymdhis] - �Խñۻ���";
sql_query(" update $g4[singo_table] set sg_notes='$sg_notes' where bo_table = '$bo_table' and wr_parent = '$write[wr_id]' ");

// �������� ����
$notice_array = explode("\n", trim($board[bo_notice]));
$bo_notice = "";
for ($k=0; $k<count($notice_array); $k++)
    if ((int)$write[wr_id] != (int)$notice_array[$k])
        $bo_notice .= $notice_array[$k] . "\n";
$bo_notice = trim($bo_notice);
sql_query(" update $g4[board_table] set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");

// �ۼ��� ����
if ($count_write > 0 || $count_comment > 0)
    sql_query(" update $g4[board_table] set bo_count_write = bo_count_write - '$count_write', bo_count_comment = bo_count_comment - '$count_comment' where bo_table = '$bo_table' ");

// �Ҵ��� - min_wr_num ������Ʈ
$result = sql_fetch(" select MIN(wr_num) as min_wr_num from $write_table ");
$sql = " update $g4[board_table] set min_wr_num = '$result[min_wr_num]' where bo_table = '$bo_table' ";
sql_query($sql);

@include_once("$board_skin_path/delete.tail.skin.php");

goto_url("$g4[bbs_path]/board.php?bo_table=$bo_table&page=$page" . $qstr);
?>
