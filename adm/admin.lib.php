<?
if (!defined("_GNUBOARD_")) exit;

/*
// 081022 : CSRF ������ ���� �ڵ带 �ۼ������� ȿ���� ���� �ּ�ó�� ��
if (!get_session("ss_admin")) {
    set_session("ss_admin", true);
    goto_url(".");
}
*/

// ��Ų��θ� ��´�
function get_skin_dir($skin, $len='')
{
    global $g4;

    $result_array = array();

    $dirname = "$g4[path]/skin/$skin/";
    $handle = opendir($dirname);
    while ($file = readdir($handle)) 
    {
        if($file == "."||$file == "..") continue;

        if (is_dir($dirname.$file)) $result_array[] = $file;
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}

// ȸ�� ����
function member_delete($mb_id)
{
    global $config;
    global $g4;

    $sql = " select mb_name, mb_nick, mb_ip, mb_recommend, mb_memo, mb_level from $g4[unlogin_table] where mb_id= '$mb_id' ";
    $mb = sql_fetch($sql);
    if ($mb[mb_recommend]) {
        $row = sql_fetch(" select count(*) as cnt from $g4[member_table] where mb_id = '".addslashes($mb[mb_recommend])."' ");
        if ($row[cnt])
            insert_point($mb[mb_recommend], $config[cf_recommend_point] * (-1), "{$mb_id}���� ȸ���ڷ� ������ ���� ��õ�� ����Ʈ ��ȯ", '@member', $mb[mb_recommend], "{$mb_id} ��õ�� ����");
    }

    // ȸ���ڷ�� ������ ���� �� ���̵�� �����Ͽ� �ٸ� ����� ������� ���ϵ��� �� : 061025
    // �޸�ȭ �Ǹ鼭 ��κ��� ������ clear ������, �ѹ� �� Ȯ���ϰ�...
    if ($mb[mb_level] >= 1) {
        $sql = " update $g4[member_table] 
                    set
                        mb_name = '',
                        mb_nick = '',
                        mb_password = '',
                        mb_level = '1',
                        mb_email = '',
                        mb_homepage = '',
                        mb_tel = '',
                        mb_hp = '',
                        mb_zip1 = '',
                        mb_zip2 = '',
                        mb_addr1 = '',
                        mb_addr2 = '',
                        mb_birth = '',
                        mb_sex = '',
                        mb_signature = '',
                        mb_memo = '".date("Ymd",$g4['server_time'])." ������\n\n$mb[mb_memo]',
                        mb_leave_date = '".date("Ymd",$g4['server_time'])."',
                        mb_profile='',
                        mb_memo_call='',
                        mb_memo_no_reply_text='',
                        mb_1='',
                        mb_2='',
                        mb_3='',
                        mb_4='',
                        mb_5='',
                        mb_6='',
                        mb_7='',
                        mb_8='',
                        mb_9='',
                        mb_10=''
                  where mb_id = '$mb_id' ";
        sql_query($sql);

        // �޸� ���̺����� mb_name, mb_nick�� clear ���� �ʽ��ϴ�.
        $sql = " update $g4[unlogin_table] 
                    set 
                        mb_password = '',
                        mb_level = '1',
                        mb_email = '',
                        mb_homepage = '',
                        mb_tel = '',
                        mb_hp = '',
                        mb_zip1 = '',
                        mb_zip2 = '',
                        mb_addr1 = '',
                        mb_addr2 = '',
                        mb_birth = '',
                        mb_sex = '',
                        mb_signature = '',
                        mb_memo = '".date("Ymd",$g4['server_time'])." ������\n\n$mb[mb_memo]',
                        mb_leave_date = '".date("Ymd",$g4['server_time'])."',
                        mb_profile='',
                        mb_memo_call='',
                        mb_memo_no_reply_text='',
                        mb_1='',
                        mb_2='',
                        mb_3='',
                        mb_4='',
                        mb_5='',
                        mb_6='',
                        mb_7='',
                        mb_8='',
                        mb_9='',
                        mb_10=''
                  where mb_id = '$mb_id' ";
        sql_query($sql);
    }
    
    /*
    // ȸ�� �ڷ� ����
    sql_query(" delete from $g4[member_table] where mb_id = '$mb_id' ");

    // ������ �ڷḦ �� �����ϸ� ���� ������
    if ($mb[mb_nick] != '[������]')
    {
        // �ٸ� ����� �� ȸ�����̵� ������� ���ϵ��� ���̵� ������ �����ϴ�.
        // �Խ��ǿ��� ȸ�����̵�� �������� �ʱ� �����Դϴ�.
        sql_query(" insert into $g4[member_table] set mb_id = '$mb_id', mb_name='$mb[mb_name]', mb_nick='[������]', mb_ip='$mb[mb_ip]', mb_datetime = '$g4[time_ymdhis]' ");
    }
    
    // ����Ʈ ���̺��� ����
    sql_query(" delete from $g4[point_table] where mb_id = '$mb_id' ");
    
    // �׷����ٰ��� ����
    sql_query(" delete from $g4[group_member_table] where mb_id = '$mb_id' ");
    
    // ���� ����
    sql_query(" delete from $g4[memo_table] where me_recv_mb_id = '$mb_id' or me_send_mb_id = '$mb_id' ");
    
    // ��ũ�� ����
    sql_query(" delete from $g4[scrap_table] where mb_id = '$mb_id' ");
    
    // �������� ����
    sql_query(" delete from $g4[auth_table] where mb_id = '$mb_id' ");

    // �׷�������� ��� �׷�����ڸ� �������� 
    sql_query(" update $g4[group_table] set gr_admin = '' where gr_admin = '$mb_id' ");

    // �Խ��ǰ������� ��� �Խ��ǰ����ڸ� ��������
    sql_query(" update $g4[board_table] set bo_admin = '' where bo_admin = '$mb_id' ");

    // ȸ�� �г��� ����
    sql_query(" delete from $g4[mb_nick_table] where mb_id = '$mb_id' ");

    // ���� �Խ��� ����
    sql_query(" delete from $g4[my_board_table] where mb_id = '$mb_id' ");

    // ���� �޴� ����
    sql_query(" delete from $g4[my_menu_table] where mb_id = '$mb_id' ");

    // ������ ����
    sql_query(" delete from $g4[recycle_table] where rc_mb_id = '$mb_id' ");

    // ȸ�����������丮 ����
    sql_query(" delete from $g4[member_level_history_table] where mb_id = '$mb_id' ");

    // ȸ�����԰������ ����
    sql_query(" delete from $g4[member_register_table] where mb_id = '$mb_id' ");

    // ���ϴٿ�ε� ���� ����
    sql_query(" delete from $g4[board_file_download_table] where mb_id = '$mb_id' ");

    // �α��ο��� ���� ����
    sql_query(" delete from $g4[login_fail_log_table] where mb_id = '$mb_id' ");

    // hidden comment ���� ����
    sql_query(" delete from $g4[hidden_comment_table] where mb_id = '$mb_id' ");

    // �Ű��� ����
    sql_query(" delete from $g4[singo_table] where mb_id = '$mb_id' ");

    // ������ ����
    @unlink("$g4[data_path]/member/".substr($mb_id,0,2)."/$mb_id.gif");
    */
}


// ȸ�������� SELECT �������� ����
function get_member_level_select($name, $start_id=0, $end_id=10, $selected='', $event='')
{
    global $g4;

    $str = "<select name='$name' $event>";
    for ($i=$start_id; $i<=$end_id; $i++)
    {
        $str .= "<option value='$i'";
        if ($i == $selected) 
            $str .= " selected";
        $str .= ">$i</option>";
    }
    $str .= "</select>";
    return $str;
}


// ȸ�����̵� SELECT �������� ����
function get_member_id_select($name, $level, $selected='', $event='')
{
    global $g4;

    $level = (int) $level;

    $sql = " select mb_id from $g4[member_table] where mb_level >= '$level' ";
    $result = sql_query($sql);
    $str = "<select name='$name' $event><option value=''>���þ���";
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        $str .= "<option value='$row[mb_id]'";
        if ($row[mb_id] == $selected) $str .= " selected";
        $str .= ">$row[mb_id]</option>";
    }
    $str .= "</select>";
    return $str;
}

// ���� �˻�
function auth_check($auth, $attr)
{
    global $is_admin;

    if ($is_admin == "super") return;

    if (!trim($auth))
        alert("�� �޴����� ���� ������ �����ϴ�.\\n\\n���� ������ �ְ�����ڸ� �ο��� �� �ֽ��ϴ�.");

    $attr = strtolower($attr);

    if (!strstr($auth, $attr)) {
        if ($attr == "r")
            alert("���� ������ �����ϴ�.");
        else if ($attr == "w")
            alert("�Է�, �߰�, ����, ���� ������ �����ϴ�.");
        else if ($attr == "d")
            alert("���� ������ �����ϴ�.");
        else 
            alert("�Ӽ��� �߸� �Ǿ����ϴ�.");
    }
}


// rm -rf �ɼ� : exec(), system() �Լ��� ����� �� ���� ���� �Ǵ� win32�� ��ü
// www.php.net ���� : pal at degerstrom dot com
function rm_rf($file) 
{
    if (file_exists($file)) {
        @chmod($file,0777);
        if (is_dir($file)) {
            $handle = opendir($file); 
            while($filename = readdir($handle)) {
                if ($filename != "." && $filename != "..") 
                    rm_rf("$file/$filename");
            }
            closedir($handle);
            rmdir($file);
        } else 
            unlink($file);
    }
}

// ��¼���
function order_select($fld, $sel="") 
{
    $s = "<select name='$fld'>";
    for ($i=1; $i<=100; $i++) {
        $s .= "<option value='$i' ";
        if ($sel) {
            if ($i == $sel) {
                $s .= "selected";
            }
        } else {
            if ($i == 50) {
                $s .= "selected";
            }
        }
        $s .= ">$i</option>";
    }
    $s .= "</select>\n";

    return $s;
}

// ���� ���� �˻�
if (!$member['mb_id'])
{
    //alert("�α��� �Ͻʽÿ�.", "$g4[bbs_path]/login.php?url=" . urlencode("$_SERVER[PHP_SELF]?w=$w&mb_id=$mb_id"));
    alert("�α��� �Ͻʽÿ�.", "$g4[bbs_path]/login.php?url=" . urlencode("$_SERVER[PHP_SELF]?$_SERVER[QUERY_STRING]"));
}
else if ($is_admin != "super") 
{
    $auth = array();
    $sql = " select au_menu, au_auth from $g4[auth_table] where mb_id = '$member[mb_id]' ";
    $result_a = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result_a); $i++) 
    {
        $auth[$row[au_menu]] = $row[au_auth];
    }

    if (!$i)
    {
        alert("�ְ������ �Ǵ� ���������� �ִ� ȸ���� ���� �����մϴ�.", $g4[path]);
    }
}

// �������� ������, �������� �ٸ��ٸ� ������ ���� �����ڿ��� ������ ������.
// ss_mb_key ������ bbs/login_check.php���� �����ȴ�.
$admin_key = md5($member[mb_datetime] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
if (get_session("ss_mb_key") !== $admin_key) {

    session_destroy();

    include_once("$g4[path]/lib/mailer.lib.php");
    // ���� �˸�
    mailer($member['mb_nick'], $member['mb_email'], $member['mb_email'], "XSS ���� �˸�", "{$_SERVER['REMOTE_ADDR']} �����Ƿ� XSS ������ �־����ϴ�.\n\n������ ������ Ż���Ϸ��� �����̹Ƿ� �����Ͻñ� �ٶ��ϴ�.\n\n�ش� �����Ǵ� �����Ͻð� �ǽɵǴ� �Խù��� �ִ��� Ȯ���Ͻñ� �ٶ��ϴ�.\n\n$g4[url]", 0);

    alert("���������� �α����Ͽ� �����Ͻñ� �ٶ��ϴ�.");
}

@ksort($auth);

// ���� �޴�
unset($auth_menu);
unset($menu);
unset($amenu);
$tmp = dir($g4['admin_path']);
while ($entry = $tmp->read()) 
{
    if (!preg_match("/^admin.menu([0-9]{3}).*\.php/", $entry, $m)) 
        continue;  // ���ϸ��� menu ���� �������� ������ �����Ѵ�. 

    $amenu[$m[1]] = $entry;
    include_once($g4['admin_path']."/".$entry);
}
@ksort($amenu);

$qstr = "";
if (isset($sst)) $qstr .= "&sst=$sst";
if (isset($sod)) $qstr .= "&sod=$sod";
if (isset($sfl)) $qstr .= "&sfl=$sfl";
if (isset($stx)) $qstr .= "&stx=$stx";
if (isset($page)) $qstr .= "&page=$page";
?>