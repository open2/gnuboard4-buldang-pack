<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 이모티콘 적용하기
function emoticon_html($str, $board_skin_path)
{
    if ($str == "no-image")
        return "";

    if ($str >= 1 && $str <= 44) {
        // 옛날 한줄게시판 데이터와의 호환을 위해
      	$emo_file = "$str.gif";
    } else if ($str >= 101 && $str <= 143) {
        // 새로운 부트스트랩 한줄게시판 이미지
      	$emo_file = "$str.png";
    } else {
        return ""; // 범위를 벗어나거나 기본표정의 경우 출력하지 않음
    }
   	$img_src = "<img src='$board_skin_path/emoticons/" . $emo_file . "' border=0> ";
	  return $img_src;
}

/*
else if ($w == "r")
{
    if ($member[mb_level] < $board[bo_reply_level]) {
        if ($member[mb_id])
            alert("글을 답변할 권한이 없습니다.");
        else
            alert("글을 답변할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", "./login.php?$qstr&url=".urlencode("$_SERVER[PHP_SELF]?bo_table=$bo_table"));
    }

    $tmp_point = $member[mb_point] ? $member[mb_point] : 0;
    if ($tmp_point + $board[bo_write_point] < 0 && !$is_admin)
        alert("보유하신 포인트(".number_format($member[mb_point]).")가 없거나 모자라서 글답변(".number_format($board[bo_comment_point]).")가 불가합니다.\\n\\n포인트를 적립하신 후 다시 글답변 해 주십시오.");

    //if (preg_match("/[^0-9]{0,1}{$wr_id}[\r]{0,1}/",$board[bo_notice]))
    if (in_array((int)$wr_id, $notice_array))
        alert("공지에는 답변 할 수 없습니다.");
*/
?>
<? if (!$member[mb_id] || $member[mb_level] >= $board[bo_write_level] ||($is_admin && $w == 'u' && $member[mb_id] != $write[mb_id]))
      include ("$board_skin_path/write.skin.php"); 
?>

<!-- 관리자화면 링크 -->
<? if ($is_checkbox) { ?>&nbsp;&nbsp;<INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox>&nbsp;&nbsp;&nbsp;<?}?>
<? if ($admin_href) { ?><a href="<?=$admin_href?>" class="btn btn-default"><i class='fa fa-cog'></i></a><?}?>

<!-- 제목 -->
<form name="fboardlist" method="post" role="form" class="form-inline">
<input type='hidden' name='bo_table' value='<?=$bo_table?>'>
<input type='hidden' name='sfl'  value='<?=$sfl?>'>
<input type='hidden' name='stx'  value='<?=$stx?>'>
<input type='hidden' name='spt'  value='<?=$spt?>'>
<input type='hidden' name='page' value='<?=$page?>'>
<input type='hidden' name='sw'   value=''>

<!-- 목록 -->
<? for ($i=0; $i<count($list); $i++) { ?>
    <table role="table" width=100% class="table table-hover table-condensed" style="word-wrap:break-word;margin-bottom:0px;margin-right:0px;margin-left:0px;padding:0;">
    <tr >
    <!-- reply 이외에는 td가 나오면 안됨. 괜히 공간 잡아먹어 이상해짐 -->
    <? if (strlen($list[$i][wr_reply]) > 0) { ?>
    <td valign=top style="border:0px;">
        <!-- 그냥 &nbsp; 출력하면 너무 적고, strlen으로 하면 너무 많고. 꼼수로 wr_reply의 5배만큼만... -->
        <? for ($k=0; $k<(strlen($list[$i][wr_reply])*6); $k++) echo "&nbsp;"; ?>
    </td>
    <?}?>
    <?
    // 공지사항일때는 success class로.
    if ($list[$i][is_notice])
        $td_class = "class='success'";
    else
        $td_class="";
    ?>
    <td align=left width=100% <?=$td_class?>>
        <? if ($is_checkbox) { ?><input type=checkbox name=chk_wr_id[] value="<?=$list[$i][wr_id]?>"><? } ?>
        <? 
        if ($list[$i][reply]) { 
            if ($list[$i][icon_reply]) echo "<i class=\"fa fa-reply fa-rotate-180\" title='reply/답글'></i> ";
        }
        ?>
        <?
        if ($list[$i][is_notice]) // 공지사항 
            echo "<i class=\"fa fa-microphone\" title='notice/공지사항'></i> ";
        else {
         		$list[$i][subject] = emoticon_html($list[$i][subject], $board_skin_path);
            echo $list[$i][subject];
        }
        ?>
        <?
        /*
        if ($list[$i][wr_subject]) {
            $emo = $list[$i][wr_subject];
            echo "<img src='$board_skin_path/emoticons/" . $emo . ".png'>";
        }
        */
            
 		    $list[$i][wr_content] = conv_content($list[$i][wr_content], 0);

     		echo $list[$i][wr_content];

        // 밑에서 한번 더 써야 하기 때문에 배열에 담아 둡니다.
        $icon_images = "";
        if ($list[$i][icon_new]) $icon_images .= " <i class=\"fa fa-pagelines\" title='new articla/새글'></i>";
        if ($list[$i][icon_secret]) $icon_images .= " <i class=\"fa fa-lock\" title='secret/비밀글'></i>";
        echo $icon_images;
        ?>

        <div class="pull-right">
            <?=$list[$i][datetime2]?>&nbsp;<?=$list[$i][name]?>
        <?
        if ($member[mb_id]) {
        ?>
            <div class="btn-group" style="margin-right:10px;">
            <a href="<?=$write_href?>&w=r&wr_id=<?=$list[$i][wr_id]?>&page=<?=$page?>&sca=<?=$ca_name?>" class="btn btn-default btn-sm">답변</a>
    		    </div>
        <? } ?>
        <?
        if (($member[mb_id] && ($member[mb_id] == $list[$i][mb_id])) || $is_admin) {
        ?>
            <div class="btn-group" style="margin-right:10px;">
            <a href="<?=$write_href?>&w=u&wr_id=<?=$list[$i][wr_id]?>&page=<?=$page?>&sca=<?=$ca_name?>" class="btn btn-default btn-sm">수정</a>
            <a href="javascript:if (confirm('삭제하시겠습니까?')) { location='./delete.php?w=d&bo_table=<?=$bo_table?>&wr_id=<?=$list[$i][wr_id]?>&sca=<?=$sca?>';}" class="btn btn-default btn-sm">삭제</a>
    		    </div>
        <? } ?>
        </div>
    </td>
    </tr>
    </table>
<?}?>

<? if (count($list) == 0) { echo "<table width=100%><tr><td height=100 align=center>게시물이 없습니다.</td></tr></table>"; } ?>
</form>

<!-- 페이지 -->
<div class="center-block hidden-xs">
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