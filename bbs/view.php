<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Խ��ǿ��� �δܾ� �̻� �˻� �� �˻��� �Խù��� �ڸ�Ʈ�� ����� ������ ���� ����
$sop = strtolower($sop);
if ($sop != "and" && $sop != "or")
    $sop = "and";

if (file_exists("$board_skin_path/view.head.skin.php"))
    @include_once("$board_skin_path/view.head.skin.php");

$sql_search = "";
// �˻��̸�
if ($sca || $stx) {
    // where ���� ����
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop);
    $search_href = "$g4[bbs_path]/board.php?bo_table=$bo_table&page=$page" . $qstr;
    $list_href = "$g4[bbs_path]/board.php?bo_table=$bo_table" . $mstr;
} else {
    $search_href = "";
    $list_href = "$g4[bbs_path]/board.php?bo_table=$bo_table&page=$page" . $mstr;
}

if (!$board['bo_use_list_view']) {
    if ($sql_search)
        $sql_search = " and " . $sql_search;

    // ������ ����
    // �Ҵ��� - tmp table�� �����, �ű⼭ ���� �� �����°� ������. $sql_search�� index�� ��Ÿ�ϱ�...
    // ����ó�� create temporaty table�� ������ ���ִ� ���, config.php���� $g4['old_stype_search'] �������� 1��.
    if ($g4['old_stype_search']) {
        if ($write['wr_reply']) {
            // ����� ��. ����� �ƴ� ���� �������� ���� �����ϴ�.
            $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num = '$write[wr_num]' and wr_reply < '$write[wr_reply]' $sql_search order by wr_num desc, wr_reply desc limit 1 ";
            $prev = sql_fetch($sql);
        }
        // ���� ���������� ���� ���� ���ߴٸ�
        if (!$prev['wr_id'])     {
            $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num < '$write[wr_num]' $sql_search order by wr_num desc, wr_reply desc limit 1 ";
            $prev = sql_fetch($sql);
        }
    } else {
        if ($write['wr_reply']) {
            // ����� ��. ����� �ƴ� ���� �������� ���� �����ϴ�.
            $sql = " select * from $write_table where wr_is_comment = 0 and wr_num = '$write[wr_num]' and wr_reply < '$write[wr_reply]' ";
            $sql_tmp = " create TEMPORARY table view_tmp_prev as $sql ";
            $sql_ord = " select wr_id, wr_subject from view_tmp_prev where 1 $sql_search order by wr_num desc, wr_reply desc limit 1 ";

            @mysql_query($sql_tmp) or die("<p>$sql_tmp<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $result = @mysql_query($sql_ord) or die("<p>$sql_ord<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $prev = @mysql_fetch_assoc($result);
        }
        // ���� ���������� ���� ���� ���ߴٸ�
        if (!$prev['wr_id'])     {
            $sql = " select * from $write_table where wr_is_comment = 0 and wr_num < '$write[wr_num]' ";
            $sql_tmp = " create TEMPORARY table view_tmp_prev1 as $sql ";
            $sql_ord = " select wr_id, wr_subject from view_tmp_prev1 where 1 $sql_search order by wr_num desc, wr_reply desc limit 1 ";

            @mysql_query($sql_tmp) or die("<p>$sql_tmp<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $result = @mysql_query($sql_ord) or die("<p>$sql_ord<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $prev = @mysql_fetch_assoc($result);
        }
    }

    // �Ʒ����� ����
    // �Ҵ��� - tmp table�� �����, �ű⼭ ���� �� �����°� ������. $sql_search�� index�� ��Ÿ�ϱ�...
    // ����ó�� create temporaty table�� ������ ���ִ� ���, config.php���� $g4['old_stype_search'] �������� 1��.
    if ($g4['old_stype_search']) {
        if ($write['wr_reply']) {
            // ����� ��. ����� �ƴ� ���� �������� ���� �����ϴ�.
            $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num = '$write[wr_num]' and wr_reply > '$write[wr_reply]' $sql_search order by wr_num, wr_reply limit 1 ";
            $next = sql_fetch($sql);
        }
        // ���� ���������� ���� ���� ���ߴٸ�
        if (!$next['wr_id']) {
            $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num > '$write[wr_num]' $sql_search order by wr_num, wr_reply limit 1 ";
            $next = sql_fetch($sql);
        }
    } else {
        if ($write['wr_reply']) {
            // ����� ��. ����� �ƴ� ���� �������� ���� �����ϴ�.
            $sql = " select * from $write_table where wr_is_comment = 0 and wr_num = '$write[wr_num]' and wr_reply > '$write[wr_reply]' ";
            $sql_tmp = " create TEMPORARY table view_tmp_next as $sql ";
            $sql_ord = " select wr_id, wr_subject from view_tmp_next where 1 $sql_search order by wr_num, wr_reply limit 1 ";

            @mysql_query($sql_tmp) or die("<p>$sql_tmp<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $result = @mysql_query($sql_ord) or die("<p>$sql_ord<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $next = @mysql_fetch_assoc($result);
        }
        // ���� ���������� ���� ���� ���ߴٸ�
        if (!$next['wr_id'])     {
            $sql = " select * from $write_table where wr_is_comment = 0 and wr_num > '$write[wr_num]' ";
            $sql_tmp = " create TEMPORARY table view_tmp_next1 as $sql ";
            $sql_ord = " select wr_id, wr_subject from view_tmp_next1 where 1 $sql_search order by wr_num, wr_reply limit 1 ";

            @mysql_query($sql_tmp) or die("<p>$sql_tmp<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $result = @mysql_query($sql_ord) or die("<p>$sql_ord<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
            $next = @mysql_fetch_assoc($result);
        }
    }
}

// ������ ��ũ
$prev_href = "";
if ($prev[wr_id]) {
    $prev_wr_subject = get_text(cut_str($prev[wr_subject], 255));
    $prev_href = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$prev[wr_id]&page=$page" . $qstr;
}

// ������ ��ũ
$next_href = "";
if ($next[wr_id]) {
    $next_wr_subject = get_text(cut_str($next[wr_subject], 255));
    $next_href = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$next[wr_id]&page=$page" . $qstr;
}

// ���� ��ũ
$write_href = "";
//if ($member[mb_level] >= $board[bo_write_level])
    $write_href = "$g4[bbs_path]/write.php?bo_table=$bo_table" . $mstr;

// �亯 ��ũ
$reply_href = "";
if ($member[mb_level] >= $board[bo_reply_level])
    $reply_href = "$g4[bbs_path]/write.php?w=r&bo_table=$bo_table&wr_id=$wr_id" . $qstr;

// ����, ���� ��ũ
$update_href = $delete_href = "";
// �α������̰� �ڽ��� ���̶�� �Ǵ� �����ڶ�� �н����带 ���� �ʰ� �ٷ� ����, ���� ����
if (($member[mb_id] && ($member[mb_id] == $write[mb_id])) || $is_admin) {
    $update_href = "$g4[bbs_path]/write.php?w=u&bo_table=$bo_table&wr_id=$wr_id&page=$page" . $qstr;
    $delete_href = "javascript:del('$g4[bbs_path]/delete.php?bo_table=$bo_table&wr_id=$wr_id&page=$page".urldecode($qstr)."');";
    if ($is_admin) 
    {
        set_session("ss_delete_token", $token = uniqid(time()));
        $delete_href = "javascript:del('$g4[bbs_path]/delete.php?bo_table=$bo_table&wr_id=$wr_id&token=$token&page=$page".urldecode($qstr)."');";
    }
}
else if (!$write[mb_id]) { // ȸ���� �� ���� �ƴ϶��
    $update_href = "$g4[bbs_path]/password.php?w=u&bo_table=$bo_table&wr_id=$wr_id&page=$page" . $qstr;
    $delete_href = "$g4[bbs_path]/password.php?w=d&bo_table=$bo_table&wr_id=$wr_id&page=$page" . $qstr;
}

// �ְ�, �׷�����ڶ�� �� ����, �̵� ����
$copy_href = $move_href = "";
if ($write[wr_reply] == "" && ($is_admin == "super" || $is_admin == "group")) {
    $copy_href = "javascript:win_open('$g4[bbs_path]/move.php?sw=copy&bo_table=$bo_table&wr_id=$wr_id&page=$page".$qstr."', 'boardcopy', 'left=50, top=50, width=500, height=550, scrollbars=1');";
    $move_href = "javascript:win_open('$g4[bbs_path]/move.php?sw=move&bo_table=$bo_table&wr_id=$wr_id&page=$page".$qstr."', 'boardmove', 'left=50, top=50, width=500, height=550, scrollbars=1');";
}

// �Խñ� ���Ǳ��
if ($board['bo_move_bo_table'] && $write['mb_id'] == $member['mb_id'] && $is_amin != 'super' && $is_admin != 'group') {
    $move_href = "javascript:move('$g4[bbs_path]/move2_update.php?bo_table=$bo_table&wr_id=$wr_id&page=$page".$qstr."');";;
}

$scrap_href = "";
$good_href = "";
$nogood_href = "";
if ($member[mb_id]) {
    // ��ũ�� ��ũ
    $scrap_href = "$g4[bbs_path]/scrap_popin.php?bo_table=$bo_table&wr_id=$wr_id";

    // ��õ ��ũ
    if ($board[bo_use_good])
        $good_href = "$g4[bbs_path]/good.php?bo_table=$bo_table&wr_id=$wr_id&good=good" . $mstr;

    // ����õ ��ũ
    if ($board[bo_use_nogood])
        $nogood_href = "$g4[bbs_path]/good.php?bo_table=$bo_table&wr_id=$wr_id&good=nogood" . $mstr;
}

$view = get_view($write, $board, $board_skin_path, 255);

// �Խñ� ��� �����ϰ� - �Ҵ��� : ���ߴ��� �� (������ �Ǵ� �Խñ� �ۼ��ڸ� �����ϰ�)
if ($is_admin || ($member["mb_id"] && $member["mb_id"] == $view["mb_id"])) { 
    if (strstr($view[wr_option], "secret")) { 
        // ��� ���� ��ư
        $nosecret_href = "javascript:post_submit('$g4[bbs_path]/proc/mw.btn.secret.php?page=$page$qstr','$bo_table','$wr_id', '', 'no', '�Խñ� �������')";
    } else { 
        // ��� ��ư
        $secret_href = "javascript:post_submit('$g4[bbs_path]/proc/mw.btn.secret.php?page=$page$qstr','$bo_table','$wr_id', '', '', '�Խñ� ���')";
    }
}

// �Խñ� ������Ʈ ��¥�� �������� - �Ҵ��� : ���ߴ��� �� (�����ڸ� �����ϰ�)
if ($is_admin) {
    $now_href = "javascript:post_submit('$g4[bbs_path]/proc/mw.time.now.php?page=$page$qstr','$bo_table','$wr_id', '', '', '�Խñ� ��¥ ������Ʈ�ϱ�')";
}
  
// �Ű� ��ũ - �ڸ�Ʈ ���� ������ �ִ� ������Ը� ���̰�
$singo_href = "";
if ($board[bo_singo] && $write_href && $member[mb_id] != $write[mb_id] && $member['mb_level'] >= $board['bo_comment_level'])
    $singo_href = "$g4[bbs_path]/singo_popin.php?bo_table=$bo_table&wr_id=$wr_id&wr_parent=$wr_id" . $mstr;

// �Ű����� ��ũ
$unsingo_href = "";
if ($board[bo_singo] && $view[wr_singo] && $member[mb_id] != $write[mb_id] && $member['mb_level'] >= $board['bo_comment_level'])
    $unsingo_href = "$g4[bbs_path]/unsingo_popin.php?bo_table=$bo_table&wr_id=$wr_id&wr_parent=$wr_id" . $mstr;

if (strstr($sfl, "subject"))
    $view[subject] = search_font($stx, $view[subject]);

$html = 0;
if (strstr($view[wr_option], "html1"))
    $html = 1;
else if (strstr($view[wr_option], "html2"))
    $html = 2;

// ��б� - ���� �� ��бۿ� �� �ڸ�Ʈ�� ���� ��  
if ($is_unlock_secret && strstr($view[wr_option], "secret") && $view['mb_id'] !== $member['mb_id']) {
    // ������ ������ �ʰ�
    $view[rich_content] = $view[content] = $view[content1] = "<font color=red><b>Ÿ���� �ۼ��� ��б� �Դϴ�.</b></font>";
    // ÷�������� ������ �ʰ�
    $view[file] = array();
    // ��ũ�� ������ �ʰ�
    $view[link] = array();
} else {

$view[content] = conv_content($view[wr_content], $html);
if (strstr($sfl, "content"))
    $view[content] = search_font($stx, $view[content]);
$view[content] = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' class='pointer' alt='' \\2 \\3", $view[content]);

//$view[rich_content] = preg_replace("/{img\:([0-9]+)[:]?([^}]*)}/ie", "view_image(\$view, '\\1', '\\2')", $view[content]);
$view[rich_content] = preg_replace("/{�̹���\:([0-9]+)[:]?([^}]*)}/ie", "view_image(\$view, '\\1', '\\2')", $view[content]);

$singo = "";
if ($write[wr_singo] and $board[bo_singo_action] > 0 and $write[wr_singo] >= $board[bo_singo_action])
{
    $singo .= "<div id='singo_title{$view[wr_id]}' class='singo_title'><font color=gray>�Ű� ������ �Խù��Դϴ�. ";
    //$singo .= "<a href='javascript:;' onclick=\"document.getElementById('singo_contents{$view[wr_id]}').style.display=(document.getElementById('singo_contents{$view[wr_id]}').style.display=='none'?'':'none');\">";
    //$singo .= "<span class='singo_here'>����</span></a>�� Ŭ���Ͻø� ������ �� �� �ֽ��ϴ�.</div>";
    $singo .= "<span class='singo_here' style='cursor:pointer;font-weight:bold;' onclick=\"document.getElementById('singo_contents{$view[wr_id]}').style.display=(document.getElementById('singo_contents{$view[wr_id]}').style.display=='none'?'':'none');\"><font color=red>����</font></span>�� Ŭ���Ͻø� ������ �� �� �ֽ��ϴ�.</font></div>";

    // �Ű������� ��� ���θ� ���� (singo_popin.skin.php���� ��¿��θ� �Ǵ��ϸ� ����ڰ� ������ ���� ������ �� �ֱ� ������)
    // ǥ�� �Ű������� ����ϴ� ���� ��Ģ. �׷��� ���� ��� �Ű����� Ÿ���� ��濡 ����� �� �ֱ� �����Դϴ�.
    // ǥ�� �Ű��̴���. sg_print = 1�� ��츸 ��� �մϴ�.
    $sql = " select distinct a.sg_reason, b.sg_print from $g4[singo_table] a, $g4[singo_reason_table] b where a.bo_table = '$bo_table' and a.wr_id = '$wr_id' and a.sg_reason = b.sg_reason order by a.sg_id ";
    $sg_result = sql_query($sql);
        
    $sg_reason = "";
    for ($i=0; $sg_row = sql_fetch_array($sg_result); $i++) {

        // sg_print = 1 : ������ ���
        if ($sg_row['sg_print'] > 0)
            $sg_reason .= $sg_row['sg_reason'] . "/" ;
    }
            
    if ($sg_reason)
        $singo .= "<font color=gray>�Ű������� \" $sg_reason \" �Դϴ�</font>";

    $singo .= "<div id='singo_contents{$view[wr_id]}' style='display:none;'><p>";
    $singo .= $view[content];
    $singo .= "</div>";

    $view[content] = $singo;
}

} // ��б� - ���� �� ��бۿ� �� �ڸ�Ʈ�� ���� �� 

// �Ҵ��� : �Խñ۾ȿ� ��õ��� ��ũ��Ʈ�� �ִ°� ���� 
$view[content] = preg_replace("/good\=good/i", "good=nogood", $view[content]); 

// �Ҵ��� : ������ �̹��� size�� ���� ������� - �������� �д�. ���� ���μ����� �ϴ� ������ ����. �̰Ŵ� fortran�� �ƴϴϱ�.
if ($board[bo_image_max_size] && $view[wr_imagesize] > 0 && $view[wr_imagesize] > $board[bo_image_max_size]) {
    $msg = "<font color=red><b>÷�����ϰ� ��������� �ø� �̹����� �հ谡 " . number_format($board[bo_image_max_size]) . " kb�� �ʰ��� " . number_format($view[wr_imagesize]) . " kb �̹Ƿ� ����� �� �����ϴ�.<br>�̹����� �ٿ��ֽñ� �ٶ��ϴ�.<br></b></font>";
    if (($member[mb_id] && ($member[mb_id] == $view[mb_id]) || $is_admin))
        $view[content] = $msg . $view[content];
    else  {
        // count�� 0�̸� for loop�� ����ȴ�.
        $view[file][count] = -1;
        // �Ű� �̹��������� �ȳ����� ����
        $view['wr_singo'] = false;
        // ���뿡�� �����
        $view[content] = $msg;
    }
}


// �Ҵ��� - �Խñ��ּ�
$posting_url = "$g4[url]/$g4[bbs]/board.php?bo_table=$bo_table&wr_id=$wr_id";

$is_signature = false;
$signature = "";
if ($board[bo_use_signature] && $view[mb_id])
{
    $is_signature = true;
    if ($member[mb_id] && $member[mb_id] == $view[mb_id]) {
        $signature = $member[mb_signature];
    } else {
        $mb = get_member($view[mb_id], "mb_signature");
        $signature = $mb[mb_signature];
    }

    //$signature = bad_tag_convert($signature);
    // 081022 : CSRF ���� �������� ���� �ڵ� ����
    $signature = conv_content($signature, 1);
}

echo "<script type='text/javascript' src='{$g4['path']}/js/ajax.js'></script>";
include_once("$board_skin_path/view.skin.php");

if (file_exists("$board_skin_path/view.tail.skin.php"))
    @include_once("$board_skin_path/view.tail.skin.php");
?>