<?
include_once("_common.php");

$reg_mb_id = $_POST['reg_mb_id'];

// echo "�ѱ�"�� ������� �ʴ� ������ Ajax �� euc_kr ���� �ѱ��� ����� �ν����� ���ϱ� ����
// ���⿡�� �������� echo �Ͽ� Request �� ���� Javascript ���� �ѱ۷� �޼����� �����

if (preg_match("/[^0-9a-z_]+/i", $reg_mb_id)) {
    echo "110"; // ��ȿ���� ���� ȸ�����̵�
} else if (strlen($reg_mb_id) < 4) {
    echo "120"; // 4���� ���� ȸ�����̵�
} else {
    $row = sql_fetch(" select count(*) as cnt from $g4[member_table] where mb_id = '$reg_mb_id' ");
    if ($row[cnt]) {
        echo "130"; // �̹� �����ϴ� ȸ�����̵�
    } else {
        if (preg_match("/[\,]?{$reg_mb_id}/i", $config[cf_prohibit_id]))
            echo "140"; // ������ ������ ȸ�����̵�
        else
            echo "000"; // ����
    }
}
?>