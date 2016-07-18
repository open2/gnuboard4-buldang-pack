<?php
/**
 * Copyright 2016 Been Kyung-yoon.
 */

/**
 * 기기 등록
 *  - 중복 저장 생략. UUID + serial
 *
 * @param array $attributes
 * @param array $member
 */
function device_save(array $attributes, $member)
{
    global $g4;

    $device = pdo(
        "select * from {$g4['php79_devices_table']} where uuid=:uuid and serial=:serial",
        array(
            ':uuid'   => $attributes['uuid'],
            ':serial' => $attributes['serial'],
        )
    )->fetch();

    $mb_id = (isset($member['mb_id'])) ? $member['mb_id'] : null;
    if ($device) {
        pdo(
            "update {$g4['php79_devices_table']} set " .
            "token=:token, " .
            "app_version=:app_version, " .
            "version=:version, " .
            "mb_id=:mb_id, " .
            "updated_at=SYSDATE() " .
            "where id=:id",
            array(
                ':token'       => $attributes['token'],
                ':app_version' => $attributes['app_version'],
                ':version'     => $attributes['version'],
                ':mb_id'       => $mb_id,
                ':id'          => $device['id'],
            )
        );
    } else {
        $data                = $attributes;
        $data['mb_id']       = $mb_id;
        $data['push_update'] = date_full();
        $data['created_at']  = date_full();
        $data['updated_at']  = date_full();

        pdo_create($g4['php79_devices_table'], $data);
    }
}

/**
 * 기기에 회원 연동
 *   - 이미 등록된 아이디와 다른 아이디로 로그인할 수 있으므로, 현재 로그인된 사용자를 기준으로 기기 소유자 변경
 *
 * @param array $attributes
 * @param array $member
 */
function device_set_user(array $attributes, $member)
{
    global $g4;

    $device = pdo(
        "select * from {$g4['php79_devices_table']} where uuid=:uuid and serial=:serial",
        array(
            ':uuid'   => $attributes['uuid'],
            ':serial' => $attributes['serial'],
        )
    )->fetch();


    if ($device) {
        pdo(
            "update {$g4['php79_devices_table']} set " .
            "mb_id=:mb_id, " .
            "updated_at=SYSDATE() " .
            "where id=:id",
            array(
                ':mb_id' => $member['mb_id'],
                ':id'    => $device['id'],
            )
        );
    }
}

/**
 * 기기 정보
 *
 * @param $uuid
 * @param $member
 *
 * @return mixed
 */
function device_info($uuid, $member)
{
    global $g4;

    return pdo(
        "select * from {$g4['php79_devices_table']} where mb_id=? and uuid=?",
        array(
            $member[mb_id],
            $uuid
        )
    )->fetch();
}

/**
 * 기기 알림 설정 변경
 *
 * @param array $attributes
 * @param array $device
 */
function device_push_update(array $attributes, $device)
{
    global $g4;

    pdo(
        "update {$g4['php79_devices_table']} set " .
        "push=:push, " .
        "push_sleep=:push_sleep, " .
        "push_sleep_start=:push_sleep_start, " .
        "push_sleep_end=:push_sleep_end, " .
        "push_update=SYSDATE(), " .
        "updated_at=SYSDATE() " .
        "where id=:id",
        array(
            ':push'             => $attributes['push'],
            ':push_sleep'       => $attributes['push_sleep'],
            ':push_sleep_start' => $attributes['push_sleep_start'],
            ':push_sleep_end'   => $attributes['push_sleep_end'],
            ':id'               => $device['id'],
        )
    );
}
