<?
// ���α׷� : �״����� �Ҵ��� ���̺귯�� 
// �� �� �� : �ƺ��Ҵ� (echo4me@gmail.com)
//
// �� �� �� : GPL2

// ������ ��� �ð� ������ �̻ڰ� ǥ���� (�̰� �����ϸ� common.lib.php�� get_list�� ��¥ ǥ���� �����ؾ� ��)
function get_datetime($datetime)
{
    global $g4;
    
    // �����̸� �ð����� ǥ��
    if (substr($datetime,0,10) == $g4['time_ymd'])
        return substr($datetime,11,5);
    // ���� �⵵�̸� ��-�Ϸ�ǥ��
    else if (substr($datetime,0,4) == substr($g4['time_ymd'],0,4))
        return substr($datetime,5,5);
    // ������ �ٸ����� 60�� �̳��̸�, �򰥸��� �ʰ� ��-�Ϸ� ǥ��
    else if (days_diff($datetime) <= 60)
        return substr($datetime,5,5);
    // ������ ��/���� ǥ��
    else
        return substr($datetime,0,7);
}

// ��¥�� ǥ����
function get_date($datetime)
{
    global $g4;
    
    return substr($datetime,5,5);
}

// mysql ����� ������ ����� ���μ��� return (update�� ��� matched�� ������ �װ��� return)
// http://kr.php.net/manual/kr/function.mysql-info.php
function mysql_modified_rows() {
    $info_str = mysql_info();
    $a_rows = mysql_affected_rows();
    preg_match("/Rows matched: ([0-9]*)/i", $info_str, $r_matched);
    return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows;
}

// �Ҵ��� : �������� �о����
function get_config($config_type='', $fields='*') 
{ 
    global $g4, $config, $board; 

    $config_type = trim($config_type);
    if ($config_type) {
        $config_extend = sql_fetch(" select $fields from $g4[config_table]_{$config_type} ");
        if ($config_extend)
            $config = array_merge($config, $config_extend);
    } else {
        $config = sql_fetch(" select $fields from $g4[config_table] ");
    }

    return $config;
}


// �Ҵ��� : �̹��� resize (û��Ҿ��� : http://www.sir.co.kr/bbs/board.php?bo_table=cm_free&wr_id=306629)
function resize($string)
{ 
    global $g4, $board;

    //print_r($board);

    // ���������� �޾Ƶ��̱� ������ �������� ������, �⺻���� �Խ����� ���� ����. �Խ��� ���� ������ �⺻���� 500
    $max_img_width = (int) $board['resize_img_width'];
    if ($max_img_width <= 0) {
        if ((int)$board['bo_image_width'] > 0)
            $max_img_width = $board['bo_image_width'];
        else
            $max_img_width = 500;
    }
    
    // max_img_height�� ���� �ִ� ���� crop�� ��� �մϴ�.
    $max_img_height = (int) $board['resize_img_height'];
    $is_crop = false;
    if ($max_img_height > 0)
        $is_crop = true;

    // ������ ������ image�� create���� ���� (������ false)
    $is_create = false;
    
    // �̹����� quality ���� ���� (������, thumb�� �⺻������ 90�� �����)
    $quality = (int) $board['resize_img_quality'];
    if ($quality <= 0)
        $quality = 90;

    // $water_mark ������ ���� �޽��ϴ�
    $water_mark = $board['water_mark'];

    // $board[thumb_create]�� ���� ������ ������ ������� ���� �մϴ�.
    if ($board[thumb_create])
        $thumb_create = 1;

    // �̹��� ���� - �⺻���� UnSharpMask
    if ($board[image_filter]) {
        $filter[type] = $board[image_filter][type];
        $filter[arg1] = $board[image_filter][arg1];
        $filter[arg2] = $board[image_filter][arg2];
        $filter[arg3] = $board[image_filter][arg3];
        $filter[arg4] = $board[image_filter][arg4];
    } else {
        $filter[type] = 99;
        $filter[arg1] = 10;
        $filter[arg2] = 1;
        $filter[arg3] = 2;
    }

    // ������ setting
    $return = $string['0']; 
    preg_match_all('@(?P<attribute>[^\s\'\"]+)\s*=\s*(\'|\")?(?P<value>[^\s\'\"]+)(\'|\")?@i', $return, $match);
    if (function_exists('array_combine')) {
        $img = array_change_key_case(array_combine($match['attribute'], $match['value']));
    }
    else {
        $img = array_change_key_case(array_combine4($match['attribute'], $match['value']));
    }

    // ���� ���丮 �̸��� ���ϰ� �����ο��� �߶� ���ڼ��� ���
    $real_dir = dirname($_SERVER['DOCUMENT_ROOT'] . "/nothing");
    $cut_len = strlen($real_dir);

    // ������ img�� �����̸��� �������� ��찡 �־ decoding ���ݴϴ� (��: &#111;&#110; = on)
    $img['src'] = html_entity_decode($img[src]); 

    // �̹��� ������ ��θ� ���� (�ܺ�? ����? �����ΰ�� ������? �����?)
    if (preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $img['src'])) {
        // �� ������ �ִ� �̹���?
        if (preg_match("/" . $_SERVER[HTTP_HOST] . "/", $img[src], $matches)) {
            $url = parse_url($img[src]);
            $img[src] = $url[path];
            $thumb_path = "1";
        } else {
            return $return;
        }
    } else {
        $thumb_path="1";
    }

    if ($thumb_path) {
        $dir = dirname(file_path($img['src']));
        $file = basename($img['src']);
        $img_path = $dir . "/" . $file;
        // ÷�������� �̸��� urlencode�� ���� �˴ϴ�. ����, decode����� �մϴ�. (/bbs/write_update.php ����)
        $img_path = urldecode($img_path);
        $img_src = @getimagesize($img_path);
        // �ؾ���������� ���⵵ urldecode �������?
        $thumb_path = urldecode($img['src']);
    }

    // �̹��������� ������ ���� ������ ��
    if (!$img_src) {
        return $return;
    }

    // �̹��������� �ּ� ���̰� ������, �̹����� �� ũ�� �̻��϶���, ���� ������¡.
    // �̰Ŵ� ���� ������ ���� ���� ���� ������ �ʰ� �Ϸ��� �ϴ°���
    if ($board[image_min] && $img_src[0] < $board[image_min])
        return $return;

    // �̹��� ������ ũ�⸦ ���ؼ�
    $fsize = filesize2bytes(filesize($img_path));

    // �̹��� ������ ��ü ũ��� ���� ����
    if ($board['bo_image_info']) {
        $g4['resize']['image_size'] = $g4['resize']['image_size'] + $fsize/1000;
        $g4['resize']['image_count'] = $g4['resize']['image_count'] + 1;
        $g4['resize']['image_file'][] = $img_path;
    }

    // �̹��������� �ּ� ���Ͽ뷮�� ������, �̹����� �� ����ũ�� �̻��϶���, ���� ������¡.
    // �̰Ŵ� ���� ������ ���� ���̳� ȿ�������� �پ�� �̹����� ���� ������ �ʰ� �Ϸ��� �ϴ°���
    if ($board[image_min_kb]) {
        // �뷮�� kb���� byte�� �ٲ㼭
        $min_kb = $board[image_min_kb]*1024;
        if ($fsize < $min_kb) {
            return $return;
        }
    }

    if(isset($img['width']) == false) {
        $img_width = $img_src[0];
        $img_height = $img_src[1];
    } else {
        $img_width = $img['width'];
        $img_height = $img['height'];
    }

    if((int)$img_width > $max_img_width) 
    {
        // width�� ����
        if (isset($img['width']) == true)
            $return = preg_replace('/width\=(\'|\")?[^\s\'\"]+(\'|\")?/i', 'width="' . $max_img_width . '"', $return); 
        else
            $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 width='" . $max_img_width . "' \\2 \\3", $return);

        // height�� ����
        $return = preg_replace('/height\=(\'|\")?[^\s\'\"]+(\'|\")?/i', null, $return); 

        // �̸��� �״��� javascript resize�� �� �ְ� ����
        if (isset($img[name]) == true)
            $return = preg_replace('/name\=(\'|\")?[^\s\'\"]+(\'|\")?/i', ' name="target_resize_image[]" ', $return);
        else
            $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' \\2 \\3", $return);

        // thumbnail�� ����
        if ($thumb_path) {
            include_once("$g4[path]/lib/thumb.lib.php");
            $thumb_path=thumbnail($thumb_path, $max_img_width,$max_img_height,$is_create,$is_crop,$quality, "", $water_mark, $filter);
            $return = preg_replace('/src\=(\'|\")?[^\s\'\"]+(\'|\")?/i', 'src="' . $thumb_path . '"', $return); 
        }

        // onclick�� ���� ��, ������ �̹��� ũ��� popup�� �ǵ��� ����
        if ($board[image_window]) {
            if (isset($img[onclick]) == true)
                $return = preg_replace('/onclick\=(\'|\")?[^\s\'\"]+(\'|\")?/i', 'onClick="image_window3(\'' . $img['src'] . '\',' . (int)$img_width . ',' . (int)$img_height . ')" ', $return);
            else
                $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 onClick='image_window3(\"" . $img['src'] . "\"," . (int)$img_width . "," . (int)$img_height . ")' \\2 \\3", $return);
        } else {
            if (isset($img[onclick]) == true)
                $return = preg_replace('/onclick\=(\'|\")?[^\s\'\"]+(\'|\")?/i', '', $return);
            else
                $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 onclick='' \\2 \\3", $return);
        }
    }
    else
    { 
        // width�� ����
        if (isset($img['width']) == true)
            $return = preg_replace('/width\=(\'|\")?[^\s\'\"]+(\'|\")?/i', 'width="' . $img_width . '"', $return); 
        else
            $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 width='" . $img_width . "' \\2 \\3", $return);

        // height�� ����
        $return = preg_replace('/height\=(\'|\")?[^\s\'\"]+(\'|\")?/i', null, $return); 

        // �̸��� �״��� javascript resize�� �� �ְ� ����
        if (isset($img[name]) == true)
            $return = preg_replace('/name\=(\'|\")?[^\s\'\"]+(\'|\")?/i', ' name="target_resize_image[]" ', $return);
        else
            $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' \\2 \\3", $return);

        // $thumb_create�� true�̸�, �̹��� ũ�Ⱑ $max_img_width���� ������, �׷��� thumb�� ����

        if ($thumb_create && $thumb_path) {
            include_once("$g4[path]/lib/thumb.lib.php");
            $thumb_path=thumbnail($thumb_path, $max_img_width,$max_img_height,$is_create,$is_crop,$quality, "", $water_mark, $filter);
            $return = preg_replace('/src\=(\'|\")?[^\s\'\"]+(\'|\")?/i', 'src="' . $thumb_path . '"', $return); 
        }

        // onclick�� ���� ��, ������ �̹��� ũ��� popup�� �ǵ��� ����
        if ($board[image_window]) {
            if (isset($img[onclick]) == true)
                $return = preg_replace('/onClick\=(\'|\")?[^\s\'\"]+(\'|\")?/i', 'onClick="image_window3(\'' . $img['src'] . '\',' . (int)$img_width . ',' . (int)$img_height . ')" ', $return);
            else
                $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 onClick='image_window3(\"" . $img['src'] . "\"," . (int)$img_width . "," . (int)$img_height . ")' \\2 \\3", $return);
        } else {
            if (isset($img[onclick]) == true)
                $return = preg_replace('/onClick\=(\'|\")?[^\s\'\"]+(\'|\")?/i', '', $return);
            else
                $return = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 onClick='' \\2 \\3", $return);
        }
    }

    return $return; 
}

// $content                                   : resize�� img �±װ� �ִ� html
// $width         = $board[resize_img_width]  : �ִ� �̹����� �� (���� ������ $board[bo_img_width] ���� ���ϴ�
// $height        = $board[resize_img_height] : �ִ� �̹����� ���� (�̰��� �����Ǹ� $is_crop = true�� �˴ϴ�) ���� ������, ������� ���̰� crop ���� �ʽ��ϴ�.
// $quality       = $board[resize_img_quality]: ����� �̹����� quality (������ �⺻��, 70%�� ���)
// $thumb_create  = $board[thumb_create]      : �̹����� ���� �������� ������쿡�� ������� ���������� ����
// $image_window  = $board[image_window]      : �̹����� ������ �˾�â�� ��� �������� ���� (1: �˾�)
// $water_mark    = $board[water_mark]        : ���͸�ũ
// $image_filter  = $board[image_filter]      : �̹�������
// $image_min     = $board[image_min]         : ���� ������, $thumb_create=1�̴��� image_min �̻��� ���� �̹����� ���ؼ���, ���� �����
// $image_min_kb  = $board[image_min_kb]      : ���� ������, $thumb_create=1�̴��� image_kb �̻��� �̹��� �뷮�� ���ؼ���, ���� �����
function resize_content($content, $width=0, $height=0, $quality=0, $thumb_create=0, $image_window=1, $water_mark='', $image_filter='', $image_min=0, $image_min_kb=0)
{
    global $board;

    if ($width > 0)
        $board['resize_img_width'] = (int)$width;
    else
        $board['resize_img_width'] = 0;

    if ($height > 0)
        $board['resize_img_height'] = (int)$height;
    else
        $board['resize_img_height'] = 0;

    if ($quality > 0)
        $board['resize_img_quality'] = (int)$quality;
    else
        $board['resize_img_quality'] = 70;

    if ($thumb_create)
        $board['thumb_create'] = 1;
    else
        $board['thumb_create'] = 0;

    if ($image_window)
        $board['image_window'] = 1;
    else
        $board['image_window'] = 0;

    if ($image_min)
        $board['image_min'] = $image_min;

    if ($image_min_kb)
        $board['image_min_kb'] = $image_min_kb;

    if ($water_mark)
        $board['water_mark'] = $water_mark;

    if ($image_filter)
        $board['image_filter'] = $image_filter;
    
    return preg_replace_callback('/\<img[^\<\>]*\>/i', 'resize', $content);
}

// $content                                   : resize�� img �±װ� �ִ� html
// $image_min     = $board[image_min]         : ���� ������, $thumb_create=1�̴��� image_min �̻��� ���� �̹����� ���ؼ���, ���� �����
// $image_min_kb  = $board[image_min_kb]      : ���� ������, $thumb_create=1�̴��� image_kb �̻��� �̹��� �뷮�� ���ؼ���, ���� �����
// $quality       = $board[resize_img_quality]: ����� �̹����� quality (������ �⺻��, 70%�� ���)
// $image_window  = $board[image_window]      : �̹����� ������ �˾�â�� ��� �������� ���� (1: �˾�)
function resize_dica($content, $image_min=0, $image_min_kb=0, $quality=90, $image_window=1)
{
    global $board;

    $board['image_min'] = (int)$image_min;

    $board['image_min_kb'] = (int)$image_min_kb;

    $board['resize_img_quality'] = (int)$quality;

    $board[image_window] = $image_window;

    return preg_replace_callback('/\<img[^\<\>]*\>/i', 'resize', $content);
}

// php4�� ���� array_combine �Լ�����, http://kr2.php.net/manual/kr/function.array-combine.php
function array_combine4($arr1, $arr2) {
    $out = array();
    
    $arr1 = array_values($arr1);
    $arr2 = array_values($arr2);
    
    foreach($arr1 as $key1 => $value1) {
        $out[(string)$value1] = $arr2[$key1];
    }
    
    return $out;
}

// ȸ������ �̹��� �����ֱ�
function role_img () {
    global $bo_table, $board, $member, $board_skin_path;
    
    if (!$bo_table)
        return;

    $role_img = "";
    
    if ($member['mb_level'] >= $board['bo_read_level'])
        $role_img .= "<img src='$board_skin_path/img/read_ok.gif' align=absmiddle title='read ok'>";
    else
        $role_img .= "<img src='$board_skin_path/img/read_no.gif' align=absmiddle title='read no'>";

    if ($member['mb_level'] >= $board['bo_write_level'])
        $role_img .= "<img src='$board_skin_path/img/write_ok.gif' align=absmiddle title='write ok'>";
    else
        $role_img .= "<img src='$board_skin_path/img/write_no.gif' align=absmiddle title='write no'>";

    if ($member['mb_level'] >= $board['bo_reply_level'])
        $role_img .= "<img src='$board_skin_path/img/reply_ok.gif' align=absmiddle title='reply ok'>";
    else
        $role_img .= "<img src='$board_skin_path/img/reply_no.gif' align=absmiddle title='reply no'>";

    if ($member['mb_level'] >= $board['bo_comment_level'])
        $role_img .= "<img src='$board_skin_path/img/comment_ok.gif' align=absmiddle title='comment ok'>";
    else
        $role_img .= "<img src='$board_skin_path/img/comment_no.gif' align=absmiddle title='comment no'>";

    $role_img .= "<img src='$board_skin_path/img/point_info.gif' align=absmiddle title='read:{$board[bo_read_point]}, write:{$board[bo_write_point]}, comment:{$board[bo_comment_point]}', download:{$board[bo_download_point]}>";
    return $role_img;
}


// http://kr2.php.net/manual/kr/function.realpath.php
// ������丮���� get_absolute_path�� ȣ���� ��, �ش� ������丮������ ��δ� �����˴ϴ�
function get_absolute_path($path) {
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    return implode(DIRECTORY_SEPARATOR, $absolutes);
}


// http://kr2.php.net/manual/kr/function.realpath.php + http://kr2.php.net/dirname
function get_absolute_path_my($path) {
    // ���� path �����ʹ� ����
    $path_org = $path;
    
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    if (substr($path_org,0,1) == "/") {
        return implode(DIRECTORY_SEPARATOR, $absolutes);
    } else {
        $my = my_dir();
        if ($my)
            if (substr($path_org,0,2) == "../")
                return $my . "/" . implode(DIRECTORY_SEPARATOR, $absolutes);
            else
                return implode(DIRECTORY_SEPARATOR, $absolutes);
        else
            return implode(DIRECTORY_SEPARATOR, $absolutes);
    }
}


// http://kr2.php.net/dirname
function my_dir(){
    return end(explode('/', dirname(!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : str_replace('\\','/',__FILE__))));
}


// ������ ��θ� ������ �ɴϴ� (�Ҵ���, /lib/common.lib.php�� ���ǵ� �Լ�)
if(!function_exists('file_path')){
function file_path($path) {

    $dir = dirname($path);
    $file = basename($path);
    
    if (substr($dir,0,1) == "/") {
        $real_dir = dirname($_SERVER['DOCUMENT_ROOT'] . "/nothing");
        $dir = $real_dir . $dir;
    }
    
    return $dir . "/" . $file;
}
}


// ���� �����ϴ� ����Ʈ�� ��쿡�� url�� �������ݴϴ�
// http://scriptplayground.com/tutorials/php/Simple-Way-to-Validate-Links/
function set_http2($url, $mb_id="") {

    global $g4, $member;

    if (!$url)
        return "";
    else
        $url = set_http($url);

    $u2 = @parse_url($url);
    if (!$u2)
        return "";
    $uhost = $u2['host'];

    $fp = @fsockopen($uhost, 80, $errno, $errstr, 1);

    if ($fp) {
        // ȸ�� id�� �ִ� ��� ȸ�������� ���� �ʵ���� ���� �Ѵ�
        if ($mb_id) {
            $sql = " update $g4[member_table] set mb_homepage_certify='$g4[time_ymdhis]' where mb_id = '$mb_id' ";
            sql_query($sql);
        }
    }
    else 
    {
        // ȸ�� id�� �ִ� ��� ȸ�������� ���� �ʵ���� clear �Ѵ�
        if ($mb_id) {
            $sql = " update $g4[member_table] set mb_homepage='', mb_homepage_certify='0000-00-00 00:00:00' where mb_id = '$mb_id' ";
            sql_query($sql);
        }
        $url = "";
    }
    
    return $url;
}


// ���丮�� �뷮 (KB)
// http://kr.php.net/manual/kr/function.filesize.php   
function get_dir_size($path)                           
{                       
    $result=explode("\t", @exec("du -k -s ".$path),2);
    return ($result[1]==$path ? $result[0] : "error"); 
}


// ip�� Ư���κ��� ��������ϴ�
function str_rev_ip($str, $pos=2, $mask='��') 
{ 
    global $is_admin;

    $ar=explode(".",$str); 
    $ar[4 - $pos] = $mask;
    return "$ar[3].$ar[2].$ar[1].$ar[0]"; 
}


// memo4_send - �Ҵ�ǥ ����4 ������
function memo4_send($me_recv_mb_id, $me_send_mb_id, $me_memo, $me_subject, $me_option="", $mb_memo_call="1", $file_name0="", $file_name3="") 
{ 
        global $g4, $config;
        
        // ���� INSERT (������)
        $sql = " insert into $g4[memo_recv_table]
                        ( me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_subject, memo_type, memo_owner, me_file_local, me_file_server, me_option )
                 values ('$me_recv_mb_id', '$me_send_mb_id', '$g4[time_ymdhis]', '$me_memo', '$me_subject', 'recv', '$me_recv_mb_id', '', '', '$me_option' ) ";
        sql_query($sql);
        $me_id = mysql_insert_id();

        // ���� INSERT (�߽��� - me_id�� �߽����� me_id�� �����ϰ� ����)
        $sql = " insert into $g4[memo_send_table]
                        ( me_id,  me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_subject, memo_type, memo_owner, me_file_local, me_file_server, me_option )
                 values ( $me_id,  '$me_recv_mb_id', '$me_send_mb_id', '$g4[time_ymdhis]', '$me_memo', '$me_subject', 'send', '$me_send_mb_id', '', '', '$me_option' ) ";
        $result = @sql_query($sql);

        // �ߺ� ������������? - index�� +10 �����ش�. �������� ���.
        if ( !$result ) {
            $sql = " update $g4[memo_recv_table] set me_id = me_id + 10 where me_id = '$me_id' ";
            sql_query($sql);

            // ���� INSERT (�߽��� - me_id�� �߽����� me_id�� �����ϰ� ����)
            $sql = " insert into $g4[memo_send_table]
                            ( me_id,  me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_subject, memo_type, memo_owner, me_file_local, me_file_server, me_option )
                     values ( $me_id,  '$me_recv_mb_id', '$me_send_mb_id', '$g4[time_ymdhis]', '$me_memo', '$me_subject', 'send', '$me_send_mb_id', '', '', '$me_option' ) ";
            $result = @sql_query($sql);
        }

        // ÷������ ���� ������Ʈ
        $sql = " update $g4[memo_recv_table]
                      set me_file_local = '$file_name0', me_file_server = '$file_name3' 
                      where me_id = $me_id ";
        sql_query($sql);
        // ÷������ ���� ������Ʈ
        $sql = " update $g4[memo_send_table]
                      set me_file_local = '$file_name0', me_file_server = '$file_name3' 
                      where me_id = $me_id ";
        sql_query($sql);

        // ������ ���� ����, ���� ���� ��¥�� ������Ʈ
        $sql = " update $g4[member_table]
                    set mb_memo_unread=mb_memo_unread+1, mb_memo_call_datetime='$g4[time_ymdhis]' 
                  where mb_id = '$me_recv_mb_id' ";
        sql_query($sql);

        // ���� ���� �˸� ���
        if ($mb_memo_call)
        {
            $sql = " update $g4[member_table]
                        set mb_memo_call = concat(mb_memo_call, concat(' ', '$me_send_mb_id'))
                      where mb_id = '$me_recv_mb_id' ";
            sql_query($sql);
        }

        require_once("$g4[path]/lib/whatson.lib.php");
        $sender_nick = pdo(
            "select mb_nick from {$g4['member_table']} where mb_id=?",
            array($me_send_mb_id)
        )->fetchColumn(0);
        whatson_send_memo($me_recv_mb_id, $me_subject, 'recv', $me_id, $sender_nick);

        // �ڵ����� ���
        $mb = get_member($me_recv_mb_id, "mb_nick, mb_memo_no_reply, mb_memo_no_reply_text");
        if ($config[cf_memo_no_reply] && $mb[mb_memo_no_reply]) {
            $me_subject = "$mb[mb_nick]���� [�ڵ�����] �޽��� �Դϴ�.";
            $me_memo = "��а� ������ ������ �� �����ϴ�. Ȯ���� �����帮�ڽ��ϴ�.<BR><BR>$mb[mb_memo_no_reply_text]";

            // ���� INSERT (������)
            $sql = " insert into $g4[memo_recv_table]
                            ( me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_subject, memo_type, memo_owner, me_file_local, me_file_server, me_option )
                     values ('$me_recv_mb_id', '$me_send_mb_id', '$g4[time_ymdhis]', '$me_memo', '$me_subject', 'recv', '$me_recv_mb_id', '', '', '$me_option' ) ";
            sql_query($sql);
            $me_id = mysql_insert_id();
                
            // ���� INSERT (�߽��� - me_id�� �߽����� me_id�� �����ϰ� ����)
            $sql = " insert into $g4[memo_send_table]
                            ( me_id,  me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_subject, memo_type, memo_owner, me_file_local, me_file_server, me_option )
                     values ( $me_id,  '$me_recv_mb_id', '$me_send_mb_id', '$g4[time_ymdhis]', '$me_memo', '$me_subject', 'send', '$me_send_mb_id', '', '', '$me_option' ) ";
            sql_query($sql);
              
        }
}

// ����5 - cancel / delete
function memo4_cancel($me_id) {

    global $g4, $member;

    // $g4['memo_delete'] == 1, ������ �����.
    $memo_delete = "";
    if ($g4['memo_delete']) {
        $memo_delete = " and memo_owner='$member[mb_id]' ";
    }

    // �߽� ���̺���, ������ ���� �ɴϴ�.
    $sql = " select * from $g4[memo_send_table] where me_id = '$me_id' ";
    $result = sql_fetch($sql);

    // �߽��ڿ� �����Ϸ��� ����� �ٸ��� �ȵǰ���?
    if ($result[me_send_mb_id] !== $member[mb_id]) 
        alert("�ٸ��� ���� ����Դϴ�");

    // trash�� ������ �־�α� - �׳� ���������� ���ؼ�.
    //$sql = "insert into $g4[memo_trash_table] select *, 'send' from $g4[memo_send_table] where me_id = '$me_id' and me_send_mb_id='$member[mb_id]' $memo_delete ";
    $me = sql_fetch("select * from $g4[memo_send_table] where me_id = '$me_id' and me_send_mb_id='$member[mb_id]' $memo_delete ");
    $sql = " insert into $g4[memo_trash_table]
                set me_id = '$me[me_id]',
                    me_recv_mb_id = '$me[me_recv_mb_id]',
                    me_send_mb_id = '$me[me_send_mb_id]',
                    me_send_datetime = '$me[me_send_datetime]',
                    me_read_datetime = '$me[me_read_datetime]',
                    me_memo = '$me[me_memo]',
                    me_file_local = '$me[me_file_local]',
                    me_file_server = '$me[me_file_server]',
                    me_subject = '$me[me_subject]',
                    memo_type = '$me[memo_type]',
                    memo_owner = '$member[mb_id]',
                    me_option = '$me[me_option]',
                    me_from_kind = 'send' ";
    sql_query($sql, FALSE);

    // �߽����� ���� �մϴ�.
    $sql = " delete from $g4[memo_send_table] where me_id = '$me_id' and me_send_mb_id = '$member[mb_id]' $memo_delete ";
    sql_query($sql);

    // �������� ������ ���� ���� ��쿡�� �����ع����ϴ� - ȸ���� ���� �Դϴ�. �� Ȳ���� ������.
    if ($result[me_read_datetime] == '0000-00-00 00:00:00') {
        $sql = " delete from $g4[memo_recv_table] where me_id = '$me_id' and me_send_mb_id = '$member[mb_id]' ";
        sql_query($sql);

        // ���� �������� �����Կ��� ���� ���� ���� ������ ������Ʈ
        sql_query(" update $g4[member_table] set mb_memo_unread = mb_memo_unread - 1 where mb_id = '$result[me_recv_mb_id]' ");

        // ���� �˸����� �ϳ��� ����
        $mb = get_member($result[me_recv_mb_id], "mb_memo_call"); 

        $memo_call = preg_replace("/$member[mb_nick]/" , '', $mb[mb_memo_call], 1);
        sql_query(" update $g4[member_table] set mb_memo_call = '$memo_call' where mb_id = '$result[me_recv_mb_id]' ");
    }
}


// �̸��� ��ȣȭ - http://davidwalsh.name/php-email-encode-prevent-spam
function encode_email($e)
{
    for ($i = 0; $i < strlen($e); $i++) { $output .= '&#'.ord($e[$i]).';'; }
  	return $output;
}


// ��¥��
function days_diff($date2)
{
    global $g4;

    if ($date2 == "")
        return;

    $_date1 = explode("-", $g4['time_ymd']);
    $_date2 = explode("-",$date2);

    $tm1 = mktime(0,0,0,$_date1[1],$_date1[2],$_date1[0]); 
    $tm2 = mktime(0,0,0,$_date2[1],$_date2[2],$_date2[0]); 

    return (int) ($tm1 - $tm2) / 86400; 
}


// �ð���
function hours_diff($date2)
{
    global $g4;

    // ����ð�
    $_date1 = $g4[server_time];

    // ���� �ð�
    $_date2 = strtotime($date2);

    return (int) ($_date1 - $_date2) / 3600; 
}


// ip ��ȣȭ - http://sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=20523
function encode_ip($ip)
{
    return crc32($ip);
}


// �Խ��� ���� ���̺��� �ϳ��� ���� ����
function get_board($bo_table, $fields='*')
{
    global $g4;

    return sql_fetch(" select $fields from $g4[board_table] where bo_table = '$bo_table' ");
}

// �Խ��� �Խñ� �ϳ��� ����
function get_write2($bo_table, $wr_id, $fields='*')
{
    global $g4;

    $tmp_write_table = $g4[write_prefix] . $bo_table;

    return sql_fetch(" select $fields from $tmp_write_table where wr_id = '$wr_id' ");
}


// email �ּ� �Ϻ� ��ȣȭ (�����ƺ���)
function encode_mail_form($email, $encode_count=2, $fields='*')
{
    $mail=explode("@",$email); 
    $email=substr($mail[0],0,$encode_count).str_repeat($fields,strlen($mail[0]))."@".$mail[1]; 

    return $email;
}


// ������ tag�� ����
// http://kr.php.net/manual/kr/function.strip-tags.php
function strip_only($str, $tags) {
    if(!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if(end($tags) == '') array_pop($tags);
    }
    foreach($tags as $tag) $str = preg_replace('#</?'.$tag.'[^>]*>#is', '', $str);
    return $str;
}


// �ֽű� �Լ��� ã�Ƽ� �� ���� replace (�ֽű��� ���� ���ø� ���μ���)
function latest_replace($matches) {
    global $g4;

    $latest_match = $matches[1];
    list($skin, $bo_table, $rows, $subj_len, $gallery_view, $options)=explode(",",$latest_match); 

    //function latest($skin_dir="", $bo_table, $rows=10, $subject_len=40, $gallery_view=0, $options="")
    $latest_data = latest($skin, $bo_table, $rows, $subj_length, $gallery_view, $options);

    return $latest_data;
}


// ������ ������ - ���������� ������ ���������� ���, ������ �Խñ��� random�ϰ� ���
function one_line_notice($bo_table, $title_len= "60", $class="") {
    global $g4;

    $tmp_board = sql_fetch(" select bo_notice from {$g4['board_table']} where bo_table = '$bo_table' ");
    $notice_list = trim($tmp_board[bo_notice]);
    if ($notice_list) {
        $notice_array = explode("\n", $notice_list);
        $notice_id = array_rand($notice_array);
        $tmp_wr_id = $notice_array[$notice_id];
        $sql = " select wr_id, wr_subject, wr_datetime from {$g4[write_prefix]}{$bo_table} where wr_id = '$tmp_wr_id' ";
    } else {
        $sql = " select wr_id, wr_subject, wr_datetime from {$g4[write_prefix]}{$bo_table} where wr_is_comment = '0' order by rand() limit 1 ";
    }
    $notice = sql_fetch($sql);
    if ($notice[wr_id]) {
        $result = $notice;
        if ($class)
            $class = " class='$class' ";
        $result['link'] = "<a href='$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$notice[wr_id]' $class>" . conv_subject($notice['wr_subject'],$title_len) . "</a>";
    } else {
        $result[link] = "";
    }
    
    return $result;
}


/** 
 * Converts human readable file size (e.g. 10 MB, 200.20 GB) into bytes. 
 * 
 * @param string $str 
 * @return int the result is in bytes 
 * @author Svetoslav Marinov 
 * @author http://slavi.biz 
 */ 
if(!function_exists('filesize2bytes')){
function filesize2bytes($str) { 
    $bytes = 0; 

    $bytes_array = array( 
        'B' => 1, 
        'KB' => 1024, 
        'MB' => 1024 * 1024, 
        'GB' => 1024 * 1024 * 1024, 
        'TB' => 1024 * 1024 * 1024 * 1024, 
        'PB' => 1024 * 1024 * 1024 * 1024 * 1024, 
    ); 

    $bytes = floatval($str); 

    if (preg_match('#([KMGTP]?B)$#si', $str, $matches) && !empty($bytes_array[$matches[1]])) { 
        $bytes *= $bytes_array[$matches[1]]; 
    } 

    $bytes = intval(round($bytes, 2)); 

    return $bytes; 
} 
}


// ����ĳ���� DB�� ����ϴ� ��, $c_code = "latest(simple, gnu4_pack)"
function db_cache($c_name, $seconds=300, $c_code) {

    global $g4;

    $result = sql_fetch(" select c_name, c_text, c_datetime from $g4[cache_table] where c_name = '$c_name' ");
    if (!$result) {
        // �ð��� offset �ؼ� �Է� (-1�� ����� ó�� call�� ĳ���� ����ϴ�)
        $new_time = date("Y-m-d H:i:s", $g4['server_time'] - $seconds - 1);
        $result['c_datetime'] = $new_time;
        sql_query(" insert into $g4[cache_table] set c_name='$c_name', c_datetime='$new_time' ");
    }

    $sec_diff = $g4['server_time'] - strtotime($result['c_datetime']);
    if ($sec_diff > $seconds) {

        // $c_code () �ȿ� ���븸 �츲 
        $pattern = "/[()]/";
        $tmp_c_code = preg_split($pattern, $c_code);
        
        // ������ �Լ��� �̸�
        $func_name = $tmp_c_code[0];

        // ������ �Լ��� ����
        $tmp_array = str_getcsv($tmp_c_code[1]);

        if ($func_name == "include_once" || $func_name == "include") {

            ob_start();
            include($tmp_array[0]);
            $c_text = ob_get_contents();
            ob_end_clean();

        } else {
        
            // ������ �Լ��� ���ڸ� ��Ƶ� ����
            $func_args = array();

            for($i=0;$i < count($tmp_array); $i++) {
                // �⺻ trim�� ���� ���� ���ش�. $charlist = " \t\n\r\0\x0B"
                $tmp_args = trim($tmp_array[$i]);
                // �߰� trim���� ���ڸ� �ѱ� �� ���� '�� ���ش�
                $tmp_args = trim($tmp_args, "'");
                // �߰� trim���� ���ڸ� �ѱ� �� ���� "�� ���ش�
                $func_args[$i] = trim($tmp_args, '"');
            }

            // ���ο� ĳ������ �����
            $c_text = call_user_func_array($func_name, $func_args);
        }

        // ���� ������ �׳� return
        if (trim($c_text) == "")
            return;

        // db�� �ֱ����� slashes���� �տ� �� �ٿ� �ֽð�
        $c_text1 = addslashes($c_text);
        
        // ���ο� ĳ������ ������Ʈ �ϰ�
        sql_query(" update $g4[cache_table] set c_text = '$c_text1', c_datetime='$g4[time_ymdhis]' where c_name = '$c_name' ");

        // ���ο� ĳ������ return (slashes�� ���°Ÿ� return �ؾ��մϴ�)
        return $c_text;

    } else {

        // ĳ���� �����͸� �״�� return
        return $result['c_text'];

    }
}

// http://www.php.net/manual/en/function.str-getcsv.php
if (!function_exists('str_getcsv')) { 
  
function str_getcsv($input, $delimiter=',', $enclosure='"', $escape=null, $eol=null) { 
  $temp=fopen("php://memory", "rw"); 
  fwrite($temp, $input); 
  fseek($temp, 0); 
  $r = array(); 
  while (($data = fgetcsv($temp, 4096, $delimiter, $enclosure)) !== false) { 
    $r[] = $data; 
  } 
  fclose($temp); 
  return $r; 
} 
  
}


function br2nl($string, $line_break=PHP_EOL) {
    $patterns = array(    
                        "/(<br>|<br \/>|<br\/>)\s*/i",
                        "/(\r\n|\r|\n)/"
    );
    $replacements = array(    
                            PHP_EOL,
                            $line_break
    );
    $string = preg_replace($patterns, $replacements, $string);
    return $string;
}

//  <BR>�� nl�� ��ȯ - http://us.php.net/manual/en/function.nl2br.php
/*
function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
} 

function br2nl($string){ 
  $return=preg_replace('/<BR[[:space:]]*\/?/i'. 
    '[[:space:]]*>',chr(13).chr(10),$string);
  return $return; 
} 
*/


// http://kr.php.net/implode]
if(!function_exists('implode_wrapped')){
function implode_wrapped($before, $after, $glue, $array){
    $output = '';
    foreach($array as $item){
        $output .= $before . $item . $after . $glue;
    }
    return substr($output, 0, -strlen($glue));
}
}


// http://blog.naver.com/xitem?Redirect=Log&logNo=140113457912
// PHP��ȣȭ �Լ�
function encrypt($data,$k) { 
  $encrypt_these_chars = "1234567890ABCDEFGHIJKLMNOPQRTSUVWXYZabcdefghijklmnopqrstuvwxyz.,/?!$@^*()_+-=:;~{}";
  $t = $data;
  $result2;
  $ki;
  $ti;
  $keylength = strlen($k);
  $textlength = strlen($t);
  $modulo = strlen($encrypt_these_chars);
  $dbg_key;
  $dbg_inp;
  $dbg_sum;
  for ($result2 = "", $ki = $ti = 0; $ti < $textlength; $ti++, $ki++) {
    if ($ki >= $keylength) {
      $ki = 0;
    }
    $dbg_inp += "["+$ti+"]="+strpos($encrypt_these_chars, substr($t, $ti,1))+" ";  
    $dbg_key += "["+$ki+"]="+strpos($encrypt_these_chars, substr($k, $ki,1))+" ";  
    $dbg_sum += "["+$ti+"]="+strpos($encrypt_these_chars, substr($k, $ki,1))+ strpos($encrypt_these_chars, substr($t, $ti,1)) % $modulo +" ";
    $c = strpos($encrypt_these_chars, substr($t, $ti,1));
    $d;
    $e;
    if ($c >= 0) {
      $c = ($c + strpos($encrypt_these_chars, substr($k, $ki,1))) % $modulo;
      $d = substr($encrypt_these_chars, $c,1);
      $e .= $d;
    } else {
      $e += $t.substr($ti,1);
    }
  }
  $key2 = $result2;
  $debug = "Key  : "+$k+"\n"+"Input: "+$t+"\n"+"Key  : "+$dbg_key+"\n"+"Input: "+$dbg_inp+"\n"+"Enc  : "+$dbg_sum;
  return $e . "";
}


function decrypt($key2,$secret) {
  $encrypt_these_chars = "1234567890ABCDEFGHIJKLMNOPQRTSUVWXYZabcdefghijklmnopqrstuvwxyz.,/?!$@^*()_+-=:;~{}";
  $input = $key2;
  $output = "";
  $debug = "";
  $k = $secret;
  $t = $input;
  $result;
  $ki;
  $ti;
  $keylength = strlen($k);
  $textlength = strlen($t);
  $modulo = strlen($encrypt_these_chars);
  $dbg_key;
  $dbg_inp;
  $dbg_sum;
  for ($result = "", $ki = $ti = 0; $ti < $textlength; $ti++, $ki++) {
    if ($ki >= $keylength){
      $ki = 0;
    }
    $c = strpos($encrypt_these_chars, substr($t, $ti,1));
    if ($c >= 0) {
      $c = ($c - strpos($encrypt_these_chars , substr($k, $ki,1)) + $modulo) % $modulo;
      $result .= substr($encrypt_these_chars , $c, 1);
    } else {
      $result += substr($t, $ti,1);
    }
  }
  return $result;
}


// �Ҵ��� - ���񿡼� Ư�����ڸ� ��~ ������������~!
// �ѹ��� �����ϱ� �� �ȳ��󰡼� �Ѱ��� �����ϴ�.
function remove_special_chars($subject) {

    global $g4;

    // ���ְ� ���� ���ڿ��� config.php���� ���� �մϴ�.
    $change = $g4['special_chars_change'];

    // euc-kr �� ��� utf-8�� ��ȯ�Ѵ�.
    if (strtolower($g4[charset]) == 'euc-kr') {
        $subject = iconv('EUC-KR','UTF-8',$subject);

        $change = iconv("EUC-KR", "UTF-8", $change);
    }

    // ���ڿ��� ġȯ �մϴ�.
    $subject = preg_replace("/[$change]/u", "_", $subject);

    // euc-kr �� ��� utf-8�� �ٽ� euc-kr ��ȯ�Ѵ�.
    if (strtolower($g4[charset]) == 'euc-kr') {
        $subject = iconv('UTF-8','EUC-KR',$subject);
    }

    return $subject;
}


// ip �ּҸ� unit 32 ���ڷ�
// http://www.zedwood.com/article/144/php-mysql-geoip-lookup
function ipaddress_to_uint32($ip) {
    list($v4,$v3,$v2,$v1) = explode(".", $ip);
    return ($v4*256 *256*256) + ($v3*256*256) + ($v2*256) + ($v1);
}


// ip�� �������� return
// http://www.zedwood.com/article/144/php-mysql-geoip-lookup
function ipaddress_to_country_code($ip) {
    global $g4;

    $i = ipaddress_to_uint32($ip);

    $query   = "select * from $g4[geoip_table] where ip32_start<= $i and $i <=ip32_end;";
    $result  = sql_fetch($query);
    
    return $result['country_code'];
}


// Ư�� ���̺��� �÷� �̸��� ��� - http://www.codediesel.com/mysql/selecting-all-except-some-columns-in-mysql/
function get_column_names($table_name) {
    global $g4;

    $query = "SHOW COLUMNS FROM {$table_name}";

    if(($result=sql_query($query, $conn))) {
 
        $column_names = array();
        while ($row = sql_fetch_array($result)) {
            $column_names[] = $row['Field'];
        }

        return $column_names;
    }
    else
        return 0;
}


// Ư�� ���̺��� �÷� �̸��� ���� SQL select ��� ��� - http://www.codediesel.com/mysql/selecting-all-except-some-columns-in-mysql/
function except_sql_statement($table_name, $exclude) {
    global $g4;

    $column_names = get_column_names($table_name);
    $statement = "";

    foreach($column_names as $name) {
        if(!in_array($name, $exclude)) {
            if($statement == "")
                $statement = $name;
            else
                $statement .= "," . $name;
        }
    }
 
    return $statement;
}


// ����Ʈ ��� auction.lib.php ���� ������
function alert_only($msg='')
{
	global $g4;

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$g4[charset]\">";
	echo "<script language='javascript'>alert('$msg');</script>";
    exit;
}


// ����ũ�� �α� ����ϱ�
function insert_unicro_log($mb_id, $log_message='', $log_url='')
{
    global $config;
    global $g4;
    global $is_admin;

    // ����ũ�� �α׸� ���
    $sql = " insert into $g4[unicro_log_table]
                set mb_id = '$mb_id',
                    log_datetime = '$g4[time_ymdhis]',
                    log_message = '".addslashes($log_message)."',
                    log_ip = '$_SERVER[REMOTE_ADDR]',
                    log_url = '$log_url' ";
    sql_query($sql);
}


// �Խ��Ǻ� �α�� Ű���� Ȯ��
function popular_list($pop_cnt=7, $date_cnt=7, $bo_table='') 
{
    global $config, $g4;

    if ($bo_table) $bo_sql = " and bo_table = '$bo_table' ";

    $date_gap = date("Y-m-d", $g4[server_time] - ($date_cnt * 86400));
    $sql = " select pp_word, sum(pp_count) as cnt from $g4[popular_sum_table]
              where pp_date between '$date_gap' and '$g4[time_ymd]' and pp_word != '' 
              $bo_sql
              group by pp_word
              order by cnt desc, pp_word
              limit 0, $pop_cnt ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) 
    {
        // ��ũ��Ʈ���� �������
        $list[$i][pp_word] = get_text($row[pp_word]);
        $list[$i][cnt] = $row[cnt];
        $list[$i][sfl] = $row[sfl];
    }
    
    return $list;
}

// �Ű� - �Խ��� ���� ����
function check_singo_nowrite($bo_singo_nowrite, $bo_table='')
{
    global $g4, $board, $member;

    // $bo_singo_nowrite�� explode �մϴ�.
    $singo_array = explode("|", trim($bo_singo_nowrite));
    foreach ($singo_array as $key => $val) {
        $res = explode(",", trim($val));
        if ($res) {
            $singo2_days[$res[0]] = $res[0];
            $singo2_count[$res[1]] = $res[1];
        }
    }
    // �Էµ� �迭�� ����
    $singo_array_count = count($singo2_count);
    // �迭�� �����ϱ� (days �� ��������)
    array_multisort($singo2_days, $singo2_count);
    // �ִ� �Ű� Ȯ����
    $max_singo_days = $singo2_days[$singo_array_count-1];

    // sort�Ǹ鼭 ��Ʈ���� key ���� �ٽ� �������ֱ�
    for ($i=0; $i < count($singo2_days); $i++) {
        $singo2_days2[$singo2_days[$i]-1] = $singo2_days[$i];
        //$singo2_count2[$singo2_days[$i]-1] = $singo2_count[$i];

        // �������� ȸ���� ���, �Ű� ī���Ϳ��� 1���� ���ݴϴ�.
        if ($member[mb_realcheck] == "0000-00-00 00:00:00")
            $singo2_count2[$singo2_days[$i]-1] = $singo2_count[$i];
        else
            $singo2_count2[$singo2_days[$i]-1] = $singo2_count[$i] - 1;
    }

    // ����� ���̵�
    $mb_id = $member[mb_id];
    
    // $bo_table�� �ִ� ��쿡�� �ش� �Խ��ǿ� ���ؼ���
    if ($bo_table)
        $sql_bo_table = " AND bo_table = '$bo_table' ";
    else
        $sql_bo_table = "";

    // ������ �Ű�Ǽ��� ������, �׳� return �ع����ϴ�.
    if ($member[mb_singo] == 0)
        return false;

    // ��κ��� ����ڴ� �Ű�Ǽ��� ���� ������, �Ű�Ǽ��� �ִ� ����� cost�� ���� ���� sql�� �����ϰ� �մϴ�.
    $sql = " SELECT count(*) as cnt from $g4[singo_table] 
              WHERE mb_id = '$mb_id' $sql_bo_table
                AND sg_datetime > '" . date("Y-m-d H:i:s", $g4[server_time] - $max_singo_days * 86400) ."' ";
    $result = sql_fetch($sql);
    if ($result[cnt] == 0)
        return false;

    // �Ű����ѿ� �ɸ����� Ȯ���� ���ϴ�.
    $sql = " SELECT to_days(now())-to_days(sg_datetime) AS t_diff, count( * ) AS cnt, date_format( sg_datetime, '%Y-%m-%d' ) 
               FROM `$g4[singo_table]` 
              WHERE mb_id = '$mb_id' $sql_bo_table
                AND (to_days(now())-to_days(sg_datetime)-$max_singo_days) < 0
              GROUP BY t_diff
          ";
    $result = sql_query($sql);

    // ������� �迭�� �ֽ��ϴ�
    for($i=0; $row = sql_fetch_array($result); $i++) {
        $singo_result[$row[t_diff]] = $row[cnt];
    }
    
    $sum = 0;
    for($i=0; $i < $max_singo_days; $i++) {
        $sum += $singo_result[$i];
        if ($singo2_days2[$i] && $singo2_count2[$i] && $singo2_days2[$i] == ($i+1) && $sum >= $singo2_count2[$i]) {
            echo "$singo2_days2[$i]�� ���� �Ű�� �Ǽ��� $singo2_count2[$i]���� �ʰ��Ͽ����ϴ�.";
            return true;
        }
    }

    return false;
}


// ȸ�� ����(����)�� ���
function get_gl_name($mb_level)
{
    global $g4;

    $sql = "select gl_name from $g4[member_group_table] where gl_id = '$mb_level'";
    $row = sql_fetch($sql, FALSE);

    if (!$row)
        $gl_name = $mb_level;
    else
        $gl_name = $row['gl_name'];

    return $gl_name;
}

// �̹��� ������ �о DB�� ����
// �̹��� ������ �о db�� �־� �ݴϴ�.
function get_chimage($string)
{ 
    global $g4, $board, $member, $_SESSION;

    // ������ setting
    $return = $string['0']; 
    preg_match_all('@(?P<attribute>[^\s\'\"]+)\s*=\s*(\'|\")?(?P<value>[^\s\'\"]+)(\'|\")?@i', $return, $match);
    if (function_exists('array_combine')) {
        $img = array_change_key_case(array_combine($match['attribute'], $match['value']));
    }
    else {
        $img = array_change_key_case(array_combine4($match['attribute'], $match['value']));
    }

    // ������ img�� �����̸��� �������� ��찡 �־ decoding ���ݴϴ� (��: &#111;&#110; = on)
    $img['src'] = html_entity_decode($img[src]); 

    // �̹��� ������ ��θ� ���� (�ܺ�? ����? �����ΰ�� ������? �����?)
    if (preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $img['src'])) {
        // �� ������ �ִ� �̹���?
        $img_src = @getimagesize($img['src']);
        if (preg_match("/" . $_SERVER[HTTP_HOST] . "/", $img[src], $matches)) {
            $url = parse_url($img[src]);
            $img[src] = $url[path];
        } else {
            return "";
        }
    }

    // update ����� ��, ���ε� ������ DB�� ���� �մϴ�.
    if ($g4[w] == "u") {
        $sql = " select bc_id from $g4[board_cheditor_table] where bo_table='$g4[bo_table]' and wr_id='$g4[wr_id]' and bc_url like '%" . $img[src] . "' and del = '1' ";
        $result = sql_fetch($sql);

        // �̹� �ö� �����̸� ������̶�� del field�� ������Ʈ �ϰ� return
        if ($result[bc_id]) {
            $sql = "update $g4[board_cheditor_table] set del = '0' where bc_id = '$result[bc_id]' ";
            sql_query($sql);

            return $return; 
        }
    }

    // $img[src] �� ���� ������ �̹Ƿ� �̹��� ������ ����θ� ���մϴ�.
    // �̷��� �߶���� ����� �� ��ΰ� ���´�.
    $fl = explode("/$g4[data]/",$img[src]);
    $rel_path = "../" . $g4[data] . "/" . $fl[1];
    $path = pathinfo($rel_path);
    $bc_dir = $path['dirname'];
    $bc_file = $path['basename'];

    // �̹��� ������ ���մϴ�
    $im = getimagesize($rel_path);
    
    // filesize�� KB ������ ����
    $fs = filesize2bytes(filesize($rel_path))/1000;

    // sub ���丮�� ��ġ�� ���...��... �Ӹ�����
    $bc_path = $g4[data] . "/" . $fl[1];

    // source file �̸��� ���� �´�
    $sql = " select * from $g4[board_cheditor_table] where bo_table='$g4[bo_table]' and wr_id is null and bc_file = '$bc_file' and bc_url like '%$bc_path' ";
    $sc = sql_fetch($sql);

    if ($sc[bc_id]) {
        // ������ ������Ʈ ���ش�
        $sql = " UPDATE $g4[board_cheditor_table]
                SET 
                    bc_url = '".$img[src]."',
                    bc_dir = '".$bc_dir."',
                    bc_file = '".$bc_file."',
                    bc_width = '". $im[0] ."',
                    bc_height = '". $im[1] ."',
                    bc_type = '". $im[2] ."',
                    bc_filesize = '". $fs ."',
                    bc_ip = '".$g4[ip_addr]."',
                    mb_id = '".$member['mb_id']."',
                    bo_table = '". $g4[bo_table] ."',
                    wr_id = '".$g4[wr_id]."',
                    bc_datetime = '".$g4[time_ymdhis]."'
              where bc_id = '$sc[bc_id]'
                     ";
        sql_query($sql);
    } else {
        // ������ ���� �־��ش�
        $sql = " INSERT INTO $g4[board_cheditor_table]
                SET 
                    bc_url = '".$img[src]."',
                    bc_dir = '".$bc_dir."',
                    bc_file = '".$bc_file."',
                    bc_width = '". $im[0] ."',
                    bc_height = '". $im[1] ."',
                    bc_type = '". $im[2] ."',
                    bc_filesize = '". $fs ."',
                    bc_ip = '".$g4[ip_addr]."',
                    mb_id = '".$member['mb_id']."',
                    bo_table = '". $g4[bo_table] ."',
                    wr_id = '".$g4[wr_id]."',
                    bc_datetime = '".$g4[time_ymdhis]."'
                     ";
        sql_query($sql);
    }

    return $return; 
}


// ������ ��¥ ������ ���� �� �� ���� �ϱ�
function check_bo_from_date()
{
    global $is_admin, $view, $board;

    if (!$view)
        return false;

    $date1 = date("Y-m-d");
    $date2 = cut_str($view[wr_datetime], 10, "");
   
    $_date1 = explode("-",$date1); 
    $_date2 = explode("-",$date2); 

    $tm1 = mktime(0,0,0,$_date1[1],$_date1[2],$_date1[0]); 
    $tm2 = mktime(0,0,0,$_date2[1],$_date2[2],$_date2[0]); 

    $date_diff = ($tm1 - $tm2) / 86400; 
    if ($board[bo_from_date] and  $date_diff > $board[bo_from_date] and !$is_admin) 
    {
        alert("�ý��ۿ��� ��ȸ�� ������� �ʴ� ������ �� �Դϴ�. �����ڿ��� �����Ͻñ� �ٶ��ϴ�.");
    }
    
    return true;
}

// ����/������ bo_sex �ʵ忡 M/F�� ��ϵ� ��쿡�� �Խ����� ������ ��� 
function check_bo_sex()
{
    global $g4, $board, $member, $is_admin;

    if($board[bo_sex] && !$is_admin) {
        if ($member[mb_sex]) { 
          if (($board[bo_sex] == $member[mb_sex]) || $is_admin)
              {;} 
          else 
          { 
              alert("����/�̸�� �����濡��, �ƺ�/������ �ƺ��濡�� ������ �� �ֽ��ϴ�"); 
          } 
        } else {
            alert("������ ����� ȸ���� ����/�̸�, �ƺ�/���� �Խ����� �̿��� �� �ֽ��ϴ�. ���������� �������ּ���.",  "$g4[bbs_path]/member_confirm.php?url=register_form.php");
        }
    }
    
    return true;
}

// http://www.creativecommons.or.kr/xe/?mid=licenses
function mw_get_ccl_info($ccl)
{
    $info = array();

    switch ($ccl)
    {
        case "by":
            $info[by] = "by";
            $info[nc] = "";
            $info[nd] = "";
            $info[kr] = "������ǥ��";
            break;
        case "by-nc":
            $info[by] = "by";
            $info[nc] = "nc";
            $info[nd] = "";
            $info[kr] = "������ǥ��-�񿵸�";
            break;
        case "by-sa":
            $info[by] = "by";
            $info[nc] = "";
            $info[nd] = "sa";
            $info[kr] = "������ǥ��-�������Ǻ������";
            break;
        case "by-nd":
            $info[by] = "by";
            $info[nc] = "";
            $info[nd] = "nd";
            $info[kr] = "������ǥ��-�������";
            break;
        case "by-nc-sa":
            $info[by] = "by";
            $info[nc] = "nc";
            $info[nd] = "sa";
            $info[kr] = "������ǥ��-�񿵸�-�������Ǻ������";
            break;
        case "by-nc-nd":
        default :
            $info[by] = "by";
            $info[nc] = "nc";
            $info[nd] = "nd";
            $info[kr] = "������ǥ��-�񿵸�-�������";
            break;
    }
    $info[ccl] = $ccl;
    $info[msg] = "ũ������Ƽ�� Ŀ���� �ڸ��� $info[kr] 2.0 ���ѹα� ���̼����� ���� �̿��Ͻ� �� �ֽ��ϴ�.";
    //$info[link] = "http://creativecommons.org/licenses/{$ccl}/2.0/kr/";
    $info[link] = "http://www.creativecommons.or.kr/xe/?mid=licenses";
    
    return $info;
}

// ���ñ� ���.. 080429, curlychoi
function mw_related($related, $count, $field="wr_id, wr_subject, wr_content")
{
    global $bo_table, $write_table, $g4, $view;

    if (!trim($related)) return;

    $view[wr_related] = "";
    
    $sql_wr_id = " and wr_id != '$view[wr_id]' ";

    $sql_where = "";
    $related = explode(",", $related);
    foreach ($related as $rel) {
        $rel = trim($rel);
        if ($rel) {
            $rel = addslashes($rel);
            if ($sql_where) {
                $sql_where .= " or ";
            }
            $sql_where .= " (instr(wr_subject, '$rel')>0 or instr(wr_content, '$rel')>0) ";
        }
    }

    if ($g4['old_stype_search']) {
        $sql = "select $field from $write_table where wr_is_comment = 0 and FIND_IN_SET('secret',wr_option) = 0 {$sql_wr_id} and ($sql_where) order by wr_num limit 0, $count ";
        $result = sql_query($sql);
    } else {
        // tmp ���̺��� �����, �װ����� order by�� limit�� �Ѵ�.
        $sql = "select $field, wr_num from $write_table where wr_is_comment = 0 and FIND_IN_SET('secret',wr_option) = 0 {$sql_wr_id} and ($sql_where) ";
        $sql_tmp = " create TEMPORARY table related_tmp_table as $sql ";
        $sql_ord = " select * from related_tmp_table order by wr_num limit 0, $count ";

        @mysql_query($sql_tmp) or die("<p>$sql_tmp<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
        $result = @mysql_query($sql_ord) or die("<p>$sql_ord<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
    }

    $list = array();
    $i = 0;
    while ($row = sql_fetch_array($result)) {
        $row[href] = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$row[wr_id]";
        $row[comment] = $row[wr_comment] ? "<span class='comment'>($row[wr_comment])</span>" : "";
        $row[subject] = get_text($row[wr_subject]);
        $list[] = $row;
        if (++$i >= $count) {
            break;
        }
    }

    return $list;
}


// ���丮�� ���� ������ �Ѱ��� ���δ�
function rslash($dpath)
{
    return rtrim($dpath, "/") . "/";
}


// ȸ�� ������ ��/�ٿ� �մϴ�.
function member_level_up($mb_id)
{
    global $g4;

    $mb = get_member($mb_id);

    $up_check = 0;
    $down_check = 0;

    $sql = " select *, 
                    (up_days+up_point+up_post+up_post_all+good) as sum_up,
                    (down_point+down_post+down_post_all+nogood+singo) as sum_down
                from $g4[member_level_table] 
               where member_level = '$mb[mb_level]' and (use_levelup = 1 or use_leveldown = 1)";
    $result = sql_fetch($sql);

    // ������
    if ($result && $result['use_levelup'] && $result['sum_up'] > 0) {

        // �������� Ȯ���ؼ� ü���Ⱓ�� �ȵǾ����� pass. ü���Ⱓ�� levelup �� ���� Ȯ��
        $wtime = date("Y-m-d", $g4[server_time] - ($result[up_days] * 86400)); 
        if ($mb[mb_level_datetime] != "0000-00-00 00:00:00")
            $jtime = substr($mb[mb_level_datetime],0,10);
        else
            $jtime = substr($mb[mb_datetime],0,10);

        // time format���� ����
        $wtime = strtotime($wtime ." 00:00:00");
        $jtime = strtotime($jtime ." 00:00:00");

        if ($wtime >= $jtime) {
            // ����Ʈ
            if ($result['up_point'] && $mb['mb_point'] >= $result['up_point']) 
                $up_check = $up_check + 1;
            else
                $up_check = -1000;

            //�����ϼ��� ������ �ش� �ϼ� ��ŭ�� �ֽű��� Ȯ��
            if ($result['up_audit_days'])
                $sql_audit = " and bn_datetime > '".date("Y-m-d H:i:s", $g4[server_time] - (86400 * $result[up_audit_days]))."' ";
            else
                $sql_audit = "";

            // �Խñ� �� (�Խñ� Ȯ���� ��ü �ֱٱ� ������ ���ؼ� �ǽ�)
            if ($result['up_post']) {
                $sql = " select count(*) as cnt from $g4[board_new_table] where mb_id = '$mb[mb_id]' and wr_id = wr_parent $sql_audit ";
                $chk = sql_fetch($sql);
                if ($chk['cnt'] >= $result['up_post'])
                    $up_check = $up_check + 1;
                else
                    $up_check = -1000;
            }

            // ��ü �Խñ� �� (�ڸ�Ʈ ����)
            if ($result['up_post_all']) {
                $sql = " select count(*) as cnt from $g4[board_new_table] where mb_id = '$mb[mb_id]' $sql_audit ";
                $chk = sql_fetch($sql);
                if ($chk['cnt'] >= $result['up_post_all'])
                    $up_check = $up_check + 1;
                else
                    $up_check = -1000;
            }

            //�����ϼ��� ������ ��õ���� �ش� ��¥��ŭ�� Ȯ��
            if ($result['up_audit_days'])
                $sql_audit = " and bg_datetime > '".date("Y-m-d H:i:s", $g4[server_time] - (86400 * $result[up_audit_days]))."' ";
            else
                $sql_audit = "";
                
            // ��õ�� (����õ���� ����)
            if ($result['good']) {
                $sql = " select count(*) as cnt from $g4[board_good_table] where mb_id = '$mb[mb_id]' and bg_flag = 'good' $sql_audit ";
                $chk = sql_fetch($sql);
                if ($chk['cnt'] >= $result['good']) {
                    $sql2 = " select count(*) as cnt from $g4[board_good_table] where mb_id = '$mb[mb_id]' and bg_flag = 'nogood' $sql_audit ";
                    $chk2 = sql_fetch($sql2);
                    if (($chk['cnt']-$chk2['cnt']) >= $result['good'])
                        $up_check = $up_check + 1;
                    else
                        $up_check = -1000;

                }
            }

            if ($up_check > 0) {
                // ȸ������ db�� ������Ʈ
                $sql = " update $g4[member_table] set mb_level = mb_level+1, mb_level_datetime = '$g4[time_ymdhis]' where mb_id = '$mb[mb_id]' ";
                sql_query($sql);
                // ȸ������ history�� insert
                $sql = " insert 
                           into $g4[member_level_history_table] 
                            set mb_id           = '$mb[mb_id]',
                                from_level      = '$mb[mb_level]',
                                to_level        = '" . (int)($mb[mb_level]+1) . "',
                                level_datetime  = '$g4[time_ymdhis]' ";
                sql_query($sql);
                
                $rstr = "$mb[mb_id] ȸ���� ������ �Ǿ����ϴ�";
            }
            
            // �������� �̷���� ��쿡�� �����ٿ��� �������� �ʰ� �ڵ带 �о�~!
            $result['sum_down'] = "";
        }
    } 
    else 
    if ($mb['mb_level'] >= 2 && $up_check != 0 && $result && $result['use_leveldown'] && $result['sum_down'] > 0) {  // �����ٿ�

        // ����Ʈ
        if ($result['down_point'] > 0 && $result['down_point'] < $mb['mb_point']) 
            $down_check = $down_check + 1;
        else
            $down_check = -1000;

        // �Խñ� �� (�Խñ� Ȯ���� ��ü �ֱٱ� ������ ���ؼ� �ǽ�)
        if ($result['down_post'] > 0) {
            $sql = " select count(*) as cnt from $g4[board_new_table] where mb_id = '$mb[mb_id]' and wr_id = wr_parent ";
            $chk = sql_fetch($sql);
            if ($chk['cnt'] < $result['down_post'])
                $down_check = $down_check + 1;
            else
                $down_check = -1000;
        }
        // ��ü �Խñ� �� (�ڸ�Ʈ ����)
        if ($result['down_post_all'] > 0) {
            $sql = " select count(*) as cnt from $g4[board_new_table] where mb_id = '$mb[mb_id]' ";
            $chk = sql_fetch($sql);
            if ($chk['cnt'] < $result['down_post_all'])
                $down_check = $down_check + 1;
            else
                $down_check = -1000;
        }

        //�����ϼ��� ������ ��õ���� �ش� ��¥��ŭ�� Ȯ��
        if ($result['down_audit_days'])
            $sql_audit = " and bg_datetime > '".date("Y-m-d H:i:s", $g4[server_time] - (86400 * $result[down_audit_days]))."' ";
        else
            $sql_audit = "";

        // ����õ�� (��õ���� �������� ����)
        if ($result['nogood'] > 0) {
            $sql = " select count(*) as cnt from $g4[board_good_table] where mb_id = '$mb[mb_id]' and bg_flag = 'nogood' $sql_audit ";
            $chk = sql_fetch($sql);
            if ($chk['cnt'] >= $result['nogood'])
                $down_check = $down_check + 1;
            else
                $down_check = -1000;
        }

        // �Ű�
        if ($result['singo'] > 0) {
            $sql = " select count(*) as cnt from $g4[singo_table] where mb_id = '$mb[mb_id]' $sql_audit ";
            $chk = sql_fetch($sql);
            if ($chk['cnt'] >= $result['nogood'])
                $down_check = $down_check + 1;
            else
                $down_check = -1000;
        }
        
        if ($down_check > 0) {
            // ȸ������ db�� ������Ʈ
            $sql = " update $g4[member_table] set mb_level = mb_level-1, mb_level_datetime = '$g4[time_ymdhis]' where mb_id = '$mb[mb_id]' ";
            sql_query($sql);
            // ȸ������ history�� insert
            $sql = " insert 
                       into $g4[member_level_history_table] 
                        set mb_id           = '$mb[mb_id]',
                            from_level      = '$mb[mb_level]',
                            to_level        = '" . (int)($mb[mb_level]-1) . "',
                            level_datetime  = '$g4[time_ymdhis]' ";
            sql_query($sql);

            $rstr = "$mb[mb_id] ȸ���� �����ٿ� �Ǿ����ϴ�";
        }
    }

    return $rstr;
}

// sideview�� ���
function print_sideview($mb_id, $board) {
    global $config, $g4;

    $subject_mb = get_member($mb_id, "mb_id, mb_nick, mb_name, mb_email, mb_email_certify, mb_homepage, mb_homepage_certify");

    // �Խ��ǿ� ����� �۾����� �̸�/�г����� �ٲ���� �� ��� �ɱ��? ���� �г��� �ٲٰ� �峭ġ�� ���� �ɱ��?
    if ($board[bo_use_name])
        $tmp_name = $subject_mb[mb_name];
    else
        $tmp_name = $subject_mb[mb_nick];

    $tmp_name = get_text(cut_str($tmp_name, $config['cf_cut_name'])); // ������ �ڸ��� ��ŭ�� �̸� ���
    $subject_sideview = get_sideview($subject_mb['mb_id'], $tmp_name, $subject_mb['wr_email'], $subject_mb['wr_homepage']);

    return $subject_sideview;
}

// �Խ��ǿ� �Խñ��� �űԷ� insert �մϴ�.
function board_write($bo_table, $mb_id)
{
    global $g4;
    global $ca_name, $html, $secret, $mail, $wr_subject, $wr_content, $wr_link1, $wr_link2, $wr_related, $wr_good, $wr_nogood;
    global $bf_file, $bf_source, $bf_type, $bf_width, $bf_height;
    global $wr_1, $wr_2, $wr_3, $wr_4, $wr_5, $wr_6, $wr_7, $wr_8, $wr_9, $wr_10;

    // �Խ��� ����
    $board = get_board($bo_table);

    // ȸ�� ����
    $member = get_member($mb_id);

    // �Խ��� ������ ����ϴ� - �ű� insert ����
    $g4_id = $board[gr_id];
    $write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�
    $wr_num = get_next_num($write_table);
    $wr_reply = "";
    $wr_name = $board[bo_use_name] ? $member[mb_name] : $member[mb_nick];
    $wr_password = $member[mb_password];
    $wr_email = $member[mb_email];
    $wr_homepage = $member[mb_homepage];
        
    $sql = " insert into $write_table
                set wr_num = '$wr_num',
                    wr_reply = '$wr_reply',
                    wr_comment = 0,
                    ca_name = '$ca_name',
                    wr_option = '$html,$secret,$mail',
                    wr_subject = '$wr_subject',
                    wr_content = '$wr_content',
                    wr_link1 = '$wr_link1',
                    wr_link2 = '$wr_link2',
                    wr_link1_hit = 0,
                    wr_link2_hit = 0,
                    wr_hit = 0,
                    wr_good = '$wr_good',
                    wr_nogood = '$wr_nogood',
                    mb_id = '$member[mb_id]',
                    wr_password = '$wr_password',
                    wr_name = '$wr_name',
                    wr_email = '$wr_email',
                    wr_homepage = '$wr_homepage',
                    wr_datetime = '$g4[time_ymdhis]',
                    wr_last = '$g4[time_ymdhis]',
                    wr_ip = '$_SERVER[REMOTE_ADDR]',
                    wr_related = '$wr_related',
                    wr_1 = '$wr_1',
                    wr_2 = '$wr_2',
                    wr_3 = '$wr_3',
                    wr_4 = '$wr_4',
                    wr_5 = '$wr_5',
                    wr_6 = '$wr_6',
                    wr_7 = '$wr_7',
                    wr_8 = '$wr_8',
                    wr_9 = '$wr_9',
                    wr_10 = '$wr_10' ";

    sql_query($sql);
    $wr_id = mysql_insert_id();

    // �θ� ���̵� UPDATE
    sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

    // ���� INSERT
    sql_query(" insert into $g4[board_new_table] ( bo_table, wr_id, wr_parent, bn_datetime, mb_id, wr_is_comment, gr_id, wr_option, parent_mb_id) 
                values ( '$bo_table', '$wr_id', '$wr_id', '$g4[time_ymdhis]', '$member[mb_id]', '0', '$gr_id', '$secret', '$parent_mb_id[mb_id]') "); 
    
    // �Խñ� 1 ����
    sql_query("update $g4[board_table] set bo_count_write = bo_count_write + 1, bo_modify_datetime = '$g4[time_ymdhis]' where bo_table = '$bo_table'");

    // �Ҵ��� - min_wr_num ������Ʈ
    $result = sql_fetch(" select MIN(wr_num) as min_wr_num from $write_table ");
    $sql = " update $g4[board_table] set min_wr_num = '$result[min_wr_num]' where bo_table = '$bo_table' ";
    sql_query($sql);

    // ÷������ insert
    if ($bf_file) {
        $sql = " insert into $g4[board_file_table]
                    set bo_table = '$bo_table',
                        wr_id = '$wr_id',
                        bf_no = '0',
                        bf_source = '$bf_source',
                        bf_file = '$bf_file',
                        bf_content = '$wr_subject',
                        bf_download = 0,
                        bf_filesize = '',
                        bf_width = '$bf_width',
                        bf_height = '$bf_height',
                        bf_type = '$bf_type',
                        bf_datetime = '$g4[time_ymdhis]' ";
        sql_query($sql);
    }

    // �Ҵ��� - ÷�������� ���� �ľ�
    $sql = " select count(*) as cnt from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_source <> '' ";
    $result = sql_fetch($sql);

    // �Ҵ��� - ÷������ ���� ������Ʈ
    $sql = " update $write_table set wr_file_count = '$result[cnt]' where wr_id = '$wr_id' ";
    sql_query($sql);

    // �Ҵ��� - CCL ���� ������Ʈ
    if ($board[bo_ccl]) {
        $wr_ccl = "";
        if ($wr_ccl_by == "by") { $wr_ccl .= "by"; }
        if ($wr_ccl_nc == "nc") { $wr_ccl .= $wr_ccl ? "-": ""; $wr_ccl .= "nc"; }
        if ($wr_ccl_nd == "nd") { $wr_ccl .= $wr_ccl ? "-": ""; $wr_ccl .= "nd"; }
        if ($wr_ccl_nd == "sa") { $wr_ccl .= $wr_ccl ? "-": ""; $wr_ccl .= "sa"; }

        sql_query("update $write_table set wr_ccl = '$wr_ccl' where wr_id = '$wr_id'");
    }
}

// �ٹٲ� ���ֱ�, http://huddak.net/bbs/board.php?bo_table=cm_free&wr_id=3629
function remove_nr($str) {
    $reg_e = array('/\n/', '/\r/', '/\"/', "/<\/script>/i"); 
    $reg_p = array(' ', ' ', '\\"', "<\/SCRIPT>"); 

    return preg_replace($reg_e, $reg_p, $str);
}

// ����������Ŵ�� - http://opencode.co.kr/bbs/board.php?bo_table=gnu4_tips&wr_id=913
function save_me($content) {

    // �ֹι�ȣ
    $content1 = preg_replace_callback("/(?<![\d+_%])\d{2}[0-1]\d[0-3]\d[-\s]?[1-8]\d{6}/", 'save_jumin', $content);

    // �ڵ�����ȣ
    $content2 = preg_replace_callback("/(?<![\d+_%])(010|011|016|017|018|019)[-\s]\d{3,4}[-\s]\d{4}/", 'save_mobile', $content1);

    // ��ȭ��ȣ
    $content3 = preg_replace_callback("/(?<![\d+_%])(070|02|031|032|033|041|042|043|051|052|053|054|055|061|062|063|064)[-\s]\d{3,4}[-\s]\d{4}/", 'save_phone', $content2);

    // �̸���
    $content4 = preg_replace_callback("/(?<![\d+_%])[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/", 'save_email', $content3);

    return $content4;
}

// �̸����� mask
function save_email($string)
{
    // @�� �������� ���ڿ��� �ڸ� ��
    $str = explode("@", $string[0]);

    // �պκ��� ��� mask
    $mask1 = strlen($str[0]);
    $str1 = substr_replace($str[0], str_repeat("*", $mask1), 0, $mask1);

    // �޺κ��� ù��°�� ��� mask
    $tmp = explode(".", $str[1]);
    $mask2 = strlen($tmp[0]);
    $tmp[0] = substr_replace($tmp[0], str_repeat("*", $mask2), 0, $mask2);
    $str2 = implode(".", $tmp);

    $return = $str1 . "@" . $str2;

    return $return; 
}

// �ֹι�ȣ�� mask, �ֹι�ȣ�� ��� key�� �츮�� ��� mix + str_shuffle
function save_jumin($string)
{ 
    // "-" �Ǵ� SPACE�� explode
    $str = preg_split("/-|\s/", $string[0]);
    
    // 3�ڸ��� *�� �ٲٰ� str_shuffle. *�� �ٲٴ� ���� ��ġ�� ������ ���� ���ؼ� ����
    if (count($str) == 2) {
        $str1 = str_shuffle(substr_replace($str[0], "***", rand(0,2), 3));
        $str2 = str_shuffle(substr_replace($str[1], "***", rand(0,3), 3));
        $return = $str1 . "-" . $str2;
    } else {
        // ������ ���� ���ڰ� �پ� �ִ� ���
        $str1 = str_shuffle(substr_replace($str[0], "******", rand(0,2), 6));
        $return = $str1;
    }

    return $return;
}

// �ڵ��� ��ȣ�� mask, 01* - *** - ****, ���� �ڸ����� �״�� ���´�.
function save_mobile($string)
{ 
    // "-" �Ǵ� SPACE�� explode
    $str = preg_split("/-|\s/", $string[0]);
    $return = substr_replace($str[0], "*", 2) . "-" . str_repeat("*", strlen($str[1])) . "-" . str_repeat("*", strlen($str[2]));

    return $return;
}

// ��ȭ��ȣ�� mask, ���� ������ ���ϰ� ������ ������ 3���� mask
function save_phone($string)
{ 
    // "-" �Ǵ� SPACE�� explode
    $str = preg_split("/-|\s/", $string[0]);
    $return = substr_replace($str[0], "**", 1, 2) . "-" . str_repeat("*", strlen($str[1])) . "-" . str_repeat("*", strlen($str[2]));

    return $return;
}

// ������ �ڸ����� ���Ĵ��޸� ������ ����
// http://stackoverflow.com/questions/12000979/alphanumeric-increment-a-string-in-php-to-a-certain-length
function getNextAlphaNumeric($code, $digit=4) {
    $base_ten = base_convert($code,36,10);
    $result = base_convert($base_ten+1,10,36);
    $result = str_pad($result, $digit, '0', STR_PAD_LEFT);
    $result = strtoupper($result);
    return $result;
}

// ����Ʈ�� ���� ���� �������� ���
// http://stackoverflow.com/questions/2510434/php-format-bytes-to-kilobytes-megabytes-gigabytes
function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');  
    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}


function redis_cache($c_name, $seconds=300, $c_code) {

    global $g4;

    // redis instance�� �浹�� ����
    $c_name = $g4["rdomain"] . $c_name;

    $redis = new Redis();
    $redis->connect($g4["rhost"], $g4["rport"]);
    $redis->select($g4["rdb"]);
    if ($redis->ttl($c_name) > 0)
        return $redis->get($c_name);
    else {

        // $c_code () �ȿ� ���븸 �츲 
        $pattern = "/[()]/";
        $tmp_c_code = preg_split($pattern, $c_code);
        
        // ������ �Լ��� �̸�
        $func_name = $tmp_c_code[0];

        // ������ �Լ��� ����
        $tmp_array = str_getcsv($tmp_c_code[1]);
        
        if ($func_name == "include_once" || $func_name == "include") {

            ob_start();
            include($tmp_array[0]);
            $c_text = ob_get_contents();
            ob_end_clean();

        } else {
        
            // ������ �Լ��� ���ڸ� ��Ƶ� ����
            $func_args = array();

            for($i=0;$i < count($tmp_array); $i++) {
                // �⺻ trim�� ���� ���� ���ش�. $charlist = " \t\n\r\0\x0B"
                $tmp_args = trim($tmp_array[$i]);
                // �߰� trim���� ���ڸ� �ѱ� �� ���� '�� ���ش�
                $tmp_args = trim($tmp_args, "'");
                // �߰� trim���� ���ڸ� �ѱ� �� ���� "�� ���ش�
                $func_args[$i] = trim($tmp_args, '"');
            }

            // ���ο� ĳ������ �����
            $c_text = call_user_func_array($func_name, $func_args);
        }

        // ���� ������ �׳� return
        if (trim($c_text) == "")
            return;

        // redis�� cache ���� ������Ʈ
        $redis->setex($c_name, $seconds, $c_text);

        // redis�� close
        $redis->close();

        // ���ο� ĳ������ return (slashes�� ���°Ÿ� return �ؾ��մϴ�)
        return $c_text;
    }
}

function redis_login($opt="") {

    global $g4;

    // redis�϶��� redis login ������ ����.
    $redis_login = new Redis();
    $redis_login->connect($g4["rhost"], $g4["rport"]);
    $redis_login->select($g4["rdb1"]);

    // g4_login ���̺��� ������ ��� ����
    $sql = " delete from $g4[login_table] ";
    sql_query($sql);

    // ��� key�� �����ͼ� g4_login DB�� �־��ݴϴ�.
    $allKeys = $redis_login->keys($g4["rdomain"] . "_login_*");   // all keys will match this.
    foreach ($allKeys as $rkey) {

        $rdat = explode ( "|", $redis_login->get($rkey) );
        if ($redis_login->ttl($rkey) > 0) {

            // $opt == "mb_id" : ȸ�������� ���
            if ($opt == "mb_id" && $rdat['1'] == "")
                continue;

           	$tmp_sql = " insert into $g4[login_table] 
           	                set
    	                        lo_ip = '$rdat[0]',
    	                        mb_id = '$rdat[1]',
        	                    lo_datetime = '$rdat[2]',
        	                    lo_location = '$rdat[3]',
    	                        lo_url = '$rdat[4]',
    	                        lo_referer = '$rdat[5]',
    	                        lo_agent = '$rdat[6]'
                              ";
            	sql_query($tmp_sql, FALSE);
        } else  {
            // expire�� key�� ����
            $redis_login->delete($rkey);
        }
    }

    // redis instance connection�� �ݾ��ݴϴ�.
    $redis_login->close();
}

// redis key�� ������ �����ش�
function redis_key_count($keys, $rdb) {

    global $g4;

    // redis�� ����
    $redis_con = new Redis();
    $redis_con->connect($g4["rhost"], $g4["rport"]);
    $redis_con->select($rdb);

    // key ������ ���� �ݴϴ�. ���ڰ� ������... �����ϰ� ƥ����???
    $allKeys = $redis_con->keys($g4["rdomain"] . $keys);   // all keys will match this.
    $total_cnt = count($allKeys);

    // redis instance connection�� �ݾ��ݴϴ�.
    $redis_con->close();

    return $total_cnt;
}

// ���� ���ſ��θ� üũ�� �ݴϴ�
function check_memo_call() {

    global $g4, $config, $member;

    $memo_call = explode(' ' ,trim($member['mb_memo_call']));

    // �ߺ� �迭�� ����
    $memo_call = array_unique($memo_call);

    $memo_sql = "";
    for ($i=0; $i < count($memo_call); $i++) {
        if ($i == 0)
            $memo_sql .= " mb_id = '$memo_call[$i]' ";
        else
            $memo_sql .= " or mb_id = '$memo_call[$i]' ";
    }

    // �̸����� ���Ǵ� �ý����� ���
    if ($config['cf_memo_mb_name'])
        $sql = " select mb_name as mb_nick from $g4[member_table] where $memo_sql ";
    else
        $sql = " select mb_nick from $g4[member_table] where $memo_sql ";
    $result_m = sql_query($sql);

    $mb_memo_nick = "";
    while ($row = sql_fetch_array($result_m))
        $mb_memo_nick .= $row['mb_nick'] . "/";

    sql_query(" update {$g4[member_table]} set mb_memo_call = '' where mb_id = '$member[mb_id]' ");

    return $mb_memo_nick;
}

// head.sub.php���� �и��� SEO�� ���� Ű���� ���� �Լ�
function seo_keyword() {

    global $g4, $config, $bo_table, $wr_id, $write;

    // �״� SEO Ű���� - ����Ʈ�� ���ԵǴ� ž �˻�� Ű����� �и�
    $seo_tag = "";

    // �Խñۿ� �پ� �ִ� žŰ���� 5���� �־��ְ� 
    if ($bo_table && $wr_id) {
        $sql = " select tag_name, count from $g4[seo_tag_table] where bo_table = '$bo_table' and wr_id = '$wr_id' order by count desc limit 0, 5";
        $result_s = sql_query($sql);
        for ($i=0; $row = sql_fetch_array($result_s); $i++) {
            $tmp = explode(" ", $row['tag_name']);
            foreach ($tmp as $tstr) {
                if (trim($tstr) && !stristr($seo_tag, trim($tstr)))
                    $seo_tag .= $tstr . " ";
            }
        }
    }

    // ����Ʈ�� �پ� �ִ� Ű���� 5���� �־��ݴϴ�
    $sql = " select tag_name, count from $g4[seo_tag_table] where bo_table = '' and tag_name <> '' order by count desc limit 0, 5";
    $result_s = sql_query($sql);
    for ($i=0; $row = sql_fetch_array($result_s); $i++) {
        if (trim($row['tag_name'])) {
            $tmp = explode(" ", trim($row['tag_name']));
            foreach ($tmp as $tstr) {
                if (!stristr($seo_tag, trim($tstr)))
                    $seo_tag .= $tstr . " ";
            }
        }
    }

    $seo_tag = preg_replace('/\s+/', ' ', $seo_tag);  // �������� ��ĭ�� 1���� ��������
    $seo_tag = trim($seo_tag);
    if ($seo_tag !== "")
        $config['cf_meta_keywords'] = "$bo_table " . $seo_tag;

   if ($write['wr_content']) {
      $g4_description = save_me($write[wr_content]);                // ����������ȣ
      $g4_description = nl2br($g4_description);                     // �ٹٲ��� <br>��
      $g4_description = preg_replace('/\<br(\s*)?\/?\>/i', " ", $g4_description); // <br>�� ��������
      $g4_description = strip_tags($g4_description);  // ��� tag�� ���� ������
      $g4_description = preg_replace("/<(.*?)\>/"," ", $g4_description);
      $g4_description = preg_replace("/&nbsp;/"," ",$g4_description);   // &nbsp;�� ��������
      $g4_description = str_replace("&amp;", "&", $g4_description); // &amp;�� &��
      $g4_description = str_replace("&lt;", "<", $g4_description);  // &lt;�� <��
      $g4_description = str_replace("&gt;", "<", $g4_description);  // &gt;�� >��
      $g4_description = str_replace("\"", " ", $g4_description);    // "�� ��������
      $g4_description = str_replace("\'", " ", $g4_description);    // "�� ��������
      $g4_description = str_replace("\`", " ", $g4_description);    // `�� ��������
      $g4_description = str_replace(",", " ", $g4_description);     // ,�� ��������
      $g4_description = str_replace(".", " ", $g4_description);     // .�� ��������
      $g4_description = str_replace("=", " ", $g4_description);     // =�� ��������
      $g4_description = str_replace("!", " ", $g4_description);
      $g4_description = str_replace("��", " ", $g4_description);
      $g4_description = str_replace("��", " ", $g4_description);
      $g4_description = str_replace("��", " ", $g4_description);
      $g4_description = str_replace("��", " ", $g4_description);
      $g4_description = str_replace("//##", " ", $g4_description); 
      $g4_description = preg_replace('/\s+/', ' ', $g4_description);  // �������� ��ĭ�� 1���� ��������
      $g4_description = cut_str($g4_description, 250, '');  // 250���ڸ�

      $config['cf_meta_description'] = $g4_description;
  }
}

// db�� ������ �������ΰ�? �ƴѰ��� üũ.
function is_admin_check($mb_id)
{
    global $g4, $config;

    if (!$mb_id) return;

    // super ���������� Ȯ�� - super�� 1���.
    if ($config['cf_admin'] == $mb_id) return 'super';

    // �׷� ���������� Ȯ��
    $mb = sql_fetch("select gr_id from $g4[group_table] where gr_admin = '$mb_id' limit 1 ");
    if ($mb) return 'group';
    
    // �Խ��� ���������� Ȯ��
    $mb = sql_fetch("select bo_table from $g4[board_table] where bo_admin = '$mb_id' limit 1 ");
    if ($mb) return 'board';
    
    return '';
}

// wr_content�� ������ ������ Ǯ���ݴϴ� - �˾������ڿ��� �̹����� ó���� �� �ʿ��մϴ�.
function conv_content_rev($content, $writeContents_id, $wr_option)
{
    $content = preg_replace("/\&lt\;/", "<", $content);
    $content = preg_replace("/\&gt\;/", ">", $content);
    $content = preg_replace("/area/", "area onmouseover=\"over('{$writeContents_id}', 'pointer');\" onmouseout=\"out('{$writeContents_id}');\" ", $content);

		if (!strstr($wr_option, "html1")) {
		    $html = 0;
        $content = conv_content($content, $html);
    }

    // map�� area �±׸� ����� ��� �±׸� ���ش� - http://kr.php.net/manual/kr/function.strip-tags.php
    $content = strip_tags($content, "<map>,<area>"); 

    return $content;
}

// mb_nick���� ����������� ��ȸ - �������� ��ȣ�� ����
function get_member_nick($mb_nick, $fields='*') 
{ 
    global $g4; 

    $mb_nick = trim($mb_nick); 
    if (!$mb_nick) return; 

    $sql = "select $fields from $g4[member_table] where mb_nick = '$mb_nick'";
    $row = sql_fetch($sql, FALSE);

    // $row�� ����? �׷��� nick�� ���ų� �ٲ� ���̰ų�?
    if (!$row) {
        // mb_nick_table���� ������ nick���� �˻�
        $sql2 = "select * from $g4[mb_nick_table] where mb_nick = '$mb_nick'";
        $row2 = sql_fetch($sql2);

        // ���ſ� nick�� ������ �װɷ� ������ ����. ������ ���ΰű�.
        if ($row2) {
            $sql = "select $fields from $g4[member_table] where mb_id = '$row2[mb_id]'";
            $row = sql_fetch($sql, FALSE);
        }
    }

    // �׷���� ���� �ѹ� �� SQL�� - join���ٴ� 2�� ���ִ°� ĳ�� ������ �����δ� �� ������? ����
    // �׳�����, member�� ���µ�, �׷������� ������ ������ ����
    if ($row) {
        // ��� �� �Ǵ� gl_name�� ���� ����
        if ($fields=="*" || preg_match("/gl_name/",$fields)) {
            $row['gl_name'] = get_gl_name($row[mb_level]);
        }
    }

    return $row; 
}

// http://www.phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=65180
// javascript�� escape/unescape�� php���� �����ϰ�
function js_unescape($str, $chr_set='CP949') { 
	  $callback_function = create_function('$matches, $chr_set="'.$chr_set.'"', 'return iconv("UTF-16BE", $chr_set, pack("n*", hexdec($matches[1])));'); 
  	return rawurldecode(preg_replace_callback('/%u([[:alnum:]]{4})/', $callback_function, $str)); 
}

function js_escape($str, $chr_set='CP949') { 
  	$arr_dec = unpack("n*", iconv($chr_set, "UTF-16BE", $str)); 
	  $callback_function = create_function('$dec', 'if(in_array($dec, array(42, 43, 45, 46, 47, 64, 95))) return chr($dec); elseif($dec >= 127) return "%u".strtoupper(dechex($dec)); else return rawurlencode(chr($dec));'); 
  	$arr_hexcode = array_map($callback_function, $arr_dec); 
	  return implode($arr_hexcode); 
}

// ��Ʈ��Ʈ�� tooltip�� ���
function help($msg, $gly='') {

    if ($gly == "")
        $gly = "info-sign";

    $str = "<a href=\"#\" data-toggle=\"tooltip\" class=\"tooltip-help\" title=\"$msg\">
            <span class=\"glyphicon glyphicon-$gly\"></span>
            </a>";

    return $str;
}

// url���� page�� �����ϱ�
function strip_page($qstr) {
    $qstr = preg_replace('/&?page=[0-9]/i', '', $qstr);
    return $qstr;
}

// Ư���� ���ڸ� ��ȯ�ϱ�
// lastest ��� ���� �Ϳ� { ( ���� ������ 1���� ������ ĳ���� ������ �˴ϴ�.
// �׷� ���ڴ� ��� break �ؾ� �մϴ�.
function conv_latest ($str) {
    $str = str_replace('(', '&#40;', $str);    // (
    $str = str_replace(')', '&#41;', $str);    // )
    $str = str_replace('{', '&#123;', $str);   // {
    $str = str_replace('}', '&#125;', $str);   // }

    return $str;
}

// $_POST �� �Ѿ�� ĸí���� üũ
function chk_recaptcha()
{
  	global $g4;

    if (!isset($_POST['g-recaptcha-response'])) return false;

    $gg_response = trim($_POST['g-recaptcha-response']);
    if ($gg_response == "") return false;

  	$url = 'https://www.google.com/recaptcha/api/siteverify';
	  $data = array('secret' => $g4['recaptcha_secret_key'], 'response' => $gg_response, 'remoteip' => $_SERVER['REMOTE_ADDR']);

    $ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_POST, sizeof($data));
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	$result = curl_exec($ch);
	  curl_close($ch);

  	$obj = json_decode($result);

    if($obj->success == false) {
        echo '<script>alert("Google Recaptcha Error!");history.go(-1);</script>';
    }

	  return true;
}

// link�� �Ľ��Ͽ� �����ݴϴ�
function link_view($link_href, $link, $link_hit, $link_len=70) {

    global $g4;

    // parse youtube video id using preg_match
    // http://stackoverflow.com/questions/2936467/parse-youtube-video-id-using-preg-match
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $match)) {
        $video_id = strip_tags($match[1]);
        $result = "<div class='row'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' src='https://www.youtube.com/embed/". $video_id . "' frameborder='0' allowfullscreen></iframe></div></div></br>";
        return $result;
    // parse vimeo
    // https://github.com/lingtalfi/video-ids-and-thumbnails/blob/master/function.video.php
    } else if (preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*([0-9]{6,11})[?]?.*#', $link, $match)) {
        $video_id = strip_tags($match[1]);
        $result = '<iframe src="https://player.vimeo.com/video/' . $video_id . '?color=ffffff&title=0&byline=0&portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></br>';
        return $result;
    // ���� tv��
    // http://tvpot.daum.net/v/s9e2733dJRX6fJGXuRjdJxR
    } else if(preg_match('/tvpot.daum.net\/v\/([0-9a-zA-Z]+)/', $link, $match)) {
 		    $video_id = $match[1];
 		    $result = "<div class='row'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' src='http://videofarm.daum.net/controller/video/viewer/Video.html?vid=" . $video_id . "&play_loc=undefined' frameborder='0' scrolling='no' ></iframe></div></div></BR>";
        return $result;
    // sound could, embed wp�� ������ ������ ��ũ���� �Ʒ��κи� �Է��ؾ� �մϴ�. 
    // https://api.soundcloud.com/tracks/173325390
    } else if(preg_match('/https:\/\/api.soundcloud\.com\/([a-z0-9-\/]+)\/([a-z0-9-\/]+)/', $link, $match)) {
        $result = "<iframe width='100%' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/185361169&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true'></iframe></BR>";
        return $result;
    // poll�� ��� �մϴ�. poll.poll��ȣ�� ���� �Դϴ�.
    } else if(preg_match('/http:\/\/poll\-([0-9]+)/', $link, $match)) {
        $po_id = (int) $match[1];

        ob_start();
        include_once("$g4[path]/lib/poll.lib.php");
        echo poll($po_id);
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    } else {
        // ǥ�õǴ� ��ũ�� ���̰� �ʹ� ��� �ٿ��� ��
        $link = cut_str($link, $link_len);
        return "<a href='$link_href' target=_blank>{$link} ($link_hit)</a></BR>";
    }
}


// ��ǥ �ߴ��� ���θ� Ȯ�� �մϴ�
function poll_check($po_id) {

    global $g4, $is_member, $member;

    // $po_id
    $po_id = (int) $po_id;

    // $po ������ ���� �ɴϴ�.
    $po = sql_fetch(" select * from $g4[poll_table] where po_id = $po_id ");

    // ��ǥ�ߴ� ip�� �߿��� ã�ƺ���
    $search_ip = false;
    $ips = explode("\n", trim($po[po_ips]));
    for ($i=0; $i<count($ips); $i++) 
    {
        if ($_SERVER[REMOTE_ADDR] == trim($ips[$i])) 
        {
            $search_ip = true;
            break;
        }
    }

    // ��ǥ�ߴ� ȸ�����̵�� �߿��� ã�ƺ���
    $search_mb_id = false;
    if ($is_member)
    {
        $ids = explode("\n", trim($po[mb_ids]));
        for ($i=0; $i<count($ids); $i++) 
        {
            if ($member[mb_id] == trim($ids[$i])) 
            {
                $search_mb_id = true;
                break;
            }
        }
    }

    if ($search_ip || $search_mb_id || get_cookie("ck_po_id") == $po[po_id])
        return true;
    else
        return false;
}
?>