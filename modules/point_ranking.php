<? 
include_once("./_common.php"); 

define("_CO_POINT_RANK_", TRUE); 

$html_title = "����Ʈ����"; 
$g4[title] = "" . $html_title; 

include_once("./_head.php"); 
?> 

<?
if (!$is_member)
    echo "<script>
          alert('ȸ���� ��ȸ�ϽǼ� �ֽ��ϴ�.');
    location.href='/bbs/login.php?wr_id=&url=../point_ranking.php';
    </script>";
?>

<table width=710 align=center> 
<tr><td valign=top colspan=2 class=lh> 
<? 

$sql = " select count(mb_id) as cnt from $g4[member_table] 
           where mb_id not in ('admin') and mb_point >= '$member[mb_point]' order by mb_point, mb_open_date desc "; // ������ ���̵�� ����Ʈ �������� ����
$row = sql_fetch($sql); 
echo "<br>&middot; ���� <b>$member[mb_nick]</b> ���� ����Ʈ ������ <B>".number_format($row[cnt])."��</B> �Դϴ� (������ ���� ���� �������� ���� ����� ������ �� �����ϴ�)."; 

$sql = " select sum(mb_point) as sum_point from $g4[member_table]"; 
$row = sql_fetch($sql); 
$sum_point = $row[sum_point]; 
echo "<br>&middot; ��ü ����Ʈ : <B>".number_format($sum_point) . "��</b><br><br>"; 

echo "</td></tr><tr><td valign=top width=50%>"; 

$keyword_yi="";
if($_GET[keyword]!="") {
    if($_GET[type]==0) 
        $keyword_yi=" and mb_nick like '%$_GET[keyword]%' ";
    if($_GET[type]==1)
        $keyword_yi=" and mb_name like '%$_GET[keyword]%' ";
    if($_GET[type]==2)
        $keyword_yi=" and mb_id='$_GET[keyword]'";
}

$sql = " select count(*) as cnt from $g4[member_table] 
         where mb_id not in ('admin') $keyword_yi "; 
$row = sql_fetch($sql); 
$total_count = $row[cnt]; 

$rows = 50; 
$total_page  = ceil($total_count / $rows);  // ��ü ������ ��� 
if ($page == "") { $page = 1; } // �������� ������ ù ������ (1 ������) 
$from_record = ($page - 1) * $rows; // ���� ���� ���� 

$mod = 25; 

$sql = " select * from $g4[member_table] 
          where mb_id not in ('admin') $keyword_yi 
          order by mb_point desc, mb_today_login desc 
          limit $from_record, $rows "; // ������ ���̵�� ����Ʈ �������� ����
$result = sql_query($sql); 
for ($i=0; $row=sql_fetch_array($result); $i++) 
{ 
    if ($i && $i%$mod==0) 
        echo "</td><td valign=top>"; 

    if($_GET[keyword]!="") {
        $sql = " select count(mb_id) as cnt from $g4[member_table] 
                   where mb_id not in ('admin') and mb_point >= '$row[mb_point]' order by mb_point desc "; // ������ ���̵�� ����Ʈ �������� ����
        $row2 = sql_fetch($sql); 
        $rank = $row2[cnt];
    } else {
        $rank = (($page - 1) * $rows) + $i + 1; 
    }
    
    $name = get_sideview($row[mb_id], get_text($row[mb_nick]), $row[mb_email], $row[mb_homepage]); 
    $point = number_format($row[mb_point]); 
    echo "<table width=98% cellpadding=0 align=center class=box border=0><tr>"; 
    echo "<td height=30>$rank. $name</td>"; 
    echo "<td align=right><font color=777777>{$point}��</font></td>"; 
    echo "</tr></table>"; 
    echo "<table width=100%><tr><td></td></tr></table>"; 
} 
?> 
</td></tr> 
</table> 

<form id="form1" name="form1" method="get" action="">
<? 
$qstr = "type=$_GET[type]&keyword=$_GET[keyword]";
$page = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page="); 
echo "<p><table width=100% cellpadding=0 cellspacing=0><tr><td width=50% align=center>$page</td>";
?>
<td align=left valign="middle">
         <select name="type" id="type">
           <option value="0" <?php if($_GET[type]==0){ echo "selected";}?>>�г���</option>
           <option value="1" <?php if($_GET[type]==1){ echo "selected";}?>>�̸�</option>
           <option value="2" <?php if($_GET[type]==2){ echo "selected";}?>>���̵�</option>
         </select>
         <input name="keyword" type="text" id="keyword" size="15" value="<?=$_GET[keyword]?>"/>
         <input type=image src="<?=$g4['path']?>/modules/img/search_btn.gif" border=0 align=absmiddle></td>
</td>
</tr></table><br><br>
</form>


<?
include_once("./_tail.php"); 
?> 
