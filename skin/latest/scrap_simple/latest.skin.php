<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div style="margin:3px 10px 0px 0px;">
	  <ul class="list-unstyled">
    <?
    if (count($list) == 0) {
        echo "<li><a href='#'>내용없슴</a></li>";
    } else {
        for ($i=0; $i<count($list); $i++) { 

          echo "<li>";
          echo "<a href='{$list[$i][href]}' onfocus='this.blur()' title='{$list_title}' {$target_link}>";

          if ($list[$i][icon_secret])
              echo "<i class=\"fa fa-lock\"></i> ";

          if ($list[$i][bo_name])
              $list_title = $list[$i][bo_name] . " : " . $list[$i][subject] . " (". $list[$i][datetime] . ")" ;
          else
              $list_title = $list[$i][subject]  . " (". $list[$i][datetime] . ")" ;

          if ($list[$i][icon_reply])
              echo "<i class=\"fa fa-reply fa-rotate-180\"></i> ";

          if ($list[$i][comment_cnt])
              echo " <small>{$list[$i][comment_cnt]}</small> ";

          if ($list[$i][is_notice])
              echo "<strong>" . $list[$i][subject] . "</strong>";
          else
              echo $list[$i][subject];

          if ($list[$i][icon_new])
              echo " <i class=\"fa fa-bell-o\"></i>";

          echo "</a>";
          echo "</li>";
      }
  }
  // fill이 true이고, 덜 채워지면 꽉 채워준다.
  if ($options && $options['fill'] && $i < $rows) {
        for ($j=$i; $j<$rows;$j++) {
            echo "<li><a href='#'>&nbsp;</a></li>";
        }
  }
  ?>
  </ul>
</div>
