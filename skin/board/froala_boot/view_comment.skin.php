<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if ($is_admin || !$view[is_notice] || !in_array( $write['mb_id'],explode(",", trim($board['bo_notice_comment_allow']) ) ) )
    $check_comment_allow = 1;

// cwin=1이면 view.skin.php를 읽지 않으므로, 스킨에서 사용하는 lib 읽어들이기
if ($cwin == 1)
    include_once("$g4[path]/lib/view.skin.lib.php");
?>

<script type="text/javascript">
// 글자수 제한
var char_min = parseInt(<?=$comment_min?>); // 최소
var char_max = parseInt(<?=$comment_max?>); // 최대
</script>

<? if ($cwin==1) { ?><div width="<?=$width?>" class="table-responsive"><?}?>

<!-- 코멘트 리스트 -->
<div name="commentContents" id="commentContents" class="commentContents">

<? if (trim($board[bo_comment_notice])) { ?>
<div class="well">
    <span class="pull-right"><i class="fa fa-volume-up"></i></span>
    <?=get_text($board[bo_comment_notice], 1)?>
</div>
<? } ?>

<?
for ($i=0; $i<count($list); $i++) {
    $comment_id = $list[$i][wr_id];
?>
<a name="c_<?=$comment_id?>" id="c_<?=$comment_id?>"></a>
<table role="table" width=100% cellpadding=0 cellspacing=0 border=0 id=view_<?=$wr_id?> >
<tr>
    <td><? for ($k=0; $k<strlen($list[$i][wr_comment_reply]); $k++) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?></td>
    <td width='100%'>

        <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td height=1 colspan=3 bgcolor="#dddddd"><td>
        </tr>
        <tr>
            <td height=1 colspan=3></td>
        </tr>
        <tr>
            <td valign=top>
                <div style="float:left; margin:2px 0 0 2px;">
                <strong><?=$list[$i][name]?></strong>
                <font style="color:#BABABA;">
                <? if ($is_ip_view) { echo "{$list[$i][ip]}"; } ?>
                </font>
                <?=get_datetime($list[$i][wr_datetime])?>
                <?
                // $board[bo_new] 시간내에 새로운 코멘트가 있으면 icon_new.gif를 뒤에
                if ($list[$i]['wr_datetime'] >= date("Y-m-d H:i:s", $g4['server_time'] - ($board['bo_new'] * 3600))) 
                    echo "<i class=\"fa fa-pagelines\" title='new articla/새글'></i>";
                ?>
                </div>
                <div class="btn-group pull-right" style="margin-top:5px;">
                <? if ($list[$i][is_reply] && $check_comment_allow) { echo "<a class=\"btn btn-default btn-sm\" href=\"javascript:comment_box('{$comment_id}','c');\">Reply</a> "; } ?>
                <? if ($list[$i][is_edit]) { echo "<a class=\"btn btn-default btn-sm\" href=\"javascript:comment_box('{$comment_id}', 'cu');\">Modify</a> "; } ?>
                <? if ($list[$i][is_del])  { echo "<a class=\"btn btn-default btn-sm\" href=\"javascript:comment_delete('{$list[$i][del_link]}');\">Delete</a> "; } ?>
                <? if ($list[$i][singo_href]) { ?><a class="btn btn-default btn-sm" href="javascript:;" onclick="win_singo('<?=$list[$i][singo_href]?>');">Singo</a><?}?>
                <? if ($list[$i][secret_href]) { ?><a class="btn btn-default btn-sm" href="<?=$list[$i][secret_href]?>">Secret</a><?}?>
                <? if ($list[$i][nosecret_href]) { ?><a class="btn btn-default btn-sm" href="<?=$list[$i][nosecret_href]?>">UnSecret</a><?}?>
                </div>

                <!-- 코멘트 출력 -->
                <div style='line-height:20px; padding:7px; word-break:break-all; word-wrap:break-word; overflow:hidden; clear:both; '>
                <?
                if (strstr($list[$i][wr_option], "secret")) echo "<span style='color:#ff6600;FONT-WEIGHT:bold'>*비밀글입니다</span><BR> ";
                
                if (strstr($list[$i][wr_option], "html"))
                    $str = $list[$i][content];
            		else if ($is_dhtml_editor) {
                    $str = nl2br($list[$i][content]);
                } else {
                    $str = $list[$i][content];
                }
                if (strstr($list[$i][wr_option], "secret"))
                    $str = "$str";

                $str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $str);
                // FLASH XSS 공격에 의해 주석 처리 - 110406
                //$str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(swf)\".*\<\/a\>\]/i", "<script>doc_write(flash_movie('$1://$2.$3'));</script>", $str);
                $str = preg_replace("/\[\<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp)\"\s*[^\>]*\>[^\s]*\<\/a\>\]/i", "<img src='$1://$2.$3' id='target_resize_image[]' onclick='image_window(this);' border='0'>", $str);

                echo resize_content($str, $board[bo_image_width] - 150);
                ?>
                </div>
                <span id='edit_<?=$comment_id?>' style='display:none;'></span><!-- 수정 -->
                <span id='reply_<?=$comment_id?>' style='display:none;'></span><!-- 답변 -->
                </div>
                <input type=hidden id='secret_comment_<?=$comment_id?>' value="<?=strstr($list[$i][wr_option],"secret")?>">
                <textarea id='save_comment_<?=$comment_id?>' style='display:none;'><? if (strstr($list[$i][wr_option], "html")) {if ($is_dhtml_editor) echo get_text($list[$i][content1],0); else echo $list[$i][wr_content0]; } else if ($is_dhtml_editor) echo get_text(nl2br($list[$i][content1]),0); else echo get_text($list[$i][content1], 0)?></textarea>
            </td>
        </tr>
        <tr>
            <td height=5 colspan=3></td>
        </tr>
        </table>

    </td>
</tr>
</table>
<? } ?>
</div>
<!-- 코멘트 리스트 -->

<? if ($is_comment_write && $check_comment_allow ) { ?>
<!-- 코멘트 입력 -->

<?
if ($is_dhtml_editor) {
    include_once("$g4[path]/lib/cheditor4.lib.php");
    echo "<script src='$g4[cheditor4_path]/cheditor.js?v=$g4[cheditor_ver]'></script>";
}
?>

<div id=comment_write style="display:none;">
<table width=100% border=0 cellpadding=1 cellspacing=0 bgcolor="#dddddd"><tr><td>
<form role="form" class="form-horizontal" name="fviewcomment" method="post" action="<?=$g4[bbs_path]?>/write_comment_update.php" onsubmit="return fviewcomment_submit(this);" autocomplete="off" style="margin:0px;">
<input type=hidden name=w           id=w value='c'>
<input type=hidden name=bo_table    value='<?=$bo_table?>'>
<input type=hidden name=wr_id       value='<?=$wr_id?>'>
<input type=hidden name=comment_id  id='comment_id' value=''>
<input type=hidden name=sca         value='<?=$sca?>' >
<input type=hidden name=sfl         value='<?=$sfl?>' >
<input type=hidden name=stx         value='<?=$stx?>'>
<input type=hidden name=spt         value='<?=$spt?>'>
<input type=hidden name=page        value='<?=$page?>'>
<input type=hidden name=cwin        value='<?=$cwin?>'>
<input type=hidden name=is_good     value=''>

<table role="table" width=100%>
<tr>
    <td colspan="2" style="padding:5px 0 0 5px;">
        <? if (!$is_dhtml_editor) { ?>
        <span style="cursor: pointer;" onclick="textarea_decrease('wr_content', 5);"> <i class="fa fa-minus-square"></i></span>
        <span style="cursor: pointer;" onclick="textarea_original('wr_content', 8);"> <i class="fa fa-circle-o"></i></span>
        <span style="cursor: pointer;" onclick="textarea_increase('wr_content', 5);"> <i class="fa fa-plus-square"></i></span>
        <? } ?>
        
        <? if ($is_guest) { ?>
            이름 <INPUT type=text maxLength=20 size=10 name="wr_name" itemname="이름" required class=ed>
            패스워드 <INPUT type=password maxLength=20 size=10 name="wr_password" itemname="패스워드" required class=ed>
            <script src='https://www.google.com/recaptcha/api.js'></script>
            <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>" style="float:right"></div>
        <? } ?>
        <label><input type=checkbox id="wr_secret" name="wr_secret" value="secret">비밀글</label>
        <? if ($comment_min || $comment_max) { ?><span id=char_count></span>글자<?}?>
    </td>
</tr>
<tr>
    <td width=95%>
    <!-- 코멘트 위치를 잡아줍니다 -->
    <a name="g4_comment"></a>

		<!-- 에디터를 화면에 출력합니다. -->
		<? if ($is_dhtml_editor) { ?>
		
    <!-- cheditor1 + chedito2를 한번에 하는 것 / 나창호님의 코드 -->
		<textarea style="display:none" id="wr_content" name="wr_content" rows="10"></textarea>
    <input type=hidden id="html" name="html" value="html1">
		<script type="text/javascript">
		var editor = new cheditor("editor");
		// 4.3.x부터는 경로를 별도로 지정하지 않아도 됨
		//editor.config.editorPath = "<?=$g4['cheditor4_path']?>";
		editor.config.editorHeight = '100px';
		editor.config.autoHeight = true;
		editor.inputForm = 'wr_content';
		editor.config.imgReSize = false;
		editor.run();
		</script>

		<? } else { ?>
        <textarea class="form-control" id="wr_content" name="wr_content" rows=8 itemname="내용" required
        <? if ($comment_min || $comment_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?}?> style='width:100%; word-break:break-all;' class=tx></textarea>
        <? if ($comment_min || $comment_max) { ?><script language="javascript"> check_byte('wr_content', 'char_count'); </script><?}?>
		<? } ?>
    </td>
    <td width=85 align=center>
        <button type="submit" class="btn btn-default">Write</button>
    </td>
</tr>
</table>
</form>
</td></tr></table>
</div>

<script type="text/javascript">

var save_before = '';
<? if (!$is_dhtml_editor) { ?>
var save_html = document.getElementById('wr_content').innerHTML;
<? } ?>

function fviewcomment_submit(f)
{
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
    <? if ($is_dhtml_editor) { ?>
        f.wr_content.value = editor.outputBodyHTML();
    <? } else { ?>
        var save_html = f.wr_content.innerHTML;
    <? } ?>

    var subject = "";
    var content = "";
    $.ajax({
        url: "<?=$g4[bbs_path]?>/ajax.filter.php",
        type: "POST",
        data: {
            "subject": "",
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        f.wr_content.focus();
        return false;
    }

    // 양쪽 공백 없애기
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
    f.wr_content.value = f.wr_content.value.replace(pattern, "");

    // 최소글자수 제한이 있을 때 check
    if (f.char_count) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(f.char_count.innerHTML);
            if (char_min > 0 && char_min > cnt) {
                alert("코멘트는 "+char_min+"글자 이상 쓰셔야 합니다.");
                return false;
            } 
            else if (char_max > 0 && char_max < cnt) {
                alert("코멘트는 "+char_max+"글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }
    }

    <? if ($is_dhtml_editor) { ?>
    if (f.wr_content) {
        if (!editor.inputLength()) { 
            alert('내용을 입력하십시오.'); 
            editor.returnFalse();
            return false;
        }
    }
    <? } ?>

    if (typeof(f.wr_name) != 'undefined')
    {
        f.wr_name.value = f.wr_name.value.replace(pattern, "");
        if (f.wr_name.value == '')
        {
            alert('이름이 입력되지 않았습니다.');
            f.wr_name.focus();
            return false;
        }
    }

    if (typeof(f.wr_password) != 'undefined')
    {
        f.wr_password.value = f.wr_password.value.replace(pattern, "");
        if (f.wr_password.value == '')
        {
            alert('패스워드가 입력되지 않았습니다.');
            f.wr_password.focus();
            return false;
        }
    }

    if (typeof(grecaptcha) != 'undefined') {
        var v = grecaptcha.getResponse();
        if(v.length == 0) {
            alert("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요.");
            return false;
        }
    }

    return true;
}

function comment_box(comment_id, work)
{
    var el_id = '';
    // 코멘트 아이디가 넘어오면 답변, 수정
    if (comment_id) {
        el_id = (work == 'c') ? 'reply_' : 'edit_';
		    el_id += comment_id;
    }
    else {
        document.getElementById('comment_write').style.display = 'block';
		    return;
	  }

    if (save_before != el_id)
    {
		    document.getElementById(el_id).appendChild(document.getElementById("comment_write"));
        document.getElementById(el_id).style.display = 'block';
        // 코멘트 수정
        if (work == 'cu')
        {
			<? if ($is_dhtml_editor) { ?>
			editor.resetEditArea('');
			editor.replaceContents(document.getElementById('save_comment_'+comment_id).value);
			editorReset();
			<? } else { ?>
				document.getElementById('wr_content').value = document.getElementById('save_comment_'+comment_id).value
			<? } ?>
          if (typeof char_count != 'undefined')
            check_byte('wr_content', 'char_count');

          if (document.getElementById('secret_comment_'+comment_id).value)
            document.getElementById('wr_secret').checked = true;
          else
            document.getElementById('wr_secret').checked = false;

		    } else if (work == 'c') {
      		  <? if ($is_dhtml_editor) { ?>
		      	editor.resetEditArea('');
    		  	editorReset();
  	    	  <? } ?>
    		}

        document.getElementById('comment_id').value = comment_id;
        document.getElementById('w').value = work;

        save_before = el_id;
    }

    if (work == 'c') {
        <? if (!$is_member) { ?>imageClick();<? } ?>
    }
}

function editorReset () {
    editor.setDefaultCss();
    editor.setEditorEvent();
  	editor.editArea.focus();
}

function comment_delete(url)
{
    if (confirm("이 코멘트를 삭제하시겠습니까?")) location.href = url;
}

comment_box('', 'c'); // 코멘트 입력폼이 보이도록 처리하기위해서 추가 (root님)
</script>
<? } ?>

<!-- 모바일에서는 로그인창이 안보입니다. 코멘트 입력을 위해 로그인창을 보여주는게 좋습니다 -->
<? if ($member['mb_id'] == "" && $board['bo_comment_level'] > 1) {
    $login_url = "<?=$g4[bbs_path]?>/login.php?wr_id=$wr_id{$qstr}&url=".urlencode("$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$wr_id#g4_comment");
?>
<div class="well"><a href="<?=$login_url?>" title="login">로그인 하시면 댓글을 남길 수 있습니다</a></div>
<? } ?>

<? if($cwin==1) { ?></div><p align=center><a class="btn btn-default" href="javascript:window.close();">닫 기</a><?}?>

<!-- post 방식으로 javascript submit을 수행 -->
<script type="text/javascript">
function post_submit(action_url, bo_table, wr_id, comment_id, flag, msg)
{
	var f = document.fpost;
  var submit_msg = msg + "을 실행하겠습니까?";
  
	if(confirm(submit_msg)) {
    f.bo_table.value    = bo_table;
    f.wr_id.value       = wr_id;
    f.comment_id.value  = comment_id;
    f.flag.value        = flag;
		f.action            = action_url;
		f.submit();
	}
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='bo_table'                    value=''>
<input type='hidden' name='wr_id'                       value=''>
<input type='hidden' name='comment_id' id='comment_id'  value=''>
<input type='hidden' name='flag'>
</form>
