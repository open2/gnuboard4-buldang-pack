<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<div class="container">
<div class="panel panel-default">
<div class="panel-heading">�ڱ�Ұ�
    <? if ($is_admin) {?>(���̵�: <?=$mb['mb_id']?>)<? }?>
    <div class="pull-right">
        <a class="btn btn-default" style="display:inline;" href="javascript:window.close();">�ݱ�</a>
    </div>
</div>
<div class="panel-body">
    <div>
    <ul>
        <li>�г��� : <?=$mb_nick?>
            <?
            // History�� �����ݴϴ�
            $sql = "select * from $g4[mb_nick_table] where mb_id = '$mb[mb_id]' and end_datetime != '0000-00-00 00:00:00' order by nick_no desc limit 5 ";
            $result = sql_query($sql);
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                echo "&nbsp;<i class=\"fa fa-arrow-left\"></i>&nbsp;" . $row[mb_nick];
            }
            ?>
        </li>
        <li>ȸ������ : <?=$mb[mb_level]?></li>
        <li>����Ʈ : <? if ($mb[mb_point]) { ?><?=number_format($mb[mb_point])?><? } ?></li>
        <? if ($mb[mb_good]) { ?>
        <li>
        ��  õ : <a href="<?=$g4[bbs_path]?>/my_good_ed.php?head_on=1" target=new><?=number_format($mb[mb_good])?></a> ȸ
        </li>
        <? } ?>
        <? if ($mb[mb_nogood]) { ?>
        <li>
        ����õ : <a href="<?=$g4[bbs_path]?>/my_good_ed.php?w=nogood&head_on=1" target=new><?=number_format($mb[mb_nogood])?></a> ȸ
        </li>
        <? } ?>
        <? if ($mb_homepage) { ?>
        <li>
        Ȩ������ : <a href="<?=$mb_homepage?>" target="<?=$config[cf_link_target]?>"><?=$mb_homepage?></a>
        </li>
        <? } ?>
        <li>ȸ�������� : <?=($member[mb_level] >= $mb[mb_level]) ?  substr($mb[mb_datetime],0,10) ." (".number_format($mb_reg_after)." ��)" : "�� �� ����"; ?></li>
        <li>���������� : <?=($member[mb_level] >= $mb[mb_level]) ? $mb[mb_today_login] : "�� �� ����";?></li>
        <?
        // �Ҵ��� - �ְ� �������� ��� mb_memo�� �� �� �ְ�
        if ($is_admin == "super") { ?>
            <li>�����ڸ޸� : <? echo nl2br($mb[mb_memo]) ?></li>
        <? } ?>

        <?
        // �ڱ�Ұ��� ���
        if ($mb[mb_profile]) {
            echo "<hr>" . $mb[mb_profile];
        }
        ?>

        <?
        // ������ ���
        if ($mb[mb_signature]) {
            echo "<hr>" . $mb[mb_signature];
        }
        ?>
    </ul>
    </div>
</div>
</div>
</div>