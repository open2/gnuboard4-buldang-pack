<?
include_once("./_common.php");
include_once("$g4[path]/memo.config.php");

if ($_SESSION["sm_datetime"] >= ($g4['server_time'] - $g4['memo_delay_sec']) && !$is_admin) 
    alert("�ʹ� ���� �ð����� ������ �����ؼ� �߽��� �� �����ϴ�.");
set_session("sm_datetime", $g4['server_time']);

$me_send_mb_id = strip_tags($_POST['me_send_mb_id']);
$me_recv_mb_id = strip_tags($_POST['me_recv_mb_id']);

if (!$member['mb_id'])
    alert("ȸ���� �̿��Ͻ� �� �ֽ��ϴ�.");

$me_send_mb_id = trim($me_send_mb_id);
if ($me_send_mb_id == $member['mb_id']) {} else
    alert("memo_update - �ٸ��� ���� ����Դϴ�.");

if ($me_subject == '')
    alert("���� ������ �Էµ��� �ʾҽ��ϴ�.");

// ������ ������ ����
$me_memo = addslashes($wr_content);

$tmp_list = explode(",", $me_recv_mb_id);

// ���� ��ź ���Ÿ� ���Ͽ�, �ߺ��� ����
$tmp_list = array_unique($tmp_list);
$tmp_list = implode(",",$tmp_list);
$tmp_list = explode(",",$tmp_list);

$me_recv_mb_id_list = "";
$msg = "";
$comma1 = $comma2 = "";
$mb_list = array();
$mb_array = array();
for ($i=0; $i<count($tmp_list); $i++) {
    $row = get_member($tmp_list[$i]);
    // ģ�������� ����Ǿ��� ��
    if ($row['mb_id']) { // ȸ�������� �ִ� ��� ���� ������Ʈ�� ���ԵǾ����� Ȯ��
        $sql2 = " select count(*) as cnt from $g4[friend_table] where fr_id = '$me_send_mb_id' and mb_id = '$row[mb_id]' and fr_type = 'black_id' ";
        $result2 = sql_fetch($sql2);
    }
    if (!$row['mb_id'] || $row['mb_leave_date'] || $row['mb_intercept_date'] || $result2['cnt']>0) {
        $msg .= "$comma1$tmp_list[$i]";
        $comma1 = ",";
    } else {
        if ($config['cf_memo_mb_name'])
            $me_recv_mb_id_list .= "$comma2$row[mb_name]";
        else
            $me_recv_mb_id_list .= "$comma2$row[mb_nick]";
        $mb_list[] = $tmp_list[$i];
        $mb_array[] = $row;
        $comma2 = ",";
    }
}

if (!$is_admin) {
    if (count($mb_list)) {
        $point = (int)$config['cf_memo_send_point'] * count($mb_list);
        if ($point) {
            if ($member['mb_point'] - $point < 0) {
                alert("�����Ͻ� ����Ʈ(".number_format($member[mb_point])."��)�� ���ڶ� ������ ���� �� �����ϴ�.");
            } 
        }
    }
}

if ($msg && count($mb_list)==0)
    alert("ȸ�����̵� \'".$msg."\' ��(��) �������� �ʰų�(Ż��, ��������) ������ �ź��ϴ� ���̵� �Դϴ�.\\n\\n������ �߼����� �ʾҽ��ϴ�.");

// ���ϸ� �ʱ�ȭ
$file_name0 = '';
$file_name3 = '';

// �������� ���ε� ������ �ִ� ���� �뷮
//$upload_max_filesize = ini_get('upload_max_filesize');

// ����2���� ���ε� ������ �ִ� ���� �뷮
//$memo2_upload_size = intval(substr($config[cf_memo_file_size],0,-1)) * 1024 * 1024;
if ($config['cf_memo_file_size'])
    $memo2_upload_size = $config['cf_memo_file_size'] * 1024 * 1024;
else {
    $max_upload_size = intval(substr(ini_get("upload_max_filesize"), 0, -1));
    $memo2_upload_size = $max_upload_size * 1024 * 1024;
}

for ($i=0; $i<count($mb_list); $i++) {

    if (trim($mb_list[$i])) {

        // ÷������
        if ($i ==0 and $_FILES[memo_file][name]) { // ù��° loop���� ÷�������� ���̵� me_id�� �����ϰ� ���� - ���ϰ� �����Ϸ���

              // ȸ������ ���丮�� ����
              $dir_name = $g4['path'] . "/data/memo2/" . $member[mb_id];
              if(!is_dir($dir_name)){
                  @mkdir("$dir_name", 0707);
                  @chmod("$dir_name", 0707);
              }
    
              $file_name0 = $_FILES[memo_file][name];

              // �����ڰ� �ƴϸ鼭 ������ ���ε� ������� ũ�ٸ� �ǳʶ�
              $tmp_file  = $_FILES[memo_file][tmp_name];
              $filesize  = $_FILES[memo_file][size];

              if (is_uploaded_file($tmp_file)) {
                  if ($filesize > $memo2_upload_size) {
                      $file_upload_msg .= "\'{$file_name0}\' ������ �뷮(".number_format($filesize)." ����Ʈ)�� ����2���� ����(".number_format($memo2_upload_size)." ����Ʈ)�� ������ ũ�Ƿ� ���ε� ���� �ʽ��ϴ�.\\n";
                  }
              }

              // ������ ������ ������ ū������ ���ε� �Ѵٸ�
              if ($file_name0) {
                  if ($_FILES[memo_file][error] == 1) {
                      $file_upload_msg .= "\'{$file_name0}\' ������ �뷮�� ������ ��{$config[cf_memo_file_size]}���� ũ�Ƿ� ���ε� �� �� �����ϴ�.\\n";
                  } else if ($_FILES[memo_file][error] != 0) {
                      $file_upload_msg .= "\'{$file_name0}\' ������ ���������� ���ε� ���� �ʾҽ��ϴ�.\\n";
                  }
              }

              // �Ҵ��� : �̹��� Ȯ���ڸ� ������� �̹����������� Ȯ��
              if ($file_name0 && preg_match("/\.($config[cf_image_extension])/i", $file_name0))
              {
                  if (!getimagesize($tmp_file)) {
                      $file_upload_msg .= "\'{$file_name0}\' ������ ���������� ���ε� ���� �ʾҽ��ϴ�.\\n";
                  }
              }

              if ($file_upload_msg) {
                  alert($file_upload_msg);
              }
              
              // �Ʒ��� ���ڿ��� �� ������ -x �� �ٿ��� ����θ� �˴��� ������ ���� ���ϵ��� ��        
              // ���ϸ� ���� (NaviGator��)
              $file_name0= str_replace(' ', '_',$file_name0); 
              $file_name0= str_replace('\\\'', '_',$file_name0); 

              $file_name1 = intval($me_id) . "_" . preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $file_name0);
              $file_name2 = str_replace('%', '', urlencode($file_name1));

             	@move_uploaded_file($_FILES[memo_file][tmp_name], "$dir_name/$file_name2");
            	@chmod("$dir_name/$file_name2", 0606);

              $file_name3 = $member[mb_id] . "/" . $file_name2;
        }

        // ���� ������
        memo4_send($mb_list[$i], $member[mb_id], $me_memo, $me_subject, "$html,$secret,$mail", 1, $file_name0, $file_name3);

        // ����Ʈ ��� - history�� ���ؼ�
        $recv_mb_nick = get_text($mb_array[$i][mb_nick]);
        $recv_mb_id = $mb_array[$i][mb_id];
        insert_point($member[mb_id], (int)$config[cf_memo_send_point] * (-1), "{$recv_mb_nick}({$recv_mb_id})�Բ� ���� �߼�", "@memo", $recv_mb_id, $me_id);
    }

} // for - loop�� ���κ�

if ($msg)
    alert("\'$msg\'���� �������� �ʰų� ������ �ź��ϴ� ���̵� �Դϴ�. \'$me_recv_mb_id_list\' �Բ� ������ �����Ͽ����ϴ�.", "./memo.php?kind=send");
else 
    alert("\'$me_recv_mb_id_list\' �Բ� ������ �����Ͽ����ϴ�.", "./memo.php?kind=send");

?>