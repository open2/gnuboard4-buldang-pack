<div class="panel panel-default">
    <div class="panel-heading">
  	    이메일 인증
    </div>
    <div class="panel-body">
  	인증받을 이메일주소를 입력하십시오.
  	<? if ($g4['email_certify_point']) { ?><br>이메일을 인증하면 <?=$g4['email_certify_point']?>포인트를 적립해 드립니다 (1회만 적립 됩니다).<? }?>
    <? 
    // 이메일인증을 사용하는 경우에는 추가 메시지를 출력해 줍니다.
    if ($config['cf_use_email_certify']) { ?>
    발송된 인증메일을 반드시 확인해야 로그인이 가능합니다.
    <? } ?>

    <form name="fwrite" method="post" onsubmit="return fwrite_submit(this);" enctype="multipart/form-data" role="form" class="form-inline">
    <input type=hidden name=token value='<?=$token?>'>
    <input type=hidden name=mb_id id='mb_id' value='<?=$member[mb_id]?>'>
    <input type=hidden name=mb_email_enabled id="mb_email_enabled" value="" >
    
    <br>
    <table class="table table-condensed table-hover">
    <tr>
        <td class="col-md-2">등록된 이메일</td>
        <td align=left>
        <?=$member['mb_email']?>
        <? if (!preg_match("/[1-9]/", $member[mb_email_certify])) echo "(인증되지 않음)"; else echo "(인증일자: " . cut_str($member[mb_email_certify],10,"") . ")"; ?>
        </td>
    </tr>
        <tr>
        <td>변경할 이메일</td>
        <td align=left>
        <input class="form-control" type=text id='mb_email' name='mb_email' required style="ime-mode:disabled" size=38 maxlength=100 value='<?=$member[mb_email]?>' onkeyup='reg_mb_email_check()'>
        &nbsp;<span id='msg_mb_email'></span>
        </td>
    </tr>
    </table>

    <div style="text-align:center">
    <button type="submit" class="btn btn-success" id="btn_submit" title="메일발송에 몇초가 걸리므로 기다려 주세요.">메일보내기</button>

    <!-- 이메일 인증을 하게 했고, 회원가입했으나 미인증된 경우 탈퇴 기능을 제공 합니다 -->
    <? if ($config[cf_use_email_certify] && !preg_match("/[1-9]/", $member[mb_email_certify])) { ?>
            &nbsp;&nbsp;&nbsp;<a href="javascript:member_leave();" class="btn btn-default pull-right">회원탈퇴</a>
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

    // E-mail 검사
    reg_mb_email_check();

    if (f.mb_email_enabled.value != '000') {
        alert('E-mail을 입력하지 않았거나 입력에 오류가 있습니다.');
        f.mb_email.focus();
        return false;
    }

    f.action = './email_re_certify_update.php';

    return true;
}

function member_leave() 
{ 
   if (confirm("정말 회원에서 탈퇴 하시겠습니까?")) 
            location.href = "<?=$g4[bbs_path]?>/mb_leave.php"; 
}
</script>
