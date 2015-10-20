<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>

<div class="container">
<span>
<strong><a href='<?=$memo_url?>?kind=<?=$kind?>'><?=$memo_title?></a></strong>
<?if ($write_header_msg) echo "&nbsp;/&nbsp;<strong>$write_header_msg</strong>"?>
</span>
<div class="btn-group pull-right">
    <a class="btn btn-default" href="javascript:popup_id('fmemoform','<?=$ss_id?>',200,500);">ȸ���˻�</a>
</div>
<div class="pull-right">
    <? if (count($my_friend) > 0) { ?>
    <select class="form-control pull-right" class=quick_move onchange="friend_add(this.value)" >
        <option value="">���� ģ����</option>
        <option value="">-------------------------</option>
        <? for ($i=0; $i<count($my_friend); $i++) {?>
            <option value="<?=$my_friend[$i][fr_id]?>"><?=$my_friend[$i][fr_id]?>-<?=cut_str($my_friend[$i][mb_nick],10)?></option>
        <? } ?>
        </select>
    <? } ?>
</div>
</div>

<form role="form" class="form-inline" name=fmemoform method=post enctype='multipart/form-data' onsubmit="return fmemoform_submit(this);" style="margin:0px;">
<input type=hidden name=me_send_mb_id value="<?=$member[mb_id]?>">
<div class="container">

<? $ss_id = 'me_recv_mb_id' ?>
<? if ($option == 'notice') { // ���������� ��� ?>
    <? include_once("$g4[admin_path]/admin.lib.php")?>
    <input type="hidden" name="<?=$ss_id?>" id="<?=$ss_id?>" required="required" itemname="�޴� ȸ�����̵�" value="<?=$me_recv_mb_id?>" placeholder="receive member id"/>
    ȸ������
    <?=get_member_level_select('notice_level_1', 2, 10, 2) ?> - <?=get_member_level_select('notice_level_2', 2, 10, 10) ?>
<? } else { ?>
    <div class="input-group">
        <span class="input-group-addon">����</span>
        <input class="form-control" type="text" name="<?=$ss_id?>" id="<?=$ss_id?>" required="required" itemname="�޴� ȸ�����̵�" value="<?=$me_recv_mb_id?>" placeholder="receive member id"/>
    </div>
<? } ?>

<div class="input-group">
    <span class="input-group-addon">����</span>
    <input class="form-control" type="text" name="me_subject" id="me_subject" required="required" value='<?=$subject?>' placeholder="subject">
</div>

<div style="margin-bottom:10px;">
<? if ($is_dhtml_editor) { ?>
    <input type=hidden value="html1" name="html">
    <?
    // cheditor�� ���� ���� ����
    include_once("$g4[path]/lib/cheditor4.lib.php");
    echo "<script type='text/javascript' src='$g4[cheditor4_path]/cheditor.js?v=$g4[cheditor_ver]'></script>";
    echo cheditor1('me_memo', '100%', '300px');
    // cheditor ������ �޴��� �Ϻθ� disable
    ?>
    <script type='text/javascript'>
    ed_me_memo.config.usePreview = false;
    ed_me_memo.config.NewDocument = false;
    ed_me_memo.config.usePasteFromWord = false;
    ed_me_memo.config.useOrderedList = false;
    ed_me_memo.config.useUnOrderedList = false;
    ed_me_memo.config.useOrderedList = false;
    ed_me_memo.config.useUnOrderedList = false;
    ed_me_memo.config.useOutdent = false;
    ed_me_memo.config.useIndent = false;
    ed_me_memo.config.useJustifyRight = false;
    ed_me_memo.config.useJustifyFull = false;

    ed_me_memo.config.useLink = false;
    ed_me_memo.config.useUnLink = false;
    ed_me_memo.config.useFlash = false;
    ed_me_memo.config.useMedia = false;
    ed_me_memo.config.useImageUrl = false;
    </script>
    <?
    echo cheditor2('me_memo', $content);
} else { ?>
    <textarea class="form-control" name='me_memo' id='me_memo' rows=15 style='width:100%;' required itemname='����' tabindex=1><?=$content?></textarea>
<? } ?>
</div>

<!-- ����÷���ϱ�-->
<? if ($config['cf_memo_use_file']) { ?>
<div>
    <? if ($memo_dir_msg) {
        echo $memo_dir_msg;
    } else { ?>
        <div class="form-group">
        <input class="form-control" type="file" id="memo_file" name="memo_file" style="width:100%">
        </div>
    <? } ?>
</div>
<? } ?>

<div class="pull-left">
    <a class="btn btn-default" href='<?=$memo_url?>?kind=recv'>List</a>
</div>

<div class="pull-right">
    <button type="submit" class="btn btn-success" id="btn_submit">Send</button>&nbsp;&nbsp;&nbsp;
</div>

</div>
</form>

<script type="text/javascript">

<? if ($option != 'notice') { ?>

with (document.fmemoform) {
    if (me_recv_mb_id.value == "")
        me_recv_mb_id.focus();
    else
        me_subject.focus();
}       
<? } ?>

function fmemoform_submit(f) {
    var s = "";

    <?
    if ($is_dhtml_editor) {
        echo cheditor3('me_memo');
        echo "if (!document.getElementById('tx_me_memo').value) { alert('������ �Է��Ͻʽÿ�.'); return; } ";
    }
    ?>

    document.getElementById('btn_submit').disabled = true;
    
    <? if ($option == 'notice') {?>
        f.action = "./memo2_form_notice_update.php";
    <? } else { ?>
        f.action = "./memo2_form_update.php";
    <? } ?>

    return true;
}

function friend_add(fr_id)
{
  if (fr_id == "") // fr_id ���� ������ return
    return;
    
  if (document.fmemoform.<?=$ss_id?>.value.length > 0) {
    document.fmemoform.<?=$ss_id?>.value = document.fmemoform.<?=$ss_id?>.value + "," + fr_id;
  } else {
    document.fmemoform.<?=$ss_id?>.value = fr_id;
  }
}
</script>