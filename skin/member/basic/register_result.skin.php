<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<div class="well">
    <p><strong>ȸ�������� �������� �����մϴ�.</strong></p>
    <p>
    ȸ������ �н������ �ƹ��� �� �� ���� ��ȣȭ �ڵ�� ����Ǹ�,<br>
    ���̵�, �н����� �нǽÿ��� ȸ�����Խ� �Է��Ͻ� �̸����� �̿��Ͽ� ã�� �� �ֽ��ϴ�.
    <? if ($config[cf_use_email_certify]) { ?>
    </p>
    <p>
    E-mail(<?=$mb[mb_email]?>)�� �߼۵� ������ Ȯ���� �� �����ϼž� ȸ�������� �Ϸ�˴ϴ�.
    <? } ?>
    <br><br>
    </p>
  <p><a class="btn btn-primary btn-md" role="button" href="<?=$g4[url]?>">Ȩ���� ����</a></p>
</div>