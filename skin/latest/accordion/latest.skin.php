<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

if (!$skin_title) {
    if ($board[bo_subject]) {
        $skin_title = $board[bo_subject];
        $skin_title_link = "$g4[bbs_path]/board.php?bo_table=$bo_table";
    } else {
        $skin_title = "최신글";
    }
}
?>
<div class="panel-group" id="accordion">
    <?
    if (count($list) == 0) {
        echo "<div style='height:200px;'><a href='#'>내용없슴</a></div>";
    } else {
        // 랜덤하게 아코디언을 열어줍니다
        $open_in = rand(0, count($list)-1);

        // 오픈되는 배열을 가장 위로 올린다
        $tmp = $list[0];
        $list[0] = $list[$open_in];
        $list[$open_in] = $tmp;

        for ($i=0; $i<count($list); $i++) { 
    ?>
        <!-- margin-bottom:-6px는 css마다 다르므로... 알아서 수정해주세요 -->
        <div class="panel panel-default" style="margin-bottom:-6px;">
        <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?=$i?>">
            <?
            if ($list[$i][icon_secret])
                echo "<i class=\"fa fa-lock\"></i> ";
  
            if ($list[$i][bo_name])
                $list_title = $list[$i][bo_name] . " : " . $list[$i][subject] . " (". $list[$i][datetime] . ")" ;
            else
                $list_title = $list[$i][subject]  . " (". $list[$i][datetime] . ")" ;
  
            if ($list[$i][icon_reply])
                echo "<i class=\"fa fa-reply fa-rotate-180\"></i> ";

  	        if ($list[$i][comment_cnt])
	              echo " <small>" . $list[$i][comment_cnt] . "</small> ";
  
            if ($list[$i][is_notice])
                echo "<strong>" . $list[$i][subject] . "</strong>";
            else
                echo $list[$i][subject];
  
            if ($list[$i][icon_new])
                echo "  <i class=\"fa fa-bell-o\"></i>";
            ?>
            </a>
            <span class="pull-right">
                <a href='<?=$list[$i][href]?>' onfocus='this.blur()' title='<?=$list_title?>' <?=$target_link?>>
                <i class="fa fa-external-link"></i>
                </a>
            </span>
        </div>
        <?
        // 처음나오는 것을 open... in이 class에 들어가면 열립니다
        if ($i == 0)
            $in = "in";
        else
            $in = "";
        ?>
        <div id="collapse_<?=$i?>" class="panel-collapse collapse <?=$in?>" >
            <div class="panel-body">
                <?=cut_str(strip_tags($list[$i][wr_content]),250)?>
            </div>
        </div>
    </div>
    <?
        }
    }
    ?>
</div>