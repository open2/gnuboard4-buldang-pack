<?
$g4[title] = "회원탈퇴";
include_once("./_common.php");

// 변수들을 setting 합니다
$mb_id        = strip_tags($_POST[mb_id]);
$mb_password  = strip_tags($_POST[mb_password]);

if (chk_recaptcha() == false)
    alert ('스팸차단코드가 틀렸습니다.');

if (!$member['mb_id'])
    alert('회원만 접근하실 수 있습니다.');

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

if ($mb_id !== $member['mb_id'])
    alert("정상적인 접근이 아닌것 같습니다.");

if (!$mb_password)
    alert("정상적인 접근이 아닌것 같습니다 (2).");

if (sql_password($mb_password) !== $member['mb_password'])
    alert("$mb_password 비밀번호가 틀리거나 정상적인 접근이 아닌것 같습니다.");

// 회원탈퇴일을 저장
$date = date("Ymd");
$sql = " update $g4[member_table] set mb_leave_date = '$date' where mb_id = '$member[mb_id]'";
sql_query($sql);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

if (!$url) 
    $url = $g4[path]; 

alert("{$member[mb_nick]}님께서는 " . date("Y년 m월 d일") . "에 회원에서 탈퇴 하셨습니다.", $url);
?>
