<?php
include_once("./_common.php");

if ($is_admin != "super") {
    alert("�ְ������ �Ǵ� ���������� �ִ� ȸ���� ���� �����մϴ�.", $g4['path']);
}

echo response("<h1>��� ���̱׷��̼��� �����մϴ�. " . date('Y-m-d H:i:s') . "</h1>");

/**
 * ���̱׷��̼� ���̺�
 *   - �ߺ� ���� ������
 *   - ���� 1ȸ�� ����
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='��� ���̱׷��̼�'";
    sql_query($q, true);

    touch($g4['php79_init_lock']);
}

$migrate_count = 0;

/**
 * ����̽� ���̺�
 *   - ��ȸ�� ��⵵ ��ϵǸ�, 1���� ȸ���� 2�� �̻��� ��� ��� ����.
 */
$q = "CREATE TABLE `{$g4['php79_devices_table']}` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) DEFAULT NULL COMMENT 'ȸ�� ID',
  `uuid` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT 'UUID',
  `token` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '��ū',
  `app_version` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '�� ����',
  `version` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '����',
  `platform` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '�÷���',
  `model` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '��',
  `manufacturer` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '������',
  `serial` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '�Ϸù�ȣ',
  `push` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Ǫ�� �ޱ�',
  `push_sleep` tinyint(1) NOT NULL DEFAULT '1' COMMENT '���� ����',
  `push_sleep_start` time NOT NULL DEFAULT '23:00:00' COMMENT '���� ���� �ð�',
  `push_sleep_end` time NOT NULL DEFAULT '08:00:00' COMMENT '���� ���� �ð�',
  `push_update` timestamp NOT NULL CURRENT_TIMESTAMP COMMENT 'Ǫ�� ������',
  `created_at` timestamp NOT NULL CURRENT_TIMESTAMP COMMENT '�����',
  `updated_at` timestamp NOT NULL CURRENT_TIMESTAMP COMMENT '������',
  PRIMARY KEY (`id`),
  KEY `{$g4['php79_devices_table']}_mb_id` (`mb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='����̽�'";
if (php79_db_migrate($q, 'php79_devices_table')) {
    $migrate_count++;
}

/**
 * Ǫ�� ���̺�
 *   - Ǫ�� �߼� ����� ����
 *   - Whats on �� �����Ǿ� �߼�.  (����Ű wo_id)
 *   - error �� Ǫ�� �߼۽� ������ �߻��� ��츸 ���
 */
$q = "CREATE TABLE `{$g4['php79_pushes_table']}` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int(10) UNSIGNED NOT NULL COMMENT '���',
  `wo_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Whats on',
  `title` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '����',
  `content` varchar(255) COLLATE utf8_general_ci NOT NULL COMMENT '����',
  `error` varchar(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '����',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '�߼���',
  PRIMARY KEY (`id`),
  KEY `{$g4['php79_pushes_table']}_device_id` (`device_id`),
  KEY `{$g4['php79_pushes_table']}_wo_id` (`wo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Ǫ�� ���'";
if (php79_db_migrate($q, 'php79_pushes_table')) {
    $migrate_count++;
}

/**
 * ť �۾� ���� ���̺�
 *   - ��׶����� Laravel ť ��Ŀ���� ������ �۾� ������
 *   - ���е� �۾��� ������ϴ� ���: https://laravel.com/docs/5.1/queues#retrying-failed-jobs
 */
$q = "CREATE TABLE `{$g4['php79_failed_jobs_table']}`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `connection` TEXT NOT NULL COMMENT 'ť ����̹�',
  `queue` TEXT NOT NULL COMMENT 'ť �̸�',
  `payload` LONGTEXT NOT NULL COMMENT '�۾� ����',
  `failed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '������'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ť �۾� ����'";
if (php79_db_migrate($q, 'php79_failed_jobs_table')) {
    $migrate_count++;
}

/**
 * ���� ���� �˸��� ���̺�
 *   - Whats On ���̺��� ���� ���� ����(wo_status = 0)
 *   - ���������� ����ǹǷ�, ���� ó���� ���� �ε��� ���̺� ����
 *   - TODO: �ϴ� �̻��, whatson_table UPDATE/DELETE ������ ���� �ҽ��� ���ǰ� �־� ������ ���� ����.
 *   - TODO: ���̺��� redis �������� whatson ä�ο� ȸ�� id -> count �� ������ ������ ��!
 */
//$q = "CREATE TABLE `{$g4['php79_member_whatson_table']}` (
//  `mb_id` varchar(20) DEFAULT NULL COMMENT 'ȸ�� ID',
//  `count` int(10) UNSIGNED NOT NULL COMMENT '���� ���� �˸���',
//  UNIQUE KEY `{$g4['php79_member_whatson_table']}_mb_id` (`mb_id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='���� ���� �˸���'";
//if (php79_db_migrate($q, 'php79_member_whatson_table')) {
//    $migrate_count++;
//}

if ($migrate_count) {
    echo "<h1>" . $migrate_count . "���� ��� ���̱׷��̼��� �Ϸ�Ǿ����ϴ�. " . date('Y-m-d H:i:s') . "</h1>";
} else {
    echo "<h1>���ο� ��� ���̱׷��̼��� �����ϴ�.</h1>";
}
