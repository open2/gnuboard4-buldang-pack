<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

// �ټ��� ��ǥ�� ����ϱ� ���ؼ�, form�� unique�ϰ� ����� �ݴϴ�
$mt = uniqid();
?>

<form role="form" name="fpoll_<?=$mt?>" method="post" action="<?=$g4[bbs_path]?>/poll_update.php" onsubmit="return fpoll_submit_<?=$mt?>(this);" target="winPoll">
<input type="hidden" name="po_id" value="<?=$po_id?>">
<input type="hidden" name="skin_dir" value="<?=$skin_dir?>">

<div class="panel panel-default">
    <div class="panel-heading"><?=$po[po_subject]?>
    <? if ($is_admin == "super") { ?>&nbsp;<a href="<?=$g4[admin_path]?>/poll_form.php?w=u&po_id=<?=$po_id?>"><i class="fa fa-cog"></i></a><? } ?>
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
    <div class="btn-group">
    <a class="btn btn-default" href="javascript:;" onclick="poll_result_<?=$mt?>('<?=$po_id?>');">���</a>
    <? if ($po_use) { ?>
    <button type="submit" class="btn btn-default">��ǥ</button>
    <? } ?>
    </div>
    </div>
</div>
</form>

<script type="text/javascript">
function fpoll_submit_<?=$mt?>(f)
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
        echo " alert('$po[gl_name] �̻��� ȸ���� ��ǥ�� �����Ͻ� �� �ֽ��ϴ�.'); return false; ";
    ?>

    if (!chk) {
        alert("�׸��� �����ϼ���");
        return false;
    }

    win_poll();
    return true;
}

function poll_result_<?=$mt?>(po_id)
{
    <?
    if ($member[mb_level] < $po[po_level])
        echo " alert('$po[gl_name] �̻��� ȸ���� ����� ���� �� �ֽ��ϴ�.'); return false; ";
    ?>

    win_poll("<?=$g4[bbs_path]?>/poll_result.php?po_id="+po_id+"&skin_dir="+document.fpoll_<?=$mt?>.skin_dir.value);
}
</script>
