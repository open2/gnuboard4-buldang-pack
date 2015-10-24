<?
$sub_menu = "300820";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$sql_common = " from $g4[board_cheditor_table] ";

// ������ �������� �� �ð� - 12�ð� ���ı��� �ƹ��͵� ������ �������� �з�
$date_gap = date("Y-m-d H:i:s", $g4[server_time] - 3600*12);

$sql_search = " where (del = 1 or wr_id is null) and (bc_datetime < '$date_gap') ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_id" :
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "bc_id";
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

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>ó��</a>";

$g4[title] = "�Ⱦ����̹�����Ϻ���";
include_once("./admin.head.php");

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);
?>

<script type="text/javascript">
var list_delete_php = "chimage_unused_delete.php";
</script>

<script type="text/javascript">
function unused_clear() {
    if (confirm("�Ⱦ��� �̹��� ������ �����Ͻø�, ������� �Ϸ� ������ ��� ������ ���� �̹����� ��� ���� �մϴ�.\n\n������ �̹����� _delete�� ������ ���丮�� ���� �ǹǷ� ����� ���� �Ͻñ� �ٶ��ϴ�.\n\n\n�׷��� �����Ͻðڽ��ϱ�?")) {
        document.location.href = "./chimage_unused_clear.php?ok=1";
    }
}
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (�Ⱦ����̹������� : <?=number_format($total_count)?>) <a href="javascript:unused_clear();">��ü �Ⱦ��� �̹��� ����</a>
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

<form name=fsingolist method=post role="form" class="form-inline">
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<tr class='bgcol1 bold col1 ht center'>
    <td width=30><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td width=110><?=subject_sort_link('mb_id')?>ȸ�����̵�</a></td>
    <td width=110><?=subject_sort_link('bo_table')?>�Խ���</a></td>
    <td width=100><?=subject_sort_link('bc_filesize')?>�̹����뷮(KB)</a></td>
	  <td width=100><?=subject_sort_link('bc_datetime')?>��¥</a></td>
    <td>�̹������� �̸�</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    if ($row[mb_id]) {
        $mb = sql_fetch(" select mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date from $g4[member_table] where mb_id = '$row[mb_id]' ");
        $mb_nick = get_text($mb[mb_nick]);
    } else 
        $mb_nick = "<span style='color:#222222;'>��ȸ��</a>";
    $mbinfo = "<a href='$_SERVER[PHP_SELF]?sfl=mb_id&stx=$row[mb_id]'>$mb_nick</a>";

    $subject = get_text($row[wr_subject]);
    $bo = get_board($row[bo_table], "bo_subject");
    $bo_subject = $bo[bo_subject];
    $boinfo = "<a href='$_SERVER[PHP_SELF]?sfl=bo_table&stx=$row[bo_table]'>$bo_subject</a>";

    // $img[src] �� ���� ������ �̹Ƿ� �̹��� ������ ����θ� ���մϴ�.
    // �̷��� �߶���� ����� �� ��ΰ� ���´�.
    $fl = explode("/$g4[data]/",$row[bc_dir]);
    $rel_path = "../" . $g4[data] . "/" . $fl[1];

    $img_link = $rel_path . "/" . $row[bc_file];
    $imginfo = "<a href='$img_link' target=new>" . $row[bc_source] . "</a>";

    $bc_filesize = number_format($row[bc_filesize]);

    echo "
    <input type=hidden name=bc_id[$i] value='$row[bc_id]'>
    <tr class='list$list col1 center' height=25>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td title='$row[mb_id]' align='left'>$mbinfo</td>
        <td>" . $boinfo . "</td>
        <td>&nbsp$bc_filesize</td>
        <td>" . get_datetime($row[bc_datetime]) . "</a></td>
        <td>" . $imginfo. "</td>
    </tr>
    ";
}

if ($i == 0)
    echo "<tr><td colspan='6' align=center height=100>������ �����ϴ�.</td></tr>";

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

<?
include_once ("./admin.tail.php");
?>
