<?
// �Ҵ����� ���������� �ʿ��� ����������׵��
// config.php�� ������ �ּҷ� �ϱ� ���ؼ� �Դϴ�.

$g4['bver'] = "4303";   // ��Ʈ��Ʈ�� CSS ����
$g4['aver'] = "4300";   // ��Ʈ��� CSS ����
$g4['sver'] = "1000";   // style.css CSS ����

// $qstr�� ���� ��Ȳ���� �ʿ��� ���� �ѱ�� ���ؼ� ��� (�޴� ���� ��...)
$mstr = "";
if (isset($mnb))  { // �Ҵ���� �⺻�޴�
    $mnb = mysql_real_escape_string($mnb);
    $mstr .= '&mnb=' . urlencode($mnb);
}

if (isset($snb))  { // �Ҵ���� ����޴�
    $snb = mysql_real_escape_string($snb);
    $mstr .= '&snb=' . urlencode($snb);
}
if (isset($snb)) {
    if ($sfl == "wr_good" || $sfl == "wr_nogood" || $sfl == "wr_nogood_down" || $sfl == "wr_7" || $sfl == "wr_hit")  {
        $mstr .= '&sfl=' . urlencode($sfl);
        $mstr .= '&stx=' . urlencode($stx);
    }
}

if (isset($head_on))  { // �Ҵ��ѿ��� ���� ���� ����
    $head_on = (int) $head_on;
    $mstr .= '&head_on=' . urlencode($head_on);
}

// �޴� ���ڿ��� ���� �ݴϴ�.
$qstr .= $mstr;

// ���̹� API
$g4['naver_api'] = "";

// ���̹� �����ּ� API - https://dev.naver.com/openapi/register
$g4['me2do_key'] = "";

// ��ü�� �����ȣ API
$g4['epost_key'] = "f91427a9fc7337ff91385268803210";

// ä�� - �ټ����� web server�� �� ��, � �������� Ȯ���� ���� ä�ο� ip ������ �ڸ��� �־��ָ� ���ϴ�.
$g4['channel'] = "";

// bbs/write.php���� �׳� ������ ������� ������ ����
// ������ �� ��� �ʿ����� ���� ���� write.head.skin.php���� false�� �ϸ� ��
$g4['write_escape'] = true;

// ����ũ�� ��Ű�� �����ݴϴ�.
if ($g4[unicro_url]) {
    $unicro_cookie_id = $member["mb_id"] . "^" . $member["mb_no"];
    if (isset($_COOKIE[unicro_id]) && $_COOKIE[unicro_id] == "$unicro_cookie_id") { } else {
        setcookie("unicro_id", "$unicro_cookie_id", $g4[server_time] + 3600, '/', $g4[cookie_domain]) ;
    }
}

// geoip üũ, �ѱ��̸� KR�� ���� �˴ϴ�.
if ($g4['use_geo_ip'])
    $geoip = ipaddress_to_country_code($_SERVER['REMOTE_ADDR']);
?>