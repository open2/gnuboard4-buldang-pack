<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 게시판 목록별로 정렬하기
$sql = " select distinct a.bo_table, b.bo_subject from $g4[board_good_table] a left join $g4[board_table] b on a.bo_table=b.bo_table where a.mb_id = '$member[mb_id]' ";
$result = sql_query($sql);
$str = "<select class='form-control' name='bo_table' id='$bo_table' onchange=\"location='$g4[bbs_path]/my_good.php?head_on=$head_on&mnb=$mnb&snb=$snb&sfl=bo_table&stx='+this.value;\">";
$str .= "<option value='all'>전체목록보기</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $str .= "<option value='$row[bo_table]'";
        if ($sfl=='bo_table' and $row[bo_table] == $stx) $str .= " selected";
        $str .= ">$row[bo_subject]</option>";
    }
    $str .= "</select>";
?>

<form name=fsearch method=get role="form" class="form-inline" style="margin-bottom:5px;">
<input type=hidden name=head_on value="<?=$head_on?>">
<input type=hidden name=mnb value="<?=$mnb?>">
<input type=hidden name=snb value="<?=$snb?>">
<a class="btn btn-default" href="<?=$g4[bbs_path]?>/my_good.php?head_on=<?=$head_on?>&mnb=<?=$mnb?>&snb=<?=$snb?>">처음</a>
<span class="pull-right"><?=$str?></span>
</form>

<table width="100%" class="table table-hover">
<tr class="success" align=center> 
    <td class="col-sm-1">번호</td>
    <td class="col-sm-2">게시판</td>
    <td>제목</td>
    <td class="col-sm-1 hidden-xs">글쓴이</td>
    <td class="col-sm-1 hidden-xs">추천날짜</td>
</tr>
<? for ($i=0; $i<count($list); $i++) { ?>
    <tr align="center"> 
        <td height="24"><?=$list[$i][num]?></td>
        <td>
            <? if ($head_on) { ?>
                <a href="<?=$list[$i][opener_href]?>">
            <? } else { ?>
                <a href="javascript:;" onclick="opener.document.location.href='<?=$list[$i][opener_href]?>';">
            <? } ?>
            <?=$list[$i][bo_subject]?></a>
        </td>
        <td align="left" style='word-break:break-all;'>
            <? // 비밀글인 스크랩의 경우 비밀글 아이콘을 앞에 표시
            if ($list[$i][secret]) 
                $secret_icon = "<i class=\"fa fa-lock\"></i>";
            else
                $secret_icon = "";
            if ($secret_icon)
                echo $secret_icon . "&nbsp;&nbsp;";
            ?>
            <? if ($head_on) { ?>
                <a href="<?=$list[$i][opener_href_wr_id]?>" title="<?=$list[$i][subject]?>">
            <? } else { ?>
                <a href="javascript:;" onclick="opener.document.location.href='<?=$list[$i][opener_href_wr_id]?>';" title="<?=$list[$i][subject]?>">
            <? } ?>
            <?=cut_str($list[$i][wr_subject],65)?></a>

            <? if ($list[$i][del_href]) { ?>
                <a href="javascript:del('<?=$list[$i][del_href]?>');"><i class="fa fa-trash-o"></i></a>
            <? } ?>
            <div class="visible-xs"><?=$list[$i][mb_nick]?> <small><?=get_date($list[$i][bg_datetime])?></small>
        </td>
        <td class="hidden-xs"><?=$list[$i][mb_nick]?></td>
        <td class="hidden-xs"><?=get_date($list[$i][bg_datetime])?></td>
    </tr>
<? } ?>

<? if ($i == 0) echo "<tr><td colspan=5 align=center height=100>자료가 없습니다.</td></tr>"; ?>
</table>

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

<? if (!$head_on) { ?>
    <a class="btn btn-default" href="javascript:window.close();">Close</a>
<? } ?>
