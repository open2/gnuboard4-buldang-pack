<?
include_once("./_common.php");
include_once("$g4[path]/lib/mailer.lib.php");

if (!$config[cf_email_use])
    alert("ȯ�漳������ \'���Ϲ߼� ���\'�� üũ�ϼž� ������ �߼��� �� �ֽ��ϴ�.\\n\\n�����ڿ��� �����Ͻñ� �ٶ��ϴ�.");

if (!$is_member && $config[cf_formmail_is_member])
    alert_close("ȸ���� �̿��Ͻ� �� �ֽ��ϴ�.");

$to = base64_decode($to);

if (substr_count($to, "@") > 1)
    alert_close('�ѹ��� �ѻ�����Ը� ������ �߼��� �� �ֽ��ϴ�.');

if (chk_recaptcha() == false)
    alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.');

for ($i=1; $i<=$attach; $i++) 
{
    if ($_FILES["file".$i][name])
        $file[] = attach_file($_FILES["file".$i][name], $_FILES["file".$i][tmp_name]);
}

$content = stripslashes($content);
if ($type == 2) 
{
    $type = 1;
    $content = preg_replace("/\n/", "<br>", $content);
} 

// html �̸�
if ($type) 
{
    $current_url = $g4[url];
    $mail_content = "<html><head><meta http-equiv='content-type' content='text/html; charset=$g4[charset]'><title>���Ϻ�����</title><link rel='stylesheet' href='$current_url/style.css' type='text/css'></head><body>$content</body></html>";
} 
else 
    $mail_content = $content;

mailer($fnick, $fmail, $to, $subject, $mail_content, $type, $file);

//$html_title = $tmp_to . "�Բ� ���Ϲ߼�";
$html_title = "���� �߼���";
include_once("$g4[path]/head.sub.php");

alert_close("������ ���������� �߼��Ͽ����ϴ�.");

include_once("$g4[path]/tail.sub.php");
?>
