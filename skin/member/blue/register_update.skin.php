<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// �ڵ��� ��ȣ�� ������ȣ�� ���� �Ѿ� �Դٸ� 
$mb_hp_certify_datetime = "0000-00-00 00:00:00"; 
if ($mb_hp && $mb_hp_certify) { 
    // ������ȣ�� ���ٸ� 
    if (get_session("ss_hp_certify_number") == $mb_hp_certify) { 
        $mb_hp_certify_datetime = $g4['time_ymdhis']; // ����ð� 
    } 
    sql_query(" update $g4[member_table] set mb_hp_certify_datetime = '$mb_hp_certify_datetime' where mb_id = '$mb_id' "); 
} else if ($mb_hp_old && $mb_hp != $mb_hp_old) { 
    sql_query(" update $g4[member_table] set mb_hp_certify_datetime = '$mb_hp_certify_datetime' where mb_id = '$mb_id' "); 
}

// ��õ�� ���� ȸ�� ���Խ� member_join_table �����ϱ�
if ($w == "" && $g4[member_suggest_join]) 
{
    $sql = "update $g4[member_suggest_table] 
              set join_mb_id = '$mb_id', join_datetime = '$g4[time_ymdhis]', join_code = password('$join_code') 
              where mb_id = '$mb_recommend' and join_code = '$join_code' ";
    sql_query ($sql);
    echo $sql;
}
?>
