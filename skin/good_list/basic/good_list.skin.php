<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<? if ($is_admin) { ?>
<script type="text/javascript">
function all_checked(sw) {  //ssh
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_gl_id[]")
            f.elements[i].checked = sw;
    }
}

function select_new_batch(sw){////ssh06-04-12
    var f = document.fboardlist;
    if (sw == 'r')
        str = "베스트글에 복구";
    else
        str = "베스트글에서 제외";

    f.sw.value = sw;
    //f.target = "hiddenframe";

    if (!confirm("선택한 게시물을 정말 "+str+" 하시겠습니까?"))
        return;

    f.action = "<?=$g4[admin_path]?>/ssh_delete_good_list.php";
    f.submit();
}
</script>
<? } ?>

<a class="btn btn-default" href="<?=$g4[bbs_path]?>/good_list.php">처음으로
<? if ($total_count > 0) {?>&nbsp;(<?=number_format($total_count)?>)<?}?>
</a>

<form name="fboardlist" method="post" role="form" class="form-inline">
<input type="hidden" name="sw"   value="">	
<input type="hidden" name="gr_id"   value="<?=$gr_id?>">	
<input type="hidden" name="view"   value="<?=$view?>">	
<input type="hidden" name="mb_id"   value="<?=$mb_id?>">	

<table width="100%" class="table table-hover table-condensed">
<tr class="success" align=center> 
    <td class="col-sm-2 hidden-xs">게시판</td>
    <td>제목
    <span class="pull-right hidden-xs">
    <?
    if ($is_admin) {
    ?>
        <label><INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox>전체선택</label>&nbsp;&nbsp;
    <? if ($gl_flag == 1) {
    ?>
        <a href="javascript:select_new_batch('r');">베스트글복구</a>&nbsp;&nbsp;
        <a href="./good_list.php?gl_id=<?=$gl_id?>&bo_table=<?=$bo_table?>&gl_flag=0">전체글목록</a>&nbsp;&nbsp;
    <? } else { ?>
        <a href="javascript:select_new_batch('d');">베스트글제외</a>&nbsp;&nbsp;
        <a href="./good_list.php?gl_id=<?=$gl_id?>&bo_table=<?=$bo_table?>&gl_flag=1">제외된글목록</a>&nbsp;&nbsp;
    <? } } ?>
    </span>
    </td>
    <td class="col-sm-2 hidden-xs">글쓴이</td>
    <td class="col-sm-1 hidden-xs"><?=subject_sort_link('wr_datetime', $qstr2, 1)?>날짜</a></td>
    <td class="col-sm-1 hidden-xs">조회</td>
</tr>
<?
for ($i=0; $i<count($list); $i++) {
    $bo_subject = cut_str($list[$i][bo_subject], 10);
    $wr_subject = get_text(cut_str($list[$i][wr_subject], 40));
?>
<tr align=center>
    <td align="center" class="hidden-xs"><a href='./good_list.php?bo_table_search=<?=$list[$i][bo_table]?>'><?=$bo_subject?></a></td>
    <td align=left class="hidden-xs">
    <?
    if ($is_admin) {
          echo "<input type=checkbox name=chk_gl_id[] value='{$list[$i][gl_id]}'>&nbsp;";
    }
    ?>
    <a href='<?=$list[$i][href]?>'><?=$wr_subject?></a>
    <? if ($list[$i][wr_comment]) echo "<span style='font-family:Tahoma;font-size:10px;color:#EE5A00;'>(" . $list[$i][wr_comment] . ")</span>"?>
    
    </td>
    <td align="center" class="hidden-xs"><?=$list[$i][name]?></td>
    <td align="center" class="hidden-xs"><?=$list[$i][wr_datetime2]?></td>
    <td align="center" class="hidden-xs"><?=$list[$i][wr_hit]?></td>
    <!-- 
    xs 사이즈에서 30글자 이상이면 table width를 넘어서 수평 스크롤이 생깁니다 
    그래서, 따로 출력하는 row를 만들어 줬습니다.
    xs 사이즈에서는 아래처럼 1개의 td만 출력 됩니다. 다른 것은 모두 hidden.
    더 좋은 방법에 대한 제안은 언제든 환영 합니다.
    -->
    <td align=left class="visible-xs" style='word-break:break-all;'>
        <div>
            <a href='<?=$list[$i][href]?>'><?=cut_str($wr_subject,30)?></a>
            <? if ($list[$i][wr_comment]) echo "<span style='color:#EE5A00;'>(" . $list[$i][wr_comment] . ")</span>"?>
            <span class="pull-right"><font style="color:#BABABA;"><?=$bo_subject?></font></span>
        </div>
        <span class="visible-xs pull-right">
        <font style="color:#BABABA;">
        <?=$list[$i][datetime2]?>&nbsp;&nbsp;
        <?=$list[$i][wr_hit]?>&nbsp;&nbsp;
        </font>
        <?=$list[$i][name]?>
        </span>
    </td>
</tr>
<?}?>
<? if ($i == 0) { ?>
<tr><td colspan="5" height=50 align=center>게시물이 없습니다.</td></tr>
<? } ?>
</table>
</form>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
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
<div class="center-block visible-xs">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>이전검색</a></li>"; } ?>
    <?
    // 기본으로 넘어오는 페이지를 아래와 같이 변환하여 다양하게 출력할 수 있습니다.
    $write_pages_xs = str_replace("이전", "<i class='fa fa-angle-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("다음", "<i class='fa fa-angle-right'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("처음", "<i class='fa fa-angle-double-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("맨끝", "<i class='fa fa-angle-double-right'></i>", $write_pages_xs);
    ?>
    <?=$write_pages_xs?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>이후검색</a></li>"; } ?>
    </ul>
</div>
