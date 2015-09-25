<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �״�SEO - ��� �Դ��� �м��ؼ� ���̺� �ֽ��ϴ�.
function convertUrlQuery($query) { 
    $queryParts = explode('&', $query); 
    
    $params = array(); 
    foreach ($queryParts as $param) { 
        $item = explode('=', $param); 
        $params[$item[0]] = $item[1]; 
    } 
    
    return $params; 
}
if ($referer) {
    $u = parse_url($referer);
    $q = convertUrlQuery($u['query']);
    $host = $u['host'];

    //$sql = " insert $g4[seo_server_table] (server_name, server_date, count) values ('$host', '$g4[time_ymd]', 1) ON DUPLICATE KEY update count = count+1 ";
    //sql_query($sql);

    $stmt = mysqli_prepare($mysqli_db, " insert $g4[seo_server_table] (server_name, server_date, count) values (?, '$g4[time_ymd]', 1) ON DUPLICATE KEY update count = count+1 ");
    mysqli_stmt_bind_param($stmt, "s", $host);
    $result = sqli_query($stmt);

    $query = "";
    // ���̹�
    if (stristr($host, ".naver.") && $q['query']) {
        $query = urldecode($q['query']);
        $query = iconv($q['ie'], $g4['charset'] , $query);
    // ����
    } else if (stristr($host, ".google.") && $q['q']) {
        $query = urldecode($q['q']);
        $query = iconv("UTF-8", $g4['charset'] , $query);
    // ����
    } else if (stristr($host, ".daum.") && $q['q']) {
        $query = urldecode($q['q']);
        $query = iconv("UTF-8", $g4['charset'] , $query);
    // sir.co.kr
    } else if (stristr($host, "sir.co.kr") && $q['stx']) {
        $query = urldecode($q['stx']);
        $query = iconv("UTF-8", $g4['charset'] , $query);
    // �� ����...�װŴ� �׳� �α�˻�� ���~
    } else if ($g4['cookie_domain'] && stristr($host, $g4['cookie_domain']) && $q['stx']) {
        ;
    }

    // �˻��� ���͸� (��Ģ �˻���� SEO�� ���� �ʽ��ϴ�)
    $result3 = sql_fetch(" select count(*) as cnt from $g4[filter_table] where pp_word = '$query' ");
    if ($result3['cnt'] > 0)
        unset($query);

    if ($query) {
        //$sql = " insert $g4[seo_tag_table] (tag_name, tag_date, count, bo_table, wr_id) 
        //         values ('$query', '$g4[time_ymd]', 1, '$bo_table', '$wr_id') ON DUPLICATE KEY update count = count+1 ";
        //sql_query($sql);

        $stmt = mysqli_prepare($mysqli_db, " insert $g4[seo_tag_table] (tag_name, tag_date, count, bo_table, wr_id) values (?, '$g4[time_ymd]', 1, ?, ?) ON DUPLICATE KEY update count = count+1 ");
        mysqli_stmt_bind_param($stmt, "ssi", $query, $bo_table, $wr_id);
        $result = sqli_query($stmt);
    }
}

// ��ǻ���� �����ǿ� ��Ű�� ����� �����ǰ� �ٸ��ٸ� ���̺� �ݿ���
if (get_cookie('ck_visit_ip') != $_SERVER['REMOTE_ADDR']) {
    set_cookie('ck_visit_ip', $_SERVER['REMOTE_ADDR'], 86400); // �Ϸ絿�� ����

    // vi_id�� auto_increment�� �����Կ� ���� ���ʿ���
    //$tmp_row = sql_fetch(" select max(vi_id) as max_vi_id from $g4[visit_table] ");
    //$vi_id = $tmp_row[max_vi_id] + 1;

    //$sql = " insert $g4[visit_table] ( vi_ip, vi_date, vi_time, vi_referer, vi_agent ) values ( '$remote_addr', '$g4[time_ymd]', '$g4[time_his]', '$referer', '$user_agent' ) ";
    //$result = sql_query($sql, FALSE);

    $stmt = mysqli_prepare($mysqli_db, " insert $g4[visit_table] ( vi_ip, vi_date, vi_time, vi_referer, vi_agent ) values ( '$remote_addr', '$g4[time_ymd]', '$g4[time_his]', ?, ?) ");
    mysqli_stmt_bind_param($stmt, "ss", $referer, $user_agent);
    $result = sqli_query($stmt, FALSE);

    // �������� INSERT �Ǿ��ٸ� �湮�� �հ迡 �ݿ�
    if ($result) {
      
        // UPDATE�� �����ϰ� ������ �߻��� insert�� ���� (����������)
        $sql = " update $g4[visit_sum_table] set vs_count = vs_count + 1 where vs_date = '$g4[time_ymd]' ";
        $result = sql_query($sql, FALSE);
        
        if ( mysql_affected_rows() == 0 ) {
            $sql = " insert $g4[visit_sum_table] ( vs_count, vs_date) values ( 1, '$g4[time_ymd]' ) ";
            $result = sql_query($sql, FALSE);
        }

        // INSERT, UPDATE �Ȱ��� �ִٸ� �⺻ȯ�漳�� ���̺� ����
        // �湮�� ���ӽø��� ���� ������ ���� �ʱ� ���� (��û�� ������ ���� ^^)

        // ����
        $sql = " select vs_count as cnt from $g4[visit_sum_table] where vs_date = '$g4[time_ymd]' ";
        $row = sql_fetch($sql);
        $vi_today = $row[cnt];

        // ����
        $sql = " select vs_count as cnt from $g4[visit_sum_table] where vs_date = DATE_SUB('$g4[time_ymd]', INTERVAL 1 DAY) ";
        $row = sql_fetch($sql);
        $vi_yesterday = $row[cnt];

        // �հ�, ��ü - ���������Բ��� SQL 2���� 1���� �ٿ��ּ̽��ϴ�.
        $sql = " select max(vs_count) as cnt , sum(vs_count) as total from $g4[visit_sum_table] "; 
        $row = sql_fetch($sql);
        $vi_sum = $row[total];
        $vi_max = $row[cnt];

        $visit = "����:$vi_today,����:$vi_yesterday,�ִ�:$vi_max,��ü:$vi_sum";

        // �⺻���� ���̺� �湮�ڼ��� ����� �� 
        // �湮�ڼ� ���̺��� ���� �ʰ� ����Ѵ�.
        // ������ ���� ���κ� ����
        sql_query(" update $g4[config_table] set cf_visit = '$visit' ");
    }
}
?>
