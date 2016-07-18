<?
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// session lock을 막기 위해서 쓴 다음에 항상 닫아준다
session_write_close();

if (is_mobile()) {
    require($g4['path'] . '/m/tail.sub.php');

    return;
}
?>

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

<? if ($is_admin == "super") { ?><div class="well" style="text-align:center;">RUN TIME : <?=get_microtime()-$begin_time;?><br></div><? } ?>

</body>
</html>
<?
include_once("$g4[bbs_path]/login_session.php");
include_once("$g4[path]/statcounter.php");
?>
