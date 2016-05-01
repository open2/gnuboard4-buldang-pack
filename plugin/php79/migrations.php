<?php
include_once("./_common.php");

if ($is_admin != "super") {
    alert("최고관리자 또는 관리권한이 있는 회원만 접근 가능합니다.", $g4['path']);
}

echo response("<h1>디비 마이그레이션을 시작합니다. " . date('Y-m-d H:i:s') . "</h1>");

/**
 * 마이그레이션 테이블
 *   - 중복 실행 방지용
 *   - 최초 1회만 생성
 */
$g4['php79_init_lock'] = $g4['path'] . '/data/php79-init.lock';
if ( ! file_exists($g4['php79_init_lock'])) {
    $q
        = "CREATE TABLE IF NOT EXISTS `{$g4['php79_migrations_table']}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `query` mediumtext,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='디비 마이그레이션'";
    sql_query($q, true);

    touch($g4['php79_init_lock']);
}

$migrate_count = 0;

/**
 * 디바이스 테이블
 *   - 비회원 기기도 등록되며, 1명의 회원이 2대 이상의 기기 등록 가능.
 */
$q = "CREATE TABLE `{$g4['php79_devices_table']}` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) DEFAULT NULL COMMENT '회원 ID',
  `uuid` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT 'UUID',
  `token` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '토큰',
  `app_version` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '앱 버전',
  `version` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '버전',
  `platform` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '플랫폼',
  `model` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '모델',
  `manufacturer` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '제조사',
  `serial` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '일련번호',
  `push` tinyint(1) NOT NULL DEFAULT '1' COMMENT '푸시 받기',
  `push_sleep` tinyint(1) NOT NULL DEFAULT '1' COMMENT '수면 설정',
  `push_sleep_start` time NOT NULL DEFAULT '23:00:00' COMMENT '수면 시작 시간',
  `push_sleep_end` time NOT NULL DEFAULT '08:00:00' COMMENT '수면 종료 시간',
  `push_update` timestamp NOT NULL CURRENT_TIMESTAMP COMMENT '푸시 설정일',
  `created_at` timestamp NOT NULL CURRENT_TIMESTAMP COMMENT '등록일',
  `updated_at` timestamp NOT NULL CURRENT_TIMESTAMP COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `{$g4['php79_devices_table']}_mb_id` (`mb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='디바이스'";
if (php79_db_migrate($q, 'php79_devices_table')) {
    $migrate_count++;
}

/**
 * 푸시 테이블
 *   - 푸시 발송 결과를 저장
 *   - Whats on 과 연동되어 발송.  (참조키 wo_id)
 *   - error 는 푸시 발송시 에러가 발생한 경우만 기록
 */
$q = "CREATE TABLE `{$g4['php79_pushes_table']}` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int(10) UNSIGNED NOT NULL COMMENT '기기',
  `wo_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Whats on',
  `title` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '제목',
  `content` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '내용',
  `error` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '에러',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '발송일',
  PRIMARY KEY (`id`),
  KEY `{$g4['php79_pushes_table']}_device_id` (`device_id`),
  KEY `{$g4['php79_pushes_table']}_wo_id` (`wo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='푸시 결과'";
if (php79_db_migrate($q, 'php79_pushes_table')) {
    $migrate_count++;
}

/**
 * 큐 작업 실패 테이블
 *   - 백그라운드의 Laravel 큐 워커에서 실패한 작업 보관용
 *   - 실패된 작업을 재실행하는 방법: https://laravel.com/docs/5.1/queues#retrying-failed-jobs
 */
$q = "CREATE TABLE `{$g4['php79_failed_jobs_table']}`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `connection` TEXT NOT NULL COMMENT '큐 드라이버',
  `queue` TEXT NOT NULL COMMENT '큐 이름',
  `payload` LONGTEXT NOT NULL COMMENT '작업 내용',
  `failed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '실패일'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='큐 작업 실패'";
if (php79_db_migrate($q, 'php79_failed_jobs_table')) {
    $migrate_count++;
}

/**
 * 읽지 않은 알림수 테이블
 *   - Whats On 테이블의 읽지 않은 갯수(wo_status = 0)
 *   - 페이지마다 노출되므로, 빠른 처리를 위해 인덱스 테이블 생성
 *   - TODO: 일단 미사용, whatson_table UPDATE/DELETE 쿼리가 많은 소스에 사용되고 있어 협의후 수정 진행.
 *   - TODO: 테이블보다 redis 서버에서 whatson 채널에 회원 id -> count 값 저장이 유리할 듯!
 */
//$q = "CREATE TABLE `{$g4['php79_member_whatson_table']}` (
//  `mb_id` varchar(20) DEFAULT NULL COMMENT '회원 ID',
//  `count` int(10) UNSIGNED NOT NULL COMMENT '읽지 않은 알림수',
//  UNIQUE KEY `{$g4['php79_member_whatson_table']}_mb_id` (`mb_id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='읽지 않음 알림수'";
//if (php79_db_migrate($q, 'php79_member_whatson_table')) {
//    $migrate_count++;
//}

if ($migrate_count) {
    echo "<h1>" . $migrate_count . "건의 디비 마이그레이션이 완료되었습니다. " . date('Y-m-d H:i:s') . "</h1>";
} else {
    echo "<h1>새로운 디비 마이그레이션이 없습니다.</h1>";
}
