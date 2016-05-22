<?php
include_once("./_common.php");
require_once("$g4[path]/lib/whatson.lib.php");

// 입력값 검사
if (empty($_GET['wo_id'])) {
    response_error("푸시 wo_id 정보가 없습니다.");
}

// 로그인 필요
if ( ! $member['mb_id']) {
    redirect("$g4[bbs_path]/login.php?url=" . urlencode($_SERVER['REQUEST_URI']));
}

// Whats on 읽음 처리
whatson_read($member['mb_id'], $_GET['wo_id']);

// 이동할 주소 가져오기
$whatson = pdo(
    "select * from {$g4['whatson_table']} where wo_id=? and mb_id=?",
    array(
        $_GET['wo_id'],
        $member['mb_id'],
    )
)->fetch();
$url     = null;
if ($whatson) {
    $url = whatson_click_url($whatson);
}

// 이동할 주소를 찾지 못한 경우, MyOn 으로 이동
if (empty($url)) {
    $url = '/bbs/myon.php';
}

redirect($url);
