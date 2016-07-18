<?
include_once("_common.php");

include_once("$g4[path]/head.php");

if (!$is_member)
    alert_close("로그인 후 이용하실 수 있습니다.");

$num = 1;
$qry = sql_query("select m.bo_table, b.bo_subject from $g4[my_menu_table] as m left join $g4[board_table] as b on b.bo_table = m.bo_table where mb_id = '$member[mb_id]'");

$list = array();
while ($row = sql_fetch_array($qry)) {
    $list[] = $row;
}

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";

include_once("$member_skin_path/my_menu.skin.php");

include_once("$g4[path]/tail.php");
?>
