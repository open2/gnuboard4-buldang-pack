<?
if ( ! defined("_GNUBOARD_")) {
    exit;
} // ���� ������ ���� �Ұ�
?>
<div id="memo_bar" class="list-btn-bar text-center">
    <div>
        <a href="<?= $g4[memo_url] ?>?kind=recv" class="btn btn-default btn-outline" id="recv"><strong>����</strong></a>
        <a href="<?= $g4[memo_url] ?>?kind=send" class="btn btn-default btn-outline" id="send"><strong>�߽�</strong></a>
        <a href="<?= $g4[memo_url] ?>?kind=save" class="btn btn-default btn-outline" id="save">����</a>
        <a href="<?= $g4[memo_url] ?>?kind=notice" class="btn btn-default btn-outline" id="notice">����</a>
    </div>
    <div style="margin-top: 5px;">
        <a href="<?= $g4[memo_url] ?>?kind=write" class="btn btn-default btn-outline" id="write"><strong>����</strong></a>
        <a href="<?= $g4[memo_url] ?>?kind=trash" class="btn btn-default btn-outline" id="trash">����</a>
        <a href="<?= $g4[memo_url] ?>?kind=spam" class="btn btn-default btn-outline" id="spam">����</a>
        <a href="<?= $g4[memo_url] ?>?kind=memo_config" class="btn btn-default btn-outline">����</a>
    </div>
</div>

<script type='text/javascript'>
    // ���� Ŭ���� ��ư�� active��
    $('#memo_bar #<?=$kind?>').addClass('active');
</script>

<!-- �޸� �޴� ���� ��Ű�� -->
<? if ($kind) { ?>
    <script type="text/javascript">
        $('#gnb #<?=$kind?>').addClass('active');
    </script>
<? } ?>

<!-- �߰��� ���κ� ���� -->
<div role="main">