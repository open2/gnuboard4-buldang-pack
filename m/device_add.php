<?php
include_once("./_common.php");
require_once(G4_PHP79_PATH . "/lib/devices.php");

// 입력값 검사
if (empty($_POST['uuid'])) {
    response_error("단말기의 UUID 정보가 없습니다.");
}
if (empty($_POST['serial'])) {
    response_error("단말기의 serial 정보가 없습니다.");
}

// 디바이스 등록/갱신
device_save($_POST, $member);

// 디바이스 구분값을 쿠키에 영구 저장
setcookie("device-uuid", $_POST['uuid'], time() + 86400 * 3650, '/', $g4['cookie_domain']);
setcookie("device-serial", $_POST['serial'], time() + 86400 * 3650, '/', $g4['cookie_domain']);

echo response_json(array(
    'success' => true,
));
