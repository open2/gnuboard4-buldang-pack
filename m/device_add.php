<?php
include_once("./_common.php");
require_once(G4_PHP79_PATH . "/lib/devices.php");

// �Է°� �˻�
if (empty($_POST['uuid'])) {
    response_error("�ܸ����� UUID ������ �����ϴ�.");
}
if (empty($_POST['serial'])) {
    response_error("�ܸ����� serial ������ �����ϴ�.");
}

// ����̽� ���/����
device_save($_POST, $member);

// ����̽� ���а��� ��Ű�� ���� ����
setcookie("device-uuid", $_POST['uuid'], time() + 86400 * 3650, '/', $g4['cookie_domain']);
setcookie("device-serial", $_POST['serial'], time() + 86400 * 3650, '/', $g4['cookie_domain']);

echo response_json(array(
    'success' => true,
));
