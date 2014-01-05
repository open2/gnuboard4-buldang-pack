<?
if (!defined("_GNUBOARD_")) exit;

$begin_time = get_microtime();

include_once("$g4[path]/head.sub.php");
?>
<body>
<a name='gnuboard4_admin_head'></a>

<header class="header-wrapper"><!-- 상단 header 시작 -->
<div class="container">

<div class="navbar navbar-default" role="navigation">
<div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-sm-2">
        <button type="button" class="btn btn-default navbar-toggle" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
            <i class="glyphicon glyphicon-list"></i>
        </button>
        <a class="navbar-brand hidden-xs" href="<?=$g4['admin_path']?>/">
        <img src="<?=$g4[path]?>/images/logo_opencode.gif" align=absmiddle alt="brand logo">
        </a>
        <a class="navbar-brand navbar-toggle pull-left" href="<?=$g4['path']?>/" style="border:0;margin-bottom:0;">
        <img src="<?=$g4[path]?>/images/logo_opencode.gif" alt="brand logo" style="width:120px;">
        </a>
    </div>
    <div class="pull-right">
        <div class="btn-toolbar">
            <div class="btn-group">
                <a data-placement="bottom" data-original-title="E-mail" data-toggle="tooltip" class="btn btn-default btn-sm">
                  <i class="fa fa-envelope"></i>
                  <span class="label label-warning">5</span>
                </a>
                <a data-placement="bottom" data-original-title="Messages" href="#" data-toggle="tooltip" class="btn btn-default btn-sm">
                  <i class="fa fa-comments"></i>
                  <span class="label label-danger">4</span>
                </a>
            </div>
              <div class="btn-group">
                <a data-placement="bottom" data-original-title="Document" href="#" data-toggle="tooltip" class="btn btn-default btn-sm">
                  <i class="fa fa-file"></i>
                </a>
                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#helpModal">
                  <i class="fa fa-question"></i>
                </a>
              </div>
              <div class="btn-group">
                <a href="<?=$g4[bbs_path]?>/logout.php" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom" class="btn btn-default btn-sm">
                  <i class="fa fa-power-off"></i>
                </a>
              </div>
            </div>
          </div><!-- /.topnav -->
          
    <div class="collapse navbar-collapse navbar-top-menu-collapse col-sm-7">
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
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_book">불당팩 매뉴얼</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_skin">불당팩 스킨</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_req">불당팩 버그 및 개선</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_qna">불당팩 묻고답하기</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=yc4_pack_download">영카트4s 불당팩</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=yc4_tips">영카트4s 팁</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=yc4_pack_qna">영카트4s 묻고답하기</a></li>
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
</div>








</div>
</header>

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