<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<form class="form-inline" role="form" name="fboardpassword" method=post onsubmit="return fboardpassword_submit(this);" autocomplete="off">
<input type=hidden name=w           value="<?=$w?>">
<input type=hidden name=bo_table    value="<?=$bo_table?>">
<input type=hidden name=wr_id       value="<?=$wr_id?>">
<input type=hidden name=comment_id  value="<?=$comment_id?>">
<input type=hidden name=sfl         value="<?=$sfl?>">
<input type=hidden name=stx         value="<?=$stx?>">
<input type=hidden name=page        value="<?=$page?>">
<input type=hidden name=pc_id       value="<?=$pc_id?>">
<input type=hidden name=po_id       value="<?=$po_id?>">

<div class="panel panel-default">

<div class="panel-heading">
    <strong>이 게시물의 패스워드를 입력하십시오.</strong>
</div>

<div class="panel-body">
        <label class="control-label">Password</label>
        <INPUT type="password" class="form-control" maxLength=20 size=15 name="wr_password" id="password_wr_password" itemname="패스워드" required placeholder="password">
        <button type="submit" class="btn btn-default">확인</button>
</div>

</div>

</form>

<script type="text/javascript">
document.fboardpassword.wr_password.focus();

function fboardpassword_submit(f)
{
    f.action = "<?=$action?>";
    return true;
}
</script>
