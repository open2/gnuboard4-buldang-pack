<?php 
$sub_menu = "200810";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$search_sort = $_GET['search_sort'];

$g4['title'] = "접속자검색";
include_once("./admin.head.php");

include_once("$g4[path]/lib/visit.lib.php");

$qstr = "search_word=$search_word&search_sort=$search_sort"; //페이징 처리관련 변수

$listall = "<a href='{$_SERVER['PHP_SELF']}' class=tt>처음</a>"; //페이지 처음으로 (초기화용도)
?>

<form name="fvisit" method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?>
</div>
<div class="pull-right">
    <select name="search_sort" id="sch_sort" class="form-control">
        <?php 
        //echo '<option value="vi_ip" '.($search_sort=='vi_ip'?'selected="selected"':'').'>IP</option>'; //selected 추가
        if($search_sort=='vi_ip'){ //select 안의 옵셥값이 vi_ip면
            echo '<option value="vi_ip" selected="selected">IP</option>'; //selected 추가
        }else{
            echo '<option value="vi_ip">IP</option>';
        }
        if($search_sort=='vi_referer'){ //select 안의 옵셥값이 vi_referer면
            echo '<option value="vi_referer" selected="selected">접속경로</option>'; //selected 추가
        }else{
            echo '<option value="vi_referer">접속경로</option>';
        }
        if($search_sort=='vi_date'){ //select 안의 옵셥값이 vi_date면
            echo '<option value="vi_date" selected="selected">날짜</option>'; //selected 추가
        }else{
            echo '<option value="vi_date">날짜</option>';
        }
        ?>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<table width="100%" class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width="100">
<colgroup width="100">
<colgroup width="100">
<colgroup width="80">
<colgroup width="">
<tr class="success">
    <td>IP</td>
    <td>브라우저</td>
    <td>OS</td>
    <td>일시</td>
    <td>접속 경로</td>
</tr>
<?php 
$sql_common = " from {$g4['visit_table']} ";
if ($search_sort) {
    if($search_sort=='vi_ip' || $search_sort=='vi_date'){
        $sql_search = " where $search_sort like '$search_word%' ";
    }else{
        $sql_search = " where $search_sort like '%$search_word%' ";
    }
}
$sql = " select count(*) as cnt
         $sql_common 
         $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page == "") $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * 
          $sql_common
          $sql_search
          order by vi_id desc
          limit $from_record, $rows ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) {
    $brow = get_brow($row['vi_agent']);
    $os   = get_os($row['vi_agent']);

    $link = "";
    $referer = "";
    $title = "";
    if ($row['vi_referer']) {

        $referer = get_text(cut_str($row[vi_referer], 80, ""));
        $referer = urldecode($referer);

        if (strtolower($g4['charset']) == 'utf-8') {
            if (!is_utf8($referer)) {
                $referer = iconv('euc-kr', 'utf-8', $referer);
            }
        }
        else {
            if (is_utf8($referer)) {
                $referer = iconv('utf-8', 'euc-kr', $referer);
            }
        }

        $title = str_replace(array("<", ">"), array("&lt;", "&gt;"), $referer);
        $link = "<a href='$row[vi_referer]' target=_blank title='$title '>";
    }

    if ($is_admin == 'super')
        $ip = $row['vi_ip'];
    else
        $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.♡.\\3.\\4", $row['vi_ip']);

    if ($brow == '기타') { $brow = "<span title='$row[vi_agent]'>$brow</span>"; }
    if ($os == '기타') { $os = "<span title='$row[vi_agent]'>$os</span>"; }

    $vi_datetime = $row[vi_date] . " " . $row[vi_time];

    echo "
    <tr class='list$list col1 ht center'>
        <td>&nbsp;<a href='{$_SERVER['PHP_SELF']}?search_sort=vi_ip&amp;search_word=$ip'>$ip</a></td>
        <td>$brow</td>
        <td>$os</td>
        <td><a href='{$_SERVER['PHP_SELF']}?search_sort=vi_date&amp;search_word={$row['vi_date']}'>" . get_datetime($vi_datetime) . "</a></td>
        <td><nobr style='display:block; overflow:hidden; width:350;'>$link$title</a></nobr></td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='5' height=100 align=center>자료가 없습니다.</td></tr>"; 

echo "</table>";
?>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<script type='text/javascript'>
$(function(){
    $("#sch_sort").change(function(){ // select #sch_sort의 옵션이 바뀔때
        if($(this).val()=="vi_date"){ // 해당 value 값이 vi_date이면
            $("#sch_word").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" }); // datepicker 실행
        }else{ // 아니라면
            $("#sch_word").datepicker("destroy"); // datepicker 미실행
        }
    });
    if($("#sch_sort option:selected").val()=="vi_date"){ // select #sch_sort 의 옵션중 selected 된것의 값이 vi_date라면
        $("#sch_word").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" }); // datepicker 실행
    }
});

function fvisit_submit(act) 
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>

<?php 
include_once("./admin.tail.php");
?>
