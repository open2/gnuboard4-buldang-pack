<?php
if ( ! defined("_GNUBOARD_")) {
    exit;
} // ���� ������ ���� �Ұ�
?>
<div class="container">
    <form role="form" class="form-inline" name='deviceForm' method='post' action="device_update.php">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>�˸� ����</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-10">
                        Ǫ�þ˸� �ޱ�
                    </div>
                    <div class="col-xs-2">
                        <label for="push"><input type=checkbox name=push
                                                 value='1' <?= ($device['push']) ? 'checked' : ''; ?>></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>���� ����</strong>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-xs-10">
                        ���� �ð����� Ǫ�� �˸��� �Ҹ�/����/LEDǥ�ø� �����մϴ�.<br>
                        ��, ����� ȭ���� ���� ���¿����� �������� �ʰ� ���� ǥ�õ˴ϴ�.
                    </div>
                    <div class="col-xs-2">
                        <label for="push_sleep"><input type=checkbox name=push_sleep
                                                       value='1' <?= ($device['push_sleep']) ? 'checked' : ''; ?>></label>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row" style="margin-bottom: 15px; padding-top: 15px; border-top: 1px solid #f4f4f4;">
                    <div class="col-xs-6 text-right">
                        ���� �ð�
                    </div>
                    <div class="col-xs-6">
                        <input type=time name='push_sleep_start' size='5' required
                               value='<?= substr($device['push_sleep_start'], 0, 5) ?>'>
                    </div>
                </div>
                <div class="row" style="padding-top: 15px; border-top: 1px solid #f4f4f4;">
                    <div class="col-xs-6 text-right">
                        ���� �ð�
                    </div>
                    <div class="col-xs-6">
                        <input type=time name='push_sleep_end' size='5' required
                               value='<?= substr($device['push_sleep_end'], 0, 5) ?>'>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success" id="btn_submit">����</button>
        </div>
    </form>
</div>