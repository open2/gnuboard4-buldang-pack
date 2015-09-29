 <?
include_once("./_common.php");

if (!$member[mb_id]) 
    alert_close("회원만 조회하실 수 있습니다.");

$g4[title] = strip_tags($member[mb_nick]) . "님의 포인트 내역";
include_once("$g4[path]/head.sub.php");

$list = array();
$params = array();

$sql_common = " from $g4[point_table] a left join $g4[board_new_table] b 
                on (a.po_rel_table = b.bo_table and a.po_rel_id = b.wr_id) ";
$sql_where = " where a.mb_id = :mb_id ";
$params[] = array(':mb_id', $member[mb_id]);

if($stx && $sfl && $stx != 'all'){
   $sql_where .= " and a.$sfl = :stx ";
   $params[] = array(':stx', $stx);
}

$sql_order = " order by a.po_id desc ";

$sql = " select count(*) as cnt $sql_common $sql_where ";
$stmt = $pdo_db->prepare(" select count(*) as cnt $sql_common $sql_where ");
$row = pdo_fetch_params($stmt, $params);

$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.po_point, a.po_datetime, a.po_content, b.bo_table, b.wr_id
                $sql_common
                $sql_where 
                $sql_order
                limit $from_record, $rows ";

$stmt = $pdo_db->prepare($sql);
$result = pdo_query_params($stmt, $params);

$point_list = array();

for ($i=0; $row=$stmt->fetch(PDO::FETCH_ASSOC); $i++) {
    $point_list[$i]['po_point'] = $row['po_point'];
    $point_list[$i]['po_datetime'] = $row['po_datetime'];
    $point_list[$i]['po_content'] = $row['po_content'];
    // 게시글의 경우에는 url link를 걸어준다
    if ($row['bo_table'] && $row['wr_id'])
        $point_list[$i]['po_url'] = $g4['bbs_path'] . "/board.php?bo_table=" . $row['bo_table'] . "&wr_id=" . $row['wr_id'];
    else
        $point_list[$i]['po_url'] = "";
}

$write_pages = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";
include_once("$member_skin_path/point.skin.php");

include_once("$g4[path]/tail.sub.php");
?>
