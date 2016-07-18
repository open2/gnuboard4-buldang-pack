<?
$sub_menu = "100440";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "r");

$g4[title] = "php info";
include_once("./admin.head.php");

// http://php-fusion.co.uk/forum/viewthread.php?thread_id=24120
$gd_support = extension_loaded('gd');
if ($gd_support)
    $gd_info = gd_info();
else
    $gd_info = array();

function print_support($gd_support) {
    if ($gd_support)
        echo "지원";
    else 
        echo "미지원";
}
?>
<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;"> 
<colgroup width=160>
<colgroup width=''>
<tr class="success">
    <td align=left>
    <strong>GD라이브러리 정보</strong>
    </td> 
    <td></td>
</tr>
<tr> 
    <td>GD 지원여부</td>
    <td><?=print_support($gd_support)?></td>
</tr>
<? if ($gd_support) { ?>
<tr> 
    <td>GD 버젼</td>
    <td><?=print_support($gd_info['GD Version'])?></td>
</tr>
<tr> 
    <td>TTF 지원 (FreeType)</td>
    <td><?=print_support($gd_info['FreeType Support'])?></td>
</tr>
<tr> 
    <td>JPEG 지원</td>
    <td><?=print_support($gd_info['JPEG Support'])?></td>
</tr>
<tr> 
    <td>PNG 지원</td>
    <td><?=print_support($gd_info['PNG Support'])?></td>
</tr>
<tr> 
    <td>GIF 지원</td>
    <td><?=print_support($gd_info['GIF Create Support'])?></td>
</tr>
<? } ?>
</table>

<?
include_once("./admin.tail.php");
?>
