<?
include_once("./_common.php");

include_once("$g4[path]/head.sub.php");

if (!$member[mb_id]) 
{
    $href = './login.php?'.$qstr.'&amp;url='.urlencode('./board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id);
    echo "<script type=\'text/javascript\'> alert(\'ȸ���� ���� �����մϴ�.\'); top.location.href = \''.str_replace('&amp;', '&', $href).'\'; </script>";
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
    <script type='text/javascript'>
    if (confirm('�̹� ��ũ���Ͻ� �� �Դϴ�.\\n\\n���� ��ũ���� Ȯ���Ͻðڽ��ϱ�?'))
        document.location.href = './scrap.php';
    else
        window.close();
    </script>";
    exit;
}

/*
$sql = " insert into $g4[scrap_table] ( mb_id, bo_table, wr_id, ms_datetime, ms_memo, wr_mb_id, wr_subject )
         values ( '$member[mb_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$wr_content', '$wr_mb_id', '$wr_subject' ) ";
sql_query($sql);
*/
$sql = " insert into $g4[scrap_table] ( mb_id, bo_table, wr_id, ms_datetime, ms_memo, wr_mb_id, wr_subject )
         values ( '$member[mb_id]', :bo_table, '$wr_id', '$g4[time_ymdhis]', :wr_content, '$wr_mb_id', :wr_subject ) ";

$stmt = $pdo_db->prepare($sql);
$stmt->bindParam(":bo_table", $bo_table);
$stmt->bindParam(":wr_content", $wr_content);
$stmt->bindParam(":wr_subject", $wr_subject);
$result = pdo_query($stmt, false);

// �Ҵ��� - ��ũ������ ���� ����Ʈ�� ���
if ($board['bo_list_scrap'] > 0) {
    $sql = " select count(*) as cnt from $g4[scrap_table] where bo_table='$bo_table' and wr_id='$wr_id' ";
    $scrap_good = sql_fetch($sql);
    if ($scrap_good['cnt'] >= $board['bo_list_scrap']) {
        // UPDATE�� �����ϰ� ������ �߻��� insert�� ����
        $sql = " update $g4[good_list_table] set good = good + 1 where bo_table='$bo_table' and wr_id='$wr_id' ";
        $result = sql_query($sql, FALSE);
        if ( mysql_affected_rows() == 0 ) {
            $sql = " insert $g4[good_list_table] ( mb_id, gr_id, bo_table, wr_id, gl_datetime, good, wr_datetime) values ( '$write[mb_id]', '$board[gr_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$list_good[cnt]', '$write[wr_datetime]' ) ";
            $result = sql_query($sql);
        }
    }
}

echo <<<HEREDOC
<script type='text/javascript'>
    if (confirm("�� ���� ��ũ�� �Ͽ����ϴ�.\\n\\n���� ��ũ���� Ȯ���Ͻðڽ��ϱ�?")) 
        document.location.href = "./scrap.php";
    else
        window.close();
</script>
HEREDOC;
?>
