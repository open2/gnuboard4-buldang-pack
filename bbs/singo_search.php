<?
include_once("./_common.php");

// ȸ���� ����� �����ϰ�
if (!$is_member) 
{
    $href = "./login.php?$qstr&url=".urlencode("./singo_search.php");

    echo "<script type='text/javascript'>alert('ȸ���� �����մϴ�.'); top.location.href = '$href';</script>";
    exit;
}

$sql_common = " from $g4[singo_table] ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "sg_reason" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super')
    $sql_search .= " and sg_mb_id = '$member[mb_id]' ";

if (!$sst) {
    $sst = "sg_id";
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
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if (!$page) $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

$g4[title] = "�Խù��Ű����";
include_once("./_head.php");

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$write_pages = get_paging($g4[singo_page_rows], $page, $total_page, "?qstr=$qstr&page=");

$singo_skin_path = "$g4[path]/skin/singo/$g4[singo_skin]";

include_once("$singo_skin_path/singo_search.skin.php");

include_once ("./_tail.php");
?>
