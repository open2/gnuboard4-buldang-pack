<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

// �׷��� ī��Ʈ ���ϱ�
$mb_sql = " select count(*) as cnt from $g4[memo_group_table] where mb_id = '$member[mb_id]' ";
$result = sql_fetch($mb_sql);
$total_count = $result['cnt'];

$one_rows = $config['cf_memo_page_rows'];       // ���������� ���μ�
$total_page = ceil($total_count / $one_rows);   // ��ü ������ ��� 
if ($page == 0)   // �������� ������ ù ������ (1 ������) 
    $page = 1; 
$from_record = ($page - 1) * $one_rows; // ���� ���� ����
$to_record = $from_record + $one_rows ;

$sql = " select * from $g4[memo_group_table] where mb_id = '$member[mb_id]' order by gr_id desc limit $from_record, $one_rows"; 
$subj = "���� �޸�׷� ���";
$result = sql_query($sql);

$cols = 6; 
$gr_width = '100%'; // �׷������ ��
$ss_id = 'gr_id'; // ���� ���̵� �����ϴϱ� ������ ���ܼ� ��¿ �� ����... ��..��
?>

<!-- �׷���� ���� -->
<table width="100%" height="30" border="0" cellspacing="0">
    <tr>
    <td>
        &nbsp;<img src="<?=$memo_skin_path?>/img/memo_icon06.gif" align=absmiddle /> <a href="<?=$memo_url?>?kind=memo_group_admin">�׷����</a> :: <?=$subj?> ::
    </td>
    </tr>
</table>

<form method="post" name="grouplist" id="grouplist">
<input type="hidden" class="ed" name="gr_edit" id="gr_edit" value="<?=$gr_edit?>" />
<table class="tbl_type" width="100%" border="1" cellspacing="0">
    <colgroup> 
      <col width="30">
      <col width="">
      <col width="100">
      <col width="100">
      <col width="100">
    </colgroup>
    <thead>
    <tr>
        <th colspan=5>
        �� �޸�׷��� <b>( <a href='<?=$memo_url?>?kind=memo_group_admin'><?=$total_count?></a> )</b>
        </th>
    </tr>
    <tr>
        <th></th>
        <th align="left">&nbsp;�׷��</th>
        <th>�����</th>
        <th>������</th>
        <th>�����</th>
    </tr>
    </thead>
    <?//���
    for ($i=0; $row = sql_fetch_array($result); $i++) { // Join �Ǵ� �˻����� ���� �ʰ� ������ member ������ fetch �ϴ� ���� ȿ�� ����
    ?>
    <tr>
        <td>
        <input type="checkbox" name="chk_gr_id[]" value="<?=$row[gr_id]?>" />
        </td>
        <td align="left">&nbsp;
        <a href="<?=$memo_url?>?kind=memo_group&gr_id=<?=$row[gr_id]?>"><?=get_text(stripslashes($row['gr_name']));?></a>
        &nbsp;&nbsp;<a href="javascript:memo_box(<?=$row['gr_id']?>)"><img src='<?=$memo_skin_path?>/img/btn_c_modify.gif' border='0' align='absmiddle'></a>
        <span id='memo_<?=$row[gr_id]?>' style='display:none;'>
        <input type="type" class="ed" id="gr_edit_<?=$row[gr_id]?>" name="gr_edit_<?=$row[gr_id]?>" size="30" value="<?=preg_replace("/\"/", "&#034;", stripslashes(get_text($row['gr_name'],0)))?>" />
        <a href="javascript:memo_update('<?=$row[gr_id]?>')"><img src='<?=$memo_skin_path?>/img/btn_c_ok.gif' border='0'/></a> </span>
        </td>
        <td>
        <? 
        $sql1 = " select count(*) as cnt from $g4[memo_group_member_table] where gr_id = '$row[gr_id]' ";
        $result1 = sql_fetch($sql1);
        echo $result1['cnt'];
        ?>
        </td>
        <td>
        <? if ($result1['cnt'] > 0) { ?>
            <a href="<?=$memo_url?>?kind=write&gr_id=<?=$row[gr_id]?>">write</a>
        <? } ?>
        </td>
        <td><?=get_datetime($row['gr_datetime'])?></td>
    </tr>
    <? } ?>
    <tfoot>
    <? if ($total_page > 1) { ?>
    <tr>
        <td colspan=5 style="padding:2px 0 2px;" height=30px>
        <?
        $page = get_paging($config[cf_write_pages], $page, $total_page, "?kind=memo_group_admin&page="); 
        echo "$page";
        ?>
        </td>
    </tr>
    <? } ?>
    <tr>
        <td colspan=5 align=left style="padding:2px 0 2px 10px;" height=30px><a href="javascript:select_delete_gr();">�׷����</a>
        </td>
    </tr>
    </tfoot>
</table>
</form>

<br>

<table class="tbl_type" width="100%" border="1" cellspacing="0">
    <thead>
    <tr>
    <th>���ο� �׷� ����ϱ�</th>
    </tr>
    </thead>
    <tr>
        <td>
        <form name="gr_register" action="javascript:gr_register_submit(document.gr_register);" method="post" enctype="multipart/form-data" autocomplete="off" >
        <input type="hidden" class="ed" name="mb_id" value="<?=$member[mb_id]?>" />
        �޸�׷� : 
        &nbsp;<input name="gr_name" type="text" class="ed" itemname='�޸�׷�' size="45" />
        &nbsp;<input type="submit" class="btn1" value=' �޸�׷���' />
        </form>
        </td>
    </tr>
</table>

<script type="text/javascript">
function gr_register_submit(f)
{
    f.action = "<?=$memo_skin_path?>/memo2_group_update.php";
    f.submit();
}

var save_before = '';
function check_confirm_gr(str) {
    var f = document.grouplist;
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_gr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(str + "�� �׷��� �Ѱ� �̻� �����ϼ���.");
        return false;
    }
    return true;
}

// ������ �׷� ����
function select_delete_gr() {
    var f = document.grouplist;

    str = "����";
    if (!check_confirm_gr(str))
        return;

    if (!confirm("������ �׷��� ���� "+str+" �Ͻðڽ��ϱ�?\n\n"))
        return;

    f.action = "<?=$memo_skin_path?>/memo2_group_delete.php";
    f.submit();
}

function memo_box(memo_id)
{
    var el_id= 'memo_' + memo_id;

    if (save_before != el_id) {
      
        if (save_before)
        {
            document.getElementById(save_before).style.display = 'none';
        }

        document.getElementById(el_id).style.display = 'block';
        save_before = el_id;
    }
}

// ������ �޸� ������Ʈ
function memo_update(gr_id) {
    var f = document.grouplist;
    var el_id = 'gr_edit_' + gr_id;

    document.getElementById('gr_edit').value = document.getElementById(el_id).value;
    f.action = "<?=$memo_skin_path?>/memo2_group_name_update.php?gr_id=" + gr_id;
    f.submit();
}
</script>

<form method="post" name="fboardlist" id="fboardlist">
</form>