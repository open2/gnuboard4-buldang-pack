<?php

// �ĵ������� �ƴ� �״����忡 ��ġ�� ���� 1�� �־��ֽø� �״����� �Ǵ� �ٸ� ���������� �۵��մϴ�.
$att['attendance_gnu'] = "1"; // ����) $att['attendance_gnu'] = "1";

if($att['attendance_gnu']){
	$g4['plugin']      = 'plugin';
	$g4['plugin_path'] = $g4['path'] . '/' . $g4['plugin'];
}

//�⼮üũ ����� ȯ�� ����
$g4['attendance'] = 'attendance';
$g4['attendance_path'] = $g4['plugin_path'] . '/' . $g4['attendance']; // �÷����� ��ġ���
$g4['attendance_img'] = 'img'; // �÷����� �̹���
$g4['attendance_css'] = 'css'; // �÷����� css
$g4['attendance_lib'] = 'lib'; // �÷����� ���̺귯��
$g4['attendance_img_path'] = $g4['attendance_path'] .'/'.$g4['attendance_img']; // �÷����� �̹��� ���
$g4['attendance_css_path'] = $g4['attendance_path'] .'/'.$g4['attendance_css']; // �÷����� css ���
$g4['attendance_lib_path'] = $g4['attendance_path'] .'/'.$g4['attendance_lib']; //  �÷����� ���̺귯�� ���


$g4['attendance_plugin_table'] = $g4['table_prefix'] . 'plugin_attendance'; // �⼮üũ ���̺�
$g4['attendance_successive_plugin_table'] = $g4['table_prefix'] . 'plugin_attendance_successive'; // �� ���� ���̺�

//��Ÿ ȯ�� ����
$att['attendance_level'] = '2'; // �⼮ üũ ���� ���� 2���� �̸��� �ϽǼ� �����ϴ�.
$att['attendance_rows'] = '20'; // �⼮ �ο� ǥ������
$att['attendance_number'] = '1'; // �Ϸ� �⼮ ���� Ƚ�� ( ���̵���� )
$att['attendance_ip'] = "2"; // ���� ������ �⼮���� 1 �Ǵ� ���Ƚ��
$att['attendance_start_time'] = '00:00:00'; // �⼮���� �ð� ��:��:��
$att['attendance_end_time'] = '23:59:00'; // �⼮���� �ð� ��:��:��

$att['attendance_win_start_point'] = "1"; // �¸� ���� ����Ʈ
$att['attendance_win_end_point'] = "2"; // �¸� ���� ����Ʈ

$att['attendance_tie_start_point'] = "1"; // ���º� ���� ����Ʈ
$att['attendance_tie_end_point'] = "2"; // ���º� ���� ����Ʈ

$att['attendance_loss_start_point'] = "1"; // �� ���� ����Ʈ
$att['attendance_loss_end_point'] = "2"; // �� ���� ����Ʈ

// ���� ����
$att['attendance_successive'] = "10,15,20"; // ����,���ӹ��º�,���� ���� ���� ���� 3,5,10  <== 3, 5, 10 ������ ����

$att['attendance_page_rows'] = "15"; // ������ ����
$att['attendance_pages'] = "10"; // ����¡ ��¼�
$att['attendance_honor_rows'] = "10"; // ���� ���� ��¼�

$att['char_min'] = "10"; // ���� �ּ� ���ڼ�
$att['char_max'] = "100"; // ���� �ִ� ���ڼ�

$att['attendance_memo'] = array(); // ���� �������� �⺻ ����Ʈ���� ���� ǥ��
$att['attendance_memo']['0'] = "�ȳ��ϼ��� ��ſ� �Ϸ� ��������!!";
$att['attendance_memo']['1'] = "������ ���̱��״�!!";
$att['attendance_memo']['2'] = "2CPU �ְ�!!";
$att['attendance_memo']['3'] = "���� �ֳ��� �ñ�����?";
$att['attendance_memo']['4'] = "����ǰ�� ����!";
$att['attendance_memo']['5'] = "�ʼ�ü�� �̸�Ƽ���� �Ⱦ��ڽ��ϴ�";
$att['attendance_memo']['9'] = "���ʹ� �Ϸ翡 1���� �鸮�ڽ��ϴ�";
$att['attendance_memo']['10'] = "���ڴ� �ָ��� ���ϴ�.";
$att['attendance_memo']['11'] = "�ָ��� ���� ���� ���� �ʽ��ϴ�.";
$att['attendance_memo']['12'] = "���ϸ��� ��ý";
$att['attendance_memo']['14'] = "����! �ູ�� �Ϸ�";
$att['attendance_memo']['15'] = "����ϰ� ���ڰ� �;��";


include_once($g4['attendance_path'] . '/lib/attendance.lib.php');
?>
<script>
/* ��Ʈ��Ʈ�� ���� ��ũ��Ʈ */
$(document).ready(function(){
    $(".tooltip-top").tooltip({trigger: 'hover click','placement': 'top'});
});
$(document).ready(function(){
    $(".tooltip-left").tooltip({trigger: 'hover click','placement': 'left'});
});
$(document).ready(function(){
    $(".tooltip-right").tooltip({trigger: 'hover click','placement': 'right'});
});
$(document).ready(function(){
    $(".tooltip-bottom").tooltip({trigger: 'hover click','placement': 'bottom'});
});
</script>
