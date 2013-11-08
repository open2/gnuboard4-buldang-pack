<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="panel panel-default">
  <div class="panel-body">
    <a href='<?=$g4['bbs_path']?>/current_connect.php'>현재접속자 : <?=$row['total_cnt']?> (회원 <?=$row['mb_cnt']?>)</a>
  </div>
</div>