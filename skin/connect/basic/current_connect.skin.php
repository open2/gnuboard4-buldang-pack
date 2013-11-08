<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<!--ui object -->
<table class="table table-hover">
<colgroup>
<col width="80px">
<col width="120px">
<col>
</colgroup>
<thead>
    <tr class="success">
    <th>번 호</th>
    <th>이 름</th>
    <th>링 크</th>
    </tr>
</thead>
<tbody>
<?
for ($i=0; $i<count($list); $i++) {

    echo "<tr>";

    echo "<td>{$list[$i][num]}</td>";
    echo "<td>{$list[$i][name]}</td>";

    $location = $list[$i][lo_location];

    // bot을 구분 합니다.
    $bot = "";
    if (preg_match('/Googlebot/', $list[$i][lo_agent]))
        $bot = "Google-bot";
    else if (preg_match('/bingbot/', $list[$i][lo_agent]))
        $bot = "bingbot";
    else if (preg_match('/Yeti/', $list[$i][lo_agent]))
        $bot = "Naver-bot";
    else if (preg_match('/Daumoa/', $list[$i][lo_agent]))
        $bot = "Daum-bot";

    // 최고관리자에게만 허용
    // 이 조건문은 가능한 변경하지 마십시오.
    if ($bot)
        echo "<td style='text-align:left'>&nbsp;{$bot}</td>";
    else if ($is_admin == "super" && $list[$i][lo_url])
            echo "<td style='text-align:left'>&nbsp;<a href='{$list[$i][lo_url]}'>{$location}</a></td>";
    else
        echo "<td  style='text-align:left'>&nbsp;{$location}</td>";

    echo "</tr>";

}

if ($i == 0)
    echo "<tr><td colspan=3 height=50 align=center>현재 접속자가 없습니다.</td></tr>";
if ($write_pages) {
    $write_pages = str_replace("처음", "&laquo 처음", $write_pages);
    $write_pages = str_replace("맨끝", "맨끝 &raquo", $write_pages);

    echo "<tr><td colspan=3 height=30 align=center>";
    echo "<ul class='pagination'>";
    echo $write_pages;
    echo "</ul></td></tr>";
}
?>
</tbody>
</table>
