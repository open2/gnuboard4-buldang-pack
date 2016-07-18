<?
$sub_menu = "200100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[member_table] ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_point" :
            $sql_search .= " ($sfl >= '$stx') ";
            break;
        case "mb_level" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        case "mb_tel" :
        case "mb_hp" :
            $sql_search .= " ($sfl like '%$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

//if ($is_admin == 'group') $sql_search .= " and mb_level = '$member[mb_level]' ";
if ($is_admin != 'super') 
    $sql_search .= " and mb_level <= '$member[mb_level]' ";

if (!$sst) {
    $sst = "mb_datetime";
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

// 휴면 회원수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and mb_unlogin <> '0000-00-00 00:00:00'
         $sql_order ";
$row = sql_fetch($sql);
$unlogin_count = $row[cnt];

// 탈퇴회원수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and mb_leave_date <> ''
         $sql_order ";
$row = sql_fetch($sql);
$leave_count = $row[cnt];

// 차단회원수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and mb_intercept_date <> ''
         $sql_order ";
$row = sql_fetch($sql);
$intercept_count = $row[cnt];

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$g4[title] = "회원관리";
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
var list_update_php = "member_list_update.php";
var list_delete_php = "member_list_delete.php";
</script>

<script type="text/javascript">
function member_delete_unvisited()
{
    if (confirm("오랫동안 접속하지 않고 글작성도 없는 회원을 정리합니다\n\n정리기간 등은 /adm/member_undelete_unvisited.php에서 설정가능합니다.\n\n\n그래도 진행하시겠습니까?"))
    {
        document.location.href = "./member_delete_unvisited.php?ok=1";
    }
}
</script>

<script type="text/javascript">
function member_delete_unlogin()
{
    if (confirm("1년이상 접속하지 않은 회원을 정리합니다\n\n진행하시겠습니까?"))
    {
        document.location.href = "./member_delete_unlogin.php?ok=1";
    }
}
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> 
    (총회원수 : <?=number_format($total_count)?>, 
    <a href='?sst=mb_unlogin&sod=desc&sfl=<?=$sfl?>&stx=<?=$stx?>' title='휴면회원부터 출력'><font color=orange>휴면 : <?=number_format($unlogin_count)?></font></a>, 
    <a href='?sst=mb_intercept_date&sod=desc&sfl=<?=$sfl?>&stx=<?=$stx?>' title='차단된 회원부터 출력'><font color=orange>차단 : <?=number_format($intercept_count)?></font></a>, 
    <a href='?sst=mb_leave_date&sod=desc&sfl=<?=$sfl?>&stx=<?=$stx?>' title='탈퇴한 회원부터 출력'><font color=crimson>탈퇴 : <?=number_format($leave_count)?></font></a>)
    &nbsp;&nbsp;<a href="javascript:member_delete_unlogin();">휴면회원정리</a>
    &nbsp;&nbsp;<a href="javascript:member_delete_unvisited();">장기미접속회원정리</a>
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='mb_id'>회원아이디</option>
        <option value='mb_name'>이름</option>
        <option value='mb_nick'>별명</option>
        <option value='mb_level'>권한</option>
        <option value='mb_email'>E-MAIL</option>
        <option value='mb_tel'>전화번호</option>
        <option value='mb_hp'>핸드폰번호</option>
        <option value='mb_point'>포인트</option>
        <option value='mb_datetime'>가입일시</option>
        <option value='mb_ip'>IP</option>
        <option value='mb_recommend'>추천인</option>
        <option value='ug_id'>사용자그룹</option>
        <option value='mb_no'>회원번호</option>
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
<colgroup width=90>
<colgroup width=80>
<colgroup width=80>
<colgroup width=''>
<colgroup width=25>
<colgroup width=70>
<colgroup width=80>
<colgroup width=40>
<colgroup width=40>
<colgroup width=40>
<colgroup width=40>
<colgroup width=50>
<colgroup width=50>
<tr class='bgcol1 bold col1 ht center'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
  	<td><a href="./member_form.php"><i class='fa fa-plus-square fa-2x' title='생성'></i></a></td>
    <td><?=subject_sort_link('mb_id')?>회원아이디</a></td>
    <td><?=subject_sort_link('mb_name')?>이름</a></td>
    <td><?=subject_sort_link('mb_nick')?>별명</a></td>
    <td><?=subject_sort_link('mb_level', '', 'desc')?>권한</a></td>
    <td><?=subject_sort_link('mb_point', '', 'desc')?>포인트</a></td>
    <td><?=subject_sort_link('mb_today_login', '', 'desc')?>최종접속</a></td>
    <td title='메일수신허용여부'><?=subject_sort_link('mb_mailling', '', 'desc')?>수신</a></td>
    <td title='정보공개여부'><?=subject_sort_link('mb_open', '', 'desc')?>공개</a></td>
    <!-- <td><?=subject_sort_link('mb_leave_date', '', 'desc')?>탈퇴</a></td> -->
    <td><?=subject_sort_link('mb_email_certify', '', 'desc')?>인증</a></td>
    <td><?=subject_sort_link('mb_intercept_date', '', 'desc')?>차단</a></td>
    <td title='사용자그룹'><?=subject_sort_link('ug_id', '', 'desc')?>U그룹</a></td>
    <td title='접근가능한 그룹수'>B그룹</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    // 접근가능한 그룹수
    $sql2 = " select count(*) as cnt from $g4[group_member_table] where mb_id = '$row[mb_id]' ";
    $row2 = sql_fetch($sql2);
    $group = "";
    if ($row2[cnt])
        $group = "<a href='./boardgroupmember_form.php?mb_id=$row[mb_id]'>$row2[cnt]</a>";

    // 사용자그룹
    $ug_id = $row[ug_id];
    if ($ug_id) {
        $result0 = sql_fetch(" select ug_subject from $g4[user_group_table] where ug_id = '$ug_id' ");
        $ug_name = "<a href='./ug_list.php?sfl=ug_id&stx=$ug_id'>". cut_str($result0[ug_subject], 6, "") . "</a>";
    } else {
        $ug_name = "";
    }
    
    if ($is_admin == 'group') 
    {
        $s_mod = "";
        $s_del = "";
    } 
    else 
    {
        $s_mod = "<a href=\"./member_form.php?$qstr&w=u&mb_id=$row[mb_id]\"><i class='fa fa-pencil' title='수정'></i></a>";
        $s_del = "&nbsp;<a href=\"javascript:post_delete('member_delete.php', '$row[mb_id]');\"><i class='fa fa-trash-o' title='삭제'></i></a>";
    }
    $s_grp = "&nbsp;<a href='./boardgroupmember_form.php?mb_id=$row[mb_id]'><i class='fa fa-group' title='삭제'></i></a>";

    $leave_date = $row[mb_leave_date] ? $row[mb_leave_date] : date("Ymd", $g4[server_time]);
    $intercept_date = $row[mb_intercept_date] ? $row[mb_intercept_date] : date("Ymd", $g4[server_time]);

    $mb_nick = get_sideview($row[mb_id], get_text($row[mb_nick]), $row[mb_email], $row[mb_homepage]);

    $mb_id = $row[mb_id];
    if ($row[mb_leave_date])
        $mb_id = "<font color=crimson>$mb_id</font>";
    else if ($row[mb_intercept_date])
        $mb_id = "<font color=orange>$mb_id</font>";

    $list = $i%2;
    echo "
    <input type=hidden name=mb_id[$i] value='$row[mb_id]'>
    <tr class='list$list col1 ht center'>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td>$s_mod $s_del $s_grp</td>
        <td title='$row[mb_id]'><nobr style='display:block; overflow:hidden; width:90;'>&nbsp;$mb_id</nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:90px;'>$row[mb_name]</nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:90px;'><u>$mb_nick</u></nobr></td>
        <td>".get_member_level_select("mb_level[$i]", 1, $member[mb_level], $row[mb_level])."</td>
        <td align=right><a href='point_list.php?sfl=mb_id&stx=$row[mb_id]' class=tt>".number_format($row[mb_point])."</a>&nbsp;</td>
        <td>".substr($row[mb_today_login],2,8)."</td>
        <td>".($row[mb_mailling]?'&radic;':'&nbsp;')."</td>
        <td>".($row[mb_open]?'&radic;':'&nbsp;')."</td>
        <!-- <td title='$row[mb_leave_date]'>".($row[mb_leave_date]?'&radic;':'&nbsp;')."</td> -->
        <td title='$row[mb_email_certify]'>".(preg_match('/[1-9]/', $row[mb_email_certify])?'&radic;':'&nbsp;')."</td>
        <td title='$row[mb_intercept_date]'><input type=checkbox name=mb_intercept_date[$i] ".($row[mb_intercept_date]?'checked':'')." value='$intercept_date'></td>
        <td>$ug_name</td>
        <td>$group</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100 class=contentbg>자료가 없습니다.</td></tr>";

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
    <input type=button class='btn btn-default' value='선택삭제' onclick="btn_check(this.form, 'delete')">
</div>

<?
if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>
</form>

* 회원자료 삭제시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 별명은 삭제하지 않고 영구 보관합니다.

<script>
// POST 방식으로 삭제
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        f.mb_id.value = val;
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
<input type='hidden' name='mb_id'>
</form>

<?
include_once ("./admin.tail.php");
?>
