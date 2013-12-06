<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<!-- 로그인 후 외부로그인 시작 -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?=$nick?></strong>
        <? if ($is_admin == "super" || $is_auth) { ?>&nbsp;<a href="<?=$g4['admin_path']?>/"><i class="fa fa-cog"></i></a><? } ?>
        <div class="pull-right">
        Lv.<?=$member[mb_level] ?>
        <? if ($config[cf_use_point]) { ?>
        &nbsp;<a href="javascript:win_point();"><font color="#737373"><?=$point?></font></a>
        <? } ?>
        </div>
    </div>
    <div class="btn-group btn-group-justified">
        <a class="btn btn-default btn-sm" style="border-color: #ffffff;" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" ?>Memo</a>
        <a class="btn btn-default btn-sm" style="border-color: #ffffff;" href="<?=$g4['bbs_path']?>/myon.php" >MyOn</a>
        <a class="btn btn-default btn-sm" style="border-color: #ffffff;" href="javascript:win_scrap();" >Scrap</a>
    </div>
    <div class="btn-group btn-group-justified">
        <a class="btn btn-default btn-sm" href="<?=$g4['bbs_path']?>/logout.php" >Logout</a>
        <a class="btn btn-default btn-sm" href="<?=$g4['bbs_path']?>/member_confirm.php?url=register_form.php" >Modify</a>
    </div>
</div>

<script type="text/javascript">
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave() 
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?")) 
            location.href = "<?=$g4['bbs_path']?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- 로그인 후 외부로그인 끝 -->
