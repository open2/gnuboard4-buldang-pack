<?
$sub_menu = "300320";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "배너그륩별 배너클릭현황";
include_once("./admin.head.php");
include_once("./banner.sub.php");
?>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=100>
<colgroup width=200>
<colgroup width=100>
<colgroup width=100>
<colgroup width=''>
<tr class="success">
    <td>순위</td>
    <td>배너그룹</td>
    <td>클릭수</td>
    <td>비율(%)</td>
    <td>그래프</td>
</tr>
<?
$max = 0;
$sum_count = 0;
$sql = " select * from $g4[banner_click_table]
          where bc_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
    $s = $row[bg_id];

    $arr[$s]++;

    if ($arr[$s] > $max) $max = $arr[$s];

    $sum_count++;
}

$i = 0;
$k = 0;
$save_count = -1;
$tot_count = 0;
if (count($arr)) {
    arsort($arr);
    foreach ($arr as $key=>$value) {
        $count = $arr[$key];
        if ($save_count != $count) {
            $i++;
            $no = $i;
            $save_count = $count;
        } else {
            $no = "";
        }

        $rate = ($count / $sum_count * 100);
        $s_rate = number_format($rate, 1);

        $bar = (int)($count / $max * 100);
        $graph = "<img src='{$g4[admin_path]}/img/graph.gif' width='$bar%' height='18'>";

        echo "
        <tr>
            <td>$no</td>
            <td><a href='./banner_click_list.php?bg_id=$key'>$key</a></td>
            <td>$count</td>
            <td>$s_rate</td>
            <td>$graph</td>
        </tr>";
    }

    echo "
    <tr>
        <td colspan=2>합계</td>
        <td>$sum_count</td>
        <td colspan=2>&nbsp;</td>
    </tr>";
} else {
    echo "<tr><td colspan='5' height=100 align=center>자료가 없습니다.</td></tr>";
}
?>
</table>

<?
include_once("./admin.tail.php");
?>