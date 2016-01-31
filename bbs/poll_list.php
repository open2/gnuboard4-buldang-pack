<?
include_once("./_common.php");

$g4[title] = "��ǥ ���";
include_once("./_head.php");

$sql_common = " from $g4[poll_table] ";

$sql_search = " where (po_use_access = 0) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default : 
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "po_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search 
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if ($page == "") $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

$write_pages = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$listall = "<a href='$_SERVER[PHP_SELF]'>ó��</a>";

$colspan = 7;
?>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (��ǥ�� : <?=number_format($total_count)?>��)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='po_subject'>����</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='�˻���' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">�˻�</button>
    </div>
</div>
</form>

<table width=100% class="table table-condensed table-hover" style="word-wrap:break-word;">
<thead>
<tr class="success">
    <th width=50px class="hidden-xs"><?=subject_sort_link('po_id', $qstr, 1)?>��ȣ</a></th>
    <? if ($is_checkbox) { ?><th class="hidden-xs"><INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox></th><?}?>
    <th>����<span class="visible-xs pull-right" style="font-weight: normal;color:#B8B8B8;">Page <?=$page?>/<?=$total_page?></span></th>
    <th width=160px class="hidden-xs">�� ��</th>
    <th width=70px class="hidden-xs">��ǥ��</th>
    <th width=70px class="hidden-xs"><?=subject_sort_link('wr_hit', $qstr, 1)?></a>
        <a href="./poll_form.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
    </th>
</tr>
</thead>

<? for ($i=0; $row=sql_fetch_array($result); $i++) { ?>
<tr>
    <?
    $sql2 = " select sum(po_cnt1+po_cnt2+po_cnt3+po_cnt4+po_cnt5+po_cnt6+po_cnt7+po_cnt8+po_cnt9) as sum_po_cnt from $g4[poll_table] where po_id = '$row[po_id]' ";
    $row2 = sql_fetch($sql2);
    
    $s_mod = "<a href='./poll_form.php?$qstr&w=u&po_id=$row[po_id]'><img src='$g4[admin_path]/img/icon_modify.gif' align=absmiddle border=0 title='����'></a>";
    $s_del = "<a href=\"javascript:post_delete('poll_form_update.php', '$row[po_id]');\"><img src='$g4[admin_path]/img/icon_delete.gif' align=absmiddle border=0 title='����'></a>";
    if ($row[po_end_date] == '0000-00-00')
        $row[po_end_date] = "������";
    ?>
    <td class="hidden-xs"><?=$row[po_id]?>
    </td>
    <? if ($is_checkbox) { ?><td class="hidden-xs"><input type=checkbox name=chk_po_id[] value="<?=$row[po_id]?>"></td><? } ?>
    <td class="hidden-xs" align=left style='word-break:break-all;'>
        <a href="javascript:;" onclick="poll_result('<?=$row[po_id]?>','<?=$row[po_skin]?>');"><?=cut_str(get_text($row[po_subject]),70)?></a>
    </td>
    <td class="hidden-xs"><?=$row[po_date]?> ~ <?=$row[po_end_date]?></td>
    <td class="hidden-xs"><?=$row2[sum_po_cnt]?></td>
    <td class="hidden-xs">
        <? if (($member[mb_id] && $member[mb_id] == $row[mb_id]) || $is_admin) { echo $s_mod . " " . $s_del; } ?>
    </td>
    <td class="visible-xs">
    <?=$row[po_id]?>&nbsp;&nbsp;<a href="javascript:;" onclick="poll_result('<?=$row[po_id]?>','<?=$row[po_skin]?>');"><?=cut_str(get_text($row[po_subject]),70)?></a><br>
    <div class="pull-left"><?=$row[po_date]?> ~ <?=$row[po_end_date]?></div>
    <div class="pull-right"><?=$row2[sum_po_cnt]?></div>
    <td>
</tr>
<?}?>
<?
if ($i==0) 
    echo "<tr><td colspan='$colspan' height=100 align=center>�ڷᰡ �����ϴ�.</td></tr>";
?>
</table>

<!-- ������ -->
<div class="hidden-xs" style="text-align:center;">
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
<div class="center-block visible-xs">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>�����˻�</a></li>"; } ?>
    <?
    // �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �پ��ϰ� ����� �� �ֽ��ϴ�.
    $write_pages_xs = str_replace("����", "<i class='fa fa-angle-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("����", "<i class='fa fa-angle-right'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("ó��", "<i class='fa fa-angle-double-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("�ǳ�", "<i class='fa fa-angle-double-right'></i>", $write_pages_xs);
    ?>
    <?=$write_pages_xs?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>���İ˻�</a></li>"; } ?>
    </ul>
</div>

<script type="text/javascript">
if ('<?=$stx?>') {
    document.fsearch.sfl.value = '<?=$sfl?>';
    document.fsearch.sop.value = '<?=$sop?>';
}
document.fsearch.stx.focus();

function poll_result(po_id, po_skin)
{    
    win_poll("<?=$g4[bbs_path]?>/poll_result.php?po_id="+po_id+"&skin_dir="+po_skin);
}
</script>

<?
include_once ("./_tail.php");
?>