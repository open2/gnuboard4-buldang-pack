<?
include_once("./_common.php");

$g4[title] = "회원탈퇴";
include_once ("./_head.php");

// 비회원의 접근을 제한 합니다
if (!$member[mb_id]) {
    $msg = "비회원은 접근할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.";
    alert($msg, "$g4[bbs_path]/login.php?url=".urlencode("$g4[bbs_path]/mb_leave.php"));
}
?>

<form name='fconfigform' method='post' onsubmit="return fconfigform_submit(this);">
<input type=hidden name=mb_id value='<?=$member[mb_id]?>'>
<div class="panel panel-default">
  <div class="panel-heading">회원탈퇴</div>

  <div class="panel-body">
  회원탈퇴를 신청하시면 해당 아이디로는 재가입이 불가능합니다.

      <table class="table" style="margin-top:30px;">
      <tr>
          <td class="active col-md-1">아이디</td>
          <td><?=$member[mb_id]?></td>
      </tr>
      <tr>
          <td class="active col-md-1">닉네임</td>
          <td><?=$member[mb_nick]?></td>
      </tr>
      <tr>
          <td class="active col-md-1">비밀번호</td>
          <td><input type=password name='mb_password' size='25' itemname="비밀번호" required></td>
      </tr>
      <tr>
          <td class="col-md-1"></td>
          <td>
              <script src='https://www.google.com/recaptcha/api.js'></script> 
              <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>"></div>
          </td>
      </tr>
      </table>
  </div>
      
  <div class="panel-footer">
      <span class="pull-right"> 
      <input type="submit" class="btn btn-success" value='  탈  퇴  '>
      </span>
      </BR></BR>
  </div>
</div>
</form>

<script type="text/javascript">
function fconfigform_submit(f)
{
    if (typeof(grecaptcha) != 'undefined') { 
        if(grecaptcha.getResponse() == "") { 
            alert("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요."); 
            return false; 
        } 
    }

    f.action = "./mb_leave_update.php";
    return true;
}
</script>
<?
include_once ("./_tail.php");
?>