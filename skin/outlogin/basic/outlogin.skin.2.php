<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

if ($member['mb_memo_unread'] > 0)
    $memo_status = "btn-info";
if ($g4['whatson_unread'] > 0)
    $myon_status = "btn-info";
?>

<!-- �α��� �� �ܺηα��� ���� -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><a href="#" onClick="javascript:win_profile('<?=$member[mb_id]?>');"><?=$nick?></a></strong>
        <span class="pull-right" style="margin-right:-10px;margin-left:5px;border:1px;"><kbd style="background-color:Gainsboro;color:black"><?=$member[mb_level]?></kbd></span>
        <? if ($config[cf_use_point]) { ?>
        <a href="javascript:win_point();"><span class="pull-right"><font color="#737373"><small><?=$point?></small></font></span></a>
        <? } ?>
    </div>
    <div class="btn-group btn-group-justified">
        <a class="btn btn-default btn-sm <?=$memo_status?>" style="border-color: #ffffff;" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" ?>Memo</a>
        <a class="btn btn-default btn-sm <?=$myon_status?>" style="border-color: #ffffff;" href="<?=$g4['bbs_path']?>/myon.php?head=1" >MyOn</a>
        <a class="btn btn-default btn-sm" style="border-color: #ffffff;" href="javascript:win_scrap();" >Scrap</a>
    </div>
    <div class="btn-group btn-group-justified">
        <a class="btn btn-default btn-sm" href="<?=$g4['bbs_path']?>/logout.php" >Logout</a>
        <a class="btn btn-default btn-sm" href="<?=$g4['bbs_path']?>/member_confirm.php?url=register_form.php" >Modify</a>
        <? if ($is_admin == "super" || $is_auth) { ?><a class="btn btn-primary btn-sm" href="<?=$g4['admin_path']?>/index.php"><i class="fa fa-cog"></i></a><? } ?>
    </div>
</div>

<script type="text/javascript">
// Ż���� ��� �Ʒ� �ڵ带 �����Ͻø� �˴ϴ�.
function member_leave() 
{
    if (confirm("���� ȸ������ Ż�� �Ͻðڽ��ϱ�?")) 
            location.href = "<?=$g4['bbs_path']?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- �α��� �� �ܺηα��� �� -->
