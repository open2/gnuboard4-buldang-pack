<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Խ��� ������ �ϴ� ����
if (strip_tags($board[bo_content_tail]) == "" || $board[bo_content_tail] == "<p>&nbsp;</p>")
    ;
else
    echo stripslashes($board[bo_content_tail]); 

// �Խ��� ������ �ϴ� �̹��� ���
if ($board[bo_image_tail]) 
    echo "<img src='$g4[data_path]/file/$bo_table/$board[bo_image_tail]' border='0'>";

// �Խ��� ������ �ϴ� ���� ���
if ($board[bo_include_tail]) 
    @include ($board[bo_include_tail]); 
?>
