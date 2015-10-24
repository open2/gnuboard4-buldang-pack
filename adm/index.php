<?
include_once("./_common.php");

$g4['title'] = "관리자메인";
include_once ("./admin.head.php");

$new_member_rows = 5;
$new_point_rows = 5;
$new_write_rows = 5;

$sql_common = " from $g4[member_table] ";
$sql_search = " where (1) ";

//if ($is_admin == 'group') $sql_search .= " and mb_level = '$member[mb_level]' ";
if ($is_admin != 'super') 
    $sql_search .= " and mb_level <= '$member[mb_level]' ";

if (!isset($sst)) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

// 전체회원수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and mb_leave_date <> ''
         ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and mb_intercept_date <> ''
         ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

// 신규가입 회원목록
$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $new_member_rows ";
$result = sql_query($sql);
?>

<div class="row-fluid row">
<div class="col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading"><a href="./member_list.php">신규가입회원 <?=$new_member_rows?>건</a>
            <span class="pull-right">총회원수 : <?=number_format($total_count)?>, 차단 : <?=number_format($intercept_count)?>, 탈퇴 : <?=number_format($leave_count)?></span>
        </div>
    </div>
    <table width=100% class="table table-hover" style="word-wrap:break-word;">
    <tr class="success">
        <td>회원아이디</td>
        <td>이름</td>
        <td>별명</td>
        <td>권한</td>
        <td>포인트</td>
        <td>최종접속</td>
    </tr>
    <?
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);
    
        $mb_id = $row['mb_id'];
        echo "
        <tr>
            <td title='$row[mb_id]'>$mb_id</td>
            <td>$row[mb_name]</td>
            <td>$mb_nick</td>
            <td>$row[mb_level]</td>
            <td><a href='./point_list.php?sfl=mb_id&stx=$row[mb_id]'>".number_format($row['mb_point'])."</a></td>
            <td>".get_datetime($row['mb_today_login'])."</td>
                 
        </tr>";
    }
    
    if ($i == 0)
        echo "<tr><td colspan='6' align=center height=100>자료가 없습니다.</td></tr>";
    ?>
    </table>
</div>
<div class="col-sm-6">
    <?
    $sql_common = " from $g4[point_table] ";
    $sql_search = " where (1) ";
    $sql_order = " order by po_id desc ";

    $sql = " select count(*) as cnt
              $sql_common
              $sql_search ";
    
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    
    $sql = " select *
              $sql_common
              $sql_search
              $sql_order
              limit $new_point_rows ";
    $result = sql_query($sql);
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><a href="./point_list.php">최근포인트 <?=$new_point_rows?>건</a>
            <span class="pull-right">
                (건수 : <?=number_format($total_count)?>, 
                <? 
                $row2 = sql_fetch(" select sum(po_point) as sum_point from $g4[point_table] ");
                echo "&nbsp;전체 포인트 합계 : " . number_format($row2[sum_point]) . "점)";
                ?>
            </span>
        </div>
    </div>

    <table width=100% class="table table-hover" style="word-wrap:break-word;">
    <tr class='success'>
        <td width=80>회원아이디</td>
        <td width=80>별명</td>
        <td width=80>일시</td>
        <td>포인트 내용</td>
        <td width=60>포인트</td>
    </tr>
    <?
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        $mb = get_member($row['mb_id']);
        $mb_nick = get_sideview($row['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']);
    
        $link1 = $link2 = "";
        if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table'])
        {
            $link1 = "<a href='$g4[bbs_path]/board.php?bo_table=$row[po_rel_table]&wr_id=$row[po_rel_id]' target=_blank>";
            $link2 = "</a>";
        }
    
        $po_datetime = get_datetime($row[po_datetime]);

        echo "
        <tr>
            <td><a href='./point_list.php?sfl=mb_id&stx=$row[mb_id]'>$row[mb_id]</a></td>
            <td>$mb_nick</td>
            <td>$po_datetime</td>
            <td>{$link1}$row[po_content]{$link2}</td>
            <td>".number_format($row['po_point'])."&nbsp;</td>
        </tr> ";
    } 
    
    if ($i == 0)
        echo "<tr><td colspan='5' align=center height=100>자료가 없습니다.</td></tr>";

    echo "</table>";
    ?>
</div>
</div> <!-- 1st row -->

<div class="row-fluid row"> <!-- 2nd row -->
<div class="col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href='<?=$g4[bbs_path]?>/new.php' target="_blank">최근게시물</a>
        </div>
    </div>

    <table width=100% class="table table-hover" style="word-wrap:break-word;">
    <tr class='success'>
        <td width=100>게시판</td>
        <td>제목</td>
        <td width=80>별명</td>
        <td width=80>일시</td>
    </tr>
    <?
    // 최근 게시물 $new_write_rows 건을 구합니다
    $sql_common = " from $g4[board_new_table] ";
    $sql_common .= " where wr_is_comment = '0' ";
    $sql_order = " order by bn_id desc ";
    
    $sql = " select *
              $sql_common
              $sql_order
              limit $new_write_rows ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        $tmp_write_table = $g4['write_prefix'] . $row['bo_table'];
    
        $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '$row[wr_id]' ");
        if (!$row2)
            continue;
    
        $name = get_sideview($row2['mb_id'], cut_str($row2['wr_name'], $config['cf_cut_name']), $row2['wr_email'], $row2['wr_homepage']);
        $datetime = get_datetime($row2['wr_datetime']);
        $bo = get_board($row[bo_table], "bo_subject");
        $bo_subject = cut_str($bo[bo_subject], 20);
    
        echo "
        <tr>
            <td><a href='$g4[bbs_path]/board.php?bo_table=$row[bo_table]'>".$bo_subject."</a></td>
            <td ><a href='$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row2[wr_id]' target=_new>".conv_subject($row2['wr_subject'], 40)."</a></td>
            <td>$name</td>
            <td>$datetime</td>
        </tr> ";  
    }
    
    if ($i == 0)
        echo "<tr><td colspan='4' align=center height=100>자료가 없습니다.</td></tr>";
    echo "</table>";
    ?>
</div>
<div class="col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href='<?=$g4[bbs_path]?>/new.php' target="_blank">최근코멘트</a>
        </div>
    </div>

    <table width=100% class="table table-hover" style="word-wrap:break-word;">
    <tr class='success'>
        <td width=100>게시판</td>
        <td>제목</td>
        <td width=80>별명</td>
        <td width=80>일시</td>
    </tr>
    <?
    // 최근 게시물 $new_write_rows 건을 구합니다
    $sql_common = " from $g4[board_new_table] ";
    $sql_common .= " where wr_is_comment = '1' ";
    $sql_order = " order by bn_id desc ";
    
    $sql = " select *
              $sql_common
              $sql_order
              limit $new_write_rows ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        $tmp_write_table = $g4['write_prefix'] . $row['bo_table'];

        // 원글의 정보
        $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '$row[wr_parent]' ");
        if (!$row2)
            continue;

        // 코멘트의 정보
        $row3 = sql_fetch(" select * from $tmp_write_table where wr_id = '$row[wr_id]' ");
        if (!$row3)
            continue;
    
        $name = get_sideview($row3['mb_id'], cut_str($row3['wr_name'], $config['cf_cut_name']), $row3['wr_email'], $row3['wr_homepage']);
        $datetime = get_datetime($row3['wr_datetime']);
        $bo = get_board($row[bo_table], "bo_subject");
        $bo_subject = cut_str($bo[bo_subject], 20);
    
        echo "
        <tr>
            <td><a href='$g4[bbs_path]/board.php?bo_table=$row[bo_table]'>".$bo_subject."</a></td>
            <td ><a href='$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row2[wr_id]#c_$row[wr_id]' target=_new>".conv_subject($row2['wr_subject'], 40)."</a></td>
            <td>$name</td>
            <td>$datetime</td>
        </tr> ";  
    }
    
    if ($i == 0)
        echo "<tr><td colspan='4' align=center height=100>자료가 없습니다.</td></tr>";
    echo "</table>";
    ?>
</div>
</div> <!-- 2nd row -->

<?
include_once ("./admin.tail.php");
?>
