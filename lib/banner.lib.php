<?
if (!defined('_GNUBOARD_')) exit;

// 배너 그룹을 SELECT 형식으로 얻음
function get_banner_group_select($name, $selected='', $event='')
{
    global $g4, $is_admin, $member;

    $sql = " select bg_id, bg_subject from $g4[banner_group_table] a ";
    if ($is_admin == "group") {
        $sql .= " left join $g4[member_table] b on (b.mb_id = a.bg_admin)
                  where b.mb_id = '$member[mb_id]' ";
    }
    $sql .= " order by a.bg_id ";

    $result = sql_query($sql);
    $str = "<select name='$name' $event>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $str .= "<option value='$row[bg_id]'";
        if ($row[bg_id] == $selected) $str .= " selected";
        $str .= ">$row[bg_subject] ($row[bg_id])</option>";
    }
    $str .= "</select>";
    return $str;
}
?>