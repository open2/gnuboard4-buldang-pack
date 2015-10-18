<?
if (!defined('_GNUBOARD_')) exit;

/*************************************************************************
**
**  �Ϲ� �Լ� ����
**
*************************************************************************/

// ����ũ�� Ÿ���� ��� ��� �������� ����
function get_microtime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}


// ����������, ����������, ���������� ������ ��, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    $str = "";
    if ($cur_page > 1) {
        $str .= "<li><a href='" . $url . "1{$add}'>ó��</a></li>";
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= "<li><a href='" . $url . ($start_page-1) . "{$add}'>����</a></li>";

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= "<li><a href='$url$k{$add}'>$k</a></li>";
            else
                $str .= "<li class='active'><a href='#'>$k<span class='sr-only'>(current)</span></a></li>";
        }
    }

    if ($total_page > $end_page) $str .= "<li><a href='" . $url . ($end_page+1) . "{$add}'>����</a></li>";

    if ($cur_page < $total_page) {
        $str .= "<li><a href='$url$total_page{$add}'>�ǳ�</a></li>";
    }

    return $str;
}


// ���� �Ǵ� �迭�� �̸��� ���� ��. print_r() �Լ��� ����
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = preg_replace("/ /", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, ����; font-size:9pt;'>$str</span>");
}


// ��Ÿ�±׸� �̿��� URL �̵�
// header("location:URL") �� ��ü
function goto_url($url)
{
    global $g4;

    // �Ҵ� ���� - Ư���� iframe�� �ִ� ���, ���� iframe���� location.replace
    // �̹� �ڵ��� �Ǿ� �ִ� ���� ��ġ��� ����, $g4 ������ �̿��մϴ�.

    // ��� ��쿡���ؼ� goto_url frame�� parent�� �ϴ� �̻��� ����...��.��..
    // ��� ���α׷��� ���ؼ���, �׷� �۾��� �ϰ� �߽��ϴ�.
    if ($g4[goto_url_pgm]) {
        $use_goto = 0;
        $bo = explode(",", $g4[goto_url_pgm]);
        foreach ($bo as $pgm) {
            if (preg_match("/$pgm/", $url))
                $use_goto = 1;
        }
    } else {
        $use_goto = 0;
    }

    if ($use_goto && $g4['goto_url_parent']) {
        echo "<script type='text/javascript'> 
              var x = parent.document.getElementsByTagName('iframe');
              var t = 0;
              for (var i=0;i<x.length;i++) { if (x[i].id == '" . $g4['goto_url_parent'] . "') t = 1; }
              if (t > 0)
                  parent." . $g4[goto_url_parent] . ".location.replace('$url');
              else
                  location.replace('$url');
              </script>;";
    } else {
        echo "<script type='text/javascript'> location.replace('$url'); </script>; ";
    }
    exit;
}


// ���Ǻ��� ����
function set_session($session_name, $value)
{
    if (PHP_VERSION < '5.3.0')
        session_register($session_name);
    // PHP ������ ���̸� ���ֱ� ���� ���
    $$session_name = $_SESSION["$session_name"] = $value;
}


// ���Ǻ����� ����
function get_session($session_name)
{
    return $_SESSION[$session_name];
}


// ��Ű���� ����
/*
function set_cookie($cookie_name, $value, $expire)
{
    global $g4;

    setcookie(md5($cookie_name), base64_encode($value), $g4[server_time] + $expire, '/', $g4[cookie_domain]);
}
*/

function set_cookie($cookie_name, $value, $expire)
{
    global $g4; 
    if ($g4[https_url]) $https_cookie = true; else $https_cookie = false;

   $php_ver = explode(".", phpversion());
   if ($php_ver[0] >= 5 && $php_ver[1] >= 2) 
         setcookie(md5($cookie_name), base64_encode($value), $g4[server_time] + $expire, '/', $g4[cookie_domain], $https_cookie, true);
   else
         setcookie(md5($cookie_name), base64_encode($value), $g4[server_time] + $expire, '/', $g4[cookie_domain], $https_cookie);
}

// ��Ű������ ����
function get_cookie($cookie_name)
{
    return base64_decode($_COOKIE[md5($cookie_name)]);
}


// ���޼����� ���â����
function alert($msg='', $url='')
{
	global $g4;

    if (!$msg) $msg = '�ùٸ� ������� �̿��� �ֽʽÿ�.';

	//header("Content-Type: text/html; charset=$g4[charset]");
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$g4[charset]\">";
	echo "<script type='text/javascript'>alert('$msg');";
    if (!$url)
        echo "history.go(-1);";
    echo "</script>";
    if ($url)
        // 4.06.00 : �ҿ����� ��� �Ʒ��� �ڵ带 ����� �ν����� ����
        //echo "<meta http-equiv='refresh' content='0;url=$url'>";
        goto_url($url);
    exit;
}


// ���޼��� ����� â�� ����
function alert_close($msg)
{
	global $g4;

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$g4[charset]\">";
    echo "<script type='text/javascript'> alert('$msg'); window.close(); </script>";
    exit;
}


// way.co.kr �� wayboard ����
function url_auto_link($str)
{
    global $g4;
    global $config;

    // �ӵ� ��� 031011
    $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
    $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
    $str = preg_replace("/&amp;/", "&", $str);
    $str = preg_replace("/&quot;/", "\"", $str);
    $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
    $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='$config[cf_link_target]'>\\2</A>", $str);
    //$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config[cf_link_target]'>\\2</A>", $str);
    // 100825 : () �߰�
    // 120315 : CHARSET �� ���� ��ũ�� ���� �߸� ������ �־� ����
    if (strtoupper($g4['charset']) == 'UTF-8') {
        $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[��-�R\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config[cf_link_target]'>\\2</A>", $str);
    } else {
        $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config[cf_link_target]'>\\2</A>", $str);
    }
    // �̸��� ����ǥ���� ���� 061004
    //$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
    $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
    $str = preg_replace("/\t_gt_\t/", "&gt;", $str);

    return $str;
}


// url�� http:// �� ���δ�
function set_http($url)
{
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
        $url = "http://" . $url;

    return $url;
}


// ������ �뷮�� ���Ѵ�.
//function get_filesize($file)
function get_filesize($size)
{
    //$size = @filesize(addslashes($file));
    if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}


// �Խñۿ� ÷�ε� ������ ��´�. (�迭�� ��ȯ)
function get_file($bo_table, $wr_id)
{
    global $g4, $qstr;

    $file['count'] = 0;
    $sql_select = " bf_no, bf_download, bf_filesize, bf_file, bf_datetime, bf_width, bf_height, bf_type, bf_source, bf_content ";
    $sql = " select $sql_select from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' order by bf_no ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $no = $row[bf_no];
        $file[$no][href] = "./download.php?bo_table=$bo_table&wr_id=$wr_id&no=$no" . $qstr;
        $file[$no][download] = $row[bf_download];
        // 4.00.11 - ���� path �߰�
        $file[$no][path] = "$g4[data_path]/file/$bo_table";
        //$file[$no][size] = get_filesize("{$file[$no][path]}/$row[bf_file]");
        $file[$no][size] = get_filesize($row[bf_filesize]);
        //$file[$no][datetime] = date("Y-m-d H:i:s", @filemtime("$g4[data_path]/file/$bo_table/$row[bf_file]"));
        $file[$no][datetime] = $row[bf_datetime];
        $file[$no][source] = addslashes($row[bf_source]);
        $file[$no][bf_content] = $row[bf_content];
        $file[$no][content] = get_text($row[bf_content]);
        //$file[$no][view] = view_file_link($row[bf_file], $file[$no][content]);
        $file[$no][view] = view_file_link($row[bf_file], $row[bf_width], $row[bf_height], $file[$no][content]);
        $file[$no][file] = $row[bf_file];
        // prosper �� ����
        //$file[$no][imgsize] = @getimagesize("{$file[$no][path]}/$row[bf_file]");
        $file[$no][image_width] = $row[bf_width] ? $row[bf_width] : 640;
        $file[$no][image_height] = $row[bf_height] ? $row[bf_height] : 480;
        $file[$no][image_type] = $row[bf_type];
        $file["count"]++;
    }

    return $file;
}


// ������ �뷮 ($dir�� / ���� �ѱ⼼��)
function get_dirsize($dir)
{
    $size = 0;
    $d = dir($dir);
    while ($entry = $d->read()) {
        if ($entry != "." && $entry != "..") {
            $size += filesize("$dir/$entry");
        }
    }
    $d->close();
    return $size;
}


/*************************************************************************
**
**  �״����� ���� �Լ� ����
**
*************************************************************************/


// �Խù� ����($write_row)�� ����ϱ� ���Ͽ� $list�� ������ ������ ���� �� ����
function get_list($write_row, $board, $skin_path, $subject_len=40, $gallery_view=0)
{
    global $g4, $config;
    global $qstr, $page;
    global $mstr;
    global $member;

    //$t = get_microtime();

    // �迭��ü�� ����
    $list = $write_row;
    unset($write_row);

    // ����������ȣ
    if ($member['mb_id'] == "")
        $list['wr_content'] = save_me($list['wr_content']);

    $list['is_notice'] = preg_match("/[^0-9]{0,1}{$list['wr_id']}[\r]{0,1}/",$board['bo_notice']);

    //�Ҵ��� - ���񿡼� Ư������ ��~ ���ֱ�
    if ($g4['remove_special_chars']) {
        $list['wr_subject'] = remove_special_chars ($list['wr_subject']);
    }

    if ($subject_len)
        $list['subject'] = conv_subject($list['wr_subject'], $subject_len, "��");
    else
        $list['subject'] = conv_subject($list['wr_subject'], $board['bo_subject_len'], "��");

    // ��Ͽ��� ���� �̸����� ����� �Խ��Ǹ� ������ ��ȯ�� (�ӵ� ���) : kkal3(Ŀ��)�Բ��� �˷��ּ̽��ϴ�.
    if ($board['bo_use_list_content'])
	{
		$html = 0;
		if (strstr($list['wr_option'], "html1"))
			$html = 1;
		else if (strstr($list['wr_option'], "html2"))
			$html = 2;

        $list['content'] = conv_content($list['wr_content'], $html);
	}

    // $board[bo_new] �ð����� ���ο� �ڸ�Ʈ�� ������ �ڸ�Ʈ ������ ����
    $list['comment_cnt'] = "";
    if ($list['wr_comment']) {
        if ($list['wr_last'] >= date("Y-m-d H:i:s", $g4['server_time'] - ($board['bo_new'] * 3600)))
            $list['comment_cnt'] = "<b>($list[wr_comment])</b>";
        else
            $list['comment_cnt'] = "($list[wr_comment])"; 
    }

    // ������ ��� �ð����� ǥ���� (����. b4.lib.php�� get_datetime �Լ�)
    $list['datetime'] = substr($list['wr_datetime'],0,10);
    $list['datetime2'] = get_datetime($list['wr_datetime']);

    // 4.1
    $list['last'] = substr($list['wr_last'],0,10);
    $list['last2'] = get_datetime($list['wr_last']);

    $list['wr_homepage'] = get_text(addslashes($list['wr_homepage']));

    // ���̵�� ������ ��� �մϴ� - �Ҵ��� / ��Ʈ��Ʈ��
    // �۾����� ���������� �ٲ� ���� ���Ŀ� ������ ���̶�� ���������� �ٽ� ������ �ʿ䰡 ������
    // ���� �۾����� ������ �ٲ������, �����͸� ������ ������ SQL Query�� �ؾ��� �ؼ� �׳� ���� �� �����ϴ�.
    if ($list['mb_id']) {
        $mb = get_member($list['mb_id'], "mb_nick, mb_name");
        if ($board[bo_use_name])
            $tmp_name = $mb[mb_name];
        else
            $tmp_name = $mb[mb_nick];
        $list['name'] = get_sideview($list['mb_id'], $tmp_name);
    } else {
        $tmp_name = get_text(cut_str($list['wr_name'], $config['cf_cut_name'])); // ������ �ڸ��� ��ŭ�� �̸� ���
        $list['name'] = "<span>$tmp_name</span>";
    }
    $reply = $list['wr_reply'];

    $list['reply'] = "";
    if (strlen($reply) > 0)
    {
        for ($k=0; $k<strlen($reply); $k++)
            $list['reply'] .= ' &nbsp;&nbsp; ';
    }

    $list['icon_reply'] = "";
    if ($list['reply'])
        $list['icon_reply'] = "<img src='$skin_path/img/icon_reply.gif' align='absmiddle' alt='reply'>";

    $list['icon_link'] = "";
    if ($list['wr_link1'] || $list['wr_link2'])
        $list['icon_link'] = "<img src='$skin_path/img/icon_link.gif' align='absmiddle' alt='link'>";

    // �з��� ��ũ
    $list['ca_name_href'] = "$g4[bbs_path]/board.php?bo_table=$board[bo_table]&sca=".urlencode($list['ca_name']);

    $list['href'] = "$g4[bbs_path]/board.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]" . $qstr . $mstr;
    //$list['href'] = "$g4[bbs_path]/board.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]";
    if ($board['bo_use_comment'])
        $list['comment_href'] = "javascript:win_comment('$g4[bbs_path]/board.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]&cwin=1');";
    else
        $list['comment_href'] = $list['href'];

    $list['icon_new'] = "";
    if ($list['wr_datetime'] >= date("Y-m-d H:i:s", $g4['server_time'] - ($board['bo_new'] * 3600)))
        $list['icon_new'] = "<img src='$skin_path/img/icon_new.gif' align='absmiddle' alt='new'>";

    $list['icon_hot'] = "";
    if ($list['wr_hit'] >= $board['bo_hot'])
        $list['icon_hot'] = "<img src='$skin_path/img/icon_hot.gif' align='absmiddle' alt='hot'>";

    $list['icon_secret'] = "";
    if (strstr($list['wr_option'], "secret"))
        $list['icon_secret'] = "<img src='$skin_path/img/icon_secret.gif' align='absmiddle' alt='secret'>";

    // ��ũ
    for ($i=1; $i<=$g4['link_count']; $i++)
    {
        $list['link'][$i] = set_http(get_text($list["wr_link{$i}"]));
        $list['link_href'][$i] = "$g4[bbs_path]/link.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]&no=$i" . $qstr;
        $list['link_hit'][$i] = (int)$list["wr_link{$i}_hit"];
    }

    // ���� ���� - get_file �Լ��� ÷������ ������ 0 �̻��� ���� �����Ѵ�
    if ($list['wr_file_count'] > 0) {
        // �Ҵ��� - ������ �ɼǿ� check �Ǿ������� ���� ������ �����´�
        if ($board['bo_gallery'] || $gallery_view)
            $list['file'] = get_file($board['bo_table'], $list['wr_id']);
        else
            $list['file']['count'] = $list['wr_file_count'];

        if ($list['file']['count'])
            $list['icon_file'] = "<img src='$skin_path/img/icon_file.gif' align='absmiddle'>";
    }

    // �Ҵ��� - �Խ��� �⺻ ������ �迭�� ����
    $list['bo_table'] = $board['bo_table'];
    $list['bo_subject'] = $board['bo_bo_subject'];

    return $list;
}

// get_list �� alias
function get_view($write_row, $board, $skin_path, $subject_len=125, $gallery_view=1)
{
    return get_list($write_row, $board, $skin_path, $subject_len, $gallery_view);
}


// set_search_font(), get_search_font() �Լ��� search_font() �Լ��� ��ü
function search_font($stx, $str)
{
    global $config;

    // ���ھտ� \ �� ���Դϴ�.
    $src = array("/", "|");
    $dst = array("\/", "\|");

    if (!trim($stx)) return $str;

    // �˻��� ��ü�� �������� ������
    $s = explode(" ", $stx);

    // "/(�˻�1|�˻�2)/i" �� ���� ������ ����
    $pattern = "";
    $bar = "";
    for ($m=0; $m<count($s); $m++) {
        if (trim($s[$m]) == "") continue;
        // �±״� �������� �ʾƾ� �ϴµ� �� �ȵǴ±�. �Ѥ�a
        //$pattern .= $bar . '([^<])(' . quotemeta($s[$m]) . ')';
        //$pattern .= $bar . quotemeta($s[$m]);
        //$pattern .= $bar . str_replace("/", "\/", quotemeta($s[$m]));
        $tmp_str = quotemeta($s[$m]);
        $tmp_str = str_replace($src, $dst, $tmp_str);
        $pattern .= $bar . $tmp_str . "(?![^<]*>)";
        $bar = "|";
    }

    // ������ �˻� ��Ʈ�� ����, ���������� ��ü
    $replace = "<span style='background-color:$config[cf_search_bgcolor]; color:$config[cf_search_color];'>\\1</span>";

    return preg_replace("/($pattern)/i", $replace, $str);
}


// ������ ��ȯ
function conv_subject($subject, $len, $suffix="")
{
    return cut_str(get_text($subject), $len, $suffix);
}

// OBJECT �±��� XSS ����
function bad120422($matches)
{
    $tag  = $matches[1];
    $code = $matches[2];
    if (preg_match("#\bscript\b#i", $code)) {
        return "$tag �±׿� ��ũ��Ʈ�� ��� �Ұ��մϴ�.";
    } else if (preg_match("#\bbase64\b#i", $code)) {
        return "$tag �±׿� BASE64�� ��� �Ұ��մϴ�.";
    }
    return $matches[0];
}

// tag ���� �ּ��� ��ȿȭ �ϱ�
function bad130128($matches)
{
    $str = $matches[2];
    return '<'.$matches[1].preg_replace('#(\/\*|\*\/)#', '', $str).'>';
}

// ������ ��ȯ
function conv_content($content, $html)
{
    global $config, $board;

    if ($html)
    {
        $source = array();
        $target = array();

        $source[] = "//";
        $target[] = "";

        // �Ҵ���, ms�� xml �±� ����
        $source[] = "/<\?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" \/>/";
        $target[] = "";

        if ($html == 2) { // �ڵ� �ٹٲ�
            $source[] = "/\n/";
            $target[] = "<br/>";
        }

        // ���̺� �±��� ������ ���� ���̺��� ������ �ʵ��� �Ѵ�.
        $table_begin_count = substr_count(strtolower($content), "<table");
        $table_end_count = substr_count(strtolower($content), "</table");
        for ($i=$table_end_count; $i<$table_begin_count; $i++)
        {
            $content .= "</table>";
        }

        $content = preg_replace_callback("/<([^>]+)>/s", 'bad130128', $content); 

        $content = preg_replace($source, $target, $content);

        // XSS (Cross Site Script) ����
        // �Ϻ��� XSS ������ ����.
        
        // �̷� ��츦 ������ <IMG STYLE="xss:expr/*XSS*/ession(alert('XSS'))">
        //$content = preg_replace("#\/\*.*\*\/#iU", "", $content);
        // ���� ���Խ��� �Ʒ��� ���� ������ �����Ű�Ƿ� not greedy(��Ž�������?) �ɼ��� ������. ignore case �ɼǵ� �ʿ� �����Ƿ� ����
        // <IMG STYLE="xss:ex//*XSS*/**/pression(alert('XSS'))"></IMG>
        $content = preg_replace("#\/\*.*\*\/#", "", $content);

        // object, embed �±׿��� javascript �ڵ� ����
        $content = preg_replace_callback("#<(object|embed)([^>]+)>#i", "bad120422", $content);

        $content = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $content);
        $content = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $content);
        $content = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $content);
        //$content = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $content);
        $content = preg_replace_callback("#<([^>]+)#", create_function('$m', 'return "<".str_replace("<", "&lt;", $m[1]);'), $content);
        //$content = preg_replace("/\<(\w|\s|\?)*(xml)/i", "", $content);
        $content = preg_replace("/\<(\w|\s|\?)*(xml)/i", "_$1$2_", $content);

        // �÷����� �׼ǽ�ũ��Ʈ�� �ڹٽ�ũ��Ʈ�� ������ �����Ͽ� �������� ����Ʈ���� �̵��� ���´�.
        // value="always" �� value="never" ��, allowScriptaccess="always" �� allowScriptaccess="never" �� ��ȯ�ϴµ� ������ �ִ�.
        //$content = preg_replace("/((?<=\<param|\<embed)[^>]+)(\s*=\s*[\'\"]?)always([\'\"]?)([^>]+(?=\>))/i", "$1$2never$3$4", $content);
        // allowscript �Ӽ��� param �±׸� �����Ѵ�.
        //$content = preg_replace("#(<param.*?allowscript[^>]+>)(<\/param>)?#i", "", $content);
        $content = preg_replace("#<param[^>]+AllowScriptAccess[^>]+>(<\/param>)?#i", "", $content);
        // embed �±��� allowscript �Ӽ��� �����Ѵ�.
        //$content = preg_replace("#(<embed.*?)(allowscriptaccess[^\s\>]+)#i", "$1", $content);
        $content = preg_replace("#(<embed[^>]+)(allowscriptaccess[^\s\>]+)#i", "$1", $content);
        // object �±׿� allowscript �� ���� never �� �Ͽ� �±׸� �߰��Ѵ�.
        $content = preg_replace("#(<object[^>]+>)#i", "$1<param name=\"allowscriptaccess\" value=\"never\">", $content);
        // embed �±׿� allowscrpt ���� never �� �Ͽ� �Ӽ��� �߰��Ѵ�.
        $content = preg_replace("#(<embed[^>]+)#i", "$1 allowscriptaccess=\"never\"", $content);

        // �̹��� �±��� src �Ӽ��� �������� ��ũ�� �ִ� ��� �Խù��� Ȯ���ϴ� �͸����ε� �������� �������� �����ϹǷ� �̰��� ����
        $content = preg_replace("/<(img[^>]+delete\.php[^>]+bo_table[^>]+)/i", "*** CSRF ���� : &lt;$1", $content);
        $content = preg_replace("/<(img[^>]+delete_comment\.php[^>]+bo_table[^>]+)/i", "*** CSRF ���� : &lt;$1", $content);
        $content = preg_replace("/<(img[^>]+logout\.php[^>]+)/i", "*** CSRF ���� : &lt;$1", $content);
        $content = preg_replace("/<(img[^>]+download\.php[^>]+bo_table[^>]+)/i", "*** CSRF ���� : &lt;$1", $content);

        $content = preg_replace_callback("#style\s*=\s*[\"\']?[^\"\']+[\"\']?#i",
                    create_function('$matches', 'return str_replace("\\\\", "", stripslashes($matches[0]));'), $content);

        $pattern = "";
        $pattern .= "(e|&#(x65|101);?)";
        $pattern .= "(x|&#(x78|120);?)";
        $pattern .= "(p|&#(x70|112);?)";
        $pattern .= "(r|&#(x72|114);?)";
        $pattern .= "(e|&#(x65|101);?)";
        $pattern .= "(s|&#(x73|115);?)";
        $pattern .= "(s|&#(x73|115);?)";
        //$pattern .= "(i|&#(x6a|105);?)";
        $pattern .= "(i|&#(x69|105);?)";
        $pattern .= "(o|&#(x6f|111);?)";
        $pattern .= "(n|&#(x6e|110);?)";
        //$content = preg_replace("/".$pattern."/i", "__EXPRESSION__", $content);
        $content = preg_replace("/<[^>]*".$pattern."/i", "__EXPRESSION__", $content); 
        // <IMG STYLE="xss:e\xpression(alert('XSS'))"></IMG> �� ���� �ڵ忡 ������� �־� ������. 121213
        $content = preg_replace("/(?<=style)(\s*=\s*[\"\']?xss\:)/i", '="__XSS__', $content); 
        $content = bad_tag_convert($content);
    }
    else // text �̸�
    {
        // & ó�� : &amp; &nbsp; ���� �ڵ带 ���� �����
        $content = html_symbol($content);

        // ���� ó��
		//$content = preg_replace("/  /", "&nbsp; ", $content);
		$content = str_replace("  ", "&nbsp; ", $content);
		$content = str_replace("\n ", "\n&nbsp;", $content);

        $content = get_text($content, 1);

        $content = url_auto_link($content);
    }

    return $content;
}


// �˻� ������ ��´�.
function get_sql_search($search_ca_name, $search_field, $search_text, $search_operator='and', $bo_table='')
{
    global $g4, $member;

    $str = "";
    if ($search_ca_name)
        $str = " ca_name = '$search_ca_name' ";

    $search_text = strip_tags(($search_text));
    $search_text = trim(stripslashes($search_text));

    if (!$search_text) {
        if ($search_ca_name) {
            return $str;
        } else {
            return '0';
        }
    }

    if (!trim($search_field)) 
        $search_field = "wr_subject"; 

    if ($str)
        $str .= " and ";

    // ������ �ӵ��� ���̱� ���Ͽ� ( ) �� �ּ�ȭ �Ѵ�.
    $op1 = "";

    // �˻�� �����ڷ� ������. ���⼭�� ����
    $s = array();
    $s = explode(" ", $search_text);

    // �˻��ʵ带 �����ڷ� ������. ���⼭�� +
    $tmp = array();
    $tmp = explode(",", trim($search_field));
    $field = explode("||", $tmp[0]);
    $not_comment = $tmp[1];

    $str .= "(";
    for ($i=0; $i<count($s); $i++) {
        // �˻���
        $search_str = trim($s[$i]);
        if ($search_str == "") continue;

        // �α�˻���
        $sql = " insert into $g4[popular_table] set pp_word = '$search_str', pp_date = '$g4[time_ymd]', pp_ip = '$_SERVER[REMOTE_ADDR]', bo_table='$bo_table', mb_id = '$member[mb_id]', sfl = '$search_field' ";
        sql_query($sql, FALSE);
        $pp_id = mysql_insert_id();
        
        // �α�˻��� sum - �űԷ� �� ����, �ȵ���? �׳� �������� �Ǵ°ž�. ����
        if ($pp_id) {
            // �Խ����� �ִ� ���
            if ($bo_table)
                $bo_sql = " and bo_table='$bo_table' ";
            else
                $bo_sql = " and bo_table='' ";

            // sum ���̺��� ������Ʈ
            $sql = " update $g4[popular_sum_table] set pp_count=pp_count+1 where pp_date='$g4[time_ymd]' and pp_word='$search_str' $bo_sql ";
            sql_query($sql, FALSE);

            // sum ���̺��� ������ insert�� �մϴ�. �̶� �˻�� mb_id, mb_name���� üũ ���ݴϴ�.
            if ( $pp_id && mysql_affected_rows() == 0 ) {
                // ���̵�� ���� ���� ���ϴ�. �̸��� �˻��� �� �� ������ �����ϱ���.
                $sql = " select count(*) as cnt from $g4[member_table] where mb_id = '$search_str' or mb_nick = '$search_str' ";
                $result4 = sql_fetch($sql);
                if ($result4['cnt'])
                    $sql_mbinfo = ", mb_info='1' ";
                else
                    $sql_mbinfo = "";

                // �˻���� ���͸��� ���� ������ �����ɴϴ�.
                $sql3 = " select pp_level from $g4[filter_table] where pp_word = '$search_str' ";
                $result3 = sql_fetch($sql3);
                $pp_level = $result3[pp_level];

                $sql = " insert into $g4[popular_sum_table] set pp_word = '$search_str', pp_date = '$g4[time_ymd]', bo_table='$bo_table', pp_count='1', pp_level='$pp_level' $sql_mbinfo ";
                sql_query($sql, FALSE);
            }
        }

        $str .= $op1;
        $str .= "(";

        $op2 = "";
        for ($k=0; $k<count($field); $k++) { // �ʵ��� ����ŭ ���� �ʵ� �˻� ���� (�ʵ�1+�ʵ�2...)

            // SQL Injection ����
            // �ʵ尪�� a-z A-Z 0-9 _ , | �̿��� ���� �ִٸ� �˻��ʵ带 wr_subject �� �����Ѵ�.
            $field[$k] = preg_match("/^[\w\,\|]+$/", $field[$k]) ? $field[$k] : "wr_subject";

            $str .= $op2;
            switch ($field[$k]) {
                case "mb_id" :
                case "wr_name" :
                    $str .= " $field[$k] = '$s[$i]' ";
                    break;
                case "wr_hit" :
                case "wr_good" :
                case "wr_nogood" :
                    $str .= " $field[$k] >= '$s[$i]' ";
                    break;
                case "wr_hit_down" :
                case "wr_good_down" :
                case "wr_nogood_down" :
                    $str .= SUBSTR($field[$k], 0, STRLEN($field[$k])-5) . " < '$s[$i]' ";
                    break;
                case "wr_1_up" :
                case "wr_2_up" :
                case "wr_3_up" :
                case "wr_4_up" :
                case "wr_5_up" :
                case "wr_6_up" :
                case "wr_7_up" :
                case "wr_8_up" :
                case "wr_0_up" :
                case "wr_10_up" :
                    $str .= " CAST( " . SUBSTR($field[$k], 0, STRLEN($field[$k])-3) . " as UNSIGNED) >= '$s[$i]' ";
                    break;
                case "wr_1_down" :
                case "wr_2_down" :
                case "wr_3_down" :
                case "wr_4_down" :
                case "wr_5_down" :
                case "wr_6_down" :
                case "wr_7_down" :
                case "wr_8_down" :
                case "wr_9_down" :
                case "wr_10_down" :
                    $str .= " CAST( ". SUBSTR($field[$k], 0, STRLEN($field[$k])-5) . " as UNSIGNED < '$s[$i]' ";
                    break;
                // ��ȣ�� �ش� �˻�� -1 �� ����
                case "wr_num" :
                    $str .= "$field[$k] = ".((-1)*$s[$i]);
                    break;
                case "wr_ip" :
                case "wr_password" :
                    $str .= "1=0"; // �׻� ����
                    break;
                // LIKE ���� INSTR �ӵ��� ����
                default :
                    if (preg_match("/[a-zA-Z]/", $search_str))
                        $str .= "INSTR(LOWER($field[$k]), LOWER('$search_str'))>0";
                    else
                        $str .= "INSTR($field[$k], '$search_str')>0";
                    break;
            }
            $op2 = " or ";
        }
        $str .= ")";

        $op1 = " $search_operator ";
    }
    $str .= " ) ";
    if ($not_comment)
        $str .= " and wr_is_comment = '0' ";

    return $str;
}


// �Խ��� ���̺��� �ϳ��� ���� ����
function get_write($write_table, $wr_id)
{
    return sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");
}


// �Խ����� ������ ��ȣ�� ��´�.
function get_next_num($table)
{
    // ���� ���� ��ȣ�� ���
    $sql = " select min(wr_num) as min_wr_num from $table ";
    $row = sql_fetch($sql);
    // ���� ���� ��ȣ�� 1�� ���� �Ѱ���
    return (int)($row[min_wr_num] - 1);
}


// �׷� ���� ���̺��� �ϳ��� ���� ����
function get_group($gr_id, $fields='*')
{
    global $g4;

    return sql_fetch(" select $fields from $g4[group_table] where gr_id = '$gr_id' ");
}


// ȸ�� ������ ��´�.
function get_member($mb_id, $fields='*') 
{ 
    global $g4; 

    $mb_id = trim($mb_id); 
    if (!$mb_id) return; 

    $sql = "select $fields from $g4[member_table] where mb_id = '$mb_id'";
    $row = sql_fetch($sql, FALSE);
    if (!$row) {
        $sql = "select * from $g4[member_table] where mb_id = '$mb_id'";
        $row = sql_fetch($sql);
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

// ��¥, ��ȸ���� ��� ���� ������� �������� �ϹǷ� $flag �� �߰�
// $flag : asc ���� ���� , desc ���� ����
// ���񺰷� �÷� �����ϴ� QUERY STRING
function subject_sort_link($col, $query_string='', $flag='asc')
{
    global $sst, $sod, $sfl, $stx, $page;

    $q1 = "sst=$col";
    if ($flag == 'asc')
    {
        $q2 = 'sod=asc';
        if ($sst == $col)
        {
            if ($sod == 'asc')
            {
                $q2 = 'sod=desc';
            }
        }
    }
    else
    {
        $q2 = 'sod=desc';
        if ($sst == $col)
        {
            if ($sod == 'desc')
            {
                $q2 = 'sod=asc';
            }
        }
    }

    return "<a href='$_SERVER[PHP_SELF]?$query_string&$q1&$q2&sfl=$sfl&stx=$stx&page=$page'>";
}


// ������ ������ ����
function get_admin($admin='super')
{
    global $config, $group, $board;
    global $g4;

    $is = false;
    if ($admin == 'board') {
        $mb = sql_fetch("select * from $g4[member_table] where mb_id in ('$board[bo_admin]') limit 1 ");
        $is = true;
    }

    if (($is && !$mb[mb_id]) || $admin == 'group') {
        $mb = sql_fetch("select * from $g4[member_table] where mb_id in ('$group[gr_admin]') limit 1 ");
        $is = true;
    }

    if (($is && !$mb[mb_id]) || $admin == 'super') {
        $mb = sql_fetch("select * from $g4[member_table] where mb_id in ('$config[cf_admin]') limit 1 ");
    }

    return $mb;
}


// �������ΰ�?
function is_admin($mb_id)
{
    global $config, $group, $board;

    if (!$mb_id) return;

    if ($config['cf_admin'] == $mb_id) return 'super';
    if ($group['gr_admin'] == $mb_id) return 'group';
    if ($board['bo_admin'] == $mb_id) return 'board';
    return '';
}


// �з� �ɼ��� ����
// 4.00 ������ ī�װ� ���̺��� ���ְ� �������̺� �ִ� �������� ��ü
function get_category_option($bo_table='')
{
    global $g4, $board;

    /*
    $sql = " select bo_category_list from $g4[board_table] where bo_table = '$bo_table' ";
    $row = sql_fetch($sql);
    $arr = explode("|", $row[bo_category_list]); // �����ڰ� , �� �Ǿ� ����
    */
    $arr = explode("|", $board[bo_category_list]); // �����ڰ� , �� �Ǿ� ����
    $str = "";
    for ($i=0; $i<count($arr); $i++)
        if (trim($arr[$i]))
            $str .= "<option value='$arr[$i]'>$arr[$i]</option>\n";

    return $str;
}


// �Խ��� �׷��� SELECT �������� ����
function get_group_select($name, $selected='', $event='')
{
    global $g4, $is_admin, $member;

    $sql = " select gr_id, gr_subject from $g4[group_table] a ";
    if ($is_admin == "group") {
        $sql .= " left join $g4[member_table] b on (b.mb_id = a.gr_admin)
                  where b.mb_id = '$member[mb_id]' ";
    }
    $sql .= " order by a.gr_id ";

    $result = sql_query($sql);
    $str = "<select name='$name' $event>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $str .= "<option value='$row[gr_id]'";
        if ($row[gr_id] == $selected) $str .= " selected";
        $str .= ">$row[gr_subject] ($row[gr_id])</option>";
    }
    $str .= "</select>";
    return $str;
}


// '��', '�ƴϿ�'�� SELECT �������� ����
function get_yn_select($name, $selected='1', $event='')
{
    $str = "<select name='$name' $event>";
    if ($selected) {
        $str .= "<option value='1' selected>��</option>";
        $str .= "<option value='0'>�ƴϿ�</option>";
    } else {
        $str .= "<option value='1'>��</option>";
        $str .= "<option value='0' selected>�ƴϿ�</option>";
    }
    $str .= "</select>";
    return $str;
}


// ����Ʈ �ο�
function insert_point($mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='')
{
    global $config;
    global $g4;
    global $is_admin;

    // ����Ʈ ����� ���� �ʴ´ٸ� return
    if (!$config[cf_use_point]) { return 0; }

    // ����Ʈ�� ���ٸ� ������Ʈ �� �ʿ� ����
    if ($point == 0) { return 0; }

    // ȸ�����̵� ���ٸ� ������Ʈ �� �ʿ� ����
    if ($mb_id == "") { return 0; }
    $mb = sql_fetch(" select mb_id from $g4[member_table] where mb_id = '$mb_id' ");
    if (!$mb[mb_id]) { return 0; }

    // �̹� ��ϵ� �����̶�� �ǳʶ�
    if ($rel_table || $rel_id || $rel_action)
    {
        if ($rel_table == "@login") {} else { // �α������̺��� ��쿡�� ��ϵ� ���� Ȯ���� ����

        $sql = " select count(*) as cnt from $g4[point_table]
                  where mb_id = '$mb_id'
                    and po_rel_table = '$rel_table'
                    and po_rel_id = '$rel_id'
                    and po_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);
        if ($row[cnt])
            return -1;
        }
    }

    // ����Ʈ �Ǻ� ����
    $sql = " insert into $g4[point_table]
                set mb_id = '$mb_id',
                    po_datetime = '$g4[time_ymdhis]',
                    po_content = '".addslashes($content)."',
                    po_point = '$point',
                    po_rel_table = '$rel_table',
                    po_rel_id = '$rel_id',
                    po_rel_action = '$rel_action' ";
    sql_query($sql);

    // ����Ʈ ������ ���� ���ϰ�
    $sql = " select sum(po_point) as sum_po_point from $g4[point_table] where mb_id = '$mb_id' ";
    $row = sql_fetch($sql);
    $sum_point = $row[sum_po_point];

    // ����Ʈ UPDATE
    $sql = " update $g4[member_table] set mb_point = '$sum_point' where mb_id = '$mb_id' ";
    sql_query($sql);

    return 1;
}

// ����Ʈ ����
function delete_point($mb_id, $rel_table, $rel_id, $rel_action)
{
    global $g4;

    $result = false;
    if ($rel_table || $rel_id || $rel_action)
    {
        $result = sql_query(" delete from $g4[point_table]
                     where mb_id = '$mb_id'
                       and po_rel_table = '$rel_table'
                       and po_rel_id = '$rel_id'
                       and po_rel_action = '$rel_action' ", false);

        // ����Ʈ ������ ���� ���ϰ�
        $sql = " select sum(po_point) as sum_po_point from $g4[point_table] where mb_id = '$mb_id' ";
        $row = sql_fetch($sql);
        $sum_point = $row[sum_po_point];

        // ����Ʈ UPDATE
        $sql = " update $g4[member_table] set mb_point = '$sum_point' where mb_id = '$mb_id' ";
        $result = sql_query($sql);
    }

    return $result;
}

// ȸ�� ���̾�
function get_sideview($mb_id, $name="", $email="", $homepage="")
{
    global $config, $g4, $member, $board;

    $tmp_name = get_text(cut_str($name, $config['cf_cut_name'])); // ������ �ڸ��� ��ŭ�� �̸� ���

    if ($mb_id) {

        if ($config['cf_use_member_icon']) {
            $mb_dir = substr($mb_id,0,2);
            $icon_file = "$g4[data_path]/member/$mb_dir/$mb_id.gif";

            // �Ҵ��� - ������ size�� ������(��:22*22) ���� ���� ���� Ȯ����ϰ� �״�� ǥ��
            $size = @getimagesize($icon_file);
            if ($size) {
                $width = $size[0];
                $height = $size[1];

                if ($width > $config['cf_member_icon_width']) 
                {
                    $width = $config['cf_member_icon_width'];
                    $height = $size[1] * ($config['cf_member_icon_width'] / $size[0]);
                } else if ($height > $config['cf_member_icon_height']) {
                    $height = $config['cf_member_icon_height'];
                    $width = $size[0] * ($config['cf_member_icon_height'] / $size[1]);
                }

                $tmp_name = "<img src='$icon_file' width='$width' height='$height' align='absmiddle' alt='' border='0'> ";

                if ($config['cf_use_member_icon'] == 2) // ȸ��������+�̸�
                    $tmp_name = $tmp_name . $name;
            }
        }
        $title_mb_id = $name;
    } else {
        $title_mb_id = "[��ȸ��]";
    }

    $tmp_name = "<a class=\"sideview\" alt=\"$title_mb_id\" style=\"cursor:pointer;\" role=\"button\" tabindex=\"0\" data-toggle=\"popover\" data-trigger=\"focus\">$tmp_name</a>";
    return $tmp_name;

}


// ������ ���̰� �ϴ� ��ũ (�̹���, �÷���, ������)
function view_file_link($file, $width, $height, $content="")
{
    global $config, $board;
    global $g4;
    static $ids;

    if (!$file) return;

    $ids++;

    // �Ҵ��� : ������ �̹��� ũ�⸦ �����Ѵ�
    $mw_width = $width;
    $mw_height = $height;
    
    // ������ ���� �Խ��Ǽ����� �̹����� ���� ũ�ٸ� �Խ��Ǽ��� ������ ���߰� ������ ���� ���̸� ���
    if ($width > $board[bo_image_width] && $board[bo_image_width])
    {
        $rate = $board[bo_image_width] / $width;
        $width = $board[bo_image_width];
        $height = (int)($height * $rate);
    }

    // ���� �ִ� ��� ���� ������ �Ӽ��� �ְ�, ������ �ڵ� ���ǵ��� �ڵ带 ������ �ʴ´�.
    if ($width)
        $attr = " width='$width' height='$height' ";
    else
        $attr = "";

    if (preg_match("/\.($config[cf_image_extension])$/i", $file))
        {
        //�Ҵ��� : �����̹����� ���� image_window2�� ���, width���ְ� height�� �������� ���� -> skin���� ó���ϵ��� ����
        //return "<img src='$g4[data_path]/file/$board[bo_table]/".urlencode($file)."' name='target_resize_image[]' onclick='image_window2(this, $mw_width, $mw_height);' width='$width' style='cursor:pointer;' title='$content'>";
        //return resize_content("<img src='$g4[data_path]/file/$board[bo_table]/".urlencode($file)."' name='target_resize_image[]' onclick='image_window2(this, $mw_width, $mw_height);' style='cursor:pointer;' title='$content'>");

        // �̹����� �Ӽ��� ���� �ʴ� ������ �̹��� Ŭ���� ���� �̹����� �����ֱ� ���Ѱ���
        // �Խ��Ǽ��� �̹������� ũ�ٸ� ��Ų�� �ڹٽ�ũ��Ʈ���� �̹����� �ٿ��ش�
        //return "<img src='$g4[data_path]/file/$board[bo_table]/".urlencode($file)."' name='target_resize_image[]' onclick='image_window(this);' style='cursor:pointer;' title='$content'>";

        // $file�� ���丮�� ��� �ִ� ���, ������ �ذ��ؾ���. - �Ҵ���
        $tmp = explode("/", $file);
        $encode_url = "";
        foreach ($tmp as $f2) {
            if ($f2 == "/")
                $tmp2[] = $f2;
            else
                $tmp2[] = urlencode($f2);
        }
        $encode_url = implode("/", $tmp2);
        return "<img src='$g4[data_path]/file/$board[bo_table]/".$encode_url."' name='target_resize_image[]' onclick='image_window(this);' style='cursor:pointer;' title='$content'>";
        }
    /*
    // 110106 : FLASH XSS �������� ���Ͽ� �ڵ� ��ü�� ����
    else if (preg_match("/\.($config[cf_flash_extension])$/i", $file))
        //return "<embed src='$g4[data_path]/file/$board[bo_table]/$file' $attr></embed>";
        return "<script>doc_write(flash_movie('$g4[data_path]/file/$board[bo_table]/$file', '_g4_{$ids}', '$width', '$height', 'transparent'));</script>";
    */
    //=============================================================================================
    // ������ ���Ͽ� �Ǽ��ڵ带 �ɴ� ��츦 �����ϱ� ���� ��θ� �������� ����
    //---------------------------------------------------------------------------------------------
    /*
    else if (preg_match("/\.($config[cf_movie_extension])$/i", $file))
        //return "<embed src='$g4[data_path]/file/$board[bo_table]/$file' $attr></embed>";
        return "<script>doc_write(obj_movie('$g4[data_path]/file/$board[bo_table]/$file', '_g4_{$ids}', '$width', '$height'));</script>";
    */
    //=============================================================================================
}


// view_file_link() �Լ����� �Ѱ��� �̹����� ���̰� �մϴ�.
// {img:0} ... {img:n} �� ���� ����
function view_image($view, $number, $attribute)
{
    if ($view['file'][$number]['view'])
        return preg_replace("/>$/", " $attribute>", $view['file'][$number]['view']);
    else
        //return "{".$number."�� �̹��� ����}";
        return "";
}


// ��Ƽ����Ʈ ���ڿ� �ڸ���
// http://kr.php.net/manual/kr/function.mb-strimwidth.php
function cut_str($str, $len, $suffix="��")
{
    global $g4;

    return mb_strimwidth($str, 0, $len, $suffix, $g4['charset']);
}


// TEXT �������� ��ȯ
function get_text($str, $html=0)
{
    /* 3.22 ���� (HTML üũ �ٹٲ޽� ��� ��������)
    $source[] = "/  /";
    $target[] = " &nbsp;";
    */

    // 3.31
    // TEXT ����� ��� &amp; &nbsp; ���� �ڵ带 �������� ����� �ֱ� ����
    if ($html == 0) {
        $str = html_symbol($str);
    }

    $source[] = "/</";
    $target[] = "&lt;";
    $source[] = "/>/";
    $target[] = "&gt;";
    //$source[] = "/\"/";
    //$target[] = "&#034;";
    $source[] = "/\'/";
    $target[] = "&#039;";
    //$source[] = "/}/"; $target[] = "&#125;";
    if ($html) {
        $source[] = "/\n/";
        $target[] = "<br/>";
    }

    return preg_replace($source, $target, $str);
}


/*
// HTML Ư������ ��ȯ htmlspecialchars
function hsc($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}
*/

// 3.31
// HTML SYMBOL ��ȯ
// &nbsp; &amp; &middot; ���� �������� ���
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}


/*************************************************************************
**
**  SQL ���� �Լ� ����
**
*************************************************************************/

// DB ����
function sql_connect($host, $user, $pass)
{
    global $g4;

    $link = @mysql_connect($host, $user, $pass);
    if (!$link) {
        if (is_dir("$g4[path]/install") && !file_exists("$g4[path]/dbconfig.php")) {
            // install ���丮�� �ְ�, dbconfig.php�� ������, ��ġȭ������ �̵�
            goto_url("$g4[path]/install/");
        } else {
            // db�� ���� ���
            include_once("$g4[path]/on_pm.php");
            die;
        }
    }

    return $link;
}


// DB ����
function sql_select_db($db, $connect)
{
    global $g4;

    $db_selected = @mysql_select_db($db, $connect);

    if (strtolower($g4['charset']) == 'utf-8') @mysql_query(" set names utf8 ");
    else if (strtolower($g4['charset']) == 'euc-kr') @mysql_query(" set names euckr ");

    return $db_selected;
}


// mysql_query �� mysql_error �� �Ѳ����� ó��
function sql_query($sql, $error=TRUE)
{
    global $mysql_db;

    // Blind SQL Injection ����� �ذ�
    $sql = trim($sql);
    // union�� ����� ������� �ʽ��ϴ�.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    // `information_schema` DB���� ������ ������� �ʽ��ϴ�.
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    if ($error)
        $result = @mysql_query($sql) or die(sql_failure_handler($sql, mysql_errno(), mysql_error()));
    else
        $result = @mysql_query($sql);
    
    // ���̺��� �������� �ڵ����� �����Ѵ�
    if ($result == FALSE) {
        $error_no = mysql_errno();
        if ($error_no == 1194) {

            $sql0 = "SHOW TABLE STATUS FROM ".$mysql_db;
            $result0 = sql_query($sql0);
            while($row = sql_fetch_array($result0))
            {
                $str = '';

                $tbl = $row['Name'];

                $sql1 = " SELECT COUNT(*) FROM `$tbl` ";
                $result1 = @mysql_query($sql1);
                if (!$result1)
                {
                    // ���̺� ����
                    $sql2 = " REPAIR TABLE `$tbl` ";
                    sql_query($sql2);
                    $str .= $sql2 . "<br/>";
                }
            }

            if ($error)
                $result = @mysql_query($sql) or die(sql_failure_handler($sql, mysql_errno(), mysql_error()));
            else
                $result = @mysql_query($sql);
        }

    }
    return $result;
}

// sql query�� ���� �ڵ鸵, http://dev.mysql.com/tech-resources/articles/guide-to-php-security-ch3.pdf
function sql_failure_handler($query, $error, $error_no) 
{
    global $g4, $is_admin, $_SERVER;

    if ($is_admin || $g4['debug']) {
        $msg = "<p><H2>" . htmlspecialchars($query) . "</H2></p><p>" .  $error_no . " : " .  $error . "</p><p><H2>error file : $_SERVER[PHP_SELF]<H2></p>";
        return $msg;
    }
    return "<p><H2>Requested page is temporarily unavailable, please try again later.</H2><p>";
}

// ������ ������ �� ��������� ������ ��´�.
function sql_fetch($sql, $error=TRUE)
{
    $result = sql_query($sql, $error);
    $row = sql_fetch_array($result);
    return $row;
}


// ��������� ���� �����迭(�̸�����)�� ��´�.
function sql_fetch_array($result)
{
    $row = @mysql_fetch_assoc($result);
    return $row;
}


// $result�� ���� �޸�(memory)�� �ִ� ������ ��� �����Ѵ�.
// sql_free_result()�� ����κ��� ���� ���� ���� Ŀ�� ���� �޸𸮸� ����� ������ ���� �� ���ȴ�.
// ��, ��� ���� ��ũ��Ʈ(script) ����ΰ� ����Ǹ鼭 �޸𸮿��� �ڵ������� ��������.
function sql_free_result($result)
{
    return mysql_free_result($result);
}


function sql_password($value)
{
    // mysql 4.0x ���� ���������� password() �Լ��� ����� 16bytes
    // mysql 4.1x �̻� ���������� password() �Լ��� ����� 41bytes
    $row = sql_fetch(" select password('$value') as pass ");
    return $row[pass];
}

function sql_old_password($value)
{
    // mysql 4.0x ���� ���������� password() �Լ��� ����� 16bytes
    // mysql 4.1x �̻� ���������� password() �Լ��� ����� 41bytes
    $row = sql_fetch(" select old_password('$value') as pass ");
    return $row[pass];
}

// PHPMyAdmin ����
function get_table_define($table, $crlf="\n")
{
    global $g4;

    // For MySQL < 3.23.20
    $schema_create .= 'CREATE TABLE ' . $table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $schema_create .= '    ' . $row['Field'] . ' ' . $row['Type'];
        if (isset($row['Default']) && $row['Default'] != '')
        {
            $schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
        }
        if ($row['Null'] != 'YES')
        {
            $schema_create .= ' NOT NULL';
        }
        if ($row['Extra'] != '')
        {
            $schema_create .= ' ' . $row['Extra'];
        }
        $schema_create     .= ',' . $crlf;
    } // end while
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $kname    = $row['Key_name'];
        $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if ($kname != 'PRIMARY' && $row['Non_unique'] == 0) {
            $kname = "UNIQUE|$kname";
        }
        if ($comment == 'FULLTEXT') {
            $kname = 'FULLTEXT|$kname';
        }
        if (!isset($index[$kname])) {
            $index[$kname] = array();
        }
        if ($sub_part > 1) {
            $index[$kname][] = $row['Column_name'] . '(' . $sub_part . ')';
        } else {
            $index[$kname][] = $row['Column_name'];
        }
    } // end while
    sql_free_result($result);

    while (list($x, $columns) = @each($index)) {
        $schema_create     .= ',' . $crlf;
        if ($x == 'PRIMARY') {
            $schema_create .= '    PRIMARY KEY (';
        } else if (substr($x, 0, 6) == 'UNIQUE') {
            $schema_create .= '    UNIQUE ' . substr($x, 7) . ' (';
        } else if (substr($x, 0, 8) == 'FULLTEXT') {
            $schema_create .= '    FULLTEXT ' . substr($x, 9) . ' (';
        } else {
            $schema_create .= '    KEY ' . $x . ' (';
        }
        $schema_create     .= implode($columns, ', ') . ')';
    } // end while

    if (strtolower($g4['charset']) == "utf-8")
        $schema_create .= $crlf . ') DEFAULT CHARSET=utf8';
    else
        $schema_create .= $crlf . ')';

    return $schema_create;
} // end of the 'PMA_getTableDef()' function


// ���۷� üũ
function referer_check($url="")
{
    /*
    // ����� üũ�� ���� ���Ͽ� �ּ� ó����
    global $g4;

    if (!$url)
        $url = $g4[url];

    if (!preg_match("/^http[s]?:\/\/".$_SERVER[HTTP_HOST]."/", $_SERVER[HTTP_REFERER]))
        alert("����� �� ������ �ƴѰ� �����ϴ�.", $url);
    */
}


// �ѱ� ����
function get_yoil($date, $full=0)
{
    $arr_yoil = array ("��", "��", "ȭ", "��", "��", "��", "��");

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= "����";
    }
    return $str;
}


// ��¥�� select �ڽ� �������� ��´�
function date_select($date, $name="")
{
    global $g4;

    $s = "";
    if (substr($date, 0, 4) == "0000") {
        $date = $g4[time_ymdhis];
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    // ��
    $s .= "<select name='{$name}_y'>";
    for ($i=$m[0]-3; $i<=$m[0]+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[0]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[2]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_d'>";
    for ($i=1; $i<=31; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[3]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    return $s;
}


// �ð��� select �ڽ� �������� ��´�
// 1.04.00
// ��ſ� �ð� ������ �����ϰ� �Ǹ鼭 �߰���
function time_select($time, $name="")
{
    preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $m);

    // ��
    $s .= "<select name='{$name}_h'>";
    for ($i=0; $i<=23; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[0]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_i'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[2]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_s'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[3]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    return $s;
}


// DEMO ��� ������ ������ ���� ȭ������ �ν���
function check_demo()
{
    global $g4;
    if (file_exists("$g4[path]/DEMO"))
        alert("���� ȭ�鿡���� �Ͻ�(����) �� ���� �۾��Դϴ�.");
}


// ���ڿ��� �ѱ�, ����, ����, Ư�����ڷ� �����Ǿ� �ִ��� �˻�
function check_string($str, $options)
{
    global $g4;

    $s = '';
    for($i=0;$i<strlen($str);$i++) {
        $c = $str[$i];
        $oc = ord($c);

        // �ѱ�
        if ($oc >= 0xA0 && $oc <= 0xFF) {
            if (strtoupper($g4['charset']) == 'UTF-8') {
                if ($options & _G4_HANGUL_) {
                    $s .= $c . $str[$i+1] . $str[$i+2];
                }
                $i+=2;
            } else {
                // �ѱ��� 2����Ʈ �̹Ƿ� �����ϳ��� �ǳʶ�
                $i++;
                if ($options & _G4_HANGUL_) {
                    $s .= $c . $str[$i];
                }
            }
        }
        // ����
        else if ($oc >= 0x30 && $oc <= 0x39) {
            if ($options & _G4_NUMERIC_) {
                $s .= $c;
            }
        }
        // ���빮��
        else if ($oc >= 0x41 && $oc <= 0x5A) {
            if (($options & _G4_ALPHABETIC_) || ($options & _G4_ALPHAUPPER_)) {
                $s .= $c;
            }
        }
        // ���ҹ���
        else if ($oc >= 0x61 && $oc <= 0x7A) {
            if (($options & _G4_ALPHABETIC_) || ($options & _G4_ALPHALOWER_)) {
                $s .= $c;
            }
        }
        // ����
        else if ($oc == 0x20) {
            if ($options & _G4_SPACE_) {
                $s .= $c;
            }
        }
        else {
            if ($options & _G4_SPECIAL_) {
                $s .= $c;
            }
        }
    }

    // �Ѿ�� ���� ���Ͽ� ������ ��, Ʋ���� ����
    return ($str == $s);
}


// �ѱ�(2bytes)���� ������ ���ڰ� 1byte�� ������ ���
// ��½� ������ ������ �߻��ϹǷ� ������ �������� ���� ����(1byte)�� �ϳ� ����
function cut_hangul_last($hangul)
{
    global $g4;

    // �ѱ��� ���ʳ��� ?�� ǥ�õǴ� ������ ����
    $cnt = 0;
    for($i=0;$i<strlen($hangul);$i++) {
        // �ѱ۸� ����
        if (ord($hangul[$i]) >= 0xA0) {
            $cnt++;
        }
    }

    // Ȧ����� �ѱ��� ���ʳ� �����̹Ƿ�
    if (strtoupper($g4['charset']) != 'UTF-8') {
        if ($cnt%2) {
            $hangul = substr($hangul, 0, $cnt-1);
        }
    }

    return $hangul;
}


// ���̺��� INDEX(Ű) ��뿩�� �˻�
function explain($sql)
{
    if (preg_match("/^(select)/i", trim($sql))) {
        $q = "explain $sql";
        echo $q;
        $row = sql_fetch($q);
        if (!$row[key]) $row[key] = "NULL";
        echo " <font color=blue>(type=$row[type] , key=$row[key])</font>";
    }
}

// �Ǽ��±� ��ȯ
function bad_tag_convert($code)
{
    global $view;
    global $member, $is_admin;

    if ($is_admin && $member[mb_id] != $view[mb_id]) {
        //$code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>(\<\/(embed|object)\>)?#i",
        // embed �Ǵ� object �±׸� ���� �ʴ� ��� ���͸��� �ǵ��� ����
        $code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>?(\<\/(embed|object)\>)?#i",
                    create_function('$matches', 'return "<div class=\"embedx\">���ȹ����� ���Ͽ� ������ ���̵�δ� embed �Ǵ� object �±׸� �� �� �����ϴ�. Ȯ���Ͻ÷��� ���������� ���� �ٸ� ���̵�� �����ϼ���.</div>";'),
                    $code);
    }

    //return preg_replace("/\<([\/]?)(script|iframe)([^\>]*)\>/i", "&lt;$1$2$3&gt;", $code);
    // script �� iframe �±׸� ���� �ʴ� ��� ���͸��� �ǵ��� ����
    //return preg_replace("/\<([\/]?)(script|iframe|form|applet)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
    return preg_replace("/\<([\/]?)(script|form|applet)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
}


// �ҹ������� ������ ��ū�� �����ϸ鼭 ��ū���� ����
function get_token()
{
    $token = md5(uniqid(rand(), true));
    set_session("ss_token", $token);

    return $token;
}


// POST�� �Ѿ�� ��ū�� ���ǿ� ����� ��ū ��
function check_token()
{
    set_session('ss_token', '');
    return true;
}


// ���ڿ��� utf8 ���ڰ� ��� �ִ��� �˻��ϴ� �Լ�
// �ڵ� : http://in2.php.net/manual/en/function.mb-check-encoding.php#95289
function is_utf8($str) 
{ 
    $len = strlen($str); 
    for($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]); 
        if ($c > 128) { 
            if (($c > 247)) return false; 
            elseif ($c > 239) $bytes = 4; 
            elseif ($c > 223) $bytes = 3; 
            elseif ($c > 191) $bytes = 2; 
            else return false; 
            if (($i + $bytes) > $len) return false; 
            while ($bytes > 1) { 
                $i++; 
                $b = ord($str[$i]); 
                if ($b < 128 || $b > 191) return false; 
                $bytes--; 
            } 
        } 
    } 
    return true; 
}

// PDO $stmt�� ������ �� ��������� ������ ��´�.
function pdo_fetch($stmt, $error=TRUE)
{

    if ($error)
        $stmt->execute() or die(print_r($stmt->errorInfo()));
    else
        $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    return $row;
}

// PDO $stmt�� ������ �� ��������� ������ ��´� - $params�� ������ �޴´�
function pdo_fetch_params($stmt, $params, $error=TRUE)
{
    // param�� binding �Ѵ�
    foreach ($params as $p1) {
        $stmt->bindParam($p1[0], $p1[1]);
    }

    if ($error)
        $stmt->execute() or die(print_r($stmt->errorInfo()));
    else
        $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    return $row;
}

// pdo->execute �� mysql_error �� �Ѳ����� ó��
function pdo_query($stmt, $error=TRUE)
{
    if ($error)
        $result = $stmt->execute() or die(print_r($stmt->errorInfo()));
    else
        try { 
            $result = $stmt->execute();
        } catch (PDOException $e) { 
            $result = 0;
        }

    return $result;
}

// pdo->execute �� mysql_error �� �Ѳ����� ó��
function pdo_query_params($stmt, $params, $error=TRUE)
{
    // param�� binding �Ѵ�
    foreach ($params as $p1) {
        $stmt->bindParam($p1[0], $p1[1]);
    }

    if ($error)
        $result = $stmt->execute() or die(print_r($stmt->errorInfo()));
    else
        try { 
            $result = $stmt->execute();
        } catch (PDOException $e) { 
            $result = 0;
        }

    return $result;
}

// pdo->prepare���� like query�� binding�� ��, \, _, %�� ���ش�
// http://phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=80197
function pdo_bind_like($stx, $pos)
{
    switch ($pos) {
        case 1  : $stx = '%' . str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $stx);
                  break;
        case 2  : $stx = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $stx) . '%';
                  break;
        default : $stx = '%' . str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $stx) . '%';
    }

    return $tex;
}

// DB ���̺��� field names�� ������������ �о array�� �����Ѵ�
// http://php.net/manual/kr/function.mysql-list-fields.php
// �Ʒ��� ���� ����ȴ�. 
// [Field] => mb_no [Type] => int(11) [Null] => NO [Key] => PRI [Default] => [Extra] => auto_increment
function sql_get_field_names($table_name)
{
    $result = sql_query(" SHOW COLUMNS FROM $table_name ");
    if (!$result) {
        alert("Error - Could not run query ");
    }

    $arr = array();
    while ($row = sql_fetch_array($result)) {
        $arr[] = $row;
    }

    return $arr;
}

// �Ҵ��� ���̺귯���� �н��ϴ�
include_once("$g4[path]/lib/b4.lib.php");
?>