<?
include_once("_common.php");

if (!function_exists('convert_charset')) {
    /*
    -----------------------------------------------------------
        Charset �� ��ȯ�ϴ� �Լ�
    -----------------------------------------------------------
    iconv �Լ��� ������ iconv �� ��ȯ�ϰ�
    ������ mb_convert_encoding �Լ��� ����Ѵ�.
    �Ѵ� ������ ����� �� ����.
    */
    function convert_charset($from_charset, $to_charset, $str) {

        if( function_exists('iconv') )
            return iconv($from_charset, $to_charset, $str);
        elseif( function_exists('mb_convert_encoding') )
            return mb_convert_encoding($str, $to_charset, $from_charset);
        else {
            include_once("$g4[bbs_path]/iconv.php");
            return iconv($from_charset, $to_charset, $str);
            //die("Not found 'iconv' or 'mbstring' library in server.");
        }
    }
}

$reg_mb_nick = $_POST['reg_mb_nick'];

if (strtolower($g4[charset]) == 'euc-kr') 
    $reg_mb_nick = convert_charset('UTF-8','CP949',$reg_mb_nick);

// ������ �ѱ�, ����, ���ڸ� ����
//if (!check_string($reg_mb_nick, _G4_HANGUL_ + _G4_ALPHABETIC_ + _G4_NUMERIC_)) {
if (preg_match('/[^0-9a-zA-Z\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]+/u', $reg_mb_nick)) {
    echo "110"; // ������ ������� �ѱ�, ����, ���ڸ� �Է� �����մϴ�.
} else if (strlen($reg_mb_nick) < 4) {
    echo "120"; // 4���� �̻� �Է�
} else if (preg_match("/[\,]?{$reg_mb_nick}/i", $config[cf_prohibit_id])) {
    echo "140"; // ������ ������ ȸ�����̵�
} else {
    // ȸ�� ���̺��� Ȯ��
    $row = sql_fetch(" select count(*) as cnt from $g4[member_table] where mb_nick = '$reg_mb_nick' ");
    $mb_count = $row[cnt];
    // �Ҵ��� - ȸ�� nick ���̺��� Ȯ�� (���� ��� �г����� �ٽ� �� �� �ְ�)
    $row = sql_fetch(" select count(*) as cnt from $g4[mb_nick_table] where mb_nick = '$reg_mb_nick' and mb_id <> '$member[mb_id]' ");
    $mb_count_pack = $row[cnt];
    if ($mb_count or $mb_count_pack) {
        echo "130"; // �̹� �����ϴ� ����
    } else {
        // �Ű�� ȸ���� �г��� ������ �� �� ���� ����
        $row1 = sql_fetch(" select count(*) as cnt from $g4[singo_table] where mb_id = '$member[mb_id]' ");
        if ($row1['cnt'] > 0)
            echo "150"; // �Ű� �־ �г��� ������ �� �� ����
        else
            echo "000"; // ����
    }
}
?>