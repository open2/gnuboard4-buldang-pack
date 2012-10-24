<?
include_once("./_common.php");

$g4[title] = "투표 목록";
include_once("./_head.php");

$sql_common = " from $g4[poll_table] ";

$sql_search = " where (po_use_access = 0) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default : 
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "po_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search 
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page == "") $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$colspan = 7;
?>

<link rel="stylesheet" href="<?=$g4['admin_path']?>/admin.style.css" type="text/css">

<table width=100%>
<form name=fsearch method=get>
<tr>
    <td width=50% align=left><?=$listall?> (투표수 : <?=number_format($total_count)?>개)</td>
    <td width=50% align=right>
        <select name=sfl>
            <option value='po_subject'>제목</option>
        </select>
        <input type=text name=stx class=ed required itemname='검색어' value='<?=$stx?>'>
        <input type=image src='<?=$g4[admin_path]?>/img/btn_search.gif' align=absmiddle></td>
</tr>
</form>
</table>

<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=60>
<colgroup width=''>
<colgroup width=160>
<colgroup width=60>
<colgroup width=70>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
	<td>번호</td>
	<td>제목</td>
	<td>기    간</td>
	<td>투표수</td>
	<td><a href="./poll_form.php"><img src='<?=$g4[admin_path]?>/img/icon_insert.gif' border=0 title='생성'></a></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $sql2 = " select sum(po_cnt1+po_cnt2+po_cnt3+po_cnt4+po_cnt5+po_cnt6+po_cnt7+po_cnt8+po_cnt9) as sum_po_cnt from $g4[poll_table] where po_id = '$row[po_id]' ";
    $row2 = sql_fetch($sql2);
    
    $s_mod = "<a href='./poll_form.php?$qstr&w=u&po_id=$row[po_id]'><img src='$g4[admin_path]/img/icon_modify.gif' align=absmiddle border=0 title='수정'></a>";
    //$s_del = "<a href=\"javascript:del('./poll_form_update.php?$qstr&w=d&po_id=$row[po_id]');\"><img src='img/icon_delete.gif' border=0 title='삭제'></a>";
    $s_del = "<a href=\"javascript:post_delete('poll_form_update.php', '$row[po_id]');\"><img src='$g4[admin_path]/img/icon_delete.gif' align=absmiddle border=0 title='삭제'></a>";

    $list = $i%2;
    
    if ($row[po_end_date] == '0000-00-00')
        $row[po_end_date] = "진행중";
?>
    <tr class='list<?=$list?> col1 ht center'>
        <td><?=$total_count - $i?></td>
        <td align=left>&nbsp;<a href="javascript:;" onclick="poll_result('<?=$row[po_id]?>','<?=$row[po_skin]?>');"><?=cut_str(get_text($row[po_subject]),70)?></a>
        </td>
        <td align=left><?=$row[po_date]?> ~ <?=$row[po_end_date]?></td>
        <td><?=$row2[sum_po_cnt]?></td>
        <td>
        <? if (($member[mb_id] && $member[mb_id] == $row[mb_id]) || $is_admin) { echo $s_mod . " " . $s_del; } ?>
        </td>
    </tr>
<?
}

if ($i==0) 
    echo "<tr><td colspan='$colspan' height=100 align=center bgcolor='#FFFFFF'>자료가 없습니다.</td></tr>";

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");
if ($pagelist)
    echo "<table width=100% cellpadding=3 cellspacing=1><tr><td align=right>$pagelist</td></tr></table>\n";

if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>

<script language='javascript'>
    document.fsearch.stx.focus();
</script>

<script>
function poll_result(po_id, po_skin)
{    
    win_poll("<?=$g4[bbs_path]?>/poll_result.php?po_id="+po_id+"&skin_dir="+po_skin);
}
</script>

<?
include_once ("./_tail.php");
?>
