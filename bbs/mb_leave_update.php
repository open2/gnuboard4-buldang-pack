<?
$g4[title] = "ȸ��Ż��";
include_once("./_common.php");

if (chk_recaptcha() == false)
    alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.');

// �������� setting �մϴ�
$mb_id        = $_POST[mb_id];
$mb_name      = $_POST[mb_name];
$mb_password  = $_POST[mb_password];
$leave_reason = $_POST[leave_reason];

// ȸ�������� ���� �ɴϴ�
$mb = get_member($mb_id, "mb_name, mb_password");
if (!$mb_id || !$mb_name || !$mb_password || $mb_name != $mb[mb_name] || (sql_password($mb_password) !== $mb[mb_password] and sql_old_password($mb_password) !== $mb[mb_password]))
    alert("ȸ�����̵�/��й�ȣ�� Ʋ���ų� �������� ������ �ƴѰ� �����ϴ�.");

// ȸ��Ż���ϰ� Ż������� ����
$date = date("Ymd");
$sql = " update $g4[member_table] set mb_leave_date = '$date', mb_profile = '$leave_reason' where mb_id = '$member[mb_id]'";
sql_query($sql);

// 3.09 ���� (�α׾ƿ�)
unset($_SESSION['ss_mb_id']);

if (!$url) 
    $url = $g4[path]; 

alert("{$member[mb_nick]}�Բ����� " . date("Y�� m�� d��") . "�� ȸ������ Ż�� �ϼ̽��ϴ�.", $url);
?>
