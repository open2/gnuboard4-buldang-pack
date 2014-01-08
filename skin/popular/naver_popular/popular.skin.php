<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="panel panel-default">
    <div class="panel-body">
    <ol class="list-unstyled">
    <?
    if (count($npop) == 0) {
          echo "<li></span> 내용없슴</li>";
    } else {
        for ($i=0; $i<count($npop); $i++) { 
            $j = $i+1;
            $rank = $npop[$i][S] . $npop[$i][V];;
            if ($i < 3)
                $bs = "label-success";
            else
                $bs = "label-default";
            echo "<li>";
            echo "&nbsp;<a href='{$npop[$i][LINK]}' onfocus='this.blur()' title='{$npop[$i][K]}' target=new>";
            echo $npop[$i][K];
            echo "</a>";
            echo "<span class='label $bs pull-left'>$j</span>";
            echo "<span class='pull-right'>$rank</span>";
            echo "</li>";
        }
    }
    ?>
    </ol>
    </div>
</div>