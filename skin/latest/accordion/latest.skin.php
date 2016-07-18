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

if ($content_len ==0)
    $content_len = 250;

//id를 랜덤하게 만듭니다. 몇개의 아코디언이 있을 때 쫑나지 않게...
$rand1 = rand();
?>
<div class="panel-group" id="accordion_<?=$rand1?>">
    <?
    if (count($list) == 0) {
        echo "<div style='height:200px;'><a href='#'>내용없슴</a></div>";
    } else {
        // 랜덤하게 아코디언을 열어줍니다
        $open_in = rand(0, count($list)-1);

        // 오픈되는 배열을 가장 위로 올린다 (선택된 것을 0으로 올리고, 0부터 선택된거 위까지 밀어 내린다)
        $tmp = $list[$open_in];
        for ($i=$open_in; $i>0; $i--) {
            $list[$i] = $list[$i-1];
        }
        $list[0] = $tmp;

        for ($i=0; $i<count($list); $i++) {
    ?>
        <!-- margin-bottom:-6px는 css마다 다르므로... 알아서 수정해주세요 -->
        <div class="panel panel-default" style="margin-bottom:-6px;">
        <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#accordion_<?=$rand1?>" href="#collapse_<?=$rand1?>_<?=$i?>">
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
            <!--
            <span class="pull-right" style="margin-top:-8px;">
                <a class="btn" href='<?=$list[$i][href]?>' onfocus='this.blur()' title='<?=$list_title?>' <?=$target_link?>>
                <font color=gray>more</font>
                </a>
            </span>
            -->
        </div>
        <?
        // 처음나오는 것을 open... in이 class에 들어가면 열립니다
        if ($i == 0)
            $in = "in";
        else
            $in = "";
        ?>
        <div id="collapse_<?=$rand1?>_<?=$i?>" class="panel-collapse collapse <?=$in?>" >
            <a style="text-decoration:none;" href='<?=$list[$i][href]?>' onfocus='this.blur()' title='<?=$list_title?>' <?=$target_link?>>
            <div class="panel-body" style="word-break:break-all;">
                <?=mb_strcut(strip_tags($list[$i][wr_content]),0, $content_len)?>
            </div>
            </a>
        </div>
    </div>
    <?
        }
    }
    ?>
</div>
