<?
$sub_menu = "300560";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[recycle_table] ";

$sql_search = " where rc_wr_id = rc_wr_parent ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        case "bo_table" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "rc_datetime";
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
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 삭제 게시글 수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and rc_delete = '1'
         $sql_order ";
$row = sql_fetch($sql);
$delete_count = $row[cnt];

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$g4[title] = "휴지통관리";
include_once("./admin.head.php");

$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$colspan = 15;
?>

<script type="text/javascript">
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
        document.location.href = "./recycle_delete.php?ok=" + ok;
    }
}
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> 
        (휴지글수 : <?=number_format($total_count)?>, 삭제글수 : <?=number_format($delete_count)?>)
        &nbsp;&nbsp;<a href="javascript:recycle_delete();">휴지글삭제</a>
        &nbsp;&nbsp;<a href="javascript:recycle_delete(1);">휴지글완전삭제</a>
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='mb_id'>회원아이디</option>
        <option value='bo_table'>게시판</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fmemberlist method=post role="form" class="form-inline">
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=100>
<colgroup width=80>
<colgroup width=60>
<colgroup width=''>
<colgroup width=40>
<colgroup width=80>
<colgroup width=80>
<tr class='success'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td><?=subject_sort_link('mb_id')?>회원아이디</a></td>
    <td><?=subject_sort_link('bo_table')?>게시판id</a></td>
    <td>게시글id</td>
    <td>게시글제목</td>
    <td><?=subject_sort_link('rc_datetime', '', 'desc')?>삭제일</a></td>
  	<td>복구</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    
    $mb = get_member($row[mb_id]);
    $mb_nick = get_sideview($mb[mb_id], get_text($mb[mb_nick]), $mb[mb_email], $mb[mb_homepage]);    

    // 게시글 제목
    $tmp_write_table = $g4['write_prefix'] . $row[rc_bo_table];
    $sql2 = " select wr_subject, wr_content from $tmp_write_table where wr_id = '$row[rc_wr_id]' ";
    $write = sql_fetch($sql2);
    $wr_subject = conv_subject($write[wr_subject],80);
    if ($row[rc_delete])
        $wr_subject = "<strike>" . $wr_subject . "</stricke>";

    // 코멘트인지 여부
    $c_flag="";
    if ($row[wr_is_comment])
        $c_flag = " C";
    
    // wr_id
    if ($c_flag)
        $wr_id = $row[wr_id] . $c_flag;
    else
        $wr_id = "<a href='$g4[admin_path]/recycle_view.php?bo_table=$row[rc_bo_table]&wr_id=$row[rc_wr_id]&org_bo_table=$row[bo_table]' target=_blank>" . $row[wr_id] . "</a>";

    // 복구 버튼을 출력
    if ($row[rc_delete] == 0)
        $s_recover = "<a href=\"javascript:post_recover('recycle_recover.php', '$row[rc_no]');\"><i class=\"fa fa-undo\" title='복구'></i></a>";
    else
        $s_recover = "";

    // 운영자가 삭제한거 (mb_id와 rc_mb_id가 다른 경우)에는 뒤에 mark
    $mb_remover="";
    if ($row[mb_id] !== $row[rc_mb_id])
        $mb_remover="&nbsp;<i class='fa fa-gavel' title='관리자가 지워버린 글'></i>";

    // 게시판아이디. 게시판 정렬
    $bo_info = get_board($row[bo_table],"bo_subject");
    $bo_table1 = "<a href='$g4[admin_path]/recycle_list.php?sfl=bo_table&stx=$row[bo_table]' title='$bo_info[bo_subject]'>$row[bo_table]</a>";

    $list = $i%2;
    echo "
    <input type=hidden name=rc_no[$i] value='$row[rc_no]'>
    <tr class='list$list col1 ht center'>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td title='$row[mb_id]'>$mb_nick$mb_remover</td>
        <td>$bo_table1</td>
        <td>$wr_id</td>
        <td>$wr_subject</td>
        <td>" . get_datetime($row[rc_datetime]) . "</td>
        <td>$s_recover</td>
    </tr>";
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
    <? if ($is_admin == "super") { ?>
        <input type=button class='btn btn-default' value='선택삭제' onclick="btn_check(this.form, 'delete')">
    <? } ?>
</div>

<?
if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>
</form>

* 휴지글 삭제시 삭제 mark만 하고 실제로는 삭제하지 않습니다. 실제 게시글 삭제를 원하시면 휴지통완전삭제 메뉴를 사용해주세요.<br>
* 회원아이디 옆에 아이콘이 있는 글은, 사용자가 삭제한 것이 아니라 관리자가 삭제한 글 입니다.<br>
* 게시판id를 클릭하면 해당 게시판의 삭제글이 정렬되며, 게시글 id를 클릭하면 해당 게시글의 새창이 뜹니다.

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

<?
include_once ("./admin.tail.php");
?>
