<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>

<script type="text/javascript" src="<?=$g4[path]?>/js/capslock.js"></script>

<form name=fmemberconfirm class="form-horizontal" method=post onsubmit="return fmemberconfirm_submit(this);">
<input type=hidden name=mb_id value='<?=$member[mb_id]?>'>
<input type=hidden name=w     value='u'>

<div class="panel panel-default">
    <div class="panel-heading"><strong>�н����� Ȯ��</strong></div>
    <div class="panel-body">
  			<div class="form-group">
	    			<label for="mb_id" class="control-label col-xs-3">���̵�</label>
			    	<div class="col-xs-6">
  					<?=$member[mb_id]?>
    				</div>
		  	</div>
  			<div class="form-group">
	    			<label for="mb_id" class="control-label col-xs-3">�н�����</label>
			    	<div class="col-xs-6">
            <INPUT type=password class="form-control" maxLength=20 size=15 name="mb_password" id="confirm_mb_password" itemname="�н�����" placeholder="password" required>
    				</div>
		  	</div>
        <div class="col-xs-3"></div>
        <input type="submit" class="btn btn-success" value="Ȯ��" id="btn_submit">
  	</div>
    <div class="panel-footer">
        �ܺηκ��� ȸ������ ������ �����ϰ� ��ȣ�ϱ� ���� �н����带 Ȯ���ϼž� �մϴ�.
    </div>
</div>

</form>

<script type='text/javascript'>
document.onload = document.fmemberconfirm.mb_password.focus();

function fmemberconfirm_submit(f)
{
    document.getElementById("btn_submit").disabled = true;

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/$url';";
    else
        echo "f.action = '$url';";
    ?>

    return true;
}
</script>
