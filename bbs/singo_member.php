<?
include_once("./_common.php");

$g4[title] = "ȸ���Ű�";
include_once("./_head.php");

if (!$is_member)
    alert("ȸ���� ��밡���� ��� �Դϴ�.");
?>

<script type="text/javascript"> 
<!-- // ȸ��ID ã��  
function popup_id(frm_name, ss_id, top, left) 
{ 
    url = './write_id.php?frm_name='+frm_name+'&ss_id='+ss_id; 
    opt = 'scrollbars=yes,width=320,height=470,top='+top+',left='+left; 
    window.open(url, "write_id", opt); 
} 
//--> 
</script> 

<?
    include_once("$g4[path]/lib/cheditor4.lib.php");
    echo "<script type='text/javascript' src='$g4[cheditor4_path]/cheditor.js?v=$g4[cheditor_ver]'></script>";
    echo cheditor1('sg_reason', '100%', '250');
?>

<form name=fsingo method=post onsubmit="return fsingo_submit(this);" enctype="multipart/form-data" autocomplete="off">
<input type=hidden value="@user" name="bo_table">

<table width=98% cellpadding=0 cellspacing=0>
<colgroup width=90>
<colgroup >
    <tr>
        <td colspan=2 style='padding-left:20px; height:30px;'>
        ȸ���Ű�
        </td>
    </tr>
    <tr class='ht'><td colspan=2 height=10></td></tr>
    <tr class='ht' height=30px>
        <td style='padding-left:20px; height:30px;'>
        �Ű��� ȸ��
        </td>
        <td>
        <input type='text' name='singo_mb_id' style="width:200px;" class=input required maxlength="20" itemname='�̸�' readonly>
        <a href="javascript:popup_id('fsingo','singo_mb_id',200,500);">ȸ���˻�</a>
        </td>
    </tr>
    <tr class='ht'>
        <td style='padding-left:20px; height:30px;'>
        �Ű����
        </td>
        <td>
            <?=cheditor2('sg_reason', '�Ű��� ������ �ڼ��� �����ּ���.');?>
        </td>
    </tr>
    <tr class='ht'><td colspan=2 height=10></td></tr>
    <tr class='ht'>
        <td>
        </td>
        <td >
        * ȸ���Ű� ����� ����Ʈ Ȱ���� �־ �����Ǵ� Ȱ���� �ϴ� ȸ���� �Ű��ϱ� ���� �Դϴ�. <br>
        * ����� �ٰŰ� ���� Ÿ���� �Ű��ϴ� ��� �Ű��ڿ��� �������� ���ư� ���� �ֽ��ϴ�.<br>
        * �Ű�� ȸ���� ����� ����� �Ҹ��� ������ ��ģ �� ȸ���Ծ࿡ ���� ��ġ�� �� �Դϴ�.<br>
        * ���� �ൿ�� Ÿ������ �Ͽ��� �����Ǵ� �ൿ�� �������� �ʾҴ��� ������ ���ñ� �ٶ��ϴ�.
        </td>
    </tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  Ȯ  ��  '>&nbsp;
    <input type=button class=btn1 value='  ��  ��  ' onclick="document.location.href='./singo_member.php?<?=$qstr?>';">

</form>

<script language='Javascript'>
    function fsingo_submit(f)
    {
        <?
        echo cheditor3('sg_reason');
        echo "if (!document.getElementById('sg_reason').value) { alert('������ �Է��Ͻʽÿ�.'); return; } ";
        ?>
            
        f.action = './singo_member_update.php';
        return true;
    }
</script>

<?
include_once("./_tail.php");
?>
