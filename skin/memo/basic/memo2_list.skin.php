<form name=fsearch method=get role="form" class="form-inline">
<input type='hidden' name='kind' value='<?=$kind?>'>
<div class="container">
    <strong><a href="<?=$memo_url?>?kind=<?=$kind?>"><?=$memo_title?></a>&nbsp;
    ( <? if ($kind == "recv") echo "<a href='$memo_url?kind=recv&unread=only' title='����������'><font color=red>$total_count_recv_unread</font></a> / "?><a href='<?=$memo_url?>?kind=$kind'><?=number_format($total_count)?></a>
    </strong>
    /&nbsp;<a href="<?=$memo_url?>?kind=<?=$kind?>&sfl=me_file&stx=me_file"><i class="fa fa-file"></i></a>
    )

    <a class="btn btn-navbar btn-xs pull-right hidden-lg hidden-md" data-toggle="collapse" data-target=".navbar-ex4-collapse" style="margin-right:10px;">
        <i class="glyphicon glyphicon-search"></i>
    </a>

    <div class="pull-right collapse navbar-collapse navbar-ex4-collapse">
        <div class="form-group" role="search">
        <select name='sfl' id='sfl' class="form-control">
            <option value="me_subject_memo">����+����</option>
            <option value="me_subject">����</option>
            <option value="me_memo">����</option>
        <? if ($kind == "recv" or $kind == "spam" or $kind == "notice") { ?>
            <option value="me_send_mb_nick">����<? if ($config['cf_memo_mb_name']) echo "(�̸�)"; else echo "(����)"; ?></option>
            <option value="me_send_mb_id">����(���̵�)</option>
        <? } else if ($kind == "send") { ?>
            <option value="me_recv_mb_nick">����<? if ($config['cf_memo_mb_name']) echo "(�̸�)"; else echo "(����)"; ?></option>
            <option value="me_recv_mb_id">����(���̵�)</option>
        <? } else if ($kind == "save" or $kind == "trash") { ?>
            <option value="me_send_mb_nick">����<? if ($config['cf_memo_mb_name']) echo "(�̸�)"; else echo "(����)"; ?></option>
            <option value="me_recv_mb_id">����(���̵�)</option>
            <option value="me_send_mb_nick">����<? if ($config['cf_memo_mb_name']) echo "(�̸�)"; else echo "(����)"; ?></option>
            <option value="me_send_mb_id">����(���̵�)</option>
        <? } ?>
        </select>
        </div>
        <div class="form-group">
            <input  class="form-control" name="stx" type="text" value='<?=$stx?>' maxlength=15 size="15" itemname="�˻���" required />
        </div>
        <button class="btn btn-primary">�˻�</button>
    </div>
</div>
</form>

<form name="fboardlist" method="post" role="form" class="form-inline">
<input type=hidden name=kind value="<?=$kind?>">

<div class="container">
<table class="table table-hover table-condensed table-borderless" width="100%">
<thead>
<tr class="success">
    <th width=35>
    <!-- ������������ ���� ������ ����... -->
    <input name="chk_me_id_all" type="checkbox" onclick="if (this.checked) all_checked(true); else all_checked(false);" />
    </th>
    <th class="hidden-xs" width=110><?=$list_title ?></th>
    <th>�� ��</th>
    <th class=width=80>�߽�</th>
    <th width=80>
    <? if ($kind == 'notice') {
        if ($is_admin=='super' || $member['mb_id']==$view['me_send_mb_id']) { ?>  
            ���ŷ���
        <? } ?>
    <? } else { ?>
        ����
    <? } ?>
    </th>
</tr>
</thead>

<? for ($i=0; $i<count($list); $i++) { // ����� ��� �մϴ�. ?>
<tr>
    <td>
        <!-- ������������ ���� ������ ����... -->
        <? if ($kind !== 'notice') { ?>
        <input name="chk_me_id[]" type="checkbox" value="<?=$list[$i][me_id]?>" />
        <? } ?>
    </td>
    <td class="hidden-xs"><?=$list[$i]['name']?></td>
    <td align="left" class="hidden-xs">
        <?
        if ($list[$i]['read_datetime'] == '���� ����' or $list[$i]['read_datetime'] == '���� ����') {
            $style1 = "<strong>";
            $style2 = "</strong>";
        } else {
            $style1 = "";
            $style2 = "";
        }
        $view_url = $list[$i]['view_href'] . "&page=$page&sfl=$sfl&stx=$stx&unread=$unread";
        ?>
        <? if ($list[$i]['me_file']) { ?><i class="fa fa-file"></i>&nbsp;<?}?><a href='<?=$view_url?>' title='<?=$list[$i]['subject']?>'><?=$style1?><?=cut_str($list[$i]['subject'],27)?><?=$style2?></a>
        </td>
        <?
        // ���������� ���� ��¥��???
        if ($kind == 'notice') { 
            if ($is_admin=='super' || $member[mb_id]==$view[me_send_mb_id])
                $list[$i]['read_datetime'] = $list[$i]['me_recv_mb_id'];
            else 
                $list[$i]['read_datetime'] = "";
        }
        ?>
        <td class="visible-xs">
        <? if ($list[$i]['me_file']) { ?><i class="fa fa-file"></i>&nbsp;<?}?><a href='<?=$view_url?>' title='<?=$list[$i]['subject']?>'><?=$style1?><?=cut_str($list[$i]['subject'],60)?><?=$style2?></a>
        <br>
        <small>
        <?=$list[$i]['name']?>
        </small>
        </td>
        <td <?=$style?>><?=$list[$i]['send_datetime']?></td>
        <td <?=$style?>><?=$list[$i]['read_datetime']?></td>
    </tr>
    <? } ?>
    <? if ($i==0) { ?>
    <tr>
        <td align=center height=100 colspan=5>�ڷᰡ �����ϴ�.</td>
    </tr>
    <? } ?>
    <tfoot>
    <tr>
        <td colspan=5>
        <div class="btn-group">
            <? if ($i > 0 and $kind !='notice') { ?>
            <a href="javascript:select_delete();" class="btn btn-default" title="delete/����"><i class="fa fa-trash-o"></i></a>
            <? } ?>
            <? if ($i > 0 and $kind == "trash") { ?>
            <a href="javascript:all_delete_trash();" class="btn btn-default">Empty Trash</a>
            <? } ?>
        </div>
        <div class="pull-right">
            <ul class="pagination" style="margin-top:0;margin-bottom:0;" >
            <?
            $write_pages = get_paging($config['cf_write_pages_xs'], $page, $total_page, "?&kind=$kind&sfl=$sfl&stx=$stx&unread=$unread&page="); 

            // �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �پ��ϰ� ����� �� �ֽ��ϴ�.
            $write_pages = str_replace("����", "<i class='fa fa-angle-left'></i>", $write_pages);
            $write_pages = str_replace("����", "<i class='fa fa-angle-right'></i>", $write_pages);
            $write_pages = str_replace("ó��", "<i class='fa fa-angle-double-left'></i>", $write_pages);
            $write_pages = str_replace("�ǳ�", "<i class='fa fa-angle-double-right'></i>", $write_pages);

            echo "$write_pages";
            ?>
            </ul>
        </div>
        </td>
    </tr>
    </tfoot>
</table>
</div>
</form>

<?
// �ϴܺο� �������� �⺻ ��������
$msg = "";
if ($kind == "write") { // ���� �϶��� �޽����� ��� �մϴ�.
    $msg .= "<li>�������� ���� �߼۽� �ĸ�(,)�� ���� �մϴ�.";
    if ($config['cf_memo_use_file'] && $config['cf_memo_file_size']) {
        $msg .= "<li>÷�ΰ����� ������ �ִ� �뷮�� " .$config['cf_memo_file_size'] . "M(�ް�) �Դϴ�.";
    }
    if ($config['cf_memo_send_point']) 
        $msg .= "<li>���� ������ ȸ���� ".number_format($config['cf_memo_send_point'])."���� ����Ʈ�� �����մϴ�.";
}
if ($kind == "send") { // ���������� �϶��� �޽����� ��� �մϴ�.
    $msg .= "<li>���� ���� ������ �����ϸ�, �߽��� ���(������ �����Կ��� ����) �˴ϴ�.";
}
if ($kind == "send" || $kind == "recv") { // ���������� �϶��� �޽����� ��� �մϴ�.
    $msg .= "<li>�����ȵ� ������ " . $config['cf_memo_del'] . "�� �� �����ǹǷ� �߿��� ������ �����Ͻñ� �ٶ��ϴ�.";
}
if ($msg !== "") { 
    echo '<div class="container"><div class="panel panel-default"><div class="panel-heading">';
    echo "<ul>$msg</ul>";
    echo '</div></div></div>';
} ?>

<?
// ���� ���� include
$ad_file = "$memo_skin_path/memo2_adsense.php";
if (file_exists($ad_file)) {
    include_once($ad_file);
}
?>

<script type="text/javascript">
if ('<?=$stx?>') {
    document.fsearch.sfl.value = '<?=$sfl?>';
}
</script>