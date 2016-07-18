<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<div class="container">
<span>
<strong><a href='<?=$memo_url?>?kind=<?=$kind?>'><?=$memo_title?></a></strong>
<?if ($write_header_msg) echo "&nbsp;/&nbsp;<strong>$write_header_msg</strong>"?>
</span>
<div class="btn-group pull-right">
    <a class="btn btn-default" href="javascript:popup_id('fmemoform','<?=$ss_id?>',200,500);">회원검색</a>
</div>
<div class="pull-right">
    <? if (count($my_friend) > 0) { ?>
    <select class="form-control pull-right" class=quick_move onchange="friend_add(this.value)" >
        <option value="">나의 친구들</option>
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
<? if ($option == 'notice') { // 공지쪽지인 경우 ?>
    <? include_once("$g4[admin_path]/admin.lib.php")?>
    <input type="hidden" name="<?=$ss_id?>" id="<?=$ss_id?>" required="required" itemname="받는 회원아이디" value="<?=$me_recv_mb_id?>" placeholder="receive member id"/>
    회원레벨
    <?=get_member_level_select('notice_level_1', 2, 10, 2) ?> - <?=get_member_level_select('notice_level_2', 2, 10, 10) ?>
<? } else { ?>
    <div class="input-group">
        <span class="input-group-addon">수신</span>
        <input class="form-control" type="text" name="<?=$ss_id?>" id="<?=$ss_id?>" required="required" itemname="받는 회원아이디" value="<?=$me_recv_mb_id?>" placeholder="receive member id"/>
    </div>
<? } ?>

<div class="input-group">
    <span class="input-group-addon">제목</span>
    <input class="form-control" type="text" name="me_subject" id="me_subject" required="required" value='<?=$subject?>' placeholder="subject">
</div>

<div style="margin-bottom:10px;">
<? if ($is_dhtml_editor) { ?>
    <input type=hidden value="html1" name="html">
    <?
    // cheditor를 쓰기 위한 설정
    include_once("$g4[path]/lib/cheditor4.lib.php");
    echo "<script type='text/javascript' src='$g4[cheditor4_path]/cheditor.js?v=$g4[cheditor_ver]'></script>";
    echo cheditor1('wr_content', '100%', '300px');
    // cheditor 편집기 메뉴의 일부를 disable
    ?>
    <script type='text/javascript'>
    ed_wr_content.config.usePreview = false;
    ed_wr_content.config.NewDocument = false;
    ed_wr_content.config.usePasteFromWord = false;
    ed_wr_content.config.useOrderedList = false;
    ed_wr_content.config.useUnOrderedList = false;
    ed_wr_content.config.useOrderedList = false;
    ed_wr_content.config.useUnOrderedList = false;
    ed_wr_content.config.useOutdent = false;
    ed_wr_content.config.useIndent = false;
    ed_wr_content.config.useJustifyRight = false;
    ed_wr_content.config.useJustifyFull = false;

    ed_wr_content.config.useLink = false;
    ed_wr_content.config.useUnLink = false;
    ed_wr_content.config.useFlash = false;
    ed_wr_content.config.useMedia = false;
    ed_wr_content.config.useImageUrl = false;
    </script>
    <?
    echo cheditor2('wr_content', $content);
} else { ?>
    <textarea class="form-control" name='wr_content' id='wr_content' rows=15 style='width:100%;' required itemname='내용' tabindex=1><?=$content?></textarea>
<? } ?>
</div>

<!-- 파일첨부하기-->
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
        echo cheditor3('wr_content');
        echo "if (!document.getElementById('tx_wr_content').value) { alert('내용을 입력하십시오.'); return; } ";
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
  if (fr_id == "") // fr_id 값이 없으면 return
    return;
    
  if (document.fmemoform.<?=$ss_id?>.value.length > 0) {
    document.fmemoform.<?=$ss_id?>.value = document.fmemoform.<?=$ss_id?>.value + "," + fr_id;
  } else {
    document.fmemoform.<?=$ss_id?>.value = fr_id;
  }
}
</script>
