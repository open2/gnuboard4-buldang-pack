<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

if (!$skin_title) {
    if ($board['bo_subject']) {
        $skin_title = $board['bo_subject'];
        $skin_title_link = "$g4[path]/$bo_table";
    } else {
        $skin_title = "최신글";
    }
}
?>
<div class="panel panel-default">
<div class="panel-heading">
    <a href='<?=$skin_title_link?>' onfocus='this.blur()'><?=$skin_title?></a>
  	<a class="pull-right" href='<?=$skin_title_link?>' onfocus='this.blur()'>more</a>
</div>
<div class="panel-body" style="overflow:hidden">
  	<ul class="list-unstyled">
    <?
    if (count($list) == 0) {
        echo "<li><a href='#'>내용없슴</a></li>";
    } else {
        for ($i=0; $i<count($list); $i++) { 
  
            echo "<li>";

            echo "<a href='{$list[$i][href]}' onfocus='this.blur()' title='{$list[$i][subject]}' {$target_link}>";

            if ($list[$i][icon_secret])
                echo "<i class=\"fa fa-lock\"></i> ";
  
            if ($list[$i][icon_reply])
                echo "<i class=\"fa fa-reply fa-rotate-180\"></i> ";

            if ($list[$i][bo_name])
                echo "<font color='gray'>" . $list[$i][bo_name] . "</font> : ";

            if ($list[$i][is_notice])
                echo "<strong>" . $list[$i][subject] . "</strong>";
            else
                echo $list[$i][subject];

  	        if ($list[$i][comment_cnt])
	              echo " <small>" . $list[$i][comment_cnt] . "</small> ";
  
            if ($list[$i][icon_new])
                echo "  <i class=\"fa fa-pagelines\" style='color:gray'></i>";

            echo "</a>";
            echo "</li>";
        }
    }
    // fill이 true이고, 덜 채워지면 꽉 채워준다.
    if (is_array($options) && $options['fill'] && $i < $rows) {
          for ($j=$i; $j<$rows;$j++) {
              echo "<li><span class='bu'></span> ";
              echo "<a href='#'><font style='font-family:돋움; font-size:9pt; color:#6A6A6A;'>&nbsp;</font></a></li>";
          }
    }
    ?>
    </ul>
</div>
</div>