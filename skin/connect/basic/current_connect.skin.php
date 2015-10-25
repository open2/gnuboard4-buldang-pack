<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<!--ui object -->
<div class="well well-sm" style="margin-bottom:0px;"><strong><a href="<?=$_SERVER[PHP_SELF]?>">����������</a></strong></div>

<table class="table table-hover" width=100%>
<thead>
<tr class="success">
    <th class="col-sm-1 hidden-xs">�� ȣ</th>
    <th class="col-sm-2 col-xs-2">�� ��</th>
    <th>�� ũ</th>
</tr>
</thead>
<tbody>
<?
for ($i=0; $i<count($list); $i++) {

    echo "<tr>";

    echo "<td class=\"hidden-xs\">{$list[$i][num]}</td>";
    echo "<td>{$list[$i][name]}</td>";

    $location = $list[$i][lo_location];

    // bot�� ���� �մϴ�.
    $bot = "";
    if (preg_match('/Googlebot/', $list[$i][lo_agent]))
        $bot = "Google-bot";
    else if (preg_match('/bingbot/', $list[$i][lo_agent]))
        $bot = "bingbot";
    else if (preg_match('/Yeti/', $list[$i][lo_agent]))
        $bot = "Naver-bot";
    else if (preg_match('/Daumoa/', $list[$i][lo_agent]))
        $bot = "Daum-bot";

    // �ְ�����ڿ��Ը� ���
    // �� ���ǹ��� ������ �������� ���ʽÿ�.
    if ($bot)
        echo "<td style='text-align:left'>&nbsp;{$bot}</td>";
    else if ($is_admin == "super" && $list[$i][lo_url])
            echo "<td style='text-align:left'>&nbsp;<a href='{$list[$i][lo_url]}'>{$location}</a></td>";
    else
        echo "<td  style='text-align:left'>&nbsp;{$location}</td>";

    echo "</tr>";

}

if ($i == 0)
    echo "<tr><td colspan=3 height=50 align=center>���� �����ڰ� �����ϴ�.</td></tr>";
if ($write_pages) {
    $write_pages = str_replace("ó��", "&laquo ó��", $write_pages);
    $write_pages = str_replace("�ǳ�", "�ǳ� &raquo", $write_pages);

    echo "<tr><td colspan=3 height=30 align=center>";
    echo "<ul class='pagination'>";
    echo $write_pages;
    echo "</ul></td></tr>";
}
?>
</tbody>
</table>
