<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="container">

<div class="panel panel-default">
  <div class="panel-heading"><h4><strong><?=$po_subject?></strong></h4></div>
 	<? if ($po[po_summary]) { ?>
 	        <div class="panel-body">
 	        <?=$po[po_summary]?>
 	        </div>
 	<? } ?>
</div>

<table class="table table-bordered table-hover table-condensed" width="100%">
<colgroup>
<col>
<col width="170">
<col width="130">
</colgroup>
<tbody>
    <tr class="active">
    <td colspan="2"><?=$po[po_date]?> ~ <?=$po[po_end_date]?></td>
    <th><?=$nf_total_po_cnt?>표</th>
    </tr>
    <? for ($i=1; $i<=count($list); $i++) { ?>
    <tr>
    <th><?=$list[$i][num]?>. <?=$list[$i][content]?></th>
    <td><div class="bar" style="width: 40%;"></div>
        <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?=$list[$i][rate]?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$list[$i][rate]?>%;">
            <span class="sr-only"><?=$list[$i][rate]?>%</span>
        </div>
        </div>
    </td>
    <th><?=$list[$i][cnt]?>표 (<?=number_format($list[$i][rate], 1)?>%)</th>
    </tr>
    <? } ?>
</table>

<? if ($is_etc) { ?>
<? if ($member[mb_level] >= $po[po_etc_level]) { ?>
<div class="panel panel-default">
    <div class="panel-body">
    <h5><?=$po_etc?></h5>
        <form role="form" class="form-inline" name="fpollresult" method="post" onsubmit="return fpollresult_submit(this);" autocomplete="off">
        <input type=hidden name=po_id value="<?=$po_id?>">
        <input type=hidden name=w value="">
        <? if (!$member[mb_id] && $config[cf_use_norobot]) { ?>
        <table width=100%>
            <tr> 
                <td>
                    이름 <input type='text' name='pc_name' size=20 class=input required itemname='이름'> &nbsp;
                    패스워드 <INPUT type=password maxLength=20 size=10 name="pc_password" itemname="패스워드" required class=ed>
                </td>
            </tr>
            <tr> 
                <td align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <script type="text/javascript" src="<?="$g4[path]/zmSpamFree/zmspamfree.js"?>"></script>
                <img id="zsfImg">
                &nbsp;&nbsp;
                <input class='ed' type=input size=10 name=wr_key id=wr_key itemname="자동등록방지" required >&nbsp;&nbsp;왼쪽의 글자를 입력하세요.
                </td>
            </tr>
        </table>
        <? } ?>
        <div class="input-group">
            <input type="text" class="form-control" name='pc_idea' required itemname='의견' maxlength="100" size=85>
            <span class="input-group-btn">
            <button type="submit" class="btn btn-success">쓰기</button>
            </span>
        </div>
        </form>
    </div>
</div>
<? } ?>

<? if (count($list2) > 0) { ?>
<table class="table table-bordered table-hover table-condensed" width="100%">
<colgroup>
<col>
<col width="100px">
<col width="80px">
</colgroup>
<tbody>
<? for ($i=0; $i<count($list2); $i++) { ?>
<tr>
    <td><? if ($list2[$i][del]) { echo $list2[$i][del] . "<button type='submit' class='btn btn-warning btn-xs'>del</button></a>"; } ?>
    <?=$list2[$i][idea]?>
    </td>
    <td align=center><?=$list2[$i][name]?></td>
    <td><?=get_datetime($list2[$i][datetime])?></td>
</tr>
<? } ?>
</table>
<? } ?>

<? } ?>

<div class="panel panel-default">
    <div class="panel-body">
    <h5>다른 투표결과 보기</h5>
        <form name=fpolletc>
            <img src="<?=$g4[bbs_img_path]?>/icon_1.gif" width="15" height="8">
            <select name=po_id onchange="select_po_id(this)"><? for ($i=0; $i<count($list3); $i++) { ?><option value='<?=$list3[$i][po_id]?>'>[<?=$list3[$i][date]?>] <?=$list3[$i][subject]?><? } ?></select><script>document.fpolletc.po_id.value='<?=$po_id?>';</script>
        </form>
    </div>
</div>

</div><!-- container -->

<script language="JavaScript">
function fpollresult_submit(f)
{
    if (typeof(f.wr_key) != 'undefined') {
        if (!checkFrm()) {
            alert ("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요.");
            return false;
        }
    }

    f.action = "./poll_etc_update.php";
    return true;
}
</script>
        
<script language='JavaScript'>
function select_po_id(fld) 
{
    document.location.href = "./poll_result.php?po_id="+fld.options[fld.selectedIndex].value;
}
</script>
