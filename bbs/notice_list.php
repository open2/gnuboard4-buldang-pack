<?
include_once("./_common.php");

$g4[title] = "전체공지사항";

$sql_common = " from $g4[notice_table] a left join $g4[board_table] b on (a.bo_table = b.bo_table) ";

if ($sst)
    $sql_order = " order by $sst $sod ";
else
    $sql_order = " order by a.no_datetime desc ";

$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

// rows는 따로 지정하지 않고...
$rows = $g4['good_list_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$list = array();
$sql = " select a.no_datetime, a.wr_id, b.*
          $sql_common
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    $tmp_write_table = $g4[write_prefix] . $row[bo_table];

    // 게시글 정보를 가져온다
    $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '$row[wr_id]' ");
    $list[$i] = $row2;
    
    $name = get_sideview($row2[mb_id], cut_str($row2[wr_name], $config[cf_cut_name]), $row2[wr_email], $row2[wr_homepage]);
    $list[$i][name] = $name;
    $list[$i][href] = "./notice_view.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]&page=$page&qstr=$qstr";
    $list[$i][wr_datetime2] = get_datetime($row2[wr_datetime]);

    $list[$i][bo_table] = $row[bo_table];
    $list[$i][bo_subject] = $row[bo_subject];
}

$write_pages = get_paging($config[cf_write_pages], $page, $total_page, "./good_list.php?gr_id=$gr_id&bo_table=$bo_table&qstr=$qstr&page=");
$write_pages_xs = get_paging($config[cf_write_pages_xs], $page, $total_page, "./good_list.php?gr_id=$gr_id&bo_table=$bo_table&qstr=$qstr&page=");

if (!$wr_id)
    include_once($g4[notice_list_head]);

$notice_list_skin_path = "$g4[path]/skin/notice_list/$g4[notice_list_skin]";
include_once("$notice_list_skin_path/notice_list.skin.php");

if (!$wr_id)
    include_once($g4['notice_list_tail']);
?>
