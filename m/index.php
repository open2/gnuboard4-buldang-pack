<?php
include_once("./_common.php");

// 앱에서 실행된 경우 쿠키로 구분
if ((isset($_GET['in-app']) && $_GET['in-app'])) {
    setcookie("in-app", $_GET['in-app'], time() + 86400 * 3650, '/', $g4['cookie_domain']);
}

redirect('/');
