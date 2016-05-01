<?php
if ( ! defined('_GNUBOARD_')) {
    exit;
} // ���� ������ ���� �Ұ�

//------------------------------------------------------------------------------
// PHP79 ��� ���� ����
//------------------------------------------------------------------------------

define('G4_PHP79_DIR', 'php79');
define('G4_PHP79_PATH', dirname(dirname(__FILE__)) . '/plugin/' . G4_PHP79_DIR);
define('G4_PHP79_URL', dirname(dirname(__FILE__)) . '/plugin/' . G4_PHP79_DIR);

// PHP79 ���� �Լ�
require_once(G4_PHP79_PATH . '/lib/helpers.php');

// ���̺��
$g4['php79_prefix']            = 'php79_';
$g4['php79_migrations_table']  = $g4['php79_prefix'] . 'migrations';
$g4['php79_pushes_table']      = $g4['php79_prefix'] . 'pushes';
$g4['php79_devices_table']     = $g4['php79_prefix'] . 'devices';
$g4['php79_failed_jobs_table'] = $g4['php79_prefix'] . 'failed_jobs';
//$g4['php79_member_whatson_table'] = $g4['php79_prefix'] . 'member_whatson';
