<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Խñ� ������ ���ؼ� (bbs/view.php���� ������ �ڵ�)
set_session("ss_delete_token", $token = uniqid(time()));

// �̸�Ƽ�� �����ϱ�
function emoticon_html($str, $board_skin_path)
{
    if ($str == "no-image")
        return "";

    if ($str >= 1 && $str <= 44) {
        // ���� ���ٰԽ��� �����Ϳ��� ȣȯ�� ����
      	$emo_file = "$str.gif";
    } else if ($str >= 101 && $str <= 143) {
        // ���ο� ��Ʈ��Ʈ�� ���ٰԽ��� �̹���
      	$emo_file = "$str.png";
    } else {
        return ""; // ������ ����ų� �⺻ǥ���� ��� ������� ����
    }
   	$img_src = "<img src='$board_skin_path/emoticons/" . $emo_file . "' border=0 alt=''> ";
	  return $img_src;
}
?>
<? if (!$member[mb_id] || $member[mb_level] >= $board[bo_write_level] ||($is_admin && $w == 'u' && $member[mb_id] != $write[mb_id]))
      include ("$board_skin_path/write.skin.php"); 
?>

<!-- ������ȭ�� ��ũ -->
<? if ($is_checkbox) { ?>&nbsp;&nbsp;<INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox>&nbsp;&nbsp;&nbsp;<?}?>
<? if ($admin_href) { ?><a href="<?=$admin_href?>" class="btn btn-default"><i class='fa fa-cog'></i></a><?}?>

<!-- ���� -->
<form name="fboardlist" method="post" role="form" class="form-inline">
<input type='hidden' name='bo_table' value='<?=$bo_table?>'>
<input type='hidden' name='sfl'  value='<?=$sfl?>'>
<input type='hidden' name='stx'  value='<?=$stx?>'>
<input type='hidden' name='spt'  value='<?=$spt?>'>
<input type='hidden' name='page' value='<?=$page?>'>
<input type='hidden' name='sw'   value=''>
<input type='hidden' name='token' value='<?=$token?>'>

<!-- ��� -->
<? for ($i=0; $i<count($list); $i++) { ?>
    <table role="table" width=100% class="table table-hover table-condensed" style="word-wrap:break-word;margin-bottom:0px;margin-right:0px;margin-left:0px;padding:0;">
    <tr >
    <!-- reply �̿ܿ��� td�� ������ �ȵ�. ���� ���� ��ƸԾ� �̻����� -->
    <? if (strlen($list[$i][wr_reply]) > 0) { ?>
    <td valign=top style="border:0px;">
        <!-- �׳� &nbsp; ����ϸ� �ʹ� ����, strlen���� �ϸ� �ʹ� ����. �ļ��� wr_reply�� 5�踸ŭ��... -->
        <? for ($k=0; $k<(strlen($list[$i][wr_reply])*6); $k++) echo "&nbsp;"; ?>
    </td>
    <?}?>
    <?
    // ���������϶��� success class��.
    if ($list[$i][is_notice])
        $td_class = "class='success'";
    else
        $td_class="";
    ?>
    <td align=left width=100% <?=$td_class?>>
        <? if ($is_checkbox) { ?><input type=checkbox name=chk_wr_id[] value="<?=$list[$i][wr_id]?>"><? } ?>
        <? 
        if ($list[$i][reply]) { 
            if ($list[$i][icon_reply]) echo "<i class=\"fa fa-reply fa-rotate-180\" title='reply/���'></i> ";
        }
        ?>
        <?
        if ($list[$i][is_notice]) // �������� 
            echo "<i class=\"fa fa-microphone\" title='notice/��������'></i> ";
        else {
         		$list[$i][subject] = emoticon_html($list[$i][subject], $board_skin_path);
            echo $list[$i][subject];
        }
        ?>
        <?
 		    $list[$i][wr_content] = conv_content($list[$i][wr_content], 0);

     		echo $list[$i][wr_content];

        // �ؿ��� �ѹ� �� ��� �ϱ� ������ �迭�� ��� �Ӵϴ�.
        $icon_images = "";
        if ($list[$i][icon_new]) $icon_images .= " <i class=\"fa fa-pagelines\" title='new articla/����'></i>";
        if ($list[$i][icon_secret]) $icon_images .= " <i class=\"fa fa-lock\" title='secret/��б�'></i>";
        echo $icon_images;
        ?>

        <div class="pull-right" style="color:gray;">
            <?=$list[$i][name]?>&nbsp;&nbsp;<?=$list[$i][datetime2]?>
        <?
        if ($member[mb_id]) {
        ?>
            <div class="btn-group" style="margin-right:10px;">
            <a href="<?=$write_href?>&w=r&wr_id=<?=$list[$i][wr_id]?>&page=<?=$page?>&sca=<?=$ca_name?>" class="btn btn-default btn-sm">�亯</a>
    		    </div>
        <? } ?>
        <?
        if (($member[mb_id] && ($member[mb_id] == $list[$i][mb_id])) || $is_admin) {
        ?>
            <div class="btn-group" style="margin-right:10px;">
            <a href="<?=$write_href?>&w=u&wr_id=<?=$list[$i][wr_id]?>&page=<?=$page?>&sca=<?=$ca_name?>" class="btn btn-default btn-sm">����</a>
            <a href="javascript:if (confirm('�����Ͻðڽ��ϱ�?')) { location='./delete.php?w=d&bo_table=<?=$bo_table?>&wr_id=<?=$list[$i][wr_id]?>&sca=<?=$sca?>&token=<?=$token?>&page=<?=$page?>';}" class="btn btn-default btn-sm">����</a>
    		    </div>
        <? } ?>
        </div>
    </td>
    </tr>
    </table>
<?}?>

<? if (count($list) == 0) { echo "<table width=100%><tr><td height=100 align=center>�Խù��� �����ϴ�.</td></tr></table>"; } ?>
</form>

<!-- ������ -->
<div class="center-block hidden-xs">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>�����˻�</a></li>"; } ?>
    <?
    // �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �پ��ϰ� ����� �� �ֽ��ϴ�.
    $write_pages = str_replace("����", "<i class='fa fa-angle-left'></i>", $write_pages);
    $write_pages = str_replace("����", "<i class='fa fa-angle-right'></i>", $write_pages);
    $write_pages = str_replace("ó��", "<i class='fa fa-angle-double-left'></i>", $write_pages);
    $write_pages = str_replace("�ǳ�", "<i class='fa fa-angle-double-right'></i>", $write_pages);
    ?>
    <?=$write_pages?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>���İ˻�</a></li>"; } ?>
    </ul>
</div>
<div class="center-block visible-xs">
    <ul class="pagination">
    <? if ($prev_part_href) { echo "<li><a href='$prev_part_href'>�����˻�</a></li>"; } ?>
    <?
    // �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �پ��ϰ� ����� �� �ֽ��ϴ�.
    $write_pages_xs = str_replace("����", "<i class='fa fa-angle-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("����", "<i class='fa fa-angle-right'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("ó��", "<i class='fa fa-angle-double-left'></i>", $write_pages_xs);
    $write_pages_xs = str_replace("�ǳ�", "<i class='fa fa-angle-double-right'></i>", $write_pages_xs);
    ?>
    <?=$write_pages_xs?>
    <? if ($next_part_href) { echo "<li><a href='$next_part_href'>���İ˻�</a></li>"; } ?>
    </ul>
</div>

<!-- ��ũ ��ư, �˻� -->
<form name=fsearch method=get role="form" class="form-inline">
<input type=hidden name=bo_table value="<?=$bo_table?>">
<input type=hidden name=sca      value="<?=$sca?>">
<? if ($list_href) { ?>
<div class="btn-group">
    <a href="<?=$list_href?>" class="btn btn-default"><i class='fa fa-list'></i> ���</a>
</div>
<? } ?>
<? if ($write_href) { ?>
<div class="btn-group">
    <a href="<?=$write_href?>" class="btn btn-default"><i class='fa fa-edit'></i> ����</a>
</div>
<? } ?>
<? if ($is_checkbox) { ?>
<span style='display:inline-block!important;vertical-align:bottom;'>
<div class="btn-group hidden-sm hidden-xs">
    <a href="javascript:select_delete();" class="btn btn-default">���û���</a>
    <a href="javascript:select_copy('copy');" class="btn btn-default">���ú���</a>
    <a href="javascript:select_copy('move');" class="btn btn-default">�����̵�</a>
    <? if ($is_category) { ?>
    <a href="javascript:select_category();"  class="btn btn-default">ī�װ�����</a>
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
        <option value='wr_subject'>����</option>
        <option value='wr_content'>����</option>
        <option value='wr_subject||wr_content'>����+����</option>
        <option value='mb_id,1'>ȸ�����̵�</option>
        <option value='mb_id,0'>ȸ�����̵�(��)</option>
        <option value='wr_name,1'>�̸�</option>
        <option value='wr_name,0'>�̸�(��)</option>
        </select>
    </div>
    <div class="form-group">
        <label class="sr-only" for="stx">stx</label>
        <input name=stx maxlength=15 size=10 itemname="�˻���" required value='<?=stripslashes($stx)?>' class="form-control">
    </div>
    <div class="form-group">
        <label class="sr-only" for="sop">sop</label>
        <select name=sop class="form-control">
            <option value=and>and</option>
            <option value=or>or</option>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary">�˻�</button>
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
        alert(str + "�� �Խù��� �ϳ� �̻� �����ϼ���.");
        return false;
    }
    return true;
}

// ������ �Խù� ����
function select_delete() {
    var f = document.fboardlist;

    str = "����";
    if (!check_confirm(str))
        return;

    if (!confirm("������ �Խù��� ���� "+str+" �Ͻðڽ��ϱ�?\n\n�ѹ� "+str+"�� �ڷ�� ������ �� �����ϴ�"))
        return;

    f.action = "./delete_all.php";
    f.submit();
}

// ������ �Խù� ���� �� �̵�
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "����";
    else
        str = "�̵�";
                       
    if (!check_confirm(str))
        return;

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}

// ������ �Խù� ī�װ��� ����
function select_category() {
    var f = document.fboardlist;
    var f2 = document.fsearch;

    str = "ī�װ�����";
    if (!check_confirm(str))
        return;

    str = f2.sca2.value;
    if (!confirm("������ �Խù��� ī�װ��� "+str+" ���� ���� �Ͻðڽ��ϱ�?"))
        return;

    // sca�� ���� �־������.
    f.sca.value = str;

    f.action = "./category_all.php";
    f.submit();
}
</script>
<? } ?>
<!-- �Խ��� ��� �� -->