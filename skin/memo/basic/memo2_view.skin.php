<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
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

<div class="container">
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
    <td align=center>제&nbsp;&nbsp;목</td>
    <td align=left>&nbsp;<?=$view['me_subject']?></td>
</tr>
<tr>
    <td align=center>발&nbsp;&nbsp;신 </td>
    <td>&nbsp;<?=$view['me_send_mb_id_nick']?> (<?=$view['me_send_datetime']?>)
    </td>
</tr>
<? if ($kind == 'notice') { ?>
    <? if ($is_admin=='super' || $member['mb_id']==$view['me_send_mb_id']) { ?>
    <tr>
        <td align=center>수신레벨</td>
        <td align=left>&nbsp;<?=$view['me_recv_mb_id']?></td>
    </tr>
    <tr>
        <td align=center>안읽은사람</td>
        <td align=left>
        <?
        $sql = " select count(*) as cnt from $g4[memo_recv_table] where me_send_datetime = '$view[me_send_datetime]' and me_send_mb_id = '$member[mb_id]' and me_read_datetime = '0000-00-00 00:00:00' ";
        $result = sql_fetch($sql);
        $memo_notice_unread = $result['cnt'];
        ?>
        &nbsp;<?=number_format($memo_notice_unread)?>명
        </td>
    </tr>
    <? } ?>
<?} else {?>
    <tr>
        <td align=center>수&nbsp;&nbsp;신</td>
        <td align=left>&nbsp;<?=$view['me_recv_mb_id_nick']?> (<?=$view['me_read_datetime']?>)
        </td>
    </tr>
<? } ?>

<? if ($view[me_file_local] && !$view[imagesize]) { ?>
<tr>
    <td>첨부파일 </td>
    <td align=left>
        <a href="javascript:file_download()" title="<?=$view[me_file_local]?>"><?=$view[me_file_local]?></a>
    </td>
</tr>
<? } ?>
<!-- 첨부파일의 이미지를 출력 -->
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

    // 서명이 있으면 서명을 출력
    if ($mb_send['mb_signature'])
        echo "<div style='; padding:25px 0;text-align:center;'>$mb_send[mb_signature]</div>";
    ?>
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
    <? if ($kind=="spam" && $is_admin == "super") { ?>
        <a class="btn btn-default" href="javascript:all_cancel_spam();">Cancel Spam</a>
    <? } ?>
    <? if ($kind=="recv" && $view[spam_href]) { ?>
        <a class="btn btn-default" href='<?=$view[spam_href]?>'>Spam</a>
    <? } ?>
    <? if ($kind=="send" and $view[me_read_datetime] == "읽지 않음") { ?>
        <a class="btn btn-default" href='<?=$view[cancel_href]?>'>Cancel</a>
    <? } ?>
    <? if ($kind=="recv" or $kind=="send") { ?>
        <a class="btn btn-default" href='<?=$view[save_href]?>'>Save</a>
    <? } ?>
    <? if ($kind=="recv" or $kind=="send" or $kind=="save" or $kind=="spam") { ?>
        <a class="btn btn-default" href='javascript:del_memo();'>Delete</a>
    <? } ?>
    <!-- 공지쪽지 삭제 = 공지쪽지삭제 + 발송된 것 모두 회수 -->
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
// 구글 광고를 include
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

// 스팸을 취소
function all_cancel_spam() {
    var f = document.fboardlist;

    str = "스팸회수";

    if (!confirm("모든 쪽지를 정말 "+str+" 하시겠습니까?\n\n한번 "+str+"한 자료는 복구할 수 없습니다"))
        return;

    f.action = "./memo2_form_spam_cancel.php";
    f.submit();
}

function del_memo() 
{ 
   if (confirm("쪽지를 삭제 하시겠습니까?")) 
            location.href = "<?=$view[del_href]?>"; 
}

function withdraw_notice_memo() 
{ 
   if (confirm("공지쪽지를 삭제하면, 발송된 쪽지를 모두 회수(삭제) 합니다.\n\n공지쪽지 삭제를 진행 하시겠습니까?")) 
            location.href = "./memo2_withdraw_notice.php?kind=<?=$kind?>&me_id=<?=$me_id?>"; 
}
</script>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script type="text/javascript">
window.onload=function() {
    resizeBoardImage($(window).width()-50);
    drawFont();
}
</script>