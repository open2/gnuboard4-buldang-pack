<?
include_once("./_common.php");
include_once("$g4[path]/lib/mailer.lib.php");

if ($member[mb_id]) 
{
    echo "<script type='text/javascript'>";
    echo "alert('�̹� �α������Դϴ�.');";
    echo "window.close();";
    echo "opener.document.location.reload();";
    echo "</script>";
    exit;
}

if (chk_recaptcha() == false)
    alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.');

$email = trim($_POST['mb_email']);

if (!$email) 
    // �����ּ� �����Դϴ�.
    alert_close("�������� ������ �ƴѰ� �����ϴ� - 100");

$sql = " select count(*) as cnt from $g4[member_table] where mb_email = '$email' ";
$row = sql_fetch($sql);
if ($row[cnt] > 1)
    alert("������ �����ּҰ� 2�� �̻� �����մϴ�.\\n\\n�����ڿ��� �����Ͽ� �ֽʽÿ�.");

$sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from $g4[member_table] where mb_email = '$email' ";
$mb = sql_fetch($sql);
$msg = "";
if (!$mb[mb_id])
    // �������� �ʴ� ȸ���Դϴ�.
    $msg = "�������� ������ �ƴѰ� �����ϴ� - 110";
else if (is_admin($mb[mb_id])) 
    // ������ ���̵�� ���� �Ұ��մϴ�.
    $msg = "�������� ������ �ƴѰ� �����ϴ� - 120";

// �Ҵ��� - �н����� ã��� �峭�� �ϴ� ���� ���ƾ���.
if ($msg) {

    // �ڵ� ȣȯ���� ���ؼ� ������ setting
    $mb_id = $mb[mb_id];

    // �Ҵ��� : �Ʒ� �ڵ�� �Ҵ����� /bbs/login_check.php�� ���� �մϴ�. ---------------------------------------

    // �α��� ������ db�� ��� �մϴ�.
    $sql = " insert into $g4[login_fail_log_table] (mb_id, ip_addr, log_datetime, log_url) values ('$mb_id', '$remote_addr', '$g4[time_ymdhis]', '/bbs/password_lost2.php') ";
    sql_query($sql);

    // ���� Ƚ���� üũ�ؼ� ���������� ���� �մϴ�.
    if ($config['cf_retry_time_interval'] > 0 && $config['cf_retry_count'] > 0) {
        $sql = " select count(*) as cnt from $g4[login_fail_log_table] where log_datetime >= '" . date("Y-m-d H:i:s", $g4[server_time] - $config['cf_retry_time_interval'] ) . "' and ip_addr='$remote_addr' ";
        $result = sql_fetch($sql);

        $ip = $_SERVER[REMOTE_ADDR];
        
        // ȸ�� -2�϶�, ��� �޽���, 4ȸ ���Ŀ� IP ������ �ϴ� ��� �޽��� ���� ���� �� �� �����Ƿ�, Ƚ���� 5ȸ �̻����� �ϴ°� �����ϴ�.
        if (($result['cnt']+3) == $config['cf_retry_count']) {
            alert("2ȸ ������ �� �ϴ� ���, IP�� ���� �˴ϴ�.");
        }

        // Ƚ�� �ʰ��� ����
        if ($result['cnt'] >= $config['cf_retry_count']) {
            $pattern = explode("\n", trim($config['cf_intercept_ip']));
            if (empty($pattern[0])) // ip ���ܸ���� ��� ���� ��
                $cf_intercept_ip = $ip;
            else
                $cf_intercept_ip = trim($config['cf_intercept_ip'])."\n{$ip}";
            $sql = " update {$g4['config_table']} set cf_intercept_ip = '$cf_intercept_ip' ";
            sql_query($sql);

            alert_close($msg);
        } else {
            alert($msg);
        }
    }
}

// ���� �߻�
srand(time());
$randval = rand(4, 6); 

$change_password = substr(md5(get_microtime()), 0, $randval);

$mb_lost_certify = sql_password($change_password);
$mb_datetime     = sql_password($mb[mb_datetime]);

$sql = " update $g4[member_table]
            set mb_lost_certify = '$mb_lost_certify'
          where mb_id = '$mb[mb_id]' ";
$res = sql_query($sql);

// $mb_no�� ��ȣȭ �մϴ�.
$mb_no = encrypt($mb[mb_no], $g4[encrypt_key]);

$href = "$g4[url]/$g4[bbs]/password_lost_certify.php?mb_no=$mb_no&mb_datetime=$mb_datetime&mb_lost_certify=$mb_lost_certify";

$subject = "��û�Ͻ� ȸ�����̵�/�н����� �����Դϴ�.";

$content = "";
$content .= "<div style='line-height:180%;'>";
$content .= "<p>��û�Ͻ� ���������� ������ �����ϴ�.</p>";
$content .= "<hr>";
$content .= "<ul>";
$content .= "<li>ȸ�����̵� : $mb[mb_id]</li>";
$content .= "<li>���� �н����� : <span style='color:#ff3300; font:13px Verdana;'><strong>$change_password</strong></span></li>";
$content .= "<li>�̸� : ".addslashes($mb[mb_name])."</li>";
$content .= "<li>���� : ".addslashes($mb[mb_nick])."</li>";
$content .= "<li>�̸����ּ� : ".addslashes($mb[mb_email])."</li>";
$content .= "<li>��û�Ͻ� : $g4[time_ymdhis]</li>";
$content .= "<li>Ȩ������ : $g4[url]</li>";
$content .= "<li>��й�ȣ �ٲٴ� ��ũ : <a href='$href' target='_blank'>$href</a></p>";
$content .= "</ul>";
$content .= "<hr>";
$content .= "<p>";
$content .= "1. ���� ��ũ�� Ŭ���Ͻʽÿ�. ��ũ�� Ŭ������ �ʴ´ٸ� ��ũ�� �������� �ּ�â�� ���� ������ �����ñ� �ٶ��ϴ�.<br />";
$content .= "2. ��ũ�� Ŭ���Ͻø� �н����尡 ���� �Ǿ��ٴ� ���� �޼����� ��µ˴ϴ�.<br />";
$content .= "3. Ȩ���������� ȸ�����̵�� ���� ���� ���� �н������ �α��� �Ͻʽÿ�.<br />";
$content .= "4. �α��� �Ͻ� �� ���ο� �н������ �����Ͻø� �˴ϴ�.<br />";
$content .= "5. <font color=red>���� ��ũ�� �ι� �θ��� ��й�ȣ�� ���Ƿ� ���� �ǹǷ�, ��й�ȣ ã��� ���ο� �н����带 �޾ƾ� �մϴ�.</font><br />";
$content .= "6. ���̵��� Ȯ�α⸦ ���ϴ� ��쿡��, ���� ��ũ�� ������ �ʰ� ȸ�����̵�� �˰� �ִ� ������ ��й�ȣ�� �α��� �ϸ� �˴ϴ�.<br />";
$content .= "</p>";
$content .= "<p>�����մϴ�.</p>";
$content .= "<p>[��]</p>";
$content .= "</div>";

$admin = get_admin('super');
mailer($admin[mb_nick], $admin[mb_email], $mb[mb_email], $subject, $content, 1);

alert_close("$email ���Ϸ� ȸ�����̵�� �н����带 ������ �� �ִ� ������ �߼� �Ǿ����ϴ�.\\n\\n������ Ȯ���Ͽ� �ֽʽÿ�.");
?>