<?
$sub_menu = "200100";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

if ($is_admin != "super")
    alert("ȸ�������� �ְ�����ڸ� �����մϴ�.");

$g4[title] = "�޸�ȸ�� ����";

include_once("./admin.head.php");
echo "<span id='ct'></span>";
include_once("./admin.tail.php");
flush();

echo "<script>document.getElementById('ct').innerHTML += '<p>�޸�ȸ�� ������...';</script>\n";
flush();

$login_time = "365"; //���� ���� ���� �������� ���� ȸ���� ���������� ����?
$today_login_time = date("Y-m-d H:i:s", $g4['server_time'] - ($login_time * 86400));

// $login_time�� ������ �α����� ȸ�� ���. �� �ֱ� $login_time�Ͼȿ� �α����� ����� ���ٴ� ���̴�.
$sql = " select * from $g4[member_table] where mb_today_login < '$today_login_time' and mb_level > '1' order by mb_today_login desc ";
$result = sql_query($sql);

$j = 0;
for ($i=0; $row=sql_fetch_array($result); $i++) { 

    // mb_unlogin�� ���� �������� update
    $sql = " update $g4[member_table] set mb_unlogin = '$g4[time_ymdhis]' where mb_id = '$row[mb_id]' ";
    sql_query($sql);

    // unlogin ���̺�� �����͸� ����
    $sql = " replace $g4[unlogin_table] select * from $g4[member_table] where mb_id = '$row[mb_id]' ";
    sql_query($sql);

    // member_table�� reset
    $sql = " update $g4[member_table]
              set 
                  mb_password = md5('" . $row[mb_password] . "'),
                  mb_name = '',
                  mb_nick = '',
                  mb_email = '',
                  mb_password_q = '',
                  mb_password_a = '',
                  mb_sex = '',
                  mb_birth = '',
                  mb_tel = '',
                  mb_hp = '',
                  mb_zip1 = '',
                  mb_zip2 = '',
                  mb_addr1 = '',
                  mb_addr2 = '',
                  mb_signature = '',
                  mb_login_ip = '',
                  mb_profile = ''
          where mb_id = '$row[mb_id]' 
    ";
    sql_query($sql);

} // end for
?>
</table>

<br><br>

<?
echo "<script>document.getElementById('ct').innerHTML += '<p>�� ".$i."���� ȸ���� ���� �Ǿ����ϴ�.';</script>\n";
?>

