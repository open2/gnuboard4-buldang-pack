<!-- 위/아래 이동하는 jQuery -->
<a href="#" class="btn btn-default back-to-top"><span class="glyphicon glyphicon-chevron-up"></span></a>
<a href="#" class="btn btn-default go-to-bottom"><span class="glyphicon glyphicon-chevron-down"></span></a>
<style>
.back-to-top {
    position: fixed;
    bottom: 9em;
    right: 10px;
    padding: 1em;
    display: none;
}
.go-to-bottom {
    position: fixed;
    bottom: 5em;
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
