<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 포인트 내역을 분류
$bo_str = "<select class=\"form-control\" name='bo_table' onchange=\"location='$g4[bbs_path]/point.php?sfl=po_rel_table&stx='+this.value;\">";
$bo_str .= "<option value='all'>전체목록보기</option>";

$sql = " select distinct po_rel_table from $g4[point_table] where mb_id = '$member[mb_id]' ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) {

        // g4_point 테이블과 g4_board 테이블을 left join 하지 않도록 코드 수정
        $row[bo_table] = $row[po_rel_table];
        $temp = sql_fetch(" select bo_subject from $g4[board_table] where bo_table = '$row[po_rel_table]' ");
        if ($temp) 
            $row[bo_subject] = $temp[bo_subject];
        else
            $row[bo_subject] = $row[bo_table];

        $bo_str .= "<option value='$row[bo_table]'";
        if ($sfl=='po_rel_table' and $row['bo_table'] == $stx) $bo_str .= " selected";
        $bo_str .= ">" . cut_str($row[bo_subject],40) . "</option>";
}
$bo_str .= "</select>";
?>
<div class="container">
<div class="panel panel-default">
<div class="panel-heading">
    <?=$g4[title]?> (<?=number_format($member[mb_point])?>)

    <div class="pull-right">
        <a class="btn btn-default" style="display:inline;" href="javascript:window.close();">닫기</a>
    </div>
</div>
<div class="panel-body">
    <div class="input-group">
        <span class="input-group-btn">
            <a class="btn btn-default" href="<?=$_SERVER[PHP_SELF]?>">처음</a>
        </span>
        <?=$bo_str?>
    </div>
    <table width=100% class="table table-hover">
    <tr class="success" align=center>
        <td class="col-sm-1">일시</td>
        <td>내용</td>
        <td class="col-sm-1">지급</td>
        <td class="col-sm-1">사용</td>
    </tr>
    <?
    $sum_point1 = $sum_point2 = 0;

    $point_count = count($point_list);
    for ($i=0; $i < $point_count; $i++) {
        $point1 = $point2 = 0;
        if ($point_list[$i][po_point] > 0) {
            $point1 = "+" . number_format($point_list[$i][po_point]);
            $sum_point1 += $point_list[$i][po_point];
        } else {
            $point2 = number_format($point_list[$i][po_point]);
            $sum_point2 += $point_list[$i][po_point];
        }
    ?>
    <tr> 
        <td><?=get_date($point_list[$i][po_datetime])?></td>
        <td align="left" title='<?=$point_list[$i][po_content]?>'>
            <?
            if ($point_list[$i][po_url])
                echo "<a href='{$point_list[$i][po_url]}' target=_new>" . $point_list[$i][po_content] . "</a>";
            else
                echo $point_list[$i][po_content];
            ?>
        </td>
        <td align=right><?=$point1?>&nbsp;</td>
        <td align=right><?=$point2?>&nbsp;</td>
        </tr>
        <? } ?>
        <?
        if ($i == 0)
            echo "<tr><td colspan=4 align=center height=100>자료가 없습니다.</td></tr>";
        else {
            if ($sum_point1 > 0)
               $sum_point1 = "+" . number_format($sum_point1);
            $sum_point2 = number_format($sum_point2);
            echo <<<HEREDOC
            <tr bgcolor="#E1E1E1" align="center"> 
                <td height="24" colspan=2 align=center>소계</td>
                <td align=right>{$sum_point1}&nbsp;</td>
                <td align=right>{$sum_point2}&nbsp;</td>
            </tr>
HEREDOC;
        }
        ?>
    </table>

    <!-- 페이지 -->
    <div class="center-block">
        <ul class="pagination">
        <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>이전검색</a></li>"; } ?>
        <?
        // 기본으로 넘어오는 페이지를 아래와 같이 변환하여 다양하게 출력할 수 있습니다.
        $write_pages = str_replace("이전", "<i class='fa fa-angle-left'></i>", $write_pages);
        $write_pages = str_replace("다음", "<i class='fa fa-angle-right'></i>", $write_pages);
        $write_pages = str_replace("처음", "<i class='fa fa-angle-double-left'></i>", $write_pages);
        $write_pages = str_replace("맨끝", "<i class='fa fa-angle-double-right'></i>", $write_pages);
        ?>
        <?=$write_pages?>
        <? if ($next_part_href) { echo "<li><a href='$next_part_href'>이후검색</a></li>"; } ?>
        </ul>
    </div>

</div>
</div>
</div>
