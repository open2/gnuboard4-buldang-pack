<?
include_once("./_common.php");

$g4[title] = "ȸ��Ż��";
include_once ("./_head.php");

// ��ȸ���� ������ ���� �մϴ�
if (!$member[mb_id]) {
    $msg = "��ȸ���� ������ ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.";
    alert($msg, "$g4[bbs_path]/login.php?url=".urlencode("$g4[bbs_path]/mb_leave.php"));
}
?>

<form name='fconfigform' method='post' onsubmit="return fconfigform_submit(this);">
<input type=hidden name=mb_id value='<?=$member[mb_id]?>'>
<div class="panel panel-default">
  <div class="panel-heading">ȸ��Ż��</div>

  <div class="panel-body">
  ȸ��Ż�� ��û�Ͻø� �ش� ���̵�δ� �簡���� �Ұ����մϴ�.

      <table class="table" style="margin-top:30px;">
      <tr>
          <td class="active col-md-1">���̵�</td>
          <td><?=$member[mb_id]?></td>
      </tr>
      <tr>
          <td class="active col-md-1">�г���</td>
          <td><?=$member[mb_nick]?></td>
      </tr>
      <tr>
          <td class="active col-md-1">��й�ȣ</td>
          <td><input type=password name='mb_password' size='25' itemname="��й�ȣ" required></td>
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
      <input type="submit" class="btn btn-success" value='  Ż  ��  '>
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
            alert("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���."); 
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