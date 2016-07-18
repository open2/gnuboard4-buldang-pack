<?php
if ( ! defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가

// 게시판에서 중복 include 예방
if (defined('_G4_TAIL')) {
    return;
} else {
    define('_G4_TAIL', 1);
}
?>
<?php if (is_popover()) { ?>

<?php } else { ?>
    </div>
    <div>
        <ul class="list-inline text-center list-horizontal-border">
            <li><a href="/company/company.php?id=privacy">개인정보취급방침</a></li>
            <li><a href="/company/company.php?id=service">이용약관</a></li>
            <li><a href="/company/company.php?id=disclaimer">책임한계</a></li>
        </ul>
        <div style="padding: 20px;">
            <div class="pull-left"><i class="material-icons">&#xE90C;</i> 2CPU</div>
            <div class="pull-right"><a href="#top" class="btn btn-black">TOP</a></div>
            <div class="clearfix"></div>
        </div>
    </div>
<?php } ?>


<div id="wrap-loading">
    <div><img src="/m/img/loading.gif"></div>
</div>

    <script type="text/javascript" src="<?=$g4['path']?>/js/wrest.js"></script>

    <!-- bootstrap tooltip script -->
    <script type="text/javascript">
        $(document).ready(function(){
            $(".tooltip-help").tooltip({trigger: 'hover click','placement': 'top'});
        });
    </script>

    <!-- sideview script -->
    <script type="text/javascript">
        $('.sideview').bind('click',function(e){

            var el=$(this);
            var popover_title = el.attr('alt');
            var _data = "mb_nick="+encodeURI(popover_title)+"&mb_nick2="+encodeURI('<?=$member[mb_nick]?>')+"&bo_table="+encodeURI('<?=$bo_table?>')+"&sfl="+encodeURI('<?=urlencode($sfl)?>');

            e.isDefaultPrevented();

            $.ajax({url: "<?=$g4[bbs_path]?>/ajax_sideview.php", type: "POST", data: _data,
                success: function(response) {
                    el.popover({html: true, content: response, title: '<a onclick="" style="cursor:pointer">'+popover_title+'&nbsp;<i class="fa fa-plus-square"></i></a>'});
                    el.popover('show');
                }
            });

            el.unbind('click');

        });
    </script>

    <!-- 새창 대신 사용하는 iframe -->
    <iframe width=0 height=0 name='hiddenframe' style='display:none;' title='hidden frame'></iframe>

    <!-- 기존 불당팩 함수를 오버라이드하므로 SPA 에서 요청시에도 포함되어야 함 -->
    <script src="/m/js/common.js?v=<?= app_version() ?>"></script>

<?php if ( ! is_ajax()) { ?>
<script src="/m/js/app.js?v=<?= app_version() ?>"></script>
<script src="/m/vendor/admin-lte/js/app.min.js?v=<?= app_version() ?>"></script>
<script src="/m/vendor/slide-push-menus/menu.js?v=<?= app_version() ?>"></script>

<?php if (in_app()) { ?>
    <script src="/m/js/android.js?v=<?= app_version() ?>"></script>
<?php } ?>
</body>
</html>
<?php } ?>

<?
include_once("$g4[bbs_path]/login_session.php");
//include_once("$g4[path]/statcounter.php");
?>
