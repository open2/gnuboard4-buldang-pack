<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="panel panel-default">
  <div class="panel-body">
      <ul class="list-unstyled">
    	<li> 오늘 <span><?=number_format($visit[1])?><? if ($is_admin == "super") { ?>&nbsp;&nbsp;<a href="<?=$g4['admin_path']?>/visit_list.php"><i class="fa fa-cog"></i></a><?}?></li>
    	<li> 어제 <span><?=number_format($visit[2])?></li>
    	<li> 최대 <span><?=number_format($visit[3])?></li>
    	<li> 전체 <span><?=number_format($visit[4])?></li>
      </li>
</ul>
  </div>
</div>