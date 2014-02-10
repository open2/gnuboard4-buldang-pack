<?
$sub_menu = "300555";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

check_token();

//print_r2($_POST);

$tmp_array = array();
if ($sg_id) { 
    // 건별삭제
    $chk[0][0] = 0;
    $_POST[chk][0] = 0;
    $chk_sg_id[0] = $_POST[sg_id];
}

for ($i=0; $i<count($chk); $i++) {
    // 실제 번호를 넘김
    $k = $_POST[chk][$i];

    // 신고 사유를 삭제
    $sql = " delete from $g4[singo_reason_table] where sg_id = '$chk_sg_id[$k]' ";
    sql_query($sql);
    echo $sql;
}

goto_url("singo_reason_list.php?$qstr");
?>
