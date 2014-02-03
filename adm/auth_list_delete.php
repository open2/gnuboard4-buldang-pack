<?
$sub_menu = "100110";
include_once("./_common.php");

check_demo();

check_token();

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.");

for ($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " delete from $g4[auth_table] where mb_id = '{$_POST['mb_id'][$k]}' and au_menu = '{$_POST['au_menu'][$k]}' ";
    sql_query($sql);

    // 불당팩 - 관리자 권한변경 작업내역을 db log에 남깁니다
    $sql = " insert into $g4[admin_log_table] 
                set log_datetime = '$g4[time_ymdhis]',
                    log = '" . mysql_real_escape_string($sql) . "' ";
    sql_query($sql);

    //불당 mb_auth_count를 업데이트
    $sql = " select count(*) as cnt from $g4[auth_table] where mb_id = '$mb_id[$k]' ";
    $result = sql_fetch($sql);
    $sql = " update $g4[member_table] set mb_auth_count = '$result[cnt]' where mb_id = '$mb_id[$k]' ";
    sql_query($sql);
}

goto_url("./auth_list.php?$qstr");
?>
