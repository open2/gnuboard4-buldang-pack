<?
$sub_menu = "300200";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[group_table] ";

$sql_search = " where (1) ";
if ($is_admin != "super")
    $sql_search .= " and (gr_admin = '$member[mb_id]') ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "gr_id" :
        case "gr_admin" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default : 
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($sst)
    $sql_order = " order by $sst $sod ";
else
    $sql_order = " order by gr_id asc ";

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

$g4[title] = "게시판그룹설정";
include_once("./admin.head.php");
?>

<script type="text/javascript">
var list_update_php = "./boardgroup_list_update.php";
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (그룹수 : <?=number_format($total_count)?>개)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value="gr_subject">제목</option>
        <option value="gr_id">ID</option>
        <option value="gr_admin">그룹관리자</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fboardgrouplist method=post>
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<tr class="success">
    <td width=30><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <td width=45><? if ($is_admin == "super") { echo "<a href='./boardgroup_form.php'><i class='fa fa-plus-square fa-2x' title='생성'></i></a>"; } ?></td>
    <td width=120><?=subject_sort_link("gr_id")?>그룹아이디</a></td>
    <td><?=subject_sort_link("gr_subject")?>제목</a></td>
    <td width=80><?=subject_sort_link("gr_admin")?>그룹관리자</a></td>
    <td width=80>게시판</td>
    <td width=80>접근사용</td>
    <td width=80>접근회원수</td>
    <td width=35>검색<br />사용</td>
    <td width=35>검색<br />순서</td>    
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    // 접근회원수
    $sql1 = " select count(*) as cnt from $g4[group_member_table] where gr_id = '$row[gr_id]' ";
    $row1 = sql_fetch($sql1);

    // 게시판수
    $sql2 = " select count(*) as cnt from $g4[board_table] where gr_id = '$row[gr_id]' ";
    $row2 = sql_fetch($sql2);

    $s_upd = "<a href='./boardgroup_form.php?$qstr&w=u&gr_id=$row[gr_id]'><i class='fa fa-pencil' title='수정'></i></a>";
    $s_del = "";
    if ($is_admin == "super") {
        $s_del = "&nbsp;<a href=\"javascript:post_delete('boardgroup_delete.php', '$row[gr_id]');\"><i class='fa fa-trash-o' title='삭제'></i></a>";
    }

    $list = $i%2;
    echo "<input type=hidden name=gr_id[$i] value='$row[gr_id]'>";
    echo "<tr>";
    echo "<td><input type=checkbox name=chk[] value='$i'></td>";
    echo "<td>$s_upd $s_del</td>";
    echo "<td><a href='$g4[bbs_path]/group.php?gr_id=$row[gr_id]'><b>$row[gr_id]</b></a></td>";
    echo "<td><input type=text name=gr_subject[$i] value='".get_text($row[gr_subject])."' size=30></td>";

    if ($is_admin == "super")
        echo "<td><input type=text class=ed name=gr_admin[$i] value='$row[gr_admin]' maxlength=20></td>";
    else
        echo "<input type=hidden name='gr_admin[$i]' value='$row[gr_admin]'><td>$row[gr_admin]</td>";

    echo "<td><a href='./board_list.php?sfl=a.gr_id&stx=$row[gr_id]'>$row2[cnt]</a></td>";
    echo "<td><input type=checkbox name=gr_use_access[$i] ".($row[gr_use_access]?'checked':'')." value='1'></td>";
    echo "<td><a href='./boardgroupmember_list.php?gr_id=$row[gr_id]'>$row1[cnt]</a></td>";
    echo "<td title='검색사용'><input type=checkbox name=gr_use_search[$i] ".($row[gr_use_search]?'checked':'')." value='1'></td>";
    echo "<td title='검색순서'><input type=text class=ed name=gr_order_search[$i] value='$row[gr_order_search]' size=2></td>";
    echo "</tr>\n";
} 

if ($i == 0)
    echo "<tr><td colspan='10' align=center height=100 bgcolor=#ffffff>자료가 없습니다.</td></tr>"; 

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
        f.gr_id.value = val;
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
<input type='hidden' name='gr_id'>
</form>

<?
include_once("./admin.tail.php");
?>
