<?
$sub_menu = "200800";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "접속자현황";
include_once("./admin.head.php");
include_once("./visit.sub.php");
?>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=140>
<colgroup width=>
<colgroup width=100>
<colgroup width=80>
<colgroup width=80>
<colgroup width=80>
<tr class="success">
    <td>ip</td>
    <td><?=subject_sort_link('vi_referer',"fr_date=$fr_date&to_date=$to_date&domain=$domain&ip=$ip")?>접속 경로</a></td>
    <td>검색어</td>
    <td>브라우저</td>
    <td>OS</td>
    <td>일시</td>
</tr>
<?
//unset($br); // 브라우저
//unset($os); // OS

$sql_common = " from $g4[visit_table] ";
$sql_search = " where vi_date between '$fr_date' and '$to_date' ";
if ($domain) {
    $sql_search .= " and vi_referer like '%$domain%' ";
}
if ($ip) {
    $sql_search .= " and vi_ip like '$ip%' ";
}

if (!$sst) {
    $sst = "vi_id";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search ";
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

for ($i=0; $row=sql_fetch_array($result); $i++) {
    $brow = get_brow($row[vi_agent]);
    $os   = get_os($row[vi_agent]);

    $link = "";
    $referer = "";
    $title = "";
    if ($row[vi_referer]) {

        $referer = get_text(cut_str($row[vi_referer], 255, ""));
        $referer = urldecode($referer);

        if (strtolower($g4['charset']) == 'utf-8') {
            if (!is_utf8($referer)) {
                $referer = iconv('euc-kr', 'utf-8', $referer);
            }
        }
        else {
            if (is_utf8($referer)) {
                $referer = iconv('utf-8', 'euc-kr', $referer);
            }
        }

        $title = str_replace(array("<", ">"), array("&lt;", "&gt;"), urldecode($row[vi_referer]));
        $link = "<a href='#' onclick=\"goto_page('" . htmlspecialchars($row['vi_referer']) . "');return false;\" title='$title '>" . "<i class='fa fa-sign-in'></i></a>";
    }

    if ($is_admin == 'super')
        $ip = $row[vi_ip];
    else
        $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.♡.\\3.\\4", $row[vi_ip]);

    $ip_link = "<a href='./visit_list.php?fr_date=$fr_date&to_date=$to_date&ip=$ip' title='$ip 로 접속한 목록'>";
    preg_match("/^(http:\/\/)?([^\/]+)/i", $title, $matches);
    $ref_domain = $matches[2];
    if ($ref_domain)
        $ref_link = "<a href='./visit_list.php?fr_date=$fr_date&to_date=$to_date&domain=$ref_domain' title='$ref_domain 으로 접속한 목록'>" . "<i class='fa fa-user'></i></a>";
    else
        $ref_link = "";
    $title_link = "<a href='./visit_list.php?fr_date=$fr_date&to_date=$to_date&domain=$title' title='$title 으로 접속한 목록'>";
    
    if ($brow == '기타') { $brow = "<span title='$row[vi_agent]'>$brow</span>"; }
    if ($os == '기타') { $os = "<span title='$row[vi_agent]'>$os</span>"; }

    $list = ($i%2);
    
    // 검색어 설정
    $query=$q="";
    //parse_str($title);
    if ($query)
        $query = iconv('EUC-KR' , $g4[charset], $query);  // naver
    else if ($q)
        $query = iconv('EUC-KR' , $g4[charset], $q);      // google

    echo "
    <tr>
        <td><a href='http://www.ip-adress.com/ip_tracer/$ip' target=_new><i class='fa fa-question'></i>&nbsp;&nbsp;$ip_link$ip</a></td>
        <td>$link $ref_link $title_link" . cut_str($title,40) . "</a></td>
        <td>$query</td>
        <td>$brow</td>
        <td>$os</td>
        <td>$row[vi_date] $row[vi_time]</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='6' height=100 align=center>자료가 없습니다.</td></tr>"; 

echo "</table>";
?>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<?
include_once("./admin.tail.php");
?>

<script type="text/javascript">
// java script로 페이지 이동 (referer를 남기지 않기 위해서)
function goto_page(page)
{
    if (page) {
        window.open(page);
    }
    return false;
}
</script>