<?
$sub_menu = "100600";
include_once("./_common.php");

check_demo();

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.", $g4[path]);

$g4[title] = "업그레이드";
if (!$g4[b4_upgrade]) include_once("./admin.head.php");

$sql = " ALTER TABLE `$g4[board_table]` ADD `bo_related` TINYINT( 4 ) NOT NULL  ";
sql_query($sql, false);

$sql = " select * from $g4[board_table] ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) {
    $tmp_write_table = $g4[write_prefix] . $row[bo_table];

    $sql4 = " ALTER TABLE $tmp_write_table ADD `wr_related` VARCHAR( 255 ) NOT NULL ";
    sql_query($sql4, false);

    echo  "<BR>" . $i . " : " . $row[bo_table] . " 게시판에 wr_related 필드를 추가 했습니다 <br>";
}

echo "<br>wr_lated UPGRADE 완료.";

if (!$g4[b4_upgrade]) include_once("./admin.tail.php");
?>
