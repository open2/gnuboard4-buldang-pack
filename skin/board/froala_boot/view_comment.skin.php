<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

if ($is_admin || !$view[is_notice] || !in_array( $write['mb_id'],explode(",", trim($board['bo_notice_comment_allow']) ) ) )
    $check_comment_allow = 1;

// cwin=1�̸� view.skin.php�� ���� �����Ƿ�, ��Ų���� ����ϴ� lib �о���̱�
if ($cwin == 1)
    include_once("$g4[path]/lib/view.skin.lib.php");
?>

<script type="text/javascript">
// ���ڼ� ����
var char_min = parseInt(<?=$comment_min?>); // �ּ�
var char_max = parseInt(<?=$comment_max?>); // �ִ�
</script>

<? if ($cwin==1) { ?><div width="<?=$width?>" class="table-responsive"><?}?>

<!-- �ڸ�Ʈ ����Ʈ -->
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
                // $board[bo_new] �ð����� ���ο� �ڸ�Ʈ�� ������ icon_new.gif�� �ڿ�
                if ($list[$i]['wr_datetime'] >= date("Y-m-d H:i:s", $g4['server_time'] - ($board['bo_new'] * 3600))) 
                    echo "<i class=\"fa fa-pagelines\" title='new articla/����'></i>";
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

                <!-- �ڸ�Ʈ ��� -->
                <div style='line-height:20px; padding:7px; word-break:break-all; word-wrap:break-word; overflow:hidden; clear:both; '>
                <?
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
            �̸� <INPUT type=text maxLength=20 size=10 name="wr_name" itemname="�̸�" required class=ed>
            �н����� <INPUT type=password maxLength=20 size=10 name="wr_password" itemname="�н�����" required class=ed>
            <script src='https://www.google.com/recaptcha/api.js'></script>
            <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>" style="float:right"></div>
        <? } ?>
        <label><input type=checkbox id="wr_secret" name="wr_secret" value="secret">��б�</label>
        <? if ($comment_min || $comment_max) { ?><span id=char_count></span>����<?}?>
    </td>
</tr>
<tr>
    <td width=95%>
    <!-- �ڸ�Ʈ ��ġ�� ����ݴϴ� -->
    <a name="g4_comment"></a>

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
        <textarea class="form-control" id="wr_content" name="wr_content" rows=8 itemname="����" required
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
    var pattern = /(^\s*)|(\s*$)/g; // \s ���� ����
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

    if (typeof(grecaptcha) != 'undefined') {
        var v = grecaptcha.getResponse();
        if(v.length == 0) {
            alert("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���.");
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

<!-- ����Ͽ����� �α���â�� �Ⱥ��Դϴ�. �ڸ�Ʈ �Է��� ���� �α���â�� �����ִ°� �����ϴ� -->
<? if ($member['mb_id'] == "" && $board['bo_comment_level'] > 1) {
    $login_url = "<?=$g4[bbs_path]?>/login.php?wr_id=$wr_id{$qstr}&url=".urlencode("$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$wr_id#g4_comment");
?>
<div class="well"><a href="<?=$login_url?>" title="login">�α��� �Ͻø� ����� ���� �� �ֽ��ϴ�</a></div>
<? } ?>

<? if($cwin==1) { ?></div><p align=center><a class="btn btn-default" href="javascript:window.close();">�� ��</a><?}?>

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
