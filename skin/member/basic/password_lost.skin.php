<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<form name="fpasswordlost" class="form-horizontal" method="post" onsubmit="return fpasswordlost_submit(this);" autocomplete="off">
<div class="panel panel-default">
  <div class="panel-heading"><div class="col-sm-offset-2"><strong>회원 아이디/패스워드 찾기</strong></div></div>
  <div class="panel-body">
			<div class="form-group">
				<label for="mb_id" class="col-sm-2 control-label">이메일주소</label>
				<div class="col-sm-2">
					<input type="text" name="mb_email" id="mb_email" class="form-control" maxLength=80 minlength="5" itemname="이메일주소" placeholder="e-mail address">
          회원가입시 등록하신 이메일주소 입력
				</div>
			</div>
			<div class="form-group">
          <script src='https://www.google.com/recaptcha/api.js'></script> 
          <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>" style="float:right"></div> 
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" accesskey="s" class="btn btn-success" value="다음">
					<a href="javascript:window.close();" class="btn btn-default">닫기</a>
				</div>
			</div>
  </div>
</div>
</form>

<script type="text/javascript">
function fpasswordlost_submit(f)
{
    if (typeof(grecaptcha) != 'undefined') { 
        if(grecaptcha.getResponse() == "") { 
            alert("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요."); 
            return false; 
        } 
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/password_lost2.php';";
    else
        echo "f.action = './password_lost2.php';";
    ?>

    return true;
}

self.focus();
document.fpasswordlost.mb_email.focus();
</script>
