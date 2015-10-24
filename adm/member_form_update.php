<?
$sub_menu = "200100";
include_once("./_common.php");

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], "w");

check_token();

$mb_id = mysql_real_escape_string(trim($_POST['mb_id']));

$mb_name         = $_POST[mb_name];
$mb_nick         = get_text($_POST[mb_nick]);
$mb_email        = $_POST[mb_email];
$mb_homepage     = $_POST[mb_homepage];
$mb_tel          = $_POST[mb_tel];
$mb_hp           = $_POST[mb_hp];
$mb_zip1         = $_POST[mb_zip1];
$mb_zip2         = $_POST[mb_zip2];
$mb_addr1        = $_POST[mb_addr1];
$mb_addr2        = $_POST[mb_addr2];
$mb_birth        = $_POST[mb_birth];
$mb_sex          = $_POST[mb_sex];
$mb_signature    = $_POST[mb_signature];
$mb_leave_date   = $_POST[mb_leave_date];
$mb_intercept_date=$_POST[mb_intercept_date];
$mb_memo         = htmlspecialchars($_POST[mb_memo]);   // mb_memo�� Ư�����ڰ� ���� ��� ������
$mb_mailling     = $_POST[mb_mailling];
$mb_sms          = $_POST[mb_sms];
$mb_open         = $_POST[mb_open];
$mb_profile      = $_POST[mb_profile];
$mb_level        = $_POST[mb_level];
$ug_id           = $_POST[ug_id];
$mb_1            = $_POST[mb_1];
$mb_2            = $_POST[mb_2];
$mb_3            = $_POST[mb_3];
$mb_4            = $_POST[mb_4];
$mb_5            = $_POST[mb_5];
$mb_6            = $_POST[mb_6];
$mb_7            = $_POST[mb_7];
$mb_8            = $_POST[mb_8];
$mb_9            = $_POST[mb_9];
$mb_10           = $_POST[mb_10];

$sql_common = " mb_name         = '$mb_name',
                mb_nick         = '$mb_nick',
                mb_email        = '$mb_email',
                mb_homepage     = '$mb_homepage',
                mb_tel          = '$mb_tel',
                mb_hp           = '$mb_hp',
                mb_zip1         = '$mb_zip1',
                mb_zip2         = '$mb_zip2',
                mb_addr1        = '$mb_addr1',
                mb_addr2        = '$mb_addr2',
                mb_birth        = '$mb_birth',
                mb_sex          = '$mb_sex',
                mb_signature    = '$mb_signature',
                mb_leave_date   = '$mb_leave_date',
                mb_intercept_date='$mb_intercept_date',
                mb_memo         = '$mb_memo',
                mb_mailling     = '$mb_mailling',
                mb_sms          = '$mb_sms',
                mb_open         = '$mb_open',
                mb_profile      = '$mb_profile',
                mb_level        = '$mb_level',
                ug_id           = '$ug_id',
                mb_1            = '$mb_1',
                mb_2            = '$mb_2',
                mb_3            = '$mb_3',
                mb_4            = '$mb_4',
                mb_5            = '$mb_5',
                mb_6            = '$mb_6',
                mb_7            = '$mb_7',
                mb_8            = '$mb_8',
                mb_9            = '$mb_9',
                mb_10           = '$mb_10' ";

// ==== �Ʒ� �κ��� bbs/register_form_update.php���� ������ �̴ϴ�.
// ==== �����ڵ� ����̶� �Ǽ��ϴµ�, �װŴ� ���ؾ���.

// �̸��� �ѱ۸� ����
if (!check_string($mb_name, _G4_HANGUL_  + _G4_ALPHABETIC_ )) 
    alert('�̸��� ������� �ѱ� �Ǵ� ������ �Է� �����մϴ�.');

// ������ �ѱ�, ����, ���ڸ� ����
if (!check_string($mb_nick, _G4_HANGUL_ + _G4_ALPHABETIC_ + _G4_NUMERIC_))
    alert('������ ������� �ѱ�, ����, ���ڸ� �Է� �����մϴ�.');

if ($w == "")
{
    // �ߺ� ������ ������ Ȯ�� �մϴ�.
    $sql = " select count(*) as cnt from $g4[member_table] where mb_nick = '$mb_nick' ";
    $row = sql_fetch($sql);
    if ($row[cnt])
        alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.");
    
    // �Ҵ��� - ȸ�����Խ� mb_nick_table�� �ߺ� ���� ������ Ȯ��
    $sql = " select count(*) as cnt from $g4[mb_nick_table] where mb_nick = '$mb_nick' ";
    $row = sql_fetch($sql);
    if ($row[cnt])
        alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.");
    
    $sql = " select count(*) as cnt from $g4[member_table] where mb_email = '$mb_email' ";
    $row = sql_fetch($sql);
    if ($row[cnt])
        alert("\'$mb_email\' ��(��) �̹� �ٸ����� ������� E-mail�̹Ƿ� ����� �Ұ��մϴ�.");
}
else if ($w == "u")
{
    // �ߺ� ������ ������ Ȯ�� �մϴ�.
    $sql = " select count(*) as cnt from $g4[member_table] where mb_nick = '$mb_nick' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row[cnt])
        alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.1");
    
    // �Ҵ��� - ȸ�����Խ� mb_nick_table�� �ߺ� ���� ������ Ȯ��
    $sql = " select count(*) as cnt from $g4[mb_nick_table] where mb_nick = '$mb_nick' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row[cnt])
        alert("\'$mb_nick\' ��(��) �̹� �ٸ����� ������� �����̹Ƿ� ����� �Ұ��մϴ�.2");
    
    $sql = " select count(*) as cnt from $g4[member_table] where mb_email = '$mb_email' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row[cnt])
        alert("\'$mb_email\' ��(��) �̹� �ٸ����� ������� E-mail�̹Ƿ� ����� �Ұ��մϴ�.");
}

if ($w == "")
{
    $mb = get_member($mb_id);
    if ($mb[mb_id])
        alert("�̹� �����ϴ� ȸ���Դϴ�.\\n\\n�ɣ� : $mb[mb_id]\\n\\n�̸� : $mb[mb_name]\\n\\n���� : $mb[mb_nick]\\n\\n���� : $mb[mb_email]");

    // �Ҵ��� - mb_nick�� db�� �߰�
    $sql2 = " insert $g4[mb_nick_table] set  mb_id = '$mb_id', mb_nick = '$mb_nick', start_datetime = '$g4[time_ymdhis]' ";
    sql_query($sql2);

    sql_query(" insert into $g4[member_table] set mb_id = '$mb_id', mb_password = '".sql_password($mb_password)."', mb_datetime = '$g4[time_ymdhis]', mb_ip = '$remote_addr', mb_email_certify = '$g4[time_ymdhis]', $sql_common  ");
}
else if ($w == "u")
{
    $mb = get_member($mb_id);
    if (!$mb[mb_id])
        alert("�������� �ʴ� ȸ���ڷ��Դϴ�.");

    if ($is_admin != "super" && $mb[mb_level] >= $member[mb_level])
        alert("�ڽź��� ������ ���ų� ���� ȸ���� ������ �� �����ϴ�.");

    if ($_POST[mb_id] == $member[mb_id] && $_POST[mb_level] != $mb[mb_level])
        alert("$mb[mb_id] : �α��� ���� ������ ������ ���� �� �� �����ϴ�.");

    $mb_dir = substr($mb_id,0,2);

    // ȸ�� ������ ����
    if ($del_mb_icon)
        @unlink("$g4[data_path]/member/$mb_dir/$mb_id.gif");

    // ������ ���ε�
    if (is_uploaded_file($_FILES[mb_icon][tmp_name])) {
        if (!preg_match("/(\.gif)$/i", $_FILES[mb_icon][name])) {
            alert($_FILES[mb_icon][name] . '��(��) gif ������ �ƴմϴ�.');
        }

        if (preg_match("/(\.gif)$/i", $_FILES[mb_icon][name])) {
            @mkdir("$g4[data_path]/member/$mb_dir", 0707);
            @chmod("$g4[data_path]/member/$mb_dir", 0707);

            $dest_path = "$g4[data_path]/member/$mb_dir/$mb_id.gif";

            move_uploaded_file($_FILES[mb_icon][tmp_name], $dest_path);
            chmod($dest_path, 0606);

            if (file_exists($dest_path)) {
                $size = getimagesize($dest_path);
                // �������� �� �Ǵ� ���̰� ������ ���� ũ�ٸ� �̹� ���ε� �� ������ ����
                if ($size[0] > $config[cf_member_icon_width] || $size[1] > $config[cf_member_icon_height]) {
                    @unlink($dest_path);
                }
            }
        }
    }

    if ($mb_password)
        $sql_password = " , mb_password = '".sql_password($mb_password)."' ";
    else
        $sql_password = "";

    if ($passive_certify)
        $sql_certify = " , mb_email_certify = '$g4[time_ymdhis]' ";
    else
        $sql_certify = "";

    $sql = " update $g4[member_table]
                set $sql_common
                    $sql_password
                    $sql_certify
              where mb_id = '$mb_id' ";
    sql_query($sql);
    
    // ȸ�������� ������Ʈ �� ��쿡�� ������ ��¥�� history�� ��� �մϴ�.
    if ($mb[mb_level] !== $mb_level) {
        sql_query(" update $g4[member_table] set mb_level_datetime = '$g4[time_ymdhis]' where mb_id='$mb_id' ");
        sql_query(" insert into $g4[member_level_history_table] set mb_id='$mb_id', from_level='$mb[mb_level]', to_level='$mb_level', level_datetime='$g4[time_ymdhis]' ");
    }

    // �Ҵ��� - �г����� ����Ǹ� history�� ��� �մϴ�.
    if ($mb[mb_nick] != $mb_nick) {
        // ���� ����ϴ� �г����� �ִ��� Ȯ��
        $sql = " select count(*) as cnt from $g4[mb_nick_table] where mb_id = '$mb_id' and mb_nick = '$mb_nick' ";
        $result = sql_fetch($sql);
        if ($result['cnt']) {
            // ������ �г����� �ݾƹ�����, 
            $sql = " update $g4[mb_nick_table] set end_datetime='$g4[time_ymdhis]' where mb_id = '$mb_id' and mb_nick = '$mb[mb_nick] ";
            // ���ο�Ŵ� �����ְ�.
            $sql = " update $g4[mb_nick_table] set start_datetime = '$g4[time_ymdhis]', end_datetime='0000-00-00 00:00:00' where mb_id = '$mb_id' and mb_nick = '$mb_nick' ";
        }
        else
        {
            $sql = " insert $g4[mb_nick_table] set  mb_id = '$mb_id', mb_nick = '$mb_nick', start_datetime = '$g4[time_ymdhis]' ";
            sql_query($sql);
            
            // ������ ���� nickname�� close
            $sql = " update $g4[mb_nick_table] set end_datetime = '$g4[time_ymdhis]' where mb_id = '$mb_id' and mb_nick = '$mb[mb_nick]' ";
            sql_query($sql);
        }
    }
}
else
    alert("����� �� ���� �Ѿ���� �ʾҽ��ϴ�.");

goto_url("./member_form.php?$qstr&w=u&mb_id=$mb_id");
?>
