<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

for ($i=0; $i<count($list); $i++) {

    // cookie 정보를 읽어서, done인 경우에는 출력을 하지 않고 통과합니다. 불필요한 창을 여는 것은 낭비입니다.
    if (!$cate) 
        $cate = "popup9125_"; // $cate가 없는 경우 html 페이지의 이름과 겹치는 경우가 발생해서 임의로 값을 넣어줍니다.
    $popup_id = $cate . $list[$i]['wr_id'];
    $cookie_name = "divpop_" . $popup_id;
    $chkbox_id = "chkbox_" . $popup_id;
    $writeContents_id = "writeContents_" . $popup_id;

    if ($_COOKIE[$cookie_name] == "done")
        continue;
?>

    <div class="container" id="divpop_<?=$popup_id?>">
    <div class="alert alert-success" id="<?=$writeContents_id?>">
        <?
        $wr_content = $list[$i]['wr_content'];
    		if (!strstr($list[$i]['wr_option'], "html1")) {
		        $html = 0;
            $wr_content = conv_content($wr_content, $html);
        }
        echo $wr_content;
        ?>
    <a id="checkbox_<?=$popup_id?>" class="close" data-dismiss="alert" href="#" aria-hidden="true" onclick="hideMe_x('<?=$popup_id?>', 7);"><i class="fa fa-times"></i></a>
    </div>
    </div>
<? } // end of for loop ?>
<script type="text/javascript">
<!--
function hideMe_x(popupid, days){
  	set_cookie( 'divpop_'+popupid, "done", days*24, g4_cookie_domain);
  	$('#writeContents_'+popupid).alert('close');
}
-->
</script>