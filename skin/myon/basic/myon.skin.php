<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

$subject_len = 60;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <a href="<?=$g4[bbs_path]?>/myon.php"><strong>MyOn</strong></a>
        <div class="pull-right" style="vertical-align:middle;">
            <strong><a href="#" onClick="javascript:win_profile('<?=$member[mb_id]?>');"><?=$member[mb_nick]?></a></strong>&nbsp;
            <? if ($config[cf_use_point]) { ?>
                &nbsp;<a href="javascript:win_point();"><font color="#737373"><small><?=number_format($member[mb_point])?></small></font></a>&nbsp;
            <? } ?>
            <span class="badge"><small><?=$member[mb_level]?></small></span>
        </div>
    </div>
    <div class="panel-body">
            <div class="btn-group">
                <a class="btn btn-default" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" ?>
                Memo (<strong><?=$member['mb_memo_unread']?></strong>)
                </a>
                <a class="btn btn-default" href="javascript:win_scrap();" >Scrap</a>
            </div>

            <a class="btn btn-default" href="<?=$g4['bbs_path']?>/member_confirm.php?url=register_form.php">Modify</a>
            <a class="btn btn-default" href="<?=$g4['bbs_path']?>/logout.php">Logout</a>
    </div>
</div>




<style type="text/css">
.trs { height:30px; background:#dddddd; text-align:center;}
.status_form_title { background:#eeeeee; width:13%; text-align:center;  }
.status_form_content { text-align:left; padding-left:5px; width:20%; background:#ffffff;  }
.n_title1 { font-family:돋움; font-size:9pt; color:#FFFFFF; }
.n_title2 { font-family:돋움; font-size:9pt; color:#5E5E5E; }
</style>
<table width="100%" height="40" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
    <td >


		
<div style="background-color:rgb(247,247,247); border-width:5px; border-color:white; border-style:solid;"></div>

<table width='100%' >
<tr>
<td>
<? include("$myon_skin_path/tab.html"); ?>
</td>
</tr>
</table>