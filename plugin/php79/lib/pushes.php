<?php
/**
 * Copyright 2016 Been Kyung-yoon.
 */

/**
 * Ǫ�� ť ����
 *  - ������������ ����ڿ��� ���� ������ �����ϱ� ����, �������� ť�� ��������� ó����.
 *  - ����� ť�� ��׶��� ���󿡼� �ڵ� �߼۵�.
 *
 * @param string $mb_id
 * @param int $wo_id
 * @param string $title
 */
function push_queue($mb_id, $wo_id, $title)
{
    global $g4;

    if (empty($mb_id)) {
        response_error("Ǫ�ø� ���� ȸ���� �������� �ʽ��ϴ�.");
    }

    $device_count = pdo(
        "select count(*) from {$g4['php79_devices_table']} where mb_id=? and push=1",
        array(
            $mb_id,
        )
    )->fetchColumn(0);

    if ($device_count) {
        $data = array(
            'job'      => "Illuminate\\Foundation\\Console\\QueuedJob",
            'data'     => array(
                'push:send',
                array(
                    'wo_id' => $wo_id,
                    'title' => to_utf8($title),
                )
            ),
            'id'       => md5(uniqid($wo_id . ':', true)),      // Laravel Str::random(32)
            'attempts' => 1,
        );
        push_redis_save(json_encode($data));
    }
}

/**
 * ���� ť ����
 *   - linked list ������ �ε����� �߰��ؾ� ��.
 *
 * @link https://github.com/phpredis/phpredis#rpush
 *
 * @param $value
 */
function push_redis_save($value)
{
    global $g4;

    $redis = new Redis();
    $redis->connect($g4["rhost"], $g4["rport"]);
    $redis->select($g4["rdb"]);
    $redis->rPush('queues:default', $value);
    $redis->close();
}

/**
 * Ǫ�� Ŭ���� �̵��� �ּ�
 *
 * @param string $mb_id
 * @param int $wo_id
 */
function push_url($mb_id, $wo_id)
{

    // TODO: Whats on click �����Ͽ� ó��..


}
