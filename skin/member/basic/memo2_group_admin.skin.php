<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g4[title] = "메모그룹관리";

if (! $is_member) alert("회원만 접속할 수 있는 페이지 입니다");

// 그룹의 카운트 구하기
$mb_sql = " select count(*) as cnt from $g4[memo_group_table] where mb_id = '$member[mb_id]' ";
$result = sql_fetch($mb_sql);
$total_count = $result[cnt];

$one_rows = 10; // 한페이지의 라인수
$total_page = ceil($total_count / $one_rows);  // 전체 페이지 계산 
if ($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지) 
$from_record = ($page - 1) * $one_rows; // 시작 열을 구함
$to_record = $from_record + $one_rows ;

$sql = " select * from $g4[memo_group_table] where mb_id = '$member[mb_id]' order by gr_id desc limit $from_record, $one_rows"; 
$subj = "나의 메모그룹 목록";
$result = sql_query($sql);

$cols = 6; 
$gr_width = '100%'; // 그룹관리의 폭
$ss_id = 'gr_id'; // 직접 아이디를 지정하니까 오류가 생겨서 어쩔 수 없이... ㅠ..ㅠ
?>

<!-- 그룹관리 제목 -->
<table width="<?=$gr_width?>" height="30" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="14">&nbsp;</td>
        <td width="25"><img src="<?=$memo_skin_path?>/img/memo_icon06.gif"/></td>
        <td ><span class="style5"><a href="<?=$memo_url?>?kind=memo_group_admin">그룹관리</a></span> :: <?=$subj?> ::</td>
    </tr>
</table>

<table width="<?=$gr_width?>" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" height="5" background="<?=$memo_skin_path?>/img/memo_box2_tl.gif"></td>
    <td width="" background="<?=$memo_skin_path?>/img/memo_line2_top.gif"></td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_box2_tr.gif"></td>
  </tr>
  <tr>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_left.gif">&nbsp;</td>
    <td align="center">
      <table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="50">
                <font style="font-size:9pt;">
                내 메모그룹은 <b>( <a href='<?=$memo_url?>?kind=memo_group_admin'><?=$total_count?></a> )</b>
                </font></td>
      </tr>
      <tr>
          <td height="1"  bgcolor="#d9d9d9"></td>
      </tr>   
      </table>
      <table width="98%" border="0" cellpadding="0" cellspacing="0">
        <colgroup width="30">
        </colgroup>
<!--
        <colgroup width="40">
        </colgroup>
-->
        <colgroup width=''>
        </colgroup>
        <colgroup width="100">
        </colgroup>
        <colgroup width="100">
        </colgroup>
        <colgroup width="100">
        </colgroup>
        <tr>
          <td colspan="<?=$cols?>" height="2"></td>
        </tr>
        <form method="post" name="grouplist" id="grouplist">
          <input type="hidden" class="ed" name="gr_edit" id="gr_edit" value="<?=$gr_edit?>" />
          <tr>
            <td height="20"></td>
            <!--<td align="center"><strong>no</strong></td>-->
            <td align="left"><strong>그룹명</strong></td>
            <td align="center"><strong>멤버수</strong></td>
            <td align="center"><strong>보내기</strong></td>
            <td align="center"><strong>등록일</strong></td>
          </tr>
          <tr>
            <td height="3"></td>
          </tr>
          <tr>
            <td colspan="<?=$cols?>"  height="1" bgcolor="#d9d9d9"></td>
          </tr>
          <tr>
            <td height="7"></td>
          </tr>
          <tr>
          <?//출력
          for ($i=0; $row = sql_fetch_array($result); $i++) { // Join 또는 검색으로 하지 않고 개별로 member 정보를 fetch 하는 것은 효율 때문
          ?>
            <td >
              <input type="checkbox" name="chk_gr_id[]" value="<?=$row[gr_id]?>" />
            </td>
            <!--
            <td align="center"><span class="style7">
              <?=$row[gr_id]?>
            </span></td>
            -->
            <td align="left"><span class="style10">
                <a href="<?=$memo_url?>?kind=memo_group&gr_id=<?=$row[gr_id]?>">
                <?=get_text(stripslashes($row[gr_name]));?>
                </a>
                &nbsp;&nbsp;<a href="javascript:memo_box(<?=$row[gr_id]?>)"><img src='<?=$memo_skin_path?>/img/btn_c_modify.gif' border='0' align='absmiddle'></a>
                <span id='memo_<?=$row[gr_id]?>' style='display:none;'>
                <input type="type" class="ed" id="gr_edit_<?=$row[gr_id]?>" name="gr_edit_<?=$row[gr_id]?>" size="30" value="<?=preg_replace("/\"/", "&#034;", stripslashes(get_text($row[gr_name],0)))?>" />
                <a href="javascript:memo_update('<?=$row[gr_id]?>')"><img src='<?=$memo_skin_path?>/img/btn_c_ok.gif' border='0'/></a> </span> </td>
            <td align="center">
                <? 
                $sql1 = " select count(*) as cnt from $g4[memo_group_member_table] where gr_id = '$row[gr_id]' ";
                $result1 = sql_fetch($sql1);
                echo $result1[cnt];
                ?>
            </td>
            <td align="center"><span class="style7">
              <? if ($result1[cnt] > 0) { ?>
                <a href="<?=$memo_url?>?kind=write&gr_id=<?=$row[gr_id]?>">write</a>
              <? } ?>
            </span></td>
            <td align="center"><span class="style7">
              <?=cut_str($row[gr_datetime],10,'')?>
            </span></td>
          </tr>
          <tr>
            <td height="3"></td>
          </tr>
          <? } ?>
        </form>
        
        <tr>
          <td height="1" colspan="<?=$cols?>" bgcolor="#d9d9d9"></td>
        </tr>
        <tr>
          <td height="10" colspan="<?=$cols?>"></td>
        </tr>
        <? if ($total_page > 0) { ?>
        <tr>
          <td colspan="<?=$cols?>" >
          <?
            $page = get_paging($config[cf_write_pages], $page, $total_page, "?kind=memo_group_admin&page="); 
            echo "$page";
         ?>
          </td>
        </tr>
        <? } ?>
        <tr>
          <td height="25" colspan="<?=$cols?>"><a href="javascript:select_delete_gr();">그룹삭제</a> </td>
        </tr>
      </table></td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_line2_right.gif">&nbsp;</td>
  </tr>
  <tr>
    <td height="5" background="<?=$memo_skin_path?>/img/memo_box2_dl.gif"></td>
    <td background="<?=$memo_skin_path?>/img/memo_line2_down.gif"></td>
    <td background="<?=$memo_skin_path?>/img/memo_box2_dr.gif"></td>
  </tr>
</table>

<table width="<?=$gr_width?>" border="0" cellpadding="0" cellspacing="0" bgcolor="ededed"> 
  <tr> 
    <td height="10" colspan="3" bgcolor="#FFFFFF"></td> 
  </tr> 
  <tr> 
    <td width="5" height="5" background="<?=$memo_skin_path?>/img/memo_box4_tl.gif"></td> 
    <td width=""></td> 
    <td width="5" background="<?=$memo_skin_path?>/img/memo_box4_tr.gif"></td> 
  </tr> 
  <tr> 
    <td width="5">&nbsp;</td> 
    <td align="center"> 
<table width="<?=$gr_width?>" border="0" cellpadding="0" cellspacing="0" bgcolor="ededed"> 
  <tr> 
    <td height="20" colspan="2" class="style5" style="padding-left:10px;">새로운 그룹 등록하기</td> 
  </tr> 
      <tr> 
        <td colspan="4" height="1" bgcolor="#dcdcdc"></td> 
      </tr> 
      <tr> 
        <td height="10"></td> 
      </tr> 

  <form name="gr_register" action="javascript:gr_register_submit(document.gr_register);" method="post" enctype="multipart/form-data" autocomplete="off" > 
  <input type="hidden" class="ed" name="mb_id" value="<?=$member[mb_id]?>" /> 
  <tr> 
    <td align="left" style="padding-left:20px;">메모그룹 : &nbsp;<input name="gr_name" type="text" class="ed" itemname='메모그룹' size="45" /></td> 
    <td >&nbsp;<input type="submit" class="btn1" value='메모그룹등록' /></td> 
  </tr> 
  </form> 
</table></td> 
    <td width="5">&nbsp;</td> 
  </tr> 
  <tr> 
    <td height="5" background="<?=$memo_skin_path?>/img/memo_box4_dl.gif"></td> 
    <td></td> 
    <td background="<?=$memo_skin_path?>/img/memo_box4_dr.gif"></td> 
  </tr> 
</table> 

<!--
<table width="<?=$gr_width?>" border="0" cellpadding="0" cellspacing="0" bgcolor="ededed">
  <tr>
    <td height="10" colspan="3" bgcolor="#FFFFFF"></td>
  </tr>
  <tr>
    <td width="5" height="5" background="<?=$memo_skin_path?>/img/memo_box4_tl.gif"></td>
    <td width=""></td>
    <td width="5" background="<?=$memo_skin_path?>/img/memo_box4_tr.gif"></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="style5" style="padding-left:20px;">새로운 그룹 등록하기</td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>

  <form name="gr_register" action="javascript:gr_register_submit(document.gr_register);" method="post" enctype="multipart/form-data" autocomplete="off" >
  <input type="hidden" class="ed" name="mb_id" value="<?=$member[mb_id]?>" />
  <tr>
    <td align="left" style="padding-left:20px;">메모그룹 : &nbsp;<input name="gr_name" type="text" class="ed" itemname='메모그룹' size="45" /></td>
    <td >&nbsp;<input type="submit" class="btn1" value='메모그룹등록' /></td>
  </tr>
  </form>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td height="5" background="<?=$memo_skin_path?>/img/memo_box4_dl.gif"></td>
    <td></td>
    <td background="<?=$memo_skin_path?>/img/memo_box4_dr.gif"></td>
  </tr>
</table>
-->

<table>
  <tr>
    <td height="10"></td>
  </tr>
</table>

<script language="JavaScript">
function gr_register_submit(f)
{
    f.action = "<?=$memo_skin_path?>/memo2_group_update.php";
    f.submit();
}

var save_before = '';
function check_confirm_gr(str) {
    var f = document.grouplist;
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_gr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(str + "할 그룹을 한개 이상 선택하세요.");
        return false;
    }
    return true;
}

// 선택한 그룹 삭제
function select_delete_gr() {
    var f = document.grouplist;

    str = "삭제";
    if (!check_confirm_gr(str))
        return;

    if (!confirm("선택한 그룹을 정말 "+str+" 하시겠습니까?\n\n"))
        return;

    f.action = "<?=$memo_skin_path?>/memo2_group_delete.php";
    f.submit();
}

function memo_box(memo_id)
{
    var el_id= 'memo_' + memo_id;

    if (save_before != el_id) {
      
        if (save_before)
        {
            document.getElementById(save_before).style.display = 'none';
        }

        document.getElementById(el_id).style.display = 'block';
        save_before = el_id;
    }
}

// 선택한 메모를 업데이트
function memo_update(gr_id) {
    var f = document.grouplist;
    var el_id = 'gr_edit_' + gr_id;

    document.getElementById('gr_edit').value = document.getElementById(el_id).value;
    f.action = "<?=$memo_skin_path?>/memo2_group_name_update.php?gr_id=" + gr_id;
    f.submit();
}
</script>

<form method="post" name="fboardlist" id="fboardlist">
</form>

