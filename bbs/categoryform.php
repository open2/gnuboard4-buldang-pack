<?
include_once("./_common.php");
include_once ("$g4[path]/lib/cheditor4.lib.php");

$sql_common = " from $g4[category_table] ";

if ($w == "") 
{
    if ($is_admin != 'super' && !$ca_id)
        alert("�ְ�����ڸ� 1�ܰ� �з��� �߰��� �� �ֽ��ϴ�.");

    $len = strlen($ca_id);
    if ($len == 10) 
        alert("�з��� �� �̻� �߰��� �� �����ϴ�.\\n\\n5�ܰ� �з������� �����մϴ�.");

    $len2 = $len + 1;

    $sql = " select MAX(SUBSTRING(ca_id,$len2,2)) as max_subid from $g4[category_table]
              where SUBSTRING(ca_id,1,$len) = '$ca_id' ";
    $row = sql_fetch($sql);

    $subid = base_convert($row[max_subid], 36, 10);
    $subid += 36;
    if ($subid >= 36 * 36) 
    {
        //alert("�з��� �� �̻� �߰��� �� �����ϴ�.");
        // ����·�
        $subid = "  ";
    }
    $subid = base_convert($subid, 10, 36);
    $subid = substr("00" . $subid, -2);
    $subid = $ca_id . $subid;

    $sublen = strlen($subid);

    if ($ca_id) // 2�ܰ��̻� �з�
    { 
        $sql = " select * from $g4[category_table] where ca_id = '$ca_id' ";
        $ca = sql_fetch($sql);
        $html_title = $ca[ca_name] . " �����з��߰�";
        $ca[ca_name] = "";
    } 
    else // 1�ܰ� �з�
    {
        $html_title = "1�ܰ�з��߰�";
        $ca[ca_use] = 1;
        $ca[ca_explan_html] = 1;
        $ca[ca_list_mod] = 4;
        $ca[ca_list_row] = 5;
    }
} 
else if ($w == "u") 
{
    $sql = " select * from $g4[category_table] where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);
    if (!$ca[ca_id]) 
        alert("�ڷᰡ �����ϴ�.");

    $html_title = $ca[ca_name] . " ����";
    $ca[ca_name] = get_text($ca[ca_name]);
}

$qstr = "page=$page&sort1=$sort1&sort2=$sort2";

$g4[title] = $html_title;
include_once ("./_head.php");
?>

<link rel="stylesheet" href="<?=$g4['admin_path']?>/admin.style.css" type="text/css">

<?=subtitle("�⺻ �Է�")?>

<script type="text/javascript" src="<?=$g4[cheditor4_path]?>/cheditor.js?v=$g4[cheditor_ver]"></script>
<?=cheditor1('ca_head_html', '100%', '150');?>
<?=cheditor1('ca_tail_html', '100%', '150');?>

<form name=fcategoryform method=post action="./categoryformupdate.php" enctype="multipart/form-data" onsubmit='return fcategoryformcheck(this);' style="margin:0px;">

<table cellpadding=0 cellspacing=0 width=100%>
<input type=hidden name=codedup  value="<?=$default[de_code_dup_use]?>">
<input type=hidden name=w        value="<?=$w?>">
<input type=hidden name=page     value="<?=$page?>">
<input type=hidden name=sort1    value="<?=$sort1?>">
<input type=hidden name=sort2    value="<?=$sort2?>">
<colgroup width=15%>
<colgroup width=35% bgcolor=#FFFFFF>
<colgroup width=15%>
<colgroup width=35% bgcolor=#FFFFFF>
<tr><td colspan=4 height=2 bgcolor=#0E87F9></td></tr>
<tr class=ht>
    <td height=28>�з��ڵ�</td>
    <td colspan=3>

    <? if ($w == "") { ?>
        <input type=text class=ed id=ca_id name=ca_id itemname='�з��ڵ�' size='<?=$sublen?>' maxlength='<?=$sublen?>' minlength='<?=$sublen?>' nospace alphanumeric value='<?=$subid?>'>
        <? if ($default[de_code_dup_use]) { ?><a href='javascript:;' onclick="codedupcheck(document.getElementById('ca_id').value)"><img src='./img/btn_code.gif' border=0 align=absmiddle></a><? } ?>
        <?=help("�ڵ����� �������� �з��ڵ带 ����Ͻñ� ���ص帮���� ���� �Է��� �����ε� ����� �� �ֽ��ϴ�.\n�з��ڵ�� ���߿� ������ ���� �����Ƿ� �����ϰ� �����Ͽ� ����Ͻʽÿ�.\n\n�з��ڵ�� 2�ڸ��� 10�ڸ��� ����Ͽ� 5�ܰ踦 ǥ���� �� �ֽ��ϴ�.\n0~z���� �Է��� �����ϸ� �� �з��� �ִ� 1296������ ǥ���� �� �ֽ��ϴ�.\n�׷��Ƿ� �� 3656158440062976������ �з��� ����� �� �ֽ��ϴ�.");?>
    <? } else { ?>
        <input type=hidden name=ca_id value='<?=$ca[ca_id]?>'><?=$ca[ca_id]?>
        <? echo icon("�̸�����", "{$g4[shop_path]}/list.php?ca_id=$ca_id"); ?>
        <? echo "<a href='./categoryform.php?ca_id=$ca_id&$qstr' title='�����з� �߰�'><img src='$g4[admin_path]/img/icon_insert.gif' border=0 align=absmiddle></a>"; ?>
        <a href='./itemlist.php?sca=<?=$ca[ca_id]?>'>��ǰ����Ʈ</a>
    <? } ?>

    </td>
</tr>
<tr class=ht>
    <td>�з���<font color="#ff6600"> <b>*</b></font></td>
    <td colspan=3><input type=text name=ca_name value='<? echo $ca[ca_name] ?>' size=38 required itemname="�з���" class=ed></td>
</tr>
<tr class=ht>
    <td>�ǸŰ���</td>
    <td colspan=3>
        <input type=checkbox name='ca_use' <? echo ($ca[ca_use]) ? "checked" : ""; ?> value='1'>��
        <?=help("��� �ǸŸ� �ߴ��ϰų� ��� ���� ��쿡 üũ�ϸ� �� �з���� �� �з��� ���� ��ǰ�� ������� ������ �ֹ��� �� �� �����ϴ�.");?>
    </td>
</tr>
<tr><td colspan=4 height=1 bgcolor=#CCCCCC></td></tr>
</table>


<p>
<?=subtitle("���� �Է�")?>
<table cellpadding=0 cellspacing=0 width=100%>
<colgroup width=15%>
<colgroup width=85% bgcolor=#FFFFFF>
<tr><td colspan=4 height=2 bgcolor=#0E87F9></td></tr>
<tr class=ht>
    <td>��� ���� ���</td>
    <td colspan=3><input type=text class=ed name=ca_include_head size=60 value="<?=$ca[ca_include_head]?>"> <?=help("�з����� ���+������ ������ �ٸ� ��� ���+���� ������ ������ ��θ� �Է��մϴ�.<p>�Է��� ������ �⺻ ��� ������ ����մϴ�.<p>��� ����� �޸� PHP �ڵ带 ����� �� �ֽ��ϴ�.");?></td>
</tr>
<tr class=ht>
    <td>�ϴ� ���� ���</td>
    <td colspan=3><input type=text class=ed name=ca_include_tail size=60 value="<?=$ca[ca_include_tail]?>"> <?=help("�з����� �ϴ�+������ ������ �ٸ� ��� �ϴ�+���� ������ ������ ��θ� �Է��մϴ�.<p>�Է��� ������ �⺻ �ϴ� ������ ����մϴ�.<p>�ϴ� ����� �޸� PHP �ڵ带 ����� �� �ֽ��ϴ�.");?></td>
</tr>
<tr class=ht>
    <td>����̹���</td>
    <td colspan=3>
        <input type=file class=ed name=ca_himg size=40>
        <?
        $himg_str = "";
        $himg = "{$category_path}/{$ca[ca_id]}_h";
        if (file_exists($himg)) 
        {
            echo "<input type=checkbox name=ca_himg_del value='1'>����";
            $himg_str = "<img src='$himg' border=0>";
            //$size = getimagesize($himg);
            //echo "<img src='$g4[admin_path]/img/icon_viewer.gif' border=0 align=absmiddle onclick=\"imageview('himg', $size[0], $size[1]);\">";
            //echo "<div id='himg' style='left:0; top:0; z-index:+1; display:none; position:absolute;'><img src='$himg' border=1></div>";
        }
        ?>
        <?=help("��ǰ����Ʈ ������ ��ܿ� ����ϴ� �̹����Դϴ�.");?>
    </td>
</tr>
<? if ($himg_str) { echo "<tr><td colspan=4>$himg_str</td></tr>"; } ?>

<tr class=ht>
    <td>�ϴ��̹���</td>
    <td colspan=3>
        <input type=file class=ed name=ca_timg size=40>
        <?
        $timg_str = "";
        $timg = "{$category_path}/{$ca[ca_id]}_t";
        if (file_exists($timg)) {
            echo "<input type=checkbox name=ca_timg_del value='1'>����";
            $timg_str = "<img src='$timg' border=0>";
            //$size = getimagesize($timg);
            //echo "<img src='$g4[admin_path]/img/icon_viewer.gif' border=0 align=absmiddle onclick=\"imageview('timg', $size[0], $size[1]);\"><input type=checkbox name=ca_timg_del value='1'>����";
            //echo "<div id='timg' style='left:0; top:0; z-index:+1; display:none; position:absolute;'><img src='$timg' border=1></div>";
        }
        ?>
        <?=help("��ǰ����Ʈ ������ �ϴܿ� ����ϴ� �̹����Դϴ�.");?>
    </td>
</tr>
<? if ($timg_str) { echo "<tr><td colspan=4>$timg_str</td></tr>"; } ?>

<tr class=ht>
    <td>��� ���� <?=help("��ǰ����Ʈ ������ ��ܿ� ����ϴ� HTML �����Դϴ�.", -150);?> </td>
    <td colspan=3 align=right><br /><?=cheditor2('ca_head_html', $ca[ca_head_html]);?></td>
</tr>
<tr class=ht>
    <td>�ϴ� ���� <?=help("��ǰ����Ʈ ������ �ϴܿ� ����ϴ� HTML �����Դϴ�.", -150);?></td>
    <td colspan=3 align=right><br /><?=cheditor2('ca_tail_html', $ca[ca_tail_html]);?></td>
</tr>
<tr><td colspan=4 height=1 bgcolor=#CCCCCC></td></tr>
</table>


<? if ($w == "u") { ?>
<p>
<?=subtitle("��Ÿ")?>
<table cellpadding=0 cellspacing=0 width=100%>
<colgroup width=15%>
<colgroup width=85% bgcolor=#FFFFFF>
<tr><td colspan=4 height=2 bgcolor=#0E87F9></td></tr>
<tr class=ht>
    <td>�����з�</td>
    <td colspan=3>
        <input type=checkbox name=sub_category value='1' onclick="if (this.checked) if (confirm('�� �з��� ���� ���� �з��� �Ӽ��� �Ȱ��� �����մϴ�.\n\n�� �۾��� �ǵ��� ����� �����ϴ�.\n\n�׷��� �����Ͻðڽ��ϱ�?')) return ; this.checked = false;"> �� �з��� ������ ���� �������� �ݿ�
        <?=help("�� �з��� �ڵ尡 10 �̶�� 10 ���� �����ϴ� �����з��� �������� �� �з��� �����ϰ� �����մϴ�.", 0, -100);?>
    </td>
</tr>
<tr><td colspan=4 height=1 bgcolor=#CCCCCC></td></tr>
</table>
<? } ?>


<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  Ȯ  ��  '>&nbsp;
    <input type=button class=btn1 accesskey='l' value='  ��  ��  ' onclick="document.location.href='./categorylist.php?<?=$qstr?>';">
</form>

<script language='javascript'>
function fcategoryformcheck(f)
{
    <?=cheditor3('ca_head_html');?>
    <?=cheditor3('ca_tail_html');?>

    if (f.w.value == "") {
        if (f.codedup.value == '1') {
            alert("�ڵ� �ߺ��˻縦 �ϼž� �մϴ�.");
            return false;
        }
    }

    return true;
}

function codedupcheck(id) 
{
    if (!id) {
        alert('�з��ڵ带 �Է��Ͻʽÿ�.');
        f.ca_id.focus();
        return;
    }

    window.open("./codedupcheck.php?ca_id="+id+'&frmname=fcategoryform', "hiddenframe");
}

document.fcategoryform.ca_name.focus();
</script>

<iframe name='hiddenFrame' width=0 height=0></iframe>

<?
include_once ("./_tail.php");
?>
