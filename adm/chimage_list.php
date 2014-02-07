<?
$sub_menu = "300810";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$sql_common = " from $g4[board_cheditor_table] ";

$sql_search = " where bo_table <> '' and wr_id <> '' and del <> '1' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "bc_id";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt, sum(bc_filesize) as filesize
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];
$total_size = $row[filesize];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$g4[title] = "이미지목록보기";
include_once("./admin.head.php");

$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);
?>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (이미지갯수 : <?=number_format($total_count)?>, 이미지용량 : <?=number_format($total_size/1024, 2)?> MB)
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
    <td width=110><?=subject_sort_link('bo_table')?>게시판</a></td>
    <td>이미지파일 이름</td>
    <td width=80><?=subject_sort_link('bc_filesize')?>용량(KB)</a></td>
    <td width=80><?=subject_sort_link('bc_filesize')?>트래픽(MB)</a></td>
	  <td width=80><?=subject_sort_link('bc_datetime')?>날짜</a></td>
</tr>
<?
$total_traffic = 0;

for ($i=0; $row=sql_fetch_array($result); $i++) {
    if ($row[mb_id]) {
        $mb = sql_fetch(" select mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date from $g4[member_table] where mb_id = '$row[mb_id]' ");
        $mb_nick = $mb[mb_nick];
    } else 
        $mb_nick = "<span style='color:#222222;'>비회원</a>";
    $mbinfo = "<a href='$_SERVER[PHP_SELF]?sfl=mb_id&stx=$row[mb_id]'>$mb_nick</a>";

    $subject = get_text($row[wr_subject]);
    $bo = get_board($row[bo_table], "bo_subject");
    $bo_subject = $bo[bo_subject];
    $boinfo = "<a href='$_SERVER[PHP_SELF]?sfl=bo_table&stx=$row[bo_table]'>$bo_subject</a>";

    // $img[src] 웹 상의 절대경로 이므로 이미지 파일의 상대경로를 구합니다.
    // 이렇게 잘라줘야 제대로 된 경로가 나온다.
    $fl = explode("/$g4[data]/",$row[bc_dir]);
    $rel_path = "../" . $g4[data] . "/" . $fl[1];

    $img_link = $rel_path . "/" . $row[bc_file];
    $imginfo = "<a href='$img_link' target=new>" . $row[bc_source] . "</a>";

    $bc_filesize = number_format($row[bc_filesize]);

    // 트래픽을 계산한다
    $tmp_write_table = $g4['write_prefix'] . $row[bo_table]; // 게시판 테이블 전체이름
    $write = sql_fetch(" select wr_hit from $tmp_write_table where wr_id = '$row[wr_id]' ");
    $bc_traffic = number_format($write[wr_hit] * $row[bc_filesize] / 1000, 2);

    $total_traffic += $bc_traffic; 

    echo "
    <input type=hidden name=bc_id[$i] value='$row[bc_id]'>
    <tr class='list$list col1 center' height=25>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td title='$row[mb_id]' align='left'>$mbinfo</td>
        <td align=left style='padding:0 5px 0 5px;'>" . $boinfo . "</td>
        <td align=left>&nbsp;" . $imginfo. "</td>
        <td align=left>&nbsp$bc_filesize</td>
        <td align=left>&nbsp$bc_traffic</td>
        <td>" . get_datetime($row[bc_datetime]) . "</a>
        </td>
    </tr>
    ";
}

if ($i == 0)
    echo "<tr><td colspan='7' align=center height=100>내역이 없습니다.</td></tr>";

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
</form>

* 현재 페이지의 이미지들의 전체 트래픽은 <?=$total_traffic?> MB 입니다.

<?
include_once ("./admin.tail.php");
?>