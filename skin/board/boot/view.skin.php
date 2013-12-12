<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 스킨에서 사용하는 lib 읽어들이기
include_once("$g4[path]/lib/view.skin.lib.php");
?>

<div width="<?=$width?>" class="table-responsive" id="view_<?=$wr_id?>">

<!-- 링크 버튼 -->
<div id="view-top">
    <div class="btn-group">
        <? if ($search_href) { echo "<a href=\"$search_href\" class=\"btn btn-default btn-sm\">검색</a> "; } ?>
        <? echo "<a href=\"$list_href\" class=\"btn btn-default btn-sm\">목록</a> "; ?>
    </div>
    <div class="btn-group">
        <? if ($write_href) { echo "<a href=\"$write_href\" class=\"btn btn-default btn-sm\">쓰기</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($reply_href) { echo "<a href=\"$reply_href\" class=\"btn btn-default btn-sm\">답변</a> "; } ?>
        <? if ($update_href) { echo "<a href=\"$update_href\" class=\"btn btn-default btn-sm\">수정</a> "; } ?>
        <? if ($delete_href) { echo "<a href=\"$delete_href\" class=\"btn btn-default btn-sm\">삭제</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($good_href) { echo "<a href=\"$good_href\" target='hiddenframe' class=\"btn btn-default btn-sm\">추천</a> "; } ?>
        <? if ($nogood_href) { echo "<a href=\"$nogood_href\" target='hiddenframe' class=\"btn btn-default btn-sm\">비추천</a> "; } ?>
        <? if ($scrap_href) { echo "<a href=\"javascript:;\" onclick=\"win_scrap('$scrap_href');\" class=\"btn btn-default btn-sm\">스크랩</a> "; } ?>
        <? if ($nosecret_href) { echo "<a href=\"$nosecret_href\" class=\"btn btn-default btn-sm\">비밀글해제</a> "; } ?>
        <? if ($secret_href) { echo "<a href=\"$secret_href\" class=\"btn btn-default btn-sm\">비밀글</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($copy_href) { echo "<a href=\"$copy_href\" class=\"btn btn-default btn-sm\">복사</a> "; } ?>
        <? if ($move_href) { echo "<a href=\"$move_href\" class=\"btn btn-default btn-sm\">이동</a> "; } ?>
        <? if ($now_href) { echo "<a href=\"$now_href\" class=\"btn btn-default btn-sm\">갱신</a> "; } ?>
    </div>

    <div class="pull-right">
    <div class="btn-group">
        <? if ($prev_href) { echo "<a href=\"$prev_href\" title=\"$prev_wr_subject\" class=\"btn btn-default btn-sm\">이전글</a>"; } ?>
        <? if ($next_href) { echo "<a href=\"$next_href\" title=\"$next_wr_subject\" class=\"btn btn-default btn-sm\">다음글</a>"; } ?>
 	      <a href="javascript:scaleFont(+1);" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-zoom-in"></span></a>
        <a href="javascript:scaleFont(-1);" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-zoom-out"></span></a>
    </div>
    </div>
</div>

<div id="view-header" class="container" style="margin-top:5px;">
		<p>
        <? if ($is_category) { echo ($category_name ? "[$view[ca_name]] " : ""); } ?>
        <strong><?=cut_hangul_last(get_text($view[wr_subject]))?></strong>
		</p>
		<p>
        글쓴이 : <?=$view[name]?><? if ($is_ip_view) { echo "&nbsp;($ip)"; } ?>&nbsp;&nbsp;
	  		날짜 : <?php echo substr($view['wr_datetime'], 2, 14); ?>&nbsp;&nbsp;
        조회 : <?=$view[wr_hit]?>&nbsp;&nbsp;
        <? if ($is_good) { ?><font style="font:normal 11px 돋움; color:#BABABA;">추천</font> :<font style="font:normal 11px tahoma; color:#BABABA;"> <?=$view[wr_good]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?></font>
        <? if ($is_nogood) { ?><font style="font:normal 11px 돋움; color:#BABABA;">비추천</font> :<font style="font:normal 11px tahoma; color:#BABABA;"> <?=$view[wr_nogood]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?></font>
        <?if ($singo_href) { ?><span style="float:right;padding-right:5px;"><a href="javascript:win_singo('<?=$singo_href?>');"><img src='<?=$board_skin_path?>/img/icon_singo.gif' alt='singo'></a></span><?}?>
        <?if ($unsingo_href) { ?><span style="float:right;padding-right:5px;"><a href="javascript:win_unsingo('<?=$unsingo_href?>');"><img src='<?=$board_skin_path?>/img/icon_unsingo.gif' alt='unsingo'></a></span><?}?>
		</p>
    <!-- 게시글 주소를 복사하기 쉽게 하기 위해서 아랫 부분을 삽입 -->
    <p>
    <font style="font:normal 11px 돋움; color:#BABABA;">게시글 주소 : <a href="javascript:clipboard_trackback('<?=$posting_url?>');" style="letter-spacing:0;" title='이 글을 소개할 때는 이 주소를 사용하세요'><?=$posting_url;?></a></font>
    <? if ($g4[use_bitly]) { ?>
        <? if ($view[bitly_url]) { ?>
        &nbsp;bitly : <span id="bitly_url" class=bitly style="font:normal 11px 돋움; color:#BABABA;"><a href=<?=$view[bitly_url]?> target=new><?=$view[bitly_url]?></a></span>
        <? } else { ?>
        &nbsp;bitly : <span id="bitly_url" class=bitly style="font:normal 11px 돋움; color:#BABABA;"></span>
        <script language=javascript>
        // encode 된 것을 넘겨주면, 알아서 decode해서 결과를 return 해준다.
        // encode 하기 전의 url이 있어야 결과를 꺼낼 수 있기 때문에, 결국 2개를 넘겨준다.
        // 왜? java script에서는 urlencode, urldecode가 없으니까. ㅎㅎ
        // 글쿠 이거는 마지막에 해야 한다. 왜??? 그래야 정보를 html page에 업데이트 하쥐~!
        get_bitly_g4('#bitly_url', '<?=$bo_table?>', '<?=$wr_id?>');
        </script>
        <?}?>
    <?}?>
    </p>
</div>

<div id="view-main" class="container">
<p>
<?
// 가변 파일
$cnt = 0;
for ($i=0; $i<count($view[file]); $i++) {
    if ($view[file][$i][source] && !$view[file][$i][view]) {
        $cnt++;
        echo "<i class=\"fa fa-file\" title='attached file'> <a href=\"javascript:file_download('{$view[file][$i][href]}', '{$view[file][$i][source]}');\" title='{$view[file][$i][content]}'><font style='normal 11px 돋움;'>{$view[file][$i][source]} ({$view[file][$i][size]}), Down : {$view[file][$i][download]}, {$view[file][$i][datetime]}</font></a><br>";
    }
}

// 링크
$cnt = 0;
for ($i=1; $i<=$g4[link_count]; $i++) {
    if ($view[link][$i]) {
        $cnt++;
        $link = cut_str($view[link][$i], 70);
        echo "<a href='{$view[link_href][$i]}' target=_blank><font  style='normal 11px 돋움;'>{$link} ({$view[link_hit][$i]})</font></a>";
    }
}
?>
<p>

        <?
        // 파일 출력
        for ($i=0; $i<=$view[file][count]; $i++) {
            if ($view[file][$i][view]) {
                echo resize_dica($view[file][$i][view],250,300) . "<p>" . $view[file][$i][content] . "<br/>";
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

        <?//echo $view[rich_content]; // {이미지:0} 과 같은 코드를 사용할 경우?>
        <!-- 테러 태그 방지용 --></xml></xmp><a href=""></a><a href=''></a>


        <? if ($is_signature) { echo "<p>$signature</p>"; } // 서명 출력 ?>

        <?
        // CCL 정보
        $view[wr_ccl] = $write[wr_ccl] = mw_get_ccl_info($write[wr_ccl]);
        ?>

        <? if ($board[bo_ccl] && $view[wr_ccl][by]) { ?>
        <tr style='padding:10px;' class=mw_basic_ccl><td>
        <a rel="license" href="<?=$view[wr_ccl][link]?>" title="<?=$view[wr_ccl][msg]?>" target=_blank><img src="<?=$board_skin_path?>/img/ccls_by.gif" alt='ccl'>
        <? if ($view[wr_ccl][nc] == "nc") { ?><img src="<?=$board_skin_path?>/img/ccls_nc.gif" alt='ccl nc'><? } ?>
        <? if ($view[wr_ccl][nd] == "nd") { ?><img src="<?=$board_skin_path?>/img/ccls_nd.gif" alt='ccl nd'><? } ?>
        <? if ($view[wr_ccl][nd] == "sa") { ?><img src="<?=$board_skin_path?>/img/ccls_sa.gif" alt='ccl sa'><? } ?>
        </a>
        </td></tr>
        <? } ?>
        
        <? if ($board[bo_related] && $view[wr_related]) { ?>
        <? $rels = mw_related($view[wr_related], $board[bo_related]); ?>
        <? if (count($rels)) {?>
        <tr>
            <td>
            <b>관련글</b> : <?=$view[wr_related]?>
            </td>
        </tr>
        <tr>
            <td>
                <ul>
                <? for ($i=0; $i<count($rels); $i++) { ?>
                <li> <a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>&wr_id=<?=$rels[$i][wr_id]?>"> <?=$rels[$i][wr_subject]?> </a> </li>
                <? } ?>
                </ul>
            </td>
        </tr>
        <? } ?>
        <? } ?>

        <? 
        // 인기검색어
        if ($board[bo_popular]) { 
        
        unset($plist);
        $plist = popular_list($board[bo_popular], $board[bo_popular_days], $bo_table);
        
        if (count($plist) > 0) {
        ?>
        <tr>
            <td>
                <b>인기검색어</b> : 
                <? 
                for ($i=0; $i<count($plist); $i++) {
                    if (trim($plist[$i][sfl]) == '' || strstr($plist[$i][sfl], '\%7C')) $plist[$i][sfl] = "wr_subject||wr_content";
                ?>
                <a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>&sfl=<?=urlencode($plist[$i][sfl])?>&stx=<?=$plist[$i]['pp_word']?>"><?=$plist[$i]['pp_word']?></a>&nbsp;&nbsp;
                <? } ?>
            </td>
        </tr>
        <? } ?>
        <? } ?>

        <?
        if ($board[bo_chimage])  {
            include_once("$g4[path]/lib/chimage.lib.php");
            $ch_list = chimage('', $bo_table, $wr_id);
            if ($ch_list) {
                echo "<tr>
                      <td>
                      $ch_list
                      </td>
                      </tr>
                ";
            }
        } ?>
</div>

<?
// 광고가 있는 경우 광고를 연결
if (file_exists("$board_skin_path/adsense_view_comment.php"))
    include_once("$board_skin_path/adsense_view_comment.php");

// 코멘트 입출력
if (!$board['bo_comment_read_level'])
  include_once("./view_comment.php");
else if ($member['mb_level'] >= $board['bo_comment_read_level'])
  include_once("./view_comment.php");
?>

<?=$link_buttons?>

</td></tr>
<tr><td>
<? include_once("$g4[path]/adsense_page_bottom.php"); ?>
</td></tr>

</div>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script language="JavaScript">
window.onload=function() {
    resizeBoardImage(<?=(int)$board[bo_image_width]?>);
    drawFont();
    OnclickCheck(document.getElementById("writeContents"), '<?=$config[cf_link_target]?>'); 
}
</script>
<!-- 게시글 보기 끝 -->
