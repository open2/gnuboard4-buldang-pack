<?
include_once("_common.php");

if (trim($mb_id)=='') {
    echo "110"; // 입력이 없습니다.
} else {
    $mb = get_member($mb_id);
    if ($row[mb_id] == "") {
        echo "130"; // 없는 아이디
    } else {
        // 결과값을 return
    }
}
?>
