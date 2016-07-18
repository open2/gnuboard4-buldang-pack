<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 불당팩 - 확장
if (file_exists("$board_skin_path/list.head.skin.php"))
    @include_once("$board_skin_path/list.head.skin.php");

// 불당팩 : 공지글의 갯수 구하기, DB 작업이 아니라서 항상 해줘도 상관엄따.
$notice = preg_split("/\n/i", trim($board[bo_notice]));
$arr_notice_1 = array();
foreach ($notice as $row) {
    if (trim($row) !== "")
        $arr_notice_1[] = $row;
}
$arr_notice_count = count($arr_notice_1);

// 공지사항 max. 갯수를 조정해 줍니다. 
// http://php.net/manual/en/function.array-slice.php
if ($g4['bo_notice_max'] > 0 && $arr_notice_count > 0 && $arr_notice_count > $g4['bo_notice_max']) {
    shuffle($arr_notice_1);
    $arr_notice = array_slice($arr_notice_1, 0, $g4['bo_notice_max']);
} else {
    $arr_notice = $arr_notice_1;
}

// SQL에서 사용할 공지사항 목록을 만들어둔다. in으로 쓰면 되는거.
if ($board[bo_notice_joongbok] && $arr_notice_count > 0)
    $sql_notice = "and wr_id not in (" . implode(",", $arr_notice) . ")";
else
    $sql_notice = "";

// 불당팩 - $board[bo_page_rows] 값이 없으면 기본값을 설정
if (!$board[bo_page_rows])
    $board[bo_page_rows] = $config[cf_page_rows];

$list_select = " * ";

// 분류 사용 여부
$is_category = false;
if ($board[bo_use_category]) 
{
    $is_category = true;
    $category_location = "$g4[path]/$bo_table?sca=";
    $category_option = get_category_option($bo_table); // SELECT OPTION 태그로 넘겨받음
}

$sop = strtolower($sop);
if ($sop != "and" && $sop != "or")
    $sop = "and";

// 분류 선택 또는 검색어가 있다면
$stx = trim($stx);
if ($sca || $stx) 
{
    // 검색권한 - 권한설정이 없으면, 조회권한과 동일하게
    if ($stx !== "") {
        if ($board['bo_search_level'] == 0 )
            $board['bo_search_level'] = $board['bo_read_level'];
        if ($board['bo_search_level'] > $member['mb_level'])
            alert("검색을 사용할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", "$g4[bbs_path]/login.php?$qstr&url=".urlencode("$_SERVER[PHP_SELF]/$bo_table?sfl=$sfl&stx=$stx&sop=$sop"));
    }
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop, $bo_table);

    // 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
    //$sql = " select MIN(wr_num) as min_wr_num from $write_table ";
    //$row = sql_fetch($sql);
    //$min_spt = $row[min_wr_num];
    $min_spt = $board['min_wr_num'];

    if (!$spt) $spt = $min_spt;

    // $max_spt가 0보다 크거나 같으면 sql_search를 할 필요가 없습니다.
    // 불필요한 query 조건이고 시스템의 부하만 가중하는 코드 입니다.
    $max_spt = $spt + $config[cf_search_part];
    if ($max_spt < 0)
        $sql_search .= " and (wr_num between '".$spt."' and '".($spt + $config[cf_search_part])."') ";

    // 원글만 얻는다. (코멘트의 내용도 검색하기 위함)
    // 라엘님 제안 코드로 대체 http://sir.co.kr/bbs/board.php?bo_table=g5_bug&wr_id=2922
    $sql = " SELECT COUNT(DISTINCT `wr_parent`) AS `cnt` FROM {$write_table} WHERE {$sql_search} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
} 
else 
{
    $sql_search = "";
    $total_count = $board[bo_count_write];
}

$total_page = ceil($total_count / $board[bo_page_rows]);        // 전체 페이지 계산

// 불당팩 - 홈이네 팁으로 수정, http://sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=20870
if ($wr_id && !$page)
{
    $query = " select COUNT(*) cnt from $write_table where wr_id > '$wr_id' and wr_is_comment = 0 ";
    $query .= $sca || $stx ? " and ".$sql_search : ""; // 분류 선택 또는 검색어가 있다면

    $row = sql_fetch( $query );
    $page = intval( $row[cnt] / $board[bo_page_rows] ) + 1;
} else if (!$page)
    { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)

$from_record = ($page - 1) * $board[bo_page_rows]; // 시작 열을 구함

// 관리자라면 CheckBox 보임
$is_checkbox = false;
if ($member[mb_id] && ($is_admin == "super" || $group[gr_admin] == $member[mb_id] || $board[bo_admin] == $member[mb_id])) 
    $is_checkbox = true;

// 정렬에 사용하는 QUERY_STRING
$qstr2 = "bo_table=$bo_table&sop=$sop";

if ($board[bo_gallery_cols])
    $td_width = (int)(100 / $board[bo_gallery_cols]);

// 정렬
// 인덱스 필드가 아니면 정렬에 사용하지 않음
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
    // 게시물 리스트의 정렬 대상 필드가 아니라면 공백으로 (nasca 님 09.06.16)
    // 리스트에서 다른 필드로 정렬을 하려면 아래의 코드에 해당 필드를 추가하세요.
    // $sst = preg_match("/^(wr_subject|wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
    $sst = preg_match("/^(wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
}

if(!$sst)
    $sst = "wr_num, wr_reply";

if ($sst)
    $sql_order = " order by $sst $sod ";

if ($sca || $stx)
{
    // 검색어 필터링 (금칙 검색어는 검색할 수 없게)
    $search_filter = 0;
    if (!$is_admin && $stx) {
        $result3 = sql_fetch(" select count(*) as cnt from $g4[filter_table] where pp_word like '%$stx%'");
        if ($result3['cnt'] > 0)
            $search_filter = 1;
    }

    if ($search_filter ==1) {
        // filtering에 걸리는 경우 결과값을 비워버린다.
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

// 년도 2자리
$today2 = $g4[time_ymd];

$list = array();
$i = 0;

// 가장 마지막 공지사항 날짜를 찾는다 (초기화)
$g4['last_notice_datetime'] = 0;

if (!$sca && !$stx) 
{
    // 불당팩 - 전체 공지를 가져 온다
    if ($board['bo_naver_notice']) {

        if ($g4[global_notice_max] > 0)
            $sql = " select * from $g4[notice_table] order by rand() limit $g4[global_notice_max] ";
        else
            $sql = " select * from $g4[notice_table] order by no_id desc ";
        $global_notice = sql_query($sql);
        $global_notice_count = mysql_num_rows($global_notice);

        while ($row_notice = sql_fetch_array($global_notice)) 
        {
            // 현재 게시판에서는 공지로 등록되어 있는 경우에, 해당 전체공지를 생략
            if ($row_notice['bo_table'] == $bo_table && in_array($row_notice[wr_id], $arr_notice))
                ;
            else {
                // 게시판 정보를 가져 옵니다.
                $n_board = get_board($row_notice['bo_table']);
                
                // 몇가지는 보여지는 게시판의 정보룰을 따라야 합니다.
                $n_board['bo_gallery'] = $board['bo_gallery'];
                
                // 정보를 가져올 테이블
                $tmp_write_table = $g4['write_prefix'] . $row_notice['bo_table']; // 게시판 테이블 전체이름
                
                $sql = " select $list_select from $tmp_write_table where wr_id = '$row_notice[wr_id]' ";
                $n_row = sql_fetch($sql);
    
                $list[$i] = get_list($n_row, $n_board, $board_skin_path, $board[bo_subject_len]);
                $list[$i]['is_notice'] = true;
                $list[$i]['n_notice'] = $n_board['bo_table'];
                $i++;

                // 가장 마지막 공지사항 날짜를 찾는다 (전체공지의 날짜를. 그런데 $list[$i][wr_datetime]은 안먹힌다. 더 수정하기 귀챦...)
                if ($n_row['wr_datetime'] > $g4['last_notice_datetime'])
                    $g4['last_notice_datetime'] = $n_row[wr_datetime];
            }
        }
    }
    
    //$arr_notice = preg_split("/\n/i", trim($board[bo_notice]));
    //$arr_notice_count = count($arr_notice);

    if ($arr_notice_count > 0 && $page == 1) { // 공지사항이 있는 경우 - 불당팩에서는 첫 페이지에서만 보이게 수정

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

                // 가장 마지막 공지사항 날짜를 찾는다 (공지의 날짜를)
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
    // 검색일 경우 wr_id만 얻었으므로 다시 한행을 얻는다
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
    // 검색일 경우 wr_id만 얻었으므로 다시 한행을 얻는다
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

// RSS 보기 사용에 체크가 되어 있어야 RSS 보기 가능 061106
$rss_href = "";
if ($board[bo_use_rss_view])
    $rss_href = "$g4[bbs_path]/rss.php?bo_table=$bo_table";

// 불당팩 : 왜 href에 $qstr을 안넣었을까?
if ($write_href) $write_href .= $qstr;
if ($rss_href) $rss_href .= $qstr;

$stx = get_text(stripslashes($stx));

// 한줄게시판은 일반 게시판과 달라 예외 처리. 모바일 분기 불필요.
if ($bo_table === 'oneline') {
    include_once($board_skin_path . "/list.skin.php");
} else {
    include_once(g4_path($board_skin_path) . "/list.skin.php");
}

// 불당팩 - 확장
if (file_exists("$board_skin_path/list.tail.skin.php"))
    @include_once("$board_skin_path/list.tail.skin.php");
?>
