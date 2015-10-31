<?
if (!defined("_GNUBOARD_")) exit;
?>

</div><!-- 메인 content 끝 -->

</div>
</div><!-- 중간의 메인부 끝 -->

<footer class="footer-wrapper" role="contentinfo" style="margin-top:20px;">
<div class="container well">
    <p>실행시간 : <?=get_microtime() - $begin_time;?>
</div>
</footer>

<? include("$g4[path]/lib/moveup.php") ?>

<script type="text/javascript" src="<?=$g4[admin_path]?>/admin.js"></script>

<? 
include_once("$g4[path]/tail.sub.php");
?>
