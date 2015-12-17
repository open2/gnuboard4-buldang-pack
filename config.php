<?
// �� ����� ���ǵ��� ������ ������ ���� �������� ������ ����� �� ����
define("_GNUBOARD_", TRUE);

// php 5.1.x �̻��̸鼭 ������ �ٸ� �ð��� ������ �ʿ��� �� ������.
// �����ϴ� timezone ����� http://kr2.php.net/manual/en/timezones.php
// 5.4.x ���ʹ� �Ʒ� ���ǰ� ������ PHP Notice�� ���� ��ϴ�.
if (function_exists("date_default_timezone_set"))
    date_default_timezone_set("Asia/Seoul");

// �Ҵ��� ����
$g4['b4_version']     = "1.1.x";

// ���丮
$g4['bbs']            = "bbs";
$g4['bbs_path']       = $g4['path'] . "/" . $g4['bbs'];
$g4['bbs_img']        = "img";
$g4['bbs_img_path']   = $g4['path'] . "/" . $g4['bbs'] . "/" . $g4['bbs_img'];

$g4['admin']          = "adm";
$g4['admin_path']     = $g4['path'] . "/" . $g4['admin'];

$g4['data']          = "data";
$g4['data_path']     = $g4['path'] . "/" . $g4['data'];

$g4['editor']         = "cheditor";
$g4['editor_path']    = $g4['path'] . "/" . $g4['editor'];

$g4['cheditor4']      = "cheditor4";
$g4['cheditor4_path'] = $g4['path'] . "/" . $g4['cheditor4'];

$g4['is_cheditor5']   = true;

$g4['geditor']        = "geditor";
$g4['geditor_path']   = $g4['path'] . "/" . $g4['geditor'];

$g4['plugin']         = "plugin";
$g4['plugin_path']    = $g4['path'] . "/" . $g4['plugin'];


// ���� ����ϴ� ��
// ������ �ð��� ���� ����ϴ� �ð��� Ʋ�� ��� �����ϼ���.
// �Ϸ�� 86400 ���Դϴ�. 1�ð��� 3600��
// 6�ð��� ���� ��� time() + (3600 * 6);
// 6�ð��� ���� ��� time() - (3600 * 6);
$g4['server_time'] = time();
$g4['time_ymd']    = date("Y-m-d", $g4['server_time']);
$g4['time_his']    = date("H:i:s", $g4['server_time']);
$g4['time_ymdhis'] = date("Y-m-d H:i:s", $g4['server_time']);

//
// ���̺� ��
// (����� �����Ѱ��� �Լ����� global ������ ���� �ʾƵ� �ٷ� ����� �� �ֱ� ����)
//
$g4['table_prefix']        = "g4_"; // ���̺�� ���λ�
$g4['write_prefix']        = $g4['table_prefix'] . "write_"; // �Խ��� ���̺�� ���λ�

$g4['auth_table']          = $g4['table_prefix'] . "auth";          // �������� ���� ���̺�
$g4['config_table']        = $g4['table_prefix'] . "config";        // �⺻ȯ�� ���� ���̺�
$g4['group_table']         = $g4['table_prefix'] . "group";         // �Խ��� �׷� ���̺�
$g4['group_member_table']  = $g4['table_prefix'] . "group_member";  // �Խ��� �׷�+ȸ�� ���̺�
$g4['board_table']         = $g4['table_prefix'] . "board";         // �Խ��� ���� ���̺�
$g4['board_file_table']    = $g4['table_prefix'] . "board_file";    // �Խ��� ÷������ ���̺�
$g4['board_good_table']    = $g4['table_prefix'] . "board_good";    // �Խù� ��õ,����õ ���̺�
$g4['board_new_table']     = $g4['table_prefix'] . "board_new";     // �Խ��� ���� ���̺�
$g4['login_table']         = $g4['table_prefix'] . "login";         // �α��� ���̺� (�����ڼ�)
$g4['mail_table']          = $g4['table_prefix'] . "mail";          // ȸ������ ���̺�
$g4['member_table']        = $g4['table_prefix'] . "member";        // ȸ�� ���̺�
$g4['poll_table']          = $g4['table_prefix'] . "poll";          // ��ǥ ���̺�
$g4['poll_etc_table']      = $g4['table_prefix'] . "poll_etc";      // ��ǥ ��Ÿ�ǰ� ���̺�
$g4['point_table']         = $g4['table_prefix'] . "point";         // ����Ʈ ���̺�
$g4['popular_table']       = $g4['table_prefix'] . "popular";       // �α�˻��� ���̺�
$g4['scrap_table']         = $g4['table_prefix'] . "scrap";         // �Խñ� ��ũ�� ���̺�
$g4['visit_table']         = $g4['table_prefix'] . "visit";         // �湮�� ���̺�
$g4['visit_sum_table']     = $g4['table_prefix'] . "visit_sum";     // �湮�� �հ� ���̺�

//
// ��Ÿ
//
$g4['memo_table']           = $g4['table_prefix'] . "memo_recv";          // �޸� ���̺� (����2/����4)
$g4['member_group_table']   = $g4['table_prefix'] . "member_group";       // ����׷�
$g4['my_menu_table']        = $g4['table_prefix'] . "my_menu";            // ���̸޴�
$g4['auction_tender_table'] = $g4['table_prefix'] . "auction_tender";     // ����Ʈ��� ���̺� 
$g4['tag_table']            = $g4['table_prefix'] . "tag";                // ���ñ� �±� ���̺� 
$g4['mb_nick_table']        = $g4['table_prefix'] . "mb_nick";            // �г��� �����丮 ���̺� 
$g4['singo_table']          = $g4['table_prefix'] . "singo";              // �Խù� �Ű� ���̺� 
$g4['singo_reason_table']   = $g4['table_prefix'] . "singo_reason";       // �Խù� �Ű� ���� ���̺� 
$g4['unsingo_table']        = $g4['table_prefix'] . "unsingo";            // �Խù� �Ű� ���̺� 
$g4['my_board_table']       = $g4['table_prefix'] . "my_board";           // ���� �湮�� �Խ��� ���̺� 
$g4['user_group_table']     = $g4['table_prefix'] . "user_group";         // ����� �׷�
$g4['hidden_comment_table'] = $g4['table_prefix'] . "hidden_comment";     // �����ɱ�
$g4['login_fail_log_table'] = $g4['table_prefix'] . "login_fail_log";     // �α��� ���� logging
$g4['config_reg_table']     = $g4['table_prefix'] . "config_reg";         // �⺻ȯ�� ���� ���̺� (�̿���, �������� ��޹�ħ �� �󵵰� ���� ��������)
$g4['member_level_table']   = $g4['table_prefix'] . "member_level";       // ȸ�� ������ ���� ���̺�
$g4['member_level_history_table'] = $g4['table_prefix'] . "member_level_history";       // ȸ�� ������ history ���̺�
$g4['category_table']       = $g4['table_prefix'] . "category";           // ȸ�� ������ ���� ���̺�
$g4['member_register_table']= $g4['table_prefix'] . "member_register";    // ȸ�� ���� ���� ���� ���̺�
$g4['recycle_table']        = $g4['table_prefix'] . "recycle";            // ������ ���� ���� ���̺�
$g4['board_file_download_table'] = $g4['board_file_table'] . "_download";     // �Խ��� ���� �ٿ�ε� ���̺�
$g4['cache_table']          = $g4['table_prefix'] . "cache";              // db cache ���̺�
$g4['board_cheditor_table'] = $g4['table_prefix'] . "board_cheditor";     // chediotr ���� ���� ���̺�
$g4['notice_table']         = $g4['table_prefix'] . "notice";             // ��ü���� ���̺�
$g4['whatson_table']        = $g4['table_prefix'] . "whatson";            // ��~��~ ���̺�
$g4['geoip_table']          = $g4['table_prefix'] . "geoip";              // GeoIP ���̺�
$g4['popular_sum_table']    = $g4['table_prefix'] . "popular_sum";        // �α�˻��� �հ� ���̺�
$g4['filter_table']         = $g4['table_prefix'] . "filter";             // �α�˻��� � ���̴� ���� ���̺�
$g4['promotion_table']      = $g4['table_prefix'] . "promotion";          // ���θ�� ���̺�
$g4['promotion_sign_table'] = $g4['table_prefix'] . "promotion_sign";     // ���θ�� ��� ���̺�
$g4['tempsave_table']       = $g4['table_prefix'] . "tempsave";           // �ӽ����� ���̺�
$g4['namecheck_table']      = $g4['table_prefix'] . "namecheck";          // �Ǹ����� History ���̺�
$g4['realcheck_table']      = $g4['table_prefix'] . "realcheck";          // �������� History ���̺�
$g4['good_list_table']      = $g4['table_prefix'] . "good_list";          // ����Ʈ�� ���̺�
$g4['seo_tag_table']        = $g4['table_prefix'] . "seo_tag";            // SEO - tag ���̺�
$g4['seo_server_table']     = $g4['table_prefix'] . "seo_server";         // SEO - ���� ���̺�
$g4['seo_history_table']    = $g4['table_prefix'] . "seo_history";        // SEO - History ���̺�
$g4['member_suggest_table'] = $g4['table_prefix'] . "member_suggest";     // ȸ����õ���� ���̺�
$g4['banner_group_table']   = $g4['table_prefix'] . "banner_group";       // ��ʱ׷� ���̺�
$g4['banner_table']         = $g4['table_prefix'] . "banner";             // ��� ���̺�
$g4['banner_click_table']   = $g4['table_prefix'] . "banner_click";       // ���Ŭ�� ���̺�
$g4['banner_click_sum_table']   = $g4['table_prefix'] . "banner_click_sum";       // ���Ŭ�� ��� ���̺�
$g4['category_table']       = $g4['table_prefix'] . "category";           // ī�װ� ���̺�
$g4['admin_log_table']      = $g4['table_prefix'] . "admin_log";          // ������ log ���̺�
$g4['menu_table']           = $g4['table_prefix'] . "menu";               // �޴����� ���̺�
$g4['cookie_table']         = $g4['table_prefix'] . "cookie";             // cookie ���̺�
$g4['unlogin_table']        = $g4['table_prefix'] . "member_unlogin";     // �޸�ȸ�� ���̺�

// �����־� (������)
$mw['table_prefix'] = $g4['table_prefix']."mw_";
$mw['board_visit_table'] = $mw['table_prefix']."board_visit";             // �Խ��Ǻ� �湮�� ���
$mw['board_visit_log_table'] = $mw['table_prefix']."board_visit_log";     // �Խ��Ǻ� �湮�� �α�
$g4['session_table'] = $g4['table_prefix'] . "session";                   // db�� ���ǰ���

// www.sir.co.kr �� sir.co.kr �������� ���� �ٸ� ���������� �ν��մϴ�. ��Ű�� �����Ϸ��� .sir.co.kr �� ���� �Է��ϼ���.
// �̰��� �Է��� ���ٸ� www ���� �����ΰ� �׷��� ���� �������� ��Ű�� �������� �����Ƿ� �α����� Ǯ�� �� �ֽ��ϴ�.
$g4['cookie_domain'] = ".diorcafe.co.kr";

// DNS Round Robin, L4 Loading Balancing ���� ���, ���ӽø��� $_SERVER[SERVER_ADDR]�� �ٲ�ϴ�.
// ����, ����Ʈ�� ��Ÿ�� �� �ִ� unique�� �̸�(��:�������̸�,����Ʈ��,����ip��)�� ����� �ڵ��α����� ��Ǯ���ϴ�.
$g4['load_balance'] = ".diorcafe.co.kr";

// �Խ��ǿ��� ��ũ�� �⺻������ ���մϴ�.
// �ʵ带 �߰��ϸ� �� ���ڸ� �ʵ���� �°� �÷��ֽʽÿ�.
$g4['link_count'] = 2;

// ���ڼ��� ���� (euc-kr/utf-8)
$g4['charset'] = "euc-kr";

// config.php �� �ִ°��� �����. �ڿ� / �� ������ ������.
// ��) http://g4.sir.co.kr
$g4['url'] = "";
$g4['https_url'] = "";
//$g4['https_url'] = "https://www.diorcafe.co.kr";
// �Է¿�
//$g4['url'] = "http://www.sir.co.kr";
//$g4['https_url'] = "https://www.sir.co.kr";

// ��ȣȭ�� ���� KEY
$g4['encrypt_key'] = "opencode";

// ��õ+�������� ������ �ϱ� ���ؼ� (0: �׳� ����, 1 : ��õ+�������θ� ����)
$g4['member_suggest_join']  = 0;

// ��õ+�������� ������ ��, ��õ �ڵ��� ��ȿ�Ⱓ (�⺻ 7��. �ð��� �ƴ϶� ��¥��.)
$g4['member_suggest_join_days']  = 90;

// �ڵ� �������� ����� ���ΰ��� ����
$g4['use_auto_levelup'] = 1;

// ����� session ���¸� ���� �մϴ�. 
// db. redis. file - 3�� �Դϴ�
// redis�� ����ϱ� ���ؼ��� PECL:redis�� phpredis�� ��ġ�ؾ��� �մϴ�. redis ������ ��ġ�� �ʿ��մϴ�.
// 3���� ���ǰ����� redis�� �����ϰ� ��õ �մϴ�.
$g4['session_type'] = "db";

// redis ����Ҷ��� ����
$g4['rhost']    = "localhost";
$g4['rport']    = "6379";
$g4['rauth']    = "";             // redis-server password. default�� ���� ����. redis.conf���� ����
$g4['rdomain']  = "diorcafe";     // redis domain. �ٸ� redis instance�� �浹���� �ʰ� unique�ϰ� ����ݴϴ�
$g4['rdb']      = "0";            // redis DB space (0) - ���ǰ����� ���
$g4['rdb1']     = "1";            // redis DB space (1) - login ������ ���. �ٸ� �͵�� ���򰥸���

// redis ���� path
$g4['rpath']    = "tcp://$g4[rhost]:$g4[rport]?weight=1&auth=$g4[rauth]&database=$g4[rdb]";    

// redis �⺻Ű ���� - �����ڷ�
// g4_login     : $g4[rdomain] . "_login_" . $remote_addr

// cdn ��θ� ���� �մϴ�. (��: http://cdnid.imagetong.com)
$g4['cdn_path']          = "";

// ������İ� ���� create temporary table�� �ȸ����� ��쿡�� �������� 1�� �ϼ���.
$g4['old_stype_search'] = 0;

// gblog�� �Խñ� �����⸦ ���� ����
$g4['use_gblog']   = 0;   // gblog�� �ۺ����⸦ ��ġ ��������, 0���� ������ �����ϸ� �˴ϴ�.

// ���񿡼� Ư������ ��� ���ֱ�
$g4['remove_special_chars'] = 1;    // 1�� ���ִ°�, 0�� �� ���ִ°�
$g4['special_chars_change'] = "�١ڡޡߡ������ۡݡآ����������������¢â���";  // ���ְ� ���� ���ڴ� ��⿡ �߰�/����

// phpmyadmin�� ��θ� ����
$g4['phpmyadmin_dir'] = $g4['admin_path'] . "/phpMyAdmin/";

// use geo_ip
$g4['use_geo_ip'] = false;

// iframe�� ���� ���, ������ iframe�� ������ ��� goto_url�� ���� frame���� �����ϰ� �Ѵ�.
// Ư�� ���α׷������� ���� ���α׷����� ����ǰ� �ϴ� �͵� ���� �����ϴ�.
$g4['goto_url_parent'] = "";
$g4['goto_url_pgm'] = "";

// �̹��� ���� ���콺 ��Ŭ�� ������ Ǯ���ֱ�
$is_test = 0;

// cheditor �̹��� ���ε� ���丮
$g4['cheditor_save_dir'] = $g4['data_path'] . "/" . $g4['cheditor4'];

// cheditor�� �̹��� ���ε� ������� ����Ʈ�� ��� �մϴ�. 
// ��) 5 * 1000 * 1000; // ũ�� ���� ����Ʈ, �⺻�� 0 (���� ����)
$g4['cheditor_uploadsize'] = 0;

// cheditor �̹��� url - ��Ȯ�ϰ� URL�� ������ �ִ� ���� ���δ� �� ���ϴ�
$g4['cheditor_image_url'] = $g4['data_path'] . "/" . $g4['cheditor4'];

// ���� ���ؼ� ������ �����Ѵ�. �ֵ弾��(1), �ֵ��÷���(2), ����Ŭ��(1) - 3���̶� 3������ �⺻ ����.
$g4['ad_type'] = rand(1, 3);

// ����Ʈ�� ������ ���⿡��
$g4['good_list_rows'] = 30;
$g4['good_list_head'] = "../head.php";
$g4['good_list_tail'] = "../tail.php";
$g4['good_list_skin'] = "basic";
$g4['goodlist_use_list_view'] = false;

// ������ ������ ���⿡��
$g4['notice_list_rows'] = 30;
$g4['notice_list_head'] = "../head.php";
$g4['notice_list_tail'] = "../tail.php";
$g4['notice_list_skin'] = "basic";
$g4['notice_use_list_view'] = true;

// ���� ������ ���⿡��
$g4['new_use_list_view'] = true;

// ���� ��õ�� ��/��õ ���� �� ����
$g4['my_good_skin'] = "basic";

// ������ ����
$g4['recycle_skin'] = "basic";
$g4['recycle_page_rows'] = 24;

// �Ű� ����
$g4['singo_skin'] = "basic";
$g4['singo_page_rows'] = 24;

// IE�� UA�� ���� - 5, 7, 8, Edge, EmulateIE7 - http://opencode.co.kr/bbs/board.php?bo_table=qna&wr_id=3611
$g4['ie_ua'] = "edge";

// �˻�����
$g4['search_level'] = 2;

// �̸��������� ������ ����Ʈ
$g4['email_certify_point'] = 500;

// ��õ, ����õ ����Ʈ �ο��ϱ�
$g4['good_point'] = 20;
$g4['nogood_point'] = -10;

// Ű���� SEO ��� ��������
$g4['keyword_seo'] = 1;

// true�̸�, SQL ������ ���, fasle�� �ٲٸ� ��� SQL ������ ��� �ȵ�
$g4['debug'] = true;

// �������θ� ������ ����ϱ�
$g4['nick_reg_only'] = 1;

// �ӽñ� ����ð� (�д��� �Դϴ�. �⺻�� 5��.)
// �����δ� 1�� �̳��� �����ϴ°� �´µ�, �� �������� �ױ⵵ �ϴ� 5���� �ݴϴ�.
$g4['tempsave_time'] = 5;

// HTML Purifier Cache ���丮
$g4['htmlpurifier_cache'] = $g4[data_path].'/cache';

// ���� ��ĸí : https://www.google.com/recaptcha/admin
$g4['recaptcha_sitekey'] = "";
$g4['recaptcha_secret_key'] = "";

// froala key
$g4['froala_key'] = "VZSZGUSXYSMZe1JGZ==";
?>