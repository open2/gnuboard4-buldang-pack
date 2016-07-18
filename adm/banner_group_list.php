<?
$sub_menu = "300310";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[banner_group_table] ";

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

if ($sst)
    $sql_order = " order by $sst $sod ";
else
    $sql_order = " order by bg_id asc ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search 
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * 
          $sql_common 
          $sql_search
          $sql_order 
          limit $from_record, $rows ";
$result = sql_query($sql);

$listall = "<a href='$_SERVER[PHP_SELF]'>처음</a>";

$g4[title] = "배너그룹관리";
include_once("./admin.head.php");
?>

<script type="text/javascript">
var list_update_php = "./banner_group_list_update.php";
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (그룹수 : <?=number_format($total_count)?>개)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value="bg_id">ID</option>
        <option value="bg_name">이름</option>
        <option value="bg_desc">설명</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fbannergrouplist method=post role="form" class="form-inline">
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=120>
<colgroup width=120>
<colgroup width=''>
<colgroup width=80>
<colgroup width=100>
<colgroup width=60>

<tr class="success">
    <td><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <td><?=subject_sort_link("bg_id")?>그룹아이디</a></td>
    <td><?=subject_sort_link("bg_subject")?>제목</a></td>
    <td>그룹관리자</td>
    <td>사용</td>
    <td>Width*Height</td>
    <td><? if ($is_admin == "super") { echo "<a href='./banner_group_form.php'><i class='fa fa-plus-square fa-2x' title='생성'></i></a>"; } ?></td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    $s_upd = "<a href='./banner_group_form.php?$qstr&w=u&bg_id=$row[bg_id]'><i class='fa fa-pencil' title='수정'></i></a>";
    $s_del = "";
    if ($is_admin == "super") {
        $s_del = "<a href=\"javascript:post_delete('banner_group_delete.php', '$row[bg_id]');\"><i class='fa fa-trash-o' title='삭제'></i></a>";
    }

    // 배너 갯수를 세어준다
    $tmp = sql_fetch(" select count(*) as cnt from $g4[banner_table] where bg_id = '$row[bg_id]' ");
    $bn_count = $tmp['cnt'];

    $list = $i%2;
    echo "<input type=hidden name=bg_id[$i] value='$row[bg_id]'>";
    echo "<tr>";
    echo "<td><input type=checkbox name=chk[] value='$i'></td>";
    echo "<td><a href='$g4[admin_path]/banner_list.php?sfl=a.bg_id&stx=$row[bg_id]'><b>$row[bg_id]</b></a> ($bn_count)</td>";
    echo "<td><input type=text class=ed name=bg_subject[$i] value='".get_text($row[bg_subject])."' size=30></td>";

    if ($is_admin == "super")
        //echo "<td>".get_member_id_select("bg_admin[$i]", 9, $row[bg_admin])."</td>";
        echo "<td><input type=text class=ed name=bg_admin[$i] value='$row[bg_admin]' maxlength=20></td>";
    else
        echo "<input type=hidden name='bg_admin[$i]' value='$row[bg_admin]'><td>$row[bg_admin]</td>";

    echo "<td><input type=checkbox name=bg_use[$i] ".($row[bg_use]?'checked':'')." value='1'></td>";
    echo "<td><input type=text class=ed name=bg_width[$i] value='$row[bg_width]' size=4><input type=text class=ed name=bg_height[$i] value='$row[bg_height]' size=4></td>";
    echo "<td>$s_upd $s_del</td>";
    echo "</tr>\n";
} 

if ($i == 0)
    echo "<tr><td colspan='7' align=center height=100>자료가 없습니다.</td></tr>"; 

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
        f.bg_id.value = val;
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
<input type='hidden' name='bg_id'>
</form>

<?
include_once("./admin.tail.php");
?>
