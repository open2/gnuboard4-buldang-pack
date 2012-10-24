<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<link rel="stylesheet" href="<?=$poll_skin_path?>/style.css" type="text/css">

<div class="section_ol">
<form name="fpoll" method="post" action="<?=$g4[bbs_path]?>/poll_update.php" onsubmit="return fpoll_submit(this);" target="winPoll">
<input type="hidden" name="po_id" value="<?=$po_id?>">
<input type="hidden" name="skin_dir" value="<?=$skin_dir?>">

<!--
<h2><a style="text-decoration:none" href='#' title='<?=$po[po_summary]?>'><?=$po[po_subject]?></a></h2>
-->
<h2 title='<?=$po[po_summary]?>'><?=$po[po_subject]?></h2>
<ol style='text-align:left;margin-left:30px;'>
    <? 
    for ($i=1; $i<=9 && $po["po_poll{$i}"]; $i++) {
        echo "<li>";
        echo "<span>";
        echo "<input type='radio' name='gb_poll' value='$i' id='gb_poll_$i'>";
        echo "<label for='gb_poll_$i'>" . $po['po_poll'.$i] . "</label>";
        echo "</span>";
        echo "</li>";
    }
    ?>
    <li style='text-align:center;'>
        <? if ($po_use) { ?>
        <input type="image" src="<?=$poll_skin_path?>/img/poll_button.gif" width="70" height="25" border="0">
        <? } ?>
        <a href="javascript:;" onclick="poll_result('<?=$po_id?>');"><img src="<?=$poll_skin_path?>/img/poll_view.gif" width="70" height="25" border="0"></a>
        <? if ($is_admin == "super") { ?><a href="<?=$g4[admin_path]?>/poll_form.php?w=u&po_id=<?=$po_id?>"><img src="<?=$poll_skin_path?>/img/admin.gif" width="33" height="15" border=0 align=absmiddle></a><? } ?>
    </li>
</ol>

</form>
</div>

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
