<?
include_once("./_common.php");

if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

include_once("$g4[path]/_head.php");

// �⼮�ð� ����
?>

<table width="100%" cellspacing="0" cellpadding="0" align="center" valign="top">
  <tr>
    <td align=center>
    <br>
    00:00:00���� �⼮������ 30���� ��� �մϴ�(�ð��� ���� ���� ����Ʈ�� ���� ���� �켱�˴ϴ�).<br>
    ���� ���� �⼮�� �п��Դ� �ƹ��� ����(?)�̳� ������ �����ϴ�.
    <br>
    <br>
    </td>
  </tr>
	<tr>
		<td width="100%">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" valign="top">
				<tr><td colspan="9" height="3" bgcolor="#F2F2F2"></td></tr>
				<tr><td colspan="9" height="1" bgcolor="#D9D9D9"></td></tr>
				<tr height="30">
					<td width="100" align="center"><span class="at_rk_s">����</span></td>
					<td width="1"></td>
					<td width="150" align="center"><span class="at_rk_s">�⼮�ð�</span></td>
					<td width="1"></td>
					<td width="100" align="center"><span class="at_rk_s">���ӻ���</span></td>
					<td width="1"></td>
					<td align="center"><span class="at_rk_s">�г���</span></td>
					<td width="1"></td>
					<td width="100" align="center" style="padding:0 0 0 5px;"><span class="at_rk_s">����Ʈ</span></td>
				</tr>
				<tr><td colspan="9" height="1" bgcolor="#D9D9D9"></td></tr>
				<tr><td colspan="9" height="3" bgcolor="#F2F2F2"></td></tr>
<?
// ���۽ð�
$str_today_time = date("Y-m-d 00:00:00");

// ȸ�����̺� ����
$sql_select = " mb_id, mb_nick, mb_email, mb_homepage, mb_today_login, mb_point ";
$sql = " select $sql_select from $g4[member_table] where mb_today_login >= '$str_today_time' and mb_id != '$config[cf_admin]' order by mb_today_login asc, mb_point asc limit 30";
$result = sql_query($sql);
for ($i=0; $row = sql_fetch_array($result); $i++) {

// ���������̺� ����
$sql2 = " select mb_id from $g4[login_table] where mb_id = '$row[mb_id]' ";
$row2 = sql_fetch($sql2);

// ���ӻ���
if ($row2['mb_id']) {
$on = "������";
} else {
$on = "";
}

// �г���
$name = get_sideview($row[mb_id], get_text($row[mb_nick]), $row[mb_email], $row[mb_homepage]);

// ��ŷ
$rank = $i + 1;

// ����
if ($member['mb_id'] == $row['mb_id']) {
$list = "2";
} else {
$list = ($i%2);
}
?>

				<tr class='list<?=$list?>' height="25" onMouseOver='this.style.backgroundColor="#ffe9e9"' onMouseOut='this.style.backgroundColor=""'>
					<td width="100" align="center"><span class="at_rk_n"><?=$rank?></span></td>
					<td width="1"></td>
					<td width="150" align="center"><span class="at_rk_n"><?=substr($row['mb_today_login'],10,16);?></span></a></td>
					<td width="1"></td>
					<td width="100" align="center"><span class="at_rk_n"><?=$on?></span></td>
					<td width="1"></td>
					<td align="center"><span class="at_rk_n"><?=$name?></span></td>
					<td width="1"></td>
					<td width="100" align="right" style="padding:0 5 0 0px;"><span class="at_rk_n"><?=number_format($row['mb_point']);?> ��</span></td>
				</tr>
				<tr><td bgcolor="#EEEEEE" colspan="9"></td></tr>
<? } ?>
				<tr><td colspan="9" height="3" bgcolor="#FAFAFA"></td></tr>
			</table></td>
	</tr>
</table>
