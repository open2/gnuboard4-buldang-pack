<?
include_once("./_common.php");
include_once("$g4[path]/lib/mailer.lib.php");

// ���ǿ� ����� ��ū�� �������� �Ѿ�� ��ū�� ���Ͽ� Ʋ���� ����
if ($_POST["token"] && get_session("ss_token") == $_POST["token"]) 
{
    // ���� �� ���� �ٷ����� ������� ��Ű�� ���ٸ� ����
    //if (!get_cookie($_POST["token"])) alert_close("��Ű ����");

    // ������ ���ǰ� ��Ű�� ���� �ٽ� �Է����� ���ؼ� �������� �Ѵ�.
    set_session("ss_token", "");
    set_cookie($_POST["token"], 0, 0);
} 
else 
{
    alert("��ū ����", "$g4[path]");
    exit;
}

// ���۷� üũ
referer_check();

// ������ ip���� ���Ӱ����� ��� �մϴ�.
// ���� ip���� 10�� �̳��� ������ ȸ���� ������, �κ����� ó�� �մϴ�.
// �޽����� �Ϻη� �����ϰ� ��� �մϴ�.
$sql = " select *, count(*) as cnt from $g4[member_table]
          where mb_login_ip = '$remote_addr' and mb_datetime >= '" . date("Y-m-d H:i:s", $g4[server_time] - 60*10 ) . "'
          order by mb_datetime desc limit 1";
$result = sql_fetch($sql);

if ($result['cnt'] > 0)
    alert("������ BUS ����", "$g4[path]");

if (!($w == "" || $w == "u")) 
    alert("w ���� ����� �Ѿ���� �ʾҽ��ϴ�.");

if ($w == "u" && $is_admin == "super") {
    if (file_exists("$g4[path]/DEMO")) 
        alert("���� ȭ�鿡���� �Ͻ�(����) �� ���� �۾��Դϴ�.");
}

// ���������� �� ��쿡��
if ($config[cf_use_norobot]) {
    include_once("$g4[path]/zmSpamFree/zmSpamFree.php");
    if ( !zsfCheck( $_POST['wr_key'], 'sms_admin' ) ) { alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.'); }    
}

//$mb_id = trim(strip_tags(mysql_real_escape_string($_POST[mb_id])));
if($w == 'u')
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';
else if($w == '')
    $mb_id = trim(strip_tags(mysql_real_escape_string($_POST[mb_id])));
else
    alert('�߸��� �����Դϴ�', $g4[url]);

if (preg_match("/[^0-9a-z_]+/i", $mb_id)) {
    alert("ȸ�����̵�� ������, ����, _ �� ����Ҽ� �ֽ��ϴ�.");
}
$mb_password = trim(mysql_real_escape_string($_POST[mb_password]));
// �г������� �����ϴ� ���, $mb_name = $mb_knick
if ($g4['nick_reg_only'] !== 1) {
    $mb_name = trim(strip_tags(mysql_real_escape_string($_POST[mb_name])));
} else {
    $mb_name = $mb_nick;
}
$mb_nick = trim(strip_tags(mysql_real_escape_string($_POST[mb_nick])));
$mb_email = trim(strip_tags(mysql_real_escape_string($_POST[mb_email])));
$mb_homepage = trim(strip_tags(mysql_real_escape_string($_POST[mb_homepage])));
$mb_email = str_replace('\\', '', $mb_email);
$mb_homepage = str_replace('\\', '', $mb_homepage);
$ug_id = trim(strip_tags(mysql_real_escape_string($_POST[ug_id])));

if ($w == '' || $w == 'u') 
{
    if (!$mb_id) alert('ȸ�����̵� �Ѿ���� �ʾҽ��ϴ�.');
    if ($w == '' && !$mb_password) alert('�н����尡 �Ѿ���� �ʾҽ��ϴ�.');
    if (!$mb_name) alert('�̸�(�Ǹ�)�� �Ѿ���� �ʾҽ��ϴ�.');
    if (!$mb_nick) alert('������ �Ѿ���� �ʾҽ��ϴ�.');
    if (!$mb_email) alert('E-mail �� �Ѿ���� �ʾҽ��ϴ�.');

    if (preg_match("/[\,]?{$mb_id}/i", $config[cf_prohibit_id]))
        alert("\'$mb_id\' ��(��) ������ ����Ͻ� �� ���� ȸ�����̵��Դϴ�.");

    if (preg_match("/[\,]?{$mb_nick}/i", $config[cf_prohibit_id]))
        alert("\'$mb_nick\' ��(��) ������ ����Ͻ� �� ���� �����Դϴ�.");

    // �̸��� �ѱ۸� ����
    if ($g4['nick_reg_only'] !== 1) {
        if (!check_string($mb_name, _G4_HANGUL_  + _G4_ALPHABETIC_ )) 
            alert('�̸��� ������� �ѱ� �Ǵ� ������ �Է� �����մϴ�.');
    }

    // ������ �ѱ�, ����, ���ڸ� ����
    if (!check_string($mb_nick, _G4_HANGUL_ + _G4_ALPHABETIC_ + _G4_NUMERIC_))
        alert('������ ������� �ѱ�, ����, ���ڸ� �Է� �����մϴ�.');

    if ($w=='')
    {
        //if (strtolower($mb_id) == strtolower($mb_recommend)) alert('������ ��õ�� �� �����ϴ�.');
        // �Ҵ��� - ��õ���� ����� ���� ��õ Ȯ��
        if ($config[cf_use_recommend]) 
        {
            if ($config[cf_req_recommend] && !$mb_recommend)
                alert('��õ�� ���̵� �Է��ؾ� ȸ�������� �����մϴ�.'); 
            if ($mb_recommend && strtolower($mb_id) == strtolower($mb_recommend)) alert('������ ��õ�� �� �����ϴ�.'); 
            $mb_recommend2 = get_member($mb_recommend, "mb_id"); 
            if ($config[cf_req_recommend] && !$mb_recommend2) alert('��õ�� ���̵� �����ϴ�.'); 
        }

        // ȸ�����Խ� member_table�� �ߺ� ���� ������ Ȯ��
        //$sql = " select count(*) as cnt from $g4[member_table] where mb_nick = '$mb_nick' ";
        //$row = sql_fetch($sql);
        $stmt = $pdo_db->prepare(" select count(*) as cnt from $g4[member_table] where mb_nick = :mb_nick ");
        $stmt->bindParam(":mb_nick", $mb_nick);
        $row = pdo_fetch($stmt);
        if ($row[cnt])
            alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.");

        // �Ҵ��� - ȸ�����Խ� mb_nick_table�� �ߺ� ���� ������ Ȯ��
        //$sql = " select count(*) as cnt from $g4[mb_nick_table] where mb_nick = '$mb_nick' ";
        //$row = sql_fetch($sql);
        $stmt = $pdo_db->prepare(" select count(*) as cnt from $g4[mb_nick_table] where mb_nick = :mb_nick ");
        $stmt->bindParam(":mb_nick", $mb_nick);
        $row = pdo_fetch($stmt);
        if ($row[cnt])
            alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.");

        //$sql = " select count(*) as cnt from $g4[member_table] where mb_email = '$mb_email' ";
        //$row = sql_fetch($sql);
        $stmt = $pdo_db->prepare(" select count(*) as cnt from $g4[member_table] where mb_email = :mb_email ");
        $stmt->bindParam(":mb_email", $mb_email);
        $row = pdo_fetch($stmt);
        if ($row[cnt])
            alert("\'$mb_email\' ��(��) �̹� �ٸ����� ������� E-mail�̹Ƿ� ����� �Ұ��մϴ�.");
    }
    else
    {
        // �ڹٽ�ũ��Ʈ�� ���������� ������ ���� ����
        // ����������� ������ �ʾҴٸ�
        if ($member[mb_nick_date] > date("Y-m-d", $g4[server_time] - ($config[cf_nick_modify] * 86400)))
            $mb_nick = $member[mb_nick];
        // ȸ�������� ������ ���� ���Ϸ� �ű�� �Ʒ����� ����
        $old_email = $member[mb_email];

        // �Ҵ��� - ȸ�������� Ȩ�������� ���� Ȩ�������� �ű�� �Ʒ����� ����
        $old_homepage = $member[mb_homepage];

        //$sql = " select count(*) as cnt from $g4[member_table] where mb_nick = '$mb_nick' and mb_id <> '$mb_id' ";
        //$row = sql_fetch($sql);
        $stmt = $pdo_db->prepare(" select count(*) as cnt from $g4[member_table] where mb_nick = :mb_nick and mb_id <> :mb_id ");
        $stmt->bindParam(":mb_nick", $mb_nick);
        $stmt->bindParam(":mb_id", $mb_id);
        $row = pdo_fetch($stmt);
        if ($row[cnt])
            alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.");

        // �Ҵ��� - ȸ�����Խ� mb_nick_table�� �ߺ� ���� ������ Ȯ��
        //$sql = " select count(*) as cnt from $g4[mb_nick_table] where mb_nick = '$mb_nick' and mb_id != '$member[mb_id]' ";
        //$row = sql_fetch($sql);
        $stmt = $pdo_db->prepare(" select count(*) as cnt from $g4[mb_nick_table] where mb_nick = :mb_nick and mb_id != '$member[mb_id]' ");
        $stmt->bindParam(":mb_nick", $mb_nick);
        $row = pdo_fetch($stmt);
        if ($row[cnt])
            alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.");

        //$sql = " select count(*) as cnt from $g4[member_table] where mb_email = '$mb_email' and mb_id <> '$mb_id' ";
        //$row = sql_fetch($sql);
        $stmt = $pdo_db->prepare(" select count(*) as cnt from $g4[member_table] where mb_email = :mb_email and mb_id <> :mb_id ");
        $stmt->bindParam(":mb_email", $mb_email);
        $stmt->bindParam(":mb_id", $mb_id);
        $row = pdo_fetch($stmt);
        if ($row[cnt])
            alert("\'$mb_email\' ��(��) �̹� �ٸ����� ������� E-mail�̹Ƿ� ����� �Ұ��մϴ�.");
    }
}

$mb_dir = "$g4[data_path]/member/".substr($mb_id,0,2);

// ������ ����
if ($del_mb_icon)
    @unlink("$mb_dir/$mb_id.gif");

$msg = "";

// ������ ���ε�
$mb_icon = "";
if (is_uploaded_file($_FILES[mb_icon][tmp_name])) 
{
    //if (preg_match("/(\.gif)$/i", $_FILES[mb_icon][name])) 
    if (preg_match("/\.(jp[e]?g|gif|png|bmp)$/i", $_FILES[mb_icon][name]))
    {
        // ������ �뷮�� ���������� ���ϸ� ���ε� ����
        if ($_FILES[mb_icon][size] <= $config[cf_member_icon_size]) 
        {
            @mkdir($mb_dir, 0707);
            @chmod($mb_dir, 0707);
            $dest_path = "$mb_dir/$mb_id.gif";
            move_uploaded_file($_FILES[mb_icon][tmp_name], $dest_path);
            chmod($dest_path, 0606);
            if (file_exists($dest_path)) 
            {
                //=================================================================\
                // 090714
                // gif ���Ͽ� �Ǽ��ڵ带 �ɾ� ���ε� �ϴ� ��츦 ����
                // �����޼����� ������� �ʴ´�.
                //-----------------------------------------------------------------
                $size = getimagesize($dest_path);
                if ($size[2] != 1) // gif ������ �ƴϸ� �ö� �̹����� �����Ѵ�.
                    @unlink($dest_path);
                else
                // �������� �� �Ǵ� ���̰� ������ ���� ũ�ٸ� �̹� ���ε� �� ������ ����
                if ($size[0] > $config[cf_member_icon_width] || $size[1] > $config[cf_member_icon_height])
                    @unlink($dest_path);
                //=================================================================\
            }
        } else 
        {
            $msg .= "{$_FILES[mb_icon][name]} ������ �뷮�� " . number_format($config[cf_member_icon_size]/1000) . "k ����Ʈ���� ũ�Ƿ� ���ε� �� �� �����ϴ�.\\n";
        }
    }
    else
        $msg .= $_FILES[mb_icon][name] . "��(��) gif/jpg/bmp/png ������ �ƴմϴ�.";
}


// �����ڴ� ȸ������
$admin = get_admin('super');


if ($w == "") 
{
    $mb = get_member($mb_id);
    if ($mb[mb_id]) 
        alert("�̹� ������ ���̵��Դϴ�.");

    $sql = " insert into $g4[member_table]
                set mb_id = '$mb_id',
                    mb_password = '".sql_password($mb_password)."',
                    mb_name = '$mb_name',
                    mb_sex = '$mb_sex',
                    mb_birth = '$mb_birth',
                    mb_nick = '$mb_nick',
                    mb_nick_date = '$g4[time_ymd]',
                    mb_email = '$mb_email',
                    mb_homepage = '$mb_homepage',
                    mb_tel = '$mb_tel',
                    mb_hp = '$mb_hp',
                    mb_zip1 = '$mb_zip1',
                    mb_zip2 = '$mb_zip2',
                    mb_addr1 = '$mb_addr1',
                    mb_addr2 = '$mb_addr2',
                    mb_signature = '$mb_signature',
                    mb_profile = '$mb_profile',
                    mb_today_login = '$g4[time_ymdhis]',
                    mb_datetime = '$g4[time_ymdhis]',
                    mb_password_change_datetime = '$g4[time_ymdhis]',
                    mb_ip = '$remote_addr',
                    mb_level = '$config[cf_register_level]',
                    mb_recommend = '$mb_recommend',
                    mb_login_ip = '$remote_addr',
                    mb_mailling = '$mb_mailling',
                    mb_sms = '$mb_sms',
                    mb_open = '$mb_open',
                    mb_open_date = '$g4[time_ymd]',
                    mb_realmemo = '$mb_realmemo',
                    mb_realmemo_sound = '$mb_realmemo_sound',
                    ug_id = '$ug_id',
                    mb_1 = '$mb_1',
                    mb_2 = '$mb_2',
                    mb_3 = '$mb_3',
                    mb_4 = '$mb_4',
                    mb_5 = '$mb_5',
                    mb_6 = '$mb_6',
                    mb_7 = '$mb_7',
                    mb_8 = '$mb_8',
                    mb_9 = '$mb_9',
                    mb_10 = '$mb_10' ";
    // �̸��� ������ ������� �ʴ´ٸ� �̸��� �����ð��� �ٷ� �ִ´�
    // �Ҵ��� : ���������� ���� �ʽ��ϴ�.
    //if (!$config[cf_use_email_certify])
    //    $sql .= " , mb_email_certify = '$g4[time_ymdhis]' ";
    sql_query($sql);

    // ȸ������ ����Ʈ �ο�
    insert_point($mb_id, $config[cf_register_point], "ȸ������ ����", '@member', $mb_id, 'ȸ������');

    // ��õ�ο��� ����Ʈ �ο�
    if ($config[cf_use_recommend] && $mb_recommend)
        insert_point($mb_recommend, $config[cf_recommend_point], "{$mb_id}�� ��õ��", '@member', $mb_recommend, "{$mb_id} ��õ");

    // �Ҵ��� - mb_nick�� db�� �߰�
    //$sql2 = " insert $g4[mb_nick_table] set  mb_id = '$mb_id', mb_nick = '$mb_nick', start_datetime = '$g4[time_ymdhis]' ";
    //sql_query($sql2);
    $stmt = $pdo_db->prepare(" insert $g4[mb_nick_table] set  mb_id = :mb_id, mb_nick = :mb_nick, start_datetime = '$g4[time_ymdhis]' ");
    $stmt->bindParam(":mb_id", $mb_id);
    $stmt->bindParam(":mb_nick", $mb_nick);
    $result = pdo_query($stmt, false);

    // ȸ���Բ� ���� �߼�
    // ���������� ����ϸ� ���������� �߼�. ���ϸ� ���� ������� �߼� - �Ҵ���
    if ($config[cf_use_email_certify])
    {
        $subject = "����Ȯ�� �����Դϴ�. " . $config['cf_title'];

        $mb_md5 = md5($mb_id.$mb_email.$member[mb_datetime]);
        $certify_href = "$g4[url]/$g4[bbs]/email_certify.php?mb_id=$mb_id&mb_md5=$mb_md5";
        
        ob_start();
        include_once ("./register_form_update_mail3.php");
        $content = ob_get_contents();
        ob_end_clean();

        mailer($config['cf_title'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
    } else
    if ($config[cf_email_mb_member])
    {
        $subject = "ȸ�������� ���ϵ帳�ϴ�. " . $config['cf_title'];

        $mb_md5 = md5($mb_id.$mb_email.$g4[time_ymdhis]);
        $certify_href = "$g4[url]/$g4[bbs]/email_certify.php?mb_id=$mb_id&mb_md5=$mb_md5";
        
        ob_start();
        include_once ("./register_form_update_mail1.php");
        $content = ob_get_contents();
        ob_end_clean();
        
        mailer($config['cf_title'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
    }

    // �Ҵ��� - ȸ���Բ� ���� �߼�
    if ($config[cf_memo_mb_member]) 
    {
        include_once("$g4[path]/memo.config.php");

        $me_subject = "ȸ�������� ���ϵ帳�ϴ�.";
        $me_memo = "<b>{$mb_name}</b>���� ȸ�������� �������� �����մϴ�.
                    <p>ȸ������ ������ �����ϰ��� ���� �� ������ �ϰڽ��ϴ�.
                    <p>�����մϴ�";
        $me_option = "html1";
        $mb_memo_call = 1;

        memo4_send($mb_id, $config[cf_admin], $me_memo, $me_subject, $me_option, $mb_memo_call);
    }


    // �ְ�����ڴԲ� ���� �߼�
    if ($config[cf_email_mb_super_admin]) 
    {
        $subject = $mb_nick . " �Բ��� ȸ������ �����ϼ̽��ϴ�.";
        
        ob_start();
        include_once ("./register_form_update_mail2.php");
        $content = ob_get_contents();
        ob_end_clean();

        mailer($mb_nick, $mb_email, $admin[mb_email], $subject, $content, 1);
    }

    // �������� ������� �ʴ� ��쿡�� �α���
    if (!$config[cf_use_email_certify])
        set_session("ss_mb_id", $mb_id);

    set_session("ss_mb_reg", $mb_id);
} 
else if ($w == "u") 
{
    if (!trim($_SESSION["ss_mb_id"]))
        alert("�α��� �Ǿ� ���� �ʽ��ϴ�.");

    if ($_SESSION["ss_mb_id"] != $_POST[mb_id])
        alert("�α��ε� ������ �����Ϸ��� ������ Ʋ���Ƿ� ������ �� �����ϴ�.\\n\\n���� �ùٸ��� ���� ����� ����ϽŴٸ� �ٷ� �����Ͽ� �ֽʽÿ�.");

    $sql_password = "";
    if ($mb_password)
        $sql_password = " , mb_password = '".sql_password($mb_password)."' ";

    $sql_icon = "";
    if ($mb_icon)
        $sql_icon = " , mb_icon = '$mb_icon' ";

    $sql_nick_date = "";
    if ($mb_nick_default != $mb_nick)
        $sql_nick_date =  " , mb_nick_date = '$g4[time_ymd]' ";

    // �Ҵ��� - �г����� ����Ǹ� history�� ��� �մϴ�.
    if ($mb_nick_default != $mb_nick) {
        // ���� ����ϴ� �г����� �ִ��� Ȯ��
        $sql = " select count(*) as cnt from $g4[mb_nick_table] where mb_id = '$member[mb_id]' and mb_nick = '$mb_nick' ";
        $result = sql_fetch($sql);
        if ($result['cnt']) {
            $sql = " update $g4[mb_nick_table] set start_datetime = '$g4[time_ymdhis]', end_datetime='0000-00-00 00:00:00' where mb_id = '$member[mb_id]' and mb_nick = '$mb_nick_default' ";
        }
        else
        {
            $sql = " insert $g4[mb_nick_table] set  mb_id = '$member[mb_id]', mb_nick = '$mb_nick', start_datetime = '$g4[time_ymdhis]' ";
            sql_query($sql);
            
            // ������ ���� nickname�� close
            $sql = " update $g4[mb_nick_table] set end_datetime = '$g4[time_ymdhis]' where mb_id = '$member[mb_id]' and mb_nick = '$mb_nick_default' ";
            sql_query($sql);
        }
    }
    
    $sql_open_date = "";
    if ($mb_open_default != $mb_open)
        $sql_open_date =  " , mb_open_date = '$g4[time_ymd]' ";

    $sql_sex = "";
    if (isset($mb_sex))
        $sql_sex = " , mb_sex = '$mb_sex' ";

    // ���� �����ּҿ� ������ �����ּҰ� Ʋ���ٸ� ������ �ٽ� �ؾ��ϹǷ� ���� ����
    $sql_email_certify = "";

    //�Ҵ��� - �̸��� ������ ���� ������, ������ �ϴ� ��Ȳ�� �ƴ϶� ������¥�� clear
    //if ($old_email != $mb_email && $config[cf_use_email_certify])
    if ($old_email != $mb_email && ($config[cf_use_email_certify] || preg_match("/[1-9]/", $member[mb_email_certify])))
        $sql_email_certify = " , mb_email_certify = '' ";

                // set mb_name         = '$mb_name', ����
    $sql = " update $g4[member_table]
                set mb_nick         = '$mb_nick',
                    mb_password_q   = '$mb_password_q',
                    mb_password_a   = '$mb_password_a',
                    mb_mailling     = '$mb_mailling',
                    mb_sms          = '$mb_sms',
                    mb_open         = '$mb_open',
                    mb_email        = '$mb_email',
                    mb_homepage     = '$mb_homepage',
                    mb_tel          = '$mb_tel',
                    mb_hp           = '$mb_hp',
                    mb_zip1         = '$mb_zip1',
                    mb_zip2         = '$mb_zip2',
                    mb_addr1        = '$mb_addr1',
                    mb_addr2        = '$mb_addr2',
                    mb_signature    = '$mb_signature',
                    mb_profile      = '$mb_profile',
                    mb_realmemo     = '$mb_realmemo',
                    mb_realmemo_sound = '$mb_realmemo_sound',
                    mb_1            = '$mb_1',
                    mb_2            = '$mb_2',
                    mb_3            = '$mb_3',
                    mb_4            = '$mb_4',
                    mb_5            = '$mb_5',
                    mb_6            = '$mb_6',
                    mb_7            = '$mb_7',
                    mb_8            = '$mb_8',
                    mb_9            = '$mb_9',
                    mb_10           = '$mb_10'
                    $sql_password 
                    $sql_icon 
                    $sql_nick_date
                    $sql_open_date
                    $sql_sex
                    $sql_email_certify
              where mb_id = '$_POST[mb_id]' ";
    sql_query($sql);

    // �������� �߼�
    if ($old_email != $mb_email && $config[cf_use_email_certify])
    {
        $subject = "����Ȯ�� �����Դϴ�.";

        $mb_md5 = md5($mb_id.$mb_email.$member[mb_datetime]);
        $certify_href = "$g4[url]/$g4[bbs]/email_certify.php?mb_id=$mb_id&mb_md5=$mb_md5";
        
        ob_start();
        include_once ("./register_form_update_mail3.php");
        $content = ob_get_contents();
        ob_end_clean();

        mailer($config['cf_title'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
    }
}

// �������� �����ֱ� (�н����尡 ����Ǹ� reset) - �Ҵ���
// ������ �ڵ尡 bbs/password_change_reset.php�� �ֽ��ϴ�.
if ($mb_password) {
    $next_change = $g4[server_time] + ($config['cf_password_change_dates'] * 24 * 60 * 60);
    $next_date = date('Y-m-d h:i:s', $next_change);

    $sql = " update $g4[member_table] set mb_password_change_datetime = '$next_date' where mb_id = '$mb_id'";
    sql_query($sql);
}

// ���԰�� ����ϱ� - �Ҵ���
if ($w == "") 
{
    $sql = " select vi_referer from `$g4[visit_table]` where vi_ip='$_SERVER[REMOTE_ADDR]' and vi_referer <> '' order by vi_id desc LIMIT 1 ";
    $temp = sql_fetch($sql);
    $ref_url = $temp[vi_referer];
    if ($ref_url) {
        $mb = get_member($mb_id, "mb_no");
        $sql = " insert into `$g4[member_register_table]` 
                    set mb_no = '$mb[mb_no]',
                        mb_id = '$mb_id',
                        ref_url='$ref_url'
              ";
        sql_query($sql);
    }
}

// ����� �ڵ� ����
@include_once ("$g4[path]/skin/member/$config[cf_member_skin]/register_update.skin.php");


if ($msg) 
    echo "<script type='text/javascript'>alert('{$msg}');</script>";

/*
// ����������� https ���� http �� ������ �Ǿ�� ��
if ($g4[https_url])
    $https_url = "$g4[https_url]/$g4[bbs]";
else
    $https_url = ".";
*/

$https_url = "$g4[url]/$g4[bbs]";

if ($w == "") {
    goto_url("{$https_url}/register_result.php");
} else if ($w == "u") {
    $row  = sql_fetch(" select mb_password from $g4[member_table] where mb_id = '$member[mb_id]' ");
    $tmp_password = $row['mb_password'];

    if ($old_email != $mb_email && $config[cf_use_email_certify]) {
        set_session("ss_mb_id", "");
        alert("ȸ�� ������ ���� �Ǿ����ϴ�.\\n\\nE-mail �ּҰ� ����Ǿ����Ƿ� �ٽ� �����ϼž� �մϴ�.", $g4[path]);
    } else {
        echo "
        <html><title>ȸ����������</title><meta http-equiv='Content-Type' content='text/html; charset=$g4[charset]'></html><body> 
        <form name='fregisterupdate' method='post' action='{$https_url}/register_form.php'>
        <input type='hidden' name='w' value='u'>
        <input type='hidden' name='mb_id' value='{$mb_id}'>
        <input type='hidden' name='mb_password' value='{$tmp_password}'>
        <input type='hidden' name='is_update' value='1'>
        </form>
        <script type='text/javascript'>
        alert('ȸ�� ������ ���� �Ǿ����ϴ�.');
        document.fregisterupdate.submit();
        </script>
        </body>
        </html>";
    }
}
?>
