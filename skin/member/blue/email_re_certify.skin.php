<div class="panel panel-default">
    <div class="panel-heading">
  	    �̸��� ����
    </div>
    <div class="panel-body">
  	�������� �̸����ּҸ� �Է��Ͻʽÿ�.
  	<? if ($g4['email_certify_point']) { ?><br>�̸����� �����ϸ� <?=$g4['email_certify_point']?>����Ʈ�� ������ �帳�ϴ� (1ȸ�� ���� �˴ϴ�).<? }?>
    <? 
    // �̸��������� ����ϴ� ��쿡�� �߰� �޽����� ����� �ݴϴ�.
    if ($config['cf_use_email_certify']) { ?>
    �߼۵� ���������� �ݵ�� Ȯ���ؾ� �α����� �����մϴ�.
    <? } ?>

    <form name="fwrite" method="post" onsubmit="return fwrite_submit(this);" enctype="multipart/form-data" role="form" class="form-inline">
    <input type=hidden name=token value='<?=$token?>'>
    <input type=hidden name=mb_id id='mb_id' value='<?=$member[mb_id]?>'>
    <input type=hidden name=mb_email_enabled id="mb_email_enabled" value="" >
    
    <br>
    <table class="table table-condensed table-hover">
    <tr>
        <td class="col-md-2">��ϵ� �̸���</td>
        <td align=left>
        <?=$member['mb_email']?>
        <? if (!preg_match("/[1-9]/", $member[mb_email_certify])) echo "(�������� ����)"; else echo "(��������: " . cut_str($member[mb_email_certify],10,"") . ")"; ?>
        </td>
    </tr>
        <tr>
        <td>������ �̸���</td>
        <td align=left>
        <input class="form-control" type=text id='mb_email' name='mb_email' required style="ime-mode:disabled" size=38 maxlength=100 value='<?=$member[mb_email]?>' onkeyup='reg_mb_email_check()'>
        &nbsp;<span id='msg_mb_email'></span>
        </td>
    </tr>
    </table>

    <div style="text-align:center">
    <button type="submit" class="btn btn-success" id="btn_submit" title="���Ϲ߼ۿ� ���ʰ� �ɸ��Ƿ� ��ٷ� �ּ���.">���Ϻ�����</button>

    <!-- �̸��� ������ �ϰ� �߰�, ȸ������������ �������� ��� Ż�� ����� ���� �մϴ� -->
    <? if ($config[cf_use_email_certify] && !preg_match("/[1-9]/", $member[mb_email_certify])) { ?>
            &nbsp;&nbsp;&nbsp;<a href="javascript:member_leave();" class="btn btn-default pull-right">ȸ��Ż��</a>
    <? } ?>
    </div>

    </form>

	  </div>
</div>

<script type="text/javascript">
var member_skin_path = "<?=$member_skin_path?>";
</script>
<script type="text/javascript" src="<?=$member_skin_path?>/jquery.ajax_register_form.js"></script>
<script type="text/javascript">
function fwrite_submit(f) {

    // E-mail �˻�
    reg_mb_email_check();

    if (f.mb_email_enabled.value != '000') {
        alert('E-mail�� �Է����� �ʾҰų� �Է¿� ������ �ֽ��ϴ�.');
        f.mb_email.focus();
        return false;
    }

    f.action = './email_re_certify_update.php';

    return true;
}

function member_leave() 
{ 
   if (confirm("���� ȸ������ Ż�� �Ͻðڽ��ϱ�?")) 
            location.href = "<?=$g4[bbs_path]?>/mb_leave.php"; 
}
</script>
