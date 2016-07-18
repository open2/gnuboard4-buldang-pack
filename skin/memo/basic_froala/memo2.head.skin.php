<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<header class="header-wrapper"><!-- 상단 header 시작 -->
<div class="container">

<div class="navbar navbar-default" role="navigation">
<div class="navbar-header" style="margin-top:3px;margin-bottom:3px;">
    <div id="memo_bar" class="pull-left" style="margin-left:5px;">
        <a onclick="javascript:window.close();" class="btn btn-success navbar-toggle" style="margin-left:10px;" id="memo_close">닫기</a>
        <a href="<?=$g4[memo_url]?>?kind=notice" class="btn btn-success navbar-toggle" id="notice">공지</a>
        <a href="<?=$g4[memo_url]?>?kind=save" class="btn btn-success navbar-toggle" id="save">보관</a>
        <a href="<?=$g4[memo_url]?>?kind=send" class="btn btn-success navbar-toggle" id="send"><strong>발신</strong></a>
        <a href="<?=$g4[memo_url]?>?kind=recv" class="btn btn-success navbar-toggle" id="recv"><strong>수신</strong></a>
    </div>
    <button type="button" class="btn btn-success navbar-toggle" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
        <i class="glyphicon glyphicon-list"></i>
    </button>
    <a href="<?=$g4[memo_url]?>?kind=write" class="btn btn-success navbar-toggle" id="write"><strong>쓰기</strong></a>
</div>

<div class="collapse navbar-collapse navbar-top-menu-collapse">
    <ul class="nav navbar-nav hidden-xs">
        <li><a href="<?=$g4[memo_url]?>?kind=recv" id="recv"><strong>수신</strong></a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=send" id="send"><strong>발신</strong></a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=write" id="write"><strong>쓰기</strong></a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=save" id="save">보관</a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=notice" id="notice">공지</a></li>
    </ul>

    <ul class="nav navbar-nav pull-right">
        <li><a href="<?=$g4[memo_url]?>?kind=trash" id="trash">삭제</a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=spam" id="spam">스팸</a></li>
        <li class="divider"></li>
        <li><a href="#">친구</a></li>
        <li><a href="#">그룹</a></li>
        <li><a href="#">주소록</a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=memo_config">설정</a></li>
  </ul>
</div>
</div><!-- navbar 끝 -->

</div>
</header><!-- 상단 header 끝 -->

<script type='text/javascript'>
// 현재 클릭한 버튼을 active로
$('#memo_bar #<?=$kind?>').addClass('active');
</script>

<!-- 메모 메뉴 반전 시키기 -->
<? if ($kind) { ?>
<script type="text/javascript">
$('#gnb #<?=$kind?>').addClass('active');
</script>
<? } ?>

<!-- 중간의 메인부 시작 -->
<div role="main">
