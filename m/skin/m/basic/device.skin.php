<?php
if ( ! defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가
?>
<div class="container">
    <form role="form" class="form-inline" name='deviceForm' method='post' action="device_update.php">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>알림 설정</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-10">
                        푸시알림 받기
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
                <strong>수면 설정</strong>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-xs-10">
                        수면 시간에는 푸시 알림시 소리/진동/LED표시를 생략합니다.<br>
                        단, 기기의 화면이 켜진 상태에서는 생략하지 않고 정상 표시됩니다.
                    </div>
                    <div class="col-xs-2">
                        <label for="push_sleep"><input type=checkbox name=push_sleep
                                                       value='1' <?= ($device['push_sleep']) ? 'checked' : ''; ?>></label>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row" style="margin-bottom: 15px; padding-top: 15px; border-top: 1px solid #f4f4f4;">
                    <div class="col-xs-6 text-right">
                        시작 시간
                    </div>
                    <div class="col-xs-6">
                        <input type=time name='push_sleep_start' size='5' required
                               value='<?= substr($device['push_sleep_start'], 0, 5) ?>'>
                    </div>
                </div>
                <div class="row" style="padding-top: 15px; border-top: 1px solid #f4f4f4;">
                    <div class="col-xs-6 text-right">
                        종료 시간
                    </div>
                    <div class="col-xs-6">
                        <input type=time name='push_sleep_end' size='5' required
                               value='<?= substr($device['push_sleep_end'], 0, 5) ?>'>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success" id="btn_submit">변경</button>
        </div>
    </form>
</div>