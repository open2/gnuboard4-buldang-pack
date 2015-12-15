<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

$notice_count = $global_notice_count + $arr_notice_count;
?>

<!-- �з� ����Ʈ �ڽ�, �Խù� ���, ������ȭ�� ��ũ -->
<div>
    <div class="btn-group">
        <a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>" class="btn btn-default"><?=$board[bo_subject]?></a>
    </div>

    <? if ($write_href) { ?>
    <div class="btn-group">
        <a href="<?=$write_href?>" class="btn btn-default"><i class='fa fa-edit'></i> ����</a>
    </div>
    <? } ?>

    <? include("$g4[bbs_path]/my_menu_add_script.php");?>
    <? if ($rss_href) { ?><a href='<?=$rss_href?>' class="btn btn-default"><i class='fa fa-rss'></i></a><?}?>
    <? if ($admin_href) { ?><a href="<?=$admin_href?>" class="btn btn-default"><i class='fa fa-cog'></i></a><?}?>

    <? if ($notice_count > 0) { ?>
    <a href="#" class="btn btn-default notice_flip" title="�������� Flip"><i class="fa fa-microphone"></i></a>
    <? } ?>

    <? if ($is_category) { ?>
    <div style="float:right;margin-left:10px;">
    <form name="fcategory" method="get" role="form" class="form-inline">
        <select class="form-control" name=sca onchange="location='<?=$category_location?>'+<?=strtolower($g4[charset])=='utf-8' ? "encodeURIComponent(this.value)" : "this.value"?>;">
        <option value=''>��ü</option><?=$category_option?></select>
    </form>
    </div>
    <? } ?>

    <div class="hidden-xs" style="block:inline;float:right;margin-right:3px;">
        <?=$page?>/<?=$total_page?>
    </div>
</div>

<!-- ���� -->
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
    <th width=50px class="hidden-xs"><?=subject_sort_link('wr_id', $qstr2, 1)?>��ȣ</a></th>
    <? if ($is_checkbox) { ?><th class="hidden-xs"><INPUT onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox></th><?}?>
    <th>����<span class="visible-xs pull-right" style="font-weight: normal;color:#B8B8B8;">Page <?=$page?>/<?=$total_page?></span></th>
    <th width=120px class="hidden-xs">�۾���</th>
    <th width=70px class="hidden-xs"><?=subject_sort_link('wr_datetime', $qstr2, 1)?>��¥</a></th>
    <th width=80px class="hidden-xs"><?=subject_sort_link('wr_hit', $qstr2, 1)?>��ȸ</a></th>
    <? if ($is_good) { ?><th width=60px class="hidden-xs"><?=subject_sort_link('wr_good', $qstr2, 1)?>��õ</a></th><?}?>
    <? if ($is_nogood) { ?><th width=60px class="hidden-xs"><?=subject_sort_link('wr_nogood', $qstr2, 1)?>����</a></th><?}?>
</tr>
</thead>

<!-- ��� -->
<? for ($i=0; $i<count($list); $i++) { ?>
<?
$is_notice = "";
if ($list[$i][is_notice])
    $is_notice = "is_notice";
?>
<tr class="<?=$is_notice?>"> 
    <td class="hidden-xs">
        <? 
        if ($list[$i][is_notice]) // �������� 
            echo "<i class=\"fa fa-microphone\" title='notice/��������'></i> ";
        else if ($wr_id == $list[$i][wr_id]) // ������ġ
            echo "<span style='font-weight:bold; color:#E15916;'>{$list[$i][num]}</span>";
        else
            echo "<span style='color:#BABABA;'>{$list[$i][num]}</span>";
        ?>
    </td>
    <? if ($is_checkbox) { ?><td class="hidden-xs"><input type=checkbox name=chk_wr_id[] value="<?=$list[$i][wr_id]?>"></td><? } ?>
    <td class="hidden-xs" align=left style='word-break:break-all;'>
        <?
        echo $list[$i][reply];
        if ($list[$i][icon_reply]) echo "<i class=\"fa fa-reply fa-rotate-180\" title='reply/���'></i> ";
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

        // �ؿ��� �ѹ� �� ��� �ϱ� ������ �迭�� ��� �Ӵϴ�.
        $icon_images = "";
        if ($list[$i][icon_new]) $icon_images .= " <i class=\"fa fa-pagelines\" title='new articla/����'></i>";
        if ($list[$i][icon_file]) $icon_images .=  " <i class=\"fa fa-file-o\" title='attached file/÷������'></i>";
        if ($list[$i][icon_link]) $icon_images .=  " <i class=\"fa fa-link\" title='link/��ũ'></i>";
        if ($list[$i][icon_hot]) $icon_images .= " <i class=\"fa fa-fire\" title='hot article/��Ƚ�� ���� ��'></i>";
        if ($list[$i][icon_secret]) $icon_images .= " <i class=\"fa fa-lock\" title='secret/��б�'></i>";
        echo $icon_images;
        ?>
        </td>
    <td class="hidden-xs"><?=$list[$i][name]?></td>
    <td class="hidden-xs"><?=$list[$i][datetime2]?></td>
    <td class="hidden-xs"><?=$list[$i][wr_hit]?></td>
    <? if ($is_good) { ?><td class="hidden-xs" align="center"><?=$list[$i][wr_good]?></td><? } ?>
    <? if ($is_nogood) { ?><td class="hidden-xs" align="center"><?=$list[$i][wr_nogood]?></td><? } ?>
    <!-- 
    xs ������� 40���� �̻��̸� table width�� �Ѿ ���� ��ũ���� ����ϴ� 
    �׷���, ���� ����ϴ� row�� ����� ����ϴ�.
    xs ��������� �Ʒ�ó�� 1���� td�� ��� �˴ϴ�. �ٸ� ���� ��� hidden.
    �� ���� ����� ���� ������ ������ ȯ�� �մϴ�.
    -->
</tr>
<tr class="visible-xs">
    <td align=left style='word-break:break-all;'>
        <div>
        <?
        if ($list[$i][is_notice]) // �������� 
            echo "<i class=\"fa fa-microphone\" title='notice/��������'></i> ";


        if ($list[$i][icon_reply]) echo "<i class=\"fa fa-reply fa-rotate-180\" title='reply/���'></i> ";
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
        // �˻��� �ϸ� $list[$i][subject]�� tag�� ��� �����Ƿ� tag�� ������ ���ڼ� ���̱⸦ �ؾ� ��...
        if ($sfl && $stx)
            echo "<span $style>" . cut_str(strip_tags($list[$i][subject]), 40) . "</span>";
        else
            echo "<span $style>" . cut_str($list[$i][subject], 40) . "</span>";
        echo "</a>";

        if ($list[$i][comment_cnt]) 
            echo " <a href=\"{$list[$i][comment_href]}\"><span style='color:#EE5A00;'><small>{$list[$i][comment_cnt]}</small></span></a>";

        // ������ ������ $icon_images ���
        echo $icon_images;
        ?>
        </div>
        <span class="pull-right">
        <font style="color:#BABABA;">
        <?=$list[$i][datetime2]?>&nbsp;&nbsp;
        <?=$list[$i][wr_hit]?>&nbsp;&nbsp;
        </font>
        <?=$list[$i][name]?>
        </span>
    </td>
</tr>
<?}?>

<? if (count($list) == 0) { echo "<tr><td colspan=6 height=100 align=center>�Խù��� �����ϴ�.</td></tr>"; } ?>
</table>
</div>
</form>

<!-- ������ -->
<div class="hidden-xs" style="text-align:center;">
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

<?
// flip cookie�� �����ͼ� �� �մϴ�
$ck_name = $bo_table . "_flip_datetime";
$flip_datetime = $_COOKIE[$ck_name];
if ($g4['last_notice_datetime'] > $flip_datetime) {
    // flip�� ���Ŀ� ������ �ö���� flip cookie�� �������ְ�, flip�� ���� �ʰ� �մϴ�. ���ο� ������ �ݵ�� ���� �մϴ�.
?>
    <script type="text/javascript">
    createCookie( '<?=$ck_name?>', '', 365);
    $('.is_notice').show();
    </script>
<?
} else {
    // flip�� �߰�, ���ο� ������ ������ ������ �����ݴϴ�
?>
    <script type="text/javascript">
    $('.is_notice').hide();
    $('.notice_flip').addClass('active');   // ��ư�� ������ ���·� �ٲ��ݴϴ�.
    </script>
<? } ?>

<script type="text/javascript">
$('.notice_flip').click(function() {
    $('.is_notice').toggle();
    createCookie( '<?=$ck_name?>', '<?=$g4[time_ymdhis]?>', 365);
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

    f.action = "<?=$g4[bbs_path]?>/delete_all.php";
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
    f.action = "<?=$g4[bbs_path]?>/move.php";
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