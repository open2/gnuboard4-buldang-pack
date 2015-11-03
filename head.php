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
echo popup("alert", "popup_alert");

// 회원인 경우 왓쑝~을 읽어서, 안읽은 갯수를 계산해 둡니다.
// 왓쑝을 안쓰는 경우에는 그냥 그런거라 선택 가능하게 head.php에 넣습니다.
if ($member[mb_id]) {
    include_once("$g4[path]/lib/whatson.lib.php");
    $g4['whatson_unread'] = whatson_count($member[mb_id]);
}

// 버튼에 알람을??? btn-info-navbar class는 style.css에 있습니다.
if ($member['mb_memo_unread'] > 0)
    $memo_btn = "btn-info btn-info-navbar";
else
    $memo_btn = "btn-default";
if ($g4['whatson_unread'] > 0)
    $whatson_btn = "btn-info btn-info-navbar";
else
    $whatson_btn = "btn-default";
?>

<header class="header-wrapper"><!-- 상단 header 시작 -->
<div class="container">

<div class="row">
<div class="hidden-xs hidden-sm col-md-2 col-lg-2">
<?
        echo get_banner("top_github", "basic", "github");
        echo get_banner("top", "basic", "", 1);
?>
</div>

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
구글광고...
</div>

</div>

<div class="navbar navbar-default" role="navigation" style="margin-top:3px;margin-bottom:3px;">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-md-2 col-lg-2">

        <? if ($member['mb_id']) { ?>
        <!-- collapse 되었을 때, 우측에 나오는 개인메뉴 버튼 -->
        <button type="button" class="btn btn-default navbar-toggle pull-right" data-toggle="collapse" data-target=".navbar-top-menu-collapse_my" style="border:none">
            <i class="glyphicon glyphicon-check"></i>
        </button>
        <? } ?>

        <!-- collapse 되었을 때, 우측에 나오는 메뉴 버튼 -->
        <button type="button" class="btn btn-default navbar-toggle pull-right" data-toggle="collapse" data-target=".navbar-top-menu-collapse" style="border:none">
            <i class="glyphicon glyphicon-list"></i>
        </button>

        <? if ($member['mb_id'] == "") { 
        $login_url = "$g4[bbs_path]/login.php?url=".urlencode($lo_url);
        ?>
        <a class="btn btn-default navbar-toggle" value="Page" onclick="location.href='<?=$login_url?>';" style="border:none">
            <i class="glyphicon glyphicon-user"></i>
        </a>
        <? } else {
        $login_url = "$g4[bbs_path]/myon.php?head=1";
        ?>
        <a class="btn <?=$whatson_btn?> navbar-toggle" value="Page" onclick="location.href='<?=$login_url?>';" style="border:none">
            <i class="glyphicon glyphicon-shopping-cart"><sup style="margin-left:3px;"><?=$g4['whatson_unread']?></sup></i>
        </a>
        <a class="btn <?=$memo_btn?> navbar-toggle" value="Page" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" style="border:none">
            <i class="glyphicon glyphicon-envelope"><sup style="margin-left:3px;"><?=$member[mb_memo_unread]?></sup></i>
        </a>
        <? } ?>
        <!-- sm, md, lg에서 나오는 로고 -->
        <a class="navbar-brand hidden-xs hidden-sm" href="<?=$g4['path']?>/" style="border:none">
        <img src="<?=$g4[path]?>/images/opencode_aaa.png" align=absmiddle alt="brand logo" style="height:38px;border:none;margin-top:-10px;">
        </a>
        <!-- collapse 되었을 때 나오는 로고 -->
        <a class="navbar-brand navbar-toggle pull-left" href="<?=$g4['path']?>/" style="margin-bottom:0;border:none;">
        <img src="<?=$g4[path]?>/images/opencode_aaa.png" alt="brand logo" style="height:30px">
        </a>

        <!-- navbar toggle이 아니므로, 2번 넣어줘야 합니다 -->
        <button type="button" class="btn btn-default navbar-toggle visible-sm visible-xs" data-toggle="collapse" data-target="#top_search" style="border:none;float:right">
            <i class="glyphicon glyphicon-search"></i>
        </button>
    </div>

    <?
    $my_menu = array();
    $sql = "select m.bo_table, b.bo_subject from $g4[my_menu_table] as m left join $g4[board_table] as b on m.bo_table = b.bo_table where mb_id = '$member[mb_id]'";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
        $my_menu[] = $row;
    }
    ?>

    <div class="collapse navbar-collapse navbar-top-menu-collapse_my pull-right">

        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#top_search" style="border:0px;float:right; background:none;">
            <i class="glyphicon glyphicon-search"></i>
        </button>

    <ul class="nav navbar-nav hidden-lg hidden-md" id="gnb_my">
        <? for ($i=0; $i<count($my_menu); $i++) { ?>
            <li id="qna_my"><a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$my_menu[$i][bo_table]?>"><?=$my_menu[$i][bo_subject]?></a></li>
        <? } ?>
        <li id="qna_my"><a href="<?=$g4[bbs_path]?>/my_menu_edit.php">바로가기편집</a></li>
    </ul>
    </div>

    <div class="collapse navbar-collapse navbar-top-menu-collapse col-sm-9 col-md-7 col-lg-7">
    <ul class="nav navbar-nav" id="gnb">
        <li id="qna"><a href="<?=$g4[path]?>/qna">자유게시판</a></li>
        <li id="test"><a href="<?=$g4[path]?>/test">한줄이야기</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">토크 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li id="notice"><a href="<?=$g4[path]?>/notice">공지</a></li>
                <li class="divider"></li>
                <li id="g4_100"><a href="<?=$g4[path]?>/g4_100">그누보드100일완성</a></li>
                <li id="g4_books"><a href="<?=$g4[path]?>/g4_books">그누보드참고서</a></li>
                <li id="sitetips"><a href="<?=$g4[path]?>/sitetips">사이트개발운영</a></li>
                <li id="biz"><a href="<?=$g4[path]?>/biz">비즈니스참고자료</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/good_list.php">베스트글</a></li>
                <li><a href="<?=$g4[bbs_path]?>/new.php">최근게시글</a></li>
                <li class="divider"></li>
                <li id="test"><a href="<?=$g4[path]?>/test">테스트</a></li>
                <li id="test2"><a href="<?=$g4[path]?>/test2">테스트2</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">개발팁 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[path]?>/linux_tips">Linux</a></li>
                <li><a href="<?=$g4[path]?>/virtual">가상화</a></li>
                <li><a href="<?=$g4[path]?>/apache_tips">Apache</a></li>
                <li><a href="<?=$g4[path]?>/mysql_tips">MySQL</a></li>
                <li><a href="<?=$g4[path]?>/mariadb_tips">Maria DB</a></li>
                <li><a href="<?=$g4[path]?>/nosql">NoSQL</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/css">CSS/부트스트랩</a></li>
                <li><a href="<?=$g4[path]?>/php_tips">PHP</a></li>
                <li><a href="<?=$g4[path]?>/jquery_tips">jQuery</a></li>
                <li><a href="<?=$g4[path]?>/javascript_tips">Java Script</a></li>
                <li><a href="<?=$g4[path]?>/ajax">AJAX</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/html_tips">HTML</a></li>
                <li><a href="<?=$g4[path]?>/html5_tips">HTML5</a></li>
                <li><a href="<?=$g4[path]?>/other_tips">기타 팁들</a></li>
                <li><a href="<?=$g4[path]?>/cheditor">cheditor(상용)</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">그누4 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[path]?>/gnu4_turning">그누보드4 튜닝</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_turning2">그누보드4 튜닝(비공개)</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/memo4">쪽지5</a></li>
                <li><a href="<?=$g4[path]?>/thumb">불당썸/Resize</a></li>
                <li><a href="<?=$g4[path]?>/layout">불당빌더(100%수동빌더)</a></li>
                <li><a href="<?=$g4[path]?>/g4_recycle">휴지통/Recycle</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_unicro">유니크로장터/게시판</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/gnu4_skin">그누보드스킨</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_tips">그누보드팁</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_qna">그누보드 묻고 답하기</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">App <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[path]?>/and_talk">안드로이드 게시판</a></li>
                <li><a href="<?=$g4[path]?>/and_tip">안드로이드 팁</a></li>
                <li><a href="<?=$g4[path]?>/and_pds">안드로이드 자료실</a></li>
                <li><a href="<?=$g4[path]?>/webapp">웹앱</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">불당팩 <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[path]?>/gnu4_pack">불당팩다운로드</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_book">불당팩 매뉴얼</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_skin">불당팩 스킨</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_req">불당팩 버그 및 개선</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_qna">불당팩 묻고답하기</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/gblog">gblog 불당버젼</a></li>
                <li><a href="<?=$g4[path]?>/blog/" target=_blank>gblog 테스트</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/club2">클럽2</a></li>
                <li><a href="$g4[path]?>/club/">클럽2 테스트</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">영카트5 <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[path]?>/yc4_pack_download">영카트5 불당팩</a></li>
                <li><a href="<?=$g4[path]?>/yc4_tips">영카트5 팁</a></li>
                <li><a href="<?=$g4[path]?>/yc4_pack_qna">영카트5 묻고답하기</a></li>
                <li><a href="<?=$g4[path]?>/shop/index.php">영카트5 테스트</a></li>
            </ul>
        </li>
        <li><a href="<?=$g4[plugin_path]?>/attendance/attendance.php">출석</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle hidden-xs hidden-sm" href="#" data-toggle="dropdown">바로가기 <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <? for ($i=0; $i<count($my_menu); $i++) { ?>
                    <li id="my_$i_m"><a href="<?=$g4[path]?>/<?=$my_menu[$i][bo_table]?>"><?=$my_menu[$i][bo_subject]?></a></li>
                <? } ?>
                <li class="divider"></li>
                <li id="qna_my"><a href="<?=$g4[bbs_path]?>/my_menu_edit.php">바로가기편집</a></li>
            </ul>
        </li>
    </ul>
    </div>

    <div class="col-sm-3 col-md-3 col-lg-3 pull-right collapse" id="top_search">
    <form role="search" method="get" onsubmit="return fsearchbox_submit(this);">
    <input type="hidden" name="sfl" value="wr_subject||wr_content">
    <input type="hidden" name="sop" value="and">
    <div class="input-group" id="search-bar">
        <input type="text" class="form-control pull-right" placeholder="검색어는 2단어까지" name="stx" id="stx" maxlength="20" value="<?=$stx;?>">
        <label for="stx" class="sr-only">search</label>
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit" >검색 <i class="glyphicon glyphicon-search"></i></button>
        </span>
    </div><!-- /input-group -->
    </form>
    </div>

</div><!-- navbar의 끝 -->

</div>
</header><!-- 상단 header 끝 -->


<!-- 중간의 메인부 시작 -->
<div role="main" class="container">
<div class="row">

<!-- 왼쪽 side 시작 -->
<div class="hidden-xs hidden-sm col-md-2 col-lg-2">
<?
// 아웃로그인
include_once("$g4[path]/lib/outlogin.lib.php");
echo outlogin("basic");
?>

<div style="overflow:hidden">
구글광고...
</div>

<!-- 로그인박스와의 여백 -->
    <table><tr><td height="1px"></td></tr></table>
    <?
    if ($member[mb_id]) {
        echo whatson("basic", 10, 14);
    }
    ?>
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
    <a href="http://idc.gabia.com/colo/" target=_blank><img src="<?=$g4[path]?>/img/banner/gabia.gif" alt=""></a>
    <a href="http://idc.kinx.net/" target=_blank><img src="<?=$g4[path]?>/img/banner/kinx.gif" alt=""></a>
    <a href="http://worknet.co.kr" target=_blank><img src="<?=$g4[path]?>/img/banner/worknet.gif" alt=""></a>
    <a href="http://jobnet.co.kr" target=_blank><img src="<?=$g4[path]?>/img/banner/jobnet.gif" alt=""></a>
    <a href="http://bugsboard.co.kr" target=_blank><img src="<?=$g4[path]?>/img/banner/bugs4_logo.gif" alt=""></a>
    <a href="http://peoplenjob.com" target=_blank><img src="<?=$g4[path]?>/img/banner/peoplenjob.gif" alt=""></a>
    </center>

</div><!-- 왼쪽 side 끝 -->

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" id="main_content"><!-- 메인 content 시작 -->

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

<!-- 게시판 메뉴 반전 시키기 -->
<? if ($board['bo_table']) { ?>
<script type="text/javascript">
$('#gnb #<?=$board[bo_table]?>').addClass('active');
</script>
<? } ?>