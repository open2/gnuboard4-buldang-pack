<?
$g4[title] = "ȸ��Ż��";
include_once("./_common.php");

// �������� setting �մϴ�
$mb_id        = strip_tags($_POST[mb_id]);
$mb_password  = strip_tags($_POST[mb_password]);

if (chk_recaptcha() == false)
    alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.');

if (!$member['mb_id'])
    alert('ȸ���� �����Ͻ� �� �ֽ��ϴ�.');

if ($is_admin == 'super')
    alert('�ְ� �����ڴ� Ż���� �� �����ϴ�');

if ($mb_id !== $member['mb_id'])
    alert("�������� ������ �ƴѰ� �����ϴ�.");

if (!$mb_password)
    alert("�������� ������ �ƴѰ� �����ϴ� (2).");

if (sql_password($mb_password) !== $member['mb_password'])
    alert("$mb_password ��й�ȣ�� Ʋ���ų� �������� ������ �ƴѰ� �����ϴ�.");

// ȸ��Ż������ ����
$date = date("Ymd");
$sql = " update $g4[member_table] set mb_leave_date = '$date' where mb_id = '$member[mb_id]'";
sql_query($sql);

// 3.09 ���� (�α׾ƿ�)
unset($_SESSION['ss_mb_id']);

if (!$url) 
    $url = $g4[path]; 

alert("{$member[mb_nick]}�Բ����� " . date("Y�� m�� d��") . "�� ȸ������ Ż�� �ϼ̽��ϴ�.", $url);
?>
