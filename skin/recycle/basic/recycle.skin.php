<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<script type="text/javascript" src="<?=$g4[path]?>/js/sideview.js"></script>
<script language="JavaScript">
var list_delete_php = "recycle_list_delete.php";
</script>

<script type="text/javascript">
function recycle_delete(ok)
{
    var msg;

    if (ok == 1)
        msg = "<?=$config[cf_recycle_days]?>일이 지난 휴지글을 완전히 삭제합니다.\n\n\n그래도 진행하시겠습니까?";
    else
        msg = "<?=$config[cf_recycle_days]?>일이 지난 휴지글을 삭제합니다.\n\n\n그래도 진행하시겠습니까?";

    if (confirm(msg)) {
        document.location.href = "<?=$g4[admin_path]?>/recycle_delete.php?ok=" + ok;
    }
}
</script>

<form name=fsearch method=get role="form" class="form-inline" >
<a class="btn btn-default" href="<?=$_SERVER[PHP_SELF]?>">처음</a>
(휴지글수 : <?=number_format($total_count)?>, 삭제글수 : <?=number_format($delete_count)?>)
<div class="pull-right">
    <select class="form-control" name=sfl class=cssfl>
        <option value='bo_table'>게시판</option>
    </select>
    <div class="form-group">
        <input class="form-control" type=text name=stx required itemname='검색어' value='<? echo $stx ?>'>
    </div>
    <input class="btn btn-default" type=submit value='검색'>
</div>
</form>

<form name=fmemberlist method=post>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-hover table-condensed">
<tr class="success" >
    <td><?=subject_sort_link('mb_id')?>글쓴이</a></td>
    <td><?=subject_sort_link('bo_table')?>게시판</a></td>
    <td>(wr_id) 게시글제목</td>
    <td>작성일</td>
    <td><?=subject_sort_link('rc_datetime', '', 'desc')?>삭제일</a></td>
  	<td>복구</td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    
    $mb = get_member($row[mb_id]);
    $mb_nick = get_sideview($mb[mb_id], $mb[mb_nick], $mb[mb_email], $mb[mb_homepage]);    

    // 게시글 제목
    $tmp_write_table = $g4['write_prefix'] . $row[rc_bo_table];
    $sql2 = " select wr_subject, wr_content, wr_datetime from $tmp_write_table where wr_id = '$row[rc_wr_id]' ";
    $write = sql_fetch($sql2);

    // 코멘트인지 여부
    $c_flag="";
    if ($row[wr_is_comment])
        $c_flag = " C";
    
    // wr_id
    if ($c_flag)
        $wr_id = $row[wr_id] . $c_flag;
    else
        $wr_id = "<a href='$g4[bbs_path]/recycle_view.php?bo_table=$row[rc_bo_table]&wr_id=$row[rc_wr_id]&org_bo_table=$row[bo_table]' target=_blank>" . $row[wr_id] . "</a>";

    // 복구 버튼을 출력
    if ($row[rc_delete] == 0)
        $s_recover = "<a href=\"javascript:post_recover('$g4[bbs_path]/recycle_recover.php', '$row[rc_no]');\"><i class=\"fa fa-undo\"></i></a>";
    else
        $s_recover = "";

    // 운영자가 삭제한거 (mb_id와 rc_mb_id가 다른 경우)에는 뒤에 mark
    $mb_remover="";
    if ($row[mb_id] !== $row[rc_mb_id])
        $mb_remover="&nbsp;<img src='$g4[admin_path]/img/icon_admin.gif' align=absmiddle border=0 title='관리자가 지워버린 글'>";

    // 게시판아이디. 게시판 정렬
    $bo_info = get_board($row[bo_table],"bo_subject");
    $bo_table1 = "<a href='$g4[bbs_path]/recycle_list.php?sfl=bo_table&stx=$row[bo_table]' title='$bo_info[bo_subject]'>$row[bo_table]</a>";

    $list = $i%2;
    echo "
    <input type=hidden name=rc_no[$i] value='$row[rc_no]'>
    <tr class='list$list col1 ht center'>
        <td title='$row[mb_id]'>$mb_nick$mb_remover</td>
        <td>$bo_table1</td>
        <td>($wr_id) " . conv_subject($write[wr_subject],80) . "</td>
        <td>" . get_datetime($write[wr_datetime]) . "</td>
        <td>" . get_datetime($row[rc_datetime]) . "</td>
        <td>$s_recover</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan=6 align=center height=100 class=contentbg>자료가 없습니다.</td></tr>";

echo "</table>";

if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>
<div class="center-block">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>이전검색</a></li>"; } ?>
    <?
    // 기본으로 넘어오는 페이지를 아래와 같이 변환하여 다양하게 출력할 수 있습니다.
    $write_pages = str_replace("이전", "<i class='fa fa-angle-left'></i>", $write_pages);
    $write_pages = str_replace("다음", "<i class='fa fa-angle-right'></i>", $write_pages);
    $write_pages = str_replace("처음", "<i class='fa fa-angle-double-left'></i>", $write_pages);
    $write_pages = str_replace("맨끝", "<i class='fa fa-angle-double-right'></i>", $write_pages);
    ?>
    <?=$write_pages?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>이후검색</a></li>"; } ?>
    </ul>
</div>
</form>

<ul>
<li>DB에서 완전히 게시글 삭제를 하기를 원하는 경우 관리자에게 문의하시기 바랍니다.</li>
<li>회원아이디 옆에 아이콘이 있는 글은, 사용자가 삭제한 것이 아니라 관리자가 삭제한 글 입니다.</li>
<li>게시판을 클릭하면 해당 게시판의 삭제글이 정렬되며, 게시글 id를 클릭하면 해당 게시글의 새창이 뜹니다.</li>
</ul>

<script type="text/javascript">
// POST 방식으로 삭제
function post_recover(action_url, val)
{
	var f = document.fpost;

	if(confirm("선택한 자료를 복구 합니다.\n\n정말 복구하시겠습니까?")) {
        f.rc_no.value = val;
		f.action      = action_url;
		f.submit();
	}
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='rc_no'>
</form>