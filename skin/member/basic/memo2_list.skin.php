<form name=fsearch method=get style="margin:0px;">
<input type='hidden' name='kind' value='<?=$kind?>'>

<table width="100%" height="30" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14">&nbsp;</td>
    <td>
        <span class="style5"><?=$memo_title?> ( <span class="style6"><? if ($kind == "recv") echo "<a href='$memo_url?kind=recv&unread=only'>$total_count_recv_unread</a> / "?><a href='<?=$memo_url?>?kind=$kind'><?=number_format($total_count)?></a></span> )</span>&nbsp<a href="<?=$memo_url?>?kind=<?=$kind?>&sfl=me_file&stx=me_file"><img src="<?=$memo_skin_path?>/img/icon_file.gif" align=absmiddle></a>
    </td>

    <!-- 검색하기 -->
    <td width="115" align="right"><span style="margin:0px;">
      <select name='sfl' id='sfl' class='small'>
            <option value="me_subject_memo">제목+내용</option>
            <option value="me_subject">제목</option>
            <option value="me_memo">내용</option>
        <? if ($kind == "recv" or $kind == "spam" or $kind == "notice") { ?>
            <option value="me_send_mb_nick">보낸<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_send_mb_id">보낸(아이디)</option>
        <? } else if ($kind == "send") { ?>
            <option value="me_recv_mb_nick">받은<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_recv_mb_id">받은(아이디)</option>
        <? } else if ($kind == "save" or $kind == "trash") { ?>
            <option value="me_send_mb_nick">받은<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_recv_mb_id">받은(아이디)</option>
            <option value="me_send_mb_nick">보낸<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_send_mb_id">보낸(아이디)</option>
        <? } ?>
      </select>
    </span>&nbsp;</td>
    <td width="105"><span style="margin:0px;">
        <input name="stx" type="text" class="ed" value='<?=$stx?>' maxlength=15 size="15" itemname="검색어" required />
    </span></td>
    <td width="50">
        <input type=image src="<?=$memo_skin_path?>/img/search.gif" border=0 align=absmiddle>
    </td>
  </tr>
</table>
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" height="5" background="<?=$memo_skin_path?>/img/memo_box2_tl.gif"></td>
    <td background="<?=$memo_skin_path?>/img/memo_line2_top.gif"></td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_box2_tr.gif"></td>
  </tr>
  <tr>
    <td background="<?=$memo_skin_path?>/img/memo_line2_left.gif">&nbsp;</td>
    <td align="center">
        <table width="98%" height="20" border="0" cellpadding="0" cellspacing="0">
        <tr align="center">
          <td width="35" align="center">
          <!-- 공지쪽지함은 삭제 선택이 없게... -->
          <? if ($kind != 'notice') { ?>
          <input name="chk_me_id_all" type="checkbox" onclick="if (this.checked) all_checked(true); else all_checked(false);" />
          <? } ?>
          </td>
          <td width="20"></td>
          <td width="110"><b>
            <?=$list_title ?>
          </b></td>
          <td ><b>제 목</b></td>
          <td width="60"><b>보낸시간</b></td>
          <td width="60"><b><? if ($kind == 'notice') {  if ($is_admin=='super' || $member[mb_id]==$view[me_send_mb_id]) { ?>수신레벨<? } } else { ?>받은시간<?}?></b></td>
        </tr>
      </table>
    <td background="<?=$memo_skin_path?>/img/memo_line2_right.gif">&nbsp;</td>
  </tr>
  <tr>
    <td height="2" bgcolor="d9d9d9" colspan=3></td>
  </tr>
</table>

<form name="fboardlist" method="post" style="margin:0px;">
<input type=hidden name=kind value="<?=$kind?>">

<table width="100%" border="0" cellspacing="0" cellpadding="0"> <!-- 목록 메인 테이블 -->
  <tr>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_left.gif">&nbsp;</td>
    <TD align="center">
      <? for ($i=0; $i<count($list); $i++) { // 목록을 출력 합니다. ?>
      <table width="98%" height="20" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="35" align="center">
            <!-- 공지쪽지함은 삭제 선택이 없게... -->
            <? if ($kind != 'notice') { ?>
            <input name="chk_me_id[]" type="checkbox" value="<?=$list[$i][me_id]?>" />
            <? } ?>
            </td>
            <? if ($list[$i][read_datetime] == '읽지 않음' or $list[$i][read_datetime] == '수신 않음') { ?>
                <td width="20" align="center"><img src="<?=$memo_skin_path?>/img/check.gif" width="13" height="12" /></td>
                <td width="110" align="center"><span class="style10"><?=$list[$i][name]?></span></td>
                <td align="left"><span class="style10"><? if ($list[$i][me_file]) { ?><img src="<?=$memo_skin_path?>/img/icon_file.gif" align=absmiddle>&nbsp;<? } ?><a href='<?=$list[$i][view_href]?>&page=<?=$page?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>'><?=cut_str($list[$i][subject],27)?></a></span></td>
                <td width="60" class="style10" align="center"><span class="style8"><?=$list[$i][send_datetime]?></span></td>
                <? if ($kind == 'notice') { if ($is_admin=='super' || $member[mb_id]==$view[me_send_mb_id]) $list[$i][read_datetime] = $list[$i][me_recv_mb_id]; else $list[$i][read_datetime] = ""; } ?>
                <td width="60" class="style10" align="center"><span class="style8"><?=$list[$i][read_datetime]?></span></td>
            <? } else { ?>
                <td width="20" align="center"><img src="<?=$memo_skin_path?>/img/nocheck.gif" width="12" height="10" /></td>
                <td width="110" align="center"><span class="style7"><?=$list[$i][name]?></span></td>
                <td align="left"><span class="style9"><? if ($list[$i][me_file]) { ?><img src="<?=$memo_skin_path?>/img/icon_file.gif" align=absmiddle>&nbsp;<? } ?><a href='<?=$list[$i][view_href]?>&page=<?=$page?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>'><?=cut_str(strip_tags($list[$i][subject]),27)?></a></span></td>
                <td width="60" class="style9" align="center"><span class="style8"><?=$list[$i][send_datetime]?></span></td>
                <td width="60" class="style9" align="center"><span class="style8"><?=$list[$i][read_datetime]?></span></td>
            <? } ?>
          </tr>
      </table>
      <? } ?>
      <? if ($i==0) { ?>
      <table width="98%" border="0" cellspacing="0" cellpadding="0">
          <tr><td align=center height=100>자료가 없습니다.
          </td></tr>
      </table>
      <? } ?>
    </td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_right.gif">&nbsp;</td>
  </tr>
</table>
</form>

<table height=40 width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_left.gif">&nbsp;</td>
      <td align=center valign=bottom>
      <table width="98%" height="25" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="10" align="center">
          <td width="250" valign="left">
          <? if ($i > 0 and $kind !='notice') { ?>
          <a href="javascript:select_delete();"><img src="<?=$memo_skin_path?>/img/bt02.gif" /></a>
          <? } ?>
          <? if ($i > 0 and $kind == "trash") { ?>
          <a href="javascript:all_delete_trash();"><img src="<?=$memo_skin_path?>/img/all_del.gif" /></a>
          <? } ?>
          </td>
          <td align="right" valign="right"><span style="font-size:10px; color:#888888;">
          </span></td>
          <td width="5" valign="middle">&nbsp;</td>
        </tr>
      </table></td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_right.gif">&nbsp;</td>
  </tr>
  <tr>
    <td height="2" bgcolor="d9d9d9" colspan=3></td>
  </tr>
</table>

<table width="100%" height="25" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_left.gif">&nbsp;</td>
     <td align="center" valign="middle">
           <? 
            $page = get_paging($config[cf_write_pages], $page, $total_page, "?&kind=$kind&sfl=$sfl&stx=$stx&unread=$unread&page="); 
            echo "&nbsp;$page";
            ?>
    </td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_right.gif">&nbsp;</td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
              <td width="5" height="5" background="<?=$memo_skin_path?>/img/memo_box2_dl.gif"></td>
              <td width=540 background="<?=$memo_skin_path?>/img/memo_line2_down.gif"></td>
              <td width="5" background="<?=$memo_skin_path?>/img/memo_box2_dr.gif"></td>
  </tr>
</table>  

<script type="text/javascript">
if ('<?=$stx?>') {
    document.fsearch.sfl.value = '<?=$sfl?>';
}
</script>