<?
$sub_menu = "200800";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "시간별 접속자현황";
include_once("./admin.head.php");
include_once("./visit.sub.php");
?>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=100>
<colgroup width=100>
<colgroup width=100>
<colgroup width=''>
<tr class="success">
    <td>시간</td>
    <td>방문자수</td>
    <td>비율(%)</td>
    <td>그래프</td>
</tr>
<?
$max = 0;
$sum_count = 0;
$sql = " select SUBSTRING(vi_time,1,2) as vi_hour, count(vi_id) as cnt 
           from $g4[visit_table]
          where vi_date between '$fr_date' and '$to_date'
          group by vi_hour
          order by vi_hour ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $arr[$row[vi_hour]] = $row[cnt];

    if ($row[cnt] > $max) $max = $row[cnt];

    $sum_count += $row[cnt];
}

$k = 0;
if ($i) {
    for ($i=0; $i<24; $i++) {
        $hour = sprintf("%02d", $i);
        $count = (int)$arr[$hour];

        $rate = ($count / $sum_count * 100);
        $s_rate = number_format($rate, 1);

        $bar = (int)($count / $max * 100);
        $graph = "<img src='{$g4[admin_path]}/img/graph.gif' width='$bar%' height='18'>";

        $list = ($k++%2);
        echo "
        <tr>
            <td>$hour</td>
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
    echo "<tr><td colspan='4' height=100 align=center>자료가 없습니다.</td></tr>";
}
?>
</table><br><br>

<?
include_once("./admin.tail.php");
?>
