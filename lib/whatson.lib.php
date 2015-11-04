<?
if (!defined('_GNUBOARD_')) exit;

function whatson_count($mb_id) {
    global $g4, $member, $config;

    $sql = " select count(*) as cnt from $g4[whatson_table] where mb_id='$mb_id' and wo_status = 0 ";
    $result = sql_fetch($sql);
    
    return $result['cnt'];
}

// �״����� �Լ�~ ����
function whatson($skin_dir="", $rows=10, $subject_len=25, $page=1, $options="", $target="", $check="", $head=1)
{
    global $g4, $member, $config;

    if ($skin_dir)
        $whatson_skin_path = "$g4[path]/skin/whatson/$skin_dir";
    else
        $whatson_skin_path = "$g4[path]/skin/whatson/basic";

   if ($target)
      $target_link = "target=" . $target;

    $list = array();

    // ��ȸ���� ��쿡�� �Լ�~�� ���� �����ϴ�.
    if (!$member[mb_id])
        return;

    // �Լ�~�� ��ü ������ ���մϴ�.
    $sql = " select count(*) as cnt from $g4[whatson_table] where mb_id='$member[mb_id]' ";
    $result = sql_fetch($sql);

    $total_count = $result[cnt];

    $total_page  = ceil($total_count / $rows);  // ��ü ������ ���

    $from_record = ($page - 1) * $rows; // ���� ���� ����
    $limit_sql = " limit $from_record, $rows ";

    if (!$head || $check==1) {
        $write_pages = get_paging($config[cf_write_pages], $page, $total_page, "$g4[bbs_path]/whatson.php?head=$head&rows=$rows&check=$check&page=");
        $write_pages_xs = get_paging($config[cf_write_pages_xs], $page, $total_page, "$g4[bbs_path]/whatson.php?head=$head&rows=$rows&check=$check&page=");
    }

    $sql = " select * from $g4[whatson_table] where mb_id='$member[mb_id]' order by wo_datetime desc $limit_sql ";
    $result = sql_query($sql);

    // ������� $list�� �ֽ��ϴ�. ��Ų �ڵ尡 �����ϰ� �ǰ�
    for ($i=0; $row = sql_fetch_array($result); $i++) {
        $list[$i] = $row;
        
        if ($check == 1)
            $list[$i][subject] = conv_latest(strip_tags(htmlspecialchars_decode($row[wr_subject])));
        else
            $list[$i][subject] = conv_latest(cut_str(strip_tags(htmlspecialchars_decode($row[wr_subject])), $subject_len));
        if ($row[bo_table] && $row[wr_id])
            $list[$i][url] = "$g4[path]/$row[bo_table]/$row[wr_id]";
        if ($row[comment_id])
            $list[$i][url] .= "#c_" . $row[comment_id];
        $list[$i][datetime] = get_datetime($row[wo_datetime]);
    }

    ob_start();
    include "$whatson_skin_path/whatson.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
?>