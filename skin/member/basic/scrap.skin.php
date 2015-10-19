<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Խ��� ��Ϻ��� �����ϱ�
$sql = " select distinct a.bo_table, b.bo_subject from $g4[scrap_table] a left join $g4[board_table] b on a.bo_table=b.bo_table where a.mb_id = '$member[mb_id]' ";
$result = sql_query($sql);
$str = "<select class='form-control' name='bo_table' onchange=\"location='$g4[bbs_path]/scrap.php?head_on=$head_on&mnb=$mnb&snb=$snb&sfl=bo_table&stx='+this.value;\">";
$str .= "<option value='all'>��ü�Խ���</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $str .= "<option value='$row[bo_table]'";
        if ($sfl=='bo_table' and $row[bo_table] == $stx) $str .= " selected";
        $str .= ">$row[bo_subject]</option>";
    }
    $str .= "</select>";

// ��ũ�� �޸𺰷� �����ϱ�
$sql = " select distinct ms_memo from $g4[scrap_table] where mb_id = '$member[mb_id]' and ms_memo != '' ";
$result = sql_query($sql);
$memo_str0 = "<select class='form-control' name='ms_memo' onchange=\"location='$g4[bbs_path]/scrap.php?head_on=$head_on&mnb=$mnb&snb=$snb&sfl=ms_memo&stx='+this.value;\">";
$memo_str = "<option value='all'>��ü�޸�</option>";
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

<a class="btn btn-default" href="<?=$g4[bbs_path]?>/scrap.php?head_on=<?=$head_on?>&mnb=<?=$mnb?>&snb=<?=$snb?>">ó��</a>
<div class="pull-right hidden-lg hidden-md hidden-sm">
    <a class="btn btn-default" data-toggle="collapse" data-target=".scrap-search-collapse"><i class='fa fa-search'></i></a>
</div>

<span class="">
<?=$str?><?=$memo_str_list?>
</span>

<div class="pull-right collapse navbar-collapse scrap-search-collapse">
    <select class='form-control' name=sfl class=cssfl>
        <option value='wr_subject_memo' <? if ($sfl=='wr_subject_memo') echo "selected" ?> >����+�޸�</option>
        <option value='wr_subject' <? if ($sfl=='wr_subject') echo "selected" ?> >����</option>
        <option value='ms_memo' <? if ($sfl=='ms_memo') echo "selected" ?> >�޸�</option>
        <option value='wr_mb_id' <? if ($sfl=='wr_mb_id') echo "selected" ?> >�۾���(���̵�+����)</option>
        <option value='bo_table' <? if ($sfl=='bo_table') echo "selected" ?> >�Խ���</option>
    </select>
    <div class="form-group">
        <input class="form-control" type=text name=stx required itemname='�˻���' value='<? echo $stx ?>'>
    </div>
    <input class="btn btn-default" type=submit value='�˻�'>
</div>
</form>

<table width="100%" class="table table-hover table-condensed">
<tr class="success"> 
    <td class="col-sm-1 hidden-xs" align=center>��ȣ</td>
    <td class="col-sm-2 hidden-xs">�Խ���</td>
    <td>����</td>
    <td class="col-sm-2 hidden-xs">�۾���</td>
    <td class="col-sm-2 hidden-xs">�޸�</td>
    <td class="col-sm-1 hidden-xs">��¥</td>
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
        <? // ��б��� ��ũ���� ��� ��б� �������� �տ� ǥ��
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
        <!-- xs... ����� ���¿��� ������ ��. �޸������� ���� �ؾ� �ϱ⿡ �ѹ� �� copy �ϸ鼭 ���� -->
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

                <!-- id�� ������ �浹�ϱ� ������ _1�� �ڿ� �ٿ��� ���� �մϴ� -->
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

    <? if ($i == 0) echo "<tr><td colspan=5 align=center height=100>�ڷᰡ �����ϴ�.</td></tr>"; ?>
</table>

<? $write_pages = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");?>
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

<? if (!$head_on) { ?>
<div class="container"  style="display: inline-block;text-align: center;">
    <a class="btn btn-default" href="javascript:window.close();" >�ݱ�</a>
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

// ������ �޸� ������Ʈ
function memo_update(ms_id) {
    var f = document.flist;
    var el_id;

    el_id = 'memo_edit_' + ms_id;
    document.getElementById('memo_edit').value = document.getElementById(el_id).value;
    f.action = "<?=$member_skin_path?>/scrap_memo_update.php?ms_id=" + ms_id + "&head_on=<?=$head_on?>&mnb=<?=$mnb?>&snb=<?=$snb?>";
    f.submit();
}
</script>
