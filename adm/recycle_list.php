<?
$sub_menu = "300560";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[recycle_table] ";

$sql_search = " where rc_wr_id = rc_wr_parent ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        case "bo_table" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "rc_datetime";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if (!$page) $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

// ���� �Խñ� ��
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and rc_delete = '1'
         $sql_order ";
$row = sql_fetch($sql);
$delete_count = $row[cnt];

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>ó��</a>";

$g4[title] = "���������";
include_once("./admin.head.php");

$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$colspan = 15;
?>

<script type="text/javascript">
var list_delete_php = "recycle_list_delete.php";
</script>

<script type="text/javascript">
function recycle_delete(ok)
{
    var msg;

    if (ok == 1)
        msg = "<?=$config[cf_recycle_days]?>���� ���� �������� ������ �����մϴ�.\n\n\n�׷��� �����Ͻðڽ��ϱ�?";
    else
        msg = "<?=$config[cf_recycle_days]?>���� ���� �������� �����մϴ�.\n\n\n�׷��� �����Ͻðڽ��ϱ�?";

    if (confirm(msg)) {
        document.location.href = "./recycle_delete.php?ok=" + ok;
    }
}
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> 
        (�����ۼ� : <?=number_format($total_count)?>, �����ۼ� : <?=number_format($delete_count)?>)
        &nbsp;&nbsp;<a href="javascript:recycle_delete();">�����ۻ���</a>
        &nbsp;&nbsp;<a href="javascript:recycle_delete(1);">�����ۿ�������</a>
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='mb_id'>ȸ�����̵�</option>
        <option value='bo_table'>�Խ���</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='�˻���' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">�˻�</button>
    </div>
</div>
</form>

<form name=fmemberlist method=post role="form" class="form-inline">
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=100>
<colgroup width=80>
<colgroup width=60>
<colgroup width=''>
<colgroup width=40>
<colgroup width=80>
<colgroup width=80>
<tr class='success'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td><?=subject_sort_link('mb_id')?>ȸ�����̵�</a></td>
    <td><?=subject_sort_link('bo_table')?>�Խ���id</a></td>
    <td>�Խñ�id</td>
    <td>�Խñ�����</td>
    <td><?=subject_sort_link('rc_datetime', '', 'desc')?>������</a></td>
  	<td>����</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    
    $mb = get_member($row[mb_id]);
    $mb_nick = get_sideview($mb[mb_id], get_text($mb[mb_nick]), $mb[mb_email], $mb[mb_homepage]);    

    // �Խñ� ����
    $tmp_write_table = $g4['write_prefix'] . $row[rc_bo_table];
    $sql2 = " select wr_subject, wr_content from $tmp_write_table where wr_id = '$row[rc_wr_id]' ";
    $write = sql_fetch($sql2);
    $wr_subject = conv_subject($write[wr_subject],80);
    if ($row[rc_delete])
        $wr_subject = "<strike>" . $wr_subject . "</stricke>";

    // �ڸ�Ʈ���� ����
    $c_flag="";
    if ($row[wr_is_comment])
        $c_flag = " C";
    
    // wr_id
    if ($c_flag)
        $wr_id = $row[wr_id] . $c_flag;
    else
        $wr_id = "<a href='$g4[admin_path]/recycle_view.php?bo_table=$row[rc_bo_table]&wr_id=$row[rc_wr_id]&org_bo_table=$row[bo_table]' target=_blank>" . $row[wr_id] . "</a>";

    // ���� ��ư�� ���
    if ($row[rc_delete] == 0)
        $s_recover = "<a href=\"javascript:post_recover('recycle_recover.php', '$row[rc_no]');\"><i class=\"fa fa-undo\" title='����'></i></a>";
    else
        $s_recover = "";

    // ��ڰ� �����Ѱ� (mb_id�� rc_mb_id�� �ٸ� ���)���� �ڿ� mark
    $mb_remover="";
    if ($row[mb_id] !== $row[rc_mb_id])
        $mb_remover="&nbsp;<i class='fa fa-gavel' title='�����ڰ� �������� ��'></i>";

    // �Խ��Ǿ��̵�. �Խ��� ����
    $bo_info = get_board($row[bo_table],"bo_subject");
    $bo_table1 = "<a href='$g4[admin_path]/recycle_list.php?sfl=bo_table&stx=$row[bo_table]' title='$bo_info[bo_subject]'>$row[bo_table]</a>";

    $list = $i%2;
    echo "
    <input type=hidden name=rc_no[$i] value='$row[rc_no]'>
    <tr class='list$list col1 ht center'>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td title='$row[mb_id]'>$mb_nick$mb_remover</td>
        <td>$bo_table1</td>
        <td>$wr_id</td>
        <td>$wr_subject</td>
        <td>" . get_datetime($row[rc_datetime]) . "</td>
        <td>$s_recover</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='7' align=center height=100>�ڷᰡ �����ϴ�.</td></tr>";

echo "</table>";
?>

<!-- ������ -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<div class="btn-group">
    <? if ($is_admin == "super") { ?>
        <input type=button class='btn btn-default' value='���û���' onclick="btn_check(this.form, 'delete')">
    <? } ?>
</div>

<?
if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>
</form>

* ������ ������ ���� mark�� �ϰ� �����δ� �������� �ʽ��ϴ�. ���� �Խñ� ������ ���Ͻø� ������������� �޴��� ������ּ���.<br>
* ȸ�����̵� ���� �������� �ִ� ����, ����ڰ� ������ ���� �ƴ϶� �����ڰ� ������ �� �Դϴ�.<br>
* �Խ���id�� Ŭ���ϸ� �ش� �Խ����� �������� ���ĵǸ�, �Խñ� id�� Ŭ���ϸ� �ش� �Խñ��� ��â�� ��ϴ�.

<script type="text/javascript">
// POST ������� ����
function post_recover(action_url, val)
{
	var f = document.fpost;

	if(confirm("������ �ڷḦ ���� �մϴ�.\n\n���� �����Ͻðڽ��ϱ�?")) {
        f.rc_no.value = val;
		f.action      = action_url;
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
<input type='hidden' name='rc_no'>
</form>

<?
include_once ("./admin.tail.php");
?>
