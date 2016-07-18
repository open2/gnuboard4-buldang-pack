<?
include_once("./_common.php");

// 비회원은 접근할 수 없게 함
if (!$is_member) 
    alert("부적절한 접근 입니다. 관리자에게 문의하시기 바랍니다.");

// 비밀번호 변경주기를 reset 합니다.
// 동일한 코드가 bbs/register_form_update.php에 있습니다.
$next_change = $g4[server_time] + ($config['cf_password_change_dates'] * 24 * 60 * 60);
$next_date = date('Y-m-d h:i:s', $next_change);

$sql = " update $g4[member_table] set mb_password_change_datetime = '$next_date' where mb_id = '$member[mb_id]'";
sql_query($sql);

if ($url)
    goto_url($url);
else
    goto_url("$g4[path]");
?>
