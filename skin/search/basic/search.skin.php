<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

if ($is_admin)
    $where_sql = "";
else
    $where_sql = " where gr_use_search = 1 ";

$sql = " select gr_id, gr_subject from $g4[group_table] $where_sql order by gr_id ";
$result = sql_query($sql);

$group_select = "<select class=\"form-control\" id='gr_id' name='gr_id' class=select><option value=''>��ü �׷�";
for ($i=0; $row=sql_fetch_array($result); $i++)
    $group_select .= "<option value='$row[gr_id]'>$row[gr_subject]";
$group_select .= "</select>";
?>
<form name=fsearch method=get onsubmit="return fsearch_submit(this);" role="form" class="form-inline" >
<input type="hidden" name="srows" value="<?=$srows?>">
        <?=$group_select?>
        <script type="text/javascript">document.getElementById("gr_id").value = "<?=$gr_id?>";</script>

        <select class="form-control" name=sfl class=select>
            <option value="wr_subject||wr_content">����+����</option>
            <option value="wr_subject">����</option>
            <option value="wr_content">����</option>
            <option value="mb_id">ȸ�����̵�</option>
            <option value="wr_name">�̸�</option>
        </select>
    		<select class="form-control">
            <option name="sop" value="and" checked>�׸���</option>
            <option name="sop" value="or" >�Ǵ�</option>
        </select>
        <div class="form-group">
            <input class="form-control" type="text" name=stx maxlength=20 required itemname="�˻���" value='<?=$text_stx?>'> 
        </div>

        <input class="btn btn-default" type=submit value=" �� �� ">

        <script type="text/javascript">
        document.fsearch.sfl.value = "<?=$sfl?>";

        function fsearch_submit(f) {
            if (f.stx.value.length < 2) {
                alert("�˻���� �α��� �̻� �Է��Ͻʽÿ�.");
                f.stx.select();
                f.stx.focus();
                return false;
            }

            // �˻��� ���� ���ϰ� �ɸ��� ��� �� �ּ��� �����ϼ���.
            var cnt = 0;
            for (var i=0; i<f.stx.value.length; i++) {
                if (f.stx.value.charAt(i) == ' ')
                    cnt++;
            }

            if (cnt > 1) {
                alert("���� �˻��� ���Ͽ� �˻�� ������ �Ѱ��� �Է��� �� �ֽ��ϴ�.");
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
        <strong>�Խ��� : <?=$board_count ?> �Խñ� : <?=number_format($total_count)?></strong>
        <div class="pull-right hidden-lg hidden-md hidden-sm">
            <a class="btn btn-default btn-sm" data-toggle="collapse" data-target=".search-board-collapse"><i class='fa fa-align-justify'></i></a>
        </div>
    </div>
    <div class="panel-body">

    <div class="collapse navbar-collapse search-board-collapse" style="margin-bottom:15px;">
    <? if ($board_count == 0) { ?>
        �˻��� �ڷᰡ �ϳ��� �����ϴ�.
    <? } else {
        if ($onetable)
            echo "<a class=\"btn btn-default btn-success\" href='?$search_query&gr_id=$gr_id'>��ü�Խ��� �˻�</a>";

        for ($i=0; $i<count($search_table); $i++) { ?>
            <a class="btn btn-default" href="<?=$_SERVER[PHP_SELF]?>?<?=$search_query?>&gr_id=<?=$gr_id?>&onetable=<?=$search_table[$i]?>"><?=$search_table_subject[$i]?> <sup><?=$search_table_result_count[$i]?></sup></a>
        <? }
    } ?>
    </div>

    <? 
    $k=0;
    for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) 
    { 
        echo "<a href='./board.php?bo_table={$search_table[$idx]}&{$search_query}'><strong>{$bo_subject[$idx]}</strong></a>������ �˻����";
        $comment_href = "";
        echo "<ul style='margin-bottom:15px;margin-top:5px;'>";
        for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) 
        {
            echo "<li>";
            if ($list[$idx][$i][wr_is_comment]) 
            {
                echo "<font color=999999>[�ڸ�Ʈ]</font> ";
                $comment_href = "#c_".$list[$idx][$i][wr_id];
            }
            echo "<a href='{$list[$idx][$i][href]}{$comment_href}'>";
            echo $list[$idx][$i][subject];
            echo "</a>";
            echo "&nbsp;<a class=\"btn btn-default btn-xs\" href='{$list[$idx][$i][href]}{$comment_href}' target=_blank>��â</a>&nbsp;";
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
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>�����˻�</a></li>"; } ?>
    <?
    // �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �پ��ϰ� ����� �� �ֽ��ϴ�.
    $write_pages = str_replace("����", "<i class='fa fa-angle-left'></i>", $write_pages);
    $write_pages = str_replace("����", "<i class='fa fa-angle-right'></i>", $write_pages);
    $write_pages = str_replace("ó��", "<i class='fa fa-angle-double-left'></i>", $write_pages);
    $write_pages = str_replace("�ǳ�", "<i class='fa fa-angle-double-right'></i>", $write_pages);
    ?>
    <?=$write_pages?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>���İ˻�</a></li>"; } ?>
    </ul>
</div>
