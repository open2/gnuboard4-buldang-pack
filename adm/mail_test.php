<?
$sub_menu = "200300";
include_once("./_common.php");

if (!$config[cf_email_use])
    alert("ȯ�漳������ \'���Ϲ߼� ���\'�� üũ�ϼž� ������ �߼��� �� �ֽ��ϴ�.");

include_once("$g4[path]/lib/mailer.lib.php");

auth_check($auth[$sub_menu], "w");

check_demo();

$g4[title] = "ȸ������ �׽�Ʈ";

$name = $member[mb_name];
$nick = get_text($member[mb_nick]);
$mb_id = $member[mb_id];
$email = $member[mb_email];
$birth = $member[mb_birth];

$sql = "select ma_subject, ma_content from $g4[mail_table] where ma_id = '$ma_id' ";
$ma = sql_fetch($sql);

$subject = $ma[ma_subject];

$content = $ma[ma_content];
$content = preg_replace("/{�̸�}/", $name, $content);
$content = preg_replace("/{����}/", $nick, $content);
$content = preg_replace("/{ȸ�����̵�}/", $mb_id, $content);
$content = preg_replace("/{�̸���}/", $email, $content);
$content = preg_replace("/{����}/", (int)substr($birth,4,2).'�� '.(int)substr($birth,6,2).'��', $content);

$mb_md5 = md5($member[mb_id].$member[mb_email].$member[mb_datetime]);

$content = $content . "<hr size=0><p><span style='font-size:9pt; font-familye:����'>�� �� �̻� ���� ������ ��ġ �����ø� [<a href='$g4[url]/$g4[bbs]/email_stop.php?mb_id=$mb_id&mb_md5=$mb_md5' target='_blank'>���Űź�</a>] �� �ֽʽÿ�.</span></p>";

mailer($config[cf_title], $member[mb_email], $member[mb_email], $subject, $content, 1);

alert("$member[mb_nick]($member[mb_email])�Բ� �׽�Ʈ ������ �߼��Ͽ����ϴ�.\\n\\nȮ���Ͽ� �ֽʽÿ�.");
?>
