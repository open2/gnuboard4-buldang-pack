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
				<label for="mb_password" class="col-sm-2 control-label"><img id="zsfImg" style="cursor:pointer" ></label>
				<div class="col-sm-2">
  				<input class="form-control" type="input" size=10 name=wr_key id=wr_key itemname="자동등록방지" required placeholder="captcha">
				</div>
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

<script type="text/javascript" src=<?="$g4[path]/zmSpamFree/zmspamfree.js"?>></script>

<script type="text/javascript">
function fpasswordlost_submit(f)
{
    if (typeof(f.wr_key) != 'undefined') {
        if (!checkFrm()) {
            alert ("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요.");
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
