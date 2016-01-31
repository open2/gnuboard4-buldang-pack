<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// ���� ���̺�
$g4['memo_config_table']          = $g4['table_prefix'] . "memo_config";          // �޸� �������̺�

$g4['memo_recv_table']            = $g4['table_prefix'] . "memo_recv";            // �޸� ���̺� (����)
$g4['memo_send_table']            = $g4['table_prefix'] . "memo_send";            // �޸� ���̺� (�߽�)
$g4['memo_save_table']            = $g4['table_prefix'] . "memo_save";            // �޸� ���̺� (����)
$g4['memo_spam_table']            = $g4['table_prefix'] . "memo_spam";            // �޸� ���̺� (����)
$g4['memo_notice_table']          = $g4['table_prefix'] . "memo_notice";          // �޸� ���̺� (����)
$g4['memo_trash_table']           = $g4['table_prefix'] . "memo_trash";           // �޸� ���̺� (������)

$g4['memo_group_table']           = $g4['table_prefix'] . "memo_group";           // �޸� ���̺� (�׷�)
$g4['memo_group_member_table']    = $g4['table_prefix'] . "memo_group_member";    // �޸� ���̺� (�׷���)
$g4['friend_table']               = $g4['table_prefix'] . "friend";               // ģ�� ���̺� 

// data_path�� �������� ������� ����, $g4[data_path]�� �Ҵ��ѿ��� ���� data ��� �Դϴ�.
if (!$g4['data_path']) {
    $g4['data']          = "data";
    $g4['data_path']     = $g4['path'] . "/" . $g4['data'];
}

// ���� - $config ���̺�� ����
if (function_exists('sql_fetch')) {

    // �ʵ庰�� select �ϴ� ���� mysql db�� cache�ǰ� �Ϸ��� �׷� ����
    $memo_config_select = " cf_memo_page_rows, cf_memo_del_unread, cf_memo_del_trash, cf_memo_delete_datetime, cf_memo_user_dhtml, ";
    $memo_config_select .= "cf_memo_use_file, cf_memo_file_size, cf_max_memo_file_size, cf_friend_management, cf_memo_no_reply, ";
    $memo_config_select .= "cf_memo_notice_board, cf_memo_notice_memo, cf_memo_before_after, cf_memo_print, cf_memo_b4_resize, cf_memo_realtime, cf_memo_mb_name ";
    $memo_config = sql_fetch(" select $memo_config_select from $g4[memo_config_table] ", FALSE);

    if ($memo_config) {

        // array_merge�� �̻��ϰ� �����ϴ� �������� �־, �������� �Ѱ��� �ְ� ������
        $config['cf_memo_page_rows']        = $memo_config['cf_memo_page_rows'];        // ������ �������� ��°���
        $config['cf_memo_del_unread']       = $memo_config['cf_memo_del_unread'];       // OO���� ���� ������ ���� ����
        $config['cf_memo_del_trash']        = $memo_config['cf_memo_del_trash'];        // OO���� ���� ������ ���� ����
        $config['cf_memo_delete_datetime']  = $memo_config['cf_memo_delete_datetime'];  //
        $config['cf_memo_user_dhtml']       = $memo_config['cf_memo_user_dhtml'];       // dhtml ������ ��뿩��
        $config['cf_memo_use_file']         = $memo_config['cf_memo_use_file'];         // ÷������ ��뿩��
        $config['cf_memo_file_size']        = $memo_config['cf_memo_file_size'];        // ÷������ ��� ������
        $config['cf_max_memo_file_size']    = $memo_config['cf_max_memo_file_size'];    // �ִ� ÷�� ���� �뷮
        $config['cf_friend_management']     = $memo_config['cf_friend_management'];     // ģ��������� ��뿩��
        $config['cf_memo_no_reply']         = $memo_config['cf_memo_no_reply'];         // ������ ���� ��������
        $config['cf_memo_notice_memo']      = $memo_config['cf_memo_notice_memo'];      // �����޸�
        $config['cf_memo_before_after']     = $memo_config['cf_memo_before_after'];     // ����/���� ���� ����
        $config['cf_memo_print']            = $memo_config['cf_memo_print'];            // ���� ��� ��� ��� ����
        $config['cf_memo_b4_resize']        = $memo_config['cf_memo_b4_resize'];        // �Ҵ�� ��뿩��
        $config['cf_memo_realtime']         = $memo_config['cf_memo_realtime'];         // �ǽð� �޸�
        $config['cf_memo_mb_name']          = $memo_config['cf_memo_mb_name'];          // �Ǹ� ���

    }
}

// ���� ÷������ ���
$g4['memo_file_path'] = $g4['data_path'] . "/memo2/" . $member['mb_id']; 

// memo skin
$g4['memo_skin'] = "basic_boot";

// ���� ��Ų ��� ����
$g4['memo_skin_path'] = "$g4[path]/skin/memo/" . $g4['memo_skin'];

// ���� ���α׷��� location�� ����, $_SERVER[PHP_SELF]�� �Ⱦ��� ���ؼ�
$g4['memo_url'] = $g4[bbs_path] . "/memo.php";

// ������� ȯ�漳���� �ǽð�����, ������ ������ ������ ����
if ($config['cf_memo_realtime'] || $config['cf_memo_no_reply'])
    $config['cf_memo_user_config'] = 1;

// ����4������ dhtml ������ ��� ����
$is_dhtml_editor = $config['cf_memo_user_dhtml']; 

// �Ҵ����� �⺻ ���̺귯���� ���ε�
if (!$g4['b4_version']) {
    include_once("$g4[path]/lib/b4.lib.php");
    ?>
    <script src="<?=$g4[path]?>/js/b4.common.js"></script> 
    <?
}

// ���� �ʱ�ȭ 
if (isset($me_id)) {
    $me_id = (int) $me_id;
}

if (isset($kind)) {
    $kind = preg_match("/^[a-zA-Z0-9_]+$/", $kind) ? $kind : "";
}

// �Ҵ��ѿ��� Ȯ���� g4_member ���̺��� ����
// mb_memo_call               : ������ ���� ���� ����� ��� (mediumtext�� type ����)
// mb_memo_call_datetime      : �ű� ������ ������ ��¥ (���⿡ ��¥�� ������, ������ ���� ������ ī��Ʈ)
// mb_real_memo               : �ǽð� ������ �� ���ΰ� ���� 
// mb_realmemo_sound          : �ǽð� �������� �Ҹ��� ���� �� ���̰� ����
// mb_memo_no_reply           : ������ ���� ��뿩��
// mb_memo_no_reply_text      : ������ ������ �� �޽���
// mb_memo_no_reply_datetime  : ������ ���� ���� ��¥
// mb_memo_unread             : ������ �޽��� ����

// �޸� ������ �ð��� ������ ���� �� �ְ� ���� (���������� ���� ���ؼ�...)
$g4['memo_delay_sec'] = 1;

// ģ��ã�� guess-work�� ���� ���ؼ�
$g4['memo_max_friend'] = 100;

// ģ�������� ����ϰ� �����ϴ� ���� ���� ���ؼ�
$g4['memo_delay_friend'] = 2;

// ģ������ �˻��� �� �� �����ϴ� ����Ʈ
$g4['memo_friend_point'] = -1;

// delete anyway - �ٸ� ���� ������ ������ �ȵ� ���� false �Ǵ� 0���� �ٲٸ� �˴ϴ�.
$g4['memo_delete'] = 1;
?>