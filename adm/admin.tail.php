<?
if (!defined("_GNUBOARD_")) exit;
?>

</div><!-- ���� content �� -->

</div>
</div><!-- �߰��� ���κ� �� -->

<footer class="footer-wrapper" role="contentinfo" style="margin-top:20px;">
<div class="container well">
    <p>����ð� : <?=get_microtime() - $begin_time;?>
</div>
</footer>

<? include("$g4[path]/lib/moveup.php") ?>

<script type="text/javascript" src="<?=$g4[admin_path]?>/admin.js"></script>

<? 
include_once("$g4[path]/tail.sub.php");
?>
