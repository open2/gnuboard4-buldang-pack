<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

$notice_count = $global_notice_count + $arr_notice_count;
?>

<!-- �з� ����Ʈ �ڽ�, �Խù� ���, ������ȭ�� ��ũ -->
<div>
    <? if ($is_category) { ?>
    <form class="form-control" name="fcategory" method="get">
        <select name=sca onchange="location='<?=$category_location?>'+<?=strtolower($g4[charset])=='utf-8' ? "encodeURIComponent(this.value)" : "this.value"?>;">
        <option value=''>��ü</option><?=$category_option?>
        </select>
    </form>
    <? } ?>

    <div class="pull-right">
        <span class="badge"><?=number_format($total_count)?></span>
        <? if ($rss_href) { ?><a href='<?=$rss_href?>' class="btn btn-default btn-sm"><i class='fa fa-rss'></i></a><?}?>
        <? if ($admin_href) { ?><a href="<?=$admin_href?>" class="btn btn-default btn-sm"><i class='fa fa-cog'></i></a><?}?>
    </div>

    <div>
    <?=subject_sort_link('wr_good', $qstr2, 1)?>��õ��</a>
    <?=subject_sort_link('wr_hit', $qstr2, 1)?>��ȸ��</a>
    <?=subject_sort_link('wr_comment', $qstr2, 1)?>�ڸ�Ʈ��</a>
    <? if ($is_checkbox) { ?><input class="form-control" onclick="if (this.checked) all_checked(true); else all_checked(false);" type=checkbox><?}?>
    </div>

</div>

<form name="fboardlist" method="post" style="margin:0px;">
<input type="hidden" name="bo_table" value="<?=$bo_table?>">
<input type="hidden" name="sfl"  value="<?=$sfl?>">
<input type="hidden" name="stx"  value="<?=$stx?>">
<input type="hidden" name="spt"  value="<?=$spt?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="sw"   value="">

<div class="container">
<ul class="list-inline col-sm-10">
<?
//for ($i=0; $i<count($list); $i++) {
for ($i=0; $i<30; $i++) {
?>
    <li class="thumbnail col-sm-1">
        <img src="../img/logo_opencode.gif">
    </li>
<? } ?>
<? if (count($list) == 0) { echo "<div class='well'>�Խù��� �����ϴ�.</div>"; } ?>
</ul>
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
</script>
<? } ?>