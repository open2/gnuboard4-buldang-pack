<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>

<div class="panel panel-default">

<div class="panel-heading"><strong>�Խ��� �ٷΰ���</strong>
</div>
</div>

<table width="100%" class="table table-hover table-condensed">
<tr class="success">
    <td align=center>��ȣ</td>
    <td>�Խ��� �ٷΰ���</td>
    <td align=center>����</td>
</tr>

<? for ($i=0; $i<count($list); $i++) { ?>
<tr>
    <td align=center><?=count($list)-$i?></td>
    <td>
        <a href="javascript:;" onclick="location.href='<?=$g4[bbs_path]?>/board.php?bo_table=<?=$list[$i][bo_table]?>';"><?=$list[$i][bo_subject]?></a>
    </td>
    <td align=center>
        <a href="javascript: del('<?=$list[$i][bo_table]?>', '<?=$list[$i][bo_subject]?>');"><i class="fa fa-trash-o"></i></a>
    </td>
</tr>
<? } ?>
<? if ($i == 0) echo "<tr><td colspan=2 align=center height=100>�ڷᰡ �����ϴ�.</td></tr>"; ?>
</table>



<script type="text/javascript">
function del(bo_table, bo_subject)
{
    if (confirm("'" + bo_subject + "' �Խ��� �ٷΰ��⸦ ���� �����Ͻðڽ��ϱ�?"))
        location.href = "<?=$g4[bbs_path]?>/my_menu_del.php?bo_table=" + bo_table;
}
</script>