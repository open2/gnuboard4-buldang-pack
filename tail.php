<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

if (is_mobile()) {
    include_once($g4['path'] . '/m/tail.php');
    include_once($g4['path'] . '/m/tail.sub.php');
    return;
}

// ����� ȭ�� ������ �ϴ��� ����ϴ� �������Դϴ�.
// ����, �ϴ� ȭ���� �ٹ̷��� �� ������ �����մϴ�.
?>

</div><!-- ���� content �� -->

</div>
</div><!-- �߰��� ���κ� �� -->

<? include("$g4[path]/lib/moveup.php") ?>

<!-- ������ �ϴܺ� footer -->
<footer class="footer-wrapper col-sm-offset-2" role="contentinfo" style="margin-top:20px;">
<div class="container-fluid" id="footer">
    <div class="panel panel-default hidden-sm hidden-md hidden-lg">
    <div class="panel-heading">
        <div>
        <a class="btn btn-default" data-toggle="collapse" data-target=".navbar-bottom-collapse">Info.</a>
        <div class="btn-group">
        <? if ($member['mb_id']) { ?>
            <a class="btn btn-default visible-xs" href="<?=$g4['bbs_path']?>/logout.php">Logout</a>
        <? } else { 
            $login_url = "$g4[bbs_path]/login.php?$qstr";
        ?>
            <a class="btn btn-default visible-xs" href="<?=$login_url?>">Login</a>
        <? } ?>
        </div>

        <a href="<?=$g4[path]?>/company/company.php?id=privacy"><strong>����������޹�ħ</strong></a>
        <small>(��)�����ڵ�</small>

        <a class="btn btn-default pull-right" href="#" onclick="$('html, body').animate({scrollTop: 0}, duration);">TOP</a>

        </div>
    </div>
    </div>

    <div class="collapse navbar-collapse navbar-bottom-collapse">
        <ul class="list-inline">
            <li><a href="<?=$g4[path]?>/company/company.php?id=privacy"><strong>����������޹�ħ</strong></a></li>
            <li><a href="<?=$g4[path]?>/company/company.php?id=service">�̿���</a></li>
            <li><a href="<?=$g4[path]?>/company/company.php?id=disclaimer">å���Ѱ�͹�������</a></li>
            <li><a href="<?=$g4[path]?>/company/company.php?id=rejection">�̸����ּҹ��ܼ����ź�</a></li>
        </ul>
        <p>(��)�����ڵ� ����ڵ�Ϲ�ȣ :000-00-00000 ����Ǹž��Ű��ȣ :��2009-���Ｍ��-0000ȣ<br>
            ��ǥ�̻� :�ƺ��Ҵ� �ּ� :����� ���ʱ� ���ʵ� �ջ� ��ȭ :00-000-0000</p>
        <p><?=$config[cf_title]?>�� �����߰��ڷμ� ������ �ֹ�, ��� �� ȯ���� �ǹ��� å���� �� ȸ���� �ֽ��ϴ�.<br>
            <?=$config[cf_title]?>�� ���� ���� ���� ���� <?=$config[cf_title]?>�� ��ü�� ����, ������ �� UI���� ����� �������� ����, ����, ��ũ���� �� ���� ����� �� �����ϴ�.<br>
            Copyright &copy; <a href="http://opencode.co.kr" target="_blank">Opencode.co.kr</a>. All rights reserved.</p>
    </div>
</div>
</footer>
<?
include_once("$g4[path]/tail.sub.php");
?>