<?
$sub_menu = "300910";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[banner_group_table] ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bn_type" :
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

$colspan = 10;
?>

<script type="text/javascript">
var list_update_php = "./banner_group_list_update.php";
</script>

<table width=100% cellpadding=3 cellspacing=1>
<form name=fsearch method=get>
<tr>
    <td width=50% align=left><?=$listall?> (그룹수 : <?=number_format($total_count)?>개)</td>
    <td width=50% align=right>
        <select name=sfl>
            <option value="bg_id">ID</option>
            <option value="bg_name">이름</option>
            <option value="bg_desc">설명</option>
        </select>
        <input type=text name=stx class=ed required itemname='검색어' value='<?=$stx?>'>
        <input type=image src='<?=$g4[admin_path]?>/img/btn_search.gif' align=absmiddle></td>
</tr>
</form>
</table>

<form name=fboardgrouplist method=post>
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<colgroup width=30>
<colgroup width=120>
<colgroup width=120>
<colgroup width=''>
<colgroup width=80>
<colgroup width=40>
<colgroup width=60>
<colgroup width=40>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <td><?=subject_sort_link("bg_id")?>그룹아이디</a></td>
    <td><?=subject_sort_link("bg_name")?>이름</a></td>
    <td>설명</td>
    <td>Width*Height</td>
    <td>사용</td>
    <td>배너타입</td>
    <td><? if ($is_admin == "super") { echo "<a href='./banner_group_form.php'><img src='$g4[admin_path]/img/icon_insert.gif' border=0 title='생성'></a>"; } ?></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) 
{

    $list = $i%2;
    echo "<input type=hidden name=gr_id[$i] value='$row[gr_id]'>";
    echo "<tr class='list$list' onmouseover=\"this.className='mouseover';\" onmouseout=\"this.className='list$list';\" height=27 align=center>";
    echo "<td><input type=checkbox name=chk[] value='$i'></td>";
    echo "<td><a href='$g4[bbs_path]/group.php?gr_id=$row[gr_id]'><b>$row[gr_id]</b></a></td>";
    echo "<td><input type=text class=ed name=gr_subject[$i] value='".get_text($row[gr_subject])."' size=30></td>";

    if ($is_admin == "super")
        //echo "<td>".get_member_id_select("gr_admin[$i]", 9, $row[gr_admin])."</td>";
        echo "<td><input type=text class=ed name=gr_admin[$i] value='$row[gr_admin]' maxlength=20></td>";
    else
        echo "<input type=hidden name='gr_admin[$i]' value='$row[gr_admin]'><td>$row[gr_admin]</td>";

    echo "<td><a href='./board_list.php?sfl=a.gr_id&stx=$row[gr_id]'>$row2[cnt]</a></td>";
    echo "<td><input type=checkbox name=gr_use_access[$i] ".($row[gr_use_access]?'checked':'')." value='1'></td>";
    echo "<td><a href='./boardgroupmember_list.php?gr_id=$row[gr_id]'>$row1[cnt]</a></td>";
    echo "<td title='검색사용'><input type=checkbox name=gr_use_search[$i] ".($row[gr_use_search]?'checked':'')." value='1'></td>";
    echo "<td title='검색순서'><input type=text class=ed name=gr_order_search[$i] value='$row[gr_order_search]' size=2></td>";
    echo "<td>$s_upd $s_del</td>";
    echo "</tr>\n";
} 

if ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100 bgcolor=#ffffff>자료가 없습니다.</td></tr>"; 

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");
echo "<table width=100% cellpadding=3 cellspacing=1>";
echo "<tr><td width=70%>";
echo "<input type=button class='btn1' value='선택수정' onclick=\"btn_check(this.form, 'update')\">";
//echo " <input type=button value='선택삭제' onclick=\"btn_check(this.form, 'delete')\">";
echo "</td>";
echo "<td width=30% align=right>$pagelist</td></tr></table>\n";

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
