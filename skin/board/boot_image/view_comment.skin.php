<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

if ($is_admin || !$view[is_notice] || !in_array( $write['mb_id'],explode(",", trim($board['bo_notice_comment_allow']) ) ) )
    $check_comment_allow = 1;

// cwin=1�̸� view.skin.php�� ���� �����Ƿ�, ��Ų���� ����ϴ� lib �о���̱�
if ($cwin == 1)
    include_once("$g4[path]/lib/view.skin.lib.php");
?>

<script language="JavaScript">
// ���ڼ� ����
var char_min = parseInt(<?=$comment_min?>); // �ּ�
var char_max = parseInt(<?=$comment_max?>); // �ִ�
</script>

<style type="text/css">
.secret, .secret p, .secret div
.secret a:hover, .secret a:active, .secret a:visited, .secret a:link
{ font-size:11px; color:#ff6600; text-decoration:none; font-family:gulim; }
</style>

<? if ($cwin==1) { ?><table width=100% cellpadding=10 align=center><tr><td><?}?>

<!-- �ڸ�Ʈ ����Ʈ -->
<div id="commentContents" class="commentContents">

<? if (trim($board[bo_comment_notice])) { ?>
<table width=100% cellpadding=0 cellspacing=0>
<tr>
    <td></td>
    <td width="100%">
        <table width=100% cellpadding=0 cellspacing=0>
        <tr>
            <!-- �̸�, ������ -->
            <td>
                <span class=mw_basic_comment_name><img src="<?=$board_skin_path?>/img/icon_notice.gif"></span>
            </td>
            <!-- ��ũ ��ư, �ڸ�Ʈ �ۼ��ð� -->
            <td align=right>
                <span class=mw_basic_comment_datetime><?=substr($view[wr_datetime],2,14)?></span>
            </td>
        </tr>
        <tr height=5><td></td></tr>
        </table>
        <table width=100% cellpadding=0 cellspacing=0 class=mw_basic_comment_content>
        <tr>                            
            <td colspan=2>
                <div><?=get_text($board[bo_comment_notice], 1)?></div>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
<br/>
<? } ?>

<?
for ($i=0; $i<count($list); $i++) {
    $comment_id = $list[$i][wr_id];
?>
<a name="c_<?=$comment_id?>" id="c_<?=$comment_id?>"></a>
<table width=100% cellpadding=0 cellspacing=0 border=0 id=view_<?=$wr_id?> >
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
                <div style="height:28px; background:url(<?=$board_skin_path?>/img/co_title_bg.gif); clear:both; line-height:28px;">
                <div style="float:left; margin:2px 0 0 2px;">
                <strong><?=$list[$i][name]?></strong>
                <span style="color:#888888; font-size:11px;"><?=$list[$i][datetime]?></span>
                </div>
                <div style="float:right; margin-top:5px;">
                <? if ($is_ip_view) { echo "&nbsp;<span style=\"color:#B2B2B2; font-size:11px;\">{$list[$i][ip]}</span>"; } ?>
                <? if ($list[$i][is_reply] && $check_comment_allow) { echo "<a href=\"javascript:comment_box('{$comment_id}','c');\"><img src='$board_skin_path/img/co_btn_reply.gif' border=0 align=absmiddle alt='�亯'></a> "; } ?>
                <? if ($list[$i][is_edit]) { echo "<a href=\"javascript:comment_box('{$comment_id}', 'cu');\"><img src='$board_skin_path/img/co_btn_modify.gif' border=0 align=absmiddle alt='����'></a> "; } ?>
                <? if ($list[$i][is_del])  { echo "<a href=\"javascript:comment_delete('{$list[$i][del_link]}');\"><img src='$board_skin_path/img/co_btn_delete.gif' border=0 align=absmiddle alt='����'></a> "; } ?>
                <? if ($list[$i][singo_href]) { ?>&nbsp;<a href="javascript:;" onclick="win_singo('<?=$list[$i][singo_href]?>');"><img src='<?=$board_skin_path?>/img/icon_singo.gif'></a><?}?>
                <? if ($list[$i][secret_href]) { ?>&nbsp;<a href="<?=$list[$i][secret_href]?>"><img src='<?=$board_skin_path?>/img/icon_comment_secret.gif' border='0' align='absmiddle'></a><?}?>
                <? if ($list[$i][nosecret_href]) { ?>&nbsp;<a href="<?=$list[$i][nosecret_href]?>"><img src='<?=$board_skin_path?>/img/icon_comment_nosecret.gif' border='0' align='absmiddle'></a><?}?>
                &nbsp;
                </div>
                </div>

                <!-- �ڸ�Ʈ ��� -->
                <div style='line-height:20px; padding:7px; word-break:break-all; overflow:hidden; clear:both; '>
                <?
                //if (strstr($list[$i][wr_option], "secret") and ($list[$i][mb_id] == $member[mb_id] or $is_admin or $member[mb_id] == $write[mb_id])) echo "<span style='color:#ff6600;FONT-WEIGHT:bold'>*��б��Դϴ�</span><BR> ";
                if (strstr($list[$i][wr_option], "secret")) echo "<span style='color:#ff6600;FONT-WEIGHT:bold'>*��б��Դϴ�</span><BR> ";
                
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
                // FLASH XSS ���ݿ� ���� �ּ� ó�� - 110406
                //$str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(swf)\".*\<\/a\>\]/i", "<script>doc_write(flash_movie('$1://$2.$3'));</script>", $str);
                $str = preg_replace("/\[\<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp)\"\s*[^\>]*\>[^\s]*\<\/a\>\]/i", "<img src='$1://$2.$3' id='target_resize_image[]' onclick='image_window(this);' border='0'>", $str);

                echo resize_content($str, $board[bo_image_width] - 150);
                ?>
                </div>
                <span id='edit_<?=$comment_id?>' style='display:none;'></span><!-- ���� -->
                <span id='reply_<?=$comment_id?>' style='display:none;'></span><!-- �亯 -->
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
<!-- �ڸ�Ʈ ����Ʈ -->

<? if ($is_comment_write && $check_comment_allow ) { ?>
<!-- �ڸ�Ʈ �Է� -->

<?
if ($is_dhtml_editor) {
    include_once("$g4[path]/lib/cheditor4.lib.php");
    echo "<script src='$g4[cheditor4_path]/cheditor.js?v=$g4[cheditor_ver]'></script>";
}
?>

<div id=comment_write style="display:none;">
<table width=100% border=0 cellpadding=1 cellspacing=0 bgcolor="#dddddd"><tr><td>
<form name="fviewcomment" method="post" action="./write_comment_update.php" onsubmit="return fviewcomment_submit(this);" autocomplete="off" style="margin:0px;">
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

<table width=100% cellpadding=3 height=156 cellspacing=0 bgcolor="#ffffff" style="border:1px solid #fff; background:url(<?=$board_skin_path?>/img/co_bg.gif) x-repeat;">
<tr>
    <td colspan="2" style="padding:5px 0 0 5px;">
        <? if (!$is_dhtml_editor) { ?>
        <span style="cursor: pointer;" onclick="textarea_decrease('wr_content', 8);"><img src="<?=$board_skin_path?>/img/co_btn_up.gif"></span>
        <span style="cursor: pointer;" onclick="textarea_original('wr_content', 8);"><img src="<?=$board_skin_path?>/img/co_btn_init.gif"></span>
        <span style="cursor: pointer;" onclick="textarea_increase('wr_content', 8);"><img src="<?=$board_skin_path?>/img/co_btn_down.gif"></span>
        <? } ?>
        
        <? if ($is_guest) { ?>
            �̸� <INPUT type=text maxLength=20 size=10 name="wr_name" itemname="�̸�" required class=ed>
            �н����� <INPUT type=password maxLength=20 size=10 name="wr_password" itemname="�н�����" required class=ed>
            <? if ($is_guest) { ?>
            <img id="zsfImg">
            <input class='ed' type=input size=10 name=wr_key id=wr_key itemname="�ڵ���Ϲ���" required >&nbsp;&nbsp;
            <script type="text/javascript" src="<?="$g4[path]/zmSpamFree/zmspamfree.js"?>"></script>
            <?}?>
        <? } ?>
        <input type=checkbox id="wr_secret" name="wr_secret" value="secret">��б�
        <? if ($comment_min || $comment_max) { ?><span id=char_count></span>����<?}?>
    </td>
</tr>
<tr>
    <td width=95%>
		<!-- �����͸� ȭ�鿡 ����մϴ�. -->
		<? if ($is_dhtml_editor) { ?>
		
    <!-- cheditor1 + chedito2�� �ѹ��� �ϴ� �� / ��âȣ���� �ڵ� -->
		<textarea style="display:none" id="wr_content" name="wr_content" rows="10"></textarea>
    <input type=hidden id="html" name="html" value="html1">
		<script type="text/javascript">
		var editor = new cheditor("editor");
		// 4.3.x���ʹ� ��θ� ������ �������� �ʾƵ� ��
		//editor.config.editorPath = "<?=$g4['cheditor4_path']?>";
		editor.config.editorHeight = '100px';
		editor.config.autoHeight = true;
		editor.inputForm = 'wr_content';
		editor.config.imgReSize = false;
		editor.run();
		</script>

		<? } else { ?>
        <textarea id="wr_content" name="wr_content" rows=8 itemname="����" required
        <? if ($comment_min || $comment_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?}?> style='width:100%; word-break:break-all;' class=tx></textarea>
        <? if ($comment_min || $comment_max) { ?><script language="javascript"> check_byte('wr_content', 'char_count'); </script><?}?>
		<? } ?>
    </td>
    <td width=85 align=center>
        <div><input type="image" src="<?=$board_skin_path?>/img/co_btn_write.gif" border=0 accesskey='s'></div>
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
    var pattern = /(^\s*)|(\s*$)/g; // \s ���� ����
    <? if ($is_dhtml_editor) { ?>
        f.wr_content.value = editor.outputBodyHTML();
    <? } else { ?>
        var save_html = f.wr_content.innerHTML;
    <? } ?>

    /*
    var s;
    if (s = word_filter_check(f.wr_content.value))
    {
        alert("���뿡 �����ܾ�('"+s+"')�� ���ԵǾ��ֽ��ϴ�");
        <? if (!$is_dhtml_editor) { ?>
        f.wr_content.focus();
        <? } ?>
        return false;
    }
    */

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
        alert("���뿡 �����ܾ�('"+content+"')�� ���ԵǾ��ֽ��ϴ�");
        f.wr_content.focus();
        return false;
    }

    // ���� ���� ���ֱ�
    var pattern = /(^\s*)|(\s*$)/g; // \s ���� ����
    f.wr_content.value = f.wr_content.value.replace(pattern, "");

    // �ּұ��ڼ� ������ ���� �� check
    if (f.char_count) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(f.char_count.innerHTML);
            if (char_min > 0 && char_min > cnt) {
                alert("�ڸ�Ʈ�� "+char_min+"���� �̻� ���ž� �մϴ�.");
                return false;
            } 
            else if (char_max > 0 && char_max < cnt) {
                alert("�ڸ�Ʈ�� "+char_max+"���� ���Ϸ� ���ž� �մϴ�.");
                return false;
            }
        }
    }

    <? if ($is_dhtml_editor) { ?>
    if (f.wr_content) {
        if (!editor.inputLength()) { 
            alert('������ �Է��Ͻʽÿ�.'); 
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
            alert('�̸��� �Էµ��� �ʾҽ��ϴ�.');
            f.wr_name.focus();
            return false;
        }
    }

    if (typeof(f.wr_password) != 'undefined')
    {
        f.wr_password.value = f.wr_password.value.replace(pattern, "");
        if (f.wr_password.value == '')
        {
            alert('�н����尡 �Էµ��� �ʾҽ��ϴ�.');
            f.wr_password.focus();
            return false;
        }
    }

    if (typeof(f.wr_key) != 'undefined')
    {
        if (!checkFrm()) {
            return false;
        }
    }

    return true;
}

function comment_box(comment_id, work)
{
    var el_id = '';
    // �ڸ�Ʈ ���̵� �Ѿ���� �亯, ����
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
        // �ڸ�Ʈ ����
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
    if (confirm("�� �ڸ�Ʈ�� �����Ͻðڽ��ϱ�?")) location.href = url;
}

comment_box('', 'c'); // �ڸ�Ʈ �Է����� ���̵��� ó���ϱ����ؼ� �߰� (root��)
</script>
<? } ?>

<? if($cwin==1) { ?></td><tr></table><p align=center><a href="javascript:window.close();"><img src="<?=$board_skin_path?>/img/btn_close.gif" border="0"></a><br><br><?}?>

<!-- post ������� javascript submit�� ���� -->
<script type="text/javascript">
function post_submit(action_url, bo_table, wr_id, comment_id, flag, msg)
{
	var f = document.fpost;
  var submit_msg = msg + "�� �����ϰڽ��ϱ�?";
  
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
