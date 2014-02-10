<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 스킨에서 사용하는 lib 읽어들이기
include_once("$g4[path]/lib/view.skin.lib.php");
?>

<div width="<?=$width?>" id="view_<?=$wr_id?>">

<!-- 링크 버튼 -->
<? ob_start(); // 한번 만들어서 두번 씁니다 ?>
<div id="view_top">
    <div class="btn-group">
        <? if ($search_href) { echo "<a href=\"$search_href\" class=\"btn btn-default btn-sm btn-search\">검색</a> "; } ?>
        <? echo "<a href=\"$list_href\" class=\"btn btn-default btn-sm btn-list\">목록</a> "; ?>
    </div>
    <div class="btn-group">
        <? if ($write_href) { echo "<a href=\"$write_href\" class=\"btn btn-default btn-sm btn-write\">쓰기</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($reply_href) { echo "<a href=\"$reply_href\" class=\"btn btn-default btn-sm btn-reply\">답변</a> "; } ?>
        <? if ($update_href) { echo "<a href=\"$update_href\" class=\"btn btn-default btn-sm btn-modify\">수정</a> "; } ?>
        <? if ($delete_href) { echo "<a href=\"$delete_href\" class=\"btn btn-default btn-sm btn-delete\">삭제</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($good_href) { echo "<a href=\"$good_href\" target='hiddenframe' class=\"btn btn-default btn-sm btn-good\">추천</a> "; } ?>
        <? if ($nogood_href) { echo "<a href=\"$nogood_href\" target='hiddenframe' class=\"btn btn-default btn-sm btn-nogood\">비추천</a> "; } ?>
        <? if ($scrap_href) { echo "<a href=\"javascript:;\" onclick=\"win_scrap('$scrap_href');\" class=\"btn btn-default btn-sm btn-scrap\">스크랩</a> "; } ?>
        <? if ($nosecret_href) { echo "<a href=\"$nosecret_href\" class=\"btn btn-default btn-sm btn-nosecret\">비밀글해제</a> "; } ?>
        <? if ($secret_href) { echo "<a href=\"$secret_href\" class=\"btn btn-default btn-sm btn-secret\">비밀글</a> "; } ?>
    </div>
    <div class="btn-group pull-right">
        <? if ($prev_href) { echo "<a href=\"$prev_href\" title=\"$prev_wr_subject\" class=\"btn btn-default btn-sm btn-prev\">이전글</a>"; } ?>
        <? if ($next_href) { echo "<a href=\"$next_href\" title=\"$next_wr_subject\" class=\"btn btn-default btn-sm btn-next\">다음글</a>"; } ?>
 	      <a href="javascript:scaleFont(+1);" class="btn btn-default btn-sm btn-zoom-in"><span class="glyphicon glyphicon-zoom-in"></span></a>
        <a href="javascript:scaleFont(-1);" class="btn btn-default btn-sm btn-zoom-out"><span class="glyphicon glyphicon-zoom-out"></span></a>
        <a href="#commentContents" class="btn btn-default btn-sm">코멘트</a>
        <? echo "<a href=\"$list_href\" class=\"btn btn-default btn-sm btn-list\">목록</a> "; ?>
    </div>
    <div class="btn-group hidden-xs hidden-sm  pull-right">
        <? if ($copy_href) { echo "<a href=\"$copy_href\" class=\"btn btn-default btn-sm btn-copy\">복사</a> "; } ?>
        <? if ($move_href) { echo "<a href=\"$move_href\" class=\"btn btn-default btn-sm btn-move\">이동</a> "; } ?>
        <? if ($now_href) { echo "<a href=\"$now_href\" class=\"btn btn-default btn-sm btn-update\">갱신</a> "; } ?>
    </div>
</div>
<?
$link_buttons = ob_get_contents();
ob_end_flush();
?>

<div id="view_header" class="panel panel-default">
<div class="panel-heading">
    <p class="pull-right">
        <?if ($singo_href) { ?><a class="btn btn-default btn-xs" href="javascript:win_singo('<?=$singo_href?>');">신고</a><?}?>
        <?if ($unsingo_href) { ?><a class="btn btn-default btn-xs" href="javascript:win_unsingo('<?=$unsingo_href?>');">신고취소</a><?}?>
		</p>
		<p>
        <? if ($is_category) { echo ($category_name ? "[$view[ca_name]] " : ""); } ?>
        <strong><?=cut_hangul_last(get_text($view[wr_subject]))?></strong>
		</p>
		<p>
        <?=$view[name]?><? if ($is_ip_view) { echo "&nbsp;($ip)"; } ?>&nbsp;&nbsp;
	  		<?php echo substr($view['wr_datetime'], 2, 14); ?>&nbsp;&nbsp;
        조회 <?=$view[wr_hit]?>&nbsp;&nbsp;
        <? if ($is_good) { ?><font style="color:#BABABA;">추천</font> <font style="color:#BABABA;"> <?=$view[wr_good]?>&nbsp;&nbsp;&nbsp;&nbsp;</font><?}?>
        <? if ($is_nogood) { ?><font style="color:#BABABA;">비추천</font> <font style="color:#BABABA;"> <?=$view[wr_nogood]?>&nbsp;&nbsp;&nbsp;&nbsp;</font><?}?>
    <!-- 게시글 주소를 복사하기 쉽게 하기 위해서 아랫 부분을 삽입 -->
    <span class="pull-right">
    <a href="javascript:clipboard_trackback('<?=$posting_url?>');" style="letter-spacing:0;" title='이 글을 소개할 때는 이 주소를 사용하세요'><i class="fa fa-link"></i></a>
    <? if ($g4[use_bitly]) { ?>
        <? if ($view[bitly_url]) { ?>
        &nbsp;<span id="bitly_url"><a href=<?=$view[bitly_url]?> target=new><?=$view[bitly_url]?></a></span>
        <? } else { ?>
        &nbsp;<span id="bitly_url"></span>
        <script type="text/javascript">
        // encode 된 것을 넘겨주면, 알아서 decode해서 결과를 return 해준다.
        // encode 하기 전의 url이 있어야 결과를 꺼낼 수 있기 때문에, 결국 2개를 넘겨준다.
        // 왜? java script에서는 urlencode, urldecode가 없으니까. ㅎㅎ
        // 글쿠 이거는 마지막에 해야 한다. 왜??? 그래야 정보를 html page에 업데이트 하쥐~!
        get_bitly_g4('#bitly_url', '<?=$bo_table?>', '<?=$wr_id?>');
        </script>
        <?}?>
    <?}?>
    </span>
    </p>

    <p>
        <?
        // 가변 파일
        $cnt = 0;
        for ($i=0; $i<count($view[file]); $i++) {
            if ($view[file][$i][source] && !$view[file][$i][view]) {
                $cnt++;
                echo "<i class=\"fa fa-file-o\" title='attached file'></i> <a href=\"javascript:file_download('{$view[file][$i][href]}', '{$view[file][$i][source]}');\" title='{$view[file][$i][content]}'><font style='normal 11px 돋움;'>{$view[file][$i][source]} ({$view[file][$i][size]}), Down : {$view[file][$i][download]}, {$view[file][$i][datetime]}</font></a><br>";
            }
        }

        // 링크
        $cnt = 0;
        for ($i=1; $i<=$g4[link_count]; $i++) {
            if ($view[link][$i]) {
                $cnt++;
                $link = cut_str($view[link][$i], 70);
                echo "<a href='{$view[link_href][$i]}' target=_blank>{$link} ({$view[link_hit][$i]})</a>";
            }
        }
    ?>
    </p>
</div>

<div id="view_main" class="panel-body">

    <?
    // 파일 출력
    for ($i=0; $i<=$view[file][count]; $i++) {
        if ($view[file][$i][view]) {
            echo resize_dica($view[file][$i][view],250,300) . "<p>" . $view[file][$i][content] . "</p>";
        }
    }

    // 신고된 게시글의 이미지를 선택하여 출력하기
    if ($view['wr_singo'] and trim($file_viewer)) {
        $singo = "<div id='singo_file_title{$view[wr_id]}' class='singo_title'><font color=gray>신고가 접수된 게시물입니다. ";
        $singo .= "<span class='singo_here' style='cursor:pointer;font-weight:bold;' onclick=\"document.getElementById('singo_file{$view[wr_id]}').style.display=(document.getElementById('singo_file{$view[wr_id]}').style.display=='none'?'':'none');\"><font color=red>여기</font></span>를 클릭하시면 첨부 이미지를 볼 수 있습니다.</font></div>";
        $singo .= "<div id='singo_file{$view[wr_id]}' style='display:none;'><p>";
        $singo .= $file_viewer;
        $singo .= "</div>";
        echo $singo;
    } else {
        echo $file_viewer;
    }
    ?>

    <!-- 내용 출력 -->
    <span id="writeContents">
    <?
        $write_contents=resize_dica($view[content],400,300);
        echo $write_contents;
    ?>
    </span>

    <? if ($is_signature && $signature) { echo "<div style='margin-top:30px;margin-bottom:30px;text-align:center;'>$signature</div>"; } // 서명 출력 ?>

</div>

<?
// 광고가 있는 경우 광고를 연결
if (file_exists("$g4[path]/adsense/adsense_view_comment.php"))
    include_once("$g4[path]/adsense/adsense_view_comment.php");
?>

</div>

<?
// 코멘트 입출력
if (!$board['bo_comment_read_level'])
  include_once("./view_comment.php");
else if ($member['mb_level'] >= $board['bo_comment_read_level'])
  include_once("./view_comment.php");
?>
<?=$link_buttons?>
<? include_once("$g4[path]/adsense/adsense_page_bottom.php"); ?>

</div>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script type="text/javascript">
window.onload=function() {
    resizeBoardImage($(view_main).width()-20);
    OnclickCheck(document.getElementById("writeContents"), '<?=$config[cf_link_target]?>'); 
}
</script>
<!-- 게시글 보기 끝 -->