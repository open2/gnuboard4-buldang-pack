<?
include_once("./_common.php");

// �ҹ������� ������ ��ū����
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

if (!$config[cf_email_use])
    alert("ȯ�漳������ \'���Ϲ߼� ���\'�� üũ�ϼž� ������ �߼��� �� �ֽ��ϴ�.\\n\\n�����ڿ��� �����Ͻñ� �ٶ��ϴ�.");

if (!$is_member && $config[cf_formmail_is_member])  
    alert_close("ȸ���� �̿��Ͻ� �� �ֽ��ϴ�.");

if ($is_member && !$member[mb_open] && $is_admin != "super" && $member[mb_id] != $mb_id) 
    alert_close("�ڽ��� ������ �������� ������ �ٸ��п��� ������ ���� �� �����ϴ�.\\n\\n�������� ������ ȸ�������������� �Ͻ� �� �ֽ��ϴ�.");

if ($mb_id) 
{
    $mb = get_member($mb_id);

    // �Ҵ��� - �����ּ� ��ȣ
    if (!$email)
        $email = base64_encode($mb[mb_email]);

    if (!$mb[mb_id]) 
        alert_close("ȸ�������� �������� �ʽ��ϴ�.\\n\\nŻ���Ͽ��� �� �ֽ��ϴ�.");

    if (!$mb[mb_open] && $is_admin != "super")
        alert_close("���������� ���� �ʾҽ��ϴ�.");
}

$sendmail_count = (int)get_session('ss_sendmail_count') + 1;
if ($sendmail_count > 3)
    alert_close('�ѹ� ������ �������� ���ϸ� �߼��� �� �ֽ��ϴ�.\n\n����ؼ� ������ �����÷��� �ٽ� �α��� �Ǵ� �����Ͽ� �ֽʽÿ�.');

$g4[title] = "���� ����";
include_once("$g4[path]/head.sub.php");

if (!$name)
    $name = base64_decode($email);
else  
    $name = get_text(stripslashes($name), true);  

if (!isset($type)) 
    $type = 0;

$type_checked[0] = $type_checked[1] = $type_checked[2] = "";
$type_checked[$type] = "checked";

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";
include_once("$member_skin_path/formmail.skin.php");

include_once("$g4[path]/tail.sub.php");
?>
