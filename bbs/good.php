<?
include_once("./_common.php");

@include_once("$board_skin_path/good.head.skin.php");

echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";

if (!$is_member) 
{
    $href = "./login.php?$qstr&url=".urlencode("./board.php?bo_table=$bo_table&wr_id=$wr_id");

    echo "<script type='text/javascript'>alert('ȸ���� �����մϴ�.'); top.location.href = '$href';</script>";
    exit;
}

if (!($bo_table && $wr_id)) 
    alert_close("���� ����� �Ѿ���� �ʾҽ��ϴ�.");

$ss_name = "ss_view_{$bo_table}_{$wr_id}";
if (!get_session($ss_name))
    alert_close("�ش� �Խù������� ��õ �Ǵ� ����õ �Ͻ� �� �ֽ��ϴ�.");

$row = sql_fetch(" select count(*) as cnt from $g4[board_table] where bo_table = '$bo_table' ", FALSE);
if (!$row['cnt'])
    alert_close("�����ϴ� �Խ����� �ƴմϴ�.");

if ($good == "good" || $good == "nogood") 
{
    if($is_admin == "" && $write[mb_id] == $member[mb_id])
        alert_close("�ڽ��� �ۿ��� ��õ �Ǵ� ����õ �Ͻ� �� �����ϴ�.");

    if (!$board[bo_use_good] && $good == "good")
        alert_close("�� �Խ����� ��õ ����� ������� �ʽ��ϴ�.");

    if (!$board[bo_use_nogood] && $good == "nogood")
        alert_close("�� �Խ����� ����õ ����� ������� �ʽ��ϴ�.");

    $sql = " select bg_flag from $g4[board_good_table]
              where bo_table = '$bo_table'
                and wr_id = '$wr_id' 
                and mb_id = '$member[mb_id]' 
                and bg_flag in ('good', 'nogood') ";
    $row = sql_fetch($sql);
    if ($row[bg_flag])
    {
        if ($row[bg_flag] == "good")
            $status = "��õ";
        else 
            $status = "����õ";
        
        echo "<script type='text/javascript'>alert('�̹� \'$status\' �Ͻ� �� �Դϴ�.');</script>";
    }
    else
    {
        // ��õ(����), ����õ(�ݴ�) ī��Ʈ ����
        sql_query(" update {$g4[write_prefix]}{$bo_table} set wr_{$good} = wr_{$good} + 1 where wr_id = '$wr_id' ");
        // ���� ����
        sql_query(" insert $g4[board_good_table] set bo_table = '$bo_table', wr_id = '$wr_id', mb_id = '$member[mb_id]', bg_flag = '$good', bg_datetime = '$g4[time_ymdhis]' ");
        // ȸ���������� �ݿ�
        if ($is_member)
            sql_query(" update $g4[member_table] set mb_{$good} = mb_{$good} + 1 where mb_id = '$write[mb_id]' ");

        // �Ҵ��� - ��õ���� ���� ����Ʈ�� ��� - ��õ�ڿ� �־�� ������ ����� �ݿ�
        if ($board[bo_list_good] > 0) {
            $sql = " select count(*) as cnt from $g4[board_good_table] where bo_table='$bo_table' and wr_id='$wr_id' and bg_flag = 'good' ";
            $list_good = sql_fetch($sql);
            if ($list_good[cnt] >= $board[bo_list_good]) {
                // UPDATE�� �����ϰ� ������ �߻��� insert�� ����
                $sql = " update $g4[good_list_table] set good = good + 1 where bo_table='$bo_table' and wr_id='$wr_id' ";
                $result = sql_query($sql, FALSE);
                if ( mysql_affected_rows() == 0 ) {
                    $sql = " insert $g4[good_list_table] ( mb_id, gr_id, bo_table, wr_id, gl_datetime, good, wr_datetime) values ( '$write[mb_id]', '$board[gr_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$list_good[cnt]', '$write[wr_datetime]' ) ";
                    $result = sql_query($sql);
                }
            }
        }
        if ($board[bo_list_nogood] > 0) {
            $sql = " select count(*) as cnt from $g4[board_good_table] where bo_table='$bo_table' and wr_id='$wr_id' and bg_flag = 'nogood' ";
            $list_nogood = sql_fetch($sql);
            if ($list_nogood[cnt] >= $board[bo_list_nogood]) {
                // UPDATE�� �����ϰ� ������ �߻��� insert�� ����
                $sql = " update $g4[good_list_table] set nogood = nogood + 1 where bo_table='$bo_table' and wr_id='$wr_id' ";
                $result = sql_query($sql, FALSE);
                if ( mysql_affected_rows() == 0 ) {
                    $sql = " insert $g4[good_list_table] ( mb_id, gr_id, bo_table, wr_id, gl_datetime, nogood, wr_datetime) values ( '$write[mb_id]', '$board[gr_id]', '$bo_table', '$wr_id', '$g4[time_ymdhis]', '$list_nogood[cnt]', '$write[wr_datetime]' ) ";
                    $result = sql_query($sql);
                }
            }
        }

        // ����Ʈ �־��ֱ�
        if ($good == "good") {
            // �Խñ� �ۼ���
            if ($write[mb_id] !== "" && $board[bo_good_point] && $board[bo_good_point] !== 0)
                insert_point($write[mb_id], $board[bo_good_point], "$board[bo_subject] $wr_id ��õ�ϱ�", $bo_table, $wr_id, '��õ��');
            // Ŭ���� ȸ��
            if ($board[bo_good_click_point] &&$board[bo_good_click_point] !== 0)
                insert_point($member[mb_id], $board[bo_good_click_point], "$board[bo_subject] $wr_id ��õ�ϱ�", $bo_table, $wr_id, '��õ');
        } else {
            // �Խñ� �ۼ���
            if ($write[mb_id] !== "" && $board[bo_nogood_point] && $board[bo_nogood_point] !== 0)
                insert_point($write[mb_id], $board[bo_nogood_point], "$board[bo_subject] $wr_id ����õ�ϱ�", $bo_table, $wr_id, '��õ��');
            // Ŭ���� ȸ��
            if ($board[bo_nogood_click_point] && $board[bo_nogood_click_point] !== 0)
                insert_point($member[mb_id], $board[bo_nogood_click_point], "$board[bo_subject] $wr_id ����õ�ϱ�", $bo_table, $wr_id, '��õ');
        }

        if ($good == "good") 
            $status = "��õ";
        else 
            $status = "����õ";

        // �Ҵ��� - ��õ�� �������� �� Ȯ�� ���α׷� ����
        @include_once("$board_skin_path/good.tail.skin.php");

        echo "<script type='text/javascript'> alert('�� ���� \'$status\' �ϼ̽��ϴ�.');</script>";
    }
}
?>
<script type="text/javascript"> window.close(); </script>