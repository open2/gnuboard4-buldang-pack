<?
include_once("_common.php");

$reg_mb_email = $_POST['reg_mb_email'];

if (trim($reg_mb_email)=='') {
    echo "110"; // �Է��� �����ϴ�.

} else if (!preg_match("/^([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)$/", $reg_mb_email)) {
    echo "120"; // E-mail �ּ� ���Ŀ� ���� ����
} else {
    $sql = " select count(*) as cnt from $g4[member_table] where mb_id <> '$reg_mb_id' and mb_email = '$reg_mb_email' ";
    $row = sql_fetch($sql);
    if ($row[cnt]) {
        echo "130"; // �̹� �����ϴ� �̸���
    } else {
        //if (preg_match("/[\,]?{$reg_mb_email}\,/i", $config[cf_prohibit_id].","))
        if (preg_match("/[\,]?{$reg_mb_email}/i", $config[cf_prohibit_id]))
            echo "140"; // ������ ������ ȸ�����̵�
        else {
            // ���� ���� ������ �˻� (register_form skin.php�� java script�� ����)
            $prohibit_email = explode(",", trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config[cf_prohibit_email]))));
            $email_domain_arr = explode("@", strtolower($reg_mb_email));
            $email_domain = $email_domain_arr[1];
            if (in_array($email_domain, $prohibit_email))
                echo "150"; // ����� ������ ������
            else 
                echo "000"; // ����
        }
    }
}
?>
