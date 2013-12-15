<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once("$g4[path]/head.sub.php");

// 배너관리 lib
include_once("$g4[path]/lib/banner.lib.php");

// 사용자 화면 상단과 좌측을 담당하는 페이지입니다.
// 상단, 좌측 화면을 꾸미려면 이 파일을 수정합니다.
?>

<?
// 상단부에 alert 팝업을 출력 합니다.
include_once("$g4[path]/lib/popup.lib.php");
echo popup("alert", "popup_alert")
?>

<header class="header-wrapper"><!-- 상단 header 시작 -->
<div class="container">
<div class="row visible-sm visible-md visible-lg">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-5">
    </div>
    <div class="col-sm-5">
        <?
        echo get_banner("top_github", "basic", "github");
        echo get_banner("top", "basic", "", 1);
        ?>
    </div>
</div>

<div class="navbar navbar-default" role="navigation">
<div class="container">
<div class="row">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-sm-2">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-search-top-collapse">
            <i class="glyphicon glyphicon-search"></i>
        </button>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <i class="glyphicon glyphicon-list"></i>
        </button>
        <a class="navbar-brand" href="<?=$g4['path']?>/">
        <img src="<?=$g4[path]?>/images/logo_opencode.gif" align=absmiddle alt="brand logo">
        </a>
    </div>

    <div class="collapse navbar-collapse navbar-ex1-collapse col-sm-7">
    <ul class="nav navbar-nav">
        <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=qna">자유게시판</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">토크 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=notice">공지</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=g4_100">그누보드100일완성</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=g4_books">그누보드참고서</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=sitetips">사이트개발운영</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=biz">비즈니스참고자료</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/good_list.php">베스트글</a></li>
                <li><a href="<?=$g4[bbs_path]?>/new.php">최근게시글</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=test">테스트</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=test2">테스트2</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">개발팁 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=linux_tips">Linux</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=virtual">가상화</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=apache_tips">Apache</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=mysql_tips">MySQL</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=mariadb_tips">Maria DB</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=nosql">NoSQL</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=css">CSS/부트스트랩</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=php_tips">PHP</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=jquery_tips">jQuery</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=javascript_tips">Java Script</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=ajax">AJAX</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=html_tips">HTML</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=html5_tips">HTML5</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=other_tips">기타 팁들</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=cheditor">cheditor(상용)</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">그누4 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_turning">그누보드4 튜닝</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_turning2">그누보드4 튜닝(비공개)</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=memo4">쪽지5</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=thumb">불당썸/Resize</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=layout">불당빌더(100%수동빌더)</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=g4_recycle">휴지통/Recycle</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_unicro">유니크로장터/게시판</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_skin">그누보드스킨</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_tips">그누보드팁</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_qna">그누보드 묻고 답하기</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">App <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=and_talk">안드로이드 게시판</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=and_tip">안드로이드 팁</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=and_pds">안드로이드 자료실</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=webapp">웹앱</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">불당팩 <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack">불당팩다운로드</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_book">불당팩 매뉴얼</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_skin">불당팩 스킨</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_req">불당팩 버그 및 개선</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_qna">불당팩 묻고답하기</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gblog">gblog 불당버젼</a></li>
                <li><a href="<?=$g4[path]?>/blog/" target=new>gblog 테스트</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=club2">클럽2</a></li>
                <li><a href="$g4[path]?>/club/">클럽2 테스트</a></li>
            </ul>
        </li>
        <li><a href="<?=$g4[plugin_path]?>/attendance/attendance.php">출석</a></li>
    </ul>
    </div>

    <div class="col-sm-3 pull-right">
    <form class="navbar-form collapse navbar-collapse navbar-search-top-collapse" role="search" method="get" onsubmit="return fsearchbox_submit(this);" >
    <div class="input-group">
        <input type="hidden" name="sfl" value="wr_subject||wr_content">
        <input type="hidden" name="sop" value="and">
        <input type="text" class="form-control" placeholder="검색어는 2단어까지" name="stx" id="stx" maxlength="20" value="<?=$stx;?>">
        <div class="input-group-btn">
            <button type="submit" class="btn">검색 <i class="glyphicon glyphicon-search"></i></button>
        </div>
    </div>
    </form>
    </div>

</div>

</div>
</div>
</div>
</header><!-- 상단 header 끝 -->


<!-- 중간의 메인부 시작 -->
<div role="main" class="container">
<div class="row">

<!-- 왼쪽 side 시작 -->
<div class="col-sm-2 visible-sm visible-md visible-lg">
<?
// 아웃로그인
include_once("$g4[path]/lib/outlogin.lib.php");
echo outlogin("basic");
?>

<!-- 로그인박스와의 여백 -->
    <table><tr><td height="1px"></td></tr></table>
    <?
    // 투표
    include_once("$g4[path]/lib/poll.lib.php");
    echo poll();
    ?>
    <?
    // 방문자
    include_once("$g4[path]/lib/visit.lib.php");
    echo visit();
    ?>
    <?
    include_once("$g4[path]/lib/popular.lib.php");
    echo board_popular("board","", 14, 5);
    ?>
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

</div><!-- 왼쪽 side 끝 -->

<div class="col-sm-10" id="main"><!-- 메인 content 시작 -->

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