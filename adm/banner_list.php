<?
$sub_menu = "300300";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[banner_table] a ";
$sql_search = " where (1) ";

if ($is_admin != "super") {
    $sql_common .= " , $g4[banner_group_table] b ";
    $sql_search .= " and (a.bg_id = b.bg_id and b.bg_admin = '$member[mb_id]') ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bn_id" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.bg_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default : 
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.bg_id, a.bn_id";
    $sod = "asc";
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
if ($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$listall = "<a href='$_SERVER[PHP_SELF]'>처음</a>";

$g4[title] = "배너관리";
include_once("./admin.head.php");

include_once ("$g4[path]/lib/banner.lib.php");
?>

<script type="text/javascript">
var list_update_php = 'banner_list_update.php';
var list_delete_php = 'banner_list_delete.php';
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (배너수 : <?=number_format($total_count)?>개)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='bn_id'>배너ID</option>
        <option value='bn_subject'>제목</option>
        <option value='a.bg_id'>그룹ID</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fbannerlist method=post role="form" class="form-inline">
<input type=hidden name=sst   value="<?=$sst?>">
<input type=hidden name=sod   value="<?=$sod?>">
<input type=hidden name=sfl   value="<?=$sfl?>">
<input type=hidden name=stx   value="<?=$stx?>">
<input type=hidden name=page  value="<?=$page?>">
<input type=hidden name=token value="<?=$token?>">

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=100>
<colgroup width=100>
<colgroup width=''>
<colgroup width=120>
<colgroup width=55>
<colgroup width=35>
<colgroup width=35>
<tr class="success">
    <td rowspan=2><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <td ><?=subject_sort_link("bn_id")?>배너ID</a></td>
    <td ><?=subject_sort_link("a.bg_id")?>그룹</a></td>
    <td ><?=subject_sort_link("bn_subject")?>제목</a></td>
    <td >시작일</td>
    <td rowspan=2 title="배너사용"><?=subject_sort_link("bn_use")?>배너<br>사용</a></td>
    <td rowspan=2 title="배너순서"><?=subject_sort_link("bn_order")?>배너<br>순서</a></td>
  	<td rowspan=2><a href="./banner_form.php"><i class='fa fa-plus-square fa-2x' title='생성'></i></a></td>
</tr>
<tr class="success">
    <td>클릭수</td>
    <td>Target(새창)</td>
    <td>URL</td>
    <td>종료일</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $s_upd = "<a href='./banner_form.php?w=u&bn_id=$row[bn_id]&$qstr'><i class='fa fa-pencil' title='수정'></i></a>";
    $s_del = "";
    if ($is_admin == "super") {
        $s_del = "<a href=\"javascript:post_delete('banner_delete.php', '$row[bn_id]');\"><i class='fa fa-trash-o' title='삭제'></i></a>";
    }

    $sql = " select count(*) as cnt from $g4[banner_click_table] where bg_id='$row[bg_id]' and bn_id='$row[bn_id]' ";
    $tmp = sql_fetch($sql);

    echo "<input type=hidden name=bn_id[$i] value='$row[bn_id]'>";
    echo "<tr>";
    echo "<td rowspan=2 height=25><input type=checkbox name=chk[] value='$i'></td>";
    echo "<td><a href='$g4[data_path]/banner/$row[bg_id]/$row[bn_image]' target=_blank><b>$row[bn_id]</b></a></td>";
    echo "<td><a href='$g4[admin_path]/banner_list.php?sfl=a.bg_id&stx=$row[bg_id]'><b>$row[bg_id]</b></a></td>";
    echo "<td><input type=text class=ed name=bn_subject[$i] value='".get_text($row[bn_subject])."' style='width:99%'></td>";
    echo "<td><input type=text class=ed name=bn_start_datetime[$i] value='$row[bn_start_datetime]' style='width:120px;'></td>";
    echo "<td rowspan=2><input type=checkbox name=bn_use[$i] ".($row[bn_use]?'checked':'')." value='1'></td>";
    echo "<td rowspan=2><input type=text class=ed name=bn_order[$i] value='$row[bn_order]' size=2></td>";
    echo "<td rowspan=2>$s_upd<br>$s_del</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>" . number_format($tmp[cnt]) . "</td>";
    echo "<td><input type=checkbox name=bn_target[$i] ".($row[bn_target]?'checked':'')." value='1'></td>";
    echo "<td><input type=text class=ed name=bn_url[$i] value='".get_text($row[bn_url])."' style='width:99%'></td>";
    echo "<td><input type=text class=ed name=bn_end_datetime[$i] value='$row[bn_end_datetime]' style='width:120px;'></td>";
    echo "</tr>\n";
} 

if ($i == 0)
    echo "<tr><td colspan='8' align=center height=100>자료가 없습니다.</td></tr>"; 

echo "</table>";
?>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<div class="btn-group">
    <input type=button class='btn btn-default' value='선택수정' onclick="btn_check(this.form, 'update')">
    <? if ($is_admin == "super") { ?>
        <input type=button class='btn btn-default' value='선택삭제' onclick="btn_check(this.form, 'delete')">
    <? } ?>
</div>

<?
if ($stx)
    echo "<script>document.fsearch.sfl.value = '$sfl';</script>";
?>
</form>

<script type="text/javascript">
// POST 방식으로 삭제
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
    f.bn_id.value = val;
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
<input type='hidden' name='bn_id'>
</form>

<?
include_once("./admin.tail.php");
?>
