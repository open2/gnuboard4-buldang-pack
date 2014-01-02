<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<header class="header-wrapper"><!-- 상단 header 시작 -->
<div class="container" style="margin-bottom:5px;">
<div class="btn-group">
  <a href="<?=$g4[memo_url]?>?kind=recv" class="btn btn-default btn-sm" id="recv"><strong>수신</strong></a>
  <a href="<?=$g4[memo_url]?>?kind=send" class="btn btn-default btn-sm" id="send"><strong>발신</strong></a>
</div>
<div class="btn-group">
  <a href="<?=$g4[memo_url]?>?kind=write" class="btn btn-default btn-sm" id="write"><strong>보내기</strong></a>
</div>
<div class="btn-group">
  <a href="<?=$g4[memo_url]?>?kind=save" class="btn btn-default btn-sm" id="save">보관</a>
  <a href="<?=$g4[memo_url]?>?kind=notice" class="btn btn-default btn-sm" id="notice">공지</a>
  <a href="<?=$g4[memo_url]?>?kind=trash" class="btn btn-default btn-sm" id="trash">삭제</a>
  <a href="<?=$g4[memo_url]?>?kind=spam" class="btn btn-default btn-sm" id="spam">스팸</a>
</div>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default btn-sm">친구</button>
  <button type="button" class="btn btn-default btn-sm">그룹</button>
  <button type="button" class="btn btn-default btn-sm">주소록</button>
  <a href="<?=$g4[memo_url]?>?kind=memo_config" class="btn btn-default btn-sm">설정</a>
</div>
</div>
</header><!-- 상단 header 끝 -->

<script type='text/javascript'>
// 현재 클릭한 버튼을 active로
$('.btn-group a#<?=$kind?>').addClass('active');
</script>

<!-- 중간의 메인부 시작 -->
<div role="main">