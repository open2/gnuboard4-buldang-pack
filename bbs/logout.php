<?
include_once("./_common.php");

// ��ȣ��� ���� �ڵ�
session_unset(); // ��� ���Ǻ����� �������� ������ 
session_destroy(); // ���������� 

// �ڵ��α��� ���� --------------------------------
$ck_mb_id = get_cookie("ck_mb_id");
$sql = " delete from $g4[cookie_table] where cookie_name='$ck_mb_id' ";
sql_query($sql);

set_cookie('ck_mb_id', "", 0);
set_cookie('ck_auto', '', 0);

// �ڵ��α��� ���� end --------------------------------

if ($url) {
    $p = parse_url($url);
    if ($p['scheme'] || $p['host']) {
        alert("url�� �������� ������ �� �����ϴ�.");
    }

    $link = $url;
} else if ($bo_table) {
    $link = "$g4[bbs_path]/board.php?bo_table=$bo_table";
} else {
    $link = $g4[path];
}

goto_url($link);
?>
