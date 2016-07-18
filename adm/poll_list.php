<?
$sub_menu = "200900";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[poll_table] ";

$sql_search = " where (1) ";
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

$g4[title] = "투표관리";
include_once("./admin.head.php");
?>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (투표수 : <?=number_format($total_count)?>개)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='po_subject'>제목</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=60>
<colgroup width=''>
<colgroup width=100>
<colgroup width=60>
<colgroup width=60>
<colgroup width=70>
<colgroup width=40>
<tr class="success">
	<td>번호</td>
	<td>제목</td>
	<td>투표권한</td>
	<td>투표수</td>
	<td>기타의견</td>
	<td>접근사용</td>
	<td><a href="./poll_form.php"><i class='fa fa-plus-square fa-2x' title='생성'></i></a></td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $sql2 = " select sum(po_cnt1+po_cnt2+po_cnt3+po_cnt4+po_cnt5+po_cnt6+po_cnt7+po_cnt8+po_cnt9) as sum_po_cnt from $g4[poll_table] where po_id = '$row[po_id]' ";
    $row2 = sql_fetch($sql2);
    $po_etc = ($row[po_etc]) ? "사용" : "<b>미사용</b>";
    $po_use_access = ($row[po_use_access]) ? "<b>사용</b>" : "미사용";
    
    $s_mod = "<a href='./poll_form.php?$qstr&w=u&po_id=$row[po_id]'><i class='fa fa-pencil' title='수정'></i></a>";
    $s_del = "<a href=\"javascript:post_delete('poll_form_update.php', '$row[po_id]');\"><i class='fa fa-trash-o' title='삭제'></i></a>";

    $list = $i%2;
?>
    <tr class='list<?=$list?> col1 ht center'>
        <td><?=$row[po_id]?></td>
        <td align=left>&nbsp;<a href="javascript:;" onclick="poll_result('<?=$row[po_id]?>','<?=$row[po_skin]?>');"><?=cut_str(get_text($row[po_subject]),70)?></a></td>
        <td><?=$row[po_level]?></td>
        <td><?=$row2[sum_po_cnt]?></td>
        <td><?=$po_etc?></td>
        <td><?=$po_use_access?></td>
        <td><?=$s_mod?>&nbsp;&nbsp;<?=$s_del?></td>
    </tr>
<?
}

if ($i==0) 
    echo "<tr><td colspan='7' height=100 align=center>자료가 없습니다.</td></tr>";

echo "</table>";
?>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<?
if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>

<script type="text/javascript">
    document.fsearch.stx.focus();
</script>

<script type="text/javascript">
// POST 방식으로 삭제
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        f.po_id.value = val;
		f.action      = action_url;
		f.submit();
	}
}

function poll_result(po_id, po_skin)
{    
    win_poll("<?=$g4[bbs_path]?>/poll_result.php?po_id="+po_id+"&skin_dir="+po_skin);
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='w'    value='d'>
<input type='hidden' name='po_id'>
</form>

<?
include_once ("./admin.tail.php");
?>
