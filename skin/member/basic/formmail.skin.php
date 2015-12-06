<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="container">
<form name="fformmail" method="post" onsubmit="return fformmail_submit(this);" enctype="multipart/form-data" style="margin:0px;">
<input type="hidden" name="to"     value="<?=$email?>">
<input type="hidden" name="attach" value="2">
<input type="hidden" name="token"  value="<?=$token?>">

<div class="panel panel-default">
    <div class="panel-heading"><strong><?=$name?>님에게 <?=$g4[title]?></strong>
      </div>
      <div class="panel-body">
          <table class="table table-hover table-condensed">
                <? if ($is_member) { // 회원이면 ?>
                <input type='hidden' name='fnick'  value='<?=get_text($member[mb_nick])?>'>
                <input type='hidden' name='fmail'  value='<?=$member[mb_email]?>'>
                <? } else { ?>
                <tr> 
                    <td height="27" align="center"><b>이름</b></td>
                    <td valign="bottom"><img src="<?=$member_skin_path?>/img/l.gif" width="1" height="8"></td>
                    <td><input type=text style='width:90%;' name='fnick' required minlength=2 itemname='이름'></td>
                </tr>
                <tr> 
                    <td height="27" align="center"><b>E-mail</b></td>
                    <td valign="bottom"><img src="<?=$member_skin_path?>/img/l.gif" width="1" height="8"></td>
                    <td><input type=text style='width:90%;' name='fmail' required email itemname='E-mail'></td>
                </tr>
                <? } ?>

          <tr>
              <td class="col-sm-2" style="border:0px;">제목</td>
              <td style="border:0px;">
                  <input class="form-control" style="width:100%;" type="text" name='subject' required itemname='제목' placeholder="subject">
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;">선택</td>
              <td style="border:0px;">
                  <label class="radio-inline"><input type='radio' name='type' value='0' checked> TEXT </label>
                  <label class="radio-inline"><input type='radio' name='type' value='1' > HTML </label>
                  <label class="radio-inline"><input type='radio' name='type' value='2' > TEXT+HTML</label>
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;">내용</td>
              <td style="border:0px;">
                  <textarea name="content" style='width:100%;' rows='9' required itemname='내용'></textarea>
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;">첨부파일</td>
              <td style="border:0px;">
                  <input class="form-control" type="file" id="file1" name="file1" style="width:100%">
                  <input class="form-control" type="file" id="file2" name="file2" style="width:100%">
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;"><img id="zsfImg"></td>
              <td style="border:0px;">
                  <script src='https://www.google.com/recaptcha/api.js'></script> 
                  <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>" style="float:right"></div> 
              </td>
          </tr>
          </table>

          <div class="pull-right">
              <button type="submit" class="btn btn-success" id="btn_submit">Send</button>&nbsp;&nbsp;
              <a class="btn btn-default" href="javascript:window.close();">닫기</a>
          </div>
      </div>
</div>

</form>
</div>


<script type="text/javascript">
with (document.fformmail) {
    if (typeof fname != "undefined")
        fname.focus();
    else if (typeof subject != "undefined")
        subject.focus();
}

with (document.fformmail) {
    if (typeof fname != "undefined")
        fname.focus();
    else if (typeof subject != "undefined")
        subject.focus();
}

function fformmail_submit(f)
{
    if (typeof(grecaptcha) != 'undefined') { 
        if(grecaptcha.getResponse() == "") { 
            alert("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요."); 
            return false; 
        } 
    }

    if (f.file1.value || f.file2.value) {
        // 4.00.11
        if (!confirm("첨부파일의 용량이 큰경우 전송시간이 오래 걸립니다.\n\n메일보내기가 완료되기 전에 창을 닫거나 새로고침 하지 마십시오."))
            return false;
    }

    document.getElementById('btn_submit').disabled = true;

    f.action = "./formmail_send.php";
    return true;
}
</script>
