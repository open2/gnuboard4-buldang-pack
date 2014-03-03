<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<? if ($is_admin) { ?>
<script type="text/javascript">
function all_checked(sw) {  //ssh
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function select_new_batch(sw){////ssh06-04-12
    var f = document.fboardlist;
    str = "삭제";
    f.sw.value = sw;
    //f.target = "hiddenframe";

    if (!confirm("선택한 게시물을 정말 "+str+" 하시겠습니까?\n\n한번 "+str+"한 자료는 복구할 수 없습니다"))
        return;

    f.action = "<?=$g4[admin_path]?>/ssh_delete_spam.php";
    f.submit();
}
</script>
<? } ?>

<!-- 분류 시작 -->
<form name=fnew method=get role="form" class="form-inline" style="margin-bottom:5px;">
<a class="btn btn-default" href="<?=$g4[bbs_path]?>/new.php">처음으로&nbsp;(<?=number_format($total_count)?>)</a>
<div class="pull-right">
    <?
    // bbs/new.php에서 하던 $group_select를 new.skin.php로 이동. select에 style을 넣기 위해서.
    $group_select = "<select class='form-control' name=gr_id id=gr_id onchange='select_change();'><option value=''>전체그룹";
    for ($i=0; $i < count($group_select_list); $i++) {
        $row = $group_select_list[$i];
        $group_select .= "<option value='$row[gr_id]'>$row[gr_subject]";
    }
    $group_select .= "</select>";
    ?>
    <?=$group_select?>
    <select class="form-control" id=view_type name=view_type onchange="select_change()">
        <option value=''>원글만
        <option value='c'>코멘트만
        <option value='a'>전체게시물
    </select>

    <? if ($member[mb_id]) { ?>
    <div class="form-group">
        <input class="form-control" type="text" id="mb_id" name="mb_id" value="<?=$mb_id?>" placeholder="회원 아이디">
    </div>
    <input class="btn btn-default" type=submit value='검색'>
    <? } ?>
</div>

<script type="text/javascript">
function select_change() {
    var f = document.fnew;

    f.action = "<?=$g4[bbs_path]?>/new.php";
    f.submit();
}

document.getElementById("gr_id").value = "<?=$gr_id?>";
document.getElementById("view_type").value = "<?=$view_type?>";
</script>
</form>
<!-- 분류 끝 -->

<form name="fboardlist" method="post" role="form" class="form-inline"">
<input type="hidden" name="sw"   value="">	
<input type="hidden" name="gr_id"   value="<?=$gr_id?>">
<input type="hidden" name="view"   value="<?=$view_type?>">
<input type="hidden" name="mb_id"   value="<?=$mb_id?>">
<input type="hidden" name="page"   value="<?=$page?>">
<input type="hidden" name="view_type"   value="<?=$view_type?>">

<!-- 제목 시작 -->
<table width="100%" class="table table-hover table-condensed">
<tr class="success" align=center> 
    <td class="col-sm-1 hidden-xs">그룹</td>
    <td class="col-sm-2 hidden-xs">게시판</td>
    <td align=left>
        <?  if ($is_admin == "super") { ?>
            <INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox>&nbsp;&nbsp;
        <? } ?>
        제목
    </td>
    <td class="col-sm-1 hidden-xs">글쓴이</td>
    <td class="col-sm-1 hidden-xs">날짜</td>
    <td class="col-sm-1 hidden-xs">조회</td>
</tr>

<?
for ($i=0; $i<count($list); $i++) 
{
    $gr_subject = cut_str($list[$i][gr_subject], 10);
    $bo_subject = cut_str($list[$i][bo_subject], 10);
    $wr_subject = get_text(cut_str($list[$i][wr_subject], 40));
    ?>
    <tr align=center>
    <td class="hidden-xs"><a href='./new.php?gr_id=<?=$list[$i][gr_id]?>'><?=$gr_subject?></a></td>
    <td class="hidden-xs"><a href='./new.php?bo_table_search=<?=$list[$i][bo_table]?>&mb_id=<?=$mb_id?>&gr_id=<?=$gr_id?>'><?=$bo_subject?></a></td>
    <td align=left class="hidden-xs">
    <?
    if ($is_admin) {
      if ($list[$i][comment])
          echo "<input type=checkbox name=chk_wr_id[] value='{$list[$i][comment_id]}|{$list[$i][bo_table]}'>";
      else
          echo "<input type=checkbox name=chk_wr_id[] value='{$list[$i][wr_id]}|{$list[$i][bo_table]}'>";
      echo "&nbsp;";
    }

    // 코멘트 갯수를 출력
    $comment_cnt = "";
    if (!$list[$i][comment] && $list[$i][wr_comment] > 0)
        $comment_cnt = " <span style='font-family:Tahoma;font-size:10px;color:#EE5A00;'>({$list[$i][wr_comment]})</span>";
    ?>
    <a href='<?=$list[$i][href]?>'><?=$list[$i][comment]?><?=$wr_subject?></a> <?=$comment_cnt?>
    <div class="visible-xs"><?=$list[$i][name]?> <small><?=$list[$i][datetime2]?></small>
    </div>
    </td>
    <td class="hidden-xs"><?=$list[$i][name]?></td>
    <td class="hidden-xs"><?=$list[$i][datetime2]?></td>
    <td class="hidden-xs"><?=$list[$i][wr_hit]?></td>
    <!-- 
    xs 사이즈에서 40글자 이상이면 table width를 넘어서 수평 스크롤이 생깁니다 
    그래서, 따로 출력하는 row를 만들어 줬습니다.
    xs 사이즈에서는 아래처럼 1개의 td만 출력 됩니다. 다른 것은 모두 hidden.
    더 좋은 방법에 대한 제안은 언제든 환영 합니다.
    -->
    <td class="visible-xs" align=left style='word-break:break-all;'>
        <div>
            <a href='<?=$list[$i][href]?>'><?=$list[$i][comment]?><?=$wr_subject?></a> <?=$comment_cnt?>
        </div>
        <span class="pull-left">
            <a href='./new.php?gr_id=<?=$list[$i][gr_id]?>'><font color="BABABA"><?=$gr_subject?></font></a> : <a href='./new.php?bo_table_search=<?=$list[$i][bo_table]?>&mb_id=<?=$mb_id?>&gr_id=<?=$gr_id?>'><font color="BABABA"><?=$bo_subject?></font></a>
        </span>
        <span class="pull-right">
            <font style="color:#BABABA;">
            <?=$list[$i][datetime2]?>&nbsp;&nbsp;
            <?=$list[$i][wr_hit]?>&nbsp;&nbsp;
            <?=$list[$i][name]?>
            </font>
        </span>
    </td>
</tr>
<? } ?>

<? if ($i == 0) { ?>
<tr><td colspan="5" height=50 align=center>게시물이 없습니다.</td></tr>
<? } ?>
</table>

<? if ($is_admin == "super") { ?>
    <a class="btn btn-default pull-right" href="javascript:select_new_batch('d');">선택삭제</a>
<? } ?>

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
