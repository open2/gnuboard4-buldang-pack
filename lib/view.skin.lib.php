<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// ������ ��¥ ������ ���� �� �� ���� �ϱ�
check_bo_from_date();

// ����/������ bo_sex �ʵ忡 M/F�� ��ϵ� ��쿡�� �Խ����� ������ ���
check_bo_sex();
?>

<!-- �ڵ���ó -->
<? if ($board[bo_source]) { ?>
<? $copy_url = set_http("{$g4[url]}/{$g4[bbs]}/board.php?bo_table={$bo_table}&wr_id={$wr_id}"); ?>
<script type="text/javascript" src="<?=$g4['path']?>/js/autosourcing.open.compact.js"></script>
<style type="text/css">
DIV.autosourcing-stub { display:none }
DIV.autosourcing-stub-extra { position:absolute; opacity:0 }
</style>
<script type="text/javascript">
AutoSourcing.setTemplate("<p style='margin:11px 0 7px 0;padding:0'> <a href='{link}' target='_blank'> [��ó] {title} - {link}</a> </p>");
AutoSourcing.setString(<?=$wr_id?> ,"<?=$config[cf_title];//$view[wr_subject]?>", "<?=$view[wr_name]?>", "<?=$copy_url?>");
AutoSourcing.init( 'view_%id%' , true);
</script>
<? } ?>

<script type="text/javascript">
function file_download(link, file) {
    <? if ($board[bo_download_point] < 0) { ?>if (confirm("'"+file+"' ������ �ٿ�ε� �Ͻø� ����Ʈ�� ����(<?=number_format($board[bo_download_point])?>��)�˴ϴ�.\n\n����Ʈ�� �Խù��� �ѹ��� �����Ǹ� ������ �ٽ� �ٿ�ε� �ϼŵ� �ߺ��Ͽ� �������� �ʽ��ϴ�.\n\n�׷��� �ٿ�ε� �Ͻðڽ��ϱ�?"))<?}?>
    document.location.href=link;
}
</script>

<!-- n���� �ڸ�Ʈ ���� ���ϰ� ����, ������� ��¥����+������ �ƴϰ�+�۾��̵� �ƴϰ�, �׷��� ���� ������ �����ڸ�ŭ ���� ������. ���� ����? ���� -->
<?
if ($board[bo_comment_nowrite] && !$is_admin && $member[mb_id] != $write[mb_id])
    if (days_diff($write[wr_datetime]) > $board[bo_comment_nowrite])
        $board[bo_comment_level] = 10;
?>
