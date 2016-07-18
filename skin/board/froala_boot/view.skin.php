<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 스킨에서 사용하는 lib 읽어들이기
include_once("$g4[path]/lib/view.skin.lib.php");
?>

<div width="<?=$width?>" id="view_<?=$wr_id?>" name="view_<?=$wr_id?>">

<!-- 링크 버튼 -->
<? ob_start(); // 한번 만들어서 두번 씁니다 ?>
<div id="view_btn_top">
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
        <a href="#commentContents" class="btn btn-default btn-sm"><i class="fa fa-chevron-down"></i></a>
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
<div class="panel-heading" style="padding-bottom:0px;">

		<p>
        <? if ($is_category) { echo ($category_name ? "[$view[ca_name]] " : ""); } ?>
        <strong><?=cut_hangul_last(get_text($view[wr_subject]))?></strong>
		</p>
		<p>
        <font style="color:#BABABA;">
        <div class="pull-left"><?=$view[name]?>&nbsp;<? if ($is_ip_view) { echo "($ip)"; } ?>&nbsp;&nbsp;</div>
	  		<div class="hidden-md hidden-lg pull-left"><?=get_datetime($view['wr_datetime'])?></div>
	  		<div class="hidden-xs hidden-sm pull-left"><?=$view['wr_datetime']?></div>&nbsp;&nbsp;
        조회 <?=$view[wr_hit]?>&nbsp;&nbsp;
        <? if ($is_good) { ?>추천  <?=$view[wr_good]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
        <? if ($is_nogood) { ?>비추천  <?=$view[wr_nogood]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
        </font>
    <!-- 게시글 주소를 복사하기 쉽게 하기 위해서 아랫 부분을 삽입 -->
    <span class="pull-right">
    <a href="javascript:clipboard_trackback('<?=$posting_url?>');" style="letter-spacing:0;" title='이 글을 소개할 때는 이 주소를 사용하세요'><i class="fa fa-link"></i></a>
    </span>
    </p>

    <p>
        <?
        // 가변 파일
        $cnt = 0;
        for ($i=0; $i<count($view[file]); $i++) {
            if ($view[file][$i][source] && !$view[file][$i][view]) {
                $cnt++;
                echo "<i class=\"fa fa-file-o\" title='attached file'></i> <a href=\"javascript:file_download('{$view[file][$i][href]}', '{$view[file][$i][source]}');\" title='{$view[file][$i][content]}'>{$view[file][$i][source]}<font style='font-weight:normal;color:#B8B8B8;'> ({$view[file][$i][size]}), Down : {$view[file][$i][download]}, " . get_datetime($view[file][$i][datetime]) . "</font></a><br>";
            }
        }

        // 링크
        $cnt = 0;
        for ($i=1; $i<=$g4[link_count]; $i++) {
            if ($view[link][$i]) {
                $cnt++;
                echo link_view($view[link_href][$i], $view[link][$i], $view[link_hit][$i]);
            }
        }
    ?>
    </p>
</div>

<div id="view_main" class="panel-body">

    <?
    // 이미지 파일이 출력 되었는지...
    $view_img_file = 0;

    // 파일 출력
    for ($i=0; $i<=$view[file][count]; $i++) {
        if ($view[file][$i][view]) {
            echo resize_dica($view[file][$i][view],250,300) . "<p>" . $view[file][$i][content] . "</p>";
            $view_img_file = 1;
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
    <? if ($view_img_file == 0) { ?>
    <div style="float:right;margin-left:10px;margin-bottom:10px;" class="">
        <!--
        125px * 125px의 adsense광고
        -->
    </div>
    <? } ?>

    <span id="writeContents" style="word-wrap:break-word;">
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
<div style="height:10px;"></div>
<?=$link_buttons?>
<? include_once("$g4[path]/adsense/adsense_page_bottom.php"); ?>

</div>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>?v=<?= app_version() ?>"></script>
<script type="text/javascript">
    $(function () {
        // SPA 지원 및 랜덤하게 리사이징 되지 않는 문제 해결을 위해, 이미지 로딩 이벤트에서 개별 실행
        //resizeBoardImage($(view_main).width()-20);
        var imageWidth = $('#view_main').width() - 20;
        $('img[name^=target_resize_image]').one('load', function () {
            resizeBoardImageOne($(this), imageWidth);
        }).each(function () {
            // for cache
            if (this.complete) {
                resizeBoardImageOne($(this), imageWidth);
            }
        });
        OnclickCheck(document.getElementById("writeContents"), '<?=$config[cf_link_target]?>');
    });
</script>

<?php if (in_app()) { ?>
    <div id="fullImage" style="position: fixed; width: 100%; height: 100%; top: 0; left: 0; overflow: auto; z-index: 999; background-color: #000; display: none;" _onclick="fullImageHide();">
        <div style="position: absolute; right: 10px; top: 10px; z-index: 10;">
            <a href="javascript:fullImageHide();"><i class="material-icons md-48" style="color: #fff; text-shadow: 1px 1px #000;">&#xE5CD;</i></a>
        </div>
        <div id="fullImageView" style="width:100%; height: 100%;"></div>
    </div>

    <script src="/m/vendor/jquery.panzoom/jquery.panzoom.min.js?v=<?= app_version() ?>"></script>
    <script type="text/javascript">
        var $fullImage = $('#fullImage'),
            $fullImageView = $('#fullImageView');

        function fullImageHide()
        {
            $fullImage.hide();
        }

        function image_window(img)
        {
            image_window3(img.src);
        }

        function image_window3(img_src, w, h)
        {
            $fullImage.show();
            $fullImageView.html('<img src="' + img_src + '">');
            $fullImageView.find('img').panzoom();
        }
    </script>
<? } ?>
<!-- 게시글 보기 끝 -->
