<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// resize를 위한 넓이를 지정
$board[resize_img_width] = $max_img_width;
?>

<script src="<?=$g4[cheditor4_path]?>/imageUtil.js"></script> 
<link rel="stylesheet" href="<?=$g4[cheditor4_path]?>/imageUtil.css" type="text/css">

<script> 
function print_contents(print_id) 
{ 
var contents = "";
contents += "<html><head><meta http-equiv='content-type' content='text/html; charset=<?=$g4[charset]?>'>";
contents += "<title><?=$g4[title]?></title>";
contents += "<link rel='stylesheet' href='<?=$g4[path]?>/style.css' type='text/css'>";
contents += "</head>";
contents += "<body>";
contents += "<link rel='stylesheet' href='<?=$member_skin_path?>/memo4_style.css' type='text/css'>";
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
    <div class="top">
        <div class="right">
        <a href="<?=$memo_url?>?kind=<?=$kind?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>&page=<?=$page?>">목록</a>
        <? if ($config[cf_memo_print]) { ?><a href="#" onclick="javascript:print_contents('memo_contents')">프린트</a><? } ?>
        <? if ($view[after_href]) { ?><a href="<?=$view[after_href]?>">다음</a><? } ?>
        <? if ($view[before_href]) { ?><a href="<?=$view[before_href]?>">이전</a><? } ?>
        </div>
        <span class="view">
        <?=$memo_title?> - 쪽지보기
    </span></div>

    <form name="fboardlist" id="fboardlist" method="post">
    <input type="hidden" name="kind" value="<?=$kind?>" />
    <input type="hidden" name="me_id" value="<?=$me_id?>" />
    <div class="inner inner_view">
        <ul>
            <li>
                <label>제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</label>
                <div><?=$view[me_subject]?></div>
            </li>
            <li>
                <label>보낸사람</label>
                <div><?=$view[me_send_mb_id_nick]?> (<?=$view[me_send_datetime]?>)</div>
            </li>
          <? if ($kind == 'notice') { ?>
            <li>
                <label>안읽은사람</label>
<?
$sql = " select count(*) as cnt from $g4[memo_recv_table] where me_send_datetime = '$view[me_send_datetime]' and me_send_mb_id = '$member[mb_id]' and me_read_datetime = '0000-00-00 00:00:00' ";
$result = sql_fetch($sql);
$memo_notice_unread = $result[cnt];
?>
                <div><?=number_format($memo_notice_unread)?>명</div>
            </li>
          <? } else { ?>
            <li>
                <label>받는사람</label>
                <div><?=$view[me_recv_mb_id_nick]?> (<?=$view[me_read_datetime]?>)</div>
            </li><? } ?>
          <? if ($view[me_file_local] && !$view[imagesize]) { ?>
            <li>
                <label>첨부파일</label>
                <div><a href="javascript:file_download()" title="<?=$view[me_file_local]?>"><?=$view[me_file_local]?></a></div>
            </li><? } ?>
        </ul>
        <div class="content">
          <? if ($view[me_file_local] && $view[imagesize]) { ?>
            <div class="image">
                <? echo resize_content("<img src='$g4[path]/data/memo2/$view[me_file_server]' name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' />"); ?>
            </div><? } ?>
            <?=resize_content($view[memo])?>
        </div>
      <? $mb = get_member($view[me_send_mb_id], "mb_signature"); $signature = $mb[mb_signature]; if ($signature) { ?>
        <div class="sign"><?=$signature;?></div><? } ?>
        <div class="btn">
            <span class="right">
              <? if ($kind=="spam" && $view[spam_href]) { ?>
                <a href="<?=$view[spam_href]?>"><img src="<?=$member_skin_path?>/img/spam_cancel.gif" /></a>&nbsp;<? } ?>
              <? if ($kind=="spam" && $is_admin == "super") { ?>
                <a href="javascript:all_cancel_spam();"><img src="<?=$member_skin_path?>/img/spam_cancel_all.gif" /></a>&nbsp;<? } ?>
              <? if ($kind=="recv") { ?>
                <a href="<?=$view[spam_href]?>"><img src="<?=$member_skin_path?>/img/bt03.gif" /></a>&nbsp;<? } ?>
              <? if ($kind=="send" and $view[me_read_datetime] == "읽지 않음") { ?>
                <a href="<?=$view[cancel_href]?>"><img src="<?=$member_skin_path?>/img/icon_cancel.gif" /></a>&nbsp;<? } ?>
              <? if ($kind=="recv" or $kind=="send") { ?>
                <a href="<?=$view[save_href]?>"><img src="<?=$member_skin_path?>/img/save.gif" /></a>&nbsp;<? } ?>
              <? if ($kind=="recv" or $kind=="send" or $kind=="save" or $kind=="spam") { ?>
                <a href="<?=$view[del_href]?>"><img src="<?=$member_skin_path?>/img/bt02.gif" /></a><? } ?>
              <? if ($kind=="notice" and ($is_admin or $view[me_send_mb_id]==$member[mb_id])) { ?>
                <a href="<?=$view[del_href]?>"><img src="<?=$member_skin_path?>/img/bt02.gif" /></a><? } ?>
              <? if ($kind=="trash" and $view[recover_href]) { ?>
                <a href="<?=$view[recover_href]?>"><img src="<?=$member_skin_path?>/img/icon_cancel.gif" /></a><? } ?>
            </span>
            <? if ($kind=="recv" or ($kind=="save" and $class=="view")) { ?>
            <a href="<?=$memo_url?>?kind=write&me_recv_mb_id=<?=$view[me_send_mb_id]?>&me_id=<?=$me_id?>&me_box=<?=$kind?>"><img src="<?=$member_skin_path?>/img/reply.gif" /></a>&nbsp;<? } ?>
            <a href="<?=$memo_url?>?kind=<?=$kind?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>&page=<?=$page?>"><img src="<?=$member_skin_path?>/img/list.gif" /></a>
        </div>
    </div>
    </form>

<script language="JavaScript">
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
</script>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script language="JavaScript">
window.onload=function() {
    resizeBoardImage(<?=(int)$max_img_width?>);
    drawFont();
}
</script>
