<?
include_once("./_common.php");
include_once("$g4[path]/lib/mailer.lib.php");

// ���۷� üũ
referer_check();

if ($w == "") 
{
    // �ڵ���Ϲ��� �˻�
    if (!$member[mb_id] && $config[cf_use_norobot]) {
        include_once("$g4[path]/zmSpamFree/zmSpamFree.php");
        if ( !zsfCheck( $_POST['wr_key'], 'sms_admin' ) ) { alert ('���������ڵ尡 Ʋ�Ƚ��ϴ�.'); }    
    }

    $po = sql_fetch(" select * from $g4[poll_table] where po_id = '$po_id' ");
    if (!$po[po_id])
        alert("po_id ���� ����� �Ѿ���� �ʾҽ��ϴ�.");

    $tmp_row = sql_fetch(" select max(pc_id) as max_pc_id from $g4[poll_etc_table] ");
    $pc_id = $tmp_row[max_pc_id] + 1;

    $pc_idea = addslashes($pc_idea);

    if ($member)
        $pc_name = $member[mb_nick];
    $pc_name = strip_tags($pc_name);

    /*
    $sql = " insert into $g4[poll_etc_table]
                    ( pc_id, po_id, mb_id, pc_name, pc_idea, pc_datetime, pc_password )
             values ( '$pc_id', '$po_id', '$member[mb_id]', '$pc_name', '$pc_idea', '$g4[time_ymdhis]', '" . sql_password($pc_password) . "' ) ";
    sql_query($sql);
    */

    $stmt = $pdo_db->prepare("insert into $g4[poll_etc_table] ( pc_id, po_id, mb_id, pc_name, pc_idea, pc_datetime, pc_password ) 
                              values ( '$pc_id', '$po_id', :mb_id, :pc_name, :pc_idea, '$g4[time_ymdhis]', '" . sql_password($pc_password) . "' ) ");
    $stmt->bindParam(":mb_id", $member[mb_id]);
    $stmt->bindParam(":pc_name", $pc_name);
    $stmt->bindParam(":pc_idea", $pc_idea);
    $result = pdo_query($stmt);

    $pc_idea = stripslashes($pc_idea);

    $name = cut_str($pc_name, $config[cf_cut_name]);
    $mb_id = "";
    if ($member[mb_id])
        $mb_id = "($member[mb_id])";

    // ȯ�漳���� ��ǥ ��Ÿ�ǰ� �ۼ��� �ְ�����ڿ��� ���Ϲ߼� ��뿡 üũ�Ǿ� ���� ���
    if ($config[cf_email_po_super_admin])
    {
        $subject = $po[po_subject];
        $content = $pc_idea;

        ob_start();
        include_once ("./poll_etc_update_mail.php");
        $content = ob_get_contents();
        ob_end_clean();

        // �����ڿ��� ������ ����
        $admin = get_admin("super");
        mailer($name, "", $admin[mb_email], "�������� ��Ÿ�ǰ� ����", $content, 1);
    }
} 
else if ($w == "d" or $w == "p") 
{
    if ($member[mb_id] || $is_admin == 'super')
    {
        $sql = " delete from $g4[poll_etc_table] where pc_id = '$pc_id' ";
        if (!$is_admin)
            $sql .= " and mb_id = '$member[mb_id]' ";
        sql_query($sql);
    } else if (!$member[mb_id]) {
        $result = sql_fetch(" select pc_password from $g4[poll_etc_table] where pc_id = '$pc_id' and po_id = '$po_id' ");
        if (sql_password($wr_password) !== $result['pc_password'])
            alert("�н����尡 Ʋ���ϴ�.");
            
        $sql = " delete from $g4[poll_etc_table] where pc_id = '$pc_id' and po_id = '$po_id' and pc_password = '" . sql_password($wr_password) . "'";
    }
}

goto_url("./poll_result.php?po_id=$po_id&skin_dir=$skin_dir");
?>
