<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Ҵ��� - Ȯ��
if (file_exists("$board_skin_path/list.head.skin.php"))
    @include_once("$board_skin_path/list.head.skin.php");

// �Ҵ��� : �������� ���� ���ϱ�, DB �۾��� �ƴ϶� �׻� ���൵ �������.
$notice = preg_split("/\n/i", trim($board[bo_notice]));
$arr_notice_1 = array();
foreach ($notice as $row) {
    if (trim($row) !== "")
        $arr_notice_1[] = $row;
}
$arr_notice_count = count($arr_notice_1);

// �������� max. ������ ������ �ݴϴ�. 
// http://php.net/manual/en/function.array-slice.php
if ($g4['bo_notice_max'] > 0 && $arr_notice_count > 0 && $arr_notice_count > $g4['bo_notice_max']) {
    shuffle($arr_notice_1);
    $arr_notice = array_slice($arr_notice_1, 0, $g4['bo_notice_max']);
} else {
    $arr_notice = $arr_notice_1;
}

// SQL���� ����� �������� ����� �����д�. in���� ���� �Ǵ°�.
if ($board[bo_notice_joongbok] && $arr_notice_count > 0)
    $sql_notice = "and wr_id not in (" . implode(",", $arr_notice) . ")";
else
    $sql_notice = "";

// �Ҵ��� - $board[bo_page_rows] ���� ������ �⺻���� ����
if (!$board[bo_page_rows])
    $board[bo_page_rows] = $config[cf_page_rows];

$list_select = " * ";

// �з� ��� ����
$is_category = false;
if ($board[bo_use_category]) 
{
    $is_category = true;
    $category_location = "$g4[path]/$bo_table?sca=";
    $category_option = get_category_option($bo_table); // SELECT OPTION �±׷� �Ѱܹ���
}

$sop = strtolower($sop);
if ($sop != "and" && $sop != "or")
    $sop = "and";

// �з� ���� �Ǵ� �˻�� �ִٸ�
$stx = trim($stx);
if ($sca || $stx) 
{
    // �˻����� - ���Ѽ����� ������, ��ȸ���Ѱ� �����ϰ�
    if ($stx !== "") {
        if ($board['bo_search_level'] == 0 )
            $board['bo_search_level'] = $board['bo_read_level'];
        if ($board['bo_search_level'] > $member['mb_level'])
            alert("�˻��� ����� ������ �����ϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.", "$g4[bbs_path]/login.php?$qstr&url=".urlencode("$_SERVER[PHP_SELF]/$bo_table?sfl=$sfl&stx=$stx&sop=$sop"));
    }
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop, $bo_table);

    // ���� ���� ��ȣ�� �� ������ ���� (�ϴ��� ����¡���� ���)
    //$sql = " select MIN(wr_num) as min_wr_num from $write_table ";
    //$row = sql_fetch($sql);
    //$min_spt = $row[min_wr_num];
    $min_spt = $board['min_wr_num'];

    if (!$spt) $spt = $min_spt;

    // $max_spt�� 0���� ũ�ų� ������ sql_search�� �� �ʿ䰡 �����ϴ�.
    // ���ʿ��� query �����̰� �ý����� ���ϸ� �����ϴ� �ڵ� �Դϴ�.
    $max_spt = $spt + $config[cf_search_part];
    if ($max_spt < 0)
        $sql_search .= " and (wr_num between '".$spt."' and '".($spt + $config[cf_search_part])."') ";

    // ���۸� ��´�. (�ڸ�Ʈ�� ���뵵 �˻��ϱ� ����)
    // �󿤴� ���� �ڵ�� ��ü http://sir.co.kr/bbs/board.php?bo_table=g5_bug&wr_id=2922
    $sql = " SELECT COUNT(DISTINCT `wr_parent`) AS `cnt` FROM {$write_table} WHERE {$sql_search} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
} 
else 
{
    $sql_search = "";
    $total_count = $board[bo_count_write];
}

$total_page = ceil($total_count / $board[bo_page_rows]);        // ��ü ������ ���

// �Ҵ��� - Ȩ�̳� ������ ����, http://sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=20870
if ($wr_id && !$page)
{
    $query = " select COUNT(*) cnt from $write_table where wr_id > '$wr_id' and wr_is_comment = 0 ";
    $query .= $sca || $stx ? " and ".$sql_search : ""; // �з� ���� �Ǵ� �˻�� �ִٸ�

    $row = sql_fetch( $query );
    $page = intval( $row[cnt] / $board[bo_page_rows] ) + 1;
} else if (!$page)
    { $page = 1; } // �������� ������ ù ������ (1 ������)

$from_record = ($page - 1) * $board[bo_page_rows]; // ���� ���� ����

// �����ڶ�� CheckBox ����
$is_checkbox = false;
if ($member[mb_id] && ($is_admin == "super" || $group[gr_admin] == $member[mb_id] || $board[bo_admin] == $member[mb_id])) 
    $is_checkbox = true;

// ���Ŀ� ����ϴ� QUERY_STRING
$qstr2 = "bo_table=$bo_table&sop=$sop";

if ($board[bo_gallery_cols])
    $td_width = (int)(100 / $board[bo_gallery_cols]);

// ����
// �ε��� �ʵ尡 �ƴϸ� ���Ŀ� ������� ����
//if (!$sst || ($sst && !(strstr($sst, 'wr_id') || strstr($sst, "wr_datetime")))) {
if (!$sst)
{
    if ($board[bo_sort_field])
        $sst = $board[bo_sort_field];
    else
        $sst  = "wr_num, wr_reply";
    $sod = "";
}
else {
    // �Խù� ����Ʈ�� ���� ��� �ʵ尡 �ƴ϶�� �������� (nasca �� 09.06.16)
    // ����Ʈ���� �ٸ� �ʵ�� ������ �Ϸ��� �Ʒ��� �ڵ忡 �ش� �ʵ带 �߰��ϼ���.
    // $sst = preg_match("/^(wr_subject|wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
    $sst = preg_match("/^(wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
}

if(!$sst)
    $sst = "wr_num, wr_reply";

if ($sst)
    $sql_order = " order by $sst $sod ";

if ($sca || $stx)
{
    // �˻��� ���͸� (��Ģ �˻���� �˻��� �� ����)
    $search_filter = 0;
    if (!$is_admin && $stx) {
        $result3 = sql_fetch(" select count(*) as cnt from $g4[filter_table] where pp_word like '%$stx%'");
        if ($result3['cnt'] > 0)
            $search_filter = 1;
    }

    if ($search_filter ==1) {
        // filtering�� �ɸ��� ��� ������� ���������.
        $result = sql_query(" select * from $g4[filter_table] where pp_word='!@#$%^&DFVDSGF'");
    } else {
        $sql = " select distinct wr_parent from {$write_table} where {$sql_search} {$sql_order} limit {$from_record}, $board[bo_page_rows] ";
        $result = sql_query($sql);
    }
}
else
{
    $sql = " select $list_select from $write_table where wr_is_comment = 0 $sql_notice $sql_order limit $from_record, $board[bo_page_rows] ";
    $result = sql_query($sql, false);
}

// �⵵ 2�ڸ�
$today2 = $g4[time_ymd];

$list = array();
$i = 0;

// ���� ������ �������� ��¥�� ã�´� (�ʱ�ȭ)
$g4['last_notice_datetime'] = 0;

if (!$sca && !$stx) 
{
    // �Ҵ��� - ��ü ������ ���� �´�
    if ($board['bo_naver_notice']) {

        if ($g4[global_notice_max] > 0)
            $sql = " select * from $g4[notice_table] order by rand() limit $g4[global_notice_max] ";
        else
            $sql = " select * from $g4[notice_table] order by no_id desc ";
        $global_notice = sql_query($sql);
        $global_notice_count = mysql_num_rows($global_notice);

        while ($row_notice = sql_fetch_array($global_notice)) 
        {
            // ���� �Խ��ǿ����� ������ ��ϵǾ� �ִ� ��쿡, �ش� ��ü������ ����
            if ($row_notice['bo_table'] == $bo_table && in_array($row_notice[wr_id], $arr_notice))
                ;
            else {
                // �Խ��� ������ ���� �ɴϴ�.
                $n_board = get_board($row_notice['bo_table']);
                
                // ����� �������� �Խ����� �������� ����� �մϴ�.
                $n_board['bo_gallery'] = $board['bo_gallery'];
                
                // ������ ������ ���̺�
                $tmp_write_table = $g4['write_prefix'] . $row_notice['bo_table']; // �Խ��� ���̺� ��ü�̸�
                
                $sql = " select $list_select from $tmp_write_table where wr_id = '$row_notice[wr_id]' ";
                $n_row = sql_fetch($sql);
    
                $list[$i] = get_list($n_row, $n_board, $board_skin_path, $board[bo_subject_len]);
                $list[$i]['is_notice'] = true;
                $list[$i]['n_notice'] = $n_board['bo_table'];
                $i++;

                // ���� ������ �������� ��¥�� ã�´� (��ü������ ��¥��. �׷��� $list[$i][wr_datetime]�� �ȸ�����. �� �����ϱ� ��ï...)
                if ($n_row['wr_datetime'] > $g4['last_notice_datetime'])
                    $g4['last_notice_datetime'] = $n_row[wr_datetime];
            }
        }
    }
    
    //$arr_notice = preg_split("/\n/i", trim($board[bo_notice]));
    //$arr_notice_count = count($arr_notice);

    if ($arr_notice_count > 0 && $page == 1) { // ���������� �ִ� ��� - �Ҵ��ѿ����� ù ������������ ���̰� ����

        $sql_case = " ";
        $j = 0;
        for ($k=0; $k<$arr_notice_count; $k++) 
        {
            if (trim($arr_notice[$k]) == '')
              continue;

            $sql_case .= " when " . $arr_notice[$k] . " then " . $k ;
            if ($j == 0)
              $sql_where = " wr_id = " . $arr_notice[$k] . " ";
            else
              $sql_where .= " or wr_id = " . $arr_notice[$k] . " ";
            $j++;
        } // end of for

        if ($j > 0) {
            $sql = " select {$list_select} , case wr_id $sql_case else 10000 end as fsort from $write_table where $sql_where order by fsort,wr_num, wr_reply ";
            $result_notice = sql_query($sql);

            while ($row_notice = sql_fetch_array($result_notice)) 
            {
                if (!$row_notice['wr_id']) continue;

                $list[$i] = get_list($row_notice, $board, $board_skin_path, $board[bo_subject_len]);
                $list[$i][is_notice] = true;

                // ���� ������ �������� ��¥�� ã�´� (������ ��¥��)
                if ($list[$i][wr_datetime] > $g4['last_notice_datetime'])
                    $g4['last_notice_datetime'] = $list[$i][wr_datetime];

                $i++;
            } // end of while
        } // end of if $j > 0
    
    } // end of if $arr_notice_count > 0
}

if (!$sca && !$stx) 
{
    // nothing
}
else 
{
    // �˻��� ��� wr_id�� ������Ƿ� �ٽ� ������ ��´�
    $sql_case = "";
    $j = 0;
    while ($row = sql_fetch_array($result)) 
    {
        $sql_case .= " when " . $row[wr_parent] . " then " . $j ;
        if ($j == 0)
            $sql_where = " wr_id = " . $row[wr_parent] . " ";
        else
            $sql_where .= " or wr_id = " . $row[wr_parent] . " ";
        $j++;
    } // end of for

    if ($sql_case) {
        $sql = " select {$list_select} , case wr_id $sql_case else 10000 end as fsort from $write_table where $sql_where order by fsort,wr_num, wr_reply ";
        $result = sql_query($sql);
    } else {
        $result = array();
    }
}

$k = 0;

while ($row = sql_fetch_array($result)) 
{
    // �˻��� ��� wr_id�� ������Ƿ� �ٽ� ������ ��´�
    //if ($sca || $stx)
    //    $row = sql_fetch(" select {$list_select} from $write_table where wr_id = '$row[wr_parent]' ");

    $list[$i] = get_list($row, $board, $board_skin_path, $board[bo_subject_len]);
    if (strstr($sfl, "subject"))
        $list[$i][subject] = search_font($stx, $list[$i][subject]);
    $list[$i][is_notice] = false;
    //$list[$i][num] = number_format($total_count - ($page - 1) * $board[bo_page_rows] - $k);
    $list[$i][num] = $total_count - ($page - 1) * $board[bo_page_rows] - $k;

    $i++;
    $k++;
}

$write_pages = get_paging($config[cf_write_pages], $page, $total_page, "$g4[path]/$bo_table?".$qstr."&page=");
$write_pages_xs = get_paging($config[cf_write_pages_xs], $page, $total_page, "$g4[path]/$bo_table?".$qstr."&page=");

$list_href = '';
$prev_part_href = '';
$next_part_href = '';
if ($sca || $stx)  
{
    $list_href = "$g4[path]/$bo_table?" . $mstr;

    //if ($prev_spt >= $min_spt) 
    $prev_spt = $spt - $config[cf_search_part];
    if (isset($min_spt) && $prev_spt >= $min_spt)
        $prev_part_href = "$g4[path]/$bo_table?".$qstr."&spt=$prev_spt";

    $next_spt = $spt + $config[cf_search_part];
    if ($next_spt < 0) 
        $next_part_href = "$g4[path]/$bo_table?".$qstr."&spt=$next_spt";
} else {
    $list_href = "$g4[path]/$bo_table?page=$page" . $mstr;
}

$write_href = "";
//if ($member[mb_level] >= $board[bo_write_level]) 
    $write_href = "$g4[bbs_path]/write.php?bo_table=$bo_table" . $mstr;

$nobr_begin = $nobr_end = "";
if (preg_match("/gecko|firefox/i", $_SERVER['HTTP_USER_AGENT'])) {
    $nobr_begin = "<nobr style='display:block; overflow:hidden;'>";
    $nobr_end   = "</nobr>";
}

// RSS ���� ��뿡 üũ�� �Ǿ� �־�� RSS ���� ���� 061106
$rss_href = "";
if ($board[bo_use_rss_view])
    $rss_href = "$g4[bbs_path]/rss.php?bo_table=$bo_table";

// �Ҵ��� : �� href�� $qstr�� �ȳ־�����?
if ($write_href) $write_href .= $qstr;
if ($rss_href) $rss_href .= $qstr;

$stx = get_text(stripslashes($stx));

// ���ٰԽ����� �Ϲ� �Խ��ǰ� �޶� ���� ó��. ����� �б� ���ʿ�.
if ($bo_table === 'oneline') {
    include_once($board_skin_path . "/list.skin.php");
} else {
    include_once(g4_path($board_skin_path) . "/list.skin.php");
}

// �Ҵ��� - Ȯ��
if (file_exists("$board_skin_path/list.tail.skin.php"))
    @include_once("$board_skin_path/list.tail.skin.php");
?>
