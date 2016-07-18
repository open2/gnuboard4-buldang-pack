<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><a href='<?=$skin_title_link?>'><?=$skin_title?></a></strong>
        <a class="pull-right" href='<?=$skin_title_link?>'><small>more</small></a>
    </div>
    <div class="panel-body">
        <ul class="list-unstyled">
            <? for ($i=0; $i<count($list); $i++) { ?>
            <li>
            <?
            if ($list[$i][icon_reply])
                echo "<i class=\"fa fa-reply fa-rotate-180\"></i> ";

            echo "<a href='{$list[$i]['href']}'>";
            if ($list[$i]['is_notice'])
                echo "<strong>{$list[$i]['subject']}</strong>";
            else
                echo "{$list[$i]['subject']}";
            echo "</a>";

            if ($list[$i]['comment_cnt']) 
                echo " <a href=\"{$list[$i]['comment_href']}\"><small>{$list[$i]['comment_cnt']}</small></a>";

            if ($list[$i]['icon_new']) echo "&nbsp;<i class=\"fa fa-bell-o\"></i>";
            if ($list[$i]['icon_file'])echo "&nbsp;<i class=\"fa fa-file-o\"></i>";
            if ($list[$i]['icon_link'])echo "&nbsp;<i class=\"fa fa-link\"></i>";
            if ($list[$i]['icon_hot'])echo "&nbsp;<i class=\"fa fa-fire\"></i>";
            if ($list[$i]['icon_secret'])echo "&nbsp;<i class=\"fa fa-lock\"></i>";
            ?>
            </li>
            <? } ?>
        </ul>

        <? if (count($list) == 0) { ?><ul><li><strong>게시물이 없습니다.</strong></li></ul><? } ?>
            
    </div>
</div>
