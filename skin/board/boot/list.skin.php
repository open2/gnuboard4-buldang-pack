<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$notice_count = $global_notice_count + $arr_notice_count;
?>

<!-- 분류 셀렉트 박스, 게시물 몇건, 관리자화면 링크 -->
<div>
    <div class="btn-group">
        <a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>" class="btn btn-default"><?=$board[bo_subject]?></a>
    </div>

    <? if ($write_href) { ?>
    <div class="btn-group">
        <a href="<?=$write_href?>" class="btn btn-default"><i class='fa fa-edit'></i> 쓰기</a>
    </div>
    <? } ?>

    <? include("$g4[bbs_path]/my_menu_add_script.php");?>
    <? if ($rss_href) { ?><a href='<?=$rss_href?>' class="btn btn-default"><i class='fa fa-rss'></i></a><?}?>
    <? if ($admin_href) { ?><a href="<?=$admin_href?>" class="btn btn-default"><i class='fa fa-cog'></i></a><?}?>

    <? if ($notice_count > 0) { ?>
    <a href="#" class="btn btn-default notice_flip" title="공지사항 Flip"><i class="fa fa-microphone"></i></a>
    <? } ?>

    <? if ($is_category) { ?>
    <form name="fcategory" method="get" role="form" class="form-inline">
        <select class="form-control" name=sca onchange="location='<?=$category_location?>'+<?=strtolower($g4[charset])=='utf-8' ? "encodeURIComponent(this.value)" : "this.value"?>;">
        <option value=''>전체</option><?=$category_option?></select>
    </form>
    <? } ?>

    <div class="pull-right hidden-xs">
        Total <?=number_format($total_count)?>
    </div>
</div>

<!-- 제목 -->
<form name="fboardlist" method="post" role="form" class="form-inline">
<input type='hidden' name='bo_table' value='<?=$bo_table?>'>
<input type='hidden' name='sfl'  value='<?=$sfl?>'>
<input type='hidden' name='stx'  value='<?=$stx?>'>
<input type='hidden' name='spt'  value='<?=$spt?>'>
<input type='hidden' name='page' value='<?=$page?>'>
<input type='hidden' name='sw'   value=''>
<input type='hidden' name='sca'   value=''>

<div class="table-responsive" id="list_<?=$bo_table?>" name="list_<?=$bo_table?>">
<table width=100% class="table table-condensed table-hover" style="word-wrap:break-word;">
<thead>
<tr class="success">
    <th width=50px class="hidden-xs"><?=subject_sort_link('wr_id', $qstr2, 1)?>번호</a></th>
    <? if ($is_checkbox) { ?><th class="hidden-xs"><INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox></th><?}?>
    <th>제목<span class="visible-xs pull-right" style="font-weight: normal;color:#B8B8B8;">Page <?=$page?>/<?=$total_page?></span></th>
    <th width=120px class="hidden-xs">글쓴이</th>
    <th width=70px class="hidden-xs"><?=subject_sort_link('wr_datetime', $qstr2, 1)?>날짜</a></th>
    <th width=80px class="hidden-xs"><?=subject_sort_link('wr_hit', $qstr2, 1)?>조회</a></th>
    <? if ($is_good) { ?><th width=60px class="hidden-xs"><?=subject_sort_link('wr_good', $qstr2, 1)?>추천</a></th><?}?>
    <? if ($is_nogood) { ?><th width=60px class="hidden-xs"><?=subject_sort_link('wr_nogood', $qstr2, 1)?>비추</a></th><?}?>
</tr>
</thead>

<!-- 목록 -->
<? for ($i=0; $i<count($list); $i++) { ?>
<?
$is_notice = "";
if ($list[$i][is_notice])
    $is_notice = "is_notice";
?>
<tr class="<?=$is_notice?>"> 
    <td class="hidden-xs">
        <? 
        if ($list[$i][is_notice]) // 공지사항 
            echo "<i class=\"fa fa-microphone\" title='notice/공지사항'></i> ";
        else if ($wr_id == $list[$i][wr_id]) // 현재위치
            echo "<span style='font-weight:bold; color:#E15916;'>{$list[$i][num]}</span>";
        else
            echo "<span style='color:#BABABA;'>{$list[$i][num]}</span>";
        ?>
    </td>
    <? if ($is_checkbox) { ?><td class="hidden-xs"><input type=checkbox name=chk_wr_id[] value="<?=$list[$i][wr_id]?>"></td><? } ?>
    <td class="hidden-xs" align=left style='word-break:break-all;'>
        <?
        echo $list[$i][reply];
        if ($list[$i][icon_reply]) echo "<i class=\"fa fa-reply fa-rotate-180\" title='reply/답글'></i> ";
        if ($is_category && $list[$i][ca_name]) {
            echo "<font color=gray><a href='{$list[$i][ca_name_href]}'><small>({$list[$i][ca_name]})</small></a></font> ";
        }

        if ($list[$i][is_notice]) 
            $style = " style='font-weight:bold;'";
        else if ($wr_id == $list[$i][wr_id])
            $style = " style='font-weight:bold;color:#E15916;'";
        else if ($list[$i][wr_singo])
            $style = " style='color:#B8B8B8;'";
        else
            $style = "";

        echo "<a href='{$list[$i][href]}'";
        echo "<span $style>" . $list[$i][subject] . "</span>";
        echo "</a>";

        if ($list[$i][comment_cnt]) 
            echo " <a href=\"{$list[$i][comment_href]}\"><span style='color:#EE5A00;'><small>{$list[$i][comment_cnt]}</small></span></a>";

        // 밑에서 한번 더 써야 하기 때문에 배열에 담아 둡니다.
        $icon_images = "";
        if ($list[$i][icon_new]) $icon_images .= " <i class=\"fa fa-pagelines\" title='new articla/새글'></i>";
        if ($list[$i][icon_file]) $icon_images .=  " <i class=\"fa fa-file-o\" title='attached file/첨부파일'></i>";
        if ($list[$i][icon_link]) $icon_images .=  " <i class=\"fa fa-link\" title='link/링크'></i>";
        if ($list[$i][icon_hot]) $icon_images .= " <i class=\"fa fa-fire\" title='hot article/조횟수 많은 글'></i>";
        if ($list[$i][icon_secret]) $icon_images .= " <i class=\"fa fa-lock\" title='secret/비밀글'></i>";
        echo $icon_images;
        ?>
        </td>
    <td class="hidden-xs"><?=$list[$i][name]?></td>
    <td class="hidden-xs"><?=$list[$i][datetime2]?></td>
    <td class="hidden-xs"><?=$list[$i][wr_hit]?></td>
    <? if ($is_good) { ?><td class="hidden-xs" align="center"><?=$list[$i][wr_good]?></td><? } ?>
    <? if ($is_nogood) { ?><td class="hidden-xs" align="center"><?=$list[$i][wr_nogood]?></td><? } ?>
    <!-- 
    xs 사이즈에서 40글자 이상이면 table width를 넘어서 수평 스크롤이 생깁니다 
    그래서, 따로 출력하는 row를 만들어 줬습니다.
    xs 사이즈에서는 아래처럼 1개의 td만 출력 됩니다. 다른 것은 모두 hidden.
    더 좋은 방법에 대한 제안은 언제든 환영 합니다.
    -->
    <td class="visible-xs" align=left style='word-break:break-all;'>
        <div>
        <?
        if ($list[$i][is_notice]) // 공지사항 
            echo "<i class=\"fa fa-microphone\" title='notice/공지사항'></i> ";


        if ($list[$i][icon_reply]) echo "<i class=\"fa fa-reply\" title='reply/답글'></i> ";
        if ($is_category && $list[$i][ca_name]) { 
            echo "<font color=gray><a href='{$list[$i][ca_name_href]}'><small>({$list[$i][ca_name]})</small></a></font> ";
        }

        if ($list[$i][is_notice]) 
            $style = " style='font-weight:bold;'";
        else if ($wr_id == $list[$i][wr_id])
            $style = " style='font-weight:bold;color:#E15916;'";
        else if ($list[$i][wr_singo])
            $style = " style='color:#B8B8B8;'";
        else
            $style = "";

        echo "<a href='" . $list[$i][href] . "'>";
        echo "<span $style>" . cut_str($list[$i][subject], 40) . "</span>";
        echo "</a>";

        if ($list[$i][comment_cnt]) 
            echo " <a href=\"{$list[$i][comment_href]}\"><span style='color:#EE5A00;'><small>{$list[$i][comment_cnt]}</small></span></a>";

        // 위에서 저장한 $icon_images 출력
        echo $icon_images;
        ?>
        </div>
        <span class="visible-xs pull-right">
        <font style="color:#BABABA;">
        <?=$list[$i][datetime2]?>&nbsp;&nbsp;
        <?=$list[$i][wr_hit]?>&nbsp;&nbsp;
        </font>
        <?=$list[$i][name]?>
        </span>
    </td>
</tr>
<?}?>

<? if (count($list) == 0) { echo "<tr><td colspan=6 height=100 align=center>게시물이 없습니다.</td></tr>"; } ?>
</table>
</div>
</form>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>이전검색</a></li>"; } ?>
    <?
    // 기본으로 넘어오는 페이지를 아래와 같이 변환하여 다양하게 출력할 수 있습니다.
    $write_pages = str_replace("이전", "<i class='fa fa-angle-left'></i>", $write_pages);
    $write_pages = str_replace("다음", "<i class='fa fa-angle-right'></i>", $write_pages);
    $write_pages = str_replace("처음", "<i class='fa fa-angle-double-left'></i>", $write_pages);
    $write_pages = str_replace("맨끝", "<i class='fa fa-angle-double-right'></i>", $write_pages);
    ?>
    <?=$write_pages?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>이후검색</a></li>"; } ?>
    </ul>
</div>
<div class="center-block visible-xs">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>이전검색</a></li>"; } ?>
    <?
    // 기본으로 넘어오는 페이지를 아래와 같이 변환하여 다양하게 출력할 수 있습니다.
    $write_pages_xs = str_replace("이전", "<i class='fa fa-angle-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("다음", "<i class='fa fa-angle-right'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("처음", "<i class='fa fa-angle-double-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("맨끝", "<i class='fa fa-angle-double-right'></i>", $write_pages_xs);
    ?>
    <?=$write_pages_xs?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>이후검색</a></li>"; } ?>
    </ul>
</div>

<!-- 링크 버튼, 검색 -->
<form name=fsearch method=get role="form" class="form-inline">
<input type=hidden name=bo_table value="<?=$bo_table?>">
<input type=hidden name=sca      value="<?=$sca?>">
<? if ($list_href) { ?>
<div class="btn-group">
    <a href="<?=$list_href?>" class="btn btn-default"><i class='fa fa-list'></i> 목록</a>
</div>
<? } ?>
<? if ($write_href) { ?>
<div class="btn-group">
    <a href="<?=$write_href?>" class="btn btn-default"><i class='fa fa-edit'></i> 쓰기</a>
</div>
<? } ?>
<? if ($is_checkbox) { ?>
<span style='display:inline-block!important;vertical-align:bottom;'>
<div class="btn-group hidden-sm hidden-xs">
    <a href="javascript:select_delete();" class="btn btn-default">선택삭제</a>
    <a href="javascript:select_copy('copy');" class="btn btn-default">선택복사</a>
    <a href="javascript:select_copy('move');" class="btn btn-default">선택이동</a>
    <? if ($is_category) { ?>
    <a href="javascript:select_category();"  class="btn btn-default">카테고리변경</a>
    <select class="form-control input-sm" name=sca2><?=$category_option?></select>
    <? } ?>
</div>
</span>
<? } ?>

<div class="pull-right hidden-lg hidden-md hidden-sm">
    <a class="btn btn-default" data-toggle="collapse" data-target=".board-bottom-search-collapse"><i class='fa fa-search'></i></a>
</div>

<div class="pull-right collapse navbar-collapse board-bottom-search-collapse">
    <div class="form-group">
        <label class="sr-only" for="sfl">sfl</label>
        <select name=sfl class="form-control">
        <option value='wr_subject'>제목</option>
        <option value='wr_content'>내용</option>
        <option value='wr_subject||wr_content'>제목+내용</option>
        <option value='mb_id,1'>회원아이디</option>
        <option value='mb_id,0'>회원아이디(코)</option>
        <option value='wr_name,1'>이름</option>
        <option value='wr_name,0'>이름(코)</option>
        </select>
    </div>
    <div class="form-group">
        <label class="sr-only" for="stx">stx</label>
        <input name=stx maxlength=15 size=10 itemname="검색어" required value='<?=stripslashes($stx)?>' class="form-control">
    </div>
    <div class="form-group">
        <label class="sr-only" for="sop">sop</label>
        <select name=sop class="form-control">
            <option value=and>and</option>
            <option value=or>or</option>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>

</form>

<script type="text/javascript">
<?
// flip cookie를 가져와서 비교 합니다
$ck_name = $bo_table . "_flip_datetime";
$flip_datetime = $_COOKIE[$ck_name];

if ($g4['last_notice_datetime'] > $flip_datetime) {
    // flip한 이후에 공지가 올라오면 flip cookie를 삭제해주고, flip이 되지 않게 합니다. 새로운 공지는 반드시 봐야 합니다.
?>
    set_cookie( '<?=$ck_name?>', '', 86400 * 365, g4_cookie_domain);
    $('.is_notice').show();
<?
} else {
    // flip은 했고, 새로운 공지도 없으면 공지를 감춰줍니다
?>
    $('.is_notice').hide();
    $('.notice_flip').addClass('active');   // 버튼이 눌러진 상태로 바꿔줍니다.
<? } ?>
$('.notice_flip').click(function() {
    $('.is_notice').toggle();
    set_cookie( '<?=$ck_name?>', '<?=$g4[time_ymdhis]?>', 86400 * 365, g4_cookie_domain);
});
</script>

<script type="text/javascript">
if ('<?=$sca?>') document.fcategory.sca.value = '<?=$sca?>';
if ('<?=$stx?>') {
    document.fsearch.sfl.value = '<?=$sfl?>';
    document.fsearch.sop.value = '<?=$sop?>';
}
</script>

<? if ($is_checkbox) { ?>
<script type="text/javascript">
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function check_confirm(str) {
    var f = document.fboardlist;
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(str + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }
    return true;
}

// 선택한 게시물 삭제
function select_delete() {
    var f = document.fboardlist;

    str = "삭제";
    if (!check_confirm(str))
        return;

    if (!confirm("선택한 게시물을 정말 "+str+" 하시겠습니까?\n\n한번 "+str+"한 자료는 복구할 수 없습니다"))
        return;

    f.action = "./delete_all.php";
    f.submit();
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";
                       
    if (!check_confirm(str))
        return;

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}

// 선택한 게시물 카테고리를 변경
function select_category() {
    var f = document.fboardlist;
    var f2 = document.fsearch;

    str = "카테고리변경";
    if (!check_confirm(str))
        return;

    str = f2.sca2.value;
    if (!confirm("선택한 게시물의 카테고리를 "+str+" 으로 변경 하시겠습니까?"))
        return;

    // sca에 값을 넣어줘야죠.
    f.sca.value = str;

    f.action = "./category_all.php";
    f.submit();
}
</script>
<? } ?>
<!-- 게시판 목록 끝 -->