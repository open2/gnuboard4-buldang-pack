<?
include_once("./_common.php");

$g4['title'] = "";
include_once("./_head.php");
?>
<?
include_once("./lib/tree.lib.php");

$tree_table = "g4_menu";
$tree = get_tree_items($tree_table);

//echo make_tree($tree, 0, 0, -1);
?>
<?
switch ($mnb) {
    case ''     : include_once("$g4[path]/index.main.php"); break;  // $mnb==""은 메인일때라는거죠.
    default     : include_once("$g4[path]/index.mnb.php"); break;
}

include_once("./_tail.php");
?>