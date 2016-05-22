<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 휴면계정 복구후 해야하는 일을 정의하는 프로그램 입니다.

// 휴면계정 복구에 대한 사항을 쪽지로 발송합니다.
include_once("$g4[path]/memo.config.php");
$me_subject = "휴면계정에서 해제 됨을 알려드립니다";
$me_memo = $g4[time_ymd] . " 일 회원님의 계정은 휴면계정에서 해제 되었으며\n로그인 ip는 ". $remote_addr . "입니다.\n로그인 1년이 경과하면 휴면계정 처리됨을 알려드립니다.\n\n다시 이용해주심에 감사드립니다";
memo4_send($mb_id, $config[cf_admin], $me_memo, $me_subject);
?>
