<?
$sub_menu = "200100";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

if ($is_admin != "super")
    alert("회원정리는 최고관리자만 가능합니다.");

$g4[title] = "휴면회원 정리";

include_once("./admin.head.php");
echo "<span id='ct'></span>";
include_once("./admin.tail.php");
flush();

echo "<script>document.getElementById('ct').innerHTML += '<p>휴면회원 정리중...';</script>\n";
flush();

$login_time = "365"; //지난 몇일 동안 접속하지 않은 회원을 삭제할지를 결정?
$today_login_time = date("Y-m-d H:i:s", $g4['server_time'] - ($login_time * 86400));

// $login_time일 이전에 로그인한 회원 출력. 즉 최근 $login_time일안에 로그인한 사람이 없다는 것이다.
$sql = " select * from $g4[member_table] where mb_today_login < '$today_login_time' and mb_level > '1' order by mb_today_login desc ";
$result = sql_query($sql);

$j = 0;
for ($i=0; $row=sql_fetch_array($result); $i++) { 

    // mb_unlogin을 현재 시점으로 update
    $sql = " update $g4[member_table] set mb_unlogin = '$g4[time_ymdhis]' where mb_id = '$row[mb_id]' ";
    sql_query($sql);

    // unlogin 테이블로 데이터를 복사
    $sql = " replace $g4[unlogin_table] select * from $g4[member_table] where mb_id = '$row[mb_id]' ";
    sql_query($sql);

    // member_table을 reset
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
echo "<script>document.getElementById('ct').innerHTML += '<p>총 ".$i."명의 회원이 정리 되었습니다.';</script>\n";
?>

