<?php
/**
 * Copyright 2016 Been Kyung-yoon.
 */

/**
 * 푸시 큐 저장
 *  - 웹페이지에서 사용자에게 빠른 응답을 제공하기 위해, 웹에서는 큐에 저장까지만 처리됨.
 *  - 저장된 큐는 백그라운드 데몬에서 자동 발송됨.
 *
 * @param string $mb_id
 * @param int $wo_id
 * @param string $title
 */
function push_queue($mb_id, $wo_id, $title)
{
    global $g4;

    if (empty($mb_id)) {
        response_error("푸시를 받을 회원이 존재하지 않습니다.");
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
 * 레디스 큐 저장
 *   - linked list 마지막 인덱스로 추가해야 함.
 *
 * @link https://github.com/phpredis/phpredis#rpush
 *
 * @param $value
 */
function push_redis_save($value)
{
    global $g4;

    $redis = new Redis();
    $redis->connect($g4["push_rhost"], $g4["push_rport"]);
    $redis->select($g4["push_rdb"]);
    $redis->rPush('queues:default', $value);
    $redis->close();
}

/**
 * 푸시 클릭시 이동할 주소
 *
 * @param string $mb_id
 * @param int $wo_id
 */
function push_url($mb_id, $wo_id)
{

    // TODO: Whats on click 참고하여 처리..


}
