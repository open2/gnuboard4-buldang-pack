<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="container">
<div class="panel panel-default">
<div class="panel-heading">자기소개
    <? if ($is_admin) {?>(아이디: <?=$mb['mb_id']?>)<? }?>
    <div class="pull-right">
        <a class="btn btn-default" style="display:inline;" href="javascript:window.close();">닫기</a>
    </div>
</div>
<div class="panel-body">
    <div>
    <ul>
        <li>닉네임 : <?=$mb_nick?>
            <?
            // History를 보여줍니다
            $sql = "select * from $g4[mb_nick_table] where mb_id = '$mb[mb_id]' and end_datetime != '0000-00-00 00:00:00' order by nick_no desc limit 5 ";
            $result = sql_query($sql);
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                echo "&nbsp;<i class=\"fa fa-arrow-left\"></i>&nbsp;" . $row[mb_nick];
            }
            ?>
        </li>
        <li>회원권한 : <?=$mb[mb_level]?></li>
        <li>포인트 : <? if ($mb[mb_point]) { ?><?=number_format($mb[mb_point])?><? } ?></li>
        <? if ($mb[mb_good]) { ?>
        <li>
        추  천 : <a href="<?=$g4[bbs_path]?>/my_good_ed.php?head_on=1" target=new><?=number_format($mb[mb_good])?></a> 회
        </li>
        <? } ?>
        <? if ($mb[mb_nogood]) { ?>
        <li>
        비추천 : <a href="<?=$g4[bbs_path]?>/my_good_ed.php?w=nogood&head_on=1" target=new><?=number_format($mb[mb_nogood])?></a> 회
        </li>
        <? } ?>
        <? if ($mb_homepage) { ?>
        <li>
        홈페이지 : <a href="<?=$mb_homepage?>" target="<?=$config[cf_link_target]?>"><?=$mb_homepage?></a>
        </li>
        <? } ?>
        <li>회원가입일 : <?=($member[mb_level] >= $mb[mb_level]) ?  substr($mb[mb_datetime],0,10) ." (".number_format($mb_reg_after)." 일)" : "알 수 없음"; ?></li>
        <li>최종접속일 : <?=($member[mb_level] >= $mb[mb_level]) ? $mb[mb_today_login] : "알 수 없음";?></li>
        <?
        // 불당팩 - 최고 관리자의 경우 mb_memo를 볼 수 있게
        if ($is_admin == "super") { ?>
            <li>관리자메모 : <? echo nl2br($mb[mb_memo]) ?></li>
        <? } ?>

        <?
        // 자기소개를 출력
        if ($mb[mb_profile]) {
            echo "<hr>" . $mb[mb_profile];
        }
        ?>

        <?
        // 서명을 출력
        if ($mb[mb_signature]) {
            echo "<hr>" . $mb[mb_signature];
        }
        ?>
    </ul>
    </div>
</div>
</div>
</div>