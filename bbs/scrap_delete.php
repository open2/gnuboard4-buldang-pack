<?
include_once("./_common.php");

if (!$member[mb_id]) 
    alert("회원만 이용하실 수 있습니다.");

$sql = " delete from $g4[scrap_table] where mb_id = '$member[mb_id]' and ms_id = '$ms_id' ";
sql_query($sql);

// 불당팩 - 스크랩수에 따라서 베스트글 등록된 경우... 스크랩이 지워지면 good count도 하나 빼준다
if ($board['bo_list_scrap'] > 0) {
    $sql = " update $g4[good_list_table] set good = good - 1 where bo_table='$bo_table' and wr_id='$wr_id' ";
    $result = sql_query($sql, FALSE);
}

goto_url("./scrap.php?page=$page&head_on=$head_on&mnb=$mnb&snb=$snb");
?>
