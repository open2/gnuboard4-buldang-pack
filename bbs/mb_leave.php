<?
include_once("./_common.php");

$g4[title] = "ȸ��Ż��";
include_once ("./_head.php");

// ��ȸ���� ������ ���� �մϴ�
if (!$member[mb_id]) {
    $msg = "��ȸ���� ������ ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.";
    alert($msg, "./login.php?url=".urlencode("./mb_leave.php"));
}
?>
        
<style type="text/css">
<!--
.col1 { color:#616161; }
.col2 { color:#868686; }
.pad1 { padding:5px 10px 5px 10px; }
.pad2 { padding:5px 0px 5px 0px; }
.bold { font-weight:bold; }
.center { text-align:center; }
.right { text-align:right; }
.ht { height:30px; }
-->
</style>

<div style="width:620px">
<p class='col1 pad1 bold'>ȸ��Ż��</p>
</div>

<div style="width:620px" class='pad1'>
<p>1. �ش� ���̵�� �簡�� �Ұ���</P>
<p>ȸ��Ż�� ��û�Ͻø� �ش� ���̵�� ��� Ż��ó���Ǹ� ���� �ش� ���̵�� ���������� ����� �����ǹǷ� �ش� ���̵�δ� �簡���� �Ұ����մϴ�. </P>

<p>2. ȸ������ �� ȸ���� ������ ���� ���� </P>
<p>ȸ��Ż��� �ش� ���̵�δ� ���̻� �α��� ���� �� �� ������, ����� ȸ�������� <?=$config[cf_leave_day]?>�� �Ŀ� ���� �˴ϴ�. </P>

<p>3. �ҷ��̿� �� �̿����ѿ� ���� ��� 1�� ���� ���� </P>
<p>����������޹�ħ�� ���� �ҷ��̿� �� �̿����ѿ� ���� ����� 1�� ���� �������� �ʰ� �����˴ϴ�.</P>
</div>

<BR>

<form name='fconfigform' method='post' onsubmit="return fconfigform_submit(this);">
<input type=hidden name=token value='<?=$token?>'>

<table width=620 cellpadding=0 cellspacing=0 border=0>
<colgroup width=120 class='col1 pad1 bold right'>
<colgroup class='col2 pad2'>
<tr class='ht'>
<td>�� �� ��</td>
<td><input type=text class=ed name='mb_id' size='25' readonly value='<?=$member[mb_id]?>'></td>
</tr>
<tr class='ht'>
<td>��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��</td>
<td><input type=text class=ed name='mb_name' size='25' itemname="�̸�" required></td>
</tr>
<tr class='ht'>
<td>��й�ȣ</td>
<td><input type=password class=ed name='mb_password' size='25' itemname="��й�ȣ" required></td>
</tr>
<tr class='ht'>
<td>Ż�����</td>
<td><textarea class=ed name='leave_reason' rows='3' style='width:99%;'></textarea></td>
</tr>
<tr class='ht'>
    <td></td>
    <td colspan=3>
        <script src='https://www.google.com/recaptcha/api.js'></script> 
        <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>" style="float:right"></div> 
    </td>
</tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  Ȯ  ��  '>

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
