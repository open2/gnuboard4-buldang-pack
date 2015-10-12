<?
if (!defined('_GNUBOARD_')) exit;

// �ֽű� ����
function latest($skin_dir="", $bo_table, $rows=10, $subject_len=40, $gallery_view=0, $notice=0, $options="")
{
    global $g4,$member;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    // $options�� explode
    if (!is_array($options))
        $opt = explode(",", $options);

    $list = array();

    $sql = " select bo_table, bo_notice, bo_subject, bo_subject_len, bo_use_list_content, bo_list_level, bo_use_sideview, bo_use_comment, bo_hot, bo_use_search, bo_new from $g4[board_table] where bo_table = '$bo_table'";
    $board = sql_fetch($sql);

    // �������� �⺻���� �����Ѵ�, $notice=1�̸� �������� ����
    $sql_notice = "";
    if ($notice) {
        // �������� �ɰ��� �迭�� �ֽ��ϴ�.
        $arr_notice = preg_split("/\n/i", trim($board[bo_notice]));
        for ($i=0; $i < count($arr_notice); $i++) { 
            // ���� ���� �����۵� �ִµ�, �װŴ� ��������...
            if (trim($arr_notice[$i]) == "") 
              continue; 
            else {
              // ���������� ���� ��� �ڿ� �޸���. �װ� �Լ��� �ϴ°ŵ� ������ �ѵ�...��...
              if ( ($i + 1) == count($arr_notice) ) 
                  $sql_notice .= $arr_notice[$i] ; 
              else 
                  $sql_notice .= $arr_notice[$i] . ","; 
            } 
        } 
        if ($i > 0) 
            $sql_notice = " and wr_id not in (" . $sql_notice . ") "; 
    }

    $tmp_write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�
    $sql_select = " wr_id, wr_subject, wr_option, wr_content, wr_comment, wr_parent, wr_datetime, wr_last, wr_homepage, wr_name, wr_reply, wr_link1, wr_link2, ca_name, wr_hit, wr_file_count, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10 ";
    $sql = " select $sql_select from $tmp_write_table where wr_is_comment = 0 $sql_notice order by wr_num limit 0, $rows ";

    // �Խ��Ǹ�Ϻ��� ������ ȸ�� ���� �̻��� ��쿡��, �ƹ��͵� ������� �ʰ� �ڵ��� �ٲ��ݴϴ�.
    if (is_array($opt) && in_array("list_level", $opt) && $board[bo_list_level] > $member[mb_level])
        $result = "";
    else
        $result = sql_query($sql);
    for ($i=0; $row = sql_fetch_array($result); $i++) 
        $list[$i] = get_list($row, $board, $latest_skin_path, $subject_len, $gallery_view);
    
    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// �����ϴ� �Խ����� ������ ����
function latest_bo_notice($skin_dir="", $bo_table, $rows=10, $subject_len=40, $gallery_view=0, $sod="rand", $skin_title="", $skin_title_link="", $options="")
{
    global $g4;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $sql = " select bo_notice from $g4[board_table] where bo_table = '$bo_table' ";
    $result = sql_fetch($sql);

    $arr_notice = preg_split("/\n/i", trim($result[bo_notice]));
    $arr_notice_count = count($arr_notice);

    // $rows�� $arr_notice_count ���� ũ��
    if ($rows < $arr_notice_count)
        $rows = $arr_notice_count;

    // �����̸� �迭�� ���ø�
    if ($sod == "rand")
        shuffle($arr_notice);

    $tmp_write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�
    $board = get_board($bo_table);
    $sql_select = " wr_id, wr_subject, wr_option, wr_content, wr_comment, wr_parent, wr_datetime, wr_last, wr_homepage, wr_name, wr_reply, wr_link1, wr_link2, ca_name, wr_hit, wr_file_count, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10 ";
    $list = array();

    for ($i=0; $i<$rows; $i++) {
        $wr_id = $arr_notice[$i];
        $sql = " select $sql_select from $tmp_write_table where wr_id = '$wr_id' ";
        $wr = sql_fetch($sql);
        $list[$i] = get_list($wr, $board, $latest_skin_path, $subject_len, $gallery_view);
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// ��ü ������ ����
function latest_notice($skin_dir="", $rows=10, $subject_len=40, $gallery_view=0, $sod="rand", $skin_title="��ü����", $skin_title_link="", $options="")
{
    global $g4;

    if ($skin_title_link == "")
        $skin_title_link = $g4[bbs_path] . "/notice_list.php";

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    if ($sod == "rand")
        $sql_order = "order by rand() ";
    else
        $sql_order = "order by $sod";

    $sql = " select * from $g4[notice_table] where 1 $sql_order limit 0, $rows";
    $result = sql_query($sql);

    $list = array();
    for ($i=0; $row = sql_fetch_array($result); $i++) {
        $tmp_write_table = $g4['write_prefix'] . $row[bo_table]; // �Խ��� ���̺� ��ü�̸�
        $board = get_board($row[bo_table]);
        $sql_select = " wr_id, wr_subject, wr_option, wr_content, wr_comment, wr_parent, wr_datetime, wr_last, wr_homepage, wr_name, wr_reply, wr_link1, wr_link2, ca_name, wr_hit, wr_file_count, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10 ";
        $sql = " select $sql_select from $tmp_write_table where wr_id = '$row[wr_id]' ";
        $wr = sql_fetch($sql);
        $list[$i] = get_list($wr, $board, $latest_skin_path, $subject_len, $gallery_view);
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// �ֱ� �α������
// $bo_hot_list : 1(�ǽð�) 2(�ְ�) 3(����) 4(�ϰ�)
// $bo_hot_list_basis : �α�� �������
function latest_popular($skin_dir="", $bo_table, $rows=10, $subject_len=40, $options="", $bo_hot_list=1, $bo_hot_list_basis="hit")
{
    global $g4;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $list = array();

    $sql = " select bo_table, bo_notice, bo_subject, bo_subject_len, bo_use_list_content, bo_use_sideview, bo_use_comment, bo_hot, bo_use_search, bo_new from $g4[board_table] where bo_table = '$bo_table'";
    $board = sql_fetch($sql);

    switch ($bo_hot_list) {
        case "1": $hot_start = ""; $hot_title = "�ǽð�"; break;
        case "2": $hot_start = date("Y-m-d H:i:s", $g4[server_time]-60*60*24*7); $hot_title = "�ְ�"; break;
        case "3": $hot_start = date("Y-m-d H:i:s", $g4[server_time]-60*60*24*30); $hot_title = "����"; break;
        case "4": $hot_start = date("Y-m-d H:i:s", $g4[server_time]-60*60*24); $hot_title = "�ϰ�"; break;
    }
    $sql_between = 1;
    if ($bo_hot_list > 1) 
    {
        $sql_between = " wr_datetime between '$hot_start' and '$g4[time_ymdhis]' ";
    }

    // ������������
    $arr_notice = preg_split("/\n/i", trim($board[bo_notice]));

    $not_sql = " ";
    for ($k=0; $k<count($arr_notice); $k++) {
        if (trim($arr_notice[$k]) !== "") {
            $not_sql .= " and wr_id <> '$arr_notice[$k]' "; 
        }
    }

    $tmp_write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�
    //$sql = " select * from $tmp_write_table where wr_is_comment = 0 order by wr_id desc limit 0, $rows ";
    // ���� �ڵ� ���� �ӵ��� ����
    //$sql = " select * from $tmp_write_table where wr_is_comment = 0 order by wr_num limit 0, $rows ";
    $sql_select = " wr_id, wr_subject, wr_option, wr_content, wr_comment, wr_parent, wr_datetime, wr_last, wr_homepage, wr_name, wr_reply, wr_link1, wr_link2, ca_name, wr_hit ";
    $sql = " SELECT $sql_select 
               FROM $tmp_write_table 
              WHERE wr_is_comment = 0 
                and $sql_between
                    $not_sql
           order by wr_{$bo_hot_list_basis} desc 
              limit 0, $rows ";
    $result = sql_query($sql);
    
    for ($i=0; $row = sql_fetch_array($result); $i++) 
        $list[$i] = get_list($row, $board, $latest_skin_path, $subject_len);

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// �� �ϳ��� �Խñ� ����
function latest_one($skin_dir="", $bo_table, $wr_id, $subject_len=40, $content_len=0, $gallery_view=0, $options="") {
    global $g4, $qstr;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $list = array();

    $board = get_board($bo_table);
    $tmp_write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�
    $row = sql_fetch(" select * from $tmp_write_table where wr_id = '$wr_id' ");

    $view = get_list($row, $board, $latest_skin_path, $subject_len, $gallery_view);

    $skin_title_link = $g4[bbs_path] . "/board.php?bo_table=$bo_table&wr_id=$wr_id" . $qstr;

    $html = 0;
    if (strstr($view[wr_option], "html1"))
        $html = 1;
    else if (strstr($view[wr_option], "html2"))
        $html = 2;

    $view[content] = conv_content($view[wr_content], $html);
    $view[content] = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $view[content]);

    if ($content_len > 0)
        $view[content] = cut_str($view[content], $content_len);

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// �������� �Խñ� ���� ($bo_table�� $wr_id�� ��Ī�Ǵ� �迭 �Դϴ�)
// $bo1[] = "�Խ���1"; $wr1[] = "�Խñ�id1"; ó�� ������ �˴ϴ�.
function latest_multi($skin_dir="", $bo_table, $wr_id, $subject_len=40, $content_len=0, $gallery_view=0, $options="") {
    global $g4, $qstr;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $list = array();

    for ($i=0; $i<count($bo_table);$i++) {

        $board = get_board($bo_table[$i]);
        $tmp_write_table = $g4['write_prefix'] . $bo_table[$i]; // �Խ��� ���̺� ��ü�̸�
        $row = sql_fetch(" select * from $tmp_write_table where wr_id = '$wr_id[$i]' ");
    
        $list[$i] = get_list($row, $board, $latest_skin_path, $subject_len, $gallery_view);
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// ��õ �������� �Խñ� ����
function latest_good($skin_dir="", $bo_table, $rows=10, $subject_len=40, $bg_flag="good", $gallery_view=0, $options="") {
    global $g4, $qstr;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $list = array();

    // �Խ����� �ֱ��� ��õ���� ��󳽴�
    $board = get_board($bo_table);
    $tmp_write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�

    $sql = " select distinct wr_id from $g4[board_good_table] where bo_table='$bo_table' and bg_flag='$bg_flag' $sql_search order by bg_id desc limit $rows ";
    $result = sql_query($sql);

    for ($i=0; $row = sql_fetch_array($result); $i++) {
        $row = sql_fetch(" select * from $tmp_write_table where wr_id = '$row[wr_id]' ");
        $list[$i] = get_list($row, $board, $latest_skin_path, $subject_len);
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
?>