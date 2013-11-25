<?
include_once("./_common.php");

include_once("$g4[path]/head.sub.php");

if (!$member[mb_id]) 
{
    $href = "./login.php?$qstr&url=".urlencode("./board.php?bo_table=$bo_table&wr_id=$wr_id");
    echo "<script language='JavaScript'> alert('회원만 접근 가능합니다.'); top.location.href = '$href'; </script>";
    exit;
}

$sql = " select count(*) as cnt from $g4[scrap_table]
          where mb_id = '$member[mb_id]'
            and bo_table = '$bo_table'
            and wr_id = '$wr_id' ";
$row = sql_fetch($sql);
if ($row[cnt]) 
{
    echo "
    <script language='JavaScript'> 
    if (confirm('이미 스크랩하신 글 입니다.\\n\\n지금 스크랩을 확인하시겠습니까?'))
        document.location.href = './scrap.php';
    else
        window.close();
    </script>";
    exit;
}

$sql = " insert into $g4[scrap_table] ( mb_id, bo_table, wr_id, ms_datetime, ms_memo, wr_mb_id, wr_subject )
         values ( '$member[mb_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$wr_content', '$wr_mb_id', '$wr_subject' ) ";
sql_query($sql);

// 불당팩 - 스크랩수에 따라서 베스트글 등록
if ($board['bo_list_scrap'] > 0) {
    $sql = " select count(*) as cnt from $g4[scrap_table] where bo_table='$bo_table' and wr_id='$wr_id' and mb_id = '$member[mb_id]' ";
    $scrap_good = sql_fetch($sql);
    if ($scrap_good['cnt'] >= $board['bo_list_scrap']) {
        // UPDATE를 먼저하고 오류가 발생시 insert를 실행
        $sql = " update $g4[good_list_table] set good = good + 1 where bo_table='$bo_table' and wr_id='$wr_id' ";
        $result = sql_query($sql, FALSE);
        if ( mysql_affected_rows() == 0 ) {
            $sql = " insert $g4[good_list_table] ( mb_id, gr_id, bo_table, wr_id, gl_datetime, good, wr_datetime) values ( '$write[mb_id]', '$board[gr_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$list_good[cnt]', '$write[wr_datetime]' ) ";
            $result = sql_query($sql);
        }
    }
}

echo <<<HEREDOC
<script language="JavaScript">
    if (confirm("이 글을 스크랩 하였습니다.\\n\\n지금 스크랩을 확인하시겠습니까?")) 
        document.location.href = "./scrap.php";
    else
        window.close();
</script>
HEREDOC;
?>
