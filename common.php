<?
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

// 짧은 환경변수를 지원하지 않는다면
if (isset($HTTP_POST_VARS) && !isset($_POST)) {
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

  if (!isset($_SESSION))
		$_SESSION = &$HTTP_SESSION_VARS;
}

//
// phpBB2 참고
// php.ini 의 magic_quotes_gpc 값이 FALSE 인 경우 addslashes() 적용
// SQL Injection 등으로 부터 보호
//
if( !get_magic_quotes_gpc() )
{
	if( is_array($_GET) )
	{
		while( list($k, $v) = each($_GET) )
		{
			if( is_array($_GET[$k]) )
			{
				while( list($k2, $v2) = each($_GET[$k]) )
				{
					$_GET[$k][$k2] = addslashes($v2);
				}
				@reset($_GET[$k]);
			}
			else
			{
				$_GET[$k] = addslashes($v);
			}
		}
		@reset($_GET);
	}

	if( is_array($_POST) )
	{
		while( list($k, $v) = each($_POST) )
		{
			if( is_array($_POST[$k]) )
			{
				while( list($k2, $v2) = each($_POST[$k]) )
				{
					$_POST[$k][$k2] = addslashes($v2);
				}
				@reset($_POST[$k]);
			}
			else
			{
				$_POST[$k] = addslashes($v);
			}
		}
		@reset($_POST);
	}

	if( is_array($_COOKIE) )
	{
		while( list($k, $v) = each($_COOKIE) )
		{
			if( is_array($_COOKIE[$k]) )
			{
				while( list($k2, $v2) = each($_COOKIE[$k]) )
				{
					$_COOKIE[$k][$k2] = addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			}
			else
			{
				$_COOKIE[$k] = addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

if ($_GET['g4_path'] || $_POST['g4_path'] || $_COOKIE['g4_path']) {
    unset($_GET['g4_path']);
    unset($_POST['g4_path']);
    unset($_COOKIE['g4_path']);
    unset($g4_path);
}

// Proxy를 거쳐서 들어오는 경우, 실제 IP로 변경, http://virendrachandak.wordpress.com/2011/10/23/getting-real-client-ip-address-in-php-2/
function get_real_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))  //check ip from share internet
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  //to check ip is pass from proxy
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (!empty($_SERVER['HTTP_X_FORWARDED']))
        $ip=$_SERVER['HTTP_X_FORWARDED'];
    else if (!empty($_SERVER['HTTP_FORWARDED_FOR']))
        $ip=$_SERVER['HTTP_FORWARDED_FOR'];
    else if (!empty($_SERVER['HTTP_FORWARDED']))
        $ip=$_SERVER['HTTP_FORWARDED'];
    else
        $ip=$_SERVER['REMOTE_ADDR'];
    return trim($ip);
}
$_SERVER["REMOTE_ADDR"] = get_real_ip();

//==========================================================================================================================
// XSS(Cross Site Scripting) 공격에 의한 데이터 검증 및 차단
//--------------------------------------------------------------------------------------------------------------------------
function xss_clean($data) 
{ 
    // If its empty there is no point cleaning it :\ 
    if(empty($data)) 
        return $data; 
         
    // Recursive loop for arrays 
    if(is_array($data)) 
    { 
        foreach($data as $key => $value) 
        { 
            $data[$key] = xss_clean($value); 
        } 
         
        return $data; 
    } 
     
    // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php 
    // +----------------------------------------------------------------------+ 
    // | Copyright (c) 2001-2006 Bitflux GmbH                                 | 
    // +----------------------------------------------------------------------+ 
    // | Licensed under the Apache License, Version 2.0 (the "License");      | 
    // | you may not use this file except in compliance with the License.     | 
    // | You may obtain a copy of the License at                              | 
    // | http://www.apache.org/licenses/LICENSE-2.0                           | 
    // | Unless required by applicable law or agreed to in writing, software  | 
    // | distributed under the License is distributed on an "AS IS" BASIS,    | 
    // | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      | 
    // | implied. See the License for the specific language governing         | 
    // | permissions and limitations under the License.                       | 
    // +----------------------------------------------------------------------+ 
    // | Author: Christian Stocker <chregu@bitflux.ch>                        | 
    // +----------------------------------------------------------------------+ 
     
    // Fix &entity\n; 
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data); 
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/', '$1;', $data); 
    $data = preg_replace('/(&#x*[0-9A-F]+);*/i', '$1;', $data); 

    if (function_exists("html_entity_decode"))
    {
        $data = html_entity_decode($data); 
    }
    else
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $data = strtr($data, $trans_tbl);
    }

    // Remove any attribute starting with "on" or xmlns 
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#i', '$1>', $data); 

    // Remove javascript: and vbscript: protocols 
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2nojavascript...', $data); 
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2novbscript...', $data); 
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#', '$1=$2nomozbinding...', $data); 

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span> 
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data); 
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data); 
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#i', '$1>', $data); 

    // Remove namespaced elements (we do not need them) 
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data); 

    do 
    { 
        // Remove really unwanted tags 
        $old_data = $data; 
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data); 
    } 
    while ($old_data !== $data); 
     
    return $data; 
} 

$_GET = xss_clean($_GET);
//==========================================================================================================================


//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // GET, POST 로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// 완두콩님이 알려주신 보안관련 오류 수정
// $member 에 값을 직접 넘길 수 있음
$config = array();
$member = array();
$board  = array();
$group  = array();
$g4     = array();

// index.php 가 있는곳의 상대경로
// php 인젝션 ( 임의로 변수조작으로 인한 리모트공격) 취약점에 대비한 코드
// prosper 님께서 알려주셨습니다.
if (!$g4_path || preg_match("/:\/\//", $g4_path))
    die("<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'><script type='text/javascript'> alert('잘못된 방법으로 변수가 정의되었습니다.'); </script>");
//if (!$g4_path) $g4_path = ".";
$g4['path'] = $g4_path;

// 경로의 오류를 없애기 위해 $g4_path 변수는 해제
unset($g4_path);

include_once("$g4[path]/lib/constant.php");  // 상수 정의
include_once("$g4[path]/config.php");  // 설정 파일
include_once("$g4[path]/lib/common.lib.php"); // 공통 라이브러리

// config.php 가 있는곳의 웹경로
if (!$g4['url'])
{
    $g4['url'] = 'http://' . $_SERVER['HTTP_HOST'];
    $dir = dirname($_SERVER["PHP_SELF"]);
    if (!file_exists("config.php"))
        $dir = dirname($dir);
    $cnt = substr_count($g4['path'], "..");
    for ($i=2; $i<=$cnt; $i++)
        $dir = dirname($dir);
    $g4['url'] .= $dir;
}
// \ 를 / 롤 변경
$g4['url'] = strtr($g4['url'], "\\", "/");
// url 의 끝에 있는 / 를 삭제한다.
$g4['url'] = preg_replace("/\/$/", "", $g4['url']);

//==============================================================================
// 공통
//==============================================================================
$dirname = dirname(__FILE__).'/';
$dbconfig_file = "dbconfig.php";
    @include_once("$g4[path]/$dbconfig_file");
    $connect_db = sql_connect($mysql_host, $mysql_user, $mysql_password);
    $select_db = sql_select_db($mysql_db, $connect_db);
    if (!$select_db)
        die("<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'><script type='text/javascript'> alert('DB 접속 오류'); </script>");

// PDO 접속
try {
    $pdo_db = new PDO( "mysql:host=$mysql_host;dbname=$mysql_db;charset=" . str_replace('-', '', $g4[charset]), "$mysql_user", "$mysql_password");
    $pdo_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'><script type='text/javascript'> alert('PDO 접속 오류 : ' . $e->getMessage()); </script>");
}

$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);

//-------------------------------------------
// SESSION 설정
//-------------------------------------------
@ini_set("session.use_trans_sid", 0);     // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags","");         // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

// 사용할 session 형태를 지정 합니다. db, redis, file - 3종 입니다
switch ($g4['session_type']) {
    case "db"       :
        include_once("$g4[path]/lib/dbsession.lib.php");
        $session = new g4_dbsession();
        session_set_save_handler(array($session, 'open'), 
                                 array($session, 'close'),
                                 array($session, 'read'),
                                 array($session, 'write'),
                                 array($session, 'destroy'),
                                 array($session, 'gc'));
        break;
    case "redis" :
        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', $g4['rpath']);
        break;
    default :
        // 그누보드 기본 세션관리
        session_save_path("{$g4['data_path']}/session");
}

if (isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

//==============================================================================
// 공용 변수
//==============================================================================
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch(" select * from $g4[config_table] ");

// redis는 세션관리가 정확하게 이루어지기 때문에, 시간을 길게 잡아줘야 합니다
if ($g4['session_type'] == "redis") {
    @ini_set("session.cache_expire", 7200); // 세션 캐쉬 보관시간 (분)
    @ini_set("session.gc_maxlifetime", 504000); // session data의 garbage collection 존재 기간을 지정 (초)
} else {
    @ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
    @ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
}
@ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
@ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, "/");
@ini_set("session.cookie_domain", $g4['cookie_domain']);

@session_start();

/*
// 081022 : CSRF 방지를 위해 코드를 작성했으나 효과가 없어 주석처리 함
if (strpos($_SERVER[PHP_SELF], $g4['admin']) === false)
    set_session("ss_admin", false);
*/

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if ($_REQUEST['PHPSESSID'] && $_REQUEST['PHPSESSID'] != session_id())
    goto_url("{$g4['bbs_path']}/logout.php");

// QUERY_STRING
$qstr = "";

if (isset($_REQUEST['sca']))  {
    $sca = clean_xss_tags(trim($_REQUEST['sca']));
    if ($sca) {
        $sca = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $sca);
        $qstr .= '&amp;sca=' . urlencode($sca);
    }
} else {
    $sca = '';
}

if (isset($_REQUEST['sfl']))  {
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl); 
    if ($sfl)
        $qstr .= '&amp;sfl=' . urlencode($sfl); // search field (검색 필드)
} else {
    $sfl = '';
}

if (isset($_REQUEST['stx']))  { // search text (검색어)
    $stx = get_search_string(trim($_REQUEST['stx']));
    if ($stx)
        $qstr .= '&amp;stx=' . urlencode(cut_str($stx, 20, ''));
} else {
    $stx = '';
}

if (isset($_REQUEST['sst']))  {
    $sst = trim($_REQUEST['sst']);
    $sst = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sst); 
    if ($sst)
        $qstr .= '&amp;sst=' . urlencode($sst); // search sort (검색 정렬 필드)
} else {
    $sst = '';
}

if (isset($_REQUEST['sod']))  { // search order (검색 오름, 내림차순)
    $sod = preg_match("/^(asc|desc)$/i", $sod) ? $sod : '';
    if ($sod)
        $qstr .= '&amp;sod=' . urlencode($sod);
} else {
    $sod = '';
}

if (isset($_REQUEST['sop']))  { // search operator (검색 or, and 오퍼레이터)
    $sop = preg_match("/^(or|and)$/i", $sop) ? $sop : '';
    if ($sop)
        $qstr .= '&amp;sop=' . urlencode($sop);
} else {
    $sop = '';
}

if (isset($_REQUEST['spt']))  { // search part (검색 파트[구간])
    $spt = (int)$spt;
    if ($spt)
        $qstr .= '&amp;spt=' . urlencode($spt);
} else {
    $spt = '';
}

if (isset($_REQUEST['page'])) { // 리스트 페이지
    $page = abs((int)$_REQUEST['page']);
    if ($page)
        $qstr .= '&amp;page=' . urlencode($page);
} else {
    $page = '';
}

if (isset($_REQUEST['w'])) {
    $w = substr($w, 0, 2);
} else {
    $w = '';
}

if (isset($_REQUEST['wr_id'])) {
    $wr_id = (int)$_REQUEST['wr_id'];
} else {
    $wr_id = 0;
}

// URL ENCODING
if (isset($url)) {
    $urlencode = urlencode($url);
}
else {
    // 2008.01.25 Cross Site Scripting 때문에 수정
    //$urlencode = $_SERVER['REQUEST_URI'];
    $urlencode = urlencode($_SERVER[REQUEST_URI]);
}
if (isset($total_page)) { // 리스트 페이지
    $total_page = (int)$total_page;
}


if (isset($comment_id)) { // 리스트 페이지
    $comment_id = (int)$comment_id;
}

if (isset($_GET['mb_id'])) {
    $mb_id = preg_replace('/[^0-9a-z\-\_]/i', '', $_GET['mb_id']);
}

if (isset($mb_email)) {
    $mb_email = mysql_real_escape_string($mb_email);
}

if (isset($po_id)) {
    $po_id = (int)$po_id;
}

if (isset($pc_id)) {
    $pc_id = (int)$pc_id;
}

if (isset($_GET['ug_id'])) {
    $ug_id = preg_replace('/[^0-9a-z\-\_]/i', '', $_GET['ug_id']);
}

// 그누보드 4.34.09 보안패치 ($_SERVER의 SQL Injection 방어)
$_SERVER['REMOTE_ADDR'] = get_text(clean_xss_tags($_SERVER['REMOTE_ADDR']));
$_SERVER['HTTP_REFERER'] = get_text(clean_xss_tags($_SERVER['HTTP_REFERER']));
$_SERVER['HTTP_USER_AGENT'] = get_text(clean_xss_tags($_SERVER['HTTP_USER_AGENT']));

$remote_addr = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
$referer    = mysql_real_escape_string($_SERVER['HTTP_REFERER']);
$user_agent  = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);

//===================================

// 자동로그인 부분에서 첫로그인에 포인트 부여하던것을 로그인중일때로 변경하면서 코드도 대폭 수정하였습니다.
if ($_SESSION['ss_mb_id']) // 로그인중이라면
{
    $member = get_member($_SESSION['ss_mb_id']);

    // 오늘 처음 로그인 이라면
    if (substr($member['mb_today_login'], 0, 10) != $g4['time_ymd'])
    {
        // 첫 로그인 포인트 지급
        insert_point($member['mb_id'], $config['cf_login_point'], "{$g4['time_ymd']} 첫로그인", "@login", $member['mb_id'], $g4['time_ymd']);

        // 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
        // 해당 회원의 접근일시와 IP 를 저장
        $sql = " update {$g4['member_table']} set mb_today_login = '{$g4['time_ymdhis']}', mb_login_ip = '$remote_addr' where mb_id = '{$member['mb_id']}' ";
        sql_query($sql);
    } else {
        // 오늘의 마지막 login_ip와 현재의 로그인 ip가 다르면, 현재의 로그인 ip로 업데이트 한다
        if ($member['mb_login_ip'] !== $_SERVER['REMOTE_ADDR']) {
            $sql = " update {$g4['member_table']} set mb_login_ip = '$remote_addr' where mb_id = '{$member['mb_id']}' ";
            sql_query($sql);
        }
    }
}
else
{
    // 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if ($tmp_mb_id = get_cookie("ck_mb_id"))
    {
        // 불당팩 - 암호화된 쿠키값을 이용해서 mb_id를 가져온다
        $sql = " select * from $g4[cookie_table] where cookie_name='$tmp_mb_id' ";
        $mb_cookie = sql_fetch($sql);

        $tmp_mb_id = $mb_cookie['cookie_value'];
        $tmp_key = $mb_cookie['cookie_key'];

        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        if (strtolower($tmp_mb_id) != strtolower($config['cf_admin'])) 
        {
            $row = get_member("$tmp_mb_id", "mb_no, mb_password, mb_intercept_date, mb_leave_date, mb_email_certify");
            if ($g4['load_balance']) {
                $key = md5($g4['load_balance'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password'] . $row['mb_no']);
            } else {
                $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password'] . $row['mb_no']);
            }

            // 쿠키에 저장된 키와 같다면
            if ($tmp_key == $key && $tmp_key)
            {
                // 차단, 탈퇴가 아니고 메일인증이 사용이면서 인증을 받았다면
                if ($row['mb_intercept_date'] == "" &&
                    $row['mb_leave_date'] == "" &&
                    (!$config['cf_use_email_certify'] || preg_match('/[1-9]/', $row['mb_email_certify'])) )
                {
                    // 세션에 회원아이디를 저장하여 로그인으로 간주
                    set_session("ss_mb_id", $tmp_mb_id);

                    // 페이지를 재실행
                    echo "<script type='text/javascript'> window.location.reload(); </script>";
                    exit;
                }
            }
            // $row 배열변수 해제
            unset($row);
        }
    }
    // 자동로그인 end ---------------------------------------
}

// 첫방문 쿠키
// 1년간 저장
if (!get_cookie("ck_first_call"))     set_cookie("ck_first_call", $g4[server_time], 86400 * 365);
if (!get_cookie("ck_first_referer"))  set_cookie("ck_first_referer", $_SERVER[HTTP_REFERER], 86400 * 365);

// 회원이 아니라면 권한을 방문객 권한으로 함
if (!($member['mb_id']))
    $member['mb_level'] = 1;
else
    $member['mb_dir'] = substr($member['mb_id'],0,2);

$write_table = "";

if (isset($_REQUEST['bo_table'])) {
    $bo_table = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['bo_table']));

    //$board = sql_fetch(" select * from {$g4['board_table']} where bo_table = '$bo_table' ");
    $stmt = $pdo_db->prepare(" select * from {$g4['board_table']} where bo_table = :bo_table ");
    $stmt->bindParam(":bo_table", $bo_table);
    $board = pdo_fetch($stmt);

    $write_table = $g4['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
    $gr_id = $board['gr_id'];
    if (isset($wr_id) && $wr_id) {
        $write = sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");
    }
} else {
    $bo_table = '';
}

// adm/board_list.php에서 gr_id를 배열로 쓰기 때문에, is_array를 체크해야 합니다. =..=...
if (isset($gr_id) && !is_array($gr_id)) {
    $gr_id = preg_match("/^[a-zA-Z0-9_]+$/", $gr_id) ? $gr_id : "";
    //$group = sql_fetch(" select * from {$g4['group_table']} where gr_id = '$gr_id' ");

    $stmt = $pdo_db->prepare(" select * from {$g4['group_table']} where gr_id = :gr_id ");
    $stmt->bindParam(":gr_id", $gr_id);
    $group = pdo_fetch($stmt);
}

// 회원, 비회원 구분
$is_member = $is_guest = false;
if ($member['mb_id'])
    $is_member = true;
else
    $is_guest = true;


$is_admin = is_admin($member['mb_id']);
if ($is_admin != "super") {
    // 접근가능 IP
    $cf_possible_ip = trim($config['cf_possible_ip']);
    if ($cf_possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $cf_possible_ip);
        for ($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if (empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pat = "/^{$pattern[$i]}/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if ($is_possible_ip)
                break;
        }
        if (!$is_possible_ip)
            die ("접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['cf_intercept_ip']));
    for ($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pat = "/^{$pattern[$i]}/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if ($is_intercept_ip)
            die ("접근 불가합니다.");
    }
}

// 스킨경로
$board_skin_path = '';
if (isset($board['bo_skin']))
    $board_skin_path = "{$g4['path']}/skin/board/{$board['bo_skin']}"; // 게시판 스킨 경로

// 방문자수의 접속을 남김
include_once("{$g4['bbs_path']}/visit_insert.inc.php");

// common.php 파일을 수정할 필요가 없도록 확장합니다.
$tmp = dir("$g4[path]/extend");
while ($entry = $tmp->read()) {
    // php 파일만 include 함
    if (preg_match("/(\.php)$/i", $entry))
      if ($entry != "index.php")
        include_once("$g4[path]/extend/$entry");
}

// 곱슬최씨 - 게시판별 카운터
if ($gr_id && $bo_table) 
{
    $board_visit = "$g4[time_ymd].$gr_id.$bo_table.$_SERVER[REMOTE_ADDR]";

    //$sql = " select count(*) as cnt from $mw[board_visit_log_table] where log = '$board_visit' ";
    //$result = sql_fetch($sql);

    $stmt = $pdo_db->prepare(" select count(*) as cnt from $mw[board_visit_log_table] where log = :board_visit ");
    $stmt->bindParam(":board_visit", $board_visit);
    $result = pdo_fetch($stmt);

    // $result[cnt] == 0 : 오늘 처음 방문하는 경우.
    if ($result[cnt] == 0) {
        //$qry = sql_query("insert into $mw[board_visit_log_table] set log = '$board_visit'", false);

        $stmt = $pdo_db->prepare(" insert into $mw[board_visit_log_table] set log = :board_visit ");
        $stmt->bindParam(":board_visit", $board_visit);
        $result = pdo_query($stmt);

        if ($result) {
            //$sql = " update $mw[board_visit_table] set bv_count = bv_count + 1 where bv_date = '$g4[time_ymd]' and gr_id = '$gr_id' and bo_table = '$bo_table' ";
            //$qry = sql_query($sql, false);

            $stmt = $pdo_db->prepare(" update $mw[board_visit_table] set bv_count = bv_count + 1 where bv_date = '$g4[time_ymd]' and gr_id = :gr_id and bo_table = :bo_table ");
            $stmt->bindParam(":gr_id", $gr_id);
            $stmt->bindParam(":bo_table", $bo_table);
            $result = pdo_query($stmt, false);

            // 수정된 row가 하나도 없다면(업데이트가 안되는 오류) 새로운 날짜별 행을 생성하도록 insert를 실행
            if ( $stmt->rowCount() == 0 ) {
                //$sql = " insert $mw[board_visit_table] set bv_date = '$g4[time_ymd]', gr_id = '$gr_id', bo_table = '$bo_table', bv_count = 1 ";
                //$qry = sql_query($sql);

                $stmt = $pdo_db->prepare(" insert $mw[board_visit_table] set bv_date = '$g4[time_ymd]', gr_id = :gr_id, bo_table = :bo_table, bv_count = 1 ");
                $stmt->bindParam(":gr_id", $gr_id);
                $stmt->bindParam(":bo_table", $bo_table);
                $result = pdo_query($stmt, false);
            }
        }
        unset($board_visit);
    }
}

// 글쓰기제한
$is_delay = false;
if ($member['mb_level'] > 1) {
    if ($member['mb_level'] >= $config['cf_delay_level'] || $member['mb_point'] >= $config['cf_delay_point'] || $is_admin)
        $is_delay = true;
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
// head.sub.php에서 이동
$lo_location = addslashes($g4['title']);
if (!$lo_location)
    $lo_location = $_SERVER['REQUEST_URI'];
$lo_url = $_SERVER['REQUEST_URI'];
if (strstr($lo_url, "/$g4[admin]/") || $is_admin == "super") $lo_url = "";

// 변수 필터링
$bg_id = preg_match("/^[a-zA-Z0-9_]+$/", $bg_id) ? $bg_id : "";

// 불당팩 - 추가적인 개별 변수설정을 위해
include_once("$g4[path]/config.2.php");  // 설정 파일
?>
