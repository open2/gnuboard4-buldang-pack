<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<div class="container">
<span>
<strong><a href='<?=$memo_url?>?kind=<?=$kind?>'><?=$memo_title?></a></strong>
</span>
<div class="btn-group pull-right">
  <? if ($view[after_href]) { ?><a class="btn btn-default" href='<?=$view[after_href]?>'>Prev</a><? } ?>
  <? if ($view[before_href]) { ?><a class="btn btn-default" href='<?=$view[before_href]?>'>Next</a><? } ?>
  <a class="btn btn-default" href='<?=$memo_url?>?kind=<?=$kind?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>&page=<?=$page?>'>List</a>
</div>
</div>

<div class="container" id="wr_content">
<form name="fboardlist" id="fboardlist" method="post" style="margin:0px;">
<input type='hidden' name='kind'  value='<?=$kind?>'>
<input type='hidden' name='me_id'  value='<?=$me_id?>'>
<table class="table table-bordered" width="100%">
<colgroup> 
    <col width="80">
    <col width="">
</colgroup> 
<thead>
<tr>
    <td align=center>��&nbsp;&nbsp;��</td>
    <td align=left>&nbsp;<?=$view['me_subject']?></td>
</tr>
<tr>
    <td align=center>��&nbsp;&nbsp;�� </td>
    <td>&nbsp;<?=$view['me_send_mb_id_nick']?> (<?=$view['me_send_datetime']?>)
    </td>
</tr>
<? if ($kind == 'notice') { ?>
    <? if ($is_admin=='super' || $member['mb_id']==$view['me_send_mb_id']) { ?>
    <tr>
        <td align=center>���ŷ���</td>
        <td align=left>&nbsp;<?=$view['me_recv_mb_id']?></td>
    </tr>
    <tr>
        <td align=center>���������</td>
        <td align=left>
        <?
        $sql = " select count(*) as cnt from $g4[memo_recv_table] where me_send_datetime = '$view[me_send_datetime]' and me_send_mb_id = '$member[mb_id]' and me_read_datetime = '0000-00-00 00:00:00' ";
        $result = sql_fetch($sql);
        $memo_notice_unread = $result['cnt'];
        ?>
        &nbsp;<?=number_format($memo_notice_unread)?>��
        </td>
    </tr>
    <? } ?>
<?} else {?>
    <tr>
        <td align=center>��&nbsp;&nbsp;��</td>
        <td align=left>&nbsp;<?=$view['me_recv_mb_id_nick']?> (<?=$view['me_read_datetime']?>)
        </td>
    </tr>
<? } ?>

<? if ($view[me_file_local] && !$view[imagesize]) { ?>
<tr>
    <td>÷������ </td>
    <td align=left>
        <a href="javascript:file_download()" title="<?=$view[me_file_local]?>"><?=$view[me_file_local]?></a>
    </td>
</tr>
<? } ?>
<!-- ÷�������� �̹����� ��� -->
<? if ($view[me_file_local] && $view[valid_image]) { ?>
<tr>
    <td height="20" align="left" style="border:none;padding-bottom:10px; width:<?=$content_inner_width?>px" colspan=2>
    <?
    if ($config['cf_memo_b4_resize']) {
        echo resize_content(" <img src='$g4[path]/data/memo2/$view[me_file_server]' style='cursor:pointer;' > ", $max_img_width);
    } else {
        echo " <img src='$g4[path]/data/memo2/$view[me_file_server]' onclick='image_window(this)' style='cursor:pointer;' > ";
    }
    ?>
    </td>
</tr>
<? } ?>

<tr>
    <td style="border:none;text-align:left; padding-left:15px; padding-top:30px; padding-bottom:30px; word-break:break-all;" colspan=2>
    <?
    if ($config['cf_memo_b4_resize'])
        echo resize_content($view['memo'], $max_img_width);
    else
        echo $view['memo'];

    // ������ ������ ������ ���
    if ($mb_send['mb_signature'])
        echo "<div style='; padding:25px 0;text-align:center;'>$mb_send[mb_signature]</div>";
    ?>

    <div class="btn-group pull-right"">
        <? if ($kind=="spam" && $is_admin == "super") { ?>
            <a class="btn btn-default btn-xs" href="javascript:all_cancel_spam();">Cancel Spam</a>
        <? } ?>
        <? if ($kind=="recv" && $view[spam_href]) { ?>
            <a class="btn btn-default btn-xs" href='<?=$view[spam_href]?>'>Spam</a>
        <? } ?>
    </div>
    </td>
</tr>

</table>

<div class="btn-group">
    <a class="btn btn-default" href='<?=$memo_url?>?kind=<?=$kind?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>&page=<?=$page?>'>List</a>
</div>
<div class="btn-group">
    <? if ($kind=="recv" or ($kind=="save" and $class=="view")) { ?>
        <a class="btn btn-default" href='<?=$memo_url?>?kind=write&me_recv_mb_id=<?=$view[me_send_mb_id]?>&me_id=<?=$me_id?>&me_box=<?=$kind?>'>Reply</a>&nbsp;
    <? } ?>
</div>

<div class="btn-group pull-right">
    <? if ($kind=="spam" && $view[spam_href]) { ?>
        <a class="btn btn-default" href='<?=$view[spam_href]?>'>Cencal</a>
    <? } ?>
    <? if ($kind=="send" and $view[me_read_datetime] == "���� ����") { ?>
        <a class="btn btn-default" href='<?=$view[cancel_href]?>'>Cancel</a>
    <? } ?>
    <? if ($kind=="recv" or $kind=="send") { ?>
        <a class="btn btn-default" href='<?=$view[save_href]?>'>Save</a>
    <? } ?>
    <? if ($kind=="recv" or $kind=="send" or $kind=="save" or $kind=="spam") { ?>
        <a class="btn btn-default" href='javascript:del_memo();'>Delete</a>
    <? } ?>
    <!-- �������� ���� = ������������ + �߼۵� �� ��� ȸ�� -->
    <? if ($kind=="notice" and ($is_admin == 'super' || $view[me_send_mb_id]==$member[mb_id])) { ?>
        <a href='javascript:withdraw_notice_memo();'>Delete</a>
    <? } ?>
    <? if ($kind=="trash" and $view[recover_href]) { ?>
        <a href='<?=$view[recover_href]?>'>Undelete</a>
    <? } ?>
</div>

</form>
</div>

<?
// ���� ������ include
$ad_file = $memo_skin_path . "/memo2_adsense.php";
if (file_exists($ad_file)) {
    include_once($ad_file);
}
?>

<script type="text/javascript">
function file_download() {
    var link = "<?=$g4[bbs_path]?>/download_memo_file.php?kind=<?=$kind?>&me_id=<?=$me_id?>";
    document.location.href=link;
}

// ������ ���
function all_cancel_spam() {
    var f = document.fboardlist;

    str = "����ȸ��";

    if (!confirm("��� ������ ���� "+str+" �Ͻðڽ��ϱ�?\n\n�ѹ� "+str+"�� �ڷ�� ������ �� �����ϴ�"))
        return;

    f.action = "./memo2_form_spam_cancel.php";
    f.submit();
}

function del_memo() 
{ 
   if (confirm("������ ���� �Ͻðڽ��ϱ�?")) 
            location.href = "<?=$view[del_href]?>"; 
}

function withdraw_notice_memo() 
{ 
   if (confirm("���������� �����ϸ�, �߼۵� ������ ��� ȸ��(����) �մϴ�.\n\n�������� ������ ���� �Ͻðڽ��ϱ�?")) 
            location.href = "./memo2_withdraw_notice.php?kind=<?=$kind?>&me_id=<?=$me_id?>"; 
}
</script>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script type="text/javascript">
window.onload=function() {
    resizeBoardImage($(wr_content).width()-10);
}
</script>