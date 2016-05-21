<?php
/**
 * Copyright 2016 Been Kyung-yoon.
 */

/**
 * PDO 쿼리
 *   - 바인딩을 배열로 받아 쉽게 처리함
 *   - 디비 에러시 서버에 에러 로그를 기록하고, 관리자일 경우 화면에도 출력
 *
 * @link http://php.net/manual/kr/pdo.prepare.php
 *
 * @param string $statement
 * @param array|null $attributes
 *
 * @return PDOStatement
 */

function pdo($statement, $attributes = array())
{
    global $pdo_db, $is_admin;

    try {
        $sth = $pdo_db->prepare($statement);
        $sth->execute($attributes);

        return $sth;
    } catch (PDOException $e) {
        $error = "[error/php79_query] " . $statement . PHP_EOL . serialize($attributes) . PHP_EOL . $e->getMessage();
        error_log($error);

        if ($is_admin === "super") {
            dd("디비 에러가 발생하였습니다.  (관리자에게만 표시됩니다.)" . PHP_EOL . $error);
        } else {
            response_error("디비 에러가 발생하였습니다.");
        }
    }
}

/**
 * PDO 데이타 삽입
 *   - 대량 할당 지원: 키->밸류 배열로 값을 전달하면 insert 쿼리문이 자동 생성되어 실행됨
 *
 * @param $table
 * @param array $attributes
 */
function pdo_create($table, $attributes = array())
{
    $columns = array();
    $values  = array();
    $params  = array();
    foreach ($attributes as $key => $val) {
        $columns[] = "`" . $key . "`";
        $values[]  = '?';
        $params[]  = $val;
    }

    $statement = "insert into `" . $table . "` (" . implode(',', $columns) . ") values (" . implode(',', $values) . ")";

    pdo($statement, $params);
}

/**
 * PDO 마지막으로 추가된 ID
 *
 * @return string
 */
function pdo_last_insert_id()
{
    global $pdo_db;

    return $pdo_db->lastInsertId();
}

/**
 * 디비 실행 결과에서 1개 컬럼만 가져오기
 *
 * @param      $sql
 * @param bool $error
 *
 * @return mixed
 */
function sql_one($sql, $error = true)
{
    global $g4;

    $result = sql_query($sql, $error);

    //$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysqli_errno() . " : " .  mysqli_error() . "<p>error file : $_SERVER['SCRIPT_NAME']");
    $row = sql_fetch_array($result);

    if (is_array($row)) {
        return array_shift($row);
    }
}

/**
 * 쿼리 결과에서 모든 행 가져오기
 *
 * @param      $sql
 * @param bool $error
 *
 * @return array
 */
function sql_fetch_all($sql, $error = true)
{
    global $g4;

    $result = sql_query($sql, $error);

    $rows = array();

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $rows[] = $row;
    }

    return $rows;
}

/**
 * 디비 마이그레이트 1회만 실행
 *  - 중복 실행 방지를 위해 디비에 기록 남김
 *
 * @param string $query
 * @param string|null $name
 * @param bool $is_debug
 *
 * @return bool
 */
function php79_db_migrate($query, $name = null, $is_debug = true)
{
    global $g4;

    if ( ! empty($name)) {
        $q = "select count(*) from {$g4['php79_migrations_table']} where name='" . addslashes($name) . "'";
    } else {
        $q = "select count(*) from {$g4['php79_migrations_table']} where query='" . addslashes($query) . "'";
    }
    $exist_count = sql_one($q);
    if ( ! $exist_count) {
        // 마이그레이트 실행
        if ($is_debug) {
            echo "<h5>" . $name . "</h5>";
            echo "<pre>" . $query . "</pre>";
        }
        if ( ! empty($query)) {
            sql_query($query);
        }

        // 기록
        $q = "insert into {$g4['php79_migrations_table']} (name, query, created_at) values ('" . addslashes($name)
             . "', '"
             . addslashes($query) . "', NOW()) ";
        sql_query($q);
//		if ( ! empty($name)) {
//			echo '[' . $name . '] ';
//		}
//		echo $query . '<br>';
//		flush();

        return true;
    } else {
        return false;
    }
}

/**
 * 디버깅 출력
 *
 * @param        $var
 * @param string $title
 */
function dd($var, $title = '')
{
    if ( ! empty($title)) {
        echo "<h1>dd: " . $title . "</h1>";
    }
    if (extension_loaded('xdebug')) {
        var_dump($var);
    } else {
        echo "<xmp>";
        var_dump($var);
        echo "</xmp>";
    }
    exit;
}

/**
 * aJax 요청 구분
 *
 * @return bool
 */
function is_ajax()
{
    return 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'];
}

/**
 * 모바일 기기 구분
 *
 * @return bool
 */
function is_mobile()
{
    return (boolean)preg_match('/iPhone|Android|Mobile Safari/', $_SERVER['HTTP_USER_AGENT']);
}

/**
 * 인앱 구분
 *   - 앱에서 모바일웹을 로딩한 경우
 *
 * @return bool
 */
function in_app()
{
    return ((isset($_GET['in-app']) && $_GET['in-app'])
            || (isset($_COOKIE['in-app']) && $_COOKIE['in-app']));
}

/**
 * 팝오버 페이지 여부
 *   - 쪽지?
 *
 * @return bool
 */
function is_popover()
{
    $popups = array(//'/bbs/memo.php',
    );

    return in_array($_SERVER['PHP_SELF'], $popups);
}

/**
 * 그누보드4 경로
 *   - 모바일 스킨을 분리한 경우, 다음처럼 함수로 둘러싸는 부분만 변경하여 PC/모바일 구분 처리
 *          include_once("$g4[memo_skin_path]/memo2.head.skin.php");
 *          include_once(g4_path($g4[memo_skin_path]) . "/memo2.head.skin.php");
 *
 * @param string|null $path
 *
 * @return string
 */
function g4_path($path = null)
{
    global $g4;

    if (is_mobile()) {
        if (strpos($path, '../') === 0) {
            return '../m/' . str_replace('../', '', $path);
        } else {
            return 'm/' . $path;
        }
    } else {
        return $path;
    }
}

/**
 * 앱 버전
 *   - 웹
 *
 * @return string
 */
function app_version()
{
    global $g4;

    if (!isset($g4['app_version'])) {
        $g4['app_version'] = file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))) . '/.app_version');
        if (isset($g4['app_version_suffix'])) {
            $g4['app_version'] .= $g4['app_version_suffix'];
        }
    }

    return $g4['app_version'];
}

/**
 * 안드로이드앱 버전
 *   - 앱(웹) 버전과 별도
 *
 * @return string
 */
function android_version()
{
    global $g4;

    if (!isset($g4['android_version'])) {
        $g4['android_version'] = file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))) . '/.android_version');
    }

    return $g4['android_version'];
}

/**
 * HTML 태그를 일반 문자열로 표시
 *   - xss 방어
 *   - UTF-8 만 가능하므로, 내부적으로 자동 변환 처리
 *
 * @param  string $value
 *
 * @return string
 */
function e($value)
{
    global $g4;

    if (strtoupper($g4['charset']) !== 'UTF-8') {
        $value = mb_convert_encoding($value, 'UTF-8', 'CP949');
    }
    $return = htmlentities($value, ENT_QUOTES, 'UTF-8', false);

    if (strtoupper($g4['charset']) !== 'UTF-8') {
        $return = mb_convert_encoding($return, 'CP949', 'UTF-8');
    }

    return $return;
}

/**
 * 문자열을 UTF-8로 변환하기
 *   - 그누보드 euc-kr, utf-8 버전 함께 지원
 *
 * @param string $value
 *
 * @return string
 */
function to_utf8($value)
{
    global $g4;

    if (strtoupper($g4['charset']) !== 'UTF-8') {
        $value = mb_convert_encoding($value, 'UTF-8', 'CP949');
    }

    return $value;
}

/**
 * 앱 타이틀
 *   - 상단 탑 메뉴에 표시
 *
 * @param $title
 *
 * @return string
 */
function app_title($title)
{
    global $g4, $board;

    // 게시판 이름 표시
    if (isset($board['bo_subject']) && ! empty($board['bo_subject'])) {
        $title = $board['bo_subject'];
    } else {
        switch ($_SERVER['PHP_SELF']) {
            case '/bbs/my_menu_edit.php';
                $title = '게시판 바로가기';
                break;
            case '/bbs/myon.php';
                $title = 'MyOn';
                break;
            case '/bbs/whatson.php';
                $title = 'Whats On';
                break;
        }
    }

    return mb_strimwidth($title, 0, 18, '...', $g4['charset']);
}

/**
 * JSON 응답
 *
 * @param mixed $data
 *
 * @return string
 */
function response_json($data)
{
    header('Content-Type: application/json');

    return json_encode($data);
}

/**
 * 에러 응답
 *   - json 요청인지, 일반 요청인지에 따라 에러 응답 다르게 처리.
 *
 * @param string $error
 * @param string|null $redirect
 */
function response_error($error, $redirect = null)
{
    if (is_ajax()) {
        echo response_json(array(
            'success'  => false,
            'error'    => $error,
            'redirect' => $redirect,
        ));
        exit;
    } else {
        alert($error, $redirect);
    }
}

/**
 * text/html 응답
 *
 * @param string $data
 *
 * @return string
 */
function response($data)
{
    global $g4;
    header("Content-Type: text/html; charset=" . $g4['charset']);

    return $data;
}

/**
 * URL 리다이렉트
 *   - 프로그램 실행 중단후, 이동
 *
 * @param string $url
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * 현재 날짜 및 시간
 *
 * @return string
 */
function date_full()
{
    return date('Y-m-d H:i:s');
}

/**
 * 현재 날짜
 * @return string
 */
function date_short()
{
    return date('Y-m-d');
}

/**
 * 로그인시 이벤트 실행
 *
 * @param array $member
 */
function on_member_login($member)
{
    // 기기 정보가 쿠키에 존재할 경우, 로그인 사용자의 기기로 지정
    if ( ! empty($_COOKIE['device-uuid']) && ! empty($_COOKIE['device-serial'])) {
        require_once(G4_PHP79_PATH . "/lib/devices.php");

        device_set_user(array(
            'uuid'   => $_COOKIE['device-uuid'],
            'serial' => $_COOKIE['device-serial'],
        ), $member);
    }
}