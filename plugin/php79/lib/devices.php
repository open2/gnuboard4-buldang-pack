<?php
/**
 * Copyright 2016 Been Kyung-yoon.
 */

/**
 * ��� ���
 *  - �ߺ� ���� ����. UUID + serial
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
 * ��⿡ ȸ�� ����
 *   - �̹� ��ϵ� ���̵�� �ٸ� ���̵�� �α����� �� �����Ƿ�, ���� �α��ε� ����ڸ� �������� ��� ������ ����
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
