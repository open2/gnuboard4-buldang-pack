<?
include_once("./_common.php");

//if (!$stx) alert("�˻�� �����ϴ�."); 

// �˻�����
if ($g4['search_level']) {
    if ($g4['search_level'] > $member['mb_level'])
        alert("�˻��� ����� ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.", "./login.php?$qstr&url=".urlencode("$_SERVER[PHP_SELF]?sfl=$sfl&stx=$stx&sop=$sop"));
}

$g4[title] = "�˻� : " . $stx;
include_once("./_head.php");

if ($stx)
{
    //$stx = trim($stx);
    $stx = preg_replace("/\//", "\/", trim($stx));
    
    // �Ҵ��� - �ܾ����͸�, ����, write_update.php, http://sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=20275
    $config[cf_filter] = trim($config[cf_filter]);
    $filters = explode(",", $config[cf_filter]);
    for ($i=0; $i<count($filters); $i++) {
        $s = trim($filters[$i]); // ���ʹܾ��� �յ� ������ ����
        // ���� ���� comma �յڿ� ��ĭ�� �����ô� �е���...
        if ($s == "")
            continue;
        if (stristr($stx, $s)) {
            alert("�˻�� �����ܾ�(\'{$s}\')�� ���ԵǾ� �ֽ��ϴ�.");
            exit;
        }
    }
    
    $sop = strtolower($sop);
    if (!$sop || !($sop == "and" || $sop == "or")) $sop = "and"; // ������ and , or
    if (!$srows) $srows = 10; // ���������� ����ϴ� �˻� ���

    unset($g4_search[tables]);
    unset($g4_search[read_level]);
    $sql = " select gr_id, bo_table, bo_read_level from $g4[board_table] where bo_use_search = '1' and bo_list_level <= '$member[mb_level]' ";
    //            and bo_read_level <= '$member[mb_level]' ";
    if ($gr_id)
        $sql .= " and gr_id = '$gr_id' ";
    if ($onetable) // �ϳ��� �Խ��Ǹ� �˻��Ѵٸ�
        $sql .= " and bo_table = '$onetable' ";
    $sql .= " order by bo_order_search, gr_id, bo_table ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        if ($is_admin != "super") 
        {
            // �׷����� ��뿡 ���� �˻� ����
            $sql2 = " select gr_use_access, gr_admin from $g4[group_table] where gr_id = '$row[gr_id]' ";
            $row2 = sql_fetch($sql2);
            // �׷������� ����Ѵٸ�
            if ($row2[gr_use_access])
            {
                // �׷�����ڰ� ������ ���� ȸ���� �׷�����ڶ�� ���
                if ($row2[gr_admin] && $row2[gr_admin] == $member[mb_id])
                    ;
                else 
                {
                    $sql3 = " select count(*) as cnt from $g4[group_member_table] where gr_id = '$row[gr_id]' and mb_id = '$member[mb_id]' and mb_id <> '' ";
                    $row3 = sql_fetch($sql3);
                    if (!$row3[cnt])
                        continue;
                }
            }
        }
        $g4_search[tables][] = $row[bo_table];
        $g4_search[read_level][] = $row[bo_read_level];
    }

    $search_query = "sfl=".urlencode($sfl)."&stx=".urlencode($stx)."&sop=$sop";


    $text_stx = get_text(stripslashes($stx));

    $op1 = "";

    // �˻�� �����ڷ� ������. ���⼭�� ����
    $s = explode(" ", strip_tags($stx));

    // �˻��ʵ带 �����ڷ� ������. ���⼭�� +
    $field = explode("||", trim($sfl));

    $str = "(";
    for ($i=0; $i<count($s); $i++) 
    {
        if (trim($s[$i]) == "") continue;
        //$search_str = strtolower($s[$i]);
        $search_str = $s[$i];
        $str .= $op1;
        $str .= "(";
        
        $op2 = "";
        for ($k=0; $k<count($field); $k++) // �ʵ��� ����ŭ ���� �ʵ� �˻� ���� (�ʵ�1+�ʵ�2...)
        {
            $str .= $op2;
            switch ($field[$k]) 
            {
                case "mb_id" :
                case "wr_name" :
                    $str .= "$field[$k] = '$s[$i]'";
                    break;
                case "wr_subject" :
                case "wr_content" :
                    if (preg_match("/[a-zA-Z]/", $search_str))
                        $str .= "INSTR(LOWER($field[$k]), LOWER('$search_str'))";
                    else
                        $str .= "INSTR($field[$k], '$search_str')";
                    break;
                default :
                    $str .= "1=0"; // �׻� ����
                    break;
            }
            $op2 = " or ";
        }
        $str .= ")";

        $op1 = " $sop ";

        // �α�˻���
        $sql = " insert into $g4[popular_table] set pp_word = '$search_str', pp_date = '$g4[time_ymd]', pp_ip = '$remote_addr', bo_table='$onetable', mb_id = '$member[mb_id]', sfl = '$sfl' ";
        sql_query($sql, FALSE);
        $pp_id = mysql_insert_id();


        // �α�˻��� sum - �űԷ� �� ����, �ȵ���? �׳� �������� �Ǵ°ž�. ����
        if ($pp_id) {
            // �Խ����� �ִ� ���
            if ($bo_table)
                $bo_sql = " and bo_table='$bo_table' ";
            else
                $bo_sql = " and bo_table='' ";
            // �ϴ� sum ���̺��� ������Ʈ �ϰ�
            $sql = " update $g4[popular_sum_table] set pp_count=pp_count+1 where pp_date='$g4[time_ymd]' and pp_word='$search_str' $bo_sql ";
            sql_query($sql, FALSE);
            // sum ���̺��� ������ insert�� �ϸ� �ǰŵ�.
            if ( $pp_id && mysql_affected_rows() == 0 ) {
                $sql = " insert into $g4[popular_sum_table] set pp_word = '$search_str', pp_date = '$g4[time_ymd]', bo_table='$bo_table', pp_count='1' ";
                sql_query($sql, FALSE);
            }
            // �㿡�� �˻���� ���͸��� ���� ������ �־��ִ°ž�.
            $sql3 = " select pp_level from $g4[filter_table] where pp_word = '$search_str' ";
            $result3 = sql_fetch($sql3);
            if ($result3) {
                $sql4 = " update  $g4[popular_sum_table] set pp_level=$result3[pp_level] where pp_date='$g4[time_ymd]' and pp_word='$search_str' $bo_sql ";
                sql_query($sql4);
            }
        }
    }
    $str .= ")";

    $sql_search = $str . " and wr_option not like '%secret%' "; // ��б��� ����

    $str_board_list = "";
    $board_count = 0;

    $time1 = get_microtime();

    $total_count = 0;
    for ($i=0; $i<count($g4_search[tables]); $i++) 
    {
        $tmp_write_table   = $g4[write_prefix] . $g4_search[tables][$i];
        
        $sql = " select wr_id from $tmp_write_table where $sql_search ";
        $result = sql_query($sql, false);
        $row[cnt] = @mysql_num_rows($result);

        //$sql = " select count(*) as cnt from $tmp_write_table where $sql_search ";
        //$row = sql_fetch($sql);

        $total_count += $row[cnt];
        if ($row[cnt]) 
        {
            $board_count++;
            $search_table[] = $g4_search[tables][$i];
            $read_level[]   = $g4_search[read_level][$i];
            $search_table_count[] = $total_count;
            $search_table_result_count[] = $row[cnt];

            $sql2 = " select bo_subject from $g4[board_table] where bo_table = '{$g4_search[tables][$i]}' ";
            $row2 = sql_fetch($sql2);
            $search_table_subject[] = $row2['bo_subject'];

            $str_board_list .= "<li><a href='$_SERVER[PHP_SELF]?$search_query&gr_id=$gr_id&onetable={$g4_search[tables][$i]}'>$row2[bo_subject]</a> ($row[cnt])";
        }
    }

    $rows = $srows;
    $total_page  = ceil($total_count / $rows);  // ��ü ������ ���
    if ($page == "") { $page = 1; } // �������� ������ ù ������ (1 ������)
    $from_record = ($page - 1) * $rows; // ���� ���� ����

    for ($i=0; $i<count($search_table); $i++)
    {
        if ($from_record < $search_table_count[$i]) 
        {
            $table_index = $i;
            $from_record = $from_record - $search_table_count[$i-1];
            break;
        }
    }

    $bo_subject = array();
    $list = array();

    $k=0;
    for ($idx=$table_index; $idx<count($search_table); $idx++) 
    {
        $sql = " select bo_subject from $g4[board_table] where bo_table = '$search_table[$idx]' ";
        $row = sql_fetch($sql);
        $bo_subject[$idx] = $row[bo_subject];

        $tmp_write_table = $g4[write_prefix] . $search_table[$idx];

        $sql = " select wr_id, wr_subject, wr_option, wr_is_comment, wr_parent, wr_content, mb_id, wr_name, wr_email, wr_homepage from $tmp_write_table where $sql_search order by wr_id desc limit $from_record, $rows ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) 
        {
            // �˻������ ��ũ�Ǹ� �Խ��� ���ϰ� �Ͼ
            $list[$idx][$i] = $row;
            $list[$idx][$i][href] = "./board.php?bo_table=$search_table[$idx]&wr_id=$row[wr_parent]";

            if ($row[wr_is_comment]) 
            { 
                $link .= "#c{$row[wr_id]}";
                $sql2 = " select wr_subject, wr_option from $tmp_write_table where wr_id = '$row[wr_parent]' ";
                $row2 = sql_fetch($sql2);
                //$row[wr_subject] = $row2[wr_subject];
                $row[wr_subject] = get_text($row2[wr_subject]);
            }

            // ��б��� �˻� �Ұ�
            if (strstr($row[wr_option].$row2[wr_option], "secret")) 
                $row[wr_content] = "[��б� �Դϴ�.]";

            $subject = get_text($row[wr_subject]);
            if (strstr($sfl, "wr_subject")) 
                $subject = search_font($stx, $subject);

            if ($read_level[$idx] <= $member[mb_level])
            {
                $content = cut_str(conv_content(strip_tags($row[wr_content]),1),300,"��");
                if (strstr($sfl, "wr_content")) 
                    $content = search_font($stx, $content);
            }
            else
                $content = '';

            $list[$idx][$i][subject] = $subject;
            $list[$idx][$i][content] = $content;
            $list[$idx][$i][name] = get_sideview($row[mb_id], cut_str($row[wr_name], $config[cf_cut_name]), $row[wr_email], $row[wr_homepage]);
            
            $k++;
            if ($k >= $rows) 
                break; 
        }
        sql_free_result($result);
        
        if ($k >= $rows) 
            break; 

        $from_record = 0;
    }

    $write_pages = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$search_query&gr_id=$gr_id&srows=$srows&onetable=$onetable&page=");

}

if (!$sfl) $sfl = "wr_subject";
if (!$sop) $sop = "or";

$search_skin_path = "$g4[path]/skin/search/$config[cf_search_skin]";

@include_once("$search_skin_path/search.head.skin.php");

include_once("$search_skin_path/search.skin.php");

@include_once("$search_skin_path/search.tail.skin.php");

include_once("./_tail.php");
?>
