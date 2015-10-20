<?
$sub_menu = "300900";
include_once("./_common.php");
include_once ("$g4[path]/lib/cheditor4.lib.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

$sql = " select count(*) as cnt from $g4[banner_group_table] ";
$row = sql_fetch($sql);
if (!$row[cnt])
    alert("��ʱ׷��� �Ѱ� �̻� �����Ǿ�� �մϴ�.", "./banner_group_form.php");

$html_title = "���";
if ($w == "") {
    $html_title .= " ����";

    $bn['bg_id'] = $bg_id;

} else if ($w == "u") {
    $html_title .= " ����";

    $sql = " select * from $g4[banner_table] where bn_id = '$bn_id' ";
    $bn = sql_fetch($sql);
    if (!$bn[bn_id])
        alert("�������� �ʴ� ��� �Դϴ�.");

    $bn_table_attr = "readonly style='background-color:#dddddd'";
}

// �׷���� ���� üũ
$sql = " select * from $g4[banner_group_table] where bg_id = '$bg_id' ";
$bg = sql_fetch($sql);

if ($is_admin !== "super") {
    if ($member[mb_id] !== $bg[bg_admin]) 
        alert("�׷��� Ʋ���ϴ�.");
    else
        $is_admin = "group";
}

$g4[title] = $html_title;
include_once ("./admin.head.php");

include_once ("$g4[path]/lib/banner.lib.php");
?>

<form name=fbannerform method=post onsubmit="return fbannerform_submit(this)" enctype="multipart/form-data">
<input type=hidden name="w"     value="<?=$w?>">
<input type=hidden name="sfl"   value="<?=$sfl?>">
<input type=hidden name="stx"   value="<?=$stx?>">
<input type=hidden name="sst"   value="<?=$sst?>">
<input type=hidden name="sod"   value="<?=$sod?>">
<input type=hidden name="page"  value="<?=$page?>">
<input type=hidden name="token" value="<?=$token?>">

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=5%>
<colgroup width=20%>
<colgroup width=75%>
<tr>
    <td colspan=3><?=$html_title?></td>
</tr>
<tr>
    <td></td>
    <td>���ID</td>
    <td><input type=text class=ed name=bn_id size=30 maxlength=20 <?=$bn_table_attr?> required itemname='���ID' value='<?=$bn[bn_id] ?>'>
        <? 
        if ($w == "") 
            echo "������, ����, _ �� ���� (������� 20�� �̳�)";
        ?>
    </td>
</tr>
<tr>
    <td></td>
    <td>�׷�</td>
    <td>
        <?=get_banner_group_select('bg_id', $bn[bg_id], "required itemname='�׷�'");?>
        <? if ($w=='u') { ?><a href="javascript:location.href='./banner_list.php?sfl=a.bg_id&stx='+document.fbannerform.bg_id.value;">���ϱ׷��ʸ��</a><?}?></td>
</tr>
<tr>
    <td></td>
    <td>��� ����</td>
    <td>
        <input type=text class=ed name=bn_subject size=60 maxlength=120 required itemname='��� ����' value='<?=get_text($bn[bn_subject])?>'> (alt �Ǵ� title)
    </td>
</tr>

<tr>
    <td></td>
    <td>��� �̹���</td>
    <td style='padding-top:7px; padding-bottom:7px;'>
        <b>��� �̹����� ���� : <?=$bg[bg_width]?>px, ���� : <?=$bg[bg_height]?>px ũ��� �־��ּ���.</b><br>
        <input type=file name=bn_image class=ed size=60>
        <?
        if ($bn[bn_image]) {
            $bn_image = "$g4[data_path]/banner/{$bn['bg_id']}/$bn[bn_image]";
            echo "<br><a href='$bn_image' target='_blank'>$bn[bn_image] ( $bn[bn_filename] )</a> <input type=checkbox name='bn_image_del' value='$bn[bn_image]'> ����";
            // ����� ���� db�� �־�� �ʿ�� ����.
            $im = getimagesize($bn_image);
            echo "<br>$im[3]";
            echo "<br><a href='$bn_image' target=_blank><img src='" . resize_dica($bn_image, 500) . "'></a>";
        }
        ?>
    </td>
</tr>
</tr>
<tr>
    <td></td>
    <td>��� TEXT</td>
    <td style='padding-top:7px; padding-bottom:7px;'>
        <script type="text/javascript" src="<?=$g4[cheditor4_path]?>/cheditor.js?v=$g4[cheditor_ver]"></script>
        <?=cheditor1('bn_text', '100%', '200');?>
        <?=cheditor2('bn_text', $bn[bn_text]);?>
    </td>
</tr>

<tr>
    <td><input type=checkbox name=chk_use value=1></td>
    <td>��ʻ��</td>
    <td><input type=checkbox name=bn_use value='1' <?=$bn[bn_use]?'checked':'';?>>���</td>
</tr>
<tr>
    <td><input type=checkbox name=chk_target value=1></td>
    <td>���Ÿ��</td>
    <td><input type=checkbox name=bn_target value='1' <?=$bn[bn_target]?'checked':'';?>>��â (üũ�ϸ� ��â����)</td>
</tr>
<tr>
    <td></td>
    <td>Ÿ��URL</td>
    <td><input type=text class=ed name=bn_url size=60 required itemname='Ÿ�� URL' value='<?=$bn[bn_url]?>'></td>
</tr>

<tr>
    <td><input type=checkbox name=chk_start_datetime value=1></td>
    <td>������</td>
    <td><input type=text class=ed name='bn_start_datetime' id='bn_start_datetime' size=24 maxlength=19 required value='<?=$bn[bn_start_datetime]?>'>
    <a href="javascript:win_calendar('bn_start_datetime', document.getElementById('bn_start_datetime').value, '-');"><img src='<?=$g4[admin_path]?>/img/calendar.gif' border=0 align=absmiddle title='�޷� - ��¥�� �����ϼ���'></a>
    </td>
</tr>
<tr>
    <td><input type=checkbox name=chk_end_datetime value=1></td>
    <td>������</td>
    <td><input type=text class=ed name='bn_end_datetime' id='bn_end_datetime' size=24 maxlength=19 required value='<?=$bn[bn_end_datetime]?>'>
    <a href="javascript:win_calendar('bn_end_datetime', document.getElementById('bn_end_datetime').value, '-');"><img src='<?=$g4[admin_path]?>/img/calendar.gif' border=0 align=absmiddle title='�޷� - ��¥�� �����ϼ���'></a>
    +30<input type=button name=end_date_chk1 value="<? echo date("Y-m-d", $g4[server_time]+(60*60*24*30)); ?>" onclick="this.form.bn_end_datetime.value=this.value+' 23:59:59'" title='����+30��'>
    +90<input type=button name=end_date_chk2 value="<? echo date("Y-m-d", $g4[server_time]+(60*60*24*90)); ?>" onclick="this.form.bn_end_datetime.value=this.value+' 23:59:59'" title='����+90��'>
    +180<input type=button name=end_date_chk3 value="<? echo date("Y-m-d", $g4[server_time]+(60*60*24*180)); ?>" onclick="this.form.bn_end_datetime.value=this.value+' 23:59:59'" title='����+180��'>
    </td>
</tr>
<tr>
    <td></td>
    <td>��� ����</td>
    <td><input type=text class=ed name=bn_order size=5 value='<?=$bn[bn_order]?>'> ���ڰ� ���� ��� ���� �˻�</td>
</tr>

<? for ($i=1; $i<=3; $i++) { ?>
<tr>
    <td><input type=checkbox name=chk_<?=$i?> value=1></td>
    <td><input type=text class=ed name='bn_<?=$i?>_subj' value='<?=get_text($bn["bn_{$i}_subj"])?>' title='�����ʵ� <?=$i?> ����' style='text-align:right;font-weight:bold;'></td>
    <td><input type=text class=ed style='width:80%;' name='bn_<?=$i?>' value='<?=get_text($bn["bn_$i"])?>' title='�����ʵ� <?=$i?> ������'></td>
</tr>
<? } ?>

<tr>
    <td></td>
    <td>��� ������</td>
    <td><?=$bn['bn_datetime']?></td>
</tr>

</table>

<p align=center>
    <input type=submit class="btn btn-default" accesskey='s' value='  Ȯ  ��  '>&nbsp;
    <input type=button class="btn btn-default" value='  ��  ��  ' onclick="document.location.href='./banner_list.php?<?=$qstr?>';">&nbsp;
</form>

<script type="text/javascript">
function fbannerform_submit(f) {
    var tmp_title;
    var tmp_image;

    tmp_title = "���";
    tmp_image = f.bn_image;
    if (tmp_image.value) {
        if (!tmp_image.value.toLowerCase().match(/.(gif|jpg|png)$/i)) {
            alert(tmp_title + "�̹����� gif, jpg, png ������ �ƴմϴ�.");
            return false;
        }
    }

    <?=cheditor3('bn_text')."\n";?>

    f.action = "./banner_form_update.php";
    return true;
}
</script>

<?
include_once ("./admin.tail.php");
?>
