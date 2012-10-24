<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// bbs/write.php의 파일에서 관련 내용을 이동 -------------

if (!$board[bo_table])
{
    if ($cwin) // 코멘트 보기
       alert_close("존재하지 않는 게시판입니다.", $g4[path]);
    else
       alert("존재하지 않는 게시판입니다.", $g4[path]);
}

if (!$bo_table) 
    alert("bo_table 값이 넘어오지 않았습니다.\\n\\nwrite.php?bo_table=code 와 같은 방식으로 넘겨 주세요.", $g4[path]);

$notice_array = explode("\n", trim($board[bo_notice]));

if ($w == "") 
{
 	$is_name = false;
	$is_password = false;

	if (!$member[mb_id] || ($is_admin && $w == 'u' && $member[mb_id] != $write[mb_id])) {
		$is_name = true;
    $is_password = true;
	}

  $password_required = "required";
 	$content = $board[bo_insert_content];
  	
} 

if (!$member[mb_id]) 
    echo "<script language='javascript' src='$g4[path]/js/md5.js'></script>\n";

// 필터
echo "<script language='javascript'> var g4_cf_filter = '$config[cf_filter]'; </script>\n";
echo "<script language='javascript' src='$g4[path]/js/filter.js'></script>\n";

// --------------------

// 기본 이모티콘을 설정
if (!$subject) $subject = 1;

// 스킨에서 사용하는 lib 읽어들이기
include_once("$g4[path]/lib/write.skin.lib.php");
?>

<script language="JavaScript">
function show() { 
    	document.getElementById('emoticon').style.visibility = "visible" ;
}

function hide() { 
	document.getElementById('emoticon').style.visibility = "hidden" ;
} 

function insertSmiley(emo){
	var skin_path = "<?=$board_skin_path?>";
	if(document.fwrite) {
		document.fwrite.mainimage.src = skin_path + "/emoticons/" + emo + ".gif";
		document.fwrite.wr_subject.value = emo;
		hide();
	}
}
</script>

<form name="fwrite" method="post" action="javascript:fwrite_check(document.fwrite);" enctype="multipart/form-data" style="margin:0px;" autocomplete="off">
<input type=hidden name=null> 
<input type=hidden name=w        value="<?=$w?>">
<input type=hidden name=bo_table value="<?=$bo_table?>">
<input type=hidden name=wr_id    value="<?=$wr_id?>">
<input type=hidden name=sca      value="<?=$sca?>">
<input type=hidden name=sfl      value="<?=$sfl?>">
<input type=hidden name=stx      value="<?=$stx?>">
<input type=hidden name=spt      value="<?=$spt?>">
<input type=hidden name=sst      value="<?=$sst?>">
<input type=hidden name=sod      value="<?=$sod?>">
<input type=hidden name=page     value="<?=$page?>">
<input type=hidden name=wr_subject value="<?=$subject?>">
<input type=hidden name='wr_1' value="<?=$write[wr_1]?>">
<input type=hidden name=mnb      value="<?=$mnb?>">
<input type=hidden name=snb      value="<?=$snb?>">

<script language=javascript>
function getFontcolor(color)
{
	var icon = "";
	icon += "<table cellpadding=0 cellspacing=0 border=0>";
	icon += "<tr><td width=40 style='padding-top:3px;'>글자색</td>";

	for(var i = 0; i < 8; i++)
	{
		icon += "<td ID='Color_td_"+i+"' style='border:1 solid "+color+";' onclick=\"getSelectImg("+i+",'"+color+"')\"><img ID='Color_img_"+i+"' src='<?=$board_skin_path?>/images/p0"+i+".gif' width=15 height=15 border=0 alt='선택' align=absmiddle style='cursor:hand;' hspace=1 vspace=1></td>";
	}
	icon += "</tr>";
	icon += "</table>";
	document.write(icon);
}

function getSelectImg(g,color)
{

	for(var i = 0; i < 8; i++)
	{
		if(i == g)
		{
			document.getElementById('Color_td_' + i).style.border = '1 solid #FFC286';
			document.getElementById('Color_td_' + i).style.background = '#ffffff';
			document.getElementById('Color_img_' + i).style.filter = 'none';
			document.fwrite.wr_1.value = i;
		}
		else {
			document.getElementById('Color_td_' + i).style.border = '1 solid '+color;
			document.getElementById('Color_td_' + i).style.background = color;
			document.getElementById('Color_img_' + i).style.filter = 'none';
		}
	}
	
	document.getElementById('wr_content').style.color = '#000000';
	if (g==1) document.getElementById('wr_content').style.color = '#ff0000';
	if (g==2) document.getElementById('wr_content').style.color = '#E9A252';
	if (g==3) document.getElementById('wr_content').style.color = '#C1CE1E';
	if (g==4) document.getElementById('wr_content').style.color = '#2DC501';
	if (g==5) document.getElementById('wr_content').style.color = '#01B6C5';
	if (g==6) document.getElementById('wr_content').style.color = '#8CABEC';
	if (g==7) document.getElementById('wr_content').style.color = '#8000FF';
}
</script>

<? if ($member[mb_level] >= $board[bo_write_level]) { ?>

<table width="<?=$width?>" align=center cellpadding=0 cellspacing=0><tr><td>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<colgroup width=100>
<colgroup width=''>
<? if ($is_name) { ?>
<tr>
    <td style='padding-left:20px; height:30px;'>· 이름</td>
    <td><input class='field_pub_01' maxlength=20 size=15 name=wr_name itemname="이름" required value="<?=$name?>"></td></tr>
<tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
<? } ?>

<? if ($is_password) { ?>
<tr>
    <td style='padding-left:20px; height:30px;'>· 패스워드</td>
    <td><input class='field_pub_01' type=password maxlength=20 size=15 name=wr_password itemname="패스워드" <?=$password_required?>></td></tr>
<tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
<? } ?>

<? if ($is_email) { ?>
<tr>
    <td style='padding-left:20px; height:30px;'>· 이메일</td>
    <td><input class='field_pub_01' maxlength=100 size=50 name=wr_email email itemname="이메일" value="<?=$email?>"></td></tr>
<tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
<? } ?>

<? if ($is_homepage) { ?>
<tr>
    <td style='padding-left:20px; height:30px;'>· 홈페이지</td>
    <td><input class='field_pub_01' size=50 name=wr_homepage itemname="홈페이지" value="<?=$homepage?>"></td></tr>
<tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
<? } ?>

<? if ($is_notice || $is_secret || $is_mail) { ?>
<tr>
    <td style='padding-left:20px; height:30px;'>· 옵션</td>
    <td><? if ($is_notice) { ?><input type=checkbox name=notice value="1" <?=$notice_checked?>>공지&nbsp;<? } ?>
        <? if ($is_secret) { ?>
            <? if ($is_admin || $is_secret==1) { ?>
            <input type=checkbox value="secret" name="secret" <?=$secret_checked?>><span class=w_title>비밀글</span>&nbsp;
            <? } else { ?>
            <input type=hidden value="secret" name="secret">
            <? } ?>
        <? } ?>
        <? if ($is_mail) { ?><input type=checkbox value="mail" name="mail" <?=$recv_email_checked?>>답변메일받기&nbsp;<? } ?></td></tr>
<tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
<? } ?>

<? if ($is_guest) { ?>
<tr>
    <td  class=write_head>
    <input type=hidden name=zsfCodeResult id="zsfCodeResult" value="" >
    <script type="text/javascript" src="<?="$g4[path]/zmSpamFree/zmspamfree.js"?>"></script>
    <img align=absmiddle src="<?=$g4[path]?>/zmSpamFree/zmSpamFree.php?zsfimg=<?php echo time();?>" id="zsfImg" alt="여기를 클릭해 주세요." title="클릭하시면 다른 문제로 바뀝니다. SpamFree.kr" onclick="this.src='<?=$g4[path]?>/zmSpamFree/zmSpamFree.php?re&zsfimg=' + new Date().getTime();" />&nbsp;
    </td>
    <td><input class='ed' type=input size=10 name=wr_key id=wr_key itemname="자동등록방지" required onblur="checkZsfCode(this);">&nbsp;&nbsp;왼쪽의 글자를 입력하세요.</td>
</tr>
<tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
<? } ?>
</table>

<table width="<?=$width?>" align=center cellpadding=0 cellspacing=0><tr><td>
<tr>
    <td colspan=2 ><script>getFontcolor('#EAF0F4');</script>
</td></tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="60"> 
    <div onClick="show()" style="background:url(<?=$board_skin_path?>/img/emo_bg.gif) no-repeat center middle; height:25px; padding-top:3px;"> 
&nbsp;&nbsp;<img name="mainimage" border=0 src='<?=$board_skin_path?>/emoticons/<?=$subject?>.gif'> 
    </div> 

	<div id="emoticon" onmouseover="show();" onMouseOut="hide();" style="position:absolute; z-index:0; visibility:hidden; padding-top:3px; width:220; height:80;">
	<table border=0 width=100% cellpadding=1 cellspacing=0 style="background-color:white;border:1px solid #cdcdcd;">
	<? 
	for($k=1; $k<=44; $k++){
		$list_emo = ($k-1) % 11;

		if(!$list_emo) echo "\n<tr>";
		echo "<td width=20><a onclick=insertSmiley('$k')><img src='$board_skin_path/emoticons/$k.gif' width=18 height=18 border=0></a></td>";
	} 
	if(!$list_emo) echo "\n<td>&nbsp;</td></tr>";
	?>
	</table>
	</div>

	</td>
  <td style='padding:5 0 5 0;'>
      &nbsp;<textarea id="wr_content" name="wr_content" class=tx style='width:90%; word-break:break-all;' rows=2 itemname="내용" required><?=$content?></textarea>
      <input type=image id="btn_submit" src="<?=$board_skin_path?>/img/btn_write.gif" border=0 accesskey='s' align=absmiddle >&nbsp;
  </td>
</tr>
</table>

</td></tr></table>
</form>

<? } ?>

<input type='hidden' name='bf_file[]'>

<script language="javascript">

with (document.fwrite) {
    if (typeof(wr_name) != "undefined")
        wr_name.focus();
    else if (typeof(wr_content) != "undefined")
        wr_content.focus();
}

function html_auto_br(obj) {
    if (obj.checked) {
        result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
        if (result)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}

function fwrite_check(f) {
    var s = "";

    if (s = word_filter_check(f.wr_content.value)) {
        alert("내용에 금지단어('"+s+"')가 포함되어있습니다");
        return;
    }

    if (typeof(f.wr_key) != "undefined") {
        if (!checkFrm()) {
            return false;
        }
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/write_update.php';";
    else
        echo "f.action = './write_update.php';";
    ?>
    f.submit();
}
</script>

<script type="text/javascript" src="<?="$g4[path]/js/board.js"?>"></script>
<script language="JavaScript">
window.onload=function() {
    drawFont();
}
</script>
