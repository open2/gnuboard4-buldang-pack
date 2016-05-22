<?php
include_once("./_common.php");
require_once(G4_PHP79_PATH . "/lib/devices.php");

if ($member[mb_id]) {
    ;
} else {
    alert("알림 설정은 회원을 위한 서비스 입니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.",
        "$g4[bbs_path]/login.php?url=" . urlencode($_SERVER['REQUEST_URI']));
}

if (empty($_COOKIE['device-uuid'])) {
    alert("알림 설정은 앱에서만 가능합니다.");
}

$device = device_info($_COOKIE['device-uuid'], $member);

if (empty($device)) {
    alert("등록되지 않은 기기입니다.  앱을 종료후 다시 실행하여 보십시오.");
}

device_push_update($_POST, $device);

alert('푸시 설정이 변경되었습니다', '/m/device.php');

