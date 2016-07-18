<?
include_once("./_common.php");

// 불법접근을 막도록 토큰생성
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$g4[title] = "이메일 인증";
include_once("$g4[path]/_head.php");

if ($is_member) {
    $mb_id = $member[mb_id];
} else {
    $mb_id = $_SESSION['email_mb_id'];
    // 로그인후에 이동한 것이면
    if ($mb_id) {
        ;
    } else {
        set_session('email_mb_id', "");
        alert("이메일 인증을 위해 로그인 하시기 바랍니다.", "./login.php?$qstr&url=".urlencode("$_SERVER[PHP_SELF]"));
    }
}
$member = get_member($mb_id);

// 관리자는 이메일 재인증을 못하게 합니다.
if ($is_admin)
    die;

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";
include_once("$member_skin_path/email_re_certify.skin.php");

include_once("$g4[path]/_tail.php");
?>
