<?
if (!defined('_GNUBOARD_')) exit;

$bn_target = $list[0][bn_target];
$bg_id = $list[0][bg_id];
$bn_id = $list[0][bn_id];

$url = $g4[bbs_path] . "/banner_link.php?bg_id=$bg_id&bn_id=$bn_id";
$img = $g4[data_path] . "/banner/" . $list[0][bg_id] . "/" . $list[0][bn_image];

if ($bn_target)
    $target = " target=_blank ";
else
    $target = "";
?>
<a href='<?=$url?>' <?=$target?> ><img src='<?=$img?>' align=absmiddle></a>