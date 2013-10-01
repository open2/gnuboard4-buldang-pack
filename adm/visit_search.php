<?php 
$sub_menu = "200810";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$search_sort = $_GET['search_sort'];

$g4['title'] = "접속자검색";
include_once("./admin.head.php");

include_once("$g4[path]/lib/visit.lib.php");

$qstr = "search_word=$search_word&search_sort=$search_sort"; //페이징 처리관련 변수

$colspan = 5;

$listall = "<a href='{$_SERVER['PHP_SELF']}' class=tt>처음</a>"; //페이지 처음으로 (초기화용도)
?>

<!-- 달력 datepicker 시작 -->
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<style>
.ui-datepicker { font:12px dotum }
.ui-datepicker select.ui-datepicker-month, 
.ui-datepicker select.ui-datepicker-year { width: 70px;}
.ui-datepicker-trigger { margin:0 0 -5px 2px }
.search_sort {width:100px;vertical-align:middle}
.ed {vertical-align:middle}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script type="text/javascript">
jQuery(function($){
    $.datepicker.regional["ko"] = { 
        closeText: "닫기", 
        prevText: "이전달", 
        nextText: "다음달", 
        currentText: "오늘", 
        monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"], 
        monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"], 
        dayNames: ["일","월","화","수","목","금","토"], 
        dayNamesShort: ["일","월","화","수","목","금","토"], 
        dayNamesMin: ["일","월","화","수","목","금","토"], 
        weekHeader: "Wk", 
        dateFormat: "yymmdd", 
        firstDay: 0, 
        isRTL: false, 
        showMonthAfterYear: true, 
        yearSuffix: ""
    };
    $.datepicker.setDefaults($.datepicker.regional["ko"]);
});
</script>
<!-- 달력 datepicker 끝 -->

<table width="100%" cellpadding="3" cellspacing="1">
<form name="fvisit" method="get">
<tr>
    <td class="sch_wrp">
        <?=$listall?>
        <label for="sch_sort">검색분류</label>
        <select name="search_sort" id="sch_sort" class="search_sort">
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
        <input type="text" name="search_word" size="20" value="<?=$search_word?>" id="sch_word" class="ed">
        <input type="image" src="<?=$g4['admin_path']?>/img/btn_search.gif" alt="검색" align="absmiddle" onclick="fvisit_submit('visit_search.php');">
    </td>
</tr>
</form>
</table>

<table width="100%" cellpadding="0" cellspacing="1" border="0">
<colgroup width="100">
<colgroup width="350">
<colgroup width="100">
<colgroup width="100">
<colgroup width="">
<tr><td colspan="<?=$colspan?>" class="line1"></td></tr>
<tr class="bgcol1 bold col1 ht center">
    <td>IP</td>
    <td>접속 경로</td>
    <td>브라우저</td>
    <td>OS</td>
    <td>일시</td>
</tr>
<tr><td colspan="<?=$colspan?>" class="line2"></td></tr>
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

        /*
        $referer = $row['vi_referer'];
        $referer = htmlspecialchars($referer);
        */
        $referer = get_text(cut_str($row[vi_referer], 255, ""));
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

    $list = ($i%2);
    echo "
    <tr class='list$list col1 ht center'>
        <td align='left'>&nbsp;<a href='{$_SERVER['PHP_SELF']}?search_sort=vi_ip&amp;search_word=$ip'>$ip</a></td>
        <td align=left><nobr style='display:block; overflow:hidden; width:350;'>$link$title</a></nobr></td>
        <td>$brow</td>
        <td>$os</td>
        <td><a href='{$_SERVER['PHP_SELF']}?search_sort=vi_date&amp;search_word={$row['vi_date']}'>$row[vi_date]</a> $row[vi_time]</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='$colspan' height=100 align=center>자료가 없습니다.</td></tr>"; 

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$page = get_paging($config['cf_write_pages'], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&domain=$domain&page=");
if ($page) {
    echo "<table width=100% cellpadding=3 cellspacing=1><tr><td align=right>$page</td></tr></table>";
}
?>

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
