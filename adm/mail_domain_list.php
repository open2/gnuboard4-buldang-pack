<?
$sub_menu = "200310";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

// http://sir.co.kr/bbs/board.php?bo_table=tip_mysql&wr_id=292
$sql = "  SELECT SUBSTRING_INDEX( mb_email, '@', -1 ) AS Domain, count( * ) AS Total
          FROM $g4[member_table] 
          GROUP BY Domain
          ORDER BY Total DESC, Domain ASC ";
$result = sql_query($sql);

$total_count = mysql_num_rows($result);

$g4[title] = "회원 이메일 도메인 목록";
include_once("./admin.head.php");
?>

<div class="btn-group">
    <?=$listall?> (도메인 갯수 : <?=number_format($total_count)?>)
</div>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<tr class='success'>
    <td width=30></td>
    <td width=110 align='left'>도메인이름</td>
    <td align='left'>도메인갯수</td>
</tr>
<?
for ($i=0; $i < $total_count; $i++) {
    $row = sql_fetch_array($result);
    
    echo "
    <tr class='list$list col1 center' height=25>
        <td></td>
        <td title='$row[Domain]' align='left'><a href='http://$row[Domain]' target='new'>$row[Domain]</a></td>
        <td align=left style='padding:0 5px 0 5px;'><a href='member_list.php?sfl=mb_email&stx=%25@$row[Domain]'>$row[Total]</a></td>
    </tr>
    ";
}

if ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100>내역이 없습니다.</td></tr>";
?>
</table>

<?
include_once ("./admin.tail.php");
?>
