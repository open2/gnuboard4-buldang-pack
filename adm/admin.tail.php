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

<a href="#" class="btn btn-default back-to-top"><span class="glyphicon glyphicon-chevron-up"></span></a>
<style>
.back-to-top {
    position: fixed;
    bottom: 2em;
    right: 10px;
    padding: 1em;
    display: none;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    var offset = 350;   // 수직으로 어느정도 움직여야 버튼이 나올까?
    var duration = 0;   // top으로 이동할때까지의 animate 시간 (밀리세컨드, default는 400. 예제의 기본은 500)
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('.back-to-top').fadeIn(duration);
        } else {
            $('.back-to-top').fadeOut(duration);
        }
    });
    
    $('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});
</script>

<script type="text/javascript" src="<?=$g4[admin_path]?>/admin.js"></script>

<? 
include_once("$g4[path]/tail.sub.php");
?>
