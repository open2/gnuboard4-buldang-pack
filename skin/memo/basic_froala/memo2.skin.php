<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// head - ���� �޴�
include_once(g4_path($g4[memo_skin_path]) . "/memo2.head.skin.php");

// ���ο� ��µ� ������� �ִ� ��----
if ($class == "view") {
    // ���� ����
include_once(g4_path($g4[memo_skin_path]) . "/memo2.head.skin.php");
} else { 
    // ���� ���Ⱑ �ƴѰ��
    switch ($kind) {
      case 'write' : 
            include_once("$g4[memo_skin_path]/memo2_write.skin.php"); 
            break;
      case 'online' :
            include_once("$g4[memo_skin_path]/memo2_online.skin.php"); 
            break;        
      case 'memo_group' :
            include_once("$g4[memo_skin_path]/memo2_group_member.skin.php"); 
            break;
      case 'memo_group_admin' :
            include_once("$g4[memo_skin_path]/memo2_group_admin.skin.php"); 
            break;
      case 'memo_address_book' :
            include_once("$g4[memo_skin_path]/memo2_memo_address_book.skin.php"); 
            break;
      case 'memo_config' :
            include_once("$g4[memo_skin_path]/memo2_config.skin.php"); 
            break;
      default :
            include_once("$g4[memo_skin_path]/memo2_list.skin.php"); 
    }
} 
// ���ο� ��µ� ������� ��----

// tail - �ϴܺ� ����
include_once("$g4[memo_skin_path]/memo2.tail.skin.php"); 
?>