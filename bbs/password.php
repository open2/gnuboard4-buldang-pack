<?
include_once("./_common.php");

if ($w == "u")
    $action = "./write.php";
else if ($w == "d")
    $action = "./delete.php";
else if ($w == "x")
    $action = "./delete_comment.php";
else if ($w == "p")
    $action = "./poll_etc_update.php";
else if ($w == "s")
{
    // �н����� â���� �α��� �ϴ� ��� ������ �Ǵ� �ڽ��� ���̸� �ٷ� �ۺ���� ��
    if ($is_admin || ($member[mb_id] == $write[mb_id] && $write[mb_id]))
        goto_url("./board.php?bo_table=$bo_table&wr_id=$wr_id");
    else
        $action = "./password_check.php";
}
else
    alert("w ���� ����� �Ѿ���� �ʾҽ��ϴ�.");

$g4[title] = "�н����� �Է�";
include_once("$g4[path]/head.sub.php");

//if ($board[bo_include_head]) { @include ($board[bo_include_head]); }
//if ($board[bo_content_head]) { echo stripslashes($board[bo_content_head]); } 

$member_skin_path = "$g4[path]/skin/member/$config[cf_member_skin]";

include_once("$member_skin_path/password.skin.php");

//if ($board[bo_content_tail]) { echo stripslashes($board[bo_content_tail]); } 
//if ($board[bo_include_tail]) { @include ($board[bo_include_tail]); }

include_once("$g4[path]/tail.sub.php");
?>
