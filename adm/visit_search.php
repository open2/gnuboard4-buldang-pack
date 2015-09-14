<?php 
$sub_menu = "200810";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$search_sort = $_GET['search_sort'];

$g4['title'] = "�����ڰ˻�";
include_once("./admin.head.php");

include_once("$g4[path]/lib/visit.lib.php");

$qstr = "search_word=$search_word&search_sort=$search_sort"; //����¡ ó������ ����

$listall = "<a href='{$_SERVER['PHP_SELF']}' class=tt>ó��</a>"; //������ ó������ (�ʱ�ȭ�뵵)
?>

<form name="fvisit" method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?>
</div>
<div class="pull-right">
    <select name="search_sort" id="sch_sort" class="form-control">
        <?php 
        //echo '<option value="vi_ip" '.($search_sort=='vi_ip'?'selected="selected"':'').'>IP</option>'; //selected �߰�
        if($search_sort=='vi_ip'){ //select ���� �ɼʰ��� vi_ip��
            echo '<option value="vi_ip" selected="selected">IP</option>'; //selected �߰�
        }else{
            echo '<option value="vi_ip">IP</option>';
        }
        if($search_sort=='vi_referer'){ //select ���� �ɼʰ��� vi_referer��
            echo '<option value="vi_referer" selected="selected">���Ӱ��</option>'; //selected �߰�
        }else{
            echo '<option value="vi_referer">���Ӱ��</option>';
        }
        if($search_sort=='vi_date'){ //select ���� �ɼʰ��� vi_date��
            echo '<option value="vi_date" selected="selected">��¥</option>'; //selected �߰�
        }else{
            echo '<option value="vi_date">��¥</option>';
        }
        ?>
    </select>
    <input class="form-control" type=text name=stx required itemname='�˻���' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">�˻�</button>
    </div>
</div>
</form>

<table width="100%" class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width="100">
<colgroup width="100">
<colgroup width="100">
<colgroup width="80">
<colgroup width="">
<tr class="success">
    <td>IP</td>
    <td>������</td>
    <td>OS</td>
    <td>�Ͻ�</td>
    <td>���� ���</td>
</tr>
<?php 
$sql_common = " from {$g4['visit_table']} ";
if ($search_sort) {
    if($search_sort=='vi_ip' || $search_sort=='vi_date'){
        $sql_search = " where $search_sort like '$search_word%' ";
    }else{
        $sql_search = " where $search_sort like '%$search_word%' ";
    }
}
$sql = " select count(*) as cnt
         $sql_common 
         $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if ($page == "") $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

$sql = " select * 
          $sql_common
          $sql_search
          order by vi_id desc
          limit $from_record, $rows ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) {
    $row['vi_agent'] = get_text($row['vi_agent']);
    $brow = get_brow($row['vi_agent']);
    $os   = get_os($row['vi_agent']);

    $link = "";
    $referer = "";
    $title = "";
    if ($row['vi_referer']) {

        $referer = get_text(cut_str($row[vi_referer], 80, ""));
        $referer = urldecode($referer);

        if (strtolower($g4['charset']) == 'utf-8') {
            if (!is_utf8($referer)) {
                $referer = iconv('euc-kr', 'utf-8', $referer);
            }
        }
        else {
            if (is_utf8($referer)) {
                $referer = iconv('utf-8', 'euc-kr', $referer);
            }
        }

        $title = str_replace(array("<", ">"), array("&lt;", "&gt;"), $referer);
        $link = "<a href='$row[vi_referer]' target=_blank title='$title '>";
    }

    if ($is_admin == 'super')
        $ip = $row['vi_ip'];
    else
        $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.��.\\3.\\4", $row['vi_ip']);

    if ($brow == '��Ÿ') { $brow = "<span title='$row[vi_agent]'>$brow</span>"; }
    if ($os == '��Ÿ') { $os = "<span title='$row[vi_agent]'>$os</span>"; }

    $vi_datetime = $row[vi_date] . " " . $row[vi_time];

    echo "
    <tr class='list$list col1 ht center'>
        <td>&nbsp;<a href='{$_SERVER['PHP_SELF']}?search_sort=vi_ip&amp;search_word=$ip'>$ip</a></td>
        <td>$brow</td>
        <td>$os</td>
        <td><a href='{$_SERVER['PHP_SELF']}?search_sort=vi_date&amp;search_word={$row['vi_date']}'>" . get_datetime($vi_datetime) . "</a></td>
        <td><nobr style='display:block; overflow:hidden; width:350;'>$link$title</a></nobr></td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='5' height=100 align=center>�ڷᰡ �����ϴ�.</td></tr>"; 

echo "</table>";
?>

<!-- ������ -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<script type='text/javascript'>
$(function(){
    $("#sch_sort").change(function(){ // select #sch_sort�� �ɼ��� �ٲ�
        if($(this).val()=="vi_date"){ // �ش� value ���� vi_date�̸�
            $("#sch_word").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" }); // datepicker ����
        }else{ // �ƴ϶��
            $("#sch_word").datepicker("destroy"); // datepicker �̽���
        }
    });
    if($("#sch_sort option:selected").val()=="vi_date"){ // select #sch_sort �� �ɼ��� selected �Ȱ��� ���� vi_date���
        $("#sch_word").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" }); // datepicker ����
    }
});

function fvisit_submit(act) 
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>

<?php 
include_once("./admin.tail.php");
?>
