<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<form role="form" name="fpoll" method="post" action="<?=$g4[bbs_path]?>/poll_update.php" onsubmit="return fpoll_submit(this);" target="winPoll">
<input type="hidden" name="po_id" value="<?=$po_id?>">
<input type="hidden" name="skin_dir" value="<?=$skin_dir?>">

<div class="panel panel-default">
    <div class="panel-heading"><?=$po[po_subject]?>
    <? if ($is_admin == "super") { ?>&nbsp<a href="<?=$g4[admin_path]?>/poll_form.php?w=u&po_id=<?=$po_id?>"><i class="fa fa-cog"></i></a><? } ?>
    </div>
    <div class="panel-body">
    <ul class="list-unstyled">
    <? 
    for ($i=1; $i<=9 && $po["po_poll{$i}"]; $i++) {
        echo "<li>";
        echo "<input type='radio' name='gb_poll' value='$i' id='gb_poll_$i'>";
        echo " <label for='gb_poll_$i'>" . $po['po_poll'.$i] . "</label>";
        echo "</li>";
    }
    ?>
    </ul>
    <div class="btn-group btn-group-justified">
    <a class="btn btn-info" href="javascript:;" onclick="poll_result('<?=$po_id?>');">결과</a>
    <? if ($po_use) { ?>
    <button type="submit" class="btn btn-success">투표</button>
    <? } ?>
    </div>
    </div>
</div>
</form>

<script type="text/javascript">
function fpoll_submit(f)
{
    var chk = false;
    for (i=0; i<f.gb_poll.length;i ++) {
        if (f.gb_poll[i].checked == true) {
            chk = f.gb_poll[i].value;
            break;
        }
    }

    <?
    if ($member[mb_level] < $po[po_level])
        echo " alert('$po[gl_name] 이상의 회원만 투표에 참여하실 수 있습니다.'); return false; ";
    ?>

    if (!chk) {
        alert("항목을 선택하세요");
        return false;
    }

    win_poll();
    return true;
}

function poll_result(po_id)
{
    <?
    if ($member[mb_level] < $po[po_level])
        echo " alert('$po[gl_name] 이상의 회원만 결과를 보실 수 있습니다.'); return false; ";
    ?>

    win_poll("<?=$g4[bbs_path]?>/poll_result.php?po_id="+po_id+"&skin_dir="+document.fpoll.skin_dir.value);
}
</script>
