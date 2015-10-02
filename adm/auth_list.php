<?
$sub_menu = "100110";
include_once("./_common.php");

if ($is_admin != "super")
    alert("�ְ�����ڸ� ���� �����մϴ�.");

$token = get_token();

// PDO bindParam ���� �־�δ� ���� �ʱ�ȭ
$params = array();

$sql_common = " from $g4[auth_table] a left join $g4[member_table] b on (a.mb_id=b.mb_id) ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default : 
            $sql_search .= " ($sfl like :stx) ";
            $params = array(":stx", "%".$stx."%");
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.mb_id, au_menu";
    $sod = "";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search 
         $sql_order ";
//$row = sql_fetch($sql);
//$total_count = $row[cnt];
$stmt = $pdo_db->prepare($sql);
$row = pdo_fetch_params($stmt, $params);
$total_count = $row['cnt'];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if ($page == "") $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
//$result = sql_query($sql);
$stmt = $pdo_db->prepare($sql);
$result = pdo_query_params($stmt, $params);  

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>ó��</a>";

$g4[title] = "�������Ѽ���";
include_once("./admin.head.php");
?>

<script type="text/javascript">
var list_delete_php = "auth_list_delete.php";
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (�Ǽ� : <?=number_format($total_count)?>)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='a.mb_id'>ȸ�����̵�</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='�˻���' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">�˻�</button>
    </div>
</div>
</form>


<form name=fauthlist method=post role="form" class="form-inline">
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-hover" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=120>
<colgroup width=150>
<colgroup width=''>
<colgroup width=100>
<tr class='success'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td><?=subject_sort_link('a.mb_id')?>ȸ�����̵�</a></td>
    <td><?=subject_sort_link('mb_nick')?>����</a></td>
  	<td>�޴�</td>
	  <td>����</td>
</tr>
<?
//for ($i=0; $row=sql_fetch_array($result); $i++)
for ($i=0; $row=$stmt->fetch(PDO::FETCH_ASSOC); $i++)
{
    $mb_nick = get_sideview($row[mb_id], $row[mb_nick], $row[mb_email], $row[mb_homepage]);

    // �޴���ȣ�� �ٲ�� ��쿡 ���� ���� ����� �޴��� ������
    if (!isset($auth_menu[$row[au_menu]]))
    {
        //sql_query(" delete from $g4[auth_table] where au_menu = '$row[au_menu]' ");
        $stmt = $pdo_db->prepare(" delete from $g4[auth_table] where au_menu = :au_menu ");
        $stmt->bindParam(":au_menu", $row[au_menu]);
        $result = pdo_query($stmt);
        continue;
    }

    $list = $i%2;
    echo "
    <input type=hidden name=mb_id[$i] value='$row[mb_id]'>
    <input type=hidden name=au_menu[$i] value='$row[au_menu]'>
    <tr class='list$list col1 ht center'>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td><a href='?sfl=a.mb_id&stx=$row[mb_id]'>$row[mb_id]</a></td>
        <td>$mb_nick</td>
        <td>[$row[au_menu]] {$auth_menu[$row[au_menu]]}</td>
        <td>$row[au_auth]</td>
    </tr>";
}

if ($i==0) 
    echo "<tr><td colspan='5' height=100 align=center>�ڷᰡ �����ϴ�.</td></tr>";

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
    echo "<script type='text/javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
?>
</form>

<script type="text/javascript">
    document.fsearch.stx.focus();
</script>

<BR>

<form name=fauthlist2 method=post onsubmit="return fauthlist2_submit(this);" autocomplete="off">
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=150>
<colgroup width=''>
<colgroup width=150>
<colgroup width=100>
<tr class="success">
    <td>ȸ�����̵�</span></td>
    <td>���ٰ��ɸ޴�</span></td>
    <td>����</span></td>
    <td>�Է�</span></td>
</tr>
<tr>
    <td><input type=text class=ed name=mb_id required itemname='ȸ�����̵�' value='<?=$mb_id?>'></td>
    <td>
        <select name=au_menu required itemname='���ٰ��ɸ޴�'>
        <option value=''>-- �����ϼ���
        <?
        foreach($auth_menu as $key=>$value)
        {
            if (!(substr($key, -3) == "000" || $key == "-" || !$key))
                echo "<option value='$key'>[$key] $value";
        }
        ?>
        </select>
    </td>
    <td>
        <table width=210 align=center>
        <tr align=center>
        	<td width=33%><input type=checkbox name='r' value='r' checked></td>
        	<td width=33%><input type=checkbox name='w' value='w'></td>
        	<td width=33%><input type=checkbox name='d' value='d'></td>
        </tr>
        <tr align=center>
        	<td>r<br>(�б�)</td>
        	<td>w<br>(�Է�,����)</td>
        	<td>d<br>(����)</td>
        </tr>
        </table></td>
    <td><input type=submit class="btn btn-default" value='  Ȯ  ��  '></td>
</tr>
</table>

</form>

<script type="text/javascript">
function fauthlist2_submit(f)
{
    f.action = "./auth_update.php";
    return true;
}
</script>

<?
include_once ("./admin.tail.php");
?>
