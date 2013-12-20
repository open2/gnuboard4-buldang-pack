<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

$subject_len = 60;

$whatson_url = "$g4[bbs_path]/whatson.php?check=1&rows=30";
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
        <div>
            <div class="btn-group">
                <a class="btn btn-default" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" ?>
                Memo (<strong><?=$member['mb_memo_unread']?></strong>)
                </a>
                <a class="btn btn-default" href="<?=$g4['bbs_path']?>/scrap.php?head_on=1&snb=scrap" >Scrap</a>
            </div>
            <a class="btn btn-default" href="<?=$g4['bbs_path']?>/member_confirm.php?url=register_form.php">Modify</a>
            <a class="btn btn-default pull-right" href="<?=$g4['bbs_path']?>/logout.php">Logout</a>
        </div>
        <div style="margin-top:5px;">
            <div class="btn-group">
                <a class="btn btn-default" href="<?=$whatson_url?>" >What's On</a>
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/new.php?gr_id=&mb_id=<?=$member[mb_id]?>">나의게시글</a>
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/new.php?gr_id=&view_type=c&mb_id=<?=$member[mb_id]?>">나의코멘트</a>
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/my_good.php?w=good&head_on=1">추천한글</a>
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/my_good.php?w=nogood&head_on=1">비추천한글</a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/singo_search.php">신고된 내역</a>
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/recycle_list.php">휴지통</a>
            </div>
        </div>
    </div>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
    <?
    include_once("$g4[path]/lib/whatson.lib.php");
    include_once("$g4[path]/lib/latest.my.lib.php");
    ?>
    <?=whatson('basic')?>
    <?=latest_my('basic','나의 게시글',80,10,25);?>
    <?=latest_my('basic','나의 코멘트',80,10,25,'comment');?>
</div>