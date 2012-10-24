<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<script type="text/javascript">
function print_contents(print_id) 
{ 
var contents = "";
contents += "<html><head><meta http-equiv='content-type' content='text/html; charset=<?=$g4[charset]?>'>";
contents += "<title><?=$g4[title]?></title>";
contents += "<link rel='stylesheet' href='<?=$g4[path]?>/style.css' type='text/css'>";
contents += "</head>";
contents += "<body>";
contents += "<link rel='stylesheet' href='<?=$memo_skin_path?>/memo2.css' type='text/css'>";
contents += "<div>";
contents += document.getElementById(print_id).innerHTML; 
contents += "</div>";
contents += "</body>";
contents += "</html>";
var width_dim = document.getElementById(print_id).clientWidth + 20;
var width = width_dim + 'px';
var height_dim = 600;
var height = height_dim + 'px'; 
var left = (screen.availWidth - width_dim) / 2; 
var top = (screen.availHeight - height_dim) / 2; 
var options = 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',status=no,resizable=no,scrollbars=yes'; 
var win = window.open('', '', options); 
win.document.write(contents); 
if (document.all) { 
win.document.execCommand('Print'); 
} 
else { 
win.print(); 
}
}
</script> 

<table width="100%" height="30" border="0" cellpadding="0" cellspacing="0">
<tr align=left>
  <td width="14">&nbsp;</td>
  <td width="20"><img src="<?=$memo_skin_path?>/img/memo_icon01.gif" width="13" height="12" /></td>
  <td><span class="style5"><?=$memo_title?> - 쪽지보기</span></td>
  <td align=right>
  <? if ($config[cf_memo_print]) { ?><a href="#" onclick="javascript:print_contents('memo_contents')">프린트</a>&nbsp;&nbsp;&nbsp;<? } ?>
  <? if ($view[after_href]) { ?><a href='<?=$view[after_href]?>'>다음</a>&nbsp;&nbsp;&nbsp;<? } ?>
  <? if ($view[before_href]) { ?><a href='<?=$view[before_href]?>'>이전</a>&nbsp;&nbsp;&nbsp;<? } ?>
  <a href='<?=$memo_url?>?kind=<?=$kind?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>&page=<?=$page?>'>목록</a></td>
</tr>
</table>

<form name="fboardlist" id="fboardlist" method="post" style="margin:0px;">
<input type='hidden' name='kind'  value='<?=$kind?>'>
<input type='hidden' name='me_id'  value='<?=$me_id?>'>      
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="memo_contents">
<tr>
  <td width="5" height="5" background="<?=$memo_skin_path?>/img/memo_box2_tl.gif"></td>
  <td background="<?=$memo_skin_path?>/img/memo_line2_top.gif"></td>
  <td width="5" background="<?=$memo_skin_path?>/img/memo_box2_tr.gif"></td>
</tr>

<tr>
  <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_left.gif">&nbsp;</td>
  <td align="center">
    <table width="99%" height="30" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" height="26"><span class="style5" style="padding-left:5px;">제&nbsp;&nbsp;목 </span></td>
        <td align=left><span class="style11" style="padding-left:5px;"><?=$view[me_subject]?></span></td>
      </tr>
      <tr>
        <td width="70" height="26"><span class="style5" style="padding-left:5px;">보낸사람 </span></td>
        <td align=left><span class="style11" style="padding-left:5px;"><?=$view[me_send_mb_id_nick]?> (<?=$view[me_send_datetime]?>)</span></td>
      </tr>
      <? if ($kind == 'notice') { ?>
      <? if ($is_admin=='super' || $member[mb_id]==$view[me_send_mb_id]) { ?>
      <tr>
        <td width="70" height="26"><span class="style5" style="padding-left:5px;">수신레벨 </span></td>
        <td align=left><span class="style11" style="padding-left:5px;"><?=$view[me_recv_mb_id]?></span></td>
      </tr>
      <tr>
        <td width="70" height="26"><span class="style5" style="padding-left:5px;">안읽은사람 </span></td>
        <td align=left><span class="style11" style="padding-left:5px;">
        <?
        $sql = " select count(*) as cnt from $g4[memo_recv_table] where me_send_datetime = '$view[me_send_datetime]' and me_send_mb_id = '$member[mb_id]' and me_read_datetime = '0000-00-00 00:00:00' ";
        $result = sql_fetch($sql);
        $memo_notice_unread = $result[cnt];
        ?>
        &nbsp;<?=number_format($memo_notice_unread)?>명
        </span>
        </td>
      </tr>
      <? } ?>
      <? } else { ?>
      <tr>
        <td width="70" height="26"><span class="style5" style="padding-left:5px;">받는사람 </span></td>
        <td align=left><span class="style11" style="padding-left:5px;"><?=$view[me_recv_mb_id_nick]?> (<?=$view[me_read_datetime]?>)</span></td>
      </tr>
      <? } ?>
      <? if ($view[me_file_local] && !$view[imagesize]) { ?>
      <tr>
        <td width="70" height="26"><span class="style5" style="padding-left:5px;">첨부파일 </span></td>
        <td align=left><span class="style11" style="padding-left:5px;">
            <a href="javascript:file_download()" title="<?=$view[me_file_local]?>"><?=$view[me_file_local]?></a>
            </span>
        </td>
      </tr>
      <? } ?>
    </table>

    <table width="99%" height="30" border="0" cellpadding="0" cellspacing="0" id="memo_view">
      <tr><td height="1" colspan="2" bgcolor="d9d9d9"></td></tr>
      <tr>
        <td height="20" colspan="2"></td>
      </tr>
      <!-- 첨부파일의 이미지를 출력 -->
      <? if ($view[me_file_local] && $view[valid_image]) { ?>
      <tr>
        <td height="20" align="center" style="padding-bottom:10px; width:<?=$content_inner_width?>px">
          <?
          if ($config['cf_memo_b4_resize']) {
              echo resize_content(" <img src='$g4[path]/data/memo2/$view[me_file_server]' style='cursor:pointer;' > ", $max_img_width);
              }
          else
              echo " <img src='$g4[path]/data/memo2/$view[me_file_server]' name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' > ";
          ?>
        </td>
      </tr>
      <? } ?>
      <tr>
        <td style="text-align:left;padding-left:10px; width:<?=$content_inner_width?>px; word-break:break-all;">
        <?
        if ($config['cf_memo_b4_resize'])
            echo resize_content($view[memo], $max_img_width);
        else
            echo $view[memo];
        ?>
        </td>
      </tr>
      <tr>
        <td height="20" colspan="2"></td>
      </tr>
      <tr><td height="1" bgcolor="#E7E7E7"></td></tr>
      <?
      if ($mb_send[mb_signature]) {
      ?>
      <tr>
        <td align='center' style='border-bottom:1px solid #E7E7E7; padding:5px 0;'>
        <?=$mb_send[mb_signature]?>
        </td>
      </tr>
      <? } ?>
      <tr>
        <td height="10" colspan="2"></td>
      </tr>
    </table>

    <table width="99%" height="30" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="250" align="right" style="padding-right:10px;">
            <? if ($kind=="recv" or ($kind=="save" and $class=="view")) { ?>
              <a href='<?=$memo_url?>?kind=write&me_recv_mb_id=<?=$view[me_send_mb_id]?>&me_id=<?=$me_id?>&me_box=<?=$kind?>'><img src="<?=$memo_skin_path?>/img/reply.gif" /></a>&nbsp;
            <? } ?>
            <a href='<?=$memo_url?>?kind=<?=$kind?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>&page=<?=$page?>'><img src="<?=$memo_skin_path?>/img/list.gif" /></a>
            </td>
            <td align="right">
            <? if ($kind=="spam" && $view[spam_href]) { ?>
            <a href='<?=$view[spam_href]?>'><img src="<?=$memo_skin_path?>/img/spam_cancel.gif" ></a>&nbsp;
            <? } ?>
            <? if ($kind=="spam" && $is_admin == "super") { ?>
            <a href="javascript:all_cancel_spam();"><img src="<?=$memo_skin_path?>/img/spam_cancel_all.gif" ></a>&nbsp;
            <? } ?>
            <? if ($kind=="recv" && $view[spam_href]) { ?>
            <a href='<?=$view[spam_href]?>'>
            <img src="<?=$memo_skin_path?>/img/bt03.gif" /></a>&nbsp;
            <? } ?>
            <? if ($kind=="send" and $view[me_read_datetime] == "읽지 않음") { ?>
            <a href='<?=$view[cancel_href]?>'>
            <img src="<?=$memo_skin_path?>/img/icon_cancel.gif" /></a>&nbsp;
            <? } ?>
            <? if ($kind=="recv" or $kind=="send") { ?>
            <a href='<?=$view[save_href]?>'>
            <img src="<?=$memo_skin_path?>/img/save.gif" /></a>&nbsp;
            <? } ?>
            <? if ($kind=="recv" or $kind=="send" or $kind=="save" or $kind=="spam") { ?>
            <a href='javascript:del_memo();'>
            <img src="<?=$memo_skin_path?>/img/bt04.gif" /></a>
            <? } ?>
            <!-- 공지쪽지 삭제 = 공지쪽지삭제 + 발송된 것 모두 회수 -->
            <? if ($kind=="notice" and ($is_admin == 'super' || $view[me_send_mb_id]==$member[mb_id])) { ?>
            <a href='javascript:withdraw_notice_memo();'>
            <img src="<?=$memo_skin_path?>/img/bt04.gif" /></a>
            <? } ?>
            <? if ($kind=="trash" and $view[recover_href]) { ?>
            <a href='<?=$view[recover_href]?>'>
            <img src="<?=$memo_skin_path?>/img/icon_undelete.gif" /></a>
            <? } ?>
            &nbsp;
            </td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
  </td>
    
  <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_right.gif">&nbsp;</td>
</tr>

<tr>
  <td height="5" background="<?=$memo_skin_path?>/img/memo_box2_dl.gif"></td>
  <td background="<?=$memo_skin_path?>/img/memo_line2_down.gif"></td>
  <td width="5" background="<?=$memo_skin_path?>/img/memo_box2_dr.gif"></td>
</tr>
</table>
</form>

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
<script language="JavaScript">
window.onload=function() {
    resizeBoardImage(<?=(int)$max_img_width?>);
    drawFont();
}
</script>