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

$mb_name = $_POST['mb_name'];

$mb_name = convert_charset('UTF-8','CP949',$mb_name);

// ������ �ѱ�, ����, ���ڸ� ����
if (!check_string($mb_name, _G4_HANGUL_ + _G4_ALPHABETIC_)) {
    echo "110"; // �̸��� ������� �ѱ�, ������ �Է� �����մϴ�.
} else if (strlen($mb_name) < 2) {
    echo "120"; // 2���� �̻� �Է�
} else if (preg_match("/[\,]?{$mb_name}/i", $config[cf_prohibit_id])) {
    echo "140"; // ������ ������ ȸ�����̵�
} else {
    echo "000"; // ����
}
?>
