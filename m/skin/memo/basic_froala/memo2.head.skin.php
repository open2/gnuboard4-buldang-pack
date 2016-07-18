<?
if ( ! defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가
?>
<div id="memo_bar" class="list-btn-bar text-center">
    <div>
        <a href="<?= $g4[memo_url] ?>?kind=recv" class="btn btn-default btn-outline" id="recv"><strong>수신</strong></a>
        <a href="<?= $g4[memo_url] ?>?kind=send" class="btn btn-default btn-outline" id="send"><strong>발신</strong></a>
        <a href="<?= $g4[memo_url] ?>?kind=save" class="btn btn-default btn-outline" id="save">보관</a>
        <a href="<?= $g4[memo_url] ?>?kind=notice" class="btn btn-default btn-outline" id="notice">공지</a>
    </div>
    <div style="margin-top: 5px;">
        <a href="<?= $g4[memo_url] ?>?kind=write" class="btn btn-default btn-outline" id="write"><strong>쓰기</strong></a>
        <a href="<?= $g4[memo_url] ?>?kind=trash" class="btn btn-default btn-outline" id="trash">삭제</a>
        <a href="<?= $g4[memo_url] ?>?kind=spam" class="btn btn-default btn-outline" id="spam">스팸</a>
        <a href="<?= $g4[memo_url] ?>?kind=memo_config" class="btn btn-default btn-outline">설정</a>
    </div>
</div>

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
