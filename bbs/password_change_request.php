<?
include_once("./_common.php");

// 비회원은 접근할 수 없게 함
if (!$is_member) 
    alert("부적절한 접근 입니다. 관리자에게 문의하시기 바랍니다.");

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";

include_once("./_head.php");
include_once("$member_skin_path/password_change_request.skin.php");
include_once("./_tail.php");
?>
