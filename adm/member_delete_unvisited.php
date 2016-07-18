<?
$sub_menu = "200100";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

if ($is_admin != "super")
    alert("회원정리는 최고관리자만 가능합니다.");

$g4[title] = "장기미접속회원 정리";

include_once("./admin.head.php");
echo "<span id='ct'></span>";
include_once("./admin.tail.php");
flush();

echo "<script>document.getElementById('ct').innerHTML += '<p>장기미접속회원 정리중...';</script>\n";
flush();

// 회원 삭제 함수 인클루드.
include_once("$g4[admin_path]/admin.lib.php");

// 개별 삭제
if ($w == 'd' && $mb_id) {

    // 데이터
    $mb = get_member($mb_id);

    // 체크
    if (!$mb['mb_id']) {

        alert("회원 데이터가 존재하지 않습니다.");

    }

    // 회원삭제
    member_delete($mb_id);

    // 이동
    goto_url("./member_delete.php");

}

$login_time = 365 * 5; //지난 몇일 동안 접속하지 않은 회원을 삭제할지를 결정?
$today_login_time = date("Y-m-d H:i:s", $g4['server_time'] - ($login_time * 86400));

// $login_time일 이전에 로그인한 회원 출력. 즉 최근 $login_time일안에 로그인한 사람이 없다는 것이다.
$sql = " select * from $g4[unlogin_table] where mb_today_login < '$today_login_time' and mb_level > '1' order by mb_today_login desc ";
$result = sql_query($sql);

$j = 0;
for ($i=0; $row=sql_fetch_array($result); $i++) { 

        // 회원삭제
        member_delete($row['mb_id']);

        // 1,000개 단위로 삭제
        if ($i > 1000)
            break;

    } // end if

} // end for
?>
</table>

<br><br>

<?
echo "<script>document.getElementById('ct').innerHTML += '<p>총 ".$j."명의 회원이 정리 되었습니다.';</script>\n";
?>

