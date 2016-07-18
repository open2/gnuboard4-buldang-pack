<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<div class="panel panel-default">

<div class="panel-heading"><strong>게시판 바로가기</strong>
</div>
</div>

<table width="100%" class="table table-hover table-condensed">
<tr class="success">
    <td align=center>번호</td>
    <td>게시판 바로가기</td>
    <td align=center>삭제</td>
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
<? if ($i == 0) echo "<tr><td colspan=2 align=center height=100>자료가 없습니다.</td></tr>"; ?>
</table>



<script type="text/javascript">
function del(bo_table, bo_subject)
{
    if (confirm("'" + bo_subject + "' 게시판 바로가기를 정말 삭제하시겠습니까?"))
        location.href = "<?=$g4[bbs_path]?>/my_menu_del.php?bo_table=" + bo_table;
}
</script>
