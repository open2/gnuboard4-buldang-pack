<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// ��Ų���� ����ϴ� lib �о���̱�
include_once("$g4[path]/lib/view.skin.lib.php");

$time = date("Y-m-d H:i:s", $g4[server_time] - 86400 * 7);
$sql = " select count(*) as cnt from $g4[board_good_table] 
          where mb_id = '$member[mb_id]'
		    and bg_datetime >= '$time' ";
$row = sql_fetch($sql);
$cnt = $row[cnt];

if ($board[bo_3] && $view[wr_nogood] >= $board[bo_3] && !$is_admin)
    alert("����õ�� ���� ���� ���� Ȯ���� �Ұ��մϴ�.");

if (function_exists('exif_read_data'))
    $exif = @exif_read_data("{$view[file][0][path]}/{$view[file][0][file]}");
?>

<!-- �Խñ� ���� ���� -->
<table width="<?=$width?>" align="center" cellpadding="0" cellspcing="0"><tr><td>

<!-- ��ũ ��ư -->
<? 
ob_start(); 
?>
<table width='100%' cellpadding=0 cellspacing=0>
<tr height=35>
    <td width=75%>
        <? if ($search_href) { echo "<a href=\"$search_href\"><img src='$board_skin_path/img/btn_search_list.gif' border='0' align='absmiddle'></a> "; } ?>
        <? echo "<a href=\"$list_href\"><img src='$board_skin_path/img/btn_list.gif' border='0' align='absmiddle'></a> "; ?>

        <? if ($write_href) { echo "<a href=\"$write_href\"><img src='$board_skin_path/img/btn_write.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($reply_href) { echo "<a href=\"$reply_href\"><img src='$board_skin_path/img/btn_reply.gif' border='0' align='absmiddle'></a> "; } ?>

        <? if ($update_href) { echo "<a href=\"$update_href\"><img src='$board_skin_path/img/btn_update.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($delete_href) { echo "<a href=\"$delete_href\"><img src='$board_skin_path/img/btn_delete.gif' border='0' align='absmiddle'></a> "; } ?>

        <? if ($is_admin) { $good_href = "./good.php?bo_table=$bo_table&wr_id=$wr_id&good=good";} ?> 
        <? if ($good_href) { echo "<a href=\"$good_href\" target='hiddenframe'><img src='$board_skin_path/img/btn_good.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($nogood_href) { echo "<a href=\"$nogood_href\" target='hiddenframe'><img src='$board_skin_path/img/btn_nogood.gif' border='0' align='absmiddle'></a> "; } ?>

        <? if ($scrap_href) { echo "<a href=\"javascript:;\" onclick=\"win_scrap('$scrap_href');\"><img src='$board_skin_path/img/btn_scrap.gif' border='0' align='absmiddle'></a> "; } ?>

        <? if ($copy_href) { echo "<a href=\"$copy_href\"><img src='$board_skin_path/img/btn_copy.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($move_href) { echo "<a href=\"$move_href\"><img src='$board_skin_path/img/btn_move.gif' border='0' align='absmiddle'></a> "; } ?>

        <? if ($nosecret_href) { echo "<a href=\"$nosecret_href\"><img src='$board_skin_path/img/btn_nosecret.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($secret_href) { echo "<a href=\"$secret_href\"><img src='$board_skin_path/img/btn_secret.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($now_href) { echo "<a href=\"$now_href\"><img src='$board_skin_path/img/btn_now.gif' border='0' align='absmiddle'></a> "; } ?>
    </td>
    <td width=25% align=right>
        <? if ($prev_href) { echo "<a href=\"$prev_href\" title=\"$prev_wr_subject\"><img src='$board_skin_path/img/btn_prev.gif' border='0' align='absmiddle'></a>&nbsp;"; } ?>
        <? if ($next_href) { echo "<a href=\"$next_href\" title=\"$next_wr_subject\"><img src='$board_skin_path/img/btn_next.gif' border='0' align='absmiddle'></a>&nbsp;"; } ?>
    </td>
</tr>
</table>
<?
$link_buttons = ob_get_contents();
ob_end_flush();
?>

<table width="100%" cellspacing="0" cellpadding="0" >
<tr><td height=2 bgcolor=#B0ADF5></td></tr> 
<tr><td height=30 bgcolor=#F8F8F9 style="padding:5 0 5 0;">&nbsp;&nbsp;<strong><? if ($is_category) { echo ($category_name ? "[$view[ca_name]] " : ""); } ?><?=$view[subject]?></strong></td></tr>
<tr><td height=30>
    <span style="float:left;">
    &nbsp;&nbsp;�۾��� : <?=$view[name]?><? if ($is_ip_view) { echo "&nbsp;($ip)"; } ?>&nbsp;&nbsp;&nbsp;&nbsp;
    ��¥ : <?=substr($view[wr_datetime],2,14)?>&nbsp;&nbsp;&nbsp;&nbsp;
    ��ȸ : <?=$view[wr_hit]?>&nbsp;&nbsp;&nbsp;&nbsp;
    <? if ($is_good) { ?><font style="font:normal 11px ����; color:#BABABA;">��õ</font> :<font style="font:normal 11px tahoma; color:#BABABA;"> <?=$view[wr_good]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?></font>
    <? if ($is_nogood) { ?><font style="font:normal 11px ����; color:#BABABA;">����õ</font> :<font style="font:normal 11px tahoma; color:#BABABA;"> <?=$view[wr_nogood]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?></font>
    </span>
    <?if ($singo_href) { ?><span style="float:right;padding-right:5px;"><a href="javascript:win_singo('<?=$singo_href?>');"><img src='<?=$board_skin_path?>/img/icon_singo.gif'></a></span><?}?>
    <?if ($unsingo_href) { ?><span style="float:right;padding-right:5px;"><a href="javascript:win_unsingo('<?=$unsingo_href?>');"><img src='<?=$board_skin_path?>/img/icon_unsingo.gif'></a></span><?}?>
</td></tr>

<!-- �Խñ� �ּҸ� �����ϱ� ���� �ϱ� ���ؼ� �Ʒ� �κ��� ���� -->
<tr><td height=30>
        <font style="font:normal 11px ����; color:#BABABA;">&nbsp;&nbsp;�Խñ� �ּ� : <a href="javascript:clipboard_trackback('<?=$posting_url?>');" style="letter-spacing:0;" title='�� ���� �Ұ��� ���� �� �ּҸ� ����ϼ���'><?=$posting_url;?></a></font>
        <? 
        if ($is_member && $g4[use_gblog]) {
            $gb4_path="../blog";
            include_once("$gb4_path/common.php");
        ?>
        <script language=javascript>
        // gblog���� ���� java script �������� ����
        var gb4_blog        = "<?=$gb4['bbs_path']?>";
        </script>
        <script type="text/javascript"  src="<?="$gb4[path]/js/blog.js"?>"></script>
        <a href="javascript:send_to_gblog('<?=$bo_table?>','<?=$wr_id?>')">��α׷� �ۺ�����</a>
        <? } ?>
</td></tr>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>

<?
// ���� ����
$cnt = 0;
for ($i=0; $i<count($view[file]); $i++) 
{
    //if ($view[file][$i][source] && !$view[file][$i][view]) 
    if ($view[file][$i][source]) 
    {
        $cnt++;
        //echo "<tr><td height=22>&nbsp;&nbsp;<img src='{$board_skin_path}/img/icon_file.gif' align=absmiddle> <a href='{$view[file][$i][href]}' title='{$view[file][$i][content]}'><strong>{$view[file][$i][source]}</strong> ({$view[file][$i][size]}), Down : {$view[file][$i][download]}, {$view[file][$i][datetime]}</a></td></tr>";
        echo "<tr><td height=22 style='padding:3px 0 3px 0;' class=lh>&nbsp;&nbsp;";
        echo "<img src='{$board_skin_path}/img/icon_file.gif' align=absmiddle> ";
        echo "<a href=\"javascript:file_download('{$view[file][$i][href]}', '{$view[file][$i][source]}');\" title='{$view[file][$i][content]}'><u><b>{$view[file][$i][source]}</b> ({$view[file][$i][size]})<!--, {$view[file][$i][datetime]}--> (�����̹��� ������ <b>{$exif[COMPUTED][Width]} x {$exif[COMPUTED][Height]}</b>)</u></a><br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<span style='color:blue;'>�� �����̹��� �ٿ�ε�� <b>".number_format(abs($board[bo_download_point]))." ����Ʈ</b>�� �����մϴ�.</span></td></tr>";
    }
}

// ��ũ
$cnt = 0;
for ($i=1; $i<=$g4[link_count]; $i++) 
{
    if ($view[link][$i]) 
    {
        $cnt++;
        $link = cut_str($view[link][$i], 70);
        echo "<tr><td height=22>&nbsp;&nbsp;<img src='{$board_skin_path}/img/icon_link.gif' align=absmiddle> <a href='{$view[link_href][$i]}' target=_blank><strong>{$link}</strong> ({$view[link_hit][$i]})</a></td></tr>";
    }
}
?>

<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr> 
    <td height="150" style='word-break:break-all; padding:10px;' bgcolor=#F8F8F9>
    <div id="resContents" style="position-horizontal:absolute;">

        <!-- <font color=#ff8800><b>��Ͽ��� ���� �̹����� Ȯ���Ͻ� �� �� ���� ������ �ٿ�ε� �޾Ƽ� ����ϼ���.</b></font>
        <br><br> -->
        <? 
        $board[bo_image_width] = $board[bo_1];
        // ���� ���
        for ($i=0; $i<=count($view[file]); $i++) {
            if ($view[file][$i][view]) {
                echo "<p><div style='width:{$board[bo_image_width]}px;border:1px solid #cccccc;background:#EEEEEE;padding:10px;'>" . resize_content($view[file][$i][view]) ."</div>";

                echo "<p>" ;
                if ($good_href) { echo "<a href=\"$good_href\" target='hiddenframe'><img src='$board_skin_path/img/btn_good.gif' border='0' align='absmiddle'></a> "; }
                if ($nogood_href) { echo "&nbsp; <a href=\"$nogood_href\" target='hiddenframe'><img src='$board_skin_path/img/btn_nogood.gif' border='0' align='absmiddle'></a> "; }

                if (function_exists('exif_read_data') && $board[bo_4]) { // bo_4�� ���� ���� ��쿡 exit ������ ���
                echo "<p><b>>>> EXIF ���� <<<</b><br>" ;
                $exif = @exif_read_data("{$view[file][$i][path]}/{$view[file][$i][file]}");
                if (isset($exif[Make]) || isset($exif[Model])) echo "ī�޶�� : $exif[Make] - $exif[Model]<br>";
                if (isset($exif[DateTimeOriginal])) echo "�Կ��Ͻ� : $exif[DateTimeOriginal]<br>";
                if (isset($exif[COMPUTED][Width]) || isset($exif[COMPUTED][Height])) echo "���� �̹���ũ�� : {$exif[COMPUTED][Width]} x {$exif[COMPUTED][Height]} �ȼ�<br>";
                if (isset($exif[COMPUTED][ApertureFNumber])) echo "������ : {$exif[COMPUTED][ApertureFNumber]}<br>";
                if (isset($exif[ISOSpeedRatings])) echo "ISO : $exif[ISOSpeedRatings]<br>";
                if (isset($exif[WhiteBalance])) echo "ȭ��Ʈ�뷱�� : {$exif[WhiteBalance]}<br>";
                if (isset($exif[ExposureTime])) echo "����ð� : $exif[ExposureTime] ��<br>";
                if (isset($exif[ExposureBiasValue])) echo "���⺸�� : $exif[ExposureBiasValue]<br>";
                if (isset($exif[COMPUTED][CCDWidth])) echo "CCD : {$exif[COMPUTED][CCDWidth]}<br>";
                if (isset($exif[Flash])) echo "�÷��� : {$exif[Flash]}<br>";
                echo "<p>" ;
                }
            }
        }
        ?>

        <BR>

        <span class="ct lh"><?=$view[content];?></span>
        <?//echo $view[rich_content]; // {�̹���:0} �� ���� �ڵ带 ����� ���?>
        <!-- �׷� �±� ������ --></xml></xmp><a href=""></a><a href=''></a>

        <tr><td height="1" bgcolor="#E7E7E7"></td></tr>
        <? if ($is_signature) { echo "<tr><td align='center' style='border-bottom:1px solid #E7E7E7; padding:5px 0;'>$signature</td></tr>"; } // ���� ��� ?>

        <?
        // CCL ����
        $view[wr_ccl] = $write[wr_ccl] = mw_get_ccl_info($write[wr_ccl]);
        ?>

        <? if ($board[bo_ccl] && $view[wr_ccl][by]) { ?>
        <tr style='padding:10px;' class=mw_basic_ccl><td>
        <a rel="license" href="<?=$view[wr_ccl][link]?>" title="<?=$view[wr_ccl][msg]?>" target=_blank><img src="<?=$board_skin_path?>/img/ccls_by.gif">
        <? if ($view[wr_ccl][nc] == "nc") { ?><img src="<?=$board_skin_path?>/img/ccls_nc.gif"><? } ?>
        <? if ($view[wr_ccl][nd] == "nd") { ?><img src="<?=$board_skin_path?>/img/ccls_nd.gif"><? } ?>
        <? if ($view[wr_ccl][nd] == "sa") { ?><img src="<?=$board_skin_path?>/img/ccls_sa.gif"><? } ?>
        </a>
        </td></tr>
        <? } ?>
        
        <? if ($board[bo_related] && $view[wr_related]) { ?>
        <? $rels = mw_related($view[wr_related], $board[bo_related]); ?>
        <? if (count($rels)) {?>
        <tr>
            <td>
            <b>���ñ�</b> : <?=$view[wr_related]?>
            </td>
        </tr>
        <tr>
            <td>
                <ul>
                <? for ($i=0; $i<count($rels); $i++) { ?>
                <li> <a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>&wr_id=<?=$rels[$i][wr_id]?>"> <?=$rels[$i][wr_subject]?> </a> </li>
                <? } ?>
                </ul>
            </td>
        </tr>
        <? } ?>
        <? } ?>

        <? 
        // �α�˻���
        if ($board[bo_popular]) { 
        
        unset($plist);
        $plist = popular_list($board[bo_popular], $board[bo_popular_days], $bo_table);
        
        if (count($plist) > 0) {
        ?>
        <tr>
            <td>
                <b>�α�˻���</b> : 
                <? 
                for ($i=0; $i<count($plist); $i++) {
                    if (trim($plist[$i][sfl]) == '' || strstr($plist[$i][sfl], '\%7C')) $plist[$i][sfl] = "wr_subject||wr_content";
                ?>
                <a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>&sfl=<?=urlencode($plist[$i][sfl])?>&stx=<?=$plist[$i]['pp_word']?>"><?=$plist[$i]['pp_word']?></a>&nbsp;&nbsp;
                <? } ?>
            </td>
        </tr>
        <? } ?>
        <? } ?>
</div>
</td>
</tr>

</table><br>

<?
// �ڸ�Ʈ �����
if (!$board['bo_comment_read_level'])
  include_once("./view_comment.php");
else if ($member['mb_level'] >= $board['bo_comment_read_level'])
  include_once("./view_comment.php");
?>

<?=$link_buttons?>

</td></tr>
<tr><td>
<? include_once("$g4[path]/adsense_page_bottom.php"); ?>
</td></tr>
</table><br>

<script language="JavaScript">
function file_download(link, file) {
<? 
if ($board['bo_download_point'] < 0) { 
    // �������̰ų� �ڽ��� ���̰ų� ����� ���ų� ������, ��� �� �Ѵ��� �������� ���
    $mb = get_member("$view[mb_id]", "mb_level");
    if ($is_admin || 
        ($view[mb_id] == $member[mb_id] && $member[mb_id]) || 
        $member[mb_level] >= $mb[mb_level] ||
        $view[wr_datetime] < date("Y-m-d H:i:s", $g4[server_time] - 86400 * 30))
        ;
    else  {

        $sql = " select count(*) as cnt from $g4[point_table]
                  where mb_id = '$member[mb_id]'
                    and po_rel_table = '$bo_table'
                    and po_rel_id = '$wr_id'
                    and po_rel_action = '�ٿ�ε�' ";
        $row = sql_fetch($sql);
        if (!$row[cnt]) {
?>
            if (confirm("'"+file+"' ������ �ٿ�ε� �Ͻø� ����Ʈ�� <?=number_format($board['bo_download_point'])?> �� �����˴ϴ�.\n\n����Ʈ�� �Խù��� �ѹ��� �����Ǹ� ������ �ٽ� �ٿ�ε� �ϼŵ� �ߺ��Ͽ� �������� �ʽ��ϴ�.\n\n�׷��� �ٿ�ε� �Ͻðڽ��ϱ�?"))
<?
        }
    }
}
?>
document.location.href = link;
}
</script>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script language="JavaScript">
window.onload=function() {
    resizeBoardImage(<?=(int)$board[bo_image_width]?>);
    drawFont();
    OnclickCheck(document.getElementById("writeContents"), '<?=$config[cf_link_target]?>'); 
}
</script>
<!-- �Խñ� ���� �� -->
