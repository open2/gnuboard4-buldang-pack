<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<div class="container">
<form name="fformmail" method="post" onsubmit="return fformmail_submit(this);" enctype="multipart/form-data" style="margin:0px;">
<input type="hidden" name="to"     value="<?=$email?>">
<input type="hidden" name="attach" value="2">
<input type="hidden" name="token"  value="<?=$token?>">

<div class="panel panel-default">
    <div class="panel-heading"><strong><?=$name?>�Կ��� <?=$g4[title]?></strong>
      </div>
      <div class="panel-body">
          <table class="table table-hover table-condensed">
                <? if ($is_member) { // ȸ���̸� ?>
                <input type='hidden' name='fnick'  value='<?=get_text($member[mb_nick])?>'>
                <input type='hidden' name='fmail'  value='<?=$member[mb_email]?>'>
                <? } else { ?>
                <tr> 
                    <td height="27" align="center"><b>�̸�</b></td>
                    <td valign="bottom"><img src="<?=$member_skin_path?>/img/l.gif" width="1" height="8"></td>
                    <td><input type=text style='width:90%;' name='fnick' required minlength=2 itemname='�̸�'></td>
                </tr>
                <tr> 
                    <td height="27" align="center"><b>E-mail</b></td>
                    <td valign="bottom"><img src="<?=$member_skin_path?>/img/l.gif" width="1" height="8"></td>
                    <td><input type=text style='width:90%;' name='fmail' required email itemname='E-mail'></td>
                </tr>
                <? } ?>

          <tr>
              <td class="col-sm-2" style="border:0px;">����</td>
              <td style="border:0px;">
                  <input class="form-control" style="width:100%;" type="text" name='subject' required itemname='����' placeholder="subject">
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;">����</td>
              <td style="border:0px;">
                  <label class="radio-inline"><input type='radio' name='type' value='0' checked> TEXT </label>
                  <label class="radio-inline"><input type='radio' name='type' value='1' > HTML </label>
                  <label class="radio-inline"><input type='radio' name='type' value='2' > TEXT+HTML</label>
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;">����</td>
              <td style="border:0px;">
                  <textarea name="content" style='width:100%;' rows='9' required itemname='����'></textarea>
              </td>
          </tr>
          <tr>
              <td class="col-sm-2" style="border:0px;">÷������</td>
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
              <a class="btn btn-default" href="javascript:window.close();">�ݱ�</a>
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
            alert("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���."); 
            return false; 
        } 
    }

    if (f.file1.value || f.file2.value) {
        // 4.00.11
        if (!confirm("÷�������� �뷮�� ū��� ���۽ð��� ���� �ɸ��ϴ�.\n\n���Ϻ����Ⱑ �Ϸ�Ǳ� ���� â�� �ݰų� ���ΰ�ħ ���� ���ʽÿ�."))
            return false;
    }

    document.getElementById('btn_submit').disabled = true;

    f.action = "./formmail_send.php";
    return true;
}
</script>
