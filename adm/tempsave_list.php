<?
$sub_menu = "300840";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$sql_common = " from $g4[tempsave_table] ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "tmp_id";
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

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$g4[title] = "임시저장글목록보기";
include_once("./admin.head.php");

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);
?>

<script type="text/javascript">
var list_delete_php = "tempsave_list_delete.php";
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (임시저장글갯수 : <?=number_format($total_count)?>)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
            <option value='mb_id'>회원아이디</option>
            <option value='ip_addr'>접속한 IP</option>
            <option value='bo_table'>게시판</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fsingolist method=post role="form" class="form-inline">
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<tr class='success'>
    <td width=30><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td width=110><?=subject_sort_link('mb_id')?>회원아이디</a></td>
    <td width=110>tempsave 일시</td>
    <td width=100><?=subject_sort_link('ip_addr')?>IP</a></td>
	  <td width=60>IP차단</td>
    <td align='left'>제목</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    if ($row[mb_id]) {
        $mb = sql_fetch(" select mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date from $g4[member_table] where mb_id = '$row[mb_id]' ");
        $mb_nick = get_sideview($mb[mb_id], get_text($mb[mb_nick]), $mb[mb_email], $mb[mb_homepage]);
    } else 
        $mb_nick = "<span style='color:#222222;'>비회원</a>";

    $log_ip = $row['ip_addr'];
    $ip_intercept = preg_match("/[\n]?$log_ip/", $config['cf_intercept_ip']);
    $log_ip_intercept = "";
    if ($ip_intercept)
        $log_ip_intercept = " <span style='color:#ff0000'>*</span>";

    $subject = get_text($row[wr_subject]);

    echo "
    <input type=hidden name=tmp_id[$i] value='$row[tmp_id]'>
    <tr>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td title='$row[mb_id]' align='left'>$mb_nick</td>
        <td>" . get_datetime($row[wr_datetime]) . "</td>
        <td><a href='?sfl=ip_addr&stx=" . $log_ip . "'>$log_ip</a> $log_ip_intercept</td>
        <td>
        <a href=\"javascript:singo_intercept('$row[mb_id]', '$log_ip');\"><span style='color:#222222;'>차단</span></a>
        <td>$subject</td>
        </td>
    </tr>
    ";
}

if ($i == 0)
    echo "<tr><td colspan='6' align=center height=100>내역이 없습니다.</td></tr>";

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

<div class="well" style="margin-top:20px;">* IP를 차단하는 경우 기본환경설정의 접근차단IP에 등록됩니다.</div>

<form name="fsingo" method="post" action="" style="margin:0px;">
<input type="hidden" name="mb_id">
<input type="hidden" name="ip">
<input type="hidden" name="page" value="<?=$page?>">
</form>

<script type="text/javascript">
function singo_intercept(mb_id, ip) 
{
    var f = document.fsingo;
    if (confirm(ip+" : IP를 정말 차단 하시겠습니까?")) {
        f.mb_id.value = ''; // 로그인 오류의 경우 회원정보는 차단하지 않습니다.
        f.ip.value = ip;
        f.action = "singo_intercept.php";
        f.submit();
    }
}
</script>

<?
include_once ("./admin.tail.php");
?>