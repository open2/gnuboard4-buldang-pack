<?
include_once("./_common.php");

if (!$is_member) {
    alert("�α��� �� �̿��ϼ���.");
}
// ��ڰ� �ƴϸ�
if ($is_admin != 'super') {
    alert("��û�Ͻ� ���񽺸� ã�� �� �����ϴ�.\\n\\nȮ���Ͻ� �� �ٽ� �̿��Ͻñ� �ٶ��ϴ�.");
}


// ���̺� ����
$sql = " CREATE TABLE $g4[attendance_plugin_table] (
  at_id int(11) NOT NULL AUTO_INCREMENT,
  mb_id varchar(255) NOT NULL,
  po_point int(11) NOT NULL default '0',
  at_type int(11) NOT NULL,
  at_default_type int(11) NOT NULL,
  at_victory int(11) NOT NULL,
  at_successive int(11) NOT NULL,
  at_memo text NOT NULL,
  at_date date NOT NULL,
  at_datetime datetime NOT NULL,
  at_ip varchar(15) NOT NULL,
  PRIMARY KEY (`at_id`),
  KEY `at_datetime` (`at_datetime`),
  KEY `at_date` (`at_date`),
  KEY `at_victory` (`at_victory`),
  KEY `mb_id` (`mb_id`),
  KEY `at_ip` (`at_ip`)
)";
sql_query($sql, false);


// ���̺� ����
$sql = " CREATE TABLE $g4[attendance_successive_plugin_table] (
  `mb_id` varchar(255) NOT NULL,
  `as_victory` int(11) NOT NULL,
  `as_successive` int(11) NOT NULL,
  `as_datetime` datetime NOT NULL,
  PRIMARY KEY (`mb_id`),
  KEY `as_successive` (`as_successive`),
  KEY `mb_id` (`mb_id`)
)";
sql_query($sql, false);

@rename("{$g4[attendance_path]}/install.php", "{$g4[attendance_path]}/install.bak");

alert("������Ʈ�� �Ϸ�Ǿ����ϴ�.\\n\\nȮ���Ͻ� �� �̿� �Ͻñ� �ٶ��ϴ�.","{$g4[attendance_path]}/attendance.php");

?>
