<?
$sub_menu = "200800";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "ip주소별 접속자현황";
include_once("./admin.head.php");
include_once("./visit.sub.php");
?>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=100>
<colgroup width=200>
<colgroup width=100>
<colgroup width=100>
<colgroup width=''>
<tr class="success">
    <td>순위</td>
    <td>ip주소</td>
    <td>방문자수</td>
    <td>비율(%)</td>
    <td>그래프</td>
</tr>
<?
$sql_common = " from $g4[visit_table] ";
$sql_search = " where vi_date between '$fr_date' and '$to_date' 
                group by vi_ip ";
$sql_order = " order by cnt desc ";

$sql = " select count(*) as cnt 
          $sql_common
          $sql_search
          ";
$result = sql_query($sql);
$total_count = mysql_num_rows($result);

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page == "") $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *, count(*) as cnt 
           $sql_common
           $sql_search
           $sql_order 
           limit $from_record, $rows";
$result = sql_query($sql);

$sql = " select count(*) as cnt 
           from $g4[visit_table]
          where vi_date between '$fr_date' and '$to_date' ";
$sum = sql_fetch($sql);
$sum_count = $sum['cnt'];

if ($sum_count > 0) {
    $i = 0;
    while ($row=sql_fetch_array($result)) {
        if ($i == 0) 
            $max = $row['cnt'];
        $i++;
        
        $count = $row['cnt'];

        $rate = ($count / $sum_count * 100);
        $s_rate = number_format($rate, 1);
        
        $bar = (int)($count / $max * 100);
        $graph = "<img src='{$g4[admin_path]}/img/graph.gif' width='$bar%' height='18'>";

        $no = $i + $from_record;
        echo "
        <tr class='list$list ht center'>
            <td>$no</td>
            <td><a href='http://www.ip-adress.com/ip_tracer/$ip' target=_new>$row[vi_ip]</a></td>
            <td>".number_format($count)."</td>
            <td>$s_rate</td>
            <td align=left>$graph</td>
        </tr>";
    }

    echo "
    <tr>
        <td>합계</td>
        <td>".number_format($sum_count)."</td>
        <td colspan=2>&nbsp;</td>
    </tr>";
} else {
    echo "<tr><td colspan='5' height=100 align=center>자료가 없습니다.</td></tr>";
}
?>
</table>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<?
include_once("./admin.tail.php");
?>
