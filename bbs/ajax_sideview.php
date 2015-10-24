<?
include_once("_common.php");

header("Content-Type: text/html; charset=utf-8");

$mb_nick = trim(get_text($_POST['mb_nick']));    // sideview�� ���
$mb_nick2 = trim(get_text($_POST['mb_nick2']));  // sideview�� Ŭ���� ���
$bo_table = trim($_POST['bo_table']);
$sca = trim($_POST['sca']);

if (strtolower($g4[charset]) == 'euc-kr') 
{
    $mb_nick = js_unescape($mb_nick);
    $mb_nick2 = js_unescape($mb_nick2);
    $sca = js_unescape($sca);
}

// ��ȸ���� sideview�� �׳� return
if ($mb_nick == "[��ȸ��]") {
    $res = "<div>";
    $res .= "<ul class='list-unstyled'>";
    $res .= "<li>��ȸ���Դϴ�</li>";
    $res .= "</ul>";
    $res .= "</div>";
    echo iconv($g4['charset'], "UTF-8", $res);
    exit;
}

// ��ȸ���� sideview ��û�� �׳� return
if ($member[mb_id] == "") {
    $res = "<div>";
    $res .= "<ul class='list-unstyled'>";
    $res .= "<li>ȸ�������Դϴ�.<br>�α��� �Ͻ��� �̿��� �ֽñ� �ٶ��ϴ�.</li>";
    $res .= "</ul>";
    $res .= "</div>";
    echo iconv($g4['charset'], "UTF-8", $res);
    exit;
}

$mb = get_member_nick($mb_nick);
$mb2 = get_member_nick($mb_nick2);

$mb_id = $mb['mb_id'];

if ($mb_id =='undefined' || $mb_id == "") {
    echo "Error: 110"; // �Է��� �����ϴ�.
} else {
    $mb = get_member($mb_id);
    if ($mb[mb_id] == "") {
        echo "Error: 130"; // ���� ���̵�
    } else {

        $memo_url = "$g4[bbs_path]/memo.php?kind=write&me_recv_mb_id=$mb_id";
        $point_url = "$g4[admin_path]/point_list.php?sfl=mb_id&stx=$mb_id";
        $bo_url = "$g4[bbs_path]/board.php?bo_table=$bo_table&sca=$sca&sfl=mb_id,1&stx=$mb_id";
        $new_url = "$g4[bbs_path]/new.php?mb_id=$mb_id";
        $member_modify_url = "$g4[admin_path]//member_form.php?w=u&mb_id=$mb_id";

        // ������� return
        $res = "<div>";
        $res .= "<ul class='list-unstyled'>";
        $res .= "<li><a href=\"javascript:;\" onClick=\"win_memo('$memo_url', '$mb_id');\">����������</a></li>";
        $res .= "<li><a href=\"javascript:;\" onClick=\"win_formmail('$mb_id','$mb_nick');\">���Ϻ�����</a></li>";
        $res .= "<li><a href=\"javascript:;\" onClick=\"win_profile('$mb_id');\">�ڱ�Ұ�</a></li>";
        if ($mb[mb_homepage])
            $res .= "<li><a href=\"javascript:;\" onClick=\"window.open('$mb[mb_homepage]');\">Ȩ������</a></li>";
        if ($bo_table)
            $res .= "<li><a href=\"$bo_url\" target=\"_blank\">���̵�ΰ˻�</a></li>";
        if ($member[mb_id] == $mb_id)
            $res .= "<li><a href=\"javascript:;\" onClick=\"win_point('');;\">����Ʈ����</a></li>";
        $res .= "<li><a href=\"$new_url\" target=\"_blank\">��ü�Խù�</a></li>";
        if ($is_admin == "super") {
            $res .= "<li><a href=\"$point_url\" target=\"_blank\">*����Ʈ����*</a></li>";
            $res .= "<li><a href=\"$member_modify_url\" target=\"_blank\">*ȸ����������*</a></li>";
        }
        $res .= "</ul>";
        $res .= "</div>";

        echo iconv($g4['charset'], "UTF-8", $res);

    }
}
?>
