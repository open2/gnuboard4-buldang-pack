<?
$sub_menu = "300555";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "w");

check_token();

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.");

for ($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update $g4[singo_reason_table]
                set sg_reason = '{$_POST['sg_reason'][$k]}',
                    sg_print  = '{$_POST['sg_print'][$k]}',
                    sg_use    = '{$_POST['sg_use'][$k]}'
              where sg_id = '{$_POST['chk_sg_id'][$k]}' ";
    sql_query($sql);
}

goto_url("./singo_reason_list.php?$qstr");
?>
