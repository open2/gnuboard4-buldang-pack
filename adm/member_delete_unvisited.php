<?
$sub_menu = "200100";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

if ($is_admin != "super")
    alert("ȸ�������� �ְ�����ڸ� �����մϴ�.");

$g4[title] = "��������ȸ�� ����";

include_once("./admin.head.php");
echo "<span id='ct'></span>";
include_once("./admin.tail.php");
flush();

echo "<script>document.getElementById('ct').innerHTML += '<p>��������ȸ�� ������...';</script>\n";
flush();

// ȸ�� ���� �Լ� ��Ŭ���.
include_once("$g4[admin_path]/admin.lib.php");

// ���� ����
if ($w == 'd' && $mb_id) {

    // ������
    $mb = get_member($mb_id);

    // üũ
    if (!$mb['mb_id']) {

        alert("ȸ�� �����Ͱ� �������� �ʽ��ϴ�.");

    }

    // ȸ������
    member_delete($mb_id);

    // �̵�
    goto_url("./member_delete.php");

}

$login_time = 365 * 5; //���� ���� ���� �������� ���� ȸ���� ���������� ����?
$today_login_time = date("Y-m-d H:i:s", $g4['server_time'] - ($login_time * 86400));

// $login_time�� ������ �α����� ȸ�� ���. �� �ֱ� $login_time�Ͼȿ� �α����� ����� ���ٴ� ���̴�.
$sql = " select * from $g4[unlogin_table] where mb_today_login < '$today_login_time' and mb_level > '1' order by mb_today_login desc ";
$result = sql_query($sql);

$j = 0;
for ($i=0; $row=sql_fetch_array($result); $i++) { 

        // ȸ������
        member_delete($row['mb_id']);

    } // end if

} // end for
?>
</table>

<br><br>

<?
echo "<script>document.getElementById('ct').innerHTML += '<p>�� ".$j."���� ȸ���� ���� �Ǿ����ϴ�.';</script>\n";
?>

