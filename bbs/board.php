<?
include_once("./_common.php");

if (!$board[bo_table])
{
    if ($cwin) // �ڸ�Ʈ ����
       alert_close("�������� �ʴ� �Խ����Դϴ�.", $g4[path]);
    else
       alert("�������� �ʴ� �Խ����Դϴ�.", $g4[path]);
}

if ($write[wr_is_comment])
{
    /*
    if ($cwin) // �ڸ�Ʈ ����
        alert_close("�ڸ�Ʈ�� �󼼺��� �Ͻ� �� �����ϴ�.");
    else
        alert("�ڸ�Ʈ�� �󼼺��� �Ͻ� �� �����ϴ�.");
    */
    goto_url("$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$write[wr_parent]#c_{$wr_id}");
}

if (!$bo_table)
{
    $msg = "bo_table ���� �Ѿ���� �ʾҽ��ϴ�.\\n\\nboard.php?bo_table=code �� ���� ������� �Ѱ� �ּ���.";
    if ($cwin) // �ڸ�Ʈ ����
        alert_close($msg);
    else
        alert($msg);
}

// wr_id ���� ������ ���б�
if ($wr_id)
{
    // ���� ���� ��� �ش� �Խ��� ������� �̵�
    if (!$write[wr_id])
    {
        $msg = "���� �������� �ʽ��ϴ�.\\n\\n���� �����Ǿ��ų� �̵��� ����Դϴ�.";
        if ($cwin)
            alert_close($msg);
        else
            alert($msg, "$g4[bbs_path]/board.php?bo_table=$bo_table");
    }

    // �׷����� ���
    if ($group[gr_use_access])
    {
        if (!$member[mb_id]) {
            $msg = "��ȸ���� �� �Խ��ǿ� ������ ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.";
            if ($cwin)
                alert_close($msg);
            else
                alert($msg, "$g4[bbs_path]/login.php?wr_id=$wr_id{$qstr}&url=".urlencode("$_SERVER[PHP_SELF]?bo_table=$bo_table&wr_id=$wr_id"));
        }

        // �׷������ �̻��̶�� ���
        if ($is_admin == "super" || $is_admin == "group")
            ;
        else
        {
            // �׷�����
            $sql = " select count(*) as cnt
                       from $g4[group_member_table]
                      where gr_id = '$board[gr_id]' and mb_id = '$member[mb_id]' ";
            $row = sql_fetch($sql);
            if (!$row[cnt])
                alert("���� ������ �����Ƿ� ���бⰡ �Ұ��մϴ�.\\n\\n�ñ��Ͻ� ������ �����ڿ��� ���� �ٶ��ϴ�.", $g4[path]);
        }
    }

    // �α��ε� ȸ���� ������ ������ �б� ���Ѻ��� �۴ٸ�
    if ($member[mb_level] < $board[bo_read_level])
    {
        if ($member[mb_id])
            //alert("���� ���� ������ �����ϴ�.");
            alert("���� ���� ������ �����ϴ�.", $g4[path]);
        else
            alert("���� ���� ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.", "$g4[bbs_path]/login.php?wr_id=$wr_id{$qstr}&url=".urlencode("$g4[path]/$bo_table/$wr_id"));
    }

    // �ڽ��� ���̰ų� �����ڶ�� ���
    if (($write[mb_id] && $write[mb_id] == $member[mb_id]) || $is_admin)
        ;
    else
    {
        // ��б��̶��
        if (strstr($write[wr_option], "secret"))
        {
            // �⺻���δ� ��б��� �� �� ����.
            $is_unlock_secret = 0;
            $is_owner = false;

            // ȸ���� ��б��� �ø��� �����ڰ� �亯���� �÷��� ���
            // ȸ���� �����ڰ� �ø� �亯���� �ٷ� �� �� ���� ������ ����
            if ($write[wr_reply] && $member[mb_id])
            {
                $sql = " select mb_id from $write_table
                          where wr_num = '$write[wr_num]'
                            and wr_reply = ''
                            and wr_is_comment = '0' ";
                $row = sql_fetch($sql);
                if ($row['mb_id'] == $member['mb_id'])
                    $is_owner = true;
            }
            
            // ����� �޾��� ��쿡 ������ ��״� ���� ����� �� ȸ���� �ֱ��� ��Ż�ϴ� ����
            // ��ȸ���� ������ Ȯ���ϴ� ���� �н����� �Է����� ���� ���� ����� risk�� Ŀ���Ƿ� ȸ���� ��쿡 ���ؼ��� ���
            if ($member['mb_id']) {
                $sql = " select count(*) as cnt from $write_table 
                          where wr_parent = '$wr_id'
                            and mb_id = '$member[mb_id]' 
                            and wr_is_comment = '1' ";
                $row = sql_fetch($sql);
                if ($row['cnt'] > 0 && !$is_owner) {
                    $is_unlock_secret = 1;
                    $is_owner = 1;
                }
            }

            $ss_name = 'ss_secret_'.$bo_table.'_'.$write['wr_num'];

            if (!$is_owner)
            {
                //$ss_name = "ss_secret_{$bo_table}_{$wr_id}";
                // �ѹ� ���� �Խù��� ��ȣ�� ���ǿ� ����Ǿ� �ְ� ���� �Խù��� ���� ���� �ٽ� �н����带 ���� �ʽ��ϴ�.
                // �� �Խù��� ����� �Խù��� �ƴϸ鼭 �����ڰ� �ƴ϶��
                //if ("$bo_table|$write[wr_num]" != get_session("ss_secret")) 
                if (!get_session($ss_name)) 
                    goto_url("$g4[bbs_path]/password.php?w=s&bo_table=$bo_table&wr_id=$wr_id{$qstr}");
            }

            // $write[wr_num] -> $wr_id... ����� ������ ��۸� �޸� ��� �� ���� ���� ����
            $ss_name = "ss_secret_{$bo_table}_{$wr_id}";
            set_session($ss_name, TRUE);
        }
    }

    // �ѹ� �������� �������� �ݱ��������� ī��Ʈ�� ������Ű�� ����
    $ss_name = "ss_view_{$bo_table}_{$wr_id}";
    if (!get_session($ss_name))
    {
        sql_query(" update $write_table set wr_hit = wr_hit + 1 where wr_id = '$wr_id' ");

        // �ڽ��� ���̸� ���
        if ($write[mb_id] && $write[mb_id] == $member[mb_id]) {
            ;
        } else if ($is_guest && $board[bo_read_level] == 1 && $write[wr_ip] == $_SERVER['REMOTE_ADDR']) {
            // ��ȸ���̸鼭 �бⷹ���� 1�̰� ��ϵ� �����ǰ� ���ٸ� �ڽ��� ���̹Ƿ� ���
            ;
        } else {
            /*
            // ȸ���̻� ���бⰡ �����ϴٸ�
            if ($board[bo_read_level] > 1) {
                if ($member[mb_point] + $board[bo_read_point] < 0)
                    alert("�����Ͻ� ����Ʈ(".number_format($member[mb_point]).")�� ���ų� ���ڶ� ���б�(".number_format($board[bo_read_point]).")�� �Ұ��մϴ�.\\n\\n����Ʈ�� ������ �� �ٽ� ���б� �� �ֽʽÿ�.");
            }
            
            // ȸ�����Ը� ����Ʈ�� insert
            if ($member[mb_id])
                insert_point($member[mb_id], $board[bo_read_point], "$board[bo_subject] $wr_id ���б�", $bo_table, $wr_id, '�б�');
            */
            // ���б� ����Ʈ�� �����Ǿ� �ִٸ�
            if ($board[bo_read_point_lock] && $board[bo_read_point] && $member[mb_point] + $board[bo_read_point] < 0)
                alert("�����Ͻ� ����Ʈ(".number_format($member[mb_point]).")�� ���ų� ���ڶ� ���б�(".number_format($board[bo_read_point]).")�� �Ұ��մϴ�.\\n\\n����Ʈ�� ������ �� �ٽ� ���б� �� �ֽʽÿ�.");

            // ȸ�����Ը� ����Ʈ�� insert
            if ($member[mb_id])
                insert_point($member[mb_id], $board[bo_read_point], "$board[bo_subject] $wr_id ���б�", $bo_table, $wr_id, '�б�');
            
            // �Ҵ��� - ��ȸ���� �������� �̻��̸� �α�۷� �Ƿη�~ �׷���, $write[wr_view]�� ���� ������, ������ ���̰� 1 �� �� �ִ�.
            $write['wr_hit2'] = $write['wr_hit'] + 1;
            if ($board[bo_list_view] > 0) {
                if ($write[wr_hit2] > 0 && $write[wr_hit2] >= $board[bo_list_view]) {
                    // UPDATE�� �����ϰ� ������ �߻��� insert�� ����
                    $sql = " update $g4[good_list_table] set hit = '$write[wr_hit2]' where bo_table='$bo_table' and wr_id='$wr_id' ";
                    $result = sql_query($sql, FALSE);
                    if ( mysql_affected_rows() == 0 ) {
                        // select�� �ؼ� ������, �н�, ������ insert
                        $sql = " insert $g4[good_list_table] ( mb_id, gr_id, bo_table, wr_id, gl_datetime, hit, wr_datetime) values ( '$write[mb_id]', '$board[gr_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$write[wr_hit2]',  '$write[wr_datetime]') ";
                        $result = sql_query($sql, FALSE);
                    }
                }
            }
        }

        set_session($ss_name, TRUE);
    }

    // �Ҵ��� - SEO + ���̹� �������� ������ ���ؼ� ����.
    $g4[title] =  strip_tags(conv_subject($write[wr_subject], 255)) . " :: " .   $config[cf_title];
}
else
{
    if ($member[mb_level] < $board[bo_list_level])
    {
        if ($member[mb_id])
            alert("����� �� ������ �����ϴ�.", $g4[path]);
        else
            alert("����� �� ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.", "$g4[bbs_path]/login.php?wr_id=$wr_id{$qstr}&url=".urlencode("$_SERVER[PHP_SELF]/$bo_table/$wr_id"));
    }

    if (!$page) $page = 1;

    $g4[title] = "$group[gr_subject] > $board[bo_subject] $page ������";
}

include_once("$g4[path]/head.sub.php");

$width = $board[bo_table_width];
if ($width <= 100)
    $width .= '%';
else
    $width .= 'px'; 

// IP���̱� ��� ����
$ip = "";
$is_ip_view = $board[bo_use_ip_view];
if ($is_admin) {
    $is_ip_view = true;
    $ip = $write[wr_ip];
} else // �����ڰ� �ƴ϶�� IP �ּҸ� ������ �����ݴϴ�.
    $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.��.\\3.\\4", $write[wr_ip]);

// �з� ���
$is_category = false;
$category_name = "";
if ($board[bo_use_category]) {
    $is_category = true;
    $category_name = $write[ca_name]; // �з���
}

// ��õ ���
$is_good = false;
if ($board[bo_use_good])
    $is_good = true;

// ����õ ���
$is_nogood = false;
if ($board[bo_use_nogood])
    $is_nogood = true;

$admin_href = "";
// �ְ������ �Ǵ� �׷�����ڶ��
if ($member[mb_id] && ($is_admin == 'super' || $group[gr_admin] == $member[mb_id]))
    $admin_href = "$g4[admin_path]/board_form.php?w=u&bo_table=$bo_table";

if (!($board[bo_use_comment] && $cwin))
    include_once("$g4[bbs_path]/board_head.php");

if (!($board[bo_use_comment] && $cwin)) {
    // �Խù� ���̵� �ִٸ� �Խù� ���⸦ INCLUDE
    if ($wr_id)
        include_once("$g4[bbs_path]/view.php");

    // ��ü��Ϻ��̱� ����� "��" �Ǵ� wr_id ���� ���ٸ� ����� ����
    //if ($board[bo_use_list_view] || empty($wr_id))
    if ($member[mb_level] >= $board[bo_list_level] && $board[bo_use_list_view] || empty($wr_id))
        include_once ("./list.php");

    include_once("$g4[bbs_path]/board_tail.php");
}
else
    include_once("$g4[bbs_path]/view_comment.php");

echo "\n<!-- ��뽺Ų : $board[bo_skin] -->\n";

include_once("$g4[path]/tail.sub.php");
?>

<?
// ���� �湮�� �Խ��� ������ db�� ���
if ($member[mb_id]) {
    sql_query(" update $g4[my_board_table] set my_datetime = '$g4[time_ymdhis]' where mb_id = '$member[mb_id]' and bo_table = '$bo_table' ");
    if (mysql_affected_rows() == 0) 
        sql_query(" insert $g4[my_board_table] set mb_id = '$member[mb_id]', bo_table = '$bo_table', my_datetime = '$g4[time_ymdhis]' ", FALSE );
}
?>