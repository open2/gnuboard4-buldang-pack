<?
include_once("./_common.php");

// clean the output buffer
ob_end_clean();

$no = (int)$no;

@include_once("$board_skin_path/download.head.skin.php");

// ��Ű�� ����� ID���� �Ѿ�� ID���� ���Ͽ� ���� ���� ��� ���� �߻�
// �ٸ������� ��ũ �Ŵ°��� �����ϱ� ���� �ڵ�
if (!get_session("ss_view_{$bo_table}_{$wr_id}")) 
    alert("�߸��� �����Դϴ�.");  

$sql = " select bf_source, bf_file, bf_datetime from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
$file = sql_fetch($sql);
if (!$file[bf_file]) {
    alert_close("���� ������ �������� �ʽ��ϴ�.");
}

if ($member[mb_level] < $board[bo_download_level]) { 
    $alert_msg = "�ٿ�ε� ������ �����ϴ�.";
    if ($member[mb_id])
        alert($alert_msg);
    else
        alert($alert_msg . "\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.", "./login.php?wr_id=$wr_id&$qstr&url=".urlencode("$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$wr_id"));
}

$filepath = "$g4[path]/data/file/$bo_table/$file[bf_file]";
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath)) {

    // ���������� ���� �մϴ�.
    //$sql = " delete from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
    //sql_query($sql);

    $tmp_write_table = $g4[write_prefix] . $bo_table;
    
    // �ش�Խñ��� ���������� ���ҽ����ݴϴ�.
    //$sql = " update $tmp_write_table set wr_file_count=wr_file_count-1 where wr_id = '$wr_id' ";
    //sql_query($sql);

    // �۾��̸� ã���ϴ�.
    $sql = " select mb_id from $tmp_write_table where wr_id = '$wr_id' ";
    $tmp1 = sql_fetch($sql);
    if ($tmp1[mb_id])
        $mb = get_member($tmp1[mb_id]);

    // �۾��̿� �����ڿ��� ������ �����ϴ�.
    include_once("$g4[path]/memo.config.php");

    $me_subject = "[���] �Խñ��� ÷������ �������� ���ؼ�";
    $msg = "�÷��ֽ� �Խñ��� ÷�������� ���������ϴ�.<br>
            �Խñ��� Ȯ���� �ֽñ� �ٶ��ϴ�.<br>
            <br>
            ÷������ ��  �� - $file[bf_source]<br>
            ÷������ ����� - $file[bf_datetime]<br>
            <br>
            <a href='$g4[url]/bbs/board.php?bo_table=$bo_table&wr_id=$wr_id' target=new>�Խñ۹ٷΰ���</a>";
    $msg = addslashes($msg);
    $me_recv_mb_id = $mb[mb_id];
    $me_send_mb_id = $config[cf_admin];

    // function memo4_send($me_recv_mb_id, $me_send_mb_id, $me_memo, $me_subject, $me_option="html1", $mb_memo_call="1") 
    memo4_send($config[cf_admin], $me_send_mb_id, "aaa", $me_subject);
    if ($me_recv_mb_id)
        memo4_send($me_recv_mb_id, $me_send_mb_id, $msg, $me_subject);

    alert("������ �������� �ʽ��ϴ�.");
}

// ����� �ڵ� ����
@include_once("$board_skin_path/download.skin.php");

// �̹� �ٿ�ε� ���� ���������� �˻��� �� �Խù��� �ѹ��� ����Ʈ�� �����ϵ��� ����
$ss_name = "ss_down_{$bo_table}_{$wr_id}_{$no}";

if (!get_session($ss_name)) 
{
    // �ڽ��� ���̶�� ���
    // �������� ��� ���
    if (($write[mb_id] && $write[mb_id] == $member[mb_id]) || $is_admin)
        ;
    else if ($board[bo_download_level] > 1) // ȸ���̻� �ٿ�ε尡 �����ϴٸ�
    {
        // �ٿ�ε� ����Ʈ�� �����̰� ȸ���� ����Ʈ�� 0 �̰ų� �۴ٸ�
        if ($member[mb_point] + $board[bo_download_point] < 0)
            alert("�����Ͻ� ����Ʈ(".number_format($member[mb_point]).")�� ���ų� ���ڶ� �ٿ�ε�(".number_format($board[bo_download_point]).")�� �Ұ��մϴ�.\\n\\n����Ʈ�� �����Ͻ� �� �ٽ� �ٿ�ε� �� �ֽʽÿ�.");

        // �Խù��� ÷�ι������� �ѹ��� �����ϵ��� ����
        //insert_point($member[mb_id], $board[bo_download_point], "$board[bo_subject] $wr_id ���� �ٿ�ε�", $bo_table, $wr_id, "{$no}_�ٿ�ε�");
        // �Խù��� �ѹ��� �����ϵ��� ����
        insert_point($member[mb_id], $board[bo_download_point], "$board[bo_subject] $wr_id ���� �ٿ�ε�", $bo_table, $wr_id, "�ٿ�ε�");
    }

    // �Ҵ��� - �ٿ�ε� ����
    if ($member[mb_id]) {

        // �Ҵ��� - db���� �ٿ�ε� ���θ� Ȯ��
        $sql = " select count(*) as cnt from $g4[board_file_download_table] 
                  where bo_table = '$bo_table' and wr_id = '$wr_id' and mb_id = '$member[mb_id]' ";
        $result = sql_fetch($sql);

        if ($result[cnt] == 0) {
            $gr_id = sql_fetch(" select gr_id from $g4[board_table] where bo_table= '$bo_table' ");
            $sql = " insert into $g4[board_file_download_table]
                        set bo_table = '$bo_table',
                            wr_id = '$wr_id',
                            bf_no = '$no',
                            mb_id = '$member[mb_id]',
                            download_point = '$board[bo_download_point]',
                            dn_count = '1',
                            dn_datetime = '$g4[time_ymdhis]',
                            dn_ip = '$remote_addr',
                            gr_id = '$gr_id[gr_id]'
                             ";
            sql_query($sql);
        } else {
            // �Ҵ��� - �ٿ�ε� ���� (�̹� �ٿ��� ��쿡�� �ش� �ٿ�ε��� ���� count�� �ϳ��� ����)
            $sql = " update $g4[board_file_download_table] set dn_count = dn_count + 1 where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
            sql_query($sql);
        }

    }
    
    // �ٿ�ε� ī��Ʈ ����
    $sql = " update $g4[board_file_table] set bf_download = bf_download + 1 where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
    sql_query($sql);

    set_session($ss_name, TRUE);
}

$g4[title] = "$group[gr_subject] > $board[bo_subject] > " . conv_subject($write[wr_subject], 255) . " > �ٿ�ε�";

$filepath = "$g4[data_path]/file/$bo_table/$file[bf_file]";
$filepath = addslashes($filepath);
// utf-8 ���� �̸� �������� ����
//if (preg_match("/^utf/i", $g4[charset]))
//    $original = urlencode($file[bf_source]);
//else
    $original = $file[bf_source];

@include_once("$board_skin_path/download.tail.skin.php");

if (file_exists($filepath)) {
    if(preg_match("/msie/i", $_SERVER[HTTP_USER_AGENT]) && preg_match("/5\.5/", $_SERVER[HTTP_USER_AGENT])) {
        header("content-type: doesn/matter");
        header("content-length: ".filesize("$filepath"));
        header("content-disposition: attachment; filename=\"$original\"");
        header("content-transfer-encoding: binary");
    } else {
        header("content-type: file/unknown");
        header("content-length: ".filesize("$filepath"));
        header("content-disposition: attachment; filename=\"$original\"");
        header("content-description: php generated data");
    }

    header("pragma: no-cache");
    header("expires: 0");
    flush();

    if (is_file("$filepath")) {
        $fp = fopen("$filepath", "rb");

        // 4.00 ��ü
        // �������ϸ� ���̷��� print �� echo �Ǵ� while ���� �̿��� ������ٴ� �̹����...
        //if (!fpassthru($fp)) {
        //    fclose($fp);
        //}

        // 1�� ������ �ٿ�ε� �ǰ�, �ٿ�ε尡 �ȵǴ� ��� �Ǵ� �ٿ�ε� �ӵ��� ������ �ʿ��� ���
        // �ٿ�ε� rate = fread ������ 100*1024(100k) * 1�� sleep(1) = �ʴ� 100k
        $download_rate = 100;
        $download_rate = round($download_rate * 1024);
        while(!feof($fp)) { 
            echo fread($fp, $download_rate); 
            flush();
            //sleep(1);
        }
        fclose ($fp); 
        flush();
    } else {
        alert("�ش� �����̳� ��ΰ� �������� �ʽ��ϴ�.");
    }

} else {
    alert("������ ã�� �� �����ϴ�.");
}
?>
