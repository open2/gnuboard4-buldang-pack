<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<script type="text/javascript" src="<?=$g4[path]?>/js/sideview.js"></script>

<form name=fsearch method=get role="form" class="form-inline">
<a class="btn btn-default" href='<?=$_SERVER[PHP_SELF]?>'>처음</a>
(신고된 게시물 : <?=number_format($total_count)?>)
<div class="pull-right">
    <select class="form-control" name=sfl class=cssfl>
        <option value='mb_id'>신고된 회원아이디</option>
        <option value='sg_reason'>신고한 이유</option>
    </select>
    <div class="form-group">
        <input class="form-control" type=text name=stx required itemname='검색어' value='<? echo $stx ?>'>
    </div>
    <input class="btn btn-default" type=submit value='검색'>
</div>
</form>

<form name=fsingolist method=post style="margin:0px;">
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>

<table width=100% class="table table-hover table-condensed">
<tr class="success">
    <td align='center'><?=subject_sort_link('sg_id')?>no.</a></td>
    <td class="col-sm-2" align='center'><?=subject_sort_link('mb_id')?>신고된 회원</a></td>
    <td align='left'>제목</td>
    <td>날짜</td>
    <td><?=subject_sort_link('sg_datetime')?>신고날짜</a></td>
    <td class="col-sm-2" align='left'><?=subject_sort_link('sg_reason')?>신고사유</a></td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $mb = array();
    $sg_mb = array();

    if ($row[mb_id]) {
        $mb = get_member($row[mb_id], "mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date");
        $mb_nick = get_sideview($mb[mb_id], $mb[mb_nick], $mb[mb_email], $mb[mb_homepage]);
    } else 
        $mb_nick = "<span style='color:#222222;'>비회원</a>";

    if ($row[sg_mb_id]) {
        $sg_mb = sql_fetch(" select mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date from $g4[member_table] where mb_id = '$row[sg_mb_id]' ");
        $sg_mb_nick = get_sideview($sg_mb[mb_id], $sg_mb[mb_nick], $sg_mb[mb_email], $sg_mb[mb_homepage]);
        $sg_mb_nick = str_replace(" class='member'", " class='member' style='color:#C15B27;'", $sg_mb_nick);
    } else
        $sg_mb_nick = "<span style='color:#C15B27;'>비회원</a>";

    $write_table = $g4['write_prefix'].$row[bo_table];
    $sql = " select wr_subject, wr_ip, wr_is_comment, wr_parent, wr_datetime from $write_table where wr_id = '$row[wr_id]' ";
    $write_row = sql_fetch($sql);
    if ($write_row[wr_is_comment]) {
        $sql = " select wr_subject, wr_ip, wr_datetime from $write_table where wr_id = '$write_row[wr_parent]' ";
        $parent_row = sql_fetch($sql);
        $wr_subject = "[코] ".$parent_row[wr_subject];
        $wr_ip = $parent_row[wr_ip];
        $wr_datetime = $parent_row[wr_datetime];
    } else {
        $wr_subject = $write_row[wr_subject];
        $wr_ip = $write_row[wr_ip];
        $wr_datetime = $write_row[wr_datetime];
    }

    $wr_subject = get_text($wr_subject);

    if (!$is_admin && $member[mb_id] != $row[mb_id]) $row[sg_reason] = "";

    if ($is_admin || $member['mb_id'] == $row['sg_mb_id'])
        $delete_link = " <a class=\"btn btn-default btn-xs\" onClick=\"del('./singo_search_delete.php?sg_id= " . $row[sg_id] . "')\"><i class=\"fa fa-undo\"></i></a>";

    echo "
    <tr class='list$list col1 center' height=25>
        <td align='center'>$row[sg_id]</td>
        <td title='$row[mb_id]' align='center'>$mb_nick</td>
        <td align=left style='padding:0 5px 0 5px;'>
            <a href='$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]' target='_blank'>
                <span style='color:#555555;'>$wr_subject</span></a></td>
        <td>" . get_date($wr_datetime) . "</td>
        <td>" . get_date($row[sg_datetime]) . "</td>
        <td align=left>". get_text($row[sg_reason])."$delete_link</td>
    </tr>
    ";
}

if ($i == 0)
    echo "<tr><td colspan='5' align=center height=100>내역이 없습니다.</td></tr>";

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

<ul class="well">
<li>신고사유는 본인만 확인할 수 있습니다.</li>
<li>신고된 글을 삭제하는 경우 관련 신고 카운터는 없어집니다.</li>
<li>신고글에 대한 소명 또는 삭제가 필요한 경우 운영자에게 문의하세요.</li>
</ul>