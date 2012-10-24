<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once("$g4[path]/head.sub.php");

// 사용자 화면 상단과 좌측을 담당하는 페이지입니다.
// 상단, 좌측 화면을 꾸미려면 이 파일을 수정합니다.

// layout 정의 및 읽기
$g4[layout_path] = $g4[path] . "/layout";                             // layout을 정의 합니다.
$g4[layout_skin] = "naver";                                           // layout skin을 정의 합니다
$g4[layout_skin_path] = $g4[layout_path] . "/" . $g4[layout_skin];    // layout skin path를 정의 합니다.

// top, side 메뉴의 class를 지정
$top_menu = "menu mc_gray";
$side_menu = "menu_v";

// 필요한 레이아웃 파일등을 읽어 들입니다.
include_once("$g4[layout_skin_path]/layout.php");
include_once("$g4[layout_path]/layout.lib.php");
?>

<!-- 웹페이지 전체의 div -->
<div id="wrap">

<div id="header">

    <!-- 전체 navi 영역 -->
    <div class="gnb">
    </div>

    <!-- 상단부 log 같은거 넣어주는 곳 -->
    <div class="sta">
        <!-- 이런 정렬은 table이 div 보다 훨~ 편합니다 -->
        <table width=100%>
            <tr>
                <td align=left>
                <a href="<?=$g4['path']?>/"><img src="<?=$g4[path]?>/images/logo_opencode.gif" align=absmiddle alt=""></a>
                </td>
                <td align=right>
                <?
                include_once("$g4[path]/lib/naver.lib.php");
                ?>
                <div style="width:200px;float:right" id="naver_popular">
                네이버 인기검색어 loading...
                </div>
                <a href="https://github.com/open2/gnuboard4-buldang-pack/commits/master/" target=new><img src="<?=$g4[path]?>/images/github_logo.jpg" align=absmiddle alt=""></a>
                <a href="http://dmshopkorea.com/" target=new><img src="<?=$g4[path]?>/images/dmshop.gif" align=absmiddle alt=""></a>
                <a href="http://onedayro.phps.kr" target=new><img src="<?=$g4[path]?>/images/onedayro.png" align=absmiddle alt=""></a>
                <a href="http://huddak.net" target=new><img src="<?=$g4[path]?>/images/hu.gif" align=absmiddle alt=""></a>
                </td>
            </tr>
        </table>
    </div>

    <div id="menu" class="<?=$top_menu?>">
        <div class="inset">
        <!-- 주메뉴 -->
        <div class="major">
            <?=print_mnb($mnb_arr)?>
        </div>
        <!-- 우측으로 쏠려 있는 메뉴 -->
        <div class="aside">
            <ul>
            <li>
            <!-- 검색창 -->
            <form name="fsearchbox" method="get" onsubmit="return fsearchbox_submit(this);" style="margin:0px;" class="srch" style="border:0">
            <input type="hidden" name="sfl" value="wr_subject||wr_content">
            <input type="hidden" name="sop" value="and">
            <span><input accesskey="s" class="keyword" title=검색어 name="stx" type="text" maxlength="20" value="<?=$stx;?>" > <input alt=검색 src="<?=$g4[layout_skin_path]?>/img/btn_srch.gif" type="image" alt=""></span>
            </form>
            </li>
            </ul>
        </div>
        <span class="gradient"></span>
    </div>
    <span class="shadow"></span>
    </div><!-- 상단 메뉴 div - menu 끝 -->

</div><!-- 상단 div - header 끝 -->

<!-- 중간의 메인부 시작 -->
<div id="container">

<!-- 왼쪽 side div 시작 -->
<div class="snb">

<?
// 아웃로그인
include_once("$g4[path]/lib/outlogin.lib.php");
echo outlogin("transparent");
?>

<!--좌측 메뉴 -->
<table><tr><td height="1px"></td></tr></table>
<div id="menu_v" class="<?=$side_menu?>">
    <?
    switch ($mnb) {
        case "myon"     : print_snb($snb_arr['myon'], 'MyOn'); print_snb($snb_arr['myboard'], '나의 게시판'); print_snb($snb_arr['myvisit'], '내가 방문한 게시판');break;
        case "tips"     :
        case "gnu4_b4"  :
        case "gblog"    :
        case "club2"    :
        case "android"  : // 통상적인 경우에는 아래처럼만 해주면 된다.
        case "mart"     : print_snb($snb_arr[$mnb], mnb_name($mnb_arr, $mnb)); break;
                          // 2번째 메뉴인 yc4_old나 test는 $mnb가 아니므로, 제목을 그냥 지정해준다. 이런식으로 몇개라도 메뉴를 내려 보낼 수 있다.
        case "yc4"      : print_snb($snb_arr[$mnb], mnb_name($mnb_arr, $mnb)); print_snb($snb_arr['yc4_old'], "영카트4(옛날자료)"); break;
        case "gnu4"     :
        case "talk"     : print_snb($snb_arr[$mnb], mnb_name($mnb_arr, $mnb)); print_snb($snb_arr['test'], '테스트');break;
        case "info"     : print_snb($snb_arr[$mnb], 'Info'); break;
        default         : // $mnb가 지정 범위를 벗어나면 내맘대로 출력한다.
                          print_snb($snb_arr['talk'], mnb_name($mnb_arr, 'talk'));
                          ?>
                          <?
                          print_snb($snb_arr['test'], '테스트'); break;
                          break;
    }
    ?>
</div>

<!--//ui object -->

<!-- 로그인박스와의 여백 -->
    <table><tr><td height="1px"></td></tr></table>
    <?
    // 투표
    include_once("$g4[path]/lib/poll.lib.php");
    echo poll();
    ?>
    
    <table><tr><td height="1px"></td></tr></table>
    <?
    // 방문자
    include_once("$g4[path]/lib/visit.lib.php");
    echo visit();
    ?>

    <table><tr><td height="1px"></td></tr></table>
    <?
    include_once("$g4[path]/lib/popular.lib.php");
    echo board_popular("board","", 14, 5);
    ?>

    <table><tr><td height="1px"></td></tr></table>
    <? // 현재접속자
    include_once("$g4[path]/lib/connect.lib.php");
    echo connect();
    ?>

    <table><tr><td height="1px"></td></tr></table>
    <center>
    <a href="http://idc.gabia.com/colo/" target=new><img src="<?=$g4[path]?>/img/banner/gabia.gif" alt=""></a>
    <a href="http://idc.kinx.net/" target=new><img src="<?=$g4[path]?>/img/banner/kinx.gif" alt=""></a>
    <a href="http://worknet.co.kr" target=new><img src="<?=$g4[path]?>/img/banner/worknet.gif" alt=""></a>
    <a href="http://jobnet.co.kr" target=new><img src="<?=$g4[path]?>/img/banner/jobnet.gif" alt=""></a>
    <a href="http://bugsboard.co.kr" target=new><img src="<?=$g4[path]?>/img/banner/bugs4_logo.gif" alt=""></a>
    <a href="http://peoplenjob.com" target=new><img src="<?=$g4[path]?>/img/banner/peoplenjob.gif" alt=""></a>
    </center>

</div>
<!-- 왼쪽 side 메뉴 끝 -->

<!-- 메인 content 메뉴 시작 -->
<div id="content">

<script type="text/javascript">
function fsearchbox_submit(f)
{
    if (f.stx.value.length < 2) {
        alert("검색어는 두글자 이상 입력하십시오.");
        f.stx.select();
        f.stx.focus();
        return false;
    }

    // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
    var cnt = 0;
    for (var i=0; i<f.stx.value.length; i++) {
        if (f.stx.value.charAt(i) == ' ')
            cnt++;
    }

    if (cnt > 1) {
        alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
        f.stx.select();
        f.stx.focus();
        return false;
    }

    f.action = "<?=$g4['bbs_path']?>/search.php";
    return true;
}
</script>

<?
// 줄바꿈 문자를 없앤다
$reg_e = array('/\n/','/\r/','/\"/'); 
$reg_p = array(' ',' ','\'');
?>
<script type="text/javascript">
$("#naver_popular").html( " <? echo preg_replace($reg_e, $reg_p, trim( db_cache("main_top_naver_cache", 300, "naver_popular('naver_popular', 4)")))?> " );
</script>
