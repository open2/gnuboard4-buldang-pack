<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �Ҵ��� - �ڸ�Ʈ �б� ����
if ($board['bo_comment_read_level'] && $board['bo_comment_read_level'] > 1 && $member['mb_level'] && $member['mb_level'] < $board['bo_comment_read_level'])
{
    if ($cwin) // �ڸ�Ʈ ����
       alert_close("�ڸ�Ʈ �б� ������ ���� �Խ����Դϴ�.");
    else
       alert("�ڸ�Ʈ �б� ������ ���� �Խ����Դϴ�.");
};

if (file_exists("$board_skin_path/view_comment.head.skin.php"))
    @include_once("$board_skin_path/view_comment.head.skin.php");

// �ڸ�Ʈ�� ��â���� ���� ��� ���ǰ��� �����Ƿ� �����Ѵ�.
if ($is_admin && !$token) 
{
    set_session("ss_delete_token", $token = uniqid(time()));
}

// �Ҵ��� - sideview Ʃ��
$sideview = array();

$list = array();

$is_comment_write = false;
if ($member[mb_level] >= $board[bo_comment_level]) 
    $is_comment_write = true;

// DHTML ������ ��� ���� �����ϰ� ���� : 061021 - write.php���� ������ �ڵ�
if ($board[bo_use_dhtml_comment] && $member[mb_level] >= $board[bo_dhtml_editor_level_comment])
    $is_dhtml_editor = true;
else
    $is_dhtml_editor = false;

// �ڸ�Ʈ ���
//$sql = " select * from $write_table where wr_parent = '$wr_id' and wr_is_comment = 1 order by wr_comment desc, wr_comment_reply ";
//$sql = " select * from $write_table where wr_parent = '$wr_id' and wr_is_comment = 1 order by wr_comment, wr_comment_reply ";
$select_sql = " wr_id, mb_id, wr_name, wr_parent, wr_option, wr_content, wr_datetime, wr_ip, wr_comment, wr_comment_reply, wr_singo,
                wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_password ";
if ($board[bo_use_sideview]) {
    $select_sql .= " ,wr_email , wr_homepage ";
}

// �Ҵ��� -- tmp ���̺��� �̿��ؼ� ������ ����� ������ ������ �����Ѵ�
$sql = " select $select_sql from $write_table where wr_parent = '$wr_id' and wr_is_comment = 1 order by wr_comment, wr_comment_reply ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    $list[$i] = $row;

    //$list[$i][name] = get_sideview($row[mb_id], cut_str($row[wr_name], 20, ''), $row[wr_email], $row[wr_homepage]);

    // html ������� �ۼ��� �ڸ�Ʈ�ε�, ������ html �����⸦ �Ⱦ��ٸ�???
    if (!$is_dhtml_editor && strstr($row[wr_option], "html")) {
      
          // euc-kr�� �ν��� ���� ���ϳ׿�. ����, CP866���� �ؾ� �մϴ�.
          if (strtoupper($g4[charset]) == "UTF-8")
              $list[$i][wr_content0] = html_entity_decode($row[wr_content],ENT_QUOTES,"UTF-8");
          else 
              $list[$i][wr_content0] = html_entity_decode($row[wr_content],ENT_QUOTES,"CP866");
          $list[$i][wr_content0] = br2nl($list[$i][wr_content0]);
         
    }

    $tmp_name = get_text(cut_str($row[wr_name], $config[cf_cut_name])); // ������ �ڸ��� ��ŭ�� �̸� ���
    if ($board[bo_use_sideview])
    {
        // ȸ���϶��� ����� sideview�� ������ ����� sideview�� ���
        if ($row[mb_id]) {

            if ($sideview[$row[mb_id]])
                $list[$i][name] = $sideview[$row[mb_id]];
            else {
                // �۾����� ���������� �ٲ� ���� ���Ŀ� ������ ���̶�� ���������� �ٽ� ������ �ʿ䰡 ������
                // ���� �۾����� ������ �ٲ������, �����͸� ������ ������ SQL Query�� �ؾ��� �ؼ� �׳� ���� �� �����ϴ�.
                $mb = get_member($row['mb_id'], "mb_email, mb_homepage");
                $row[wr_email] = $mb[mb_email];
                $row[wr_homepage] = $mb[mb_homepage];
                $list[$i][name] = get_sideview($row[mb_id], $tmp_name, $row[wr_email], $row[wr_homepage]);
                $sideview[$row[mb_id]] = $list[$i][name];
            }
            
        } else {
            $list[$i][name] = get_sideview($row[mb_id], $tmp_name, $row[wr_email], $row[wr_homepage]);
        }
    }
    else
        $list[$i][name] = "<span class='".($row[mb_id]?'member':'guest')."'>$tmp_name</span>";

    // ������� ���� �Է��� ���� �ڸ��� (way ���� ����. way.co.kr)
    //$list[$i][content] = eregi_replace("[^ \n<>]{130}", "\\0\n", $row[wr_content]);

    $singo = "";
    $singo .= "<div id='singo_title{$list[$i][wr_id]}' class='singo_title'><font color=gray>�Ű� ������ �Խù��Դϴ�. ";
    $singo .= "<span class='singo_here' style='cursor:pointer;font-weight:bold;' onclick=\"document.getElementById('singo_contents{$list[$i][wr_id]}').style.display=(document.getElementById('singo_contents{$list[$i][wr_id]}').style.display=='none'?'':'none');\"><font color=red>����</font></span>�� Ŭ���Ͻø� ������ �� �� �ֽ��ϴ�.</font></div>";
    $singo .= "<div id='singo_contents{$list[$i][wr_id]}' style='display:none;'><p>";

    // �ڸ�Ʈ�� ��쿡�� �Ű������� ������� ���� (�ڵ尡 �̻��ϰ� ��������)
    if (!strstr($row[wr_option], "secret")) {

        $list[$i][content1] = $row[wr_content];
        if (!strstr($row[wr_option], "html"))
            $list[$i][content] = conv_content($row[wr_content], 0, 'wr_content');
        else
            $list[$i][content] = $list[$i][content1];
        $list[$i][content] = search_font($stx, $list[$i][content]);

        // �Ű�� �ڸ�Ʈ ����ϱ�
        if ($row[wr_singo] and $board[bo_singo_action] > 0 and $row[wr_singo] >= $board[bo_singo_action] )
        {
            $singo .= $list[$i][content];
            $singo .= "</div>";

            $list[$i][content] = $singo;
        }

    } else if ($is_admin || 
        ($member[mb_id] && $write[mb_id]==$member[mb_id]) || 
        ($member[mb_id] && $row[mb_id]==$member[mb_id])) {

        $list[$i][content1] .= $row[wr_content];
        if (!strstr($row[wr_option], "html"))
            $list[$i][content] = conv_content($row[wr_content], 0, 'wr_content');
        else
            $list[$i][content] = $list[$i][content1];
        $list[$i][content] = search_font($stx, $list[$i][content]);
        
        // �Ű�� �ڸ�Ʈ ����ϱ�
        if ($row[wr_singo] and $board[bo_singo_action] > 0 and $row[wr_singo] >= $board[bo_singo_action])
        {
            $singo .= $list[$i][content];
            $singo .= "</div>";

            $list[$i][content] = $singo;
        }

    } else if ($member[mb_id]) {

        // ������ �ڸ�Ʈ ��б� ó��
        $comment_depth = strlen($list[$i][wr_comment_reply]);

        // �ٷ� ������ ��ۿ� ���ؼ��� ���� üũ�� �մϴ�.
        $parent_wr_comment_reply = substr($list[$i][wr_comment_reply], 0, $comment_depth-1);
        $parent_wr_comment = $list[$i][wr_comment];
        $parent_wr_parent = $list[$i][wr_parent];

        // sql query caching�� �����ϰ�, mb_id�� where�� ���� �ʴ´�
        $sql5 = " select mb_id from $write_table where wr_parent = '$parent_wr_parent' and wr_is_comment=1 and wr_comment = '$parent_wr_comment' and wr_comment_reply='$parent_wr_comment_reply' ";
        $result5 = sql_fetch($sql5);

        // �ٷ��� ����� ������ �� �д� ����̸�, ��б��̴��� �����ش�
        if ($result5) {
            if ($result5[mb_id] == $member[mb_id]) {

                $list[$i][content1] = $row[wr_content];
                if (!strstr($row[wr_option], "html"))
                    $list[$i][content] = conv_content($row[wr_content], 0, 'wr_content');
                else
                    $list[$i][content] = $list[$i][content1];
                $list[$i][content] = search_font($stx, $list[$i][content]);
            } else {
              
                $list[$i][content] = $list[$i][content0] = $list[$i][content1] = $list[$i][wr_content0] = "";
            }
        } else {
            $list[$i][content] = $list[$i][content0] = $list[$i][content1] = $list[$i][wr_content0] = "";
        }

        // �Ű�� �ڸ�Ʈ ����ϱ�
        if ($row[wr_singo] and $board[bo_singo_action] > 0 and $row[wr_singo] >= $board[bo_singo_action])
        {
            $singo .= $list[$i][content];
            $singo .= "</div>";

            $list[$i][content] = $singo;
        }

    } else {

        $list[$i][content] = $list[$i][content0] = $list[$i][content1] = $list[$i][wr_content0] = "";

    }

    // �Ҵ��� : image Resize�� ���ؼ�
    $list[$i][content] = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $list[$i][content]);

    $list[$i][datetime] = substr($row[wr_datetime],2,14);

    // �����ڰ� �ƴ϶�� �߰� IP �ּҸ� ������ �����ݴϴ�.
    $list[$i][ip] = $row[wr_ip];
    if (!$is_admin)
        $list[$i][ip] = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.��.\\3.\\4", $row[wr_ip]);

    // �Ű� ��ũ
    if ($board[bo_singo] && $is_comment_write && $member[mb_id] != $row[mb_id])
        $list[$i][singo_href] = "$g4[bbs_path]/singo_popin.php?bo_table=$bo_table&wr_id=$row[wr_id]&wr_parent=$row[wr_parent]";

    $list[$i][is_reply] = false;
    $list[$i][is_edit] = false;
    $list[$i][is_del]  = false;
    if ($is_comment_write || $is_admin) 
    {
        if ($member[mb_id]) 
        {
            if ($row[mb_id] == $member[mb_id] || $is_admin) 
            {
                $list[$i][del_link]  = "$g4[bbs_path]/delete_comment.php?bo_table=$bo_table&comment_id=$row[wr_id]&token=$token&cwin=$cwin&page=$page".$qstr;
                $list[$i][is_edit]   = true;
                $list[$i][is_del]    = true;
            }
        } 
        else 
        {
            if (!$row[mb_id]) {
                $list[$i][del_link] = "$g4[bbs_path]/password.php?w=x&bo_table=$bo_table&comment_id=$row[wr_id]&cwin=$cwin&page=$page".$qstr;
                $list[$i][is_del]   = true;
            }
        }

        if (strlen($row[wr_comment_reply]) < 5)
            $list[$i][is_reply] = true;
    }

    // 05.05.22
    // �亯�ִ� �ڸ�Ʈ�� ����, ���� �Ұ�
    if ($i > 0 && !$is_admin)
    {
        if ($row[wr_comment_reply]) 
        {
            $tmp_comment_reply = substr($row[wr_comment_reply], 0, strlen($row[wr_comment_reply]) - 1);
            if ($tmp_comment_reply == $list[$i-1][wr_comment_reply])
            {
                $list[$i-1][is_edit] = false;
                $list[$i-1][is_del] = false;
            }
        }
    }
    
    // �Խñ� ��� �����ϰ� - �Ҵ��� : ���ߴ��� �� (������ �Ǵ� �Խñ� �ۼ��ڸ� �����ϰ�)
    if ($is_admin || ($member['mb_id'] && $member['mb_id'] == $list[$i][mb_id])) { 
        if (strstr($list[$i][wr_option], "secret")) { 
            // ��� ���� ��ư
            $list[$i][nosecret_href] = "javascript:post_submit('$g4[bbs_path]/proc/mw.btn.secret.php','$bo_table','$wr_id', '{$list[$i][wr_id]}', 'no', '�Խñ� �������')";
        } else { 
            // ��� ��ư
            $list[$i][secret_href] = "javascript:post_submit('$g4[bbs_path]/proc/mw.btn.secret.php','$bo_table','$wr_id', '{$list[$i][wr_id]}', '', '�Խñ� ���')";
        }
    }
}

//  �ڸ�Ʈ�� ���� ������
if ($is_admin)
{
    $comment_min = $comment_max = 0;
}
else
{
    $comment_min = (int)$board[bo_comment_min];
    $comment_max = (int)$board[bo_comment_max];
}

include_once("$board_skin_path/view_comment.skin.php");

// ����
if (!file_exists("$g4[bbs_path]/ajax.filter.php")) {
echo "<script type='text/javascript'> var g4_cf_filter = '$config[cf_filter]'; </script>\n";
echo "<script type='text/javascript' src='$g4[path]/js/filter.js'></script>\n";
}

if (file_exists("$board_skin_path/view_comment.tail.skin.php"))
    @include_once("$board_skin_path/view_comment.tail.skin.php");
?>
