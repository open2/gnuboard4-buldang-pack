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
        $res = "<div>";
        $res .= "<ul class='list-unstyled'>";
        $res .= "<li>쪽지보내기</li>";
        $res .= "<li>메일보내기</li>";
        $res .= "<li>자기소개</li>";
        $res .= "<li>아이디로검색</li>";
        $res .= "<li>포인트내역</li>";
        $res .= "<li>전체게시물</li>";
        $res .= "</ul>";
        $res .= "</div>";

        echo iconv($g4['charset'], "UTF-8", $res);

    }
}
?>
