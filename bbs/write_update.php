<?
include_once("./_common.php");

$g4[title] = $wr_subject . "���Է�";

// 090710
/*
if (substr_count($wr_content, "&#") > 50) {
    alert("���뿡 �ùٸ��� ���� �ڵ尡 �ټ� ���ԵǾ� �ֽ��ϴ�.");
    exit;
}
*/

// �ڵ���Ϲ��� �˻� - ��ȸ���� ��츸
if (!$is_member) {
    if ($w=='' || $w=='r') {
        if (chk_recaptcha() == false)
            alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.', $goto_url);
    }
}

// ���� ���� �������� �Խ��ǿ� �� �Ͻô� �е��� ���ؼ�. �ڿ��� ���� �����Ŀ� ������ �Ͱ� ��
if ($w == "" || $w == "r") {
    if ($is_admin == "") {
        $tmp_subject = md5($_SERVER[REMOTE_ADDR].$wr_subject);
        if ($tmp_subject == $_SESSION['ss_wr_subject'])
            alert("���뿡 �ùٸ��� ���� �ڵ尡 �ټ� ���ԵǾ� �ֽ��ϴ�.");
    }
}

// �Ҵ��� - ������ ����� ���� ��~ �����, �ӽ����� DB�� ������ ���ݴϴ�.
$ss_tempsave = $_SESSION[ss_tempsave];
if ($ss_tempsave) {
    //$sql = " delete from $g4[tempsave_table] where wr_session='$ss_tempsave' ";
    //sql_query($sql);

    $stmt = $pdo_db->prepare(" delete from $g4[tempsave_table] where wr_session=:ss_tempsave ");
    $stmt->bindParam(":ss_tempsave", $ss_tempsave);
    $result = pdo_query($stmt, false);
}

/*
$sql = " insert into $g4[tempsave_table] 
            set 
                bo_table='$bo_table', 
                wr_id='$wr_id',
                wr_subject='$wr_subject', 
                wr_content='$wr_content', 
                mb_id='$member[mb_id]', 
                wr_datetime='$g4[time_ymdhis]', 
                ip_addr = '$remote_addr',
                wr_session='$ss_tempsave' ";
sql_query($sql);
*/
$sql = " insert into $g4[tempsave_table] 
            set 
                bo_table=:bo_table, 
                wr_id=:wr_id,
                wr_subject=:wr_subject, 
                wr_content=:wr_content, 
                mb_id='$member[mb_id]', 
                wr_datetime='$g4[time_ymdhis]', 
                ip_addr = '$remote_addr',
                wr_session=:ss_tempsave ";
$stmt = $pdo_db->prepare($sql);
$stmt->bindParam(":bo_table", $bo_table);
$stmt->bindParam(":wr_id", $wr_id);
$stmt->bindParam(":wr_subject", $wr_subject);
$stmt->bindParam(":wr_content", $wr_content);
$stmt->bindParam(":ss_tempsave", $ss_tempsave);
$result = pdo_query($stmt, false);


// �ӽ� DB ������ ���ؼ� ���ư� url�� ������ �ݴϴ�.
if ($w == "r")
    $goto_url = "$g4[bbs_path]/write.php?bo_table=$bo_table&w=r&wr_id=$wr_id";
else if ($w == "u")
    $goto_url = "$g4[bbs_path]/write.php?bo_table=$bo_table&w=u&wr_id=$wr_id";
else
    $goto_url = "$g4[bbs_path]/write.php?bo_table=$bo_table";

if (file_exists("$board_skin_path/write_update.head.skin.php"))
    @include_once("$board_skin_path/write_update.head.skin.php");

/*
$filters = explode(",", $config[cf_filter]);
for ($i=0; $i<count($filters); $i++) {
    $s = trim($filters[$i]); // ���ʹܾ��� �յ� ������ ����
    if (stristr($wr_subject, $s)) {
        alert("���� �����ܾ�(\'{$s}\')�� ���ԵǾ� �ֽ��ϴ�.");
        exit;
    }
    if (stristr($wr_content, $s)) {
        alert("���뿡 �����ܾ�(\'{$s}\')�� ���ԵǾ� �ֽ��ϴ�.");
        exit;
    }
}
*/

if($board['bo_use_category']) {
    $ca_name = trim($_POST['ca_name']);
    if(!$ca_name) {
            alert("�з��� �����ϼ���");
    } else {
        $categories = array_map('trim', explode("|", $board['bo_category_list'].($is_admin ? '|����' : '')));
        if(!empty($categories) && !in_array($ca_name, $categories))
            alert("�з��� �ùٸ��� �Է��ϼ���");
    }
}

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST))
    alert("���� �Ǵ� �۳����� ũ�Ⱑ �������� ������ ���� �Ѿ� ������ �߻��Ͽ����ϴ�.\\n\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=$upload_max_filesize\\n\\n�Խ��ǰ����� �Ǵ� ���������ڿ��� ���� �ٶ��ϴ�.");

// ���۷� üũ
//referer_check();

$w = $_POST['w'];
$wr_link1 = mysql_real_escape_string(strip_tags($_POST['wr_link1']));
$wr_link2 = mysql_real_escape_string(strip_tags($_POST['wr_link2']));
$wr_email = mysql_real_escape_string(strip_tags($_POST['wr_email']));

$notice_array = explode("\n", trim($board[bo_notice]));

if ($w == "u" || $w == "r") {
    $wr = get_write($write_table, $wr_id);
    if (!$wr[wr_id])
        alert("���� �������� �ʽ��ϴ�.\\n\\n���� �����Ǿ��ų� �̵��Ͽ��� �� �ֽ��ϴ�.", $goto_url); 
}

// �ܺο��� ���� ����� �� �ִ� ���װ� �����ϹǷ� ��б��� ����� ��쿡�� �����ؾ� ��
if (!$is_admin && !$board[bo_use_secret] && $secret)
	alert("��б� �̻�� �Խ��� �̹Ƿ� ��б۷� ����� �� �����ϴ�.", $goto_url);

// �ܺο��� ���� ����� �� �ִ� ���װ� �����ϹǷ� ��б� ������ ����϶��� �����ڸ� ����(����)�ϰ� ������ ��б۷� ���
if (!$is_admin && $board[bo_use_secret] == 2) {
    $secret = "secret";
}

// �Ҵ��� - ��ü���� (�ְ� �������϶���..._
// �������� ��ü������ �Ұ����� check, 
// ���� insert�� ���� �Ʒ�����. �ű��� ��� wr_id�� �����ϱ�)
$insert_g_notice = 0;
if ($is_admin == "super") {

    if ($w == "") {
        if ($g_notice) {
            // ��ü������ ��쿡��, �ڵ����� ���� �Խñ۷� üũ�� �ݴϴ�.
            $notice = 1;
            $insert_g_notice = 1;
        }
    } 
    else 
    if ($w == "u") {
        // ��ü������ �ִ��� Ȯ��
        $sql = " SELECT count(*) as cnt from $g4[notice_table] where bo_table = '$bo_table' and wr_id = '$wr_id' ";
        $cnt = sql_fetch($sql);

        if ($g_notice) {
            if ($cnt[cnt] > 0)
                ;
            else {
                // ��ü������ ��쿡��, �ڵ����� ���� �Խñ۷� üũ�� �ݴϴ�.
                $notice = 1;
                $insert_g_notice = 1;
            }
        } else {
            if ($cnt[cnt] > 0) {
                // ��ü������ ���������� ����������, �Ϲݰ����� ���δ� �׳� ���д�
                $sql = " DELETE FROM $g4[notice_table] where bo_table = '$bo_table' and wr_id = '$wr_id' ";
                sql_query($sql);
            }
        }
    }
}

// �Ҵ��� - bbs/write.php���� ������ �ڵ�
// 090713
if ($board[bo_use_dhtml_editor] && $member[mb_level] >= $board[bo_html_level])
    $is_dhtml_editor = true;
else
    $is_dhtml_editor = false;

if ($w == "" || $w == "u") {
    // �輱�� 1.00 : �۾��� ���Ѱ� ������ ������ ó���Ǿ�� ��
    if($w =="u" && $member['mb_id'] && $wr['mb_id'] == $member['mb_id'])
        ;
    else if ($member[mb_level] < $board[bo_write_level]) 
        alert("���� �� ������ �����ϴ�.", $goto_url);

	// �ܺο��� ���� ����� �� �ִ� ���װ� �����ϹǷ� ������ �����ڸ� ����� �����ؾ� ��
	if (!$is_admin && $notice)
		alert("�����ڸ� ������ �� �ֽ��ϴ�.", $goto_url);
} 
else if ($w == "r") 
{
    if (in_array((int)$wr_id, $notice_array))
        alert("�������� �亯 �� �� �����ϴ�.", $goto_url);

    if ($member[mb_level] < $board[bo_reply_level]) 
        alert("���� �亯�� ������ �����ϴ�.", $goto_url);

    // �Խñ� �迭 ����
    $reply_array = &$wr;

    // �ִ� �亯�� ���̺� ��Ƴ��� wr_reply �����ŭ�� �����մϴ�.
    if (strlen($reply_array[wr_reply]) == 10)
        alert("�� �̻� �亯�Ͻ� �� �����ϴ�.\\n\\n�亯�� 10�ܰ� ������ �����մϴ�.", $goto_url);

    $reply_len = strlen($reply_array[wr_reply]) + 1;
    if ($board[bo_reply_order]) {
        $begin_reply_char = "A";
        $end_reply_char = "Z";
        $reply_number = +1;
        $sql = " select MAX(SUBSTRING(wr_reply, $reply_len, 1)) as reply from $write_table where wr_num = '$reply_array[wr_num]' and SUBSTRING(wr_reply, $reply_len, 1) <> '' ";
    } else {
        $begin_reply_char = "Z";
        $end_reply_char = "A";
        $reply_number = -1;
        $sql = " select MIN(SUBSTRING(wr_reply, $reply_len, 1)) as reply from $write_table where wr_num = '$reply_array[wr_num]' and SUBSTRING(wr_reply, $reply_len, 1) <> '' ";
    }
    if ($reply_array[wr_reply]) $sql .= " and wr_reply like '$reply_array[wr_reply]%' ";
    $row = sql_fetch($sql);

    if (!$row[reply])
        $reply_char = $begin_reply_char;
    else if ($row[reply] == $end_reply_char) // A~Z�� 26 �Դϴ�.
        alert("�� �̻� �亯�Ͻ� �� �����ϴ�.\\n\\n�亯�� 26�� ������ �����մϴ�.", $goto_url);
    else
        $reply_char = chr(ord($row[reply]) + $reply_number);

    $reply = $reply_array[wr_reply] . $reply_char;
} else 
    alert("w ���� ����� �Ѿ���� �ʾҽ��ϴ�.", $goto_url); 


if ($w == "" || $w == "r") 
{
    if ($_SESSION["ss_datetime"] >= ($g4[server_time] - $config[cf_delay_sec]) && !$is_delay) 
        alert("�ʹ� ���� �ð����� �Խù��� �����ؼ� �ø� �� �����ϴ�.", $goto_url);

    set_session("ss_datetime", $g4[server_time]);

    // ���ϳ��� ���� ��� �Ұ�
    $row = sql_fetch(" select MD5(CONCAT(wr_ip, wr_subject, wr_content)) as prev_md5 from $write_table order by wr_id desc limit 1 ");
    $curr_md5 = md5($_SERVER[REMOTE_ADDR].$wr_subject.$wr_content);
    if ($row[prev_md5] == $curr_md5 && !$is_admin)
        alert("������ ������ �����ؼ� ����� �� �����ϴ�.", $goto_url);
}

if (!isset($_POST[wr_subject]) || !trim($_POST[wr_subject])) 
    alert("������ �Է��Ͽ� �ֽʽÿ�.", $goto_url); 

if ($w == "" || $w == "r") 
{
    if ($member[mb_id]) 
    {
        $mb_id = $member[mb_id];
        $wr_name = $board[bo_use_name] ? $member[mb_name] : $member[mb_nick];
        $wr_password = $member[mb_password];
        $wr_email = $member[mb_email];
        $wr_homepage = $member[mb_homepage];
    } 
    else 
    {
        $mb_id = "";
        // ��ȸ���� ��� �̸��� �����Ǵ� ��찡 ����
        $wr_name = strip_tags(mysql_real_escape_string($_POST['wr_name']));
        if (!trim($wr_name))
            alert("�̸��� ���� �Է��ϼž� �մϴ�.", $goto_url);
        $wr_password = sql_password($wr_password);
    }

    if ($w == "r") 
    {
        // �亯�� ������ ��б��̶�� �н������ ���۰� �����ϰ� �ִ´�.
        if ($secret)
            $wr_password = $wr[wr_password];

        //�Ҵ��� - ����� ��� ������ mb_id�� �Է�
        $parent_mb_id = sql_fetch(" select mb_id from $write_table where wr_id = '$wr_id' ");
        
        $wr_id = $wr_id . $reply;
        $wr_num = $write[wr_num];
        $wr_reply = $reply;
        
        // �Ҵ��� - ������ ���� (����� ���+�����ڿ� ����ڰ� �ٸ� ���)
        if ($parent_mb_id != $member[mb_id])
            sql_query(" update $g4[board_new_table] set my_datetime = '$g4[time_ymdhis]' where bo_table = '$bo_table' and wr_id = '$wr_id' ");
    }
    else 
    {
        $wr_num = get_next_num($write_table);
        $wr_reply = "";
    }

    $sql = " insert into $write_table
                set wr_num = '$wr_num',
                    wr_reply = '$wr_reply',
                    wr_comment = 0,
                    ca_name = '$ca_name',
                    wr_option = '$html,$secret,$mail',
                    wr_subject = '$wr_subject',
                    wr_content = '$wr_content',
                    wr_link1 = '$wr_link1',
                    wr_link2 = '$wr_link2',
                    wr_link1_hit = 0,
                    wr_link2_hit = 0,
                    wr_hit = 0,
                    wr_good = 0,
                    wr_nogood = 0,
                    mb_id = '$member[mb_id]',
                    wr_password = '$wr_password',
                    wr_name = '$wr_name',
                    wr_email = '$wr_email',
                    wr_homepage = '$wr_homepage',
                    wr_datetime = '$g4[time_ymdhis]',
                    wr_last = '$g4[time_ymdhis]',
                    wr_ip = '$_SERVER[REMOTE_ADDR]',
                    wr_related = '$wr_related',
                    wr_1 = '$wr_1',
                    wr_2 = '$wr_2',
                    wr_3 = '$wr_3',
                    wr_4 = '$wr_4',
                    wr_5 = '$wr_5',
                    wr_6 = '$wr_6',
                    wr_7 = '$wr_7',
                    wr_8 = '$wr_8',
                    wr_9 = '$wr_9',
                    wr_10 = '$wr_10' ";
    sql_query($sql);

    $wr_id = mysql_insert_id();

    // �θ� ���̵� UPDATE
    sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

    // ���� INSERT
    //sql_query(" insert into $g4[board_new_table] ( bo_table, wr_id, wr_parent, bn_datetime, mb_id, wr_is_comment, gr_id, wr_option, parent_mb_id) 
    //            values ( '$bo_table', '$wr_id', '$wr_id', '$g4[time_ymdhis]', '$member[mb_id]', '0', '$gr_id', '$secret', '$parent_mb_id[mb_id]') "); 

    $stmt = $pdo_db->prepare(" insert into $g4[board_new_table] ( bo_table, wr_id, wr_parent, bn_datetime, mb_id, wr_is_comment, gr_id, wr_option, parent_mb_id) values ( :bo_table, '$wr_id', '$wr_id', '$g4[time_ymdhis]', '$member[mb_id]', '0', :gr_id, '$secret', '$parent_mb_id[mb_id]') ");
    $stmt->bindParam(":gr_id", $gr_id);
    $stmt->bindParam(":bo_table", $bo_table);
    $result = pdo_query($stmt, false);
    
    // �Խñ� 1 ����
    //sql_query(" update $g4[board_table] set bo_count_write = bo_count_write + 1, bo_modify_datetime = '$g4[time_ymdhis]' where bo_table = '$bo_table' ");

    $stmt = $pdo_db->prepare(" update $g4[board_table] set bo_count_write = bo_count_write + 1, bo_modify_datetime = '$g4[time_ymdhis]' where bo_table = :bo_table ");
    $stmt->bindParam(":bo_table", $bo_table);
    $result = pdo_query($stmt, false);

    // ���� ����Ʈ �ο�
    if ($w == '') 
    {
        if ($notice)
        {
            $bo_notice = $wr_id . "\n" . $board[bo_notice];
            sql_query(" update $g4[board_table] set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");
        }

        insert_point($member[mb_id], $board[bo_write_point], "$board[bo_subject] $wr_id �۾���", $bo_table, $wr_id, '����');
    }
    else 
    {
        // �亯�� �ڸ�Ʈ ����Ʈ�� �ο���
        // �亯 ����Ʈ�� ���� ��� �ڸ�Ʈ ��� �亯�� �ϴ� ��찡 ����
        insert_point($member[mb_id], $board[bo_comment_point], "$board[bo_subject] $wr_id �۴亯", $bo_table, $wr_id, '����');
    }

    // �Ҵ��� - ��~��~ : ����� �Լ� �뺸, ������ ȸ���� ���̰�, ������ ȸ�����̵�� ���� �۾��� ���̵� �ٸ� ��쿡��
    if ($w == 'r' && $wr[mb_id] && $wr[mb_id] !== $member[mb_id]) 
    {
        /*
        $tsql = " UPDATE $g4[whatson_table] 
                      SET wr_subject = '" . get_text(stripslashes($wr[wr_subject])) . "',
                          wo_count = wo_count+1,
                          wo_datetime = '$g4[time_ymdhis]' 
                    where bo_table = '$bo_table' and wr_id='$wr[wr_id]' and mb_id='$wr[mb_id]' and wo_type='write_reply' ";
        sql_query($tsql);
        */
        $tsql = " UPDATE $g4[whatson_table] 
                      SET wr_subject = :wr_subject,
                          wo_count = wo_count+1,
                          wo_datetime = '$g4[time_ymdhis]' 
                    where bo_table = :bo_table and wr_id='$wr[wr_id]' and mb_id='$wr[mb_id]' and wo_type='write_reply' ";

        $stmt = $pdo_db->prepare($tsql);
        $stmt->bindParam(":wr_subject", get_text(stripslashes($wr[wr_subject])));
        $stmt->bindParam(":bo_table", $bo_table);
        $result = pdo_query($stmt, false);

        // update�� �ȵǴ� ��쿡�� insert�� �մϴ�.
        //if (!mysql_affected_rows()) {
        if ($stmt->rowCount() < 1) {
            /*
            $tsql = " insert into $g4[whatson_table] ( mb_id, wr_subject, wo_type, wo_count, wo_datetime, bo_table, wr_id ) 
                      values ('$wr[mb_id]', '" . get_text(stripslashes($wr[wr_subject])) . "','write_reply','1','$g4[time_ymdhis]','$bo_table','$wr[wr_id]') ";
            sql_query($tsql);
            */
            $tsql = " insert into $g4[whatson_table] ( mb_id, wr_subject, wo_type, wo_count, wo_datetime, bo_table, wr_id ) 
                      values ('$wr[mb_id]', :wr_subject,'write_reply','1','$g4[time_ymdhis]', :bo_table,'$wr[wr_id]') ";

            $stmt = $pdo_db->prepare($tsql);
            $stmt->bindParam(":wr_subject", get_text(stripslashes($wr[wr_subject])));
            $stmt->bindParam(":bo_table", $bo_table);
            $result = pdo_query($stmt, false);
        }
    }
} 
else if ($w == "u") 
{
    if (get_session('ss_bo_table') != $_POST['bo_table'] || get_session('ss_wr_id') != $_POST['wr_id']) {
        alert('�ùٸ� ������� �����Ͽ� �ֽʽÿ�.');
    }

    if ($is_admin == "super") // �ְ������ ���
        ;
    else if ($is_admin == "group") { // �׷������
        $mb = get_member($write[mb_id]);
        if ($member[mb_id] != $group[gr_admin]) // �ڽ��� �����ϴ� �׷��ΰ�?
            alert("�ڽ��� �����ϴ� �׷��� �Խ����� �ƴϹǷ� ������ �� �����ϴ�.", $goto_url);
        else if ($member[mb_level] < $mb[mb_level]) // �ڽ��� ������ ũ�ų� ���ٸ� ���
            alert("�ڽ��� ���Ѻ��� ���� ������ ȸ���� �ۼ��� ���� ������ �� �����ϴ�.", $goto_url);
    } else if ($is_admin == "board") { // �Խ��ǰ������̸�
        $mb = get_member($write[mb_id]);
        if ($member[mb_id] != $board[bo_admin]) // �ڽ��� �����ϴ� �Խ����ΰ�?
            alert("�ڽ��� �����ϴ� �Խ����� �ƴϹǷ� ������ �� �����ϴ�.", $goto_url);
        else if ($member[mb_level] < $mb[mb_level]) // �ڽ��� ������ ũ�ų� ���ٸ� ���
            alert("�ڽ��� ���Ѻ��� ���� ������ ȸ���� �ۼ��� ���� ������ �� �����ϴ�.", $goto_url);
    } else if ($member[mb_id]) {
        if ($member[mb_id] != $write[mb_id])
            alert("�ڽ��� ���� �ƴϹǷ� ������ �� �����ϴ�.", $goto_url);
    } else {
        if ($write[mb_id]) {
            alert("�α��� �� �����ϼ���.", "./login.php?url=".urlencode("./board.php?bo_table=$bo_table&wr_id=$wr_id"));
        }
    }

    if ($member[mb_id]) 
    {
        // �ڽ��� ���̶��
        if ($member[mb_id] == $wr[mb_id]) 
        {
            $mb_id = $member[mb_id];
            $wr_name = $board[bo_use_name] ? $member[mb_name] : $member[mb_nick];
            $wr_email = $member[mb_email];
            $wr_homepage = $member[mb_homepage];
        } 
        else
        {
            $mb_id = $wr[mb_id];
            $wr_name = $wr[wr_name];
            $wr_email = $wr[wr_email];
            $wr_homepage = $wr[wr_homepage];
        }
    } 
    else 
    {
        $mb_id = "";
        // ��ȸ���� ��� �̸��� �����Ǵ� ��찡 ����
        //if (!trim($wr_name)) alert("�̸��� ���� �Է��ϼž� �մϴ�.");
    }

    $sql_password = $wr_password ? " , wr_password = '".sql_password($wr_password)."' " : "";

    $sql_ip = "";
    if (!$is_admin)
        $sql_ip = " , wr_ip = '$_SERVER[REMOTE_ADDR]' ";

    $sql = " update $write_table
                set ca_name = '$ca_name',
                    wr_option = '$html,$secret,$mail',
                    wr_subject = '$wr_subject',
                    wr_content = '$wr_content',
                    wr_link1 = '$wr_link1',
                    wr_link2 = '$wr_link2',
                    mb_id = '$mb_id',
                    wr_name = '$wr_name',
                    wr_email = '$wr_email',
                    wr_homepage = '$wr_homepage',
                    wr_related = '$wr_related',
                    wr_1 = '$wr_1',
                    wr_2 = '$wr_2',
                    wr_3 = '$wr_3',
                    wr_4 = '$wr_4',
                    wr_5 = '$wr_5',
                    wr_6 = '$wr_6',
                    wr_7 = '$wr_7',
                    wr_8 = '$wr_8',
                    wr_9 = '$wr_9',
                    wr_10= '$wr_10'
                    $sql_ip
                    $sql_password
              where wr_id = '$wr[wr_id]' ";
    sql_query($sql);

    // �з��� �����Ǵ� ��� �ش�Ǵ� �ڸ�Ʈ�� �з��� ��� ������
    // �ڸ�Ʈ�� �з��� �������� ������ �˻��� ����� ���� ����
    $sql = " update $write_table set ca_name = '$ca_name' where wr_parent = '$wr[wr_id]' ";
    sql_query($sql);

    // ������ �ۿ� wr_option UPDATE
    sql_query(" update $g4[board_new_table] set wr_option = '$secret' where bo_table = '$bo_table' and wr_id = '$wr[wr_id]' ");
                
    if ($notice) 
    {
        if (!in_array((int)$wr_id, $notice_array))
        {
            $bo_notice = $wr_id . "\n" . $board[bo_notice];
            //sql_query(" update $g4[board_table] set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");

            $stmt = $pdo_db->prepare(" update $g4[board_table] set bo_notice = :bo_notice where bo_table = :bo_table ");
            $stmt->bindParam(":bo_notice", $bo_notice);
            $stmt->bindParam(":bo_table", $bo_table);
            $result = pdo_query($stmt, false);
        }
    } 
    else 
    {
        $bo_notice = '';
        for ($i=0; $i<count($notice_array); $i++)
            if ((int)$wr_id != (int)$notice_array[$i])
                $bo_notice .= $notice_array[$i] . "\n";
        $bo_notice = trim($bo_notice);
        //sql_query(" update $g4[board_table] set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");

        $stmt = $pdo_db->prepare(" update $g4[board_table] set bo_notice = :bo_notice where bo_table = :bo_table ");
        $stmt->bindParam(":bo_notice", $bo_notice);
        $stmt->bindParam(":bo_table", $bo_table);
        $result = pdo_query($stmt, false);
    }
}

// ���丮�� ���ٸ� �����մϴ�. (�۹̼ǵ� �����ϱ���.)
@mkdir("$g4[data_path]/file/$bo_table", 0707);
@chmod("$g4[data_path]/file/$bo_table", 0707);

// ������� �����͸� �����ϰ�, $ym�� ���Ѵ� - �Ҵ���
$data_dir = dirname($g4[data_path] . "/nothing");
$ym = date("ym", $g4[server_time]);
@mkdir("$g4[data_path]/file/$bo_table/$ym", 0707);
@chmod("$g4[data_path]/file/$bo_table/$ym", 0707);

// "���ͳݿɼ� > ���� > ��������Ǽ��� > ��ũ���� > Action ��ũ���� > ��� �� ��" �� ����� ���� ó��
// �� �ɼ��� ��� �� ������ ������ ��� � ��ũ��Ʈ�� ���� ���� �ʽ��ϴ�.
//if (!$_POST[wr_content]) die ("������ �Է��Ͽ� �ֽʽÿ�.");

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
//print_r2($chars_array); exit;

// ���� ���� ���ε�
$file_upload_msg = "";
$upload = array();
for ($i=0; $i<count($_FILES[bf_file][name]); $i++) 
{
    // ������ üũ�� �Ǿ��ִٸ� ������ �����մϴ�.
    if ($_POST[bf_file_del][$i]) 
    {
        $upload[$i][del_check] = true;

        $row = sql_fetch(" select bf_file from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
        @unlink("$g4[data_path]/file/$bo_table/$row[bf_file]");
    }
    else
        $upload[$i][del_check] = false;

    $tmp_file  = $_FILES[bf_file][tmp_name][$i];
    $filesize  = $_FILES[bf_file][size][$i];
    $filename  = $_FILES[bf_file][name][$i];
    $filename  = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $filename);

    // ������ ������ ������ ū������ ���ε� �Ѵٸ�
    if ($filename)
    {
        if ($_FILES[bf_file][error][$i] == 1)
        {
            $file_upload_msg .= "\'{$filename}\' ������ �뷮�� ������ ����($upload_max_filesize)�� ������ ũ�Ƿ� ���ε� �� �� �����ϴ�.\\n";
            continue;
        }
        else if ($_FILES[bf_file][error][$i] != 0)
        {
            $file_upload_msg .= "\'{$filename}\' ������ ���������� ���ε� ���� �ʾҽ��ϴ�.\\n";
            continue;
        }
    }

    if (is_uploaded_file($tmp_file)) 
    {
        // �����ڰ� �ƴϸ鼭 ������ ���ε� ������� ũ�ٸ� �ǳʶ�
        if (!$is_admin && $filesize > $board[bo_upload_size]) 
        {
            $file_upload_msg .= "\'{$filename}\' ������ �뷮(".number_format($filesize)." ����Ʈ)�� �Խ��ǿ� ����(".number_format($board[bo_upload_size])." ����Ʈ)�� ������ ũ�Ƿ� ���ε� ���� �ʽ��ϴ�.\\n";
            continue;
        }

        //=================================================================\
        // 090714
        // �̹����� �÷��� ���Ͽ� �Ǽ��ڵ带 �ɾ� ���ε� �ϴ� ��츦 ����
        // �����޼����� ������� �ʴ´�.
        //-----------------------------------------------------------------
        $timg = @getimagesize($tmp_file);
        // image type
        if ( preg_match("/\.($config[cf_image_extension])$/i", $filename) ||
             preg_match("/\.($config[cf_flash_extension])$/i", $filename) ) 
        {
            if ($timg[2] < 1 || $timg[2] > 16)
            {
                //$file_upload_msg .= "\'{$filename}\' ������ �̹����� �÷��� ������ �ƴմϴ�.\\n";
                continue;
            }
        }
        //=================================================================

        $upload[$i][image] = $timg;

        // 4.00.11 - �۴亯���� ���� ���ε�� ������ ������ �����Ǵ� ������ ����
        if ($w == 'u')
        {
            // �����ϴ� ������ �ִٸ� �����մϴ�.
            $row = sql_fetch(" select bf_file from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
            @unlink("$g4[data_path]/file/$bo_table/$row[bf_file]");
        }

        // ���α׷� ���� ���ϸ�
        $upload[$i][source] = $filename;
        $upload[$i][filesize] = $filesize;

        // �Ʒ��� ���ڿ��� �� ������ -x �� �ٿ��� ����θ� �˴��� ������ ���� ���ϵ��� ��
        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

        // ���̻縦 ���� ���ϸ�
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr(md5(uniqid($g4[server_time])),0,8).'_'.urlencode($filename);
        // �޺��µ��� ���� : �ѱ������� urlencode($filename) ó���� �Ұ�� '%'�� �ٿ��ְ� �Ǵµ� '%'ǥ�ô� �̵���÷��̾ �ν��� ���ϱ� ������ ����� �ȵ˴ϴ�. �׷��� ������ ���ϸ��� '%'�κ��� ���ָ� �ذ�˴ϴ�. 
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr(md5(uniqid($g4[server_time])),0,8).'_'.str_replace('%', '', urlencode($filename)); 
        shuffle($chars_array);
        $shuffle = implode("", $chars_array);
        // �Ҵ��� - ip�ּҸ� �״�� �����ϴ� ���̶� timestamp�� ����
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode($filename)); 
        // ÷������ ÷�ν� ÷�����ϸ� ������ ���ԵǾ� ������ �Ϻ� PC���� ������ �ʰų� �ٿ�ε� ���� �ʴ� ������ �ֽ��ϴ�. (����� �� 090925)
        //$upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode($filename));
        
        // �����̸��� 255���ڸ� ������ ������ ���� �� �־, ������ �̸����� �ٲ�����ϴ�. ��¿ �� �����ϴ�.
        if (strlen(str_replace('%', '', urlencode(str_replace(' ', '_', $filename)))) > 200)
            $upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.md5(uniqid($g4[server_time]));
        else
            $upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));

        // ����($ym)���� ÷�������� �и��Ͽ� ���ε� - �Ҵ���
        $upload[$i][file] = $ym . "/" . $upload[$i][file];

        $dest_file = "$g4[data_path]/file/$bo_table/" . $upload[$i][file];

        // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES[bf_file][error][$i]);

        // �ö� ������ �۹̼��� �����մϴ�.
        chmod($dest_file, 0606);

        //$upload[$i][image] = @getimagesize($dest_file);

    }
}

//------------------------------------------------------------------------------
// ���� ���� ���ε�
// ���߿� ���̺� �����ϴ� ������ $wr_id ���� �����ؾ� �ϱ� �����Դϴ�.
for ($i=0; $i<count($upload); $i++) 
{
    if (!get_magic_quotes_gpc()) {
        $upload[$i]['source'] = addslashes($upload[$i]['source']);
    }

    $row = sql_fetch(" select count(*) as cnt from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
    if ($row[cnt]) 
    {
        // ������ üũ�� �ְų� ������ �ִٸ� ������Ʈ�� �մϴ�.
        // �׷��� �ʴٸ� ���븸 ������Ʈ �մϴ�.
        if ($upload[$i][del_check] || $upload[$i][file]) 
        {
            $sql = " update $g4[board_file_table]
                        set bf_source = '{$upload[$i][source]}',
                            bf_file = '{$upload[$i][file]}',
                            bf_content = '{$bf_content[$i]}',
                            bf_filesize = '{$upload[$i][filesize]}',
                            bf_width = '{$upload[$i][image][0]}',
                            bf_height = '{$upload[$i][image][1]}',
                            bf_type = '{$upload[$i][image][2]}',
                            bf_datetime = '$g4[time_ymdhis]'
                      where bo_table = '$bo_table'
                        and wr_id = '$wr_id'
                        and bf_no = '$i' ";
            sql_query($sql);
        } 
        else 
        {
            $sql = " update $g4[board_file_table]
                        set bf_content = '{$bf_content[$i]}' 
                      where bo_table = '$bo_table'
                        and wr_id = '$wr_id'
                        and bf_no = '$i' ";
            sql_query($sql);
        }
    } 
    else 
    {
        $sql = " insert into $g4[board_file_table]
                    set bo_table = '$bo_table',
                        wr_id = '$wr_id',
                        bf_no = '$i',
                        bf_source = '{$upload[$i][source]}',
                        bf_file = '{$upload[$i][file]}',
                        bf_content = '{$bf_content[$i]}',
                        bf_download = 0,
                        bf_filesize = '{$upload[$i][filesize]}',
                        bf_width = '{$upload[$i][image][0]}',
                        bf_height = '{$upload[$i][image][1]}',
                        bf_type = '{$upload[$i][image][2]}',
                        bf_datetime = '$g4[time_ymdhis]' ";
        sql_query($sql);
    }
}

// ���ε�� ���� ���뿡�� ���� ū ��ȣ�� ��� �Ųٷ� Ȯ���� ���鼭
// ���� ������ ���ٸ� ���̺��� ������ �����մϴ�.
$row = sql_fetch(" select max(bf_no) as max_bf_no from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' ");
for ($i=(int)$row[max_bf_no]; $i>=0; $i--) 
{
    $row2 = sql_fetch(" select bf_file from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");

    // ������ �ִٸ� �����ϴ�.
    if ($row2[bf_file]) break;

    // �׷��� �ʴٸ� ������ �����մϴ�.
    sql_query(" delete from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
}
//------------------------------------------------------------------------------

// ��б��̶�� ���ǿ� ��б��� ���̵� �����Ѵ�. �ڽ��� ���� �ٽ� �н����带 ���� �ʱ� ����
if ($secret) 
    set_session("ss_secret_{$bo_table}_{$wr_num}", TRUE);

// ���Ϲ߼� ��� (�������� �߼����� ����)
if (!($w == "u" || $w == "cu") && $config[cf_email_use] && $board[bo_use_email]) 
{
    // �������� ������ ���
    $super_admin = get_admin("super");
    $group_admin = get_admin("group");
    $board_admin = get_admin("board");

    $wr_subject = get_text(stripslashes($wr_subject));

    $tmp_html = 0;
    if (strstr($html, "html1"))
        $tmp_html = 1;
    else if (strstr($html, "html2"))
        $tmp_html = 2;

    $wr_content = conv_content(stripslashes($wr_content), $tmp_html);

    $warr = array( ""=>"�Է�", "u"=>"����", "r"=>"�亯", "c"=>"�ڸ�Ʈ", "cu"=>"�ڸ�Ʈ ����" );
    $str = $warr[$w];

    $subject = "'{$board[bo_subject]}' �Խ��ǿ� {$str}���� �ö�Խ��ϴ�.";
    $link_url = "$g4[url]/$g4[bbs]/board.php?bo_table=$bo_table&wr_id=$wr_id&$qstr";

    include_once("$g4[path]/lib/mailer.lib.php");

    ob_start();
    include_once ("./write_update_mail.php");
    $content = ob_get_contents();
    ob_end_clean();

    $array_email = array();
    // �Խ��ǰ����ڿ��� ������ ����
    if ($config[cf_email_wr_board_admin]) $array_email[] = $board_admin[mb_email];
    // �Խ��Ǳ׷�����ڿ��� ������ ����
    if ($config[cf_email_wr_group_admin]) $array_email[] = $group_admin[mb_email];
    // �ְ�����ڿ��� ������ ����
    if ($config[cf_email_wr_super_admin]) $array_email[] = $super_admin[mb_email];

    // �ɼǿ� ���ϹޱⰡ üũ�Ǿ� �ְ�, �Խ����� ������ �ִٸ�
    if (strstr($wr[wr_option], "mail") && $wr[wr_email]) {
        // ���� ���Ϲ߼ۿ� üũ�� �Ǿ� �ִٸ�
        if ($config[cf_email_wr_write]) $array_email[] = $wr[wr_email];

        // �ڸ�Ʈ �� ����̿��� ���� �߼��� �Ǿ� �ִٸ� (�ڽſ��Դ� �߼����� �ʴ´�)
        if ($config[cf_email_wr_comment_all]) {
            $sql = " select distinct wr_email from $write_table
                      where wr_email not in ( '$wr[wr_email]', '$member[mb_email]', '' )
                        and wr_parent = '$wr_id' ";
            $result = sql_query($sql);
            while ($row=sql_fetch_array($result))
                $array_email[] = $row[wr_email];
        }
    }

    // �ߺ��� ���� �ּҴ� ����
    $unique_email = array_unique($array_email);
    $unique_email = array_values($unique_email);
    for ($i=0; $i<count($unique_email); $i++) {
        mailer($wr_name, $wr_email, $unique_email[$i], $subject, $content, 1);
    }
}

// �Ҵ��� - min_wr_num ������Ʈ
$result = sql_fetch(" select MIN(wr_num) as min_wr_num from $write_table ");
$sql = " update $g4[board_table] set min_wr_num = '$result[min_wr_num]' where bo_table = '$bo_table' ";
sql_query($sql);
    
// �Ҵ��� - ÷�������� ���� �ľ�
$sql = " select count(*) as cnt from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_source <> '' ";
$result = sql_fetch($sql);

// �Ҵ��� - ÷������ ���� ������Ʈ
$sql = " update $write_table set wr_file_count = '$result[cnt]' where wr_id = '$wr_id' ";
sql_query($sql);

// �Ҵ��� - CCL ���� ������Ʈ
if ($board[bo_ccl]) {
    $wr_ccl = "";
    if ($wr_ccl_by == "by") { $wr_ccl .= "by"; }
    if ($wr_ccl_nc == "nc") { $wr_ccl .= $wr_ccl ? "-": ""; $wr_ccl .= "nc"; }
    if ($wr_ccl_nd == "nd") { $wr_ccl .= $wr_ccl ? "-": ""; $wr_ccl .= "nd"; }
    if ($wr_ccl_nd == "sa") { $wr_ccl .= $wr_ccl ? "-": ""; $wr_ccl .= "sa"; }
    
    sql_query("update $write_table set wr_ccl = '$wr_ccl' where wr_id = '$wr_id'");
}

// �Ҵ��� - ��ü����
if ($insert_g_notice) {
    $sql = " INSERT INTO $g4[notice_table] SET bo_table = '$bo_table', wr_id='$wr_id', no_datetime='$g4[time_ymdhis]' ";
    sql_query($sql);
}

// ����� �ڵ� ����
@include_once ("$board_skin_path/write_update.skin.php");

// ------------------------------------------------------------
// �Ҵ��� - DHTML �̿�ÿ� cheditor ���� ����ϱ�
if($board['bo_use_dhtml_editor'])
{
    // �������� �۷ι� ������ ����
    $g4['w'] = "u";
    $g4['bo_table'] = $bo_table;
    $g4['wr_id'] = $wr_id;
    $g4['ip_addr'] = $remote_addr;

    // $w == "u"�̸�, ���� ���� db�� del �ʵ带 ��� 1�� ���� �մϴ�.
    if ($w == "u") {
        $sql = " update $g4[board_cheditor_table] set del = '1' where bo_table = '$bo_table' and wr_id = '$wr_id' ";
        sql_query($sql, FALSE);
    }

    // ���� html code�� �ٲ㼭 callback�� �ҷ� �ݴϴ�.
    preg_replace_callback('/\<img[^\<\>]*\>/i', 'get_chimage', stripslashes($wr_content));
    
    // $w == "u"�̸�, ������� db�� del �ʵ��� 1�� ���� ��� ���� �մϴ�. Ȯ���� ������ û��.
    if ($w == "u") {
        $sql = " select * from $g4[board_cheditor_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and del = '1' ";
        $result3 = sql_query($sql);
        while ($row=sql_fetch_array($result3)) {
              $file_path = $row[bc_dir] . "/" . $row[bc_file];
              $file_dir = $row[bc_dir];
              @unlink($file_path);
              $sql_d = " delete from $g4[board_cheditor_table] where bc_id = '$row[bc_id]' ";
              sql_query($sql_d);
        }
    }
}

// �Ҵ��� - �ӽ������ ���� ��� �����ְ� ���ǵ� �����ϴ� (ss_tempsave ���Ǹ� ����� ��� ���� �� �ֽ�...)
$sql = " delete from $g4[tempsave_table] where bo_table='$bo_table' and mb_id = '$member[mb_id]' ";
sql_query($sql);
set_session("ss_tempsave", "");

// �Ҵ��� - �̹��� �뷮�� ����ؼ� wr_imagesize �ʵ带 ������Ʈ ���ݴϴ�.
if($board['bo_use_dhtml_editor'] && $board['bo_chimage']) {
    $sql = " select sum(bc_filesize) as imagesum from $g4[board_cheditor_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and del = 0 ";
    $chsum = sql_fetch($sql);
    $sql = " select sum(bf_filesize) as imagesum from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_type > 0 ";
    $filesum = sql_fetch($sql);
    $wr_imagesize = (int) ($chsum[imagesum] + ($filesum[imagesum])/1000);
    $sql = " update $write_table set wr_imagesize = '$wr_imagesize' where wr_id = '$wr_id' ";
    sql_query($sql);
}

if (file_exists("$board_skin_path/write_update.tail.skin.php"))
    @include_once("$board_skin_path/write_update.tail.skin.php");

// ���� ���� �������� �Խ��ǿ� �� �Ͻô� �е��� ���ؼ�. üũ�� �տ���.
if ($w == "" || $w == "r")
{
    if ($is_admin == "") {
        set_session("ss_wr_subject", md5($_SERVER[REMOTE_ADDR].$wr_subject));
    }
}

if ($g4[https_url])
    $https_url = "$g4[url]";
else
    $https_url = ".";

if ($file_upload_msg)
    alert($file_upload_msg, "{$https_url}/board.php?bo_table=$bo_table&wr_id=$wr_id&page=$page" . $qstr);
else
    goto_url("{$https_url}/$bo_table/$wr_id?page=$page" . $qstr);
?>
