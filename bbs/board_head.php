<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Խ��� ������ ��� ���� ���
if ($board[bo_include_head]) 
    @include ($board[bo_include_head]); 

// �Խ��� ������ ��� �̹��� ���
if ($board[bo_image_head]) 
    echo "<img src='$g4[data_path]/file/$bo_table/$board[bo_image_head]' border='0'>";

// �Խ��� ������ ��� ����
if (strip_tags($board[bo_content_head]) == "" || $board[bo_content_head] == "<p>&nbsp;</p>")
    ;
else
    echo stripslashes($board[bo_content_head]); 
?>
