<?
include_once("_common.php");

if (strtolower($g4[charset]) == 'euc-kr') 
{
    $_POST['mb_nick'] = iconv("UTF-8", $g4['charset'], $_POST['mb_nick']);
    $_POST['mb_nick2'] = iconv("UTF-8", $g4['charset'], $_POST['mb_nick2']);
    $_POST['sca'] = iconv("UTF-8", $g4['charset'], $_POST['sca']);
}

$mb_nick = trim(get_text($_POST['mb_nick']));    // sideview의 대상
$mb_nick2 = trim(get_text($_POST['mb_nick2']));  // sideview를 클릭한 사람
$bo_table = trim($_POST['bo_table']);
$sca = trim($_POST['sca']);

// 비회원의 sideview는 그냥 return
if ($mb_nick == "[비회원]") {
    $res = "<div>";
    $res .= "<ul class='list-unstyled'>";
    $res .= "<li>비회원입니다</li>";
    $res .= "</ul>";
    $res .= "</div>";
    echo iconv($g4['charset'], "UTF-8", $res);
    exit;
}

// 비회원의 sideview 요청도 그냥 return
if ($member[mb_id] == "") {
    $res = "<div>";
    $res .= "<ul class='list-unstyled'>";
    $res .= "<li>회원서비스입니다.<br>로그인 하신후 이용해 주시기 바랍니다.</li>";
    $res .= "</ul>";
    $res .= "</div>";
    echo iconv($g4['charset'], "UTF-8", $res);
    exit;
}

$mb = get_member_nick($mb_nick);
$mb2 = get_member_nick($mb_nick2);

$mb_id = $mb['mb_id'];

// 출력할 변수를 앞에서 초기화...
$res = "";

if ($mb_id =='undefined' || $mb_id == "") {
    echo "Error: 110"; // 입력이 없습니다.
} else {
    $mb = get_member($mb_id);
    if ($mb[mb_id] == "") {
        echo "Error: 130"; // 없는 아이디
    } else {

        // mb_nick의 갯수를 세어 봅니다. mb_nick이 여러개인 경우가 있더라구요.
        $sql = "select count(*) as cnt from $g4[member_table] where mb_nick = '$mb_nick' ";
        $result = sql_fetch($sql);
        if ($result['cnt'] > 1) {
            $res = "<div>[Error] 닉네임 중복. 관리자에게 문의 하시기 바랍니다</div>";
            echo iconv($g4['charset'], "UTF-8", $res);
            die;
        }

        $memo_url = "$g4[bbs_path]/memo.php?kind=write&me_recv_mb_id=$mb_id";
        $point_url = "$g4[admin_path]/point_list.php?sfl=mb_id&stx=$mb_id";
        $bo_url = "$g4[bbs_path]/board.php?bo_table=$bo_table&sca=$sca&sfl=mb_id,1&stx=$mb_id";
        $new_url = "$g4[bbs_path]/new.php?mb_id=$mb_id";
        $member_modify_url = "$g4[admin_path]//member_form.php?w=u&mb_id=$mb_id";

        // 결과값을 return
        $res .= "<div>";
        $res .= "<ul class='list-unstyled'>";
        $res .= "<li><a href=\"javascript:;\" onClick=\"win_memo('$memo_url', '$mb_id');\">쪽지보내기</a></li>";
        $res .= "<li><a href=\"javascript:;\" onClick=\"win_formmail('$mb_id','$mb_nick');\">메일보내기</a></li>";
        $res .= "<li><a href=\"javascript:;\" onClick=\"win_profile('$mb_id');\">자기소개</a></li>";
        if ($mb[mb_homepage])
            $res .= "<li><a href=\"javascript:;\" onClick=\"window.open('$mb[mb_homepage]');\">홈페이지</a></li>";
        if ($bo_table)
            $res .= "<li><a href=\"$bo_url\" target=\"_blank\">아이디로검색</a></li>";
        if ($member[mb_id] == $mb_id)
            $res .= "<li><a href=\"javascript:;\" onClick=\"win_point('');;\">포인트내역</a></li>";
        $res .= "<li><a href=\"$new_url\" target=\"_blank\">전체게시물</a></li>";
        if ($is_admin == "super") {
            $res .= "<li><a href=\"$point_url\" target=\"_blank\">*포인트내역*</a></li>";
            $res .= "<li><a href=\"$member_modify_url\" target=\"_blank\">*회원정보변경*</a></li>";
        }
        $res .= "</ul>";
        $res .= "</div>";

        echo iconv($g4['charset'], "UTF-8", $res);
    }
}
?>
