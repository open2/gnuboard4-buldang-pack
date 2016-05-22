<?
include_once("_common.php");

$w = preg_match("/^[a-zA-Z0-9_]+$/", $w) ? $w : "";
$wo_in = (int) $wo_id;
$mb_id = $member[mb_id];
$head = (int) $head;
$page = (int) $page;
$rows = (int) $rows;
$check = (int) $check;
$url_prev = strip_tags($url_prev);

switch($w) {
  case 'd' :
      $sql = " delete from $g4[whatson_table] where wo_id = '$wo_id' and mb_id = '$mb_id' ";
      sql_query($sql);

      $url = "$g4[bbs_path]/whatson.php?head=$head&page=$page&check=$check&rows=$rows";
      if ($url_prev)
          $url = $url_prev . $url;

      goto_url("$url");
      exit;
  case 'r' :
      include_once("$g4[path]/lib/whatson.lib.php");
      whatson_read($mb_id, $wo_id);

      echo "000";
  default :
}
?>
