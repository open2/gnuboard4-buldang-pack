<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 게시판 목록별로 정렬하기
$sql = " select distinct a.bo_table, b.bo_subject from $g4[scrap_table] a left join $g4[board_table] b on a.bo_table=b.bo_table where a.mb_id = '$member[mb_id]' ";
$result = sql_query($sql);
$str = "<select class='form-control' name='bo_table' onchange=\"location='$g4[bbs_path]/scrap.php?head_on=$head_on&mnb=$mnb&snb=$snb&sfl=bo_table&stx='+this.value;\">";
$str .= "<option value='all'>전체게시판</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $str .= "<option value='$row[bo_table]'";
        if ($sfl=='bo_table' and $row[bo_table] == $stx) $str .= " selected";
        $str .= ">$row[bo_subject]</option>";
    }
    $str .= "</select>";

// 스크랩 메모별로 정렬하기
$sql = " select distinct ms_memo from $g4[scrap_table] where mb_id = '$member[mb_id]' and ms_memo != '' ";
$result = sql_query($sql);
$memo_str0 = "<select class='form-control' name='ms_memo' onchange=\"location='$g4[bbs_path]/scrap.php?head_on=$head_on&mnb=$mnb&snb=$snb&sfl=ms_memo&stx='+this.value;\">";
$memo_str = "<option value='all'>전체메모</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $memo_str .= "<option value='$row[ms_memo]'";
        if ($sfl=='ms_memo' and $row[ms_memo] == $stx) $memo_str .= " selected";
        $memo_str .= ">" . cut_str($row[ms_memo],30,'') . "</option>";
    }
    $memo_str .= "</select>";
$memo_str_list = $memo_str0 . $memo_str;
?>

<div class="container">

<form name=fsearch method=get role="form" class="form-inline">
<input type=hidden name=head_on value="<?=$head_on?>">
<input type=hidden name=mnb value="<?=$mnb?>">
<input type=hidden name=snb value="<?=$snb?>">

<a class="btn btn-default" href="<?=$g4[bbs_path]?>/scrap.php?head_on=<?=$head_on?>&mnb=<?=$mnb?>&snb=<?=$snb?>">처음</a>
<div class="pull-right hidden-lg hidden-md hidden-sm">
    <a class="btn btn-default" data-toggle="collapse" data-target=".scrap-search-collapse"><i class='fa fa-search'></i></a>
</div>

<span class="">
<?=$str?><?=$memo_str_list?>
</span>

<div class="pull-right collapse navbar-collapse scrap-search-collapse">
    <select class='form-control' name=sfl class=cssfl>
        <option value='wr_subject_memo' <? if ($sfl=='wr_subject_memo') echo "selected" ?> >제목+메모</option>
        <option value='wr_subject' <? if ($sfl=='wr_subject') echo "selected" ?> >제목</option>
        <option value='ms_memo' <? if ($sfl=='ms_memo') echo "selected" ?> >메모</option>
        <option value='wr_mb_id' <? if ($sfl=='wr_mb_id') echo "selected" ?> >글쓴이(아이디+별명)</option>
        <option value='bo_table' <? if ($sfl=='bo_table') echo "selected" ?> >게시판</option>
    </select>
    <div class="form-group">
        <input class="form-control" type=text name=stx required itemname='검색어' value='<? echo $stx ?>'>
    </div>
    <input class="btn btn-default" type=submit value='검색'>
</div>
</form>

<table width="100%" class="table table-hover table-condensed">
<tr class="success"> 
    <td class="col-sm-1 hidden-xs" align=center>번호</td>
    <td class="col-sm-2 hidden-xs">게시판</td>
    <td>제목</td>
    <td class="col-sm-2 hidden-xs">글쓴이</td>
    <td class="col-sm-2 hidden-xs">메모</td>
    <td class="col-sm-1 hidden-xs">날짜</td>
</tr>
<? for ($i=0; $i<count($list); $i++) { ?>
    <tr> 
        <td class="hidden-xs" align="center"><?=$list[$i][num]?></td>
        <td class="hidden-xs">
        <? if ($head_on) { ?>
            <a href="<?=$list[$i][opener_href]?>">
        <? } else { ?>
            <a href="javascript:;" onclick="opener.document.location.href='<?=$list[$i][opener_href]?>';">
        <? } ?>
        <?=$list[$i][bo_subject]?></a>
        </td>
        <? // 비밀글인 스크랩의 경우 비밀글 아이콘을 앞에 표시
        if ($list[$i][secret]) 
            $secret_icon = "<i class=\"fa fa-lock\"></i>&nbsp;";
        else
            $secret_icon = "";
        ?>
        <td class="hidden-xs" align="left" style='word-break:break-all;'><?=$secret_icon?>
        <? if ($head_on) { ?>
            <a href="<?=$list[$i][opener_href_wr_id]?>" title="<?=$list[$i][subject]?>">
        <? } else { ?>
            <a href="javascript:;" onclick="opener.document.location.href='<?=$list[$i][opener_href_wr_id]?>';" title="<?=$list[$i][subject]?>">
        <? } ?>
        <?=cut_str($list[$i][wr_subject],80)?></a>
        <a href="javascript:del('<?=$list[$i][del_href]?>');"><i class="fa fa-trash-o"></i></a>
        </td>
        <td class="hidden-xs"><?=$list[$i][mb_nick]?></td>
        <td class="hidden-xs" align="left" style='word-break:break-all;'><a href="#" title="<?=$list[$i][ms_memo]?>"><?=$list[$i][ms_memo]?></a>
        &nbsp;<a class="btn btn-default btn-xs" href="javascript:memo_box(<?=$list[$i][ms_id]?>)"><i class="fa fa-pencil-square-o"></i></a>

        <span id='memo_<?=$list[$i][ms_id]?>' style='display:none;'>
            <div class="input-group" style="margin:5px 0;">
            <input type="type" class="form-control" placeholder="scrap memo" name="memo_edit_<?=$list[$i][ms_id]?>" id="memo_edit_<?=$list[$i][ms_id]?>" size="50" value="<?=preg_replace("/\"/", "&#034;", stripslashes(get_text($list[$i][ms_memo],0)))?>" />
            <span class="input-group-btn">
            <a class="btn btn-default" href='javascript:memo_update(<?=$list[$i][ms_id]?>)'>write</a>
            </span>
            </div>
            <?
            $memo_str_tmp = "<select class='form-control' name='ms_memo_{$list[$i][ms_id]}' onchange=\"javascript:document.getElementById('memo_edit_{$list[$i][ms_id]}').value=this.value;\">";
            echo $memo_str_tmp . $memo_str;
            ?>
        </span>

        </td>
        <td class="hidden-xs"><?=get_date($list[$i][ms_datetime])?></td>
        <!-- xs... 모바일 상태에서 나오는 것. 메모편집은 따로 해야 하기에 한번 더 copy 하면서 수정 -->
        <td class="visible-xs">
            <? if ($head_on) { ?>
                <a href="<?=$list[$i][opener_href_wr_id]?>">
            <? } else { ?>
                <a href="javascript:;" onclick="opener.document.location.href='<?=$list[$i][opener_href_wr_id]?>';">
            <? } ?>
            <?=cut_str($list[$i][wr_subject],60)?></a>
            <a href="javascript:del('<?=$list[$i][del_href]?>');"><i class="fa fa-trash-o"></i></a>
            <br>
            <div class="pull-left">
                <?=$list[$i][bo_subject]?>&nbsp;&nbsp;<a class="btn btn-default btn-xs" href="javascript:memo_box('<?=$list[$i][ms_id]?>_1')"><i class="fa fa-pencil-square-o"></i></a> <?=$list[$i][ms_memo]?>

                <!-- id가 같으면 충돌하기 때문에 _1을 뒤에 붙여서 구분 합니다 -->
                <span id='memo_<?=$list[$i][ms_id]?>_1' style='display:none;'>
                    <div class="input-group" style="margin:5px 0;">
                    <input type="type" class="form-control" placeholder="scrap memo" name="memo_edit_<?=$list[$i][ms_id]?>_1" id="memo_edit_<?=$list[$i][ms_id]?>_1" size="50" value="<?=preg_replace("/\"/", "&#034;", stripslashes(get_text($list[$i][ms_memo],0)))?>" />
                    <span class="input-group-btn">
                    <a class="btn btn-default" href="javascript:memo_update('<?=$list[$i][ms_id]?>_1')">write</a>
                    </span>
                    </div>
                    <?
                    $memo_str_tmp = "<select class='form-control' name='ms_memo_{$list[$i][ms_id]}' onchange=\"javascript:document.getElementById('memo_edit_{$list[$i][ms_id]}_1').value=this.value;\">";
                    echo $memo_str_tmp . $memo_str;
                    ?>
                </span>

            </div>
            <div class="pull-right">
                <?=$list[$i][mb_nick]?>&nbsp;&nbsp;<?=get_date($list[$i][ms_datetime])?>
            </div>
        </td>
    </tr>
<? } ?>

    <? if ($i == 0) echo "<tr><td colspan=5 align=center height=100>자료가 없습니다.</td></tr>"; ?>
</table>

<? $write_pages = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");?>
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
<div class="container"  style="display: inline-block;text-align: center;">
    <a class="btn btn-default" href="javascript:window.close();" >닫기</a>
</div>
<? } ?>

</div>

<form name=flist method=post>
<input type="hidden" class="ed" id="memo_edit" name="memo_edit" value="<?=$memo_edit?>" />
</form>

<script type="text/javascript">
var save_before = '';

function memo_box(memo_id)
{
    var el_id;

    el_id = 'memo_' + memo_id;

    if (save_before != el_id) {
      
        if (save_before)
        {
            document.getElementById(save_before).style.display = 'none';
        }

        document.getElementById(el_id).style.display = 'block';
        save_before = el_id;
    } else {
        if (save_before)
            if (document.getElementById(save_before).style.display == 'none')
                document.getElementById(save_before).style.display = 'block';
            else
                document.getElementById(save_before).style.display = 'none';
    }
    
}

// 선택한 메모를 업데이트
function memo_update(ms_id) {
    var f = document.flist;
    var el_id;

    el_id = 'memo_edit_' + ms_id;
    document.getElementById('memo_edit').value = document.getElementById(el_id).value;
    f.action = "<?=$member_skin_path?>/scrap_memo_update.php?ms_id=" + ms_id + "&head_on=<?=$head_on?>&mnb=<?=$mnb?>&snb=<?=$snb?>";
    f.submit();
}
</script>
