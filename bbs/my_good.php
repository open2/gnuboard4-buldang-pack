<?
include_once("./_common.php");

if (!$member[mb_id]) 
    alert_close("ȸ���� ��ȸ�Ͻ� �� �ֽ��ϴ�.");

$g4[title] = $member[mb_nick] . "���� $txt �Խñ�";

if ($head_on)
    include_once("$g4[path]/head.php");
else
    include_once("$g4[path]/head.sub.php");

$sql_search = " where mb_id = '$member[mb_id]' ";
if ($w == "nogood") {
    $txt = "����õ";
    $sql_search .= " and bg_flag = 'nogood' ";
} else {
    $txt = "��õ";
    $sql_search .= " and bg_flag = 'good' ";
}

if ($sfl)
    if ($stx !== "all")
        $sql_search .= " and $sfl = '$stx' ";

$sql_common = " from $g4[board_good_table] ";
$sql_order = " order by bg_id desc ";

/*
�˻��κ�...
*/

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

if ($rows)
    $rows = (int) $rows;
else
    $rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if (!$page) $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

$list = array();
$sql = " select bg_id, bo_table, bg_id, wr_id , bg_datetime
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    $list[$i] = $row;

    $bo_table = $row[bo_table];

    // �������� ��ȣ (����)
    $list[$i]['num'] = $total_count - ($page - 1) * $rows - $i;

    // �Խ��� ����
    extract(get_board($bo_table, "bo_subject"));
    $bo_subject = cut_str($bo_subject, 20);

    // �Խù� ����
    $write = get_write2($bo_table, $row[wr_id], "wr_subject, mb_id, wr_option ");
    $wr_subject = get_text($write['wr_subject']);
    if ($wr_subject == "") 
        $wr_subject = "[�� ����]";
    if (strstr($row[wr_option], "secret"))
        $wr_secret = true;
    else
        $wr_secret = false;

    $list[$i][bo_subject] = $bo_subject;
    $list[$i][wr_subject] = $wr_subject;
    $list[$i][wr_secret] = $wr_secret;

    $list[$i][opener_href] = "$_SERVER[PHP_SELF]?sfl=bo_table&stx=$row[bo_table]&$mstr";
    $list[$i][opener_href_wr_id] = "./board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
    $list[$i][del_href] = "./my_good_update.php?w=d&bg_id=$row[bg_id]&page=$page&head_on=$head_on&mnb=$mnb&snb=$snb&bg_good=$bg_good";

    $mb = get_member($write[mb_id], "mb_id, mb_nick, mb_email, mb_homepage");
    $list[$i][mb_id] = $mb[mb_id];
    $list[$i][mb_nick] = get_sideview($mb['mb_id'], get_text($mb[mb_nick]), $mb['mb_email'], $mb['mb_homepage']);
}

// �Խ��� ��Ϻ��� �����ϱ�
$sql = " select distinct a.bo_table, b.bo_subject from $g4[board_good_table] a left join $g4[board_table] b on a.bo_table=b.bo_table where a.mb_id = '$member[mb_id]' ";
$result = sql_query($sql);
$bo_list = array();
for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bo_list[$i] = $row;
}

$write_pages = get_paging($config[cf_write_pages], $page, $total_page, "?qstr=$qstr&page=");

$skin_path = "$g4[path]/skin/my_good/$g4[my_good_skin]";
include_once("$skin_path/my_good.skin.php");

if ($head_on)
    include_once("$g4[path]/tail.php");
else
    include_once("$g4[path]/tail.sub.php");
?>
