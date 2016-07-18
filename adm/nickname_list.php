<?
$sub_menu = "200150";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[mb_nick_table] ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "nick_no";
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
$listall = "<a href='$_SERVER[PHP_SELF]'>처음</a>";

$g4[title] = "닉네임변경이력";
include_once ("./admin.head.php");
?>
<script type="text/javascript">
var list_update_php = "";
var list_delete_php = "nickname_list_delete.php";
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (건수 : <?=number_format($total_count)?>)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='mb_id'>회원아이디</option>
        <option value='mb_nick'>닉네임</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fpointlist method=post>
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>
<input type=hidden name=token value="<?=$token?>">

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=30>
<colgroup width=100>
<colgroup width=80>
<colgroup width=140>
<colgroup>

<tr class='success'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td>번호</td>
    <td><?=subject_sort_link('mb_id')?>회원아이디</a></td>
    <td>별명</td>
    <td><?=subject_sort_link('start_datetime')?>사용시작일</a></td>
    <td><?=subject_sort_link('end_datetime')?>사용종료일</a></td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    if ($row2[mb_id] != $row[mb_id])
    {
        $sql2 = " select *  from $g4[mb_nick_table] where mb_id = '$row[mb_id]' ";
        $row2 = sql_fetch($sql2);
    }

    $mb_nick = get_sideview($row[mb_id], get_text($row2[mb_nick]), $row2[mb_email], $row2[mb_homepage]);

    $link1 = $link2 = "";
    if (!preg_match("/^\@/", $row[po_rel_table]) && $row[po_rel_table])
    {
        $link1 = "<a href='$g4[bbs_path]/board.php?bo_table={$row[po_rel_table]}&wr_id={$row[po_rel_id]}' target=_blank>";
        $link2 = "</a>";
    }

    $list = $i%2;
    echo "
    <input type=hidden name=nick_no[$i] value='$row[nick_no]'>
    <input type=hidden name=mb_id[$i] value='$row[mb_id]'>
    <tr class='list$list col1 ht center'>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td>$row[nick_no]</td>
        <td><a href='?sfl=mb_id&stx=$row[mb_id]'>$row[mb_id]</a></td>
        <td>$row[mb_nick]</td>
        <td>" . get_datetime($row[start_datetime]) . "</td>
        <td>" . get_datetime($row[end_datetime]) . "</td>
    </tr> ";
}

if ($i == 0)
    echo "<tr><td colspan='6' align=center height=100>자료가 없습니다.</td></tr>";

echo "</table>";
?>

<!-- 페이지 -->
<div>
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<div class="btn-group">
    <input type=button class='btn btn-default' value='선택삭제' onclick="btn_check(this.form, 'delete')">
</div>

<?
if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
?>
</form>

<script language='javascript'> document.fsearch.stx.focus(); </script>

<?
include_once ("./admin.tail.php");
?>
