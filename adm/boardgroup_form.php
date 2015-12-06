<?
$sub_menu = "300200";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

if ($is_admin != "super" && $w == "") alert("�ְ�����ڸ� ���� �����մϴ�.");

$html_title = "�Խ��Ǳ׷�";
if ($w == "") 
{
    $gr_id_attr = "required";
    $gr[gr_use_access] = 0;
    $gr[gr_use_search] = '1';
    $html_title .= " ����";
} 
else if ($w == "u") 
{
    $gr_id_attr = "readonly style='background-color:#dddddd'";
    $gr = sql_fetch(" select * from $g4[group_table] where gr_id = '$gr_id' ");
    $html_title .= " ����";
} 
else
    alert("����� �� ���� �Ѿ���� �ʾҽ��ϴ�.");

$g4[title] = $html_title;
include_once("./admin.head.php");
?>

<form name=fboardgroup method=post onsubmit="return fboardgroup_check(this);" autocomplete="off" role="form" class="form-inline">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100%  class="table table-condensed table-hover" style="word-wrap:break-word;">
<colgroup width=20%>
<colgroup width=30%>
<colgroup width=20%>
<colgroup width=30%>
<tr class='success'>
    <td colspan=4 align=left><?=$html_title?></td>
</tr>
<tr>
    <td>�׷� ID</td>
    <td colspan=3><input type='text' class=ed name=gr_id size=21 maxlength=20 <?=$gr_id_attr?> alphanumericunderline itemname='�׷� ���̵�' value='<?=$group[gr_id]?>'> ������, ����, _ �� ���� (�������)</td>
</tr>
<tr>
    <td>�׷� ����</td>
    <td colspan=3>
        <input type='text' class=ed name=gr_subject size=40 required itemname='�׷� ����' value='<?=get_text($group[gr_subject])?>'>
        <? 
        if ($w == 'u')
            echo "<input type=button class='btn1' value='�Խ��ǻ���' onclick=\"location.href='./board_form.php?gr_id=$gr_id';\">";
        ?>
    </td>
</tr>
<tr>
    <td>�׷� ������</td>
    <td colspan=3>
        <?
        if ($is_admin == "super")
            //echo get_member_id_select("gr_admin", 9, $row[gr_admin]);
            echo "<input type='text' class=ed name='gr_admin' value='$gr[gr_admin]' maxlength=20>";
        else
            echo "<input type=hidden name='gr_admin' value='$gr[gr_admin]' size=40>$gr[gr_admin]";
        ?></td>
</tr>
<tr>
    <td>����ȸ�����</td>
    <td colspan=3>
        <input type=checkbox name=gr_use_access value='1' <?=$gr[gr_use_access]?'checked':'';?>>��� 
        <?=help("��뿡 üũ�Ͻø� �� �׷쿡 ���� �Խ����� ���ٰ����� ȸ���� ������ �����մϴ�.")?>
    </td>
</tr>
<tr>
    <td>����ȸ����</td>
    <td colspan=3>
        <?
        // ����ȸ����
        $sql1 = " select count(*) as cnt from $g4[group_member_table] where gr_id = '$gr_id' ";
        $row1 = sql_fetch($sql1);
        echo "<a href='./boardgroupmember_list.php?gr_id=$gr_id'>$row1[cnt]</a>";
        ?>
    </td>
</tr>

<tr>
    <td>��ü �˻� ���</td>
    <td colspan=3>
        <input type=checkbox name=gr_use_search value='1' <?=$gr[gr_use_search]?'checked':'';?>>���
    </td>
</tr>

<tr>
    <td>��ü �˻� ����</td>
    <td colspan=3>
        <input type=text class=ed name=gr_order_search size=5 value='<?=$gr[gr_order_search]?>'> ���ڰ� ���� �׷� ���� �˻�
    </td>
</tr>

<? for ($i=1; $i<=10; $i=$i+2) { $k=$i+1; ?>
<tr>
    <td><input type=text class=ed name='gr_<?=$i?>_subj' value='<?=get_text($group["gr_{$i}_subj"])?>' title='�����ʵ� <?=$i?> ����' style='text-align:right;font-weight:bold;' size=15></td>
    <td><input type='text' class=ed style='width:99%;' name=gr_<?=$i?> value='<?=$gr["gr_$i"]?>' title='�����ʵ� <?=$i?> ������'></td>
    <td><input type=text class=ed name='gr_<?=$k?>_subj' value='<?=get_text($group["gr_{$k}_subj"])?>' title='�����ʵ� <?=$k?> ����' style='text-align:right;font-weight:bold;' size=15></td>
    <td><input type='text' class=ed style='width:99%;' name=gr_<?=$k?> value='<?=$gr["gr_$k"]?>' title='�����ʵ� <?=$k?> ������'></td>
</tr>
<? } ?>

<tr>
    <td></td>
    <td colspan=3>
        <script src='https://www.google.com/recaptcha/api.js'></script> 
        <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>"></div> 
    </td>
</tr>

</table>

<p align=center>
    <input type=submit class="btn btn-default" accesskey='s' value='  Ȯ  ��  '>&nbsp;
    <input type=button class="btn btn-default" value='  ��  ��  ' onclick="document.location.href='./boardgroup_list.php?<?=$qstr?>';">
</form>

<script type="text/javascript">
if (document.fboardgroup.w.value == '')
    document.fboardgroup.gr_id.focus();
else
    document.fboardgroup.gr_subject.focus();

function fboardgroup_check(f)
{
    if (typeof(grecaptcha) != 'undefined') { 
        if(grecaptcha.getResponse() == "") { 
            alert("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���."); 
            return false; 
        } 
    }

    f.action = "./boardgroup_form_update.php";
    return true;
}
</script>

<?
include_once ("./admin.tail.php");
?>
