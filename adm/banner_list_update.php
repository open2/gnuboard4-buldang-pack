<?
$sub_menu = "300900";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "w");

check_token();

for ($i=0; $i<count($chk); $i++) 
{
    // ���� ��ȣ�� �ѱ�
    $k = $chk[$i];

    $bn_id = $_POST[bn_id][$k];
    $bn_subject = $_POST[bn_subject][$k];
    $bn_use = $_POST[bn_use][$k];
    $bn_url = $_POST[bn_url][$k];
    $bn_target = $_POST[bn_target][$k];
    $bn_order = $_POST[bn_order][$k];
    $bn_start_datetime = $_POST[bn_start_datetime][$k];
    $bn_end_datetime = $_POST[bn_end_datetime][$k];

    // ��¥�� ��� ������ ������ �־��ش�
    if ($bn_start_datetime == "0000-00-00 00:00:00" || $bn_end_datetime == "0000-00-00 00:00:00")
        $bn_start_datetime = $bn_end_datetime = $g4['time_ymdhis'];
    // ��¥�� ������ �ڿ� ��.��.�ʸ� �ٿ��ش�
    if (strlen(trim($bn_end_datetime)) == 10)
        $bn_end_datetime .= " 23:59:59";

    if ($is_admin != "super")
    {
        $bn = sql_fetch(" select * from $g4[banner_table] where bn_id = '$bn_id' ");
        $bg = sql_fetch(" select * from $g4[banner_group_table] where bg_id = '$bn[bg_id]' ");
        if ($member[mb_id] !== $bg[bg_admin])
            alert("�ְ������� �Ǵ� ��ʱ׷� �����ڰ� �ƴ� ��� ������ �Ұ��մϴ�.");
    }

    $sql = " update $g4[banner_table]
                set 
                    bn_subject          = '$bn_subject',
                    bn_use              = '$bn_use',
                    bn_target           = '$bn_target',
                    bn_url              = '$bn_url',
                    bn_order            = '$bn_order',
                    bn_start_datetime   = '$bn_start_datetime',
                    bn_end_datetime     = '$bn_end_datetime'
              where bn_id               = '$bn_id' ";
    sql_query($sql);
}

goto_url("./banner_list.php?$qstr");
?>