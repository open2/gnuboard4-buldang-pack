<!-- ��/�Ʒ� �̵��ϴ� jQuery -->
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
    var offset1 = 300;    // �������� ������� �������� ��ư�� ���ñ�?
    var offset2 = 100;    // �������� ������� �������� ��ư�� ���ñ�?
    var duration = 0;     // top���� �̵��Ҷ������� animate �ð� (�и�������, default�� 400. ������ �⺻�� 500)
    var delay1 = 3000;    // ��ư�� ������������� �ð� (3000 = 3��)

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
