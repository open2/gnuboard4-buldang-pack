<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div><!-- 메인 content 끝 -->

</div>
</div><!-- 중간의 메인부 끝 -->

<a href="#" class="btn btn-default back-to-top"><span class="glyphicon glyphicon-chevron-up"></span></a>
<a href="#" class="btn btn-default go-to-bottom"><span class="glyphicon glyphicon-chevron-down"></span></a>
<style>
.back-to-top {
    position: fixed;
    bottom: 6em;
    right: 10px;
    padding: 1em;
    display: none;
}
.go-to-bottom {
    position: fixed;
    bottom: 2em;
    right: 10px;
    padding: 1em;
    display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
    var offset1 = 300;    // 수직으로 어느정도 움직여야 버튼이 나올까?
    var offset2 = 100;    // 수직으로 어느정도 움직여야 버튼이 나올까?
    var duration = 0;     // top으로 이동할때까지의 animate 시간 (밀리세컨드, default는 400. 예제의 기본은 500)
    var delay1 = 3000;    // 버튼이 사라질때까지의 시간 (3000 = 3초)

    var timer;
    $(window).bind('scroll',function () {
        clearTimeout(timer);
        timer = setTimeout( refresh , 150 );
    });
    var refresh = function () { 
        if ($(this).scrollTop() > offset2) {
            $('.go-to-bottom').fadeIn(duration);
            setTimeout(function(){$('.go-to-bottom').hide();},2000);
        } else {
            $('.go-to-bottom').fadeOut(duration);
        }

        if ($(this).scrollTop() > offset1) {
            $('.back-to-top').fadeIn(duration);
            setTimeout(function(){$('.back-to-top').hide();},2000);
        } else {
            $('.back-to-top').fadeOut(duration);
        }
    };

    $('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
    $('.go-to-bottom').click(function(event) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: $(document).height() }, duration);
        return false;
    })
});
</script>

<!-- 페이지 하단부 footer -->
<footer class="footer-wrapper col-sm-offset-2" role="contentinfo" style="margin-top:20px;">
<div class="container" id="footer">
    <div class="panel panel-default hidden-sm hidden-md hidden-lg">
    <div class="panel-heading">
        <div>
        <a class="btn btn-default" data-toggle="collapse" data-target=".navbar-bottom-collapse">Info.</a>
        <div class="btn-group">
        <? if ($member['mb_id']) { ?>
            <a class="btn btn-default visible-xs" href="<?=$g4['bbs_path']?>/logout.php">Logout</a>
        <? } else { 
            $login_url = "$g4[bbs_path]/login.php?$qstr";
        ?>
            <a class="btn btn-default visible-xs" href="<?=$login_url?>">Login</a>
        <? } ?>
        </div>

        <a href="<?=$g4[path]?>/company/company.php?id=privacy"><strong>개인정보취급방침</strong></a>
        <small>(주)오픈코드</small>

        </div>
    </div>
    </div>

    <div class="collapse navbar-collapse navbar-bottom-collapse">
        <ul class="list-inline">
        <li><a href="<?=$g4[path]?>/company/company.php?id=privacy"><strong>개인정보취급방침</strong></a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=service">이용약관</a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=disclaimer">책임한계와법적고지</a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=rejection">이메일주소무단수집거부</a></li>
        </ul>
    <p>(주)오픈코드 사업자등록번호 :000-00-00000 통신판매업신고번호 :제2009-서울서초-0000호<br>
       대표이사 :아빠불당 주소 :서울시 서초구 서초동 먼산 전화 :00-000-0000</p>
    <p><?=$config[cf_title]?>는 지식중개자로서 지식의 주문, 배송 및 환불의 의무와 책임은 각 회원에 있습니다.<br>
    <?=$config[cf_title]?>의 사전 서면 동의 없이 <?=$config[cf_title]?>의 일체의 정보, 콘텐츠 및 UI등을 상업적 목적으로 전재, 전송, 스크래핑 등 무단 사용할 수 없습니다.<br>
    Copyright &copy; <a href="http://opencode.co.kr" target="_blank">Opencode.co.kr</a>. All rights reserved.</p>
    </div>
</div>
</footer>
<?
include_once("$g4[path]/tail.sub.php");
?>