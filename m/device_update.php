<?php
include_once("./_common.php");
require_once(G4_PHP79_PATH . "/lib/devices.php");

if ($member[mb_id]) {
    ;
} else {
    alert("�˸� ������ ȸ���� ���� ���� �Դϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.",
        "$g4[bbs_path]/login.php?url=" . urlencode($_SERVER['REQUEST_URI']));
}

if (empty($_COOKIE['device-uuid'])) {
    alert("�˸� ������ �ۿ����� �����մϴ�.");
}

$device = device_info($_COOKIE['device-uuid'], $member);

if (empty($device)) {
    alert("��ϵ��� ���� ����Դϴ�.  ���� ������ �ٽ� �����Ͽ� ���ʽÿ�.");
}

device_push_update($_POST, $device);

alert('Ǫ�� ������ ����Ǿ����ϴ�', '/m/device.php');

