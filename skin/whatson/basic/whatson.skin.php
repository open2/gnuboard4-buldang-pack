<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$whatson_url = "$g4[bbs_path]/whatson.php?check=1&rows=30";
?>

<script type="text/javascript" src="<?=$g4[admin_path]?>/admin.js"></script>
<script type="text/javascript">
var list_delete_php = "whatson_delete_all.php";
</script>

<form role="form" class="form-inline" name=fsingolist method=post>
<input type=hidden name=head  value='<?=$head?>'>
<input type=hidden name=check value='<?=$check?>'>
<input type=hidden name=rows  value='<?=$rows?>'>
<div class="panel panel-default">
    <div class="panel-heading"><a href="<?=$whatson_url?>">What's On</a>
    <? if ($total_count > 0) { ?>(<?=$total_count?>)<?}?>
    <span class="pull-right">
    <a href="<?=$whatson_url?>"><small>more</small></a>
    </span>
    </div>
    <div class="panel-body">
        <ul class="list-unstyled">
        <?
        if (count($list) == 0) {
            echo "<li>내용없슴</li>";
        } else {

            for ($i=0; $i<count($list); $i++) {

                echo "<li>";
                if ($check == 1) {
                    echo $row[wo_id];
                    echo "<input type=hidden name=wo_id[$i] value='{$list[$i][wo_id]}'>";
                    echo "<label class='checkbox-inline'><input type=checkbox name=chk[] value='$i'></label>&nbsp;";
                }

                // 이미 읽은 글은 바로 새창, 아니면, ajax로 읽은거 mark 한 후에 새창
                if ($list[$i][wo_status])
                    echo "<a href='" . $list[$i][url]  . "' $target_link >";
                else
                    echo "<a href='javascript: void(0)' onclick='javascript:whatson_read(\"" . $list[$i][url] . "\", " . $list[$i][wo_id] . ");return false;'>";

                // 이미 읽은 글은 회색으로 표시
                if ($list[$i][wo_status])
                    echo "<span class='text-muted'>";

                echo "(" . $list[$i][wo_count] . ")"; 
                echo " " . $list[$i][subject];
                if ($list[$i][wo_status])
                    echo "</span>";

                echo "</a>";

                if ($check == 1) {
                echo "&nbsp;<small>" . $list[$i][datetime] . "</small>&nbsp;";

                $delete_href = "javascript:del('" . $g4[bbs_path] . "/ajax_whatson.php?w=d&page=$page&rows=$rows&check=$check&wo_id=".$list[$i][wo_id]."');";
                echo " " . "<a href=\"$delete_href\" ><i class=\"fa fa-trash-o\"></i></a> ";
                }
                echo "</li>";
        }
    }
    ?>
  	</ul>

    <? if ($check == 1 && $i>0) { ?>
        <label for=chkall><input type="checkbox" name=chkall id=chkall value='1' onclick='check_all(this.form)'>&nbsp;전체선택</label>&nbsp;&nbsp;
        <input type="button" class="btn btn-default" value='선택삭제' onclick="btn_check(this.form, 'delete')">
    <? } ?>

    </div>
</div>
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

<script type="text/javascript">
function whatson_read(url, wo_id) {

	var post_url = "<?=$g4[bbs_path]?>/ajax_whatson.php?w=r&wo_id="+wo_id;
  var data = "";

	$.ajax({
		type:"POST",
		url:post_url,
		data:data,
		async:false,
		error:function() {
			alert('fail');
		},
		success: function(){
		    <? if ($target) { ?>
        parent.<?=$target?>.location.href = url ;
		    <? } else { ?>
        location.href = url;
        <? } ?>
		}
	});
	
	return false;
}
</script>