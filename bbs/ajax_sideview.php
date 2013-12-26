<?
include_once("_common.php");

if (trim($mb_id)=='undefined') {
    echo "Error: 110"; // 입력이 없습니다.
} else {
    $mb = get_member($mb_id);
    if ($mb[mb_id] == "") {
        echo "Error: 130"; // 없는 아이디
    } else {
        // 결과값을 return
        $res = "쪽지보내기";
        echo iconv($g4['charset'], "UTF-8", $res);

    }
}
?>
