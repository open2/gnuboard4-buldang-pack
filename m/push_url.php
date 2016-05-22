<?php
include_once("./_common.php");
require_once("$g4[path]/lib/whatson.lib.php");

// �Է°� �˻�
if (empty($_GET['wo_id'])) {
    response_error("Ǫ�� wo_id ������ �����ϴ�.");
}

// �α��� �ʿ�
if ( ! $member['mb_id']) {
    redirect("$g4[bbs_path]/login.php?url=" . urlencode($_SERVER['REQUEST_URI']));
}

// Whats on ���� ó��
whatson_read($member['mb_id'], $_GET['wo_id']);

// �̵��� �ּ� ��������
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

// �̵��� �ּҸ� ã�� ���� ���, MyOn ���� �̵�
if (empty($url)) {
    $url = '/bbs/myon.php';
}

redirect($url);
