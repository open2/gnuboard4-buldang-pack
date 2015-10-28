<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

if ($is_admin)
    $where_sql = "";
else
    $where_sql = " where gr_use_search = 1 ";

$sql = " select gr_id, gr_subject from $g4[group_table] $where_sql order by gr_id ";
$result = sql_query($sql);

$group_select = "<select class=\"form-control\" id='gr_id' name='gr_id' class=select><option value=''>전체 그룹";
for ($i=0; $row=sql_fetch_array($result); $i++)
    $group_select .= "<option value='$row[gr_id]'>$row[gr_subject]";
$group_select .= "</select>";
?>
<form name=fsearch method=get onsubmit="return fsearch_submit(this);" role="form" class="form-inline" >
<input type="hidden" name="srows" value="<?=$srows?>">
        <?=$group_select?>
        <script type="text/javascript">document.getElementById("gr_id").value = "<?=$gr_id?>";</script>

        <select class="form-control" name=sfl class=select>
            <option value="wr_subject||wr_content">제목+내용</option>
            <option value="wr_subject">제목</option>
            <option value="wr_content">내용</option>
            <option value="mb_id">회원아이디</option>
            <option value="wr_name">이름</option>
        </select>
    		<select class="form-control">
            <option name="sop" value="and" checked>그리고</option>
            <option name="sop" value="or" >또는</option>
        </select>
        <div class="form-group">
            <input class="form-control" type="text" name=stx maxlength=20 required itemname="검색어" value='<?=$text_stx?>'> 
        </div>

        <input class="btn btn-default" type=submit value=" 검 색 ">

        <script type="text/javascript">
        document.fsearch.sfl.value = "<?=$sfl?>";

        function fsearch_submit(f) {
            if (f.stx.value.length < 2) {
                alert("검색어는 두글자 이상 입력하십시오.");
                f.stx.select();
                f.stx.focus();
                return false;
            }

            // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
            var cnt = 0;
            for (var i=0; i<f.stx.value.length; i++) {
                if (f.stx.value.charAt(i) == ' ')
                    cnt++;
            }

            if (cnt > 1) {
                alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
                f.stx.select();
                f.stx.focus();
                return false;
            }
            
            f.action = "";
            return true;
        }
        </script>
</form>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>게시판 : <?=$board_count ?> 게시글 : <?=number_format($total_count)?></strong>
        <div class="pull-right hidden-lg hidden-md hidden-sm">
            <a class="btn btn-default btn-sm" data-toggle="collapse" data-target=".search-board-collapse"><i class='fa fa-align-justify'></i></a>
        </div>
    </div>
    <div class="panel-body">

    <div class="collapse navbar-collapse search-board-collapse" style="margin-bottom:15px;">
    <? if ($board_count == 0) { ?>
        검색된 자료가 하나도 없습니다.
    <? } else {
        if ($onetable)
            echo "<a class=\"btn btn-default btn-success\" href='?$search_query&gr_id=$gr_id'>전체게시판 검색</a>";

        for ($i=0; $i<count($search_table); $i++) { ?>
            <a class="btn btn-default" href="<?=$_SERVER[PHP_SELF]?>?<?=$search_query?>&gr_id=<?=$gr_id?>&onetable=<?=$search_table[$i]?>"><?=$search_table_subject[$i]?> <sup><?=$search_table_result_count[$i]?></sup></a>
        <? }
    } ?>
    </div>

    <? 
    $k=0;
    for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) 
    { 
        echo "<a href='./board.php?bo_table={$search_table[$idx]}&{$search_query}'><strong>{$bo_subject[$idx]}</strong></a>에서의 검색결과";
        $comment_href = "";
        echo "<ul style='margin-bottom:15px;margin-top:5px;'>";
        for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) 
        {
            echo "<li>";
            if ($list[$idx][$i][wr_is_comment]) 
            {
                echo "<font color=999999>[코멘트]</font> ";
                $comment_href = "#c_".$list[$idx][$i][wr_id];
            }
            echo "<a href='{$list[$idx][$i][href]}{$comment_href}'>";
            echo $list[$idx][$i][subject];
            echo "</a>";
            echo "&nbsp;<a class=\"btn btn-default btn-xs\" href='{$list[$idx][$i][href]}{$comment_href}' target=_blank>새창</a>&nbsp;";
            echo "<font color=#999999>{$list[$idx][$i][wr_datetime]}</font>&nbsp;";
            echo $list[$idx][$i][name];
            echo "<p>";
            echo $list[$idx][$i][content];
            echo "</p>";
            echo "</li>";
        }
        echo "</ul>";
    }
    ?>
    </div>
</div>

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
