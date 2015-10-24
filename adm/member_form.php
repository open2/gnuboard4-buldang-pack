<?
$sub_menu = "200100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

if ($w == "") 
{
    $required_mb_id = "required minlength=3 alphanumericunderline itemname='ȸ�����̵�'";
    $required_mb_password = "required itemname='�н�����'";

    $mb[mb_mailling] = 1;
    $mb[mb_open] = 1;
    $mb[mb_level] = $config[cf_register_level];
    $html_title = "���";
}
else if ($w == "u") 
{
    $mb = get_member($mb_id);
    if (!$mb[mb_id])
        alert("�������� �ʴ� ȸ���ڷ��Դϴ�."); 

    if ($is_admin != 'super' && $mb[mb_level] >= $member[mb_level])
        alert("�ڽź��� ������ ���ų� ���� ȸ���� ������ �� �����ϴ�.");

    $required_mb_id = "readonly style='background-color:#dddddd;'";
    $required_mb_password = "";
    $html_title = "����";

    $mb[mb_name]        = get_text($mb[mb_name]);
    $mb[mb_nick]        = get_text($mb[mb_nick]);
    $mb[mb_email]       = get_text($mb[mb_email]);
    $mb[mb_homepage]    = get_text($mb[mb_homepage]);
    $mb[mb_birth]       = get_text($mb[mb_birth]);
    $mb[mb_tel]         = get_text($mb[mb_tel]);
    $mb[mb_hp]          = get_text($mb[mb_hp]);
    $mb[mb_addr1]       = get_text($mb[mb_addr1]);
    $mb[mb_addr2]       = get_text($mb[mb_addr2]);
    $mb[mb_signature]   = get_text($mb[mb_signature]);
    $mb[mb_recommend]   = get_text($mb[mb_recommend]);
    $mb[mb_profile]     = get_text($mb[mb_profile]);
    $mb[mb_1]           = get_text($mb[mb_1]);
    $mb[mb_2]           = get_text($mb[mb_2]);
    $mb[mb_3]           = get_text($mb[mb_3]);
    $mb[mb_4]           = get_text($mb[mb_4]);
    $mb[mb_5]           = get_text($mb[mb_5]);
    $mb[mb_6]           = get_text($mb[mb_6]);
    $mb[mb_7]           = get_text($mb[mb_7]);
    $mb[mb_8]           = get_text($mb[mb_8]);
    $mb[mb_9]           = get_text($mb[mb_9]);
    $mb[mb_10]          = get_text($mb[mb_10]);
} 
else 
    alert("����� �� ���� �Ѿ���� �ʾҽ��ϴ�.");

if ($mb[mb_mailling]) $mailling_checked = "checked"; // ���� ����
if ($mb[mb_sms])      $sms_checked = "checked"; // SMS ����
if ($mb[mb_open])     $open_checked = "checked"; // ���� ����

$g4[title] = "ȸ������ " . $html_title;
include_once("./admin.head.php");
?>

<form name=fmember method=post onsubmit="return fmember_submit(this);" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=120px>
<colgroup>
<tr>
    <td colspan=4 class="success"><?=$g4[title]?></td>
</tr>
<tr>
    <td>���̵�</td>
    <td>
        <input type=text class=ed name='mb_id' size=20 maxlength=20 minlength=2 <?=$required_mb_id?> itemname='���̵�' value='<? echo $mb[mb_id] ?>'>
        <?if ($w=="u"){?><a href='./boardgroupmember_form.php?mb_id=<?=$mb[mb_id]?>'>���ٰ��ɱ׷캸��</a><?}?>
    </td>
</tr>
<tr>
    <td>�н�����</td>
    <td><input type=password class=ed name='mb_password' size=20 maxlength=20 <?=$required_mb_password?> itemname='��ȣ'></td>
</tr>
<tr>
    <td>�̸�(�Ǹ�)</td>
    <td><input type=text class=ed name='mb_name' maxlength=20 minlength=2 required itemname='�̸�(�Ǹ�)' value='<? echo $mb[mb_name] ?>'></td>
</tr>
<tr>
    <td>����</td>
    <td><input type=text class=ed name='mb_nick' maxlength=20 minlength=2 required itemname='����' value='<? echo $mb[mb_nick] ?>'> <? if ($mb['mb_nick_date'] && $mb['mb_nick_date']!="0000-00-00") { ?>(<?=$mb['mb_nick_date']?>)<? } ?></td>
</tr>
<tr>
    <td>ȸ�� ����</td>
    <td><?=get_member_level_select("mb_level", 1, $member[mb_level], $mb[mb_level])?></td>
</tr>
<tr>
    <td>����Ʈ</td>
    <td><a href='./point_list.php?sfl=mb_id&stx=<?=$mb[mb_id]?>' class='bold'><?=number_format($mb[mb_point])?></a> ��</td>
</tr>
<tr>
    <td>����ڱ׷�</td>
    <td>
    <input type=text class=ed name='ug_id' id='ug_id' size=20 maxlength=40 value='<? echo $mb[ug_id] ?>' itemname='����ڱ׷�' readonly>
    <? 
    // ����ڱ׷� ���
    $sql = " select * from $g4[user_group_table] ";
    $result = sql_query($sql);
    $ug_str = "<select name='ug_str' onchange=\"javascript:document.getElementById('ug_id').value=this.value;\">";
    $ug_str .= "<option value=''>����ڱ׷� �����ϱ�</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $ug_str .= "<option value='$row[ug_id]'";
        $ug_str .= ">" . cut_str($row[ug_subject],60,'') . "</option>";
    }
    $ug_str .= "</select>";
    echo $ug_str;
    ?>
    </td>
</tr>
<tr>
    <td>E-mail</td>
    <td><input type=text class=ed name='mb_email' size=40 maxlength=100 required email itemname='e-mail' value='<? echo $mb[mb_email] ?>'></td>
</tr>
<tr>
    <td>Ȩ������</td>
    <td><input type=text class=ed name='mb_homepage' size=40 maxlength=255 itemname='Ȩ������' value='<? echo $mb[mb_homepage] ?>'></td>
</tr>
<tr>
    <td>��ȭ��ȣ</td>
    <td><input type=text class=ed name='mb_tel' maxlength=20 itemname='��ȭ��ȣ' value='<? echo $mb[mb_tel] ?>'></td>
</tr>
<tr>
    <td>�ڵ�����ȣ</td>
    <td><input type=text class=ed name='mb_hp' maxlength=20 itemname='�ڵ�����ȣ' value='<? echo $mb[mb_hp] ?>'></td>
</tr>
<tr>
    <td>�ּ�</td>
    <td>
        <input type=text class=ed name='mb_zip1' size=4 maxlength=3 readonly itemname='�����ȣ ���ڸ�' value='<? echo $mb[mb_zip1] ?>'> -
        <input type=text class=ed name='mb_zip2' size=4 maxlength=3 readonly itemname='�����ȣ ���ڸ�' value='<? echo $mb[mb_zip2] ?>'>
        <a href="javascript:;" onclick="win_zip('fmember', 'mb_zip1', 'mb_zip2', 'mb_addr1', 'mb_addr2');"><img src='<?=$g4[bbs_img_path]?>/btn_zip.gif' align=absmiddle border=0></a>
        <br><input type=text class=ed name='mb_addr1' size=40 readonly value='<? echo $mb[mb_addr1] ?>'>
        <br><input type=text class=ed name='mb_addr2' size=25 itemname='���ּ�' value='<? echo $mb[mb_addr2] ?>'> ���ּ� �Է�</td>
</tr>
<tr>
    <td>ȸ��������</td>
    <td colspan=3>
        <input type=file name='mb_icon' class=ed><br>�̹��� ũ��� <?=$config[cf_member_icon_width]?>x<?=$config[cf_member_icon_height]?>���� ���ּ���.
        <?
        $mb_dir = substr($mb[mb_id],0,2);
        $icon_file = "$g4[data_path]/member/$mb_dir/$mb[mb_id].gif";
        if (file_exists($icon_file)) {
            echo "<br><img src='$icon_file' align=absmiddle>";
            echo " <input type=checkbox name='del_mb_icon' value='1' class='csscheck'>����";
        }   
        ?>
    </td>
</tr>
<tr>
    <td>�������</td>
    <td><input type=text class=ed name=mb_birth size=9 maxlength=8 value='<? echo $mb[mb_birth] ?>'></td>
</tr>
<tr>
    <td>����</td>
    <td>
        <select name=mb_sex><option value=''>----<option value='F'>����<option value='M'>����</select>
        <script type="text/javascript"> document.fmember.mb_sex.value = "<?=$mb[mb_sex]?>"; </script></td>
</tr>
<tr>
    <td>���� ����</td>
    <td><input type=checkbox name=mb_mailling value='1' <?=$mailling_checked?>> ���� ������ ����</td>
</tr>
<tr>
    <td>SMS ����</td>
    <td><input type=checkbox name=mb_sms value='1' <?=$sms_checked?>> ���ڸ޼����� ����</td>
</tr>
<tr>
    <td>���� ����</td>
    <td><input type=checkbox name=mb_open value='1' <?=$open_checked?>> Ÿ�ο��� �ڽ��� ������ ����</td>
</tr>
<tr>
    <td>����</td>
    <td><textarea class=ed name=mb_signature rows=5 style='width:99%; word-break:break-all;'><? echo $mb[mb_signature] ?></textarea></td>
</tr>
<tr>
    <td>�ڱ� �Ұ�</td>
    <td><textarea class=ed name=mb_profile rows=5 style='width:99%; word-break:break-all;'><? echo $mb[mb_profile] ?></textarea></td>
</tr>
<tr>
    <td>�޸�</td>
    <td><textarea class=ed name=mb_memo rows=5 style='width:99%; word-break:break-all;'><? echo $mb[mb_memo] ?></textarea></td>
</tr>

<? if ($w == "u") { ?>
<tr>
    <td>ȸ��������</td>
    <td><?=$mb[mb_datetime]?></td>
</tr>
<tr>
    <td>�ֱ�������</td>
    <td><?=$mb[mb_today_login]?></td>
</tr>
<tr>
    <td>IP</td>
    <td><?=$mb[mb_ip]?></td>
</tr>
<tr>
    <td>��������</td>
    <td><?=$mb[mb_realcheck]?></td>
</tr>
<? if ($config[cf_use_email_certify] || $mb[mb_email_certify] !== "0000-00-00 00:00:00") { ?>
<tr>
    <td>�̸�������</td>
    <td><?=$mb[mb_email_certify]?> 
        <? if ($mb[mb_email_certify] == "0000-00-00 00:00:00") { echo "<input type=checkbox name=passive_certify>��������"; } ?>
    </td>
</tr>
<? } ?>
<? } ?>

<? if ($config[cf_use_recommend]) { // ��õ�� ��� ?>
<tr>
    <td>��õ��</td>
    <td><?=($mb[mb_recommend] ? get_text($mb[mb_recommend]) : "����"); // 081022 : CSRF ���� �������� ���� �ڵ� ���� ?></td>
</tr>
<? } ?>

<tr>
    <td>Ż������</td>
    <td><input type=text class=ed name=mb_leave_date size=9 maxlength=8 value='<? echo $mb[mb_leave_date] ?>'></td>
</tr>
<tr>
    <td>������������</td>
    <td><input type=text class=ed name=mb_intercept_date size=9 maxlength=8 value='<? echo $mb[mb_intercept_date] ?>'> <input type=checkbox value='<? echo date("Ymd"); ?>' onclick='if (this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else { this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; } '>����</td>
</tr>

<? for ($i=1; $i<=10; $i++) { ?>
<tr>
    <td>���� �ʵ� <?=$i?></td>
    <td><input type=text class=ed style='width:99%;' name='mb_<?=$i?>' maxlength=255 value='<?=$mb["mb_$i"]?>'></td>
</tr>
<? } ?>

<tr class="success">
    <td>
        ������ �н�����
    </td>
    <td colspan=3>
        <input class='ed' type='password' name='admin_password' itemname="������ �н�����" required>
        <?=help("������ ������ ���ѱ� �Ϳ� ����Ͽ� �α����� �������� �н����带 �ѹ� �� ���°� �Դϴ�.");?>
    </td>
</tr>
</table>

<p align=center>
    <input type=submit class="btn btn-default" accesskey='s' value='  Ȯ    ��  '>&nbsp;
    <input type=button class="btn btn-default" value='  ��  ��  ' onclick="document.location.href='./member_list.php?<?=$qstr?>';">&nbsp;
    
    <? if ($w != '') { ?>
    <input type=button class="btn btn-default" value='  ��  ��  ' onclick="post_delete('./member_delete.php','<?=$mb[mb_id]?>');">&nbsp;
    <? } ?>
</form>

<script type='text/javascript'>
if (document.fmember.w.value == "")
    document.fmember.mb_id.focus();
else if (document.fmember.w.value == "u")
    document.fmember.mb_password.focus();

if (typeof(document.fmember.mb_level) != "undefined") 
    document.fmember.mb_level.value   = "<?=$mb[mb_level]?>"; 

function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jp[e]g|png)$/i) && f.mb_icon.value) {
        alert('�������� �̹��� ������ �ƴմϴ�. (bmp ����)');
        return false;
    }

    f.action = './member_form_update.php';
    return true;
}
</script>

<script type='text/javascript'>
// POST ������� ����
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("�ѹ� ������ �ڷ�� ������ ����� �����ϴ�.\n\n���� �����Ͻðڽ��ϱ�?")) {
    f.mb_id.value = val;
    f.admin_password.value = document.fmember.admin_password.value;
		f.action         = action_url;
		f.submit();
	}
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='w'     value='d'>
<input type='hidden' name='mb_id'>
<input type='hidden' name='admin_password'>
</form>

<?
include_once("./admin.tail.php");
?>
