<?php
include_once("./_common.php");
require_once(G4_PHP79_PATH . "/lib/devices.php");

$g4[title] = "알림 설정";

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

include_once("./head.sub.php");

include_once(g4_path("$g4[path]/skin/m/basic") . "/device.skin.php");

include_once("./tail.sub.php");
