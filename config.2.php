<?
// 불당팩의 사용과정에서 필요한 설정변경사항들로
// config.php의 수정을 최소로 하기 위해서 입니다.

$g4['bver']         = "3304";   // 부트스트랩 CSS 버젼
$g4['aver']         = "4400";   // 폰트어썸 CSS 버젼
$g4['sver']         = "1000";   // style.css CSS 버젼
$g4['cheditor_ver'] = "1000";   // cheditor 버젼
$g4['froala_ver']   = "20301";  // froala editor 버젼

// $qstr이 없는 상황에서 필요한 것을 넘기기 위해서 사용 (메뉴 변수 등...)
$mstr = "";
if (isset($mnb))  { // 불당빌더 기본메뉴
    $mnb = mysql_real_escape_string($mnb);
    $mstr .= '&mnb=' . urlencode($mnb);
}

if (isset($snb))  { // 불당빌더 서브메뉴
    $snb = mysql_real_escape_string($snb);
    $mstr .= '&snb=' . urlencode($snb);
}
if (isset($snb)) {
    if ($sfl == "wr_good" || $sfl == "wr_nogood" || $sfl == "wr_nogood_down" || $sfl == "wr_7" || $sfl == "wr_hit")  {
        $mstr .= '&sfl=' . urlencode($sfl);
        $mstr .= '&stx=' . urlencode($stx);
    }
}

if (isset($head_on))  { // 불당팩에서 가끔 쓰는 변수
    $head_on = (int) $head_on;
    $mstr .= '&head_on=' . urlencode($head_on);
}

// 메뉴 문자열을 합쳐 줍니다.
$qstr .= $mstr;

// 네이버 API
$g4['naver_api'] = "";

// 네이버 단축주소 API - https://dev.naver.com/openapi/register
$g4['me2do_key'] = "";

// 우체국 우편번호 API
$g4['epost_key'] = "f91427a9fc7337ff91385268803210";

// 채널 - 다수대의 web server를 쓸 때, 어떤 서버인지 확인을 위해 채널에 ip 마지막 자리를 넣어주면 편하다.
$g4['channel'] = "";

// bbs/write.php에서 그냥 나갈때 경고할지 안할지 결정
// 쓰기할 때 경고가 필요하지 않은 경우는 write.head.skin.php에서 false로 하면 됨
$g4['write_escape'] = true;

// 유니크로 쿠키를 구워줍니다.
if ($g4[unicro_url]) {
    $unicro_cookie_id = $member["mb_id"] . "^" . $member["mb_no"];
    if (isset($_COOKIE[unicro_id]) && $_COOKIE[unicro_id] == "$unicro_cookie_id") { } else {
        setcookie("unicro_id", "$unicro_cookie_id", $g4[server_time] + 3600, '/', $g4[cookie_domain]) ;
    }
}

// geoip 체크, 한국이면 KR이 리턴 됩니다.
if ($g4['use_geo_ip'])
    $geoip = ipaddress_to_country_code($_SERVER['REMOTE_ADDR']);
?>
