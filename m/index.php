<?php
include_once("./_common.php");

// �ۿ��� ����� ��� ��Ű�� ����
if ((isset($_GET['in-app']) && $_GET['in-app'])) {
    setcookie("in-app", $_GET['in-app'], time() + 86400 * 3650, '/', $g4['cookie_domain']);
}

redirect('/');