<?
include_once("./_common.php");

$mb_id       = strip_tags($_POST[mb_id]);
$mb_password = strip_tags($_POST[mb_password]);

if (!trim($mb_id) || !trim($mb_password))
    alert("ȸ�����̵� �н����尡 �����̸� �ȵ˴ϴ�.");

/*
// �ڵ� ��ũ��Ʈ�� �̿��� ���ݿ� ����Ͽ� �α��� ���нÿ��� �����ð��� �����Ŀ� �ٽ� �α��� �ϵ��� ��
if ($check_time = get_session("ss_login_check_time")) {
    if ($check_time > $g4['server_time'] - 15) {
        alert("�α��� ���нÿ��� 15�� ���Ŀ� �ٽ� �α��� �Ͻñ� �ٶ��ϴ�.");
    }
}
set_session("ss_login_check_time", $g4['server_time']);
*/

$mb = get_member($mb_id);

// �޸�����̸�, �޸���� ���̺��� �о �α����� ���� �մϴ�.
if ($mb['mb_unlogin'] !== "0000-00-00 00:00:00") {

    // lib/common.lib.php�� get_member �Լ� ����
    $sql = "select * from $g4[unlogin_table] where mb_id = '$mb_id'";
    $mb = sql_fetch($sql, FALSE);
}

// ���Ե� ȸ���� �ƴϴ�. �н����尡 Ʋ����. ��� �޼����� ���� �������� �ʴ� ������ 
// ȸ�����̵� �Է��� ���� ������ �� �н����带 �Է��غ��� ��츦 �����ϱ� ���ؼ��Դϴ�.
// �ҹ�������� ��� ȸ�����̵� Ʋ����, �н����尡 Ʋ������ �˱������ ���� �ð��� �ҿ�Ǳ� �����Դϴ�.
//if (!$mb[mb_id] || (sql_password($mb_password) !== $mb[mb_password]))
//if (!$mb[mb_id] || ($check_password !== $mb[mb_password] and sql_old_password($mb_password) !== $mb[mb_password])) {

$login_check=0;
if (!$mb[mb_id]) {
    $login_check = 1;
} else if (sql_password($mb_password) !== $mb[mb_password]) {

    // ���� ������ �н����������� �𸣴ϱ� �ѹ� �� Ȯ���մϴ�.
    if (sql_old_password($mb_password) !== $mb[mb_password]) {
        $login_check = 1;
    } else {
        // ���� �н����带 ���ο� �н������ �ٲߴϴ�.
        //$sql = " update $g4[member_table] set mb_password='" . sql_password($mb_password) . "' where mb_id='$mb_id' ";
        //sql_query($sql);

        $sql = " update $g4[member_table] set mb_password= :mb_password where mb_id=:mb_id ";
        $stmt = $pdo_db->prepare($sql);
        $stmt->bindParam(":mb_password", sql_password($mb_password));
        $stmt->bindParam(":mb_id", $mb_id);
        $result = pdo_query($stmt);
    }

}

if ($login_check) {
    // �α��� ������ db�� ��� �մϴ�.
    //$sql = " insert into $g4[login_fail_log_table] (mb_id, ip_addr, log_datetime, log_url) values ('$mb_id', '$remote_addr', '$g4[time_ymdhis]', '/bbs/login_check.php') ";
    //sql_query($sql);

    $sql = " insert into $g4[login_fail_log_table] (mb_id, ip_addr, log_datetime, log_url) values (:mb_id, '$remote_addr', '$g4[time_ymdhis]', '/bbs/login_check.php') ";
    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":mb_id", $mb_id);
    $result = pdo_query($stmt);

    // ���� Ƚ���� üũ�ؼ� ���������� ���� �մϴ�.
    if ($config['cf_retry_time_interval'] > 0 && $config['cf_retry_count']) {
        $sql = " select count(*) as cnt from $g4[login_fail_log_table] where log_datetime >= '" . date("Y-m-d H:i:s", $g4[server_time] - $config['cf_retry_time_interval'] ) . "' and ip_addr='$remote_addr' ";
        $result = sql_fetch($sql);
        
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($result['cnt'] >= $config['cf_retry_count']) {
            $pattern = explode("\n", trim($config['cf_intercept_ip']));
            if (empty($pattern[0])) // ip ���ܸ���� ��� ���� ��
                $cf_intercept_ip = $ip;
            else
                $cf_intercept_ip = trim($config['cf_intercept_ip'])."\n{$ip}";
            $sql = " update {$g4['config_table']} set cf_intercept_ip = '$cf_intercept_ip' ";
            sql_query($sql);

            $msg = "[ERROR:L001] ���Ե� ȸ���� �ƴϰų� �н����尡 Ʋ���ϴ�.";
        } else {
            $msg = "[ERROR:L002] ���Ե� ȸ���� �ƴϰų� �н����尡 Ʋ���ϴ�.";
        }
        alert($msg);
    }
    
    alert("���Ե� ȸ���� �ƴϰų� �н����尡 Ʋ���ϴ�.\\n\\n�н������ ��ҹ��ڸ� �����մϴ�.");
}

// ���ܵ� ���̵��ΰ�?
if ($mb[mb_intercept_date] && $mb[mb_intercept_date] <= date("Ymd", $g4[server_time])) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1�� \\2�� \\3��", $mb[mb_intercept_date]); 
    alert("ȸ������ ���̵�� ������ �����Ǿ� �ֽ��ϴ�.\\n\\nó���� : $date");
}

// Ż���� ���̵��ΰ�?
if ($mb[mb_leave_date] && $mb[mb_leave_date] <= date("Ymd", $g4[server_time])) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1�� \\2�� \\3��", $mb[mb_leave_date]); 
    alert("Ż���� ���̵��̹Ƿ� �����Ͻ� �� �����ϴ�.\\n\\nŻ���� : $date");
}

if ($config[cf_use_email_certify] && !preg_match("/[1-9]/", $mb[mb_email_certify])) {
    set_session('email_mb_id', $mb[mb_id]);
    alert("���������� �����ž� �α��� �Ͻ� �� �ֽ��ϴ�.\\n\\nȸ������ �����ּҴ� $mb[mb_email] �Դϴ�.", "$g4[bbs_path]/email_re_certify.php");
}

// �Ҵ��� - �ߺ��α��� ����
if ($config['cf_double_login'] && $mb_id) {

    // db session�� ����ϴ� ���
    if ($g4['session_type'] == "db") {
        $sql = "select * from $g4[session_table] where mb_id = '$mb[mb_id]' and ss_ip != '$remote_addr' and ss_datetime > '$login_time' ";
        $sql.= "order by ss_datetime desc limit 1";

            $login_time = date("Y-m-d H:i:s", $g4[server_time] - 60*10); // 10��
            $sql = " SELECT * from $g4[session_table] 
                      WHERE mb_id = '$mb[mb_id]' and ip_addr != '$remote_addr' and ss_datetime > '$login_time' ";
            // ip�� ������� �ʴ� ��� (��â�� ���ų� �ҿ���� ie�� �Բ� ����ϸ� �ߺ� �α��� ������ ���ɴϴ�)
            //$sql = " SELECT * from $g4[session_table] where mb_id = '$mb[mb_id]' and ss_datetime > '$login_time'  ";
            $result = sql_query($sql);
        
            if (mysql_num_rows($result) > $config['cf_double_login']) {
                alert("�ٸ� ip���� �̹� �α��εǾ� �ֽ��ϴ�. �����ڿ��� �����Ͻñ� �ٶ��ϴ�.");
            }
    }
    // ���ϼ����� ����ϴ� ���
    else if ($g4['session_type'] = "file") {
        $result = sql_fetch(" select count(*) as cnt from $g4[login_table] where mb_id='$mb[mb_id]' and lo_ip <> '$_SERVER[REMOTE_ADDR]' ");
        
        if ($result['cnt'] > $config['cf_double_login']) {
            alert("�ٸ� ip���� �̹� �α��εǾ� �ֽ��ϴ�. �����ڿ��� �����Ͻñ� �ٶ��ϴ�.");
        }
    }
    // redis ������ �̿��ϴ� ���
    else if ($g4['session_type'] == "redis") {

        $redis_con = new Redis();
        $redis_con->connect($g4["rhost"], $g4["rport"]);
        $redis_con->select($g4["rdb1"]);

        // ip�� ���� key���� ��� ��� ���ϴ�.
        $con_key = $g4["rdomain"] . "_login_" . "$remote_addr";
        $allKeys = $redis_con->keys($con_key);

        $con_cnt = 0;
        foreach ($allKeys as $rkey) {
            $rdat = explode ( "|", $redis_con->get($rkey) );
            if ($redis_con->ttl($rkey) > 0) {
                if ($mb['mb_id'] == $rdat['1']) {
                    $con_cnt++;
                }
            }
        }
        $redis_con->close();

        if ($con_cnt > $config['cf_double_login']) {
            alert("�ٸ� ip���� �̹� �α��εǾ� �ֽ��ϴ�. �����ڿ��� �����Ͻñ� �ٶ��ϴ�.");
        }
    }
}

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";
if (file_exists("$member_skin_path/login_check.skin.php"))
    @include_once("$member_skin_path/login_check.skin.php");

// --- �Ҵ��� ȸ�� ������/�����ٿ�

// ȸ���� ������/�����ٿ�
if ($g4['use_auto_levelup'] && !is_admin_check($mb_id))
{
    $res = member_level_up($mb_id);
    if ($res) {

        //$tsql = " insert into $g4[whatson_table] ( mb_id, wr_subject, wo_type, wo_count, wo_datetime, bo_table, wr_id ) 
        //          values ('$mb_id', '$res','mb_level','1','$g4[time_ymdhis]','','') ";
        //sql_query($tsql);

        $tsql = " insert into $g4[whatson_table] ( mb_id, wr_subject, wo_type, wo_count, wo_datetime, bo_table, wr_id ) 
                  values (:mb_id, '$res','mb_level','1','$g4[time_ymdhis]','','') ";

        $stmt = $pdo_db->prepare($sql);
        $stmt->bindParam(":mb_id", $mb_id);
        $result = pdo_query($stmt, FALSE);
    }
}
// --- �Ҵ��� ȸ�� ������ ��

// ȸ�����̵� ���� ����
set_session('ss_mb_id', $mb[mb_id]);
// FLASH XSS ���ݿ� �����ϱ� ���Ͽ� ȸ���� ����Ű�� ������ ���´�. �����ڿ��� �˻��� - 110106
set_session('ss_mb_key', md5($mb[mb_datetime] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
session_write_close();

// 3.26
// ���̵� ��Ű�� �Ѵް� ����
if ($auto_login) {
    // 3.27
    // �ڵ��α��� ---------------------------
    // ��Ű �Ѵް� ����
    if ($g4['load_balance']) {
        $key = md5($g4['load_balance'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password'] . $mb['mb_no']);
    } else {
        $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password'] . $mb['mb_no']);
    }

    // �Ҵ��� - unique�� ���� ������ �ݴϴ�
    $uid = md5($mb['mb_no'] . $_SERVER['HTTP_USER_AGENT']);

    // cookie DB���� key�� ���� ��츦 ��� �������ݴϴ�
    //$sql = " delete from $g4[cookie_table] where cookie_key='$key' ";
    //sql_query($sql);

    $sql = " delete from $g4[cookie_table] where cookie_key=:key ";
    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":key", $key);
    $result = pdo_query($stmt, FALSE);

    // ��Ű�� Key�� DB�� ����
    //$sql = " insert into $g4[cookie_table] set cookie_name='$uid', cookie_value='$mb[mb_id]', cookie_key='$key', cookie_datetime='$g4[time_ymdhis]' ";
    //sql_query($sql);

    $sql = " insert into $g4[cookie_table] set cookie_name=:uid, cookie_value=:mb_id, cookie_key=:key, cookie_datetime='$g4[time_ymdhis]' ";

    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":uid", base64_encode($uid));
    $stmt->bindParam(":mb_id", $mb[mb_id]);
    $stmt->bindParam(":key", $key);
    $result = pdo_query($stmt);

    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_mb_id', $uid, 86400 * 31);
    // �ڵ��α��� end ---------------------------
} else {
    set_cookie('ck_mb_id', '', 0);
}

// �Ҵ��� - ���̵� �ڵ�����
if ($auto_mb_id) {
    // ��Ű �Ѵް� ����
    set_cookie('ck_auto_mb_id', encrypt($mb[mb_id],$g4[encrypt_key]), 86400 * 31);
} else {
    set_cookie('ck_auto_mb_id', '', 0);
}

if ($url) 
{
    $link = urldecode($url);
    // 2003-06-14 �߰� (�ٸ� �������� �Ѱ��ֱ� ����)
    if (preg_match("/\?/", $link))
        $split= "&"; 
    else
        $split= "?"; 

    // $_POST �迭�������� �Ʒ��� �̸��� ������ ���� �͸� �ѱ�
    foreach($_POST as $key=>$value) 
    {
        if ($key != "mb_id" && $key != "mb_password" && $key != "x" && $key != "y" && $key != "url") 
        {
            $link .= "$split$key=$value";
            $split = "&";
        }
    }
} 
else
    $link = $g4[path];

// �������� �����ֱ�
if ($mb['mb_password_change_datetime'] == '0000-00-00 00:00:00') {
    // �ʱ�ȭ (�������������ֱ�)

    $next_change = strtotime("$mb[mb_open_date] 00:00:00") + ($config['cf_password_change_dates'] * 24 * 60 * 60);
    $next_date = date('Y-m-d h:i:s', $next_change);

    //$sql = " update $g4[member_table] set mb_password_change_datetime = '$next_date' where mb_id = '$mb_id'";
    //sql_query($sql);

    $sql = " update $g4[member_table] set mb_password_change_datetime = '$next_date' where mb_id = :mb_id ";

    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":mb_id", $mb_id);
    $result = pdo_query($stmt, FALSE);
    
    // ������ ����
    $mb['mb_password_change_datetime'] = $next_date;
}

$change_alert = $g4[server_time] - strtotime($mb['mb_password_change_datetime']);
if ($config['cf_password_change_dates'] > 0 && $change_alert > 0) {
        $link = "$g4[bbs_path]/password_change_request.php?url=$url";
}

// �Ҵ��� - ������ �α��� ������ db log�� ����ϴ�
if (is_admin_check($mb_id)) {
    $log = "�����ڷα��� : $mb_id - $remote_addr - $_SERVER[HTTP_USER_AGENT]";
    //$sql = " insert into $g4[admin_log_table] 
    //            set log_datetime = '$g4[time_ymdhis]',
    //                log = '" . mysql_real_escape_string($log) . "' ";
    //sql_query($sql);

    $sql = " insert into $g4[admin_log_table] set log_datetime = '$g4[time_ymdhis]', log = :log ";

    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":log", $log);
    $result = pdo_query($stmt, FALSE);
}

// �Ҵ��� - �޸�ȸ�� ������ DB�� �����Ѵ�
// g4_member�� g4_member_unlogin�� db�� ������ ������ 100% �����ϰ� �������� �մϴ�!!!
if ($mb['mb_unlogin'] !== "0000-00-00 00:00:00") {
    $sql = " replace $g4[member_table] select * from $g4[unlogin_table] where mb_id = '$mb_id' ";
    sql_query($sql);

    // mb_unlogin �ʵ带 �ʱ�ȭ �մϴ�.
    //$sql = " update $g4[member_table] set mb_unlogin = '0000-00-00 00:00:00' where mb_id = '$mb_id' ";
    //sql_query($sql);

    $sql = " update $g4[member_table] set mb_unlogin = '0000-00-00 00:00:00' where mb_id = :mb_id ";

    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":mb_id", $mb_id);
    $result = pdo_query($stmt, FALSE);

    // unlogin_table�� �ش� �ʵ带 ���� �մϴ�.
    //$sql = " delete from $g4[unlogin_table] where mb_id = '$mb_id' ";
    //sql_query($sql);

    $sql = " delete from $g4[unlogin_table] where mb_id = :mb_id ";

    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":mb_id", $mb_id);
    $result = pdo_query($stmt, FALSE);

    // �޸���� ������ ���� �ؾ� �ϴ� ������ ���� �մϴ�.
    if (file_exists("$member_skin_path/unlogin_member.skin.php"))
        @include_once("$member_skin_path/unlogin_member.skin.php");

    // ��й�ȣ �����û �������� �̵� �մϴ�.
    $link = "$g4[bbs_path]/password_change_request.php?url=$url";
}

goto_url($link);
?>
