<?
include_once("./_common.php");

if (!$member[mb_id]) 
    alert_close("ȸ���� �̿��Ͻ� �� �ֽ��ϴ�.");

if (!$member[mb_open] && $is_admin != "super" && $member[mb_id] != $mb_id) 
    alert_close("�ڽ��� ������ �������� ������ �ٸ����� ������ ��ȸ�� �� �����ϴ�.\\n\\n�������� ������ ȸ�������������� �Ͻ� �� �ֽ��ϴ�.");

//$mb = get_member($mb_id);
$mb = get_member($mb_id, "mb_id, mb_level, mb_point, mb_homepage, mb_open, mb_nick, mb_datetime, mb_today_login, mb_profile, mb_memo, mb_signature");

if (!$mb['mb_id'])
    alert_close("ȸ�������� �������� �ʽ��ϴ�.\\n\\nŻ���Ͽ��� �� �ֽ��ϴ�.");

// ����� ȸ���� ������ �����ֱ� �ʱ�
if (!$mb['mb_open'] && $is_admin != "super" && $member['mb_id'] != $mb_id) {
    //$mb['mb_level'] = "";
    //$mb['mb_point'] = "";
    $mb['mb_profile'] = "";
    $mb['mb_homepage'] = "";
    $mb['mb_datetime'] = "";
    $mb['mb_today_login'] = "";
}

// ��õ ������ �����ֱ� (�׻�...)
$mb['mb_good'] = 0;
$sql = " select count(*) as cnt from $g4[board_good_table] where wr_mb_id = '$mb_id' and bg_flag = 'good' ";
$result = sql_fetch($sql);
if ($result['cnt'] > 0)
    $mb['mb_good'] = $result['cnt'];

// ����õ ������ �����ֱ� (���ΰ� �����ڸ�...)
$mb['mb_nogood'] = 0;
if ($is_member && ( $member['mb_id'] == '$mb_id' || $is_admin == 'super')) {
    $sql = " select count(*) as cnt from $g4[board_good_table] where wr_mb_id = '$mb_id' and bg_flag = 'nogood' ";
    $result = sql_fetch($sql);
    if ($result['cnt'] > 0)
        $mb['mb_nogood'] = $result['cnt'];
}

$g4[title] = $mb[mb_nick] . "���� �ڱ�Ұ�";
include_once("$g4[path]/head.sub.php");

$mb_nick = get_sideview($mb[mb_id], get_text($mb[mb_nick]), $mb[mb_email], $mb[mb_homepage], $mb[mb_open]);

// ȸ�������� ����°����? + 1 �� ������ �����Ѵٴ� ��
$sql = " select (TO_DAYS('$g4[time_ymdhis]') - TO_DAYS('$mb[mb_datetime]') + 1) as days ";
$row = sql_fetch($sql);
$mb_reg_after = $row[days];

$mb_homepage = set_http(get_text(clean_xss_tags($mb['mb_homepage']))); 
$mb_profile = $mb[mb_profile] ? conv_content($mb[mb_profile],1) : "�Ұ� ������ �����ϴ�.";

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";
include_once("$member_skin_path/profile.skin.php");

include_once("$g4[path]/tail.sub.php");
?>
