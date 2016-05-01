<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

$subject_len = 60;

$whatson_url = "$g4[bbs_path]/whatson.php?check=1&rows=30";
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <a href="<?=$g4[bbs_path]?>/myon.php?head=<?=$head?>"><strong>MyOn</strong></a>
        <? if ($is_admin == "super" || $is_auth) { ?><a class="btn btn-primary btn-sm" href="<?=$g4['admin_path']?>/"><i class="fa fa-cog"></i></a><? } ?>
        <div class="pull-right" style="vertical-align:middle;">
            <strong><a href="#" onClick="javascript:win_profile('<?=$member[mb_id]?>');"><?=$member[mb_nick]?></a></strong>&nbsp;
            <? if ($config[cf_use_point]) { ?>
                &nbsp;<a href="javascript:win_point();"><font color="#737373"><small><?=number_format($member[mb_point])?></small></font></a>&nbsp;
            <? } ?>
            <span class="badge"><small><?=$member[mb_level]?></small></span>
        </div>
    </div>
    <div class="panel-body">
        <div class="list-btn-bar">
            <div class="btn-group">
                <a class="btn btn-default" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" ?>
                Memo (<strong><?=$member['mb_memo_unread']?></strong>)
                </a>
                <a class="btn btn-default" href="<?=$g4['bbs_path']?>/scrap.php?head_on=1&snb=scrap" >Scrap</a>
            </div>
            <?
            // ��õ���� ȸ������
            if ($g4['member_suggest_join']) { ?>
                <a class="btn btn-default" href="<?=$g4['path']?>/plugin/recommend/index.php" >������õ</a>
            <? } ?>
            <!--<a class="btn btn-default" href="<?/*=$g4['bbs_path']*/?>/member_confirm.php?url=register_form.php">��������</a>-->
            <!--<a class="btn btn-default pull-right" href="<?/*=$g4['bbs_path']*/?>/logout.php">Logout</a>-->
        </div>
        <div class="list-btn-bar" style="margin-top:5px;">
            <div class="btn-group">
                <!--<a class="btn btn-default" href="<? /*=$whatson_url*/ ?>" >What's On</a>-->
                <!--<a class="btn btn-default" href="<? /*=$g4[bbs_path]*/ ?>/new.php?gr_id=&mb_id=<? /*=$member[mb_id]*/ ?>">���ǰԽñ�</a>-->
                <!--<a class="btn btn-default" href="<? /*=$g4[bbs_path]*/ ?>/new.php?gr_id=&view_type=c&mb_id=<? /*=$member[mb_id]*/ ?>">�����ڸ�Ʈ</a>-->
                <a class="btn btn-default" href="<?= $g4[bbs_path] ?>/my_good.php?w=good&head_on=1">��õ</a>
                <a class="btn btn-default" href="<?= $g4[bbs_path] ?>/my_good.php?w=nogood&head_on=1">����õ</a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" href="<?= $g4[bbs_path] ?>/singo_search.php">�Ű�</a>
                <a class="btn btn-default" href="<?=$g4[bbs_path]?>/recycle_list.php">������</a>
            </div>
            <!--<div class="col-sm-2">
                <?/* include_once("$g4[bbs_path]/my_menu_outlogin_script.php")*/?>
            </div>-->
        </div>

    </div>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
    <?
    include_once("$g4[path]/lib/whatson.lib.php");
    include_once("$g4[path]/lib/latest.my.lib.php");
    ?>
    <?=whatson('basic')?>
    <?=latest_my('basic','���� �Խñ�',80,10,25);?>
    <?=latest_my('basic','���� �ڸ�Ʈ',80,10,25,'comment');?>
</div>