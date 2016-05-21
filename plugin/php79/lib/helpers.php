<?php
/**
 * Copyright 2016 Been Kyung-yoon.
 */

/**
 * PDO ����
 *   - ���ε��� �迭�� �޾� ���� ó����
 *   - ��� ������ ������ ���� �α׸� ����ϰ�, �������� ��� ȭ�鿡�� ���
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
            dd("��� ������ �߻��Ͽ����ϴ�.  (�����ڿ��Ը� ǥ�õ˴ϴ�.)" . PHP_EOL . $error);
        } else {
            response_error("��� ������ �߻��Ͽ����ϴ�.");
        }
    }
}

/**
 * PDO ����Ÿ ����
 *   - �뷮 �Ҵ� ����: Ű->��� �迭�� ���� �����ϸ� insert �������� �ڵ� �����Ǿ� �����
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
 * PDO ���������� �߰��� ID
 *
 * @return string
 */
function pdo_last_insert_id()
{
    global $pdo_db;

    return $pdo_db->lastInsertId();
}

/**
 * ��� ���� ������� 1�� �÷��� ��������
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
 * ���� ������� ��� �� ��������
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
 * ��� ���̱׷���Ʈ 1ȸ�� ����
 *  - �ߺ� ���� ������ ���� ��� ��� ����
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
        // ���̱׷���Ʈ ����
        if ($is_debug) {
            echo "<h5>" . $name . "</h5>";
            echo "<pre>" . $query . "</pre>";
        }
        if ( ! empty($query)) {
            sql_query($query);
        }

        // ���
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
 * ����� ���
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
 * aJax ��û ����
 *
 * @return bool
 */
function is_ajax()
{
    return 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'];
}

/**
 * ����� ��� ����
 *
 * @return bool
 */
function is_mobile()
{
    return (boolean)preg_match('/iPhone|Android|Mobile Safari/', $_SERVER['HTTP_USER_AGENT']);
}

/**
 * �ξ� ����
 *   - �ۿ��� ��������� �ε��� ���
 *
 * @return bool
 */
function in_app()
{
    return ((isset($_GET['in-app']) && $_GET['in-app'])
            || (isset($_COOKIE['in-app']) && $_COOKIE['in-app']));
}

/**
 * �˿��� ������ ����
 *   - ����?
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
 * �״�����4 ���
 *   - ����� ��Ų�� �и��� ���, ����ó�� �Լ��� �ѷ��δ� �κи� �����Ͽ� PC/����� ���� ó��
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
 * �� ����
 *   - ��
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
 * �ȵ���̵�� ����
 *   - ��(��) ������ ����
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
 * HTML �±׸� �Ϲ� ���ڿ��� ǥ��
 *   - xss ���
 *   - UTF-8 �� �����ϹǷ�, ���������� �ڵ� ��ȯ ó��
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
 * ���ڿ��� UTF-8�� ��ȯ�ϱ�
 *   - �״����� euc-kr, utf-8 ���� �Բ� ����
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
 * �� Ÿ��Ʋ
 *   - ��� ž �޴��� ǥ��
 *
 * @param $title
 *
 * @return string
 */
function app_title($title)
{
    global $g4, $board;

    // �Խ��� �̸� ǥ��
    if (isset($board['bo_subject']) && ! empty($board['bo_subject'])) {
        $title = $board['bo_subject'];
    } else {
        switch ($_SERVER['PHP_SELF']) {
            case '/bbs/my_menu_edit.php';
                $title = '�Խ��� �ٷΰ���';
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
 * JSON ����
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
 * ���� ����
 *   - json ��û����, �Ϲ� ��û������ ���� ���� ���� �ٸ��� ó��.
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
 * text/html ����
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
 * URL �����̷�Ʈ
 *   - ���α׷� ���� �ߴ���, �̵�
 *
 * @param string $url
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * ���� ��¥ �� �ð�
 *
 * @return string
 */
function date_full()
{
    return date('Y-m-d H:i:s');
}

/**
 * ���� ��¥
 * @return string
 */
function date_short()
{
    return date('Y-m-d');
}

/**
 * �α��ν� �̺�Ʈ ����
 *
 * @param array $member
 */
function on_member_login($member)
{
    // ��� ������ ��Ű�� ������ ���, �α��� ������� ���� ����
    if ( ! empty($_COOKIE['device-uuid']) && ! empty($_COOKIE['device-serial'])) {
        require_once(G4_PHP79_PATH . "/lib/devices.php");

        device_set_user(array(
            'uuid'   => $_COOKIE['device-uuid'],
            'serial' => $_COOKIE['device-serial'],
        ), $member);
    }
}