<?
include_once("./_common.php");

$sql_common = " from $g4[recycle_table] ";

$sql_search = " where rc_wr_id = rc_wr_parent and mb_id = '$member[mb_id]' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
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
    $sst = "rc_no";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $g4[recycle_page_rows];
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

$g4[title] = "휴지통관리";
include_once("./_head.php");

$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$write_pages = get_paging($g4[recycle_page_rows], $page, $total_page, "?qstr=$qstr&page=");

$recycle_skin_path = "$g4[path]/skin/recycle/$g4[recycle_skin]";

include_once("$recycle_skin_path/recycle.skin.php");

include_once ("./_tail.php");
?>
